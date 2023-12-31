<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoChave extends Model
{
    use HasFactory;

    protected $table = 'tipos_chaves';
    protected $primaryKey = 'id';

    protected $fillable = [
        'nome',
    ];

    public function contasBancarias()
    {
        return $this->hasMany(ContaBancaria::class);
    }
}
