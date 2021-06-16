@extends('shop')

@section('content')
<h1 style="margin-top:150px;text-align:center"><strong>Statistics</strong></h1>

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.3.2/chart.js"></script>

<style>
    * {
        box-sizing: border-box;
    }

    /* Create two equal columns that floats next to each other */
    .column {
        float: left;
        width: 50%;
        height: 300px;
        /* Should be removed. Only for demonstration */
    }

    /* Clear floats after the columns */
    .row:after {
        content: "";
        display: table;
        clear: both;
    }
</style>

<div class="row">
    <div class="column">
        <div style="max-width:60%;margin:auto">
            <div style="margin-left:20px;">
                <button class="btn btn-danger dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                    Year
                </button>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    @foreach($datas as $data)
                    <a class="dropdown-item" href="{{ route('shop.statistics',['data' => $data]) }}">{{ $data }}</a>
                    @endforeach
                </div>
            </div>
            <canvas id="stamps" width="100" height="100"></canvas>
        </div>
    </div>
    <div class="column">
        <div style="max-width:60%;margin:auto">
            <canvas id="deliveries" width="100" height="100"></canvas>
        </div>
    </div>
</div>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
    <div class="row">
        <div class="column" style="margin:auto">
            <div style="max-width:80%;">
                <canvas id="categories" width="100" height="100"></canvas>
            </div>
        </div>
    </div>
<script>
    var ctx = document.getElementById('deliveries').getContext('2d');
    var deliveriesChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: @json($coresNomes),
            datasets: [{
                label: '# of Votes',
                data: @json($coresCounter),
                backgroundColor: [
                    @foreach($cores as $cor)
                    '#' + @json($cor -> cor_codigo),
                    @endforeach

                ],
                borderColor: [
                    @foreach($cores as $cor)
                    '#' + @json($cor -> cor_codigo),
                    @endforeach
                ],
                borderWidth: 1
            }]
        },
        options: {
            plugins: {
                title: {
                    display: true,
                    text: 'Most sold Colors',
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        }
    });


    var ctx2 = document.getElementById('stamps').getContext('2d');
    var stampsChart = new Chart(ctx2, {
        type: 'line',
        data: {
            labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
            datasets: [{
                label: '# of Votes',
                data: @json($num_encomendas_por_mes),
                backgroundColor: [
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(153, 102, 255, 0.2)',
                    'rgba(255, 159, 64, 0.2)',
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(153, 102, 255, 0.2)',
                    'rgba(255, 159, 64, 0.2)',

                ],
                borderColor: [
                    'rgba(255, 99, 132, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 159, 64, 1)',
                    'rgba(255, 99, 132, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 159, 64, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            plugins: {
                title: {
                    display: true,
                    text: 'Deliveries throughout the year of ' + @json($ano),
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        }
    });

    var ctx3 = document.getElementById('categories').getContext('2d');
    var categoriesChart = new Chart(ctx3, {
        type: 'bar',
        data: {
            responsive:true,
            labels: @json($categoriasNomes),
            datasets: [{
                label: '# of TShirts sold with that category',
                data: @json($categoriasCounter),
                backgroundColor: [
                    'rgba(255, 190, 0, 1)',

                ],
                borderColor: [
                    'rgba(255, 100, 0, 0.3)',
                ],

                borderWidth: 1
            }]
        },
        options: {
            plugins: {
                title: {
                    display: true,
                    text: 'Most sold Categories',
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        }
    });
</script>
@stop