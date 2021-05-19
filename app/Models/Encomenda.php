<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Encomenda extends Model
{
    use HasFactory;

    protected $table = 'encomendas';

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'cliente_id');
    }
}
