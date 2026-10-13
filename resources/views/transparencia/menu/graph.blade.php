<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <style>
            .pie-chart {
                width: 600px;
                height: 400px;
                margin: 0 auto;
            }
        </style>
        {{-- make sure you are using http, and not https --}}
        <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

        <script type="text/javascript">
            function init() {
                google.charts.load('42',{'packages':['corechart','line']});
                //google.charts.load('current', {'packages':['corechart']});
                var interval = setInterval(function() {
                    if ( google.visualization !== undefined && google.visualization.DataTable !== undefined
                        && google.visualization.PieChart !== undefined
                        && google.visualization.LineChart !== undefined){
                        clearInterval(interval);
                        //drawLines();
                        //AreaChartB();
                        //drawVisualization();
                        //drawPie();
                        drawChartB();
                        window.status = 'ready';
                    }
                }, 500);
                //google.charts.setOnLoadCallback(drawChartB);
                //google.charts.load({'packages':['corechart']});
                //google.charts.setOnLoadCallback(AreaChart);
            }

            function drawPie() {
                var data = new google.visualization.DataTable();
                data.addColumn('string', 'Tea');
                data.addColumn('number', 'Populartiy');
                data.addRows([
                    ['Bourn Tea', 40],
                    ['Lemon Tea', 22],
                    ['Green Tea', 26],
                    ['Black Tea', 35], // Below limit.
                    ['Special Tea', 35] // Below limit.
                ]);

                var options = {
                    title: 'Popularity of Types of Tea',
                    sliceVisibilityThreshold: .2
                };

                var chart = new google.visualization.PieChart(document.getElementById('pie_div'));
                chart.draw(data, options);
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

                data.addRows([[ 'May 2020',  porcentaceS(0)]]);
                data.addRows([[ 'Jun 2020',  porcentaceS(5)]]);
                data.addRows([[ 'Jul 2020',  porcentaceS(5)]]);
                data.addRows([[ 'Aug 2020',  porcentaceS(0)]]);
                data.addRows([[ 'Sep 2020',  porcentaceS(0)]]);
                data.addRows([[ 'Oct 2020',  porcentaceS(4)]]);
                data.addRows([[ 'Nov 2020',  porcentaceS(1)]]);
                data.addRows([[ 'Dec 2020',  porcentaceS(2)]]);
                data.addRows([[ 'Jan 2021',  porcentaceS(2)]]);
                data.addRows([[ 'Feb 2021',  porcentaceS(3)]]);
                data.addRows([[ 'Mar 2021',  porcentaceS(2)]]);
                data.addRows([[ 'Apr 2021',  porcentaceS(7)]]);

                var options = {
                    title: 'Calificación Socioeconómica (Tramo porcentual de menores ingresos o mayor vulnerabilidad sociedaeconómica al que permanece el hogar)',
                    /*titleTextStyle : {color: "grey", fontSize: 16},*/
                    width: 700,
                    height: 400,
                    fontSize: 10,
                    /*colors: ['#262626'],*/
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

            function AreaChart() {
                var data = new google.visualization.DataTable();
                data.addColumn('date', 'Date');
                data.addColumn('number', 'Pensiones');
                data.addColumn('number', 'Trabajo');
                data.addColumn('number', 'Capital');

                /*data.addRows([[ new Date('2021/01'),  50000,400000,0]]);
                data.addRows([[ new Date('2021/02'),  100000,403000,300000]]);
                data.addRows([[ new Date('2021/03'),  1000000,400000,100000]]);
                data.addRows([[ new Date('2021/04'),  1000000,400000,100000]]);
                data.addRows([[ new Date('2021/05'),  1050000,400000,150000]]);
                data.addRows([[ new Date('2021/06'),  1000000,400000,50000]]);
                data.addRows([[ new Date('2021/07'),  1000000,1400000,50000]]);
                data.addRows([[ new Date('2021/08'),  1000000,400000,150000]]);
                data.addRows([[ new Date('2021/09'),  1000000,400000,50000]]);
                data.addRows([[ new Date('2021/10'),  1000000,400000,50000]]);
                data.addRows([[ new Date('2021/11'),  1000000,400000,50000]]);
                data.addRows([[ new Date('2021/12'),  100000,400000,500000]]);*/
                data.addRows([[ new Date('2021/01'),  null,null,null]]);
                data.addRows([[ new Date('2021/02'),  null,null,null]]);
                data.addRows([[ new Date('2021/03'),  null,null,null]]);
                data.addRows([[ new Date('2021/04'),  null,null,null]]);
                data.addRows([[ new Date('2021/05'),  null,null,null]]);
                data.addRows([[ new Date('2021/06'),  null,null,null]]);
                data.addRows([[ new Date('2021/07'),  null,null,null]]);
                data.addRows([[ new Date('2021/08'),  null,null,null]]);
                data.addRows([[ new Date('2021/09'),  null,null,null]]);
                data.addRows([[ new Date('2021/10'),  null,null,null]]);
                data.addRows([[ new Date('2021/11'),  null,null,null]]);
                data.addRows([[ new Date('2021/12'),  null,null,null]]);

                let options = {
                    title: 'Evolución del promedio eb 12 meses de ingresos del hogar',
                    subtitle: 'Meses/ Pesos',
                    isStacked:true,
                    width: 900,
                    height: 500,
                    series: {
                        0: { axis: 'Pensiones', color: '#004488' }, // Bind series 0 to an axis named 'distance'.
                        1: { axis: 'Trabajo', color: '#BB5566' } ,
                        2: { axis: 'Capital', color: '#DDAA33' }// Bind series 1 to an axis named 'brightness'.
                    },
                    hAxis: {
                        title: 'Mes/Año',
                        gridlines: { count: 15 },
                        format: 'MMM yyyy',
                    },
                    vAxis: {
                        title: 'Ingresos',
                        baseline: 0,
                        minValue: 0,
                        maxValue: 2000000,
                    },
                };

                let chart = new google.visualization.AreaChart(document.getElementById('lines_div'));

                chart.draw(data, options);

            }
            function AreaChartB() {
                var data = new google.visualization.DataTable();
                data.addColumn('date', 'Date');
                data.addColumn('number', 'Pensiones');
                data.addColumn('number', 'Trabajo');
                data.addColumn('number', 'Capital');
                data.addColumn('number');
                data.addColumn('number');
                data.addColumn('number');
                data.addRows([[ new Date('2021/01'),  50000,400000,0,null,null,null]]);
                data.addRows([[ new Date('2021/02'),  100000,403000,300000,null,null,null]]);
                data.addRows([[ new Date('2021/03'),  1000000,400000,100000,null,null,null]]);
                data.addRows([[ new Date('2021/04'),  1000000,400000,100000,null,null,null]]);
                data.addRows([[ new Date('2021/05'),  1050000,400000,150000,null,null,null]]);
                data.addRows([[ new Date('2021/06'),  1000000,400000,50000,null,null,null]]);
                data.addRows([[ new Date('2021/07'),  1000000,1400000,50000,null,null,null]]);
                data.addRows([[ new Date('2021/08'),  1000000,400000,150000,null,null,null]]);
                data.addRows([[ new Date('2021/09'),  1000000,400000,50000,null,null,null]]);
                data.addRows([[ new Date('2021/10'),  1000000,400000,50000,null,null,null]]);
                data.addRows([[ new Date('2021/11'),  1000000,400000,50000,null,null,null]]);
                data.addRows([[ new Date('2021/12'),  100000,400000,1500000,100000,400000,1500000]]);
                /*data.addRows([[ new Date('2021/01'),  null,null,null]]);
                data.addRows([[ new Date('2021/02'),  null,null,null]]);
                data.addRows([[ new Date('2021/03'),  null,null,null]]);
                data.addRows([[ new Date('2021/04'),  null,null,null]]);
                data.addRows([[ new Date('2021/05'),  null,null,null]]);
                data.addRows([[ new Date('2021/06'),  null,null,null]]);
                data.addRows([[ new Date('2021/07'),  null,null,null]]);
                data.addRows([[ new Date('2021/08'),  null,null,null]]);
                data.addRows([[ new Date('2021/09'),  400000,null,null]]);
                data.addRows([[ new Date('2021/10'),  0,0,200000]]);
                data.addRows([[ new Date('2021/11'),  null,null,null]]);
                data.addRows([[ new Date('2021/12'),  null,null,null]]);*/

                let options = {
                    title: 'Evolución del promedio eb 12 meses de ingresos del hogar',
                    subtitle: 'Meses/ Pesos',
                    isStacked:true,
                    width: 900,
                    height: 500,
                    seriesType: 'area',
                    series: {
                        0: {targetAxisIndex:0, axis: 'Pensiones', color: '#004488' },
                        1: {targetAxisIndex:0, axis: 'Trabajo', color: '#BB5566' } ,
                        2: {targetAxisIndex:0, axis: 'Capital', color: '#DDAA33' },
                        3: {targetAxisIndex:1, color: 'white'} ,
                        4: {targetAxisIndex:1, color: 'white'} ,
                        5: {targetAxisIndex:1, color: 'white'},
                    },
                    hAxis: {
                        title: 'Mes/Año',
                        gridlines: { count: 15 },
                        format: 'MMM yyyy',
                    },
                    vAxis: {
                        // Adds titles to each axis.
                        0: {title: 'Temps (Celsius)'},
                        1: {title: 'Daylight'}
                    },
                };
                var container = document.getElementById('lines_div');
                let chart = new google.visualization.ComboChart(container);
                /*google.visualization.events.addListener(chart, 'ready', function () {
                    Array.prototype.forEach.call(container.getElementsByTagName('rect'), function(rect, index) {
                        console.log(rect);
                    });
                });*/

                chart.draw(data, options);

            }
            function drawChartB() {


                var data = new google.visualization.DataTable();
                data.addColumn('date', 'Date');
                data.addColumn('number', 'Pensiones');
                data.addColumn('number', 'Trabajo');
                data.addColumn('number', 'Capital');
                data.addColumn('number');
                data.addColumn('number');
                data.addColumn('number');
                data.addRows([[ new Date('2021/01'),  50000,400000,400000,null,null,null]]);
                data.addRows([[ new Date('2021/02'),  100000,403000,300000,null,null,null]]);
                data.addRows([[ new Date('2021/03'),  1000000,400000,100000,null,null,null]]);
                data.addRows([[ new Date('2021/04'),  1000000,400000,100000,null,null,null]]);
                data.addRows([[ new Date('2021/05'),  1050000,400000,150000,null,null,null]]);
                data.addRows([[ new Date('2021/06'),  1000000,400000,50000,null,null,null]]);
                data.addRows([[ new Date('2021/07'),  1000000,1400000,50000,null,null,null]]);
                data.addRows([[ new Date('2021/08'),  1000000,400000,150000,null,null,null]]);
                data.addRows([[ new Date('2021/09'),  1000000,400000,50000,null,null,null]]);
                data.addRows([[ new Date('2021/10'),  1000000,400000,50000,null,null,null]]);
                data.addRows([[ new Date('2021/11'),  1000000,400000,50000,null,null,null]]);
                data.addRows([[ new Date('2021/12'),  100000,70000,1500000,100000,70000,1500000]]);

                var options = {
                    title: 'Evolución del promedio eb 12 meses de ingresos del hogar',
                    subtitle: 'Meses/ Pesos',
                    isStacked:true,
                    width: 700,
                    height: 400,
                    // Gives each series an axis that matches the vAxes number below.
                    series: {
                        0: {targetAxisIndex:0, axis: 'Pensiones', color: '#004488' },
                        1: {targetAxisIndex:0, axis: 'Trabajo', color: '#BB5566' } ,
                        2: {targetAxisIndex:0, axis: 'Capital', color: '#DDAA33' },
                        3: {targetAxisIndex:1, color: '#fafafa'} ,
                        4: {targetAxisIndex:1, color: '#fafafa'} ,
                        5: {targetAxisIndex:1, color: '#fafafa'},
                    },
                    //fontSize: 10,
                    vAxes: {
                        // Adds titles to each axis.
                        0: {title: 'Promedio ingreso 12 meses (CLP$)',
                            //ticks: [500000,1000000,1500000,2000000,2500000],
                            textStyle: {
                                fontSize: 10 // or the number you want
                            },
                            maxValue:data.maxValue,
                        },
                        1: {
                            //title: 'Daylight',
                            textStyle: {
                                fontSize: 10 // or the number you want
                            },
                            ticks: [{v:100000, f:'100000'},{v:170000, f:'70000'},{v:1670000, f:'1670000'}],
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
                            max: 3000000
                        }
                    }
                };
                var container = document.getElementById('lines_div');
                var chart = new google.visualization.AreaChart(container);
                chart.draw(data, options);

            }
        </script>
        </head>

<body onload="init()">

<h2 style="text-align: center; ">Generate PDF with Graph Using Snappy in Laravel - NiceSnippets.com</h2>
<div id="pie_div" class="pie-chart"></div>
<div id="lines_div" class="pie-chart"></div>

</body>
</html>
