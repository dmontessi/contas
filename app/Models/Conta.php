<?php

namespace App\Models;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Conta extends Model
{
    use HasFactory;

    protected $table = 'contas';
    protected $primaryKey = 'id';

    protected $fillable = [
        'user_id',
        'fornecedor_id',
        'devedor_id',
        'descricao',
        'cobranca',
        'vencimento',
        'valor',
        'data_pagamento',
        'valor_pago',
        'formapagamento_id',
        'contabancaria_pagamento_id',
        'comprovante',
        'recorrente'
    ];

    protected $casts = [
        'id' => 'integer',
        'user_id' => 'integer',
        'fornecedor_id' => 'integer',
        'devedor_id' => 'integer',
        'valor' => 'decimal:2',
        'valor_pago' => 'decimal:2',
        'recorrente' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function fornecedor()
    {
        return $this->belongsTo(Fornecedor::class);
    }

    public function devedor()
    {
        return $this->belongsTo(Devedor::class);
    }

    public function forma_pagamento()
    {
        return $this->belongsTo(FormaPagamento::class);
    }

    public function conta_pagamento()
    {
        return $this->belongsTo(ContaBancaria::class);
    }

    public static function boot() {
        parent::boot();
        
        static::creating(function ($model) {
            $model->user_id = $model->user_id ?? Auth::id();
        });
    }
}
