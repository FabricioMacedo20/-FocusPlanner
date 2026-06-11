<?php
// Modelo de nota: Representa uma nota ou lembrete do usuário, com marcação de importância e conclusão
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

// NOTA: Bloco de notas para atividades do dia
class Note extends Model
{
    // Colunas que podem ser preenchidas via create() ou update()
    protected $fillable = [
        'user_id',      // Relacionamento: qual usuário possui essa nota
        'title',        // Título da nota
        'content',      // Conteúdo da nota
        'date',         // Data da nota (para atividades do dia)
        'important',   // Marca se a nota está priorizada
        'completed',   // Marca se a nota foi concluída
        'status',      // Status da nota: todo, in_progress, done
    ];

    protected $casts = [
        'important' => 'boolean',
        'completed' => 'boolean',
    ];

    // Relacionamento com User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
