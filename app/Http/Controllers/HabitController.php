<?php
//  Controlador de hábitos: Gerenciar criação, edição, exclusão e marcação de hábitos como concluídos
namespace App\Http\Controllers;

use App\Models\Habit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class HabitController extends Controller
{
    public function __construct()
    {
        //  Middleware de autenticação: obrigatório estar LOGADO para acessar hábitos
        // Conexão: Auth Middleware → esse controller → redireciona para login se não autenticado
        $this->middleware('auth');
    }

    public function index()
    {
        //  LISTAR: Mostra todos os hábitos do usuário logado com paginação
        // Exibido em: resources/views/habits/index.blade.php
        // Relacionamento: User (logado) → via Auth::id() → Habit::user_id
        $habitsQuery = Habit::where('user_id', Auth::id());
        $totalActiveHabits = (clone $habitsQuery)->count();
        $habits = $habitsQuery->paginate(5)->withQueryString();

        // Hábitos completados hoje
        $today = Carbon::now()->format('Y-m-d');
        $completedHabitsToday = Habit::where('user_id', Auth::id())
            ->whereDate('last_completed_at', $today)
            ->get();

        // Estatísticas para o resumo
        $completedCount = $completedHabitsToday->count();
        $completionRate = $totalActiveHabits > 0 ? round(($completedCount / $totalActiveHabits) * 100) : 0;

        // Mensagem motivacional baseada no desempenho
        if ($completedCount === 0) {
            $motivationalMessage = "Nenhum hábito concluído hoje. Comece agora para manter sua consistência.";
        } elseif ($completedCount === $totalActiveHabits) {
            $motivationalMessage = "Parabéns! Todos os hábitos planejados para hoje foram concluídos.";
        } else {
            $motivationalMessage = "Ótimo trabalho! Continue mantendo sua rotina.";
        }

        return view('habits.index', compact('habits', 'completedHabitsToday', 'totalActiveHabits', 'completedCount', 'completionRate', 'motivationalMessage'));
    }

    public function create()
    {
        return view('habits.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255', 'not_regex:/^\s*$/'],
            'description' => 'nullable|string',
        ], [
            'name.required' => 'O nome do hábito é obrigatório.',
            'name.max' => 'O nome do hábito não pode ter mais de 255 caracteres.',
            'name.not_regex' => 'O nome do hábito não pode ficar em branco.',
        ]);

        Habit::create([
            'user_id' => Auth::id(),
            'name' => trim($request->name),
            'description' => $request->description,
        ]);

        return redirect()->route('habits.index');
    }

    public function edit(Habit $habit)
    {
        //  SEGURANÇA: Vérifica se o hábito pertence AO USUÁRIO logado
        // Evita que um usuário edite hábito de outro (unauthorized access protection)
        // Conexão: Auth::id() vs habit.user_id
        if ($habit->user_id !== Auth::id()) {
            abort(403);
        }

        return view('habits.edit', compact('habit'));
    }

    public function update(Request $request, Habit $habit)
    {
        if ($habit->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'name' => ['required', 'string', 'max:255', 'not_regex:/^\s*$/'],
            'description' => 'nullable|string',
        ], [
            'name.required' => 'O nome do hábito é obrigatório.',
            'name.max' => 'O nome do hábito não pode ter mais de 255 caracteres.',
            'name.not_regex' => 'O nome do hábito não pode ficar em branco.',
        ]);

        $habit->update([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        return redirect()->route('habits.index');
    }

    public function destroy(Habit $habit)
    {
        if ($habit->user_id !== Auth::id()) {
            abort(403);
        }

        $habit->delete();

        return redirect()->route('habits.index');
    }

    public function complete(Habit $habit)
    {
        //  SEGURANÇA: Valida se hábito pertence ao usuário
        if ($habit->user_id !== Auth::id()) {
            abort(403);
        }

        //  LÓGICA DO STREAK (sequência de dias)
        // HOJE: data atual (sem hora)
        $today = Carbon::today();
        // ONTEM: used para verificar se hábito foi concluído no dia anterior
        $yesterday = $today->copy()->subDay();

        // Se já foi marcado HOJE, não aumenta o streak novamente
        // Evita que o usuário clique múltiplas vezes no mesmo dia
        if ($habit->last_completed_at && $habit->last_completed_at->isSameDay($today)) {
            return redirect()->route('habits.index');
        }

        //  STREAK LOGIC (lógica da sequência):
        //   Se foi concluído ONTEM: aumenta strerak em +1 🔝🔝
        //   Se foi concluído em outro dia: reinicia em 1 (perdeu a sequência)
        if ($habit->last_completed_at && $habit->last_completed_at->isSameDay($yesterday)) {
            // Mantém a sequência! Ontem foi concluído, então hoje incrementa
            $habit->streak = $habit->streak + 1;
        } else {
            // Sequência quebrada ou primeiro registro: comecça em 1
            $habit->streak = 1;
        }

        //  GRAVA os dados do hábito:
        //   - last_completed_at: data de hoje (marcado como concluído)
        //   - streak: sequência atualizada
        // Exibido em: resources/views/habits/index.blade.php (coluna "Streak")
        $habit->last_completed_at = $today;
        $habit->save();

        return redirect()->route('habits.index');
    }
}
