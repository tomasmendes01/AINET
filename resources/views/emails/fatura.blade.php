<h1 style="margin:auto;text-align:center">Thank you for shopping in MagicShirts!</h1>
<p>

<h3><u>User Info</u></h3>
<p><strong>Name:</strong> {{ $user->name }}</p>
<p><strong>Email: </strong>{{ $user->email }}</p>
<p><strong>Order date: </strong>{{ $date }}</p>
<p><strong>NIF: </strong>{{ $user->cliente->nif }}</p>
<p><strong>Address: </strong>{{ $user->cliente->endereco }}</p>
<p><strong>Payment Method: </strong>{{ $user->cliente->tipo_pagamento }}</p>
<p><strong>Payment Reference: </strong>{{ $user->cliente->ref_pagamento }}</p>

<h3><u>Purchased Items</u></h3>
<div class="container">
    <div class="row">
        <table style="width:100%;margin:auto;margin-top:50px;text-align:center">
            <tr>
                <th>Product</th>
                <th>Size</th>
                <th>Quantity</th>
                <th>Price</th>
            </tr>
            @foreach($cart->items as $product)
            <tr>
                <td>{{ $product['item']->nome }}</td>
                <td>{{ $product['item']->size }}</td>
                <td>{{ $product['quantity'] }}</td>
                <td>{{ $product['price'] }}€</td>
            </tr>
            @endforeach
        </table>
    </div>
</div>
<br>
<h3 style="text-align:right;margin-bottom:-220px">Total price: {{ $cart->totalPrice }}€</h3>
</p>