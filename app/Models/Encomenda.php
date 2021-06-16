<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Encomenda extends Model
{
    use HasFactory;

    protected $table = 'encomendas';

    protected $fillable = [
        'estado',
        'cliente_id',
        'data',
        'preco_total',
        'nif',
        'endereco',
        'ref_pagamento'
    ];

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'cliente_id');
    }

    public function cliente()
    {
        return $this->hasOne(Cliente::class, 'id', 'cliente_id');
    }

    public function tshirt()
    {
        return $this->hasMany(TShirt::class, 'encomenda_id', 'id');
    }
}
