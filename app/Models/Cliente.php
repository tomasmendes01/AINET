<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cliente extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'id',
        'nif',
        'endereco',
        'tipo_pagamento',
        'ref_pagamento'
    ];

    public function user(){
        //return $this->hasOne(User::class,'id','id');
        return $this->belongsTo(User::class,'id','id');
    }
}
