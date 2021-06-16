@extends('shop')

@section('content')

<style>
    @import "bourbon";

    body {
        background: #eee !important;
    }

    .wrapper {
        margin-top: 8rem;
        margin-bottom: 80px;
    }

    .form-signin {
        max-width: 380px;
        padding: 15px 35px 45px;
        margin: 0 auto;
        background-color: #fff;
        border: 1px solid rgba(0, 0, 0, 0.1);

        .form-signin-heading,
        .checkbox {
            margin-bottom: 30px;
        }

        .checkbox {
            font-weight: normal;
        }

        .form-control {
            position: relative;
            font-size: 16px;
            height: auto;
            padding: 10px;
        }

        input[type="text"] {
            margin-bottom: -1px;
            border-bottom-left-radius: 0;
            border-bottom-right-radius: 0;
        }

        input[type="password"] {
            margin-bottom: 20px;
            border-top-left-radius: 0;
            border-top-right-radius: 0;
        }
    }
</style>

<div class="wrapper">
    <form class="form-signin" method="POST" action="{{ route('shop.prices.update') }}">
        @csrf
        <h2 class="form-signin-heading">Shop Prices</h2>
        Preco Unidade Catálogo<input type="number" class="form-control" name="precouncatalogo" placeholder="{{ $prices->preco_un_catalogo }}€" />
        Preco Unidade Próprio<input type="number" class="form-control" name="precounproprio" placeholder="{{ $prices->preco_un_proprio }}€" />
        Preco Unidade Catálogo Desconto<input type="number" class="form-control" name="precouncatalogodesconto" placeholder="{{ $prices->preco_un_catalogo_desconto }}€" />
        Preco Unidade Próprio Desconto<input type="number" class="form-control" name="precounpropriodesconto" placeholder="{{ $prices->preco_un_proprio_desconto }}€" />
        Quantidade Desconto<input type="number" class="form-control" name="quantidadedesconto" placeholder="{{ $prices->quantidade_desconto }}" />
        <br>
        <button class="btn btn-lg btn-primary btn-block" type="submit">Save</button>
    </form>
</div>

<script>
    document.getElementsByClassName("form-control").addEventListener("change", function() {
        this.value = parseFloat(this.value).toFixed(2);
    });
</script>

@stop