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
        $activeGoalsQuery = Goal::where('user_id', Auth::id())->where('status', false);
        $goals = $activeGoalsQuery->paginate(5)->withQueryString();
        $completedGoals = Goal::where('user_id', Auth::id())->where('status', true)->get();

        // Dados para o resumo
        $totalActiveGoals = (clone $activeGoalsQuery)->count();
        $totalCompletedGoals = $completedGoals->count();
        $totalGoals = $totalActiveGoals + $totalCompletedGoals;
        $completionRate = $totalGoals > 0 ? round(($totalCompletedGoals / $totalGoals) * 100) : 0;

        // Meta em destaque (buscar a meta marcada como is_featured = true)
        $featuredGoal = Goal::where('user_id', Auth::id())->where('is_featured', true)->where('status', false)->first();
        
        // Se não houver meta em destaque, usar a primeira ativa
        if (!$featuredGoal && $totalActiveGoals > 0) {
            $featuredGoal = Goal::where('user_id', Auth::id())->where('status', false)->first();
        }

        // Mensagem de contexto
        if ($totalActiveGoals === 0) {
            $contextMessage = "Todas as metas foram concluídas. Crie uma nova meta para continuar evoluindo.";
        } else {
            $contextMessage = "Você possui " . $totalActiveGoals . " meta" . ($totalActiveGoals > 1 ? "s" : "") . " em andamento. Continue avançando para alcançar seus objetivos.";
        }

        return view('goals.index', compact('goals', 'completedGoals', 'totalActiveGoals', 'totalCompletedGoals', 'completionRate', 'featuredGoal', 'contextMessage'));
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
            'title' => ['required', 'string', 'max:255', 'not_regex:/^\s*$/'],
            'description' => 'nullable|string',
            'target_value' => 'required|integer|min:1',
            'current_value' => 'nullable|integer|min:0',
        ], [
            'title.required' => 'O título da meta é obrigatório.',
            'title.max' => 'O título da meta não pode ter mais de 255 caracteres.',
            'title.not_regex' => 'O título da meta não pode ficar em branco.',
            'target_value.required' => 'O valor alvo é obrigatório.',
            'target_value.integer' => 'O valor alvo deve ser um número inteiro.',
            'target_value.min' => 'O valor alvo deve ser pelo menos 1.',
            'current_value.integer' => 'O progresso atual deve ser um número inteiro.',
            'current_value.min' => 'O progresso atual não pode ser negativo.',
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
            'title' => ['required', 'string', 'max:255', 'not_regex:/^\s*$/'],
            'description' => 'nullable|string',
        ], [
            'title.required' => 'O título da meta é obrigatório.',
            'title.max' => 'O título da meta não pode ter mais de 255 caracteres.',
            'title.not_regex' => 'O título da meta não pode ficar em branco.',
        ]);

        //  ATUALIZA: Salva somente os dados de edição da meta
        $goal->update([
            'title' => $request->title,
            'description' => $request->description,
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
        // Mantém o current_value como está (não altera automaticamente para 100%)
        $goal->update([
            'status' => !$goal->status,
            'is_featured' => false  // Remover destaque ao desativar
        ]);

        return redirect()->route('goals.index');
    }

    public function setFeatured(Goal $goal)
    {
        //  SEGURANÇA: Verifica se a meta pertence ao usuário logado
        if ($goal->user_id !== Auth::id()) {
            abort(403);
        }

        //  Verificar se a meta está ativa (status = false)
        if ($goal->status === true) {
            return redirect()->route('goals.index')->with('error', 'Apenas metas ativas podem ser definidas como principais.');
        }

        //  REMOVER destaque de todas as metas do usuário
        Goal::where('user_id', Auth::id())->update(['is_featured' => false]);

        //  DEFINIR esta meta como destaque
        $goal->update(['is_featured' => true]);

        return redirect()->route('goals.index');
    }
}
