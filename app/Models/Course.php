<?php
// Modelo de curso: Representa um curso que o usuário está acompanhando, com progresso em porcentagem
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// CURSO: Rastreia cursos com progresso em porcentagem (0-100%)
class Course extends Model
{
    // Colunas que podem ser PREENCHIDAS via create() ou update()
    protected $fillable = [
        'user_id',      // Relacionamento: qual usuario possui esse curso
        'name',         // Nome do curso (ex: "Curso de JavaScript")
        'progress',     // Progresso do curso em porcentagem (0-100)
        'content',      // Conteúdo do curso em formato de texto/anotações
    ];

    // Progresso eh simples: valor direto de 0 a 100
    // Barra visual na view calcula: width = progress%
}
