<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FormaPagamento extends Model
{
    use HasFactory;

    protected $table = 'formas_pagamentos';
    protected $primaryKey = 'id';

    protected $fillable = [
        'nome',
    ];

    public function contas()
    {
        return $this->hasMany(Conta::class);
    }
}
