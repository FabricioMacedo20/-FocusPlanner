<?php
// Rotas web principais

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\HabitController;
use App\Http\Controllers\GoalController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\ReadingController;
use App\Http\Controllers\RelatorioController;

// Debug login
Route::get('/debug-login', function () {
    $user = \App\Models\User::where('email', 'luizfabricio0811@icloud.com')->first();
    if ($user) {
        \Illuminate\Support\Facades\Auth::login($user);
        return redirect()->route('dashboard');
    }
    return 'Usuário não encontrado: ' . \App\Models\User::count() . ' usuários no banco';
});

// Redireciona para dashboard
Route::get('/', function () {
    return redirect()->route('dashboard');
});

// Testa middleware auth
Route::middleware('auth')->get('/test-auth', function () {
    return 'Autenticado: ' . auth()->user()->name ?? 'Nenhum usuário';
});

// Rotas protegidas
// Exigem login e redirecionam para /login
Route::middleware('auth')->group(function () {

    // Tarefas e planner
    Route::get('/dashboard', [TaskController::class, 'dashboard'])->name('dashboard');
    Route::get('/planner', [TaskController::class, 'planner'])->name('planner');
    Route::post('/task/store', [TaskController::class, 'store'])->name('task.store');
    Route::post('/task/update-status/{id}/{status}', [TaskController::class, 'updateStatus'])->name('task.update-status');
    Route::get('/task/complete/{id}', [TaskController::class, 'complete'])->name('task.complete');
    Route::get('/task/delete/{id}', [TaskController::class, 'delete'])->name('task.delete');

    // Hábitos e conclusão
    // Resource CRUD
    Route::resource('habits', HabitController::class)->except(['show']);
    // Marca hábito como concluído
    Route::get('habits/{habit}/complete', [HabitController::class, 'complete'])->name('habits.complete');

    // Metas e status
    // Resource CRUD
    Route::resource('goals', GoalController::class)->except(['show']);
    // Alterna status da meta
    Route::get('goals/{goal}/toggle-status', [GoalController::class, 'toggleStatus'])->name('goals.toggle-status');
    // Define meta principal
    Route::get('goals/{goal}/set-featured', [GoalController::class, 'setFeatured'])->name('goals.set-featured');

    // Cursos e detalhes
    // Resource CRUD
    Route::resource('courses', CourseController::class)->except(['show']);
    // Mostra detalhes do curso
    Route::get('courses/{course}', [CourseController::class, 'show'])->name('courses.show');

    // Leituras e conclusão
    // Resource CRUD
    Route::resource('readings', ReadingController::class)->except(['show']);
    // Marca leitura como concluída
    Route::get('readings/{reading}/complete', [ReadingController::class, 'complete'])->name('readings.complete');

    // Relatório de produtividade
    Route::get('/relatorio', [RelatorioController::class, 'index'])->name('relatorio');
    Route::post('/relatorio/days', [RelatorioController::class, 'saveMonthlyDay'])->name('relatorio.save-day');
    Route::post('/relatorio/reset', [RelatorioController::class, 'resetStatistics'])->name('relatorio.reset');

});

// Rotas de autenticação
// Definidas em auth.php
// Redireciona ao não autenticado
require __DIR__.'/auth.php';