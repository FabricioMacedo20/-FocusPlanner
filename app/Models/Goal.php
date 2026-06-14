<?php
// Modelo de meta: Representa uma meta que o usuário deseja alcançar, com progresso em porcentagem
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// META: Rastreia metas com progresso numerico (0 a 100%)
class Goal extends Model
{
    // Colunas que podem ser PREENCHIDAS via create() ou update()
    protected $fillable = [
        'user_id',          // Relacionamento: qual usuario possui essa meta
        'title',            // Titulo da meta (ex: "Aprender Laravel")
        'description',      // Descricao opcional da meta
        'target_value',     // Valor alvo (ex: 100 para 100%)
        'current_value',    // Valor atual (ex: 50, ja atingiu 50%)
        'status',           // Status da meta (true = concluída, false = em andamento)
        'is_featured',      // Meta principal (true = principal, false = não)
    ];

    protected $casts = [
        'target_value' => 'integer',
        'current_value' => 'integer',
        'status' => 'boolean',
        'is_featured' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Progresso calculado na VIEW como: (current_value / target_value) * 100
}
