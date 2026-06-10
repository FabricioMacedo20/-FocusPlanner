<?php
//  Controlador de tarefas: Gerenciar criação, edição, conclusão e exclusão de tarefas diárias
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\Habit;
use App\Models\Goal;
use App\Models\Course;
use App\Models\Reading;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class TaskController extends Controller
{

    public function __construct()
    {
        // 🔐 Middleware de autenticação: garante que APENAS usuários logados acessem este controller
        // Conexão: Rota auth.php → aqui → protege dashboard e planner
        $this->middleware('auth');
    }

    // 📊 DASHBOARD: Mostra resumo visual de tarefas e produtividade da semana
    // Exibido em: resources/views/dashboard.blade.php
    public function dashboard()
    {
        $userId = Auth::id();
        $today = Carbon::now()->format('Y-m-d');

        // Buscar tarefas do dia (tabela tasks)
        $tasksToday = Task::where('user_id', $userId)
            ->whereDate('date', $today)
            ->orderBy('created_at', 'desc')
            ->get();

        // Contar totais, concluídas e pendentes com base apenas em tasks
        $totalTasksToday = $tasksToday->count();
        $tasksCompletedToday = $tasksToday->where('status', true)->count();
        $tasksPendingToday = $tasksToday->where('status', false)->count();

        // Calcular produtividade
        $productivity = $totalTasksToday > 0 ? round(($tasksCompletedToday / $totalTasksToday) * 100) : 0;

        $habitsCompletedToday = Habit::where('user_id', $userId)
            ->whereNotNull('last_completed_at')
            ->whereDate('last_completed_at', $today)
            ->count();

        // Metas ativas no Dashboard devem seguir a mesma lógica do módulo Metas:
        // status = false (em andamento) e não apenas current_value < target_value.
        $activeGoals = Goal::where('user_id', $userId)
            ->where('status', false)
            ->count();

        $activeGoalTitle = Goal::where('user_id', $userId)
            ->where('status', false)
            ->orderBy('created_at')
            ->value('title');

        $activeReadingsCount = Reading::where('user_id', $userId)
            ->where('completed', false)
            ->count();

        $activeCoursesCount = Course::where('user_id', $userId)
            ->where('progress', '<', 100)
            ->count();

        $goalsCompletedToday = Goal::where('user_id', $userId)
            ->whereDate('created_at', $today)
            ->count();

        $readingsCompletedToday = Reading::where('user_id', $userId)
            ->whereDate('created_at', $today)
            ->count();

        $coursesCompletedToday = Course::where('user_id', $userId)
            ->whereDate('created_at', $today)
            ->count();

        return view('dashboard', compact(
            'tasksToday',
            'totalTasksToday',
            'tasksCompletedToday',
            'tasksPendingToday',
            'productivity',
            'habitsCompletedToday',
            'activeGoals',
            'activeGoalTitle',
            'activeReadingsCount',
            'activeCoursesCount',
            'goalsCompletedToday',
            'readingsCompletedToday',
            'coursesCompletedToday'
        ));
    }

    //  PLANNER: Tela de planejamento diário com lista de tarefas
    // Exibido em: resources/views/planner.blade.php
    // Diferença da Dashboard: permite CRIAR e DELETAR tarefas
    public function planner()
    {
        // 📋 Buscar tarefas pendentes do usuário no planner com paginação
        $tasksQuery = Task::where('user_id', Auth::id())
                    ->where('status', false)
                    ->orderBy('date');

        $tasks = $tasksQuery->paginate(5)->withQueryString();

        // Buscar tarefas concluídas hoje
        $today = Carbon::now()->format('Y-m-d');
        $completedTasks = Task::where('user_id', Auth::id())
                    ->where('status', true)
                    ->whereDate('date', $today)
                    ->orderBy('date')
                    ->get();

        return view('planner', compact('tasks', 'completedTasks'));
    }


    public function store(Request $request)
    {
        // Validar entrada para evitar dados inválidos
        $request->validate([
            'title' => ['required', 'string', 'min:3', 'max:255', 'not_regex:/^\s*$/'],
            'date' => ['required', 'date'],
            'priority' => ['required', 'in:baixa,media,alta'],
        ], [
            'title.required' => 'O título é obrigatório.',
            'title.min' => 'O título deve ter pelo menos 3 caracteres.',
            'title.max' => 'O título não pode ter mais de 255 caracteres.',
            'title.not_regex' => 'O título não pode ficar em branco.',
            'date.required' => 'Informe uma data válida.',
            'date.date' => 'Informe uma data válida.',
            'priority.required' => 'Selecione uma prioridade.',
            'priority.in' => 'Selecione uma prioridade válida.',
        ]);

        // Criar nova tarefa do dia no banco
        Task::create([
            'user_id' => Auth::id(),
            'title' => trim($request->title),
            'date' => Carbon::now()->format('Y-m-d'),
            'priority' => $request->priority,
            'status' => false // false = pendente, true = concluída
        ]);

        // Redirecionar para o planner após criar
        return redirect()->route('planner');
    }


    public function complete($id)
    {
        // Buscar tarefa pelo ID e pelo usuário logado
        $task = Task::where('user_id', Auth::id())->find($id);

        if ($task) {
            $task->status = true;
            $task->save();
        }

        // Redirecionar para o planner após concluir
        return redirect()->route('planner');
    }


    public function delete($id)
    {
        $task = Task::where('user_id', Auth::id())->find($id);

        if ($task) {
            $task->delete();
        }

        return redirect()->route('planner');

    }

    // 📊 KANBAN: Exibe tarefas em um quadro kanban com 3 colunas
    // Tarefas agrupadas por status: 0 = A Fazer, 2 = Em Andamento, 1 = Concluído
    // 🔄 ATUALIZAR STATUS: Altera o status da tarefa no kanban
    // POST /task/update-status/{id}/{status}
    // status: 0 = A Fazer, 2 = Em Andamento, 1 = Concluído
    public function updateStatus($id, $status)
    {
        $task = Task::where('user_id', Auth::id())->find($id);
        $statusValue = (int) $status;

        if ($task && in_array($statusValue, [0, 1, 2], true)) {
            $task->status = $statusValue;
            $task->save();

            return redirect()->back()->with('success', 'Tarefa movida com sucesso!');
        }

        // Se não encontrou, redirecionar com erro
        return redirect()->back()->with('error', 'Tarefa não encontrada!');
    }

}
