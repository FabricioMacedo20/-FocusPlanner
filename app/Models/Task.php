<?php
// Modelo de tarefa: Rastreia tarefas diarias do usuario com status (concluida ou pendente)
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// TAREFA: Rastreia tarefas diarias do usuario com status (concluida ou pendente)
class Task extends Model
{
    // Colunas que podem ser PREENCHIDAS via create() ou update()
    protected $fillable = [
        'user_id',      // Relacionamento: qual usuario possui essa tarefa
        'title',        // Titulo da tarefa (ex: "Fazer compras")
        'description',  // Descricao opcional da tarefa
        'priority',     // Prioridade (baixa, media, alta)
        'status',       // Status: 0 = pendente, 1 = concluida
        'date',         // Data planejada da tarefa
    ];

    // Fluxo: Usuario cria no /planner -> clica "Concluir" -> status muda para 1
    // Dashboard exibe stats: total, concluidas, pendentes, produtividade%
}