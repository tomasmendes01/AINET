<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Models\Preco;

class Estampa extends Model
{
    use HasFactory;

    protected $table = 'estampas';

    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }

    public function autor()
    {
        return $this->belongsTo(User::class, 'cliente_id', 'id');
    }

    public function encomenda()
    {
        return $this->belongsToMany(Estampa::class);
    }

    public function tshirt()
    {
        return $this->belongsToMany(TShirt::class);
    }
}
