<!doctype html>
<html lang="es">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">

    <!-- Bootstrap CSS -->
    <link href="{{asset('assets/css/bootstrap.css')}}" rel="stylesheet">
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.gstatic.com" />
    <link href="{{asset('assets/css/google.css')}}" rel="stylesheet">
    <!-- Reporte CSS -->
    <link href="{{asset('assets/css/reporte-pdf.css')}}?{{time()}}" rel="stylesheet">
    <!-- wkhtmltopdf Helper -->
    <link href="{{asset('assets/css/wkhtmltopdf/wkhtmltopdf.css')}}" rel="stylesheet">
    <!-- libreria para charts -->
    {{ HTML::script('assets/js/charts/loader.js') }}
    <title>MDSF Solicitud</title>
    <!--  -->
    <script type="text/javascript">
        function init() {
            google.charts.load('42', {'packages':['corechart']});
            var interval = setInterval(function() {
                if (google.visualization.LineChart !== undefined
                    && google.visualization.AreaChart !== undefined
                    && google.visualization.BarChart !== undefined){
                    clearInterval(interval);
                    drawLines();
                    //drawChartA();
                    //drawChartB();
                    drawChartC();
                    drawChartD();
                    //AreaChartHogar();
                    //AreaChartTitular();
                    window.status = 'ready';
                }
            }, 500);

        }
        function porcentaceS(valor){
            porcetaje = null;
            switch (valor) {
                case 1:
                    porcetaje = 20;
                    break;
                case 2:
                    porcetaje = 45;
                    break;
                case 3:
                    porcetaje = 55;
                    break;
                case 4:
                    porcetaje = 65;
                    break;
                case 5:
                    porcetaje = 75;
                    break;
                case 6:
                    porcetaje = 85;
                    break;
                case 7:
                    porcetaje = 95;
                    break;
            }
            return porcetaje;
        }

        function drawLines() {
            var data = new google.visualization.DataTable();
            data.addColumn('string', 'Fecha de la públicación de la Calificación Socioeconómica (CSE)');
            data.addColumn('number', 'Calificación Socioeconómica');
            @foreach($data['ingresosObservados'] as $items )
            data.addRows([['{{$items->fecha_formato}}',porcentaceS({{$items->tramo_cse ?? 0 }})]]);
            @endforeach
            /*data.addRows([[ 'May 2020',  porcentaceS(4)]]);
            data.addRows([[ 'Jun 2020',  porcentaceS(5)]]);
            data.addRows([[ 'Jul 2020',  porcentaceS(5)]]);
            data.addRows([[ 'Aug 2020',  porcentaceS(7)]]);
            data.addRows([[ 'Sep 2020',  porcentaceS(3)]]);
            data.addRows([[ 'Oct 2020',  porcentaceS(4)]]);
            data.addRows([[ 'Nov 2020',  porcentaceS(1)]]);
            data.addRows([[ 'Dec 2020',  porcentaceS(2)]]);
            data.addRows([[ 'Jan 2021',  porcentaceS(2)]]);
            data.addRows([[ 'Feb 2021',  porcentaceS(3)]]);
            data.addRows([[ 'Mar 2021',  porcentaceS(2)]]);
            data.addRows([[ 'Apr 2021',  porcentaceS(7)]]);*/

            var options = {
                //title: 'Calificación Socioeconómica (Tramo porcentual de menores ingresos o mayor vulnerabilidad socioeconómica al que permanece el hogar)',
                //titleTextStyle : {color: '#a2a2a2', fontSize: 16},
                width: 700,
                height: 400,
                fontSize: 10,
                colors: ['#262626'],
                legend: {position: 'bottom'},
                pointSize: 5,
                vAxis: {
                    /*gridlines: {color:'#a2a2a2'},*/
                    maxValue:100,
                    minValue: 0,
                    ticks: [{v:20, f:'0% a 40%'},{v:40, f:''}, {v:45, f:'41% a 50%'},{v:50, f:''}, {v:55, f:'51% a 60%'},{v:60, f:''}, {v:65, f:'61% a 70%'},{v:70, f:''}, {v:75, f:'71% a 80%'},{v:80, f:''}, {v:85, f:'81% a 90%'},{v:90, f:''}, {v:95, f:'91% a 100%'},{v:100, f:''}],
                },
                hAxis: {
                    /*gridlines: {color:'#a2a2a2'},*/
                    title: 'Fecha de la públicación de la Calificación Socioeconómica (CSE)',
                }
            };
            var container = document.getElementById('lines_div');
            var chart = new google.visualization.LineChart(container);
            google.visualization.events.addListener(chart, 'ready', function () {
                $ban = 0;
                $bar = true;
                Array.prototype.forEach.call(container.getElementsByTagName('rect'), function(rect, index) {

                    if ($ban<19 && $bar) {
                        rect.setAttribute('fill', 'none');
                        $bar = false;
                    }else {
                        $bar = true;
                    }
                    $ban++;
                });
            });
            chart.draw(data, options);

            //var chart = new google.charts.Line(document.getElementById('lines_div'));
            //chart.draw(data, google.charts.Line.convertOptions(options));
        }

        function drawChartA() {
            <?php $mayor = ["Capital" => 0, "Pensiones" => 0, "Trabajo" => 0];?>
            @foreach($data['ingresosObservados'] as $items )
                <?php
                $mayor['Capital'] = $items->y_k ?? 0;
                $mayor['Pensiones'] = $items->y_pen ?? 0;
                $mayor['Trabajo'] = $items->y_lab ?? 0;
                ?>
            @endforeach
            <?php
                arsort($mayor);
            ?>

            var data = new google.visualization.DataTable();
            data.addColumn('string', 'Date');
            @foreach($mayor as $key => $items )
                data.addColumn('number', '{{$key}}');
            @endforeach
            data.addColumn('number');
            data.addColumn('number');
            data.addColumn('number');
            <?php $total=0;$Capital=0;$Pensiones=0;$Trabajo=0;$valorMax=500000; ?>
            @foreach($data['ingresosObservados'] as $items )
                <?php
                    $Capital = ($items->y_k>0) ?$items->y_k: null;
                    $Pensiones = ($items->y_pen>0) ?$items->y_pen: null;
                    $Trabajo = ($items->y_lab>0) ?$items->y_lab: null;
                    $total = $Capital + $Pensiones + $Trabajo;
                    if($valorMax<$total){$valorMax = $total;}
                ?>
                 data.addRows([['{{$items->fecha_formato}}',
                @foreach($mayor as $key => $item )
                {{$$key}},
                @endforeach
                null,null,null]]);
            @endforeach


            /*data.addRows([[ 'Jan 2021',  50000,400000,0,null,null,null]]);
            data.addRows([[ 'Feb 2021',  100000,403000,0,null,null,null]]);
            data.addRows([[ 'Mar 2021',  1000000,400000,0,null,null,null]]);
            data.addRows([[ 'Apr 2021',  300000,400000,0,null,null,null]]);
            data.addRows([[ 'May 2021',  350000,400000,150000,null,null,null]]);
            data.addRows([[ 'Jun 2021',  400000,400000,50000,null,null,null]]);
            data.addRows([[ 'Jul 2021',  300000,300000,50000,null,null,null]]);
            data.addRows([[ 'Aug 2021',  400000,400000,150000,null,null,null]]);
            data.addRows([[ 'Sep 2021',  500000,400000,50000,null,null,null]]);
            data.addRows([[ 'Oct 2021',  400000,400000,50000,null,null,null]]);
            data.addRows([[ 'Nov 2021',  400000,400000,50000,null,null,null]]);
            data.addRows([[ 'Dec 2021',  100000,70000,1500000,null,null,null]]);*/

            <?php
            $arrayColor = ["Capital" => "#DDAA33", "Pensiones" => "#004488", "Trabajo" => "#BB5566"];
            $indice = 0;
            $total=0;
            $ban=0;
            $valorAnterior=0;
            $position=0;
            ?>

            var options = {
                title: 'Evolución del promedio en 12 meses de ingresos del Titular',
                subtitle: 'Meses/ Pesos',
                isStacked:true,
                width: 800,
                height: 450,
                series: {
                    @foreach($mayor as $key => $item )
                    {{$indice}}: {targetAxisIndex:0, axis: '{{$key}}', color: '{{$arrayColor[$key]}}' },
                    <?php $indice++ ?>
                    @endforeach
                    3: {targetAxisIndex:1, color: '#fafafa'} ,
                    4: {targetAxisIndex:1, color: '#fafafa'} ,
                    5: {targetAxisIndex:1, color: '#fafafa'},
                },
                //fontSize: 10,
                vAxes: {
                    0: {title: 'Promedio ingreso 12 meses (CLP$)',
                    },
                    1: {
                        ticks: [
                                @foreach($mayor as $key => $item )

                                <?php

                                $valor=$$key;
                                $total+=$valor;
                                if($valor>50000){
                                    $ban=0;
                                    $position = -50000;
                                }else{
                                    $ban++;
                                    $position = $ban * 50000;
                                }
                                $valorAnterior = $total+$position;
                                if($valorMax<$valorAnterior){
                                    $valorMax=$valorAnterior;
                                }
                                ?>
                                @if($valor>0)
                            {v:{{$valorAnterior}}, f:'{{$key}} {{\App\Http\Controllers\Utiles::ponePuntosNumero($valor)}}'},
                                @endif
                                @endforeach

                                @if($total>0)
                            {v:{{$valorAnterior+100000 }}, f:'Total {{\App\Http\Controllers\Utiles::ponePuntosNumero($total)}}'},
                            @endif
                        ],
                    }
                },
                hAxis: {
                    title: 'Mes/Año',
                    gridlines: { count: 15 },
                    format: 'MMM yyyy',
                    textStyle: {
                        fontSize: 10 // or the number you want
                    },
                },
                vAxis: {
                    viewWindow: {
                        max: {{$valorMax+150000}}
                    },
                    textStyle: {
                        fontSize: 9 // or the number you want
                    },
                }
            };
            var chart = new google.visualization.AreaChart(document.getElementById('grafico-ingresos-titular'));
            chart.draw(data,options);

        }
        function drawChartB() {
            <?php $mayor = ["Capital" => 0, "Pensiones" => 0, "Trabajo" => 0];?>
            @foreach($data['ingresosObservados'] as $items )
            <?php
            $mayor['Capital'] = $items->y_k_hogar?? 0;
            $mayor['Pensiones'] = $items->y_pen_hogar ?? 0;
            $mayor['Trabajo'] = $items->y_lab_hogar ?? 0;
            ?>
            @endforeach
            <?php
            arsort($mayor);
            ?>

            var data = new google.visualization.DataTable();
            data.addColumn('string', 'Date');
            @foreach($mayor as $key => $items )
                data.addColumn('number', '{{$key}}');
            @endforeach
            data.addColumn('number');
            data.addColumn('number');
            data.addColumn('number');
            <?php $total=0;$Capital=0;$Pensiones=0;$Trabajo=0;$valorMax=500000; ?>
            @foreach($data['ingresosObservados'] as $items )
                <?php
                    $Capital = ($items->y_k_hogar>0) ?$items->y_k_hogar: null;
                    $Pensiones = ($items->y_pen_hogar>0) ?$items->y_pen_hogar: null;
                    $Trabajo = ($items->y_lab_hogar>0) ?$items->y_lab_hogar: null;
                    $total = $Capital + $Pensiones + $Trabajo;
                    if($valorMax<$total){$valorMax = $total;}
                ?>
                data.addRows([['{{$items->fecha_formato}}',
                @foreach($mayor as $key => $item )
                {{$$key}},
                @endforeach
                null,null,null]]);
            @endforeach


            /*data.addRows([[ 'Jan 2021',  50000,400000,0,null,null,null]]);
            data.addRows([[ 'Feb 2021',  100000,403000,0,null,null,null]]);
            data.addRows([[ 'Mar 2021',  1000000,400000,0,null,null,null]]);
            data.addRows([[ 'Apr 2021',  300000,400000,0,null,null,null]]);
            data.addRows([[ 'May 2021',  350000,400000,150000,null,null,null]]);
            data.addRows([[ 'Jun 2021',  400000,400000,50000,null,null,null]]);
            data.addRows([[ 'Jul 2021',  300000,300000,50000,null,null,null]]);
            data.addRows([[ 'Aug 2021',  400000,400000,150000,null,null,null]]);
            data.addRows([[ 'Sep 2021',  500000,400000,50000,null,null,null]]);
            data.addRows([[ 'Oct 2021',  400000,400000,50000,null,null,null]]);
            data.addRows([[ 'Nov 2021',  400000,400000,50000,null,null,null]]);
            data.addRows([[ 'Dec 2021',  100000,70000,1500000,null,null,null]]);*/

            <?php
                $arrayColor = ["Capital" => "#DDAA33", "Pensiones" => "#004488", "Trabajo" => "#BB5566"];
                $indice = 0;
                $total=0;
                $ban=0;
                $valorAnterior=0;
                $position=0;
            ?>
            var options = {
                title: 'Evolución del promedio en 12 meses de ingresos del hogar',
                subtitle: 'Meses/ Pesos',
                isStacked:true,
                width: 800,
                height: 450,
                series: {
                    @foreach($mayor as $key => $item )
                    {{$indice}}: {targetAxisIndex:0, axis: '{{$key}}', color: '{{$arrayColor[$key]}}' },
                    <?php $indice++ ?>
                    @endforeach
                    3: {targetAxisIndex:1, color: '#fafafa'} ,
                    4: {targetAxisIndex:1, color: '#fafafa'} ,
                    5: {targetAxisIndex:1, color: '#fafafa'},
                },
                //fontSize: 10,
                vAxes: {
                    0: {title: 'Promedio ingreso 12 meses (CLP$)',
                    },
                    1: {
                        ticks: [
                                @foreach($mayor as $key => $item )
                                <?php

                                $valor=$$key;
                                $total+=$valor;
                                if($valor>50000){
                                    $ban=0;
                                    $position = -50000;
                                }else{
                                    $ban++;
                                    $position = $ban * 50000;
                                }
                                $valorAnterior = $total+$position;
                                if($valorMax<$valorAnterior){
                                    $valorMax=$valorAnterior;
                                }
                                ?>
                                @if($valor>0)
                            {v:{{$valorAnterior}}, f:'{{$key}} {{\App\Http\Controllers\Utiles::ponePuntosNumero($valor)}}'},
                                @endif
                                @endforeach

                                @if($total>0)
                            {v:{{$valorAnterior+100000 }}, f:'Total {{\App\Http\Controllers\Utiles::ponePuntosNumero($total)}}'},
                            @endif
                        ],
                    }
                },
                hAxis: {
                    title: 'Mes/Año',
                    gridlines: { count: 15 },
                    format: 'MMM yyyy',
                    textStyle: {
                        fontSize: 10 // or the number you want
                    },
                },
                vAxis: {
                    viewWindow: {
                        max: {{$valorMax+150000}}
                    },
                    textStyle: {
                        fontSize: 9 // or the number you want
                    },
                }
            };
            var chart = new google.visualization.AreaChart(document.getElementById('grafico-ingresos-hogar'));

            chart.draw(data,options);

        }
        function drawChartC() {
            <?php $mayor = ["Capital" => 0, "Pensiones" => 0, "Trabajo" => 0];?>
            @foreach($data['ingresosObservados'] as $items )
            <?php
            $mayor['Capital'] = $items->y_k ?? 0;
            $mayor['Pensiones'] = $items->y_pen ?? 0;
            $mayor['Trabajo'] = $items->y_lab ?? 0;
            ?>
            @endforeach
            <?php
            arsort($mayor);
            ?>

            var data = new google.visualization.DataTable();
            data.addColumn('string', 'Date');
            @foreach($mayor as $key => $items )
            data.addColumn('number', '{{$key}}');
            @endforeach
            data.addColumn('number');
            data.addColumn('number');
            data.addColumn('number');
            <?php $total=0;$Capital=0;$Pensiones=0;$Trabajo=0;$valorMax=500000; ?>
            @foreach($data['ingresosObservados'] as $items )
            <?php
            $Capital = ($items->y_k>0) ?$items->y_k: null;
            $Pensiones = ($items->y_pen>0) ?$items->y_pen: null;
            $Trabajo = ($items->y_lab>0) ?$items->y_lab: null;
            $total = $Capital + $Pensiones + $Trabajo;
            if($valorMax<$total){$valorMax = $total;}
            ?>
            data.addRows([['{{$items->fecha_formato}}',
                @foreach($mayor as $key => $item )
                {{$$key}},
                @endforeach
                    null,null,null]]);
            @endforeach


            /*data.addRows([[ 'Jan 2021',  50000,400000,0,null,null,null]]);
            data.addRows([[ 'Feb 2021',  100000,403000,0,null,null,null]]);
            data.addRows([[ 'Mar 2021',  1000000,400000,0,null,null,null]]);
            data.addRows([[ 'Apr 2021',  300000,400000,0,null,null,null]]);
            data.addRows([[ 'May 2021',  350000,400000,150000,null,null,null]]);
            data.addRows([[ 'Jun 2021',  400000,400000,50000,null,null,null]]);
            data.addRows([[ 'Jul 2021',  300000,300000,50000,null,null,null]]);
            data.addRows([[ 'Aug 2021',  400000,400000,150000,null,null,null]]);
            data.addRows([[ 'Sep 2021',  500000,400000,50000,null,null,null]]);
            data.addRows([[ 'Oct 2021',  400000,400000,50000,null,null,null]]);
            data.addRows([[ 'Nov 2021',  400000,400000,50000,null,null,null]]);
            data.addRows([[ 'Dec 2021',  100000,70000,1500000,null,null,null]]);*/

            <?php
            $arrayColor = ["Capital" => "#DDAA33", "Pensiones" => "#004488", "Trabajo" => "#BB5566"];
            $indice = 0;
            $total=0;
            $ban=0;
            $valorAnterior=0;
            $position=0;
            ?>

            var options = {
                title: 'Evolución del promedio en 12 meses de ingresos del Titular',
                subtitle: 'Meses/ Pesos',
                isStacked:true,
                width: 800,
                height: 450,
                series: {
            @foreach($mayor as $key => $item )
            {{$indice}}: {targetAxisIndex:0, axis: '{{$key}}', color: '{{$arrayColor[$key]}}' },
            <?php $indice++ ?>
                @endforeach
                3: {targetAxisIndex:1, color: '#fafafa'} ,
            4: {targetAxisIndex:1, color: '#fafafa'} ,
            5: {targetAxisIndex:1, color: '#fafafa'},
        },
            //fontSize: 10,
            vAxes: {
                0: {title: 'Promedio ingreso 12 meses (CLP$)',
                },
                1: {
                    gridlines: {color:'white'},
                    ticks: [
                            @foreach($mayor as $key => $item )

                            <?php

                            $valor=$$key;
                            $total+=$valor;
                            if($valor>50000){
                                $ban=0;
                                $position = -50000;
                            }else{
                                $ban++;
                                $position = $ban * 50000;
                            }
                            $valorAnterior = $total+$position;
                            if($valorMax<$valorAnterior){
                                $valorMax=$valorAnterior;
                            }
                            ?>
                            @if($valor>0)
                        {v:{{$valorAnterior}}, f:'{{$key}} {{\App\Http\Controllers\Utiles::ponePuntosNumero($valor)}}'},
                            @endif
                            @endforeach

                            @if($total>0)
                        {v:{{$valorAnterior+100000 }}, f:'Total {{\App\Http\Controllers\Utiles::ponePuntosNumero($total)}}'},
                        @endif
                    ],
                }
            },
            hAxis: {
                title: 'Mes/Año',
                    gridlines: { count: 15 },
                format: 'MMM yyyy',
                    textStyle: {
                    fontSize: 10 // or the number you want
                },
            },
            vAxis: {
                viewWindow: {
                    max: {{$valorMax+150000}}
                        },
                textStyle: {
                    fontSize: 9 // or the number you want
                },
            }
        };
            var chart = new google.visualization.ColumnChart(document.getElementById('grafico-ingresos-titular'));
            chart.draw(data,options);

        }
        function drawChartD() {
            <?php $mayor = ["Capital" => 0, "Pensiones" => 0, "Trabajo" => 0];?>
            @foreach($data['ingresosObservados'] as $items )
            <?php
            $mayor['Capital'] = $items->y_k_hogar?? 0;
            $mayor['Pensiones'] = $items->y_pen_hogar ?? 0;
            $mayor['Trabajo'] = $items->y_lab_hogar ?? 0;
            ?>
            @endforeach
            <?php
            arsort($mayor);
            ?>

            var data = new google.visualization.DataTable();
            data.addColumn('string', 'Date');
            @foreach($mayor as $key => $items )
            data.addColumn('number', '{{$key}}');
            @endforeach
            data.addColumn('number');
            data.addColumn('number');
            data.addColumn('number');
            <?php $total=0;$Capital=0;$Pensiones=0;$Trabajo=0;$valorMax=500000; ?>
            @foreach($data['ingresosObservados'] as $items )
            <?php
            $Capital = ($items->y_k_hogar>0) ?$items->y_k_hogar: null;
            $Pensiones = ($items->y_pen_hogar>0) ?$items->y_pen_hogar: null;
            $Trabajo = ($items->y_lab_hogar>0) ?$items->y_lab_hogar: null;
            $total = $Capital + $Pensiones + $Trabajo;
            if($valorMax<$total){$valorMax = $total;}
            ?>
            data.addRows([['{{$items->fecha_formato}}',
                @foreach($mayor as $key => $item )
                {{$$key}},
                @endforeach
                    null,null,null]]);
            @endforeach


            /*data.addRows([[ 'Jan 2021',  50000,400000,0,null,null,null]]);
            data.addRows([[ 'Feb 2021',  100000,403000,0,null,null,null]]);
            data.addRows([[ 'Mar 2021',  1000000,400000,0,null,null,null]]);
            data.addRows([[ 'Apr 2021',  300000,400000,0,null,null,null]]);
            data.addRows([[ 'May 2021',  350000,400000,150000,null,null,null]]);
            data.addRows([[ 'Jun 2021',  400000,400000,50000,null,null,null]]);
            data.addRows([[ 'Jul 2021',  300000,300000,50000,null,null,null]]);
            data.addRows([[ 'Aug 2021',  400000,400000,150000,null,null,null]]);
            data.addRows([[ 'Sep 2021',  500000,400000,50000,null,null,null]]);
            data.addRows([[ 'Oct 2021',  400000,400000,50000,null,null,null]]);
            data.addRows([[ 'Nov 2021',  400000,400000,50000,null,null,null]]);
            data.addRows([[ 'Dec 2021',  100000,70000,1500000,null,null,null]]);*/

            <?php
            $arrayColor = ["Capital" => "#DDAA33", "Pensiones" => "#004488", "Trabajo" => "#BB5566"];
            $indice = 0;
            $total=0;
            $ban=0;
            $valorAnterior=0;
            $position=0;
            ?>
            var options = {
                title: 'Evolución del promedio en 12 meses de ingresos del hogar',
                subtitle: 'Meses/ Pesos',
                isStacked:true,
                width: 800,
                height: 450,
                series: {
            @foreach($mayor as $key => $item )
            {{$indice}}: {targetAxisIndex:0, axis: '{{$key}}', color: '{{$arrayColor[$key]}}' },
            <?php $indice++ ?>
                @endforeach
                3: {targetAxisIndex:1, color: '#fafafa'} ,
            4: {targetAxisIndex:1, color: '#fafafa'} ,
            5: {targetAxisIndex:1, color: '#fafafa'},
        },
            //fontSize: 10,
            vAxes: {
                0: {title: 'Promedio ingreso 12 meses (CLP$)',
                },
                1: {
                    gridlines: {color:'white'},
                    ticks: [
                            @foreach($mayor as $key => $item )
                            <?php

                            $valor=$$key;
                            $total+=$valor;
                            if($valor>50000){
                                $ban=0;
                                $position = -50000;
                            }else{
                                $ban++;
                                $position = $ban * 50000;
                            }
                            $valorAnterior = $total+$position;
                            if($valorMax<$valorAnterior){
                                $valorMax=$valorAnterior;
                            }
                            ?>
                            @if($valor>0)
                        {v:{{$valorAnterior}}, f:'{{$key}} {{\App\Http\Controllers\Utiles::ponePuntosNumero($valor)}}'},
                            @endif
                            @endforeach

                            @if($total>0)
                        {v:{{$valorAnterior+100000 }}, f:'Total {{\App\Http\Controllers\Utiles::ponePuntosNumero($total)}}'},
                        @endif
                    ],
                }
            },
            hAxis: {
                title: 'Mes/Año',
                    gridlines: { count: 15 },
                format: 'MMM yyyy',
                    textStyle: {
                    fontSize: 10 // or the number you want
                },
            },
            vAxis: {
                viewWindow: {
                    max: {{$valorMax+150000}}
                        },
                textStyle: {
                    fontSize: 9 // or the number you want
                },
            }
        };
            var chart = new google.visualization.ColumnChart(document.getElementById('grafico-ingresos-hogar'));

            chart.draw(data,options);

        }

    </script>

</head>
<body onload="init()">
    <!-- Header -->
    <header id="header" class="row justify-content-between">
        <div class="col-4">
            <p>Ministerio de Desarrollo Social y Familia</p>
            <table>
                <tbody>
                <tr>
                    <td style="width: 40px; background-color: blue; line-height: 10px;">.</td>
                    <td style="width: 45px; background-color: red; line-height: 10px;">.</td>
                    <td></td>
                </tr>
                </tbody>
            </table>
        </div>
        <div class="col-5 header-title mt-3">
            <h4 class="card-title mb-0">
                Reporte de <strong>Calificación Socioeconómica</strong><br />
            </h4>
        </div>
        <div class="col-3 header-info alert alert-primary">
            <ul class="header-info__list">
                <li>
                    Fecha de Emisión:<br /><span><strong>{{ date('Y-m-d') }}</strong></span>
                </li>
                <li>
                    Nombre Funcionario(a):<br /><span><strong>{{ $data['baseGeneralPlantilla']['nombreSolicitante'] }}</strong></span>
                </li>
            </ul>
        </div>
    </header>
    <!-- end Header -->
    <main>
        <section id="titular" class="mb-0">
            <div class="titular-container p-2">
                <div class="row no-gutters justify-content-between mb-0 text-light">
                    <div class="user-info row no-gutters">
                        <img src="{{asset('assets/images/icons/titular-b.png')}}" height="55px" />
                        <div class="user-info">
                            <h6>R.U.N. Titular</h6>
                            <h6 class="user-info__title">{{ \App\Http\Controllers\Utiles::ponePuntosNumero($data['personalTitular']['runTitular']) }} - {{ $data['personalTitular']['dvTitular'] }}</h6>
                        </div>
                    </div>
                    <div class="user-info">
                        <h6>Nombre Titular</h6>
                        <h6 class="user-info__title">{{ $data['personalTitular']['nombreTitular'] }}  {{ $data['personalTitular']['apellidoPTitular'] }} {{ $data['personalTitular']['apellidoMTitular'] }}</h6>
                    </div>
                    <div class="user-info">
                        <h6>Folio</h6>
                        <h6 class="user-info__title">{{ $data['baseGeneralPlantilla']['folioVigente'] }}</h6>
                    </div>
                </div>
            </div>
        </section>
        <!-- Ingresos -->
        <section id="ingresos" class="alert-success p-3">
            <div>
                <div class="row no-gutters justify-content-between mb-3">
                    <div class="col-7">
                        <h4 class="section-title">
                            <span><i class="circle px-2"><img src="{{asset('assets/images/icons/ingresos-b.png')}}" height="30px"/></i>
                            </span>
                            {{ $data['baseGeneralPlantilla']['titulo_item1'] }}
                        </h4>
                    </div>
                    <div class="col-5">
                        <div class="alert alert-primary text-center">
                            {{ $data['baseGeneralPlantilla']['subtituloItem1'] }}
                        </div>
                    </div>
                </div>
                <div class="row no-gutters justify-content-center mb-3">
                    <div class="col-4 text-center" style="background-color: #FFFFFF">
                        <br/><br/><br/>
                        <p class="text-body pt-5">Calificación Socioeconómica (Tramo porcentual de menores ingresos o mayor vulnerabilidad socioeconómica al que pertenece el hogar)</p>
                    </div>
                    <div id="lines_div" class="pie-chart col-8"></div>
                </div>
            </div>
            <div>
                <div class="row no-gutters justify-content-between mb-3">
                    <div class="col-7">
                        <h4 class="section-title">
                          <span>
                            <i class="circle px-2"><img src="{{asset('assets/images/icons/ingresos-b.png')}}" height="30px"/></i>
                          </span>
                          {{ $data['baseGeneralPlantilla']['titulo_item2'] }}
                        </h4>
                    </div>
                    <div class="col-5">
                        <div class="alert alert-primary text-center">
                            {{ $data['baseGeneralPlantilla']['subtituloItem2'] }}
                        </div>
                    </div>
                </div>
                @if( $data['ocultarFilaIngreso']['ocultaTodotabla'] )
                <div class="table-container">
                    <table class="table table-sm table-striped table-light small">
                        <thead>
                        <tr>
                            <th style="text-align: center;">Año/Mes</th>
                            @if( $data['ocultarFilaIngreso']['fuenteIngTrabajo'] )
                                <th style="text-align: center;">Laborales</th>
                            @endif
                            @if( $data['ocultarFilaIngreso']['fuenteTrabajo'] )
                                <th style="text-align: center;">Fuente</th>
                            @endif
                            @if( $data['ocultarFilaIngreso']['fuenteIngPension'] )
                                <th style="text-align: center;">Pensiones</th>
                            @endif
                            @if( $data['ocultarFilaIngreso']['fuentePension'] )
                                <th style="text-align: center;">Fuente</th>
                            @endif
                            @if( $data['ocultarFilaIngreso']['fuenteIngCapital'] )
                                <th style="text-align: center;">Capital</th>
                            @endif
                            @if( $data['ocultarFilaIngreso']['fuenteCapital'] )
                                <th style="text-align: center;">Fuente</th>
                            @endif
                        </tr>
                        </thead>
                        <tbody>
                            @foreach( $data['ingresosObservados'] as $items )
                            <tr>
                                <td style="text-align: center;">{{ $items->fecha_formato }}</td>
                                @if( $data['ocultarFilaIngreso']['fuenteIngTrabajo'] )
                                    <td style="text-align: center;">${{ \App\Http\Controllers\Utiles::ponePuntosNumero($items->rmin_y_lab) }} a $ {{ \App\Http\Controllers\Utiles::ponePuntosNumero($items->rmax_y_lab) }}</td>
                                @endif
                                @if( $data['ocultarFilaIngreso']['fuenteTrabajo'] )
                                    <td style="text-align: center;">{{ $items->lab }}</td>
                                @endif
                                @if( $data['ocultarFilaIngreso']['fuenteIngPension'] )
                                    <td style="text-align: center;">${{ \App\Http\Controllers\Utiles::ponePuntosNumero($items->rmin_y_pen) }} a ${{ \App\Http\Controllers\Utiles::ponePuntosNumero($items->rmax_y_pen) }}</td>
                                @endif
                                @if( $data['ocultarFilaIngreso']['fuentePension'] )
                                    <td style="text-align: center;">{{ $items->pen }}</td>
                                @endif
                                @if( $data['ocultarFilaIngreso']['fuenteIngCapital'] )
                                    <td style="text-align: center;">${{ \App\Http\Controllers\Utiles::ponePuntosNumero($items->rmin_y_k) }} a ${{ \App\Http\Controllers\Utiles::ponePuntosNumero($items->rmax_y_k) }}</td>
                                @endif
                                @if( $data['ocultarFilaIngreso']['fuenteCapital'] )
                                    <td style="text-align: center;">{{ $items->cap }}</td>
                                @endif
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <caption>
                    @if( ! $data['ocultarFilaIngreso']['fuenteTrabajo'] )
                    <p><strong>* {{ $data['ocultarFilaIngreso']['fuenteTrabajoTxt'] }}. </strong></p>
                    @endif
                    @if( ! $data['ocultarFilaIngreso']['fuentePension'] )
                    <p><strong>* {{ $data['ocultarFilaIngreso']['fuentePensionTxt'] }}. </strong></p>
                    @endif
                    @if( ! $data['ocultarFilaIngreso']['fuenteCapital'] )
                    <p><strong>* {{ $data['ocultarFilaIngreso']['fuenteCapitalTxt'] }}. </strong></p>
                    @endif
                </caption>
                @else
                    <div class="row no-gutters">
                        <div class="col-12 pl-1 mb-3">
                            <div class="alert alert-info" role="alert">
                                <strong>No se observan ingresos para el titular del dato.</strong>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </section>
        <!-- fin Ingresos -->

        <!-- Medios -->
        <section id="medios" class="break-page">
            <div class="row no-gutters justify-content-between mb-3">
                <div class="col-8">
                    <h4 class="section-title">
                        <span><i class="circle"><img src="{{asset('assets/images/icons/medios-b.png')}}" height="30px"/></i>
                        </span>
                        {{ $data['baseGeneralPlantilla']['titulo_item3'] }}
                    </h4>
                </div>
                <div class="col-4">
                    <div class="alert alert-info text-center">
                        {{ $data['baseGeneralPlantilla']['subtituloItem3'] }}
                    </div>
                </div>
            </div>

            @if( ! is_null($data['salud']) )
                <div class="row no-gutters">
                    <div class="col-12 pr-1 mb-3">
                        <!-- Salud -->
                        <div class="card">
                            <h5 class="card-header alert-danger">
                                <img src="{{asset('assets/images/icons/salud.png')}}" height="30px" />
                                Salud
                            </h5>
                            <div class="card-body">
                                <table class="table table-sm table-red">
                                    <caption>
                                        * Medio activado en base a la información entregada por la Superintendencia de Salud, con datos del  {{ $data['salud'][0]->fecha_etiqueta }}
                                    </caption>
                                    <thead>
                                        <tr>
                                            <th style="text-align: center;">R.U.N. activador del medio</th>
                                            <th style="text-align: center;">Tipo de Beneficiario</th>
                                            <th style="text-align: center;">R.U.N. cotizante</th>
                                            <th style="text-align: center;">ISAPRE</th>
                                            <th style="text-align: center;">Rango Monto Pactado</th>
                                            <th style="text-align: center;">Percentil Monto Pactado</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach( $data['salud'] as $items  )
                                            <tr>
                                                @if( $items->tipo_benef == 'Carga')
                                                    <td style="text-align: center;"><strong>{{ \App\Http\Controllers\Utiles::ponePuntosNumero($items->run_carga) }} - {{ \App\Http\Controllers\Utiles::dv($items->run_carga) }}</strong></td>
                                                @else
                                                    <td style="text-align: center;"><strong>{{ \App\Http\Controllers\Utiles::ponePuntosNumero($items->run_cotizante) }} - {{ \App\Http\Controllers\Utiles::dv($items->run_cotizante) }}</strong></td>
                                                @endif
                                                <td style="text-align: center;"><strong>{{ $items->tipo_benef  }}</strong></td>
                                                <td style="text-align: center;"><strong>{{ \App\Http\Controllers\Utiles::ponePuntosNumero($items->run_cotizante) }} - {{ \App\Http\Controllers\Utiles::dv($items->run_cotizante) }}</strong></td>
                                                <td style="text-align: center;"><strong>{{ $items->etiqueta_cÓdigo_isapre }}</strong></td>
                                                <td style="text-align: center;"><strong>${{ \App\Http\Controllers\Utiles::ponePuntosNumero($items->rmin_v19) }} a ${{ \App\Http\Controllers\Utiles::ponePuntosNumero($items->rmax_v19) }}</strong></td>
                                                <td style="text-align: center;" ><strong>{{ $items->percentil_v19 }}</strong></td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            @if( ! is_null($data['educacion']) )
                <div class="row no-gutters">
                <div class="col-12 pl-1 mb-3">
                    <!-- Educación -->
                    <div class="card">
                        <h5 class="card-header alert-primary">
                            <img src="{{asset('assets/images/icons/educacion.png')}}" height="30px" />
                            Educación
                        </h5>
                        <div class="card-body">
                            <table class="table table-sm table-blue">
                                <caption>
                                    * Medio activado en base a la información entregada por MINEDUC, con datos del {{ $data['educacion'][0]->agno }}.
                                </caption>
                                <thead>
                                <tr>
                                    <th style="text-align: center;">R.U.N. activador del medio</th>
                                    <th style="text-align: center;">Rol Base de Datos (RBD)</th>
                                    <th style="text-align: center;">Nombre Estab. Educacional</th>
                                    <th style="text-align: center;">Comuna Estab. Educacional</th>
                                </tr>
                                </thead>
                                <tbody>
                                    @foreach( $data['educacion'] as $items  )
                                        <tr>
                                            <td style="text-align: center;"><strong>{{ \App\Http\Controllers\Utiles::ponePuntosNumero($items->run) }} - {{ $data['personalTitular']['dvTitular'] }}</strong></td>
                                            <td style="text-align: center;"><strong>{{ $items->rbd }}</strong></td>
                                            <td style="text-align: center;"><strong>{{ $items->nom_rbd_mat }}</strong></td>
                                            <td style="text-align: center;"><strong>{{ $items->com_descripcion }}</strong></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            @endif
            @if( ! is_null($data['bienesRaices']) )
                <?php $banderaOcultaTd = true;?>
                @foreach( $data['bienesRaices'] as $items )
                   @if( $items->monto_av_com_min > 0 && $items->monto_av_com_max > 0 ){
                        {{ $banderaOcultaTd = true }}
                   @endif
                @endforeach
                <div class="row no-gutters">
                    <div class="col-12 pr-1 mb-3">
                        <!-- Bienes Raíces -->
                        <div class="card">
                            <h5 class="card-header alert-success">
                                <img src="{{asset('assets/images/icons/bienesraices.png')}}" height="30px" />
                                Bienes raíces
                            </h5>
                            <div class="card-body">
                                <table class="table table-sm table-green">
                                    <caption>
                                        <span>* Medio activado en base a la información entregada por SII, con datos del {{ $data['bienesRaices'][0]->semestre_texto }} del {{ $data['bienesRaices'][0]->ano }}. </span><br>
                                        <span>* La suma del avalúo de los bienes raíces que son propiedad de los integrantes del hogar se encuentra entre
                                                {{ \App\Http\Controllers\Utiles::ponePuntosNumero($data['bienesRaices'][0]->suma_min) }} y {{ \App\Http\Controllers\Utiles::ponePuntosNumero($data['bienesRaices'][0]->suma_max) }}.
                                                Esto corresponde al percentil {{ $data['bienesRaices'][0]->percentil }} del universo de avalúos presentes en el
                                                catastro de bienes raíces elaborado semestralmente por el Servicio de Impuestos Internos (SII).</span>
                                    </caption>
                                    <thead>
                                    <tr>
                                        <th style="text-align: center;">R.U.N. activador del medio</th>
                                        <th style="text-align: center;">Rol</th>
                                        <th style="text-align: center;">Rango Av. Fiscal</th>
                                        @if(! $banderaOcultaTd )
                                          <th style="text-align: center;">Rango Av. Fiscal Complemento</th>
                                        @endif
                                        <th style="text-align: center;">Comuna</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach( $data['bienesRaices'] as $items )
                                        <tr>
                                            <td style="text-align: center;"><strong>{{ \App\Http\Controllers\Utiles::ponePuntosNumero($items->run) }} - {{ $data['personalTitular']['dvTitular'] }}</strong></td>
                                            <td style="text-align: center;"><strong>{{ ($data['rol'][0] != \App\Constantes\Constante::ROL_OFUSCAR ) ? $items->rol : substr($items->rol,0,12).'--'}}</strong></td>
                                            <td style="text-align: center;"><strong>${{ \App\Http\Controllers\Utiles::ponePuntosNumero($items->monto_av_min) }} a ${{ \App\Http\Controllers\Utiles::ponePuntosNumero($items->monto_av_max) }}</strong></td>
                                            @if(! $banderaOcultaTd )
                                                @if( $items->monto_av_com_min == 0 && $items->monto_av_com_max == 0 )
                                                    <td style="text-align: center;"><strong>-</strong></td>
                                                @else
                                                    <td style="text-align: center;"><strong>$ {{ \App\Http\Controllers\Utiles::ponePuntosNumero($items->monto_av_com_min) }} a ${{ \App\Http\Controllers\Utiles::ponePuntosNumero($items->monto_av_com_max) }}</strong></td>
                                                @endif
                                            @endif
                                            <td style="text-align: center;"><strong>{{ $items->comuna_texto }}</strong></td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            @if( ! is_null($data['vehiculo']) )
                <div class="row no-gutters">
                    <div class="col-12 pl-1 mb-3">
                        <!-- Vehículos -->
                        <div class="card">
                            <h5 class="card-header alert-warning">
                                <img src="{{asset('assets/images/icons/vehiculo.png')}}" height="30px" />
                                Vehículos
                            </h5>
                            <div class="card-body">
                                <table class="table table-sm table-yellow">
                                    <caption>
                                        * Medio activado en base a la información entregada por SRCeI, con datos del {{ $data['vehiculo'][0]->agno_sii }}.<br>
                                        * La suma de las tasaciones de vehículos que son propiedad de los integrantes del hogar se encuentra entre {{ \App\Http\Controllers\Utiles::ponePuntosNumero($data['vehiculo'][0]->suma_min) }} y {{ \App\Http\Controllers\Utiles::ponePuntosNumero($data['vehiculo'][0]->suma_max) }}. Esto corresponde al percentil
                                        {{ $data['vehiculo'][0]->percentil }} de la distribución de tasación fiscal de vehículos livianos elaborada anualmente por el Servicio de Impuestos Internos (SII).
                                    </caption>
                                    <thead>
                                    <tr>
                                        <th style="text-align: center;">R.U.N. activador del medio</th>
                                        <th style="text-align: center;">Patente</th>
                                        <th style="text-align: center;">Marca</th>
                                        <th style="text-align: center;">Modelo</th>
                                        <th style="text-align: center;">Tipo de patente</th>
                                        <th style="text-align: center;">Rango Tasación</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach( $data['vehiculo'] as $items )
                                        <tr>
                                            <td style="text-align: center;"><strong>{{ \App\Http\Controllers\Utiles::ponePuntosNumero($items->run) }} - {{ $data['personalTitular']['dvTitular'] }}</strong></td>
                                            <td style="text-align: center;"><strong>{{ ($data['rol'][0] != \App\Constantes\Constante::ROL_OFUSCAR ) ? $items->patente : substr($items->patente,0,4).'--' }}</strong></td>
                                            <td style="text-align: center;"><strong>{{ $items->marca }}</strong></td>
                                            <td style="text-align: center;"><strong>{{ $items->modelo }}</strong></td>
                                            <td style="text-align: center;"><strong>{{ $items->tipo_patente_texto }}</strong></td>
                                            <td style="text-align: center;"><strong>${{ \App\Http\Controllers\Utiles::ponePuntosNumero($items->monto_tas_min) }} a ${{ \App\Http\Controllers\Utiles::ponePuntosNumero($items->monto_tas_max) }}</strong></td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            @if( ! is_null($data['pensioAlimentos']) )
                <div class="row no-gutters">
                    <div class="col-12 pr-1 mb-3">
                        <!-- Pensión de alimentos -->
                        <div class="card">
                            <h5 class="card-header alert-info">
                                <img src="{{asset('assets/images/icons/pension.png')}}" height="30px" />
                                Pensión de alimentos
                            </h5>
                            <div class="card-body">
                                <table class="table table-sm table-teal">
                                    <caption>
                                        {{--{{ $data['pensioAlimentos'][0]->version_cse }}--}}
                                        * En el hogar se reciben y/o pagan pensiones de alimentos reportadas mediante solicitud de complemento de información. Medio activado en base a la información disponible de ingresos para el padre/madre ausente. Datos de {{ $data['pensioAlimentos'][0]->fecha_etiqueta }} (publicada el [ 1 de {{ $data['pensioAlimentos'][0]->fecha_etiqueta }}]) entregados por el Ministerio de Desarrollo Social y Familia.
                                    </caption>
                                    <thead>
                                        <tr>
                                            <th style="text-align: center;">Pago o Recepción de Pensión de alim.</th>
                                            <th style="text-align: center;">R.U.N. Pagador/Receptor</th>
                                            <th style="text-align: center;">Nombre Causante</th>
                                            <th style="text-align: center;">Rango Monto Pactado</th>
                                            <th style="text-align: center;">Fecha Aprobación Solicitud en RSH</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach( $data['pensioAlimentos'] as $items )
                                        <tr>
                                            <td style="text-align: center;"><strong>{{ $items->tipo }}</strong></td>
                                            <td style="text-align: center;"><strong>{{ ($items->tipo == 'Recibe') ? \App\Http\Controllers\Utiles::ponePuntosNumero($items->rut_receptor) .'-'. \App\Http\Controllers\Utiles::dv($items->rut_receptor)  : \App\Http\Controllers\Utiles::ponePuntosNumero($items->rut_pagador) .'-'. \App\Http\Controllers\Utiles::dv($items->rut_pagador) }}</strong></td>
                                            <td style="text-align: center;"><strong>{{ $items->per_nombres }} {{ $items->per_apellidopaterno }} {{ $items->per_apellidomaterno }}</strong></td>
                                            <td style="text-align: center;"><strong>${{ \App\Http\Controllers\Utiles::ponePuntosNumero($items->rmin_monto_bruto_declarado) }} a ${{ \App\Http\Controllers\Utiles::ponePuntosNumero($items->rmax_monto_bruto_declarado) }}</strong></td>
                                            <td style="text-align: center;"><strong>{{ $items->fecha_aprobacion_solicitud }}</strong></td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            @if( ! is_null($data['pmFueraHogar']) )
                <div class="row no-gutters">
                    <div class="col-12 pl-1 mb-3">
                        <!-- Padre / Madre fuera de hogar -->
                        <div class="card">
                            <h5 class="card-header alert-danger">
                                <img src="{{asset('assets/images/icons/padremadre.png')}}" height="30px" />
                                Padre/Madre fuera de hogar
                            </h5>
                            <div class="card-body">
                                <table class="table table-sm table-red">
                                    <caption>
                                       {{-- {{ $data['pmFueraHogar'][0]->version_cse }}--}}
                                        * Medio activado en base a la información disponible de ingresos para el padre/madre ausente. Medio activado en base a la información disponible de ingresos para el padre/madre ausente. Datos de {{ $data['pmFueraHogar'][0]->fecha_etiqueta }} (publicada el [ 1 de {{ $data['pmFueraHogar'][0]->fecha_etiqueta }} ]) entregados por el Ministerio de Desarrollo Social y Familia.
                                    </caption>
                                    <thead>
                                    <tr>
                                        <th style="text-align: center;">R.U.N. hijo/a activador del medio</th>
                                        <th style="text-align: center;">R.U.N. Padre/Madre ausente</th>
                                        <th style="text-align: center;">Percentil Ingreso Padre/Madre</th>
                                        <th style="text-align: center;">Folio Actual RSH Padre/Madre</th>
                                        <th style="text-align: center;">Comuna Padre/Madre</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                        @foreach( $data['pmFueraHogar'] as $items )
                                            <tr>
                                                <td style="text-align: center;"><strong>{{ \App\Http\Controllers\Utiles::ponePuntosNumero($items->run) }} - {{ \App\Http\Controllers\Utiles::dv($items->run) }}</strong></td>
                                                <td style="text-align: center;"><strong>{{ \App\Http\Controllers\Utiles::ponePuntosNumero($items->run_padre_latente) }} - {{ \App\Http\Controllers\Utiles::dv($items->run_padre_latente) }}</strong></td>
                                                <td style="text-align: center;"><strong>{{ $items->percentil_pa }}</strong></td>
                                                <td style="text-align: center;"><strong>{{ ( $items->run_padre_latente = $items->run_padre_ausente1 ) ? $items->folio_hogar_1 : $items->folio_hogar_2 }}</strong></td>
                                                <td style="text-align: center;"><strong>{{ $items->etiqueta_comuna_pa }}</strong></td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            @if( is_null($data['salud']) && is_null($data['educacion']) && is_null($data['bienesRaices']) && is_null($data['vehiculo']) && is_null($data['pmFueraHogar']) && is_null($data['pensioAlimentos']) )
                <div class="row no-gutters">
                    <div class="col-12 pl-1 mb-3">
                        <div class="alert alert-info" role="alert">
                            <strong>El tramo del hogar asociado al R.U.N. consultado no está sujeto a "Evaluación de Medios",
                            ni presenta información en torno a Pensión de Alimentos.</strong>
                        </div>
                    </div>
                </div>

            @endif
</section>
<!-- fin Medios -->

        <!-- Graficos -->
        <section id="graficos" class="alert-success break-page">
            <div>
                <div class="row no-gutters justify-content-between mb-3">
                    <div class="col-7">
                        <h4 class="section-title">
                                <span>
                                  <i class="circle"><img src="{{asset('assets/images/icons/titular-b.png')}}" width="40px"/></i>
                                </span>
                            {{ $data['baseGeneralPlantilla']['titulo_item4'] }}
                        </h4>
                    </div>
                    <div class="col-5">
                        <div class="alert alert-primary">
                            {{ $data['baseGeneralPlantilla']['subtituloItem4'] }}
                        </div>
                    </div>
                </div>
                @if( $data['ocultarFilaIngreso']['ocultaTodotabla'] )
                <div class="row mb-3 mx-2 justify-content-center">
                    <div id="grafico-ingresos-titular" class="pie-chart"></div>
                </div>
                @else
                    <div class="row no-gutters">
                        <div class="col-12 pl-1 mb-3">
                            <div class="alert alert-info" role="alert">
                                <strong>No se observan ingresos para el titular del dato.</strong>
                            </div>
                        </div>
                    </div>
                @endif
                {{--                            <table class="table table-sm table-borderless">--}}
                {{--                                <thead>--}}
                {{--                                <tr>--}}
                {{--                                    <th>Estadísticas</th>--}}
                {{--                                    <th>Trabajo</th>--}}
                {{--                                    <th>Pensión</th>--}}
                {{--                                    <th>Capital</th>--}}
                {{--                                    <th>Ingreso total</th>--}}
                {{--                                </tr>--}}
                {{--                                </thead>--}}
                {{--                                <tbody>--}}
                {{--                                <tr>--}}
                {{--                                    <td>Promedio 12 meses</td>--}}
                {{--                                    <td>$0 a $50.000</td>--}}
                {{--                                    <td>$0 a $50.000</td>--}}
                {{--                                    <td>$0 a $50.000</td>--}}
                {{--                                    <td>$0 a $50.000</td>--}}
                {{--                                </tr>--}}
                {{--                                </tbody>--}}
                {{--                            </table>--}}
            </div>
        </section>
        <section id="graficos-hogar" class="alert-success break-page">
            <div>
                <div class="row no-gutters justify-content-between mb-3">
                    <div class="col-7">
                        <h4 class="section-title">
                                <span>
                                  <i class="circle"><img src="{{asset('assets/images/icons/titular-b.png')}}" width="40px"/></i>
                                </span>
                            {{ $data['baseGeneralPlantilla']['titulo_item5'] }}
                        </h4>
                    </div>
                    <div class="col-5">
                        <div class="alert alert-primary">
                            {{ $data['baseGeneralPlantilla']['subtituloItem5'] }}
                        </div>
                    </div>
                </div>
                @if( $data['ocultarFilaIngreso']['ocultaTodotabla'] )
                    <div class="row mb-3 mx-2 justify-content-center">
                        <div id="grafico-ingresos-hogar">
                        </div>
                    </div>
                @else
                    <div class="row no-gutters">
                        <div class="col-12 pl-1 mb-3">
                            <div class="alert alert-info" role="alert">
                                <strong>No se observan ingresos para el hogar del titular del dato.</strong>
                            </div>
                        </div>
                    </div>
                @endif
                {{--                            <table class="table table-sm table-borderless">--}}
                {{--                                <thead>--}}
                {{--                                <tr>--}}
                {{--                                    <th>Estadísticas</th>--}}
                {{--                                    <th>Trabajo</th>--}}
                {{--                                    <th>Pensión</th>--}}
                {{--                                    <th>Capital</th>--}}
                {{--                                    <th>Ingreso total</th>--}}
                {{--                                </tr>--}}
                {{--                                </thead>--}}
                {{--                                <tbody>--}}
                {{--                                <tr>--}}
                {{--                                    <td>Promedio 12 meses</td>--}}
                {{--                                    <td>$0 a $50.000</td>--}}
                {{--                                    <td>$0 a $50.000</td>--}}
                {{--                                    <td>$0 a $50.000</td>--}}
                {{--                                    <td>$0 a $50.000</td>--}}
                {{--                                </tr>--}}
                {{--                                </tbody>--}}
                {{--                            </table>--}}
            </div>
        </section>
        <!-- fin Graficos-->

<!-- Otros -->
<section id="otros" class="p-0 break-page">
    <h4 class="section-title p-3">
        <span><i class="circle"><img src="{{asset('assets/images/icons/otros-b.png')}}" width="35px" height="25px"/></i></span>
            {{ $data['baseGeneralPlantilla']['titulo_item6'] }}
    </h4>
<div class="alert-warning p-3">
    <h4>Composición del hogar</h4>
    <table class="table table-sm table-striped table-light border-warning small">
    <caption class="alert-warning">
        <p><strong>* Edad calculada al momento de calcular la CSE.</strong></p>
        @if( $data['ocultarFilaHogar']['discapacidad'] == 'Sin discapacidad o dependencia' )
            <p><strong>* Ninguna de las personas que integra el hogar se encuentra en situación de dependencia/discapacidad.</strong></p>
        @else
            @if( $data['ocultarFilaHogar']['discapacidad'])
                <p><strong>* {{ $data['ocultarFilaHogar']['discapacidadTxt'] }}.</strong></p>
            @endif
            @if( $data['ocultarFilaHogar']['fuente'])
                <p><strong>* {{ $data['ocultarFilaHogar']['fuenteTxt'] }}.</strong></p>
            @endif
        @endif
    </caption>
        <thead>
            <tr>
                <th style="text-align: center;">R.U.N.</th>
                <th style="text-align: center;">Nombre</th>
                <th style="text-align: center;">Parentesco con Jefe/a de Hogar</th>
                <th style="text-align: center;">Edad</th>
                <th width="25%" style="text-align: center;">Fecha incorporación al folio (RSH)</th>
                <th style="text-align: center;">Folio anterior</th>
                @if( ! $data['ocultarFilaHogar']['discapacidad'] )
                <th style="text-align: center;">Dependencia/Discapacidad</th>
                @endif
                <th style="text-align: center;">Coeficiente</th>
                @if( ! $data['ocultarFilaHogar']['fuente'] )
                <th width="25%" style="text-align: center;">Fuente (dependencia)</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @foreach( $data['composicionHogar'] as $items )
            <tr>
                <td style="text-align: center;">{{ \App\Http\Controllers\Utiles::ponePuntosNumero($items->run_falso) }} - {{ $items->per_dv }}</td>
                <td style="text-align: center;">{{ $items->per_nombres }} {{ $items->per_apellidopaterno }} {{ $items->per_apellidomaterno }}</td>
                <td style="text-align: center;">{{ $items->per_fic_parentesco }}</td>
                <td style="text-align: center;">{{ $items->edad }}</td>
                <td style="text-align: center;">{{ $items->fecha_folio_actual }}</td>
                <td style="text-align: center;">{{ $items->folio_falso_anterior }}</td>
                @if( ! $data['ocultarFilaHogar']['discapacidad'] )
                    <td style="text-align: center;">{{ $items->etiqueta_dependencia }}</td>
                @endif
                <td style="text-align: center;">{{ $items->coefic }}</td>
                @if( ! $data['ocultarFilaHogar']['fuente'] )
                    <td style="text-align: center;">{{ $items->etiqueta_fuente_dependencia }}</td>
                @endif
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="row no-gutters">
        <div class="col-4">
            <table class="table table-sm table-striped table-light border-warning">
                <thead>
                <tr>
                    <th style="text-align: center;">Total integrantes</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td style="text-align: center;" ><h4>{{ ( is_null($data['composicionHogar']) ) ? 0 : count($data['composicionHogar']) }}</h4></td>
                </tr>
                </tbody>
            </table>
        </div>
        <div class="col-4 px-2">
            <table class="table table-sm table-striped table-light border-warning">
                <thead>
                <tr>
                    <th style="text-align: center;">Índice de Necesidades</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td style="text-align: center;" ><h4>{{ \App\Http\Controllers\Utiles::poneComaNumero($data['composicionHogarCSE']['indice']) }}</h4></td>
                </tr>
                </tbody>
            </table>
        </div>
        <div class="col-4">
            <table class="table table-sm table-striped table-light border-warning">
                <thead>
                <tr>
                    <th style="text-align: center;">Suavización de Movimiento de Tramo</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td style="text-align: center;" > {{ ( $data['composicionHogar'][0]->contador == '1') ? 'Si' : ( $data['composicionHogar'][0]->contador == '2') ? 'Si' : 'No'  }} , se utiliza CSE publicada el 01 de {{ $data['composicionHogar'][0]->fecha_etiqueta }}.</td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>

    <h4>Solicitudes</h4>
    <table class="table table-sm table-striped table-light border-warning small">
        <thead>
            <tr>
            <th style="text-align: center;">R.U.N.</th>
            <th style="text-align: center;">Folio</th>
            <th style="text-align: center;">Nº de Solicitud</th>
            <th style="text-align: center;">Estado</th>
            <th style="text-align: center;">Sub estado</th>
            <th style="text-align: center;">Comuna</th>
            <th style="text-align: center;">Fecha creación</th>
            <th style="text-align: center;">Fecha modificación</th>
            <th style="text-align: center;">Tipo</th>
            </tr>
        </thead>
        <tbody>
            @if( is_null($data['solicitudes']) )
            <tr> <td colspan="9" style="text-align: center;"><strong> No posee Información </strong></td>  </tr>
            @else
                @foreach( $data['solicitudes'] as $items )
                <tr>
                    <td style="text-align: center;">{{ \App\Http\Controllers\Utiles::ponePuntosNumero($data['personalTitular']['runTitular']) }} - {{ $data['personalTitular']['dvTitular'] }}</td>
                    <td style="text-align: center;">{{ $items->nuevofolio }}</td>
                    <td style="text-align: center;">{{ $items->id_solicitud }}</td>
                    <td style="text-align: center;">{{ $items->nombre_estado }}</td>
                    <td style="text-align: center;">{{ $items->nombre_sub_estado }}</td>
                    <td style="text-align: center;">{{ $items->com_descripcion }}</td>
                    <td style="text-align: center;">{{ $items->fecha_creacion }}</td>
                    <td style="text-align: center;">{{ $items->fecha_modificacion }}</td>
                    <td style="text-align: center;">{{ $items->tipo_solicitud }}</td>
                </tr>
                @endforeach
            @endif
        </tbody>
    </table>
</div>
</section>
<!-- fin Otros -->
</main>
<!-- footer -->
<footer id="footer" class="p-0 break-page">
    <hr />
    <div class="row no-gutters justify-content-between">
        <div>
            <p>Ministerio de Desarrollo Social y Familia</p>
            <table>
                <tbody>
                    <tr>
                        <td style="width: 40px; background-color: blue; line-height: 10px;">.</td>
                        <td style="width: 45px; background-color: red; line-height: 10px;">.</td>
                        <td></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div>
            <span>Reporte de <strong>Calificación Socioeconómica</strong></span>
        </div>
    </div>
</footer>
<!-- fin footer -->
</body>
</html>
