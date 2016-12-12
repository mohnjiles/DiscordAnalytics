@extends('layout')

@section('content')

    <div class="row">
        <div class="col-xs-12 text-center">
            <h1>Discord Analytics</h1>
            <h6>Game Stats</h6>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12 text-center">
            <h3><a href="../">Channel Stats</a> | Game Stats</h3>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-10 col-xs-offset-2">
            <div id="gametime">
                {{-- google chart goes here --}}
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-6">
            <div id="overwatchTime">

            </div>
        </div>
        <div class="col-xs-6">
            <div id="ffxivTime">

            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-4">
            <div id="hotsTime">

            </div>
        </div>
        <div class="col-xs-4">
            <div id="gw2Time">

            </div>
        </div>
        <div class="col-xs-4">
            <div id="lmiTime">

            </div>
        </div>
    </div>

    <script type="text/javascript">
        setTimeout(function () {
            google.charts.load("43", {packages: ["corechart"]});
            google.charts.setOnLoadCallback(drawChart);
            google.charts.setOnLoadCallback(drawOverwatchChart);
            google.charts.setOnLoadCallback(drawFfxivChart);
            google.charts.setOnLoadCallback(drawLmiChart);
            google.charts.setOnLoadCallback(drawGw2Chart);
            google.charts.setOnLoadCallback(drawHotsChart);


            function drawChart() {
                var data = google.visualization.arrayToDataTable([
                    ['Game Name', 'Seconds', {role: 'tooltip'}],
                    ['Overwatch', {{$overwatchSeconds}}, 'Overwatch:\n{{$overwatchReadable}}'],
                    ['FFXIV', {{$ffxivSeconds}}, 'FFXIV:\n{{$ffxivReadable}}'],
                    ['Hearthstone', {{$hearthstoneSeconds}}, 'Hearthstone:\n{{$hearthstoneReadable}}'],
                    ['Heroes of the Storm', {{$hotsSeconds}}, 'Heroes of the Storm:\n{{$hotsReadable}}'],
                    ['Guild Wars 2', {{$gw2Seconds}}, 'Guild Wars 2:\n{{$gw2Readable}}']
                ]);

                var options = {
                    title: 'Game Time',
                    height: 600,
                    backgroundColor: {
                        fill: "#252525",
                        stroke: "#252525"
                    },
                    titleTextStyle: {
                        color: '#ffffff',
                        fontName: 'Roboto'
                    },
                    pieHole: 0.4,
                    is3D: true,
                    legend: {
                        textStyle: {
                            color: "#FFFFFF",
                            fontName: 'Roboto'
                        }
                    }
                };

                var chart = new google.visualization.PieChart(document.getElementById('gametime'));
                chart.draw(data, options);
            }

            function drawOverwatchChart() {
                var data = google.visualization.arrayToDataTable([
                    ['Player Name', 'Seconds', {role: 'tooltip'}],
                    @foreach($overwatch->times as $key=>$time)
                    ['{{$key}}', {{$time}}, '{{$key}}:\n{{$overwatch->readable[$key]}}'],
                    @endforeach
                ]);

                var options = {
                    title: 'Time Spent in Overwatch',
                    height: 400,
                    backgroundColor: {
                        fill: "#252525",
                        stroke: "#252525"
                    },
                    titleTextStyle: {
                        color: '#ffffff',
                        fontName: 'Roboto'
                    },
                    pieHole: 0.4,
                    is3D: true,
                    legend: {
                        textStyle: {
                            color: "#FFFFFF",
                            fontName: 'Roboto'
                        }
                    }
                };

                var chart = new google.visualization.PieChart(document.getElementById('overwatchTime'));
                chart.draw(data, options);
            }

            function drawFfxivChart() {
                var data = google.visualization.arrayToDataTable([
                    ['Player Name', 'Seconds', {role: 'tooltip'}],
                    @foreach($ffxiv->times as $key=>$time)
                    ['{{$key}}', {{$time}}, '{{$key}}:\n{{$ffxiv->readable[$key]}}'],
                    @endforeach
                ]);

                var options = {
                    title: 'Time Spent in FFXIV',
                    height: 400,
                    backgroundColor: {
                        fill: "#252525",
                        stroke: "#252525"
                    },
                    titleTextStyle: {
                        color: '#ffffff',
                        fontName: 'Roboto'
                    },
                    pieHole: 0.4,
                    is3D: true,
                    legend: {
                        textStyle: {
                            color: "#FFFFFF",
                            fontName: 'Roboto'
                        }
                    }
                };

                var chart = new google.visualization.PieChart(document.getElementById('ffxivTime'));
                chart.draw(data, options);
            }

            function drawLmiChart() {
                var data = google.visualization.arrayToDataTable([
                    ['Player Name', 'Seconds', {role: 'tooltip'}],
                    @foreach($lmi->times as $key=>$time)
                    ['{{$key}}', {{$time}}, '{{$key}}:\n{{$lmi->readable[$key]}}'],
                    @endforeach
                ]);

                var options = {
                    title: 'Time Spent in LogMeIn',
                    height: 400,
                    backgroundColor: {
                        fill: "#252525",
                        stroke: "#252525"
                    },
                    titleTextStyle: {
                        color: '#ffffff',
                        fontName: 'Roboto'
                    },
                    pieHole: 0.4,
                    is3D: true,
                    legend: {
                        textStyle: {
                            color: "#FFFFFF",
                            fontName: 'Roboto'
                        }
                    }
                };

                var chart = new google.visualization.PieChart(document.getElementById('lmiTime'));
                chart.draw(data, options);
            }

            function drawGw2Chart() {
                var data = google.visualization.arrayToDataTable([
                    ['Player Name', 'Seconds', {role: 'tooltip'}],
                        @foreach($gw2->times as $key=>$time)
                    ['{{$key}}', {{$time}}, '{{$key}}:\n{{$gw2->readable[$key]}}'],
                    @endforeach
                ]);

                var options = {
                    title: 'Time Spent in Guild Wars 2',
                    height: 400,
                    backgroundColor: {
                        fill: "#252525",
                        stroke: "#252525"
                    },
                    titleTextStyle: {
                        color: '#ffffff',
                        fontName: 'Roboto'
                    },
                    pieHole: 0.4,
                    is3D: true,
                    legend: {
                        textStyle: {
                            color: "#FFFFFF",
                            fontName: 'Roboto'
                        }
                    }
                };

                var chart = new google.visualization.PieChart(document.getElementById('gw2Time'));
                chart.draw(data, options);
            }

            function drawHotsChart() {
                var data = google.visualization.arrayToDataTable([
                    ['Player Name', 'Seconds', {role: 'tooltip'}],
                        @foreach($hots->times as $key=>$time)
                    ['{{$key}}', {{$time}}, '{{$key}}:\n{{$hots->readable[$key]}}'],
                    @endforeach
                ]);

                var options = {
                    title: 'Time Spent in Heroes of the Storm',
                    height: 400,
                    backgroundColor: {
                        fill: "#252525",
                        stroke: "#252525"
                    },
                    titleTextStyle: {
                        color: '#ffffff',
                        fontName: 'Roboto'
                    },
                    pieHole: 0.4,
                    is3D: true,
                    legend: {
                        textStyle: {
                            color: "#FFFFFF",
                            fontName: 'Roboto'
                        }
                    }
                };

                var chart = new google.visualization.PieChart(document.getElementById('hotsTime'));
                chart.draw(data, options);
            }

        }, 100);
    </script>

@endsection