<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pessoa extends Model
{
    protected $table = 'pessoas';

    protected $fillable = [
        'nome',
        'cpf',
        'telefone',
        'email',
        'idade',
        'estado',
        'cidade',
        'bairro',
        'rua',
        'numero',
    ];
}