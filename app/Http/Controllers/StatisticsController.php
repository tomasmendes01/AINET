<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Encomenda;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class StatisticsController extends Controller
{
    public function getStatistics()
    {
        if (request()->data == null) {
            request()->data = date("Y");
        }
        $num_encomendas_por_mes = [
            '0' => 0,
            '1' => 0,
            '2' => 0,
            '3' => 0,
            '4' => 0,
            '5' => 0,
            '6' => 0,
            '7' => 0,
            '8' => 0,
            '9' => 0,
            '10' => 0,
            '11' => 0,
        ];

        $datas = [];

        $encomendas = Encomenda::with('tshirt')->get()->all();
        $dataAux = date("Y", strtotime(Encomenda::get()->first()->value('data')));
        array_push($datas, $dataAux);
        foreach ($encomendas as $encomenda) {
            if (date("Y", strtotime($encomenda->data)) != $dataAux) {
                $dataAux = date("Y", strtotime($encomenda->data));
                array_push($datas, $dataAux);
            }

            $ano = date("Y", strtotime($encomenda->data));
            $mes = date("m", strtotime($encomenda->data));
            if ($ano == request()->data) {
                $num_encomendas_por_mes[$mes - 1]++;
            }
        }

        //$testeCores = Encomenda::with('tshirt')->groupBy('cor_codigo')->get();
        $cores = DB::table('encomendas')
            ->join('tshirts', 'tshirts.encomenda_id', '=', 'encomendas.id')
            ->join('cores', 'tshirts.cor_codigo', '=', 'cores.codigo')
            ->select(DB::raw('count(*) as cor_count, tshirts.cor_codigo, cores.nome'))
            ->groupBy('tshirts.cor_codigo')
            ->get();

        $coresCounter = [];
        $coresNomes = [];
        foreach ($cores as $cor) {
            array_push($coresCounter, $cor->cor_count);
            array_push($coresNomes, $cor->nome);
        }

        $categorias = DB::table('encomendas')
            ->join('tshirts', 'tshirts.encomenda_id', '=', 'encomendas.id')
            ->join('estampas', 'tshirts.estampa_id', '=', 'estampas.id')
            ->join('categorias', 'estampas.categoria_id', '=', 'categorias.id')
            ->select(DB::raw('count(*) as cat_count, categorias.id, categorias.nome'))
            ->groupBy('categorias.id')
            ->get();

        $categoriasCounter = [];
        $categoriasNomes = [];
        foreach ($categorias as $categoria) {
            array_push($categoriasCounter, $categoria->cat_count);
            array_push($categoriasNomes, $categoria->nome);
        }

        //dd($categorias);
        return view('admin.statistics')->with(['categoriasCounter' => $categoriasCounter, 'categoriasNomes' => $categoriasNomes, 'categorias' => $categorias, 'coresNomes' => $coresNomes, 'coresCounter' => $coresCounter, 'cores' => $cores, 'encomendas' => $encomendas, 'num_encomendas_por_mes' => $num_encomendas_por_mes, 'datas' => $datas, 'ano' => request()->data]);
    }
}
