@extends('shop')

@section('css')
<link href="/css/custom_stamp.css" rel="stylesheet" />
@stop

@section('content')

@if ($message = Session::get('error'))
<div class="alert alert-danger alert-block" style="text-align:center;margin-left:50px;margin-top:8rem;margin-bottom:-10rem">
    <button type="button" class="close" data-dismiss="alert">×</button>
    <strong>{{ $message }}</strong>
</div>
@endif

@if (count($errors) > 0)
<div class="alert alert-danger" style="text-align:center;margin-left:50px;margin-top:8rem;margin-bottom:-10rem">
    <ul>
        @foreach($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

@if(Session::get('success'))
<div class="alert alert-success" style="text-align:center;margin-left:50px;margin-top:8rem;margin-bottom:-10rem">
    <button type="button" class="close" data-dismiss="alert">×</button>
    <strong>{{session::get('success')}}</strong>
</div>
@endif

<form method="POST" action="{{ route('shop.createStamp') }}" enctype="multipart/form-data">
    {{ csrf_field() }}
    <div class="row" style="margin-left:20rem;margin-top:-3rem;padding:50px;">
        <div class="column">
            <h2>Custom T-Shirt</h2>

            <input type="hidden" name="author" value="{{Auth::user()->id}}">

            <label for="image">Stamp Name:</label>
            <input type="text" name="stamp_name" size="50%" required>

            <label for="image">Stamp Description:</label>
            <textarea type="text" name="stamp_description" size="50%"></textarea>

            <label for="image">Upload your stamp here:</label>
            <input accept="image/*" type="file" id="stamp_image" name="stamp_image" size="50%" required>

            <script>
                stamp_image.onchange = evt => {
                    const [file] = stamp_image.files
                    if (file) {
                        stamp.src = URL.createObjectURL(file)
                    }
                }
            </script>

            <button class="dropbtn">Submit</button>

        </div>
        <div class="column">
            <img style="position:absolute;z-index:-1;max-width:400px;max-height:400px;" src="/storage/tshirt_base/1e1e21.jpg">
            <img id="stamp" style="transform:translateX(126px) translateY(-30px);z-index:1;max-width:120px;max-height:auto;" />
        </div>

    </div>
</form>

<!-- Bootstrap core JS-->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js"></script>
<!-- Third party plugin JS-->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.4.1/jquery.easing.min.js"></script>
<!-- Contact form JS-->
<script src="/mail/jqBootstrapValidation.js"></script>
<script src="/mail/contact_me.js"></script>
<!-- Core theme JS-->
<script src="/js/scripts.js"></script>

@stop