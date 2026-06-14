<?php
// Modelo de leitura: Rastreia livros que o usuário está lendo, com progresso em páginas e status de conclusão
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// LEITURA: Rastreia livros com progresso de paginas lidas
class Reading extends Model
{
    // Colunas que podem ser PREENCHIDAS via create() ou update()
    protected $fillable = [
        'user_id',          // Relacionamento: qual usuario possui esse livro
        'book_title',       // Titulo do livro (ex: "O Poder do Habito")
        'total_pages',      // Total de paginas do livro (ex: 400)
        'current_page',     // Paginas lidas ate agora (ex: 125)
        'completed',        // Se o livro foi concluído (true/false)
    ];

    protected $casts = [
        'total_pages' => 'integer',
        'current_page' => 'integer',
        'completed' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Progresso calculado na VIEW como: (current_page / total_pages) * 100
    // Exemplo: se total = 400 e current = 100, entao progresso = 25%
}
