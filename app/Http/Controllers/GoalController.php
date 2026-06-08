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

        // Dados para o resumo
        $totalActiveGoals = $goals->count();
        $totalCompletedGoals = $completedGoals->count();
        $totalGoals = $totalActiveGoals + $totalCompletedGoals;
        $completionRate = $totalGoals > 0 ? round(($totalCompletedGoals / $totalGoals) * 100) : 0;

        // Meta em destaque (buscar a meta marcada como is_featured = true)
        $featuredGoal = Goal::where('user_id', Auth::id())->where('is_featured', true)->where('status', false)->first();
        
        // Se não houver meta em destaque, usar a primeira ativa
        if (!$featuredGoal && $goals->count() > 0) {
            $featuredGoal = $goals->first();
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
