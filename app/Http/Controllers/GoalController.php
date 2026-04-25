<?php
// Gerenciamento de metas: Permitir que os usuários criem, editem, visualizem e excluam metas, além de acompanhar o progresso em cada meta
namespace App\Http\Controllers;

use App\Models\Goal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GoalController extends Controller
{
    public function __construct()
    {
        //  Middleware de autenticação: obrigatório estar LOGADO para acessar metas
        // Conexão: Auth Middleware → esse controller → protege todas as ações
        $this->middleware('auth');
    }

    public function index()
    {
        //  LISTAR: Mostra todas as metas do usuário com progresso (%)
        // Exibido em: resources/views/goals/index.blade.php
        // Na view, o cálculo é: (current_value / target_value) * 100 = percentual
        $goals = Goal::where('user_id', Auth::id())->where('status', false)->get();
        $completedGoals = Goal::where('user_id', Auth::id())->where('status', true)->get();

        return view('goals.index', compact('goals', 'completedGoals'));
    }

    public function create()
    {
        return view('goals.create');
    }

    public function store(Request $request)
    {
        //  VALIDAÇÕES antes de criar meta:
        //   - title: obrigatório, string, máximo 255 caracteres
        //   - target_value: obrigatório, inteiro, mínimo 1
        //   - current_value: opcional, inteiro, mínimo 0
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'target_value' => 'required|integer|min:1',
            'current_value' => 'nullable|integer|min:0',
        ]);

        //  GRAVA: Nova meta com dados do usuário logado
        // Relacionamento: User (logado) → via Auth::id() → Goal::user_id
        Goal::create([
            'user_id' => Auth::id(),
            'title' => $request->title,
            'description' => $request->description,
            'target_value' => $request->target_value,
            'current_value' => $request->current_value ?? 0,  // Se vazio, comecça em 0
        ]);

        return redirect()->route('goals.index');
    }

    public function edit(Goal $goal)
    {
        // SEGURANÇA: Verifica se a meta pertence AO USUÁRIO logado
        // Laravel injeta automaticamente o model Goal pelo ID na rota
        // Mas verificamos se pertence ao usuário (unauthorized access protection)
        if ($goal->user_id !== Auth::id()) {
            abort(403);  // HTTP 403: Forbidden
        }

        return view('goals.edit', compact('goal'));
    }

    public function update(Request $request, Goal $goal)
    {
        //  SEGURANÇA: Mesma verificação de propriedade
        if ($goal->user_id !== Auth::id()) {
            abort(403);
        }

        //  VALIDAÇÕES de atualização
        // Note: current_value aqui é REQUIRED (obrigatório) para atualizar
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'target_value' => 'required|integer|min:1',
            'current_value' => 'required|integer|min:0',
        ]);

        //  ATUALIZA: Salva os novos dados da meta
        // Exemplo: usuário aumenta current_value aos poucos conforme progride
        $goal->update([
            'title' => $request->title,
            'description' => $request->description,
            'target_value' => $request->target_value,
            'current_value' => $request->current_value,
        ]);

        return redirect()->route('goals.index');
    }

    public function destroy(Goal $goal)
    {
        if ($goal->user_id !== Auth::id()) {
            abort(403);
        }

        $goal->delete();

        return redirect()->route('goals.index');
    }

    public function toggleStatus(Goal $goal)
    {
        //  SEGURANÇA: Verifica se a meta pertence ao usuário logado
        if ($goal->user_id !== Auth::id()) {
            abort(403);
        }

        //  ALTERNA: Se estava concluída (true), fica em andamento (false) e vice-versa
        $goal->update([
            'status' => !$goal->status
        ]);

        return redirect()->route('goals.index');
    }
}
