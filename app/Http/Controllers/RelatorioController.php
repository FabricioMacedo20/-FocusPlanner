<?php
//  Controlador de relatório: Gerar relatórios de desempenho semanal, mensal e anual com base nas atividades do usuário
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\Habit;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class RelatorioController extends Controller
{
    private $monthNames = [
        'janeiro', 'fevereiro', 'março', 'abril', 'maio', 'junho',
        'julho', 'agosto', 'setembro', 'outubro', 'novembro', 'dezembro'
    ];

    public function __construct()
    {
        // 🔐 Protege a rota: apenas usuários logados acessam
        $this->middleware('auth');
        
        // Definir locale para português brasileiro
        Carbon::setLocale('pt_BR');
    }

    public function index()
    {
        $userId = Auth::id();

        // ========== DESEMPENHO SEMANAL ==========
        
        // Calcular desempenho da semana atual (segunda a domingo)
        $weeklyPerformance = $this->calculateWeeklyPerformance($userId);

        // ========== DESEMPENHO MENSAL ==========
        
        // Calcular desempenho do mês atual (média das semanas)
        $monthlyPerformance = $this->calculateMonthlyPerformance($userId);

        // ========== DESEMPENHO ANUAL (TODOS OS MESES) ==========
        
        // Calcular desempenho de cada mês do ano
        $yearlyPerformance = $this->calculateYearlyPerformance($userId);

        // Enviar dados para a view
        return view('relatorio', [
            'weeklyPerformance' => $weeklyPerformance,
            'monthlyPerformance' => $monthlyPerformance,
            'yearlyPerformance' => $yearlyPerformance,
        ]);
    }

    /**
     * Calcula o desempenho da semana atual incluindo todas as atividades
     *
     * Fórmula ponderada:
     * desempenho (%) = média ponderada de (tarefas + hábitos + metas + cursos + leituras)
     * Pesos: Tarefas (3x) + Hábitos (2x) + Metas (2x) + Cursos (1x) + Leituras (1x)
     *
     * REALISMO:
     * - Limita o desempenho máximo a 90% (usuário nunca atinge 100%)
     * - Dados vêm do banco, não são fixos
     */
    private function calculateWeeklyPerformance($userId)
    {
        // Obter data de inicio da semana (segunda-feira)
        $startOfWeek = Carbon::now()->startOfWeek(Carbon::MONDAY);
        // Obter data de fim da semana (domingo)
        $endOfWeek = Carbon::now()->endOfWeek(Carbon::SUNDAY);

        // ===== TAREFAS =====
        $tasksThisWeek = Task::where('user_id', $userId)
                            ->whereBetween('date', [$startOfWeek->format('Y-m-d'), $endOfWeek->format('Y-m-d')])
                            ->get();
        $totalTasks = $tasksThisWeek->count();
        $completedTasks = $tasksThisWeek->where('status', 1)->count();
        $taskPerformance = $totalTasks > 0 ? ($completedTasks / $totalTasks) * 100 : 0;

        // ===== HÁBITOS =====
        $habitsCompleted = \App\Models\Habit::where('user_id', $userId)
            ->whereBetween('last_completed_at', [$startOfWeek, $endOfWeek])
            ->count();
        $totalHabits = \App\Models\Habit::where('user_id', $userId)->count();
        $habitPerformance = $totalHabits > 0 ? ($habitsCompleted / $totalHabits) * 100 : 0;

        // ===== METAS =====
        $goalsCompleted = \App\Models\Goal::where('user_id', $userId)
            ->where('status', true)
            ->whereBetween('updated_at', [$startOfWeek, $endOfWeek])
            ->count();
        $totalGoals = \App\Models\Goal::where('user_id', $userId)->count();
        $goalPerformance = $totalGoals > 0 ? ($goalsCompleted / $totalGoals) * 100 : 0;

        // ===== CURSOS =====
        $coursesAvgProgress = \App\Models\Course::where('user_id', $userId)
            ->whereBetween('updated_at', [$startOfWeek, $endOfWeek])
            ->avg('progress') ?? 0;
        $coursePerformance = $coursesAvgProgress;

        // ===== LEITURAS =====
        $readings = \App\Models\Reading::where('user_id', $userId)
            ->whereBetween('updated_at', [$startOfWeek, $endOfWeek])
            ->get();
        $totalReadingPages = $readings->sum('current_page');
        $totalReadingExpected = $readings->sum('total_pages');
        $readingPerformance = $totalReadingExpected > 0 ? ($totalReadingPages / $totalReadingExpected) * 100 : 0;

        // ===== CÁLCULO PONDERADO =====
        // Pesos: Tarefas (3x) + Hábitos (2x) + Metas (2x) + Cursos (1x) + Leituras (1x)
        $weights = [3, 2, 2, 1, 1];
        $performances = [$taskPerformance, $habitPerformance, $goalPerformance, $coursePerformance, $readingPerformance];

        $totalWeight = array_sum($weights);
        $weightedPerformance = 0;

        foreach ($performances as $i => $perf) {
            $weightedPerformance += $perf * $weights[$i];
        }

        $performance = $totalWeight > 0 ? $weightedPerformance / $totalWeight : 0;

        // REALISMO: Limitar máximo a 90%
        if ($performance > 90) {
            $performance = 90;
        }

        // Arredondar para número inteiro
        $performance = round($performance);

        // Determinar classificação de desempenho
        if ($performance >= 0 && $performance <= 40) {
            $classification = 'Baixo';
            $color = 'red'; // Vermelho
        } elseif ($performance > 40 && $performance <= 70) {
            $classification = 'Médio';
            $color = 'yellow'; // Amarelo
        } else {
            $classification = 'Alto';
            $color = 'green'; // Verde
        }

        // Retornar dados formatados incluindo estatísticas de todas as atividades
        return [
            'percentage' => $performance,
            'classification' => $classification,
            'color' => $color,
            'totalTasks' => $totalTasks,
            'completedTasks' => $completedTasks,
            'totalHabits' => $totalHabits,
            'completedHabits' => $habitsCompleted,
            'totalGoals' => $totalGoals,
            'completedGoals' => $goalsCompleted,
            'totalCourses' => \App\Models\Course::where('user_id', $userId)->count(),
            'totalReadings' => \App\Models\Reading::where('user_id', $userId)->where('completed', false)->count(),
            'startDate' => $startOfWeek->format('d/m/Y'),
            'endDate' => $endOfWeek->format('d/m/Y'),
        ];
    }

    /**
     * Calcula o desempenho do mês atual
     * 
     * Lógica:
     * - Dividir o mês em 4 semanas
     * - Calcular desempenho de cada semana
     * - Fazer média das 4 semanas
     * 
     * Exemplo:
     * Semana 1: 60%
     * Semana 2: 70%
     * Semana 3: 50%
     * Semana 4: 80%
     * 
     * Média = (60 + 70 + 50 + 80) / 4 = 65%
     */
    private function calculateMonthlyPerformance($userId)
    {
        // Obter primeiro dia do mês
        $startOfMonth = Carbon::now()->startOfMonth();
        // Obter último dia do mês
        $endOfMonth = Carbon::now()->endOfMonth();

        // Dividir o mês em 4 semanas e calcular desempenho de cada
        $weekPerformances = [];
        $weekDayActivities = [];

        // SEMANA 1: Dia 1 até fim do primeiro domingo
        $week1Start = $startOfMonth;
        $week1End = $startOfMonth->copy()->addDays(6); // Próximos 7 dias
        $week1 = $this->calculatePerformanceBetweenDates($userId, $week1Start, $week1End);
        $weekPerformances[] = $week1;
        $weekDayActivities[] = $this->calculateWeekDayActivities($userId, $week1Start, $week1End);

        // SEMANA 2: Próxenos 7 dias
        $week2Start = $week1End->copy()->addDay();
        $week2End = $week2Start->copy()->addDays(6);
        $week2 = $this->calculatePerformanceBetweenDates($userId, $week2Start, $week2End);
        $weekPerformances[] = $week2;
        $weekDayActivities[] = $this->calculateWeekDayActivities($userId, $week2Start, $week2End);

        // SEMANA 3: Próxenos 7 dias
        $week3Start = $week2End->copy()->addDay();
        $week3End = $week3Start->copy()->addDays(6);
        $week3 = $this->calculatePerformanceBetweenDates($userId, $week3Start, $week3End);
        $weekPerformances[] = $week3;
        $weekDayActivities[] = $this->calculateWeekDayActivities($userId, $week3Start, $week3End);

        // SEMANA 4: Restante do mês
        $week4Start = $week3End->copy()->addDay();
        $week4End = $endOfMonth;
        $week4 = $this->calculatePerformanceBetweenDates($userId, $week4Start, $week4End);
        $weekPerformances[] = $week4;
        $weekDayActivities[] = $this->calculateWeekDayActivities($userId, $week4Start, $week4End);

        // Calcular média das 4 semanas
        $totalPerformance = array_sum($weekPerformances);
        $performance = round($totalPerformance / count($weekPerformances));

        // Limitar máximo a 90% (REALISMO)
        if ($performance > 90) {
            $performance = 90;
        }

        // Determinar classificação
        if ($performance >= 0 && $performance <= 40) {
            $classification = 'Baixo';
            $color = 'red';
        } elseif ($performance > 40 && $performance <= 70) {
            $classification = 'Médio';
            $color = 'yellow';
        } else {
            $classification = 'Alto';
            $color = 'green';
        }

        return [
            'percentage' => $performance,
            'classification' => $classification,
            'color' => $color,
            'month' => $this->monthNames[Carbon::now()->month - 1],
            'weeklyBreakdown' => $weekPerformances,
            'weekDayActivities' => $weekDayActivities,
        ];
    }

    /**
     * Calcula o desempenho anual (todos os 12 meses)
     * 
     * Retorna:
     * - Nome do mês
     * - Desempenho (%)
     * - Se não houver dados, retorna 0%
     */
    private function calculateYearlyPerformance($userId)
    {
        $yearlyData = [];

        // Iterar pelos 12 meses do ano
        for ($month = 1; $month <= 12; $month++) {
            // Criar data para o primeiro dia do mês
            $startMonth = Carbon::create(now()->year, $month, 1)->startOfMonth();
            // Obter último dia do mês
            $endMonth = $startMonth->copy()->endOfMonth();

            // Calcular desempenho do mês
            $performance = $this->calculatePerformanceBetweenDates($userId, $startMonth, $endMonth);

            // Limitar máximo a 90%
            if ($performance > 90) {
                $performance = 90;
            }

            // Obter nome do mês em português
            $monthName = $this->monthNames[$month - 1];

            // Armazenar dados do mês
            $yearlyData[] = [
                'month' => $monthName,
                'monthNumber' => $month,
                'percentage' => $performance,
            ];
        }

        return $yearlyData;
    }

    /**
     * Calcula desempenho entre duas datas incluindo todas as atividades
     * 
     * Fórmula ponderada:
     * desempenho (%) = média ponderada de (tarefas + hábitos + metas + cursos + leituras)
     * Pesos: Tarefas (3x) + Hábitos (2x) + Metas (2x) + Cursos (1x) + Leituras (1x)
     * 
     * @param $userId ID do usuário
     * @param $startDate Data de início (Carbon)
     * @param $endDate Data de fim (Carbon)
     * @return int Percentual de desempenho (0-100)
     */
    private function calculatePerformanceBetweenDates($userId, $startDate, $endDate)
    {
        // ===== TAREFAS =====
        $tasks = Task::where('user_id', $userId)
                    ->whereBetween('date', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])
                    ->get();
        $totalTasks = $tasks->count();
        $completedTasks = $tasks->where('status', 1)->count();
        $taskPerformance = $totalTasks > 0 ? ($completedTasks / $totalTasks) * 100 : 0;

        // ===== HÁBITOS =====
        $habitsCompleted = \App\Models\Habit::where('user_id', $userId)
            ->whereBetween('last_completed_at', [$startDate, $endDate])
            ->count();
        $totalHabits = \App\Models\Habit::where('user_id', $userId)->count();
        $habitPerformance = $totalHabits > 0 ? ($habitsCompleted / $totalHabits) * 100 : 0;

        // ===== METAS =====
        $goalsCompleted = \App\Models\Goal::where('user_id', $userId)
            ->where('status', true)
            ->whereBetween('updated_at', [$startDate, $endDate])
            ->count();
        $totalGoals = \App\Models\Goal::where('user_id', $userId)->count();
        $goalPerformance = $totalGoals > 0 ? ($goalsCompleted / $totalGoals) * 100 : 0;

        // ===== CURSOS =====
        $coursesAvgProgress = \App\Models\Course::where('user_id', $userId)
            ->whereBetween('updated_at', [$startDate, $endDate])
            ->avg('progress') ?? 0;
        $coursePerformance = $coursesAvgProgress;

        // ===== LEITURAS =====
        $readings = \App\Models\Reading::where('user_id', $userId)
            ->whereBetween('updated_at', [$startDate, $endDate])
            ->get();
        $totalReadingPages = $readings->sum('current_page');
        $totalReadingExpected = $readings->sum('total_pages');
        $readingPerformance = $totalReadingExpected > 0 ? ($totalReadingPages / $totalReadingExpected) * 100 : 0;

        // ===== CÁLCULO PONDERADO =====
        // Pesos: Tarefas (3x) + Hábitos (2x) + Metas (2x) + Cursos (1x) + Leituras (1x)
        $weights = [3, 2, 2, 1, 1];
        $performances = [$taskPerformance, $habitPerformance, $goalPerformance, $coursePerformance, $readingPerformance];
        
        $totalWeight = array_sum($weights);
        $weightedPerformance = 0;
        
        foreach ($performances as $i => $perf) {
            $weightedPerformance += $perf * $weights[$i];
        }
        
        $performance = $totalWeight > 0 ? $weightedPerformance / $totalWeight : 0;

        return round($performance);
    }

    /**
     * Calcula quais dias da semana tiveram atividade (segunda a sábado)
     * Retorna array de 6 booleanos indicando se houve atividade em cada dia
     */
    private function calculateWeekDayActivities($userId, $startDate, $endDate)
    {
        $dayActivities = [];

        // Para cada dia da semana (segunda=1 até sábado=6)
        for ($dayOfWeek = 1; $dayOfWeek <= 6; $dayOfWeek++) {
            $currentDate = $startDate->copy();

            // Encontrar o dia específico da semana dentro do período
            while ($currentDate <= $endDate) {
                if ($currentDate->dayOfWeek == $dayOfWeek) {
                    break;
                }
                $currentDate->addDay();
            }

            // Se encontrou o dia dentro do período da semana
            if ($currentDate <= $endDate && $currentDate->dayOfWeek == $dayOfWeek) {
                $dateStr = $currentDate->format('Y-m-d');

                // Verificar se houve qualquer atividade neste dia
                $hasActivity = false;

                // Verificar tarefas
                if (Task::where('user_id', $userId)->where('date', $dateStr)->exists()) {
                    $hasActivity = true;
                }

                // Verificar hábitos completados
                if (!$hasActivity && \App\Models\Habit::where('user_id', $userId)
                    ->whereDate('last_completed_at', $dateStr)->exists()) {
                    $hasActivity = true;
                }

                // Verificar metas atualizadas
                if (!$hasActivity && \App\Models\Goal::where('user_id', $userId)
                    ->where('status', true)->whereDate('updated_at', $dateStr)->exists()) {
                    $hasActivity = true;
                }

                // Verificar cursos atualizados
                if (!$hasActivity && \App\Models\Course::where('user_id', $userId)
                    ->whereDate('updated_at', $dateStr)->exists()) {
                    $hasActivity = true;
                }

                // Verificar leituras atualizadas
                if (!$hasActivity && \App\Models\Reading::where('user_id', $userId)
                    ->whereDate('updated_at', $dateStr)->exists()) {
                    $hasActivity = true;
                }

                $dayActivities[] = $hasActivity;
            } else {
                // Dia não existe nesta semana (pode acontecer na semana 4)
                $dayActivities[] = false;
            }
        }

        return $dayActivities;
    }
}
