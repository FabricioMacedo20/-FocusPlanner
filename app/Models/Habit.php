<?php
// Modelo de habito: Representa um habito diario do usuário, com sequencia (streak) e data do ultimo dia concluido
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// HABITO: Rastreia habitos diarios com sequencia (streak)
class Habit extends Model
{
    // Colunas que podem ser PREENCHIDAS via create() ou update()
    protected $fillable = [
        'user_id',              // Relacionamento: qual usuario possui esse habito
        'name',                 // Nome do habito (ex: "Ler 15 minutos")
        'description',          // Descricao opcional do habito
        'streak',               // Sequencia: quantos dias consecutivos concluiu
        'last_completed_at',    // Data do ultimo dia que foi marcado como concluido
    ];

    protected $casts = [
        'streak' => 'integer',
        'last_completed_at' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // CASTS: Converte colunas do banco para tipos PHP automaticamente
    // last_completed_at eh armazenado como string no BD, mas aqui vira objeto Carbon
    // Isso permite usar operacoes de data como: $habit->last_completed_at->format('d/m/Y')
}
