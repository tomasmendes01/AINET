<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Estampa extends Model
{
    use HasFactory;

    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }

    public function tshirt()
    {
        return $this->hasMany(TShirt::class);
    }

    public function high_low_tshirt()
    {
        return $this->hasMany(TShirt::class)
            ->where('tamanho', 'M')
            ->orderBy('preco_un', 'DESC');
    }

    public function low_high_tshirt()
    {
        return $this->hasMany(TShirt::class)
            ->where('tamanho', 'M')
            ->orderBy('preco_un', 'ASC');
    }
}
