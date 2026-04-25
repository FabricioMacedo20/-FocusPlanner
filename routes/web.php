<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\HabitController;
use App\Http\Controllers\GoalController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\ReadingController;
use App\Http\Controllers\RelatorioController;
use App\Http\Controllers\NoteController;

// ROTA DE DEBUG
Route::get('/debug-login', function () {
    $user = \App\Models\User::where('email', 'luizfabricio0811@icloud.com')->first();
    if ($user) {
        \Illuminate\Support\Facades\Auth::login($user);
        return redirect()->route('dashboard');
    }
    return 'Usuário não encontrado: ' . \App\Models\User::count() . ' usuários no banco';
});

// ROTA PUBLICA: Redireciona para dashboard
Route::get('/', function () {
    return redirect()->route('dashboard');
});

// ROTA DE TESTE PARA VERIFICAR MIDDLEWARE
Route::middleware('auth')->get('/test-auth', function () {
    return 'Autenticado: ' . auth()->user()->name ?? 'Nenhum usuário';
});

// GRUPO DE ROTAS PROTEGIDAS POR AUTENTICACAO
// Todas as rotas abaixo exigem que o usuario esteja LOGADO
// Se não logado, redireciona para /login (definido em auth.php)
Route::middleware('auth')->group(function () {

    // ==================== TAREFAS (Planejamento Diario) ====================
    Route::get('/dashboard', [TaskController::class, 'dashboard'])->name('dashboard');
    Route::get('/planner', [TaskController::class, 'planner'])->name('planner');
    Route::get('/atividades-do-dia', [TaskController::class, 'atividadesDoDia'])->name('atividades-do-dia');
    Route::post('/task/store', [TaskController::class, 'store'])->name('task.store');
    Route::post('/task/update-status/{id}/{status}', [TaskController::class, 'updateStatus'])->name('task.update-status');
    Route::get('/task/complete/{id}', [TaskController::class, 'complete'])->name('task.complete');
    Route::get('/task/delete/{id}', [TaskController::class, 'delete'])->name('task.delete');

    // ==================== HABITOS (Sequencia Diaria) ====================
    // Resource = cria automaticamente: index, create, store, edit, update, destroy
    Route::resource('habits', HabitController::class)->except(['show']);
    // Rota customizada para marcar habito como concluido (incrementa streak)
    Route::get('habits/{habit}/complete', [HabitController::class, 'complete'])->name('habits.complete');

    // ==================== METAS (Progresso Numerico) ====================
    // Resource = cria automaticamente CRUD completo sem 'show'
    Route::resource('goals', GoalController::class)->except(['show']);
    // Rota customizada para alternar status da meta (concluída/em andamento)
    Route::get('goals/{goal}/toggle-status', [GoalController::class, 'toggleStatus'])->name('goals.toggle-status');

    // ==================== CURSOS (Progresso em %) ====================
    // Resource = cria automaticamente CRUD completo sem 'show'
    Route::resource('courses', CourseController::class)->except(['show']);
    // Rota customizada para mostrar detalhes do curso
    Route::get('courses/{course}', [CourseController::class, 'show'])->name('courses.show');

    // ==================== LEITURA (Paginas Lidas) ====================
    // Resource = cria automaticamente CRUD completo sem 'show'
    Route::resource('readings', ReadingController::class)->except(['show']);
    // Rota customizada para marcar leitura como concluída
    Route::get('readings/{reading}/complete', [ReadingController::class, 'complete'])->name('readings.complete');

    // ==================== ATIVIDADES DO DIA (Notas) ====================
    // Resource de notas para o menu 'Atividades do dia'
    Route::resource('notes', NoteController::class)->except(['show', 'create', 'edit']);
    Route::post('notes/{note}/complete', [NoteController::class, 'complete'])->name('notes.complete');

    // ==================== ATIVIDADES DO DIA (Kanban de Tarefas) ====================
    // Rotas para quadro kanban com 3 colunas (A Fazer, Em Andamento, Concluído)
    // Usar /atividades-do-dia em vez das rotas antigas de NoteController

    // ==================== RELATÓRIO (Análise de Produtividade) ====================
    // Exibe desempenho semanal, mensal e anual
    Route::get('/relatorio', [RelatorioController::class, 'index'])->name('relatorio');

});

// ROTAS DE AUTENTICACAO: Login, Register, Logout, etc
// Definidas em auth.php (Laravel Breeze)
// Cliente automaticamente redireciona para login quando tenta acessar grupo 'auth'
require __DIR__.'/auth.php';