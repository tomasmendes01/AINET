<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TShirt extends Model
{
    use HasFactory;

    protected $table = 'tshirts';
    public $timestamps = false;

    protected $fillable = [
        'encomenda_id',
        'estampa_id',
        'cor_codigo',
        'tamanho',
        'quantidade',
        'preco_un',
        'subtotal'
    ];

    public function estampa()
    {
        return $this->hasOne(Estampa::class,'id','estampa_id');
    }

    public function encomenda()
    {
        return $this->belongsTo(Encomenda::class);
    }
}
