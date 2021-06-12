@extends('shop')

@section('content')
<h1 style="margin-top:150px;text-align:center"><strong>Statistics</strong></h1>

<p>This month:</p>
<p>{{ count($encomendas) }} deliveries</p>

@stop