<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TShirt extends Model
{
    use HasFactory;

    protected $table = 'tshirts';
    public $timestamps = false;

    public function estampa()
    {
        return $this->hasOne(Estampa::class,'id','estampa_id');
    }

    public function encomenda()
    {
        return $this->belongsTo(Encomenda::class);
    }
}
