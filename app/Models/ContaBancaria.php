<?php

namespace App\Models;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContaBancaria extends Model
{
    use HasFactory;

    protected $table = 'contas_bancarias';
    protected $primaryKey = 'id';

    protected $fillable = [
        'user_id',
        'banco_id',
        'devedor_id',
        'agencia',
        'conta',
        'tipochave_id',
        'chave_pix',
        'ativa',
    ];

    protected $casts = [
        'ativa' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function banco()
    {
        return $this->belongsTo(Banco::class);
    }

    public function devedor()
    {
        return $this->belongsTo(Devedor::class);
    }

    public function tipoChave()
    {
        return $this->belongsTo(TipoChave::class, 'tipochave_id');
    }

    public static function boot() {
        parent::boot();
        
        static::creating(function ($model) {
            $model->user_id = $model->user_id ?? Auth::id();
        });
    }
}
