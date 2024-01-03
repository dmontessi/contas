<?php

namespace App\Models;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Devedor extends Model
{
    use HasFactory;

    protected $table = 'devedores';
    protected $primaryKey = 'id';

    protected $fillable = [
        'user_id',
        'nome',
        'apelido',
        'documento',
        'cor',
        'ativo',
    ];

    protected $casts = [
        'id' => 'integer',
        'user_id' => 'integer',
        'ativo' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function contas()
    {
        return $this->hasMany(Conta::class, 'devedor_id');
    }

    public static function boot() {
        parent::boot();
        
        static::creating(function ($model) {
            $model->user_id = $model->user_id ?? Auth::id();
        });
    }
}
