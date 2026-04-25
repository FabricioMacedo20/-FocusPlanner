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

        $tasksToday = Task::where('user_id', $userId)
            ->where('date', $today)
            ->orderBy('created_at', 'desc')
            ->get();

        $total = $tasksToday->count();
        $completed = $tasksToday->where('status', 1)->count();
        $pending = $tasksToday->where('status', 0)->count();
        $rate = $total > 0 ? round(($completed / $total) * 100) : 0;

        $habitsCompletedToday = Habit::where('user_id', $userId)
            ->whereDate('created_at', $today)
            ->count();

        $activeGoals = Goal::where('user_id', $userId)
            ->whereRaw('current_value < target_value')
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
            'total',
            'completed',
            'pending',
            'rate',
            'habitsCompletedToday',
            'activeGoals',
            'goalsCompletedToday',
            'readingsCompletedToday',
            'coursesCompletedToday'
        ));
    }

    // 📌 ATIVIDADES DO DIA: Exibe tarefas em um quadro kanban com 3 colunas
    // Tarefas agrupadas por status: 0 = A Fazer, 2 = Em Andamento, 1 = Concluído
    // Exibido em: resources/views/atividades.blade.php
    public function atividadesDoDia()
    {
        $userId = Auth::id();
        $today = Carbon::now()->format('Y-m-d');

        // 📋 Buscar todas as tarefas do usuário do dia em ordem de criação
        $tasks = Task::where('user_id', $userId)
                    ->where('date', $today)
                    ->orderBy('created_at', 'desc')
                    ->get();

        // Retornar tarefas para a view kanban
        return view('atividades', compact('tasks'));
    }


    //  PLANNER: Tela de planejamento diário com lista de tarefas
    // Exibido em: resources/views/planner.blade.php
    // Diferença da Dashboard: permite CRIAR e DELETAR tarefas
    public function planner()
    {
        // 📋 Busca tarefas pendentes do usuário, ORDENADAS por data
        // Relacionamento: User (logado) → User::id → Task::user_id
        $tasks = Task::where('user_id', Auth::id())
                    ->where('status', false)
                    ->orderBy('date')
                    ->get();

        // Tarefas concluídas hoje
        $today = Carbon::now()->format('Y-m-d');
        $completedTasks = Task::where('user_id', Auth::id())
                    ->where('status', true)
                    ->where('date', $today)
                    ->orderBy('date')
                    ->get();

        return view('planner', compact('tasks', 'completedTasks'));

    }


    public function store(Request $request)
    {

        Task::create([

            'user_id' => Auth::id(),

            'title' => $request->title,

            'date' => $request->date,

            'priority' => $request->priority,

            'status' => false

        ]);

        return redirect()->route('planner');

    }


    public function complete($id)
    {

        $task = Task::find($id);

        if($task && $task->user_id == Auth::id())
        {

            $task->status = true;

            $task->save();

        }

        return redirect()->route('dashboard');

    }


    public function delete($id)
    {

        $task = Task::find($id);

        if($task && $task->user_id == Auth::id())
        {

            $task->delete();

        }

        return redirect()->route('dashboard');

    }

    // 📊 KANBAN: Exibe tarefas em um quadro kanban com 3 colunas
    // Tarefas agrupadas por status: 0 = A Fazer, 2 = Em Andamento, 1 = Concluído
    // 🔄 ATUALIZAR STATUS: Altera o status da tarefa no kanban
    // POST /task/update-status/{id}/{status}
    // status: 0 = A Fazer, 2 = Em Andamento, 1 = Concluído
    public function updateStatus($id, $status)
    {
        // Buscar a tarefa pelo ID
        $task = Task::find($id);

        // Verificar se a tarefa existe e pertence ao usuário logado
        if($task && $task->user_id == Auth::id())
        {
            // Atualizar o status da tarefa
            $task->status = (int) $status;
            $task->save();

            // Retornar sucesso com mensagem
            return redirect()->back()->with('success', 'Tarefa movida com sucesso!');
        }

        // Se não encontrou, redirecionar com erro
        return redirect()->back()->with('error', 'Tarefa não encontrada!');
    }

}
