<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chamado extends Model
{
    use HasFactory;

    protected $fillable = [
        'titulo',
        'descricao',
        'prioridade',
        'status',
        'responsavel_id',
        'setor',
        'escalonado',
        'nome_solicitante',
        'matricula_solicitante',
    ];
    public function responsavel()
    {
        return $this->belongsTo(User::class, 'responsavel_id');
    }
}