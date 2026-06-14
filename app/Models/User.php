<?php
// Modelo de usuário: Representa um usuário do sistema, com autenticação e verificação de email
namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    // Atributos que podem ser preenchidos em massa
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    // Atributos ocultos na serialização
    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Conversão de tipos de atributos
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // Relacionamento: tarefas do usuário
    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    // Relacionamento: hábitos do usuário
    public function habits()
    {
        return $this->hasMany(Habit::class);
    }

    // Relacionamento: metas do usuário
    public function goals()
    {
        return $this->hasMany(Goal::class);
    }

    // Relacionamento: cursos do usuário
    public function courses()
    {
        return $this->hasMany(Course::class);
    }

    // Relacionamento: leituras do usuário
    public function readings()
    {
        return $this->hasMany(Reading::class);
    }

    // Relacionamento: relatórios mensais do usuário
    public function monthlyReports()
    {
        return $this->hasMany(MonthlyReport::class);
    }
}
