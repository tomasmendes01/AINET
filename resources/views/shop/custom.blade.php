@extends('shop')

@section('css')
<link href="/css/custom_stamp.css" rel="stylesheet" />
@stop

@section('content')

<form method="POST" action="{{ route('shop.createStamp') }}" enctype="multipart/form-data">
    {{ csrf_field() }}
    <div class="row" style="margin-top:-6%;padding:50px;">
        <div class="column" >

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
        <h2>Preview</h2>
            <img id="stamp" src="#" style="width:auto;max-height:500px" />
        </div>

    </div>
</form>

<footer class="footer py-4">
    <div class="container" style="width: 100%; margin-bottom:2%; height:2.5rem; bottom:0; left: 0; right: 0">
        <div class="row align-items-center">
            <div class="col-lg-4 text-lg-left">MagicShirts © AINet - Politécnico de Leiria</div>
            <div class="col-lg-4 my-3 my-lg-0">
                <a class="btn btn-dark btn-social mx-2" href="#!"><i class="fab fa-twitter"></i></a>
                <a class="btn btn-dark btn-social mx-2" href="#!"><i class="fab fa-facebook-f"></i></a>
                <a class="btn btn-dark btn-social mx-2" href="#!"><i class="fab fa-linkedin-in"></i></a>
            </div>
            <div class="col-lg-4 text-lg-right">
                <a class="mr-3" href="#!">Privacy Policy</a>
                <a href="#!">Terms of Use</a>
            </div>
        </div>
    </div>
</footer>

<!-- Bootstrap core JS-->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js"></script>
<!-- Third party plugin JS-->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.4.1/jquery.easing.min.js"></script>
<!-- Contact form JS-->
<script src="mail/jqBootstrapValidation.js"></script>
<script src="mail/contact_me.js"></script>
<!-- Core theme JS-->
<script src="/js/scripts.js"></script>

@stop