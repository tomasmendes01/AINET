<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Encomenda;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use DateTime;

class EncomendasController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    function index()
    {
        if (Auth::user()->tipo != 'F' && Auth::user()->tipo != 'A') {
            return back();
        }

        if (request()->id != null) {
            $encomenda = Encomenda::with('user', 'tshirt.estampa')
                ->where('cliente_id', request()->client_id)
                ->findOrFail(request()->id);

            return view('management.encomenda', ['encomenda' => $encomenda]);
        }

        if (request()->orderBy == "price_low_high") {
            $encomendas = Encomenda::with('user')
                ->where('estado', '=', 'pendente')
                ->orWhere('estado', '=', 'paga')
                ->orderBy('preco_total', 'ASC')
                ->paginate(12);
        } elseif (request()->orderBy == "price_high_low") {
            $encomendas = Encomenda::with('user')
                ->where('estado', '=', 'pendente')
                ->orWhere('estado', '=', 'paga')
                ->orderBy('preco_total', 'DESC')
                ->paginate(12);
        } elseif (request()->orderBy == "id_ascendente") {
            $encomendas = Encomenda::with('user')
                ->where('estado', '=', 'pendente')
                ->orWhere('estado', '=', 'paga')
                ->orderBy('id', 'ASC')
                ->paginate(12);
        } elseif (request()->orderBy == "id_descendente") {
            $encomendas = Encomenda::with('user')
                ->where('estado', '=', 'pendente')
                ->orWhere('estado', '=', 'paga')
                ->orderBy('id', 'DESC')
                ->paginate(12);
        } elseif (request()->orderBy == "data_ascendente") {
            $encomendas = Encomenda::with('user')
                ->where('estado', '=', 'pendente')
                ->orWhere('estado', '=', 'paga')
                ->orderBy('data', 'ASC')
                ->paginate(12);
        } elseif (request()->orderBy == "data_descendente") {
            $encomendas = Encomenda::with('user')
                ->where('estado', '=', 'pendente')
                ->orWhere('estado', '=', 'paga')
                ->orderBy('data', 'DESC')
                ->paginate(12);
        } else {
            $encomendas = Encomenda::with('user')
                ->where('estado', '=', 'pendente')
                ->orWhere('estado', '=', 'paga')
                ->paginate(12);
        }

        return view('management.encomendas')->with(['encomendas' => $encomendas]);
    }

    function cancelOrder(Request $request)
    {
        $orderID = $request->orderID;
        $encomenda = Encomenda::where('id', $orderID)->first();

        $encomendas = Encomenda::with('user')
            ->where('estado', '=', 'pendente')
            ->orWhere('estado', '=', 'paga')
            ->paginate(12);

        try {
            DB::beginTransaction();
            $encomenda->estado = 'anulada';
            $encomenda->save();

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect('/encomendas')->with(['encomendas' => $encomendas, 'error' => $e->getMessage()]);
        }

        /* Enviar email a avisar que a encomenda foi cancelada */
        $user = User::findOrFail($encomenda->cliente_id);
        $date = new DateTime();
        $resultDate = $date->format('Y-m-d H:i:s');

        Mail::send('emails.order_cancelation', ['user' => $user, 'date' => $resultDate], function ($m) use ($user) {
            $m->from('hello@app.com', 'MagicShirts');
            $m->to($user->email, $user->name)->subject('Order Canceled');
        });

        return redirect('/encomendas')->with(['encomendas' => $encomendas, 'success' => 'Order canceled!']);
    }

    function prepareOrder(Request $request)
    {
        //dd($request->orderID);
        $orderID = $request->orderID;
        $encomenda = Encomenda::where('id', $orderID)->first();

        $encomendas = Encomenda::with('user')
            ->where('estado', '=', 'pendente')
            ->orWhere('estado', '=', 'paga')
            ->paginate(12);

        try {
            DB::beginTransaction();
            $encomenda->estado = 'fechada';
            $encomenda->save();

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect('/encomendas')->with(['encomendas' => $encomendas, 'error' => $e->getMessage()]);
        }

        /* Enviar email a avisar que a encomenda foi cancelada */
        $user = User::findOrFail($encomenda->cliente_id);

        Mail::send('emails.order_sent', ['user' => $user], function ($m) use ($user) {
            $m->from('hello@app.com', 'MagicShirts');
            $m->to($user->email, $user->name)->subject('Order Delivered');
        });

        return redirect('/encomendas')->with(['encomendas' => $encomendas, 'success' => 'Order delivered!']);
    }
}
