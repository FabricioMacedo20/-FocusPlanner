<?php

namespace App\Models;

// Relatório mensal com registro de atividades do usuário
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MonthlyReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'year',
        'month',
        'days_marked',
    ];

    protected $casts = [
        'days_marked' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
