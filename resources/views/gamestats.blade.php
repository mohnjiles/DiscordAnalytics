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
        <div class="col-xs-12">
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
        <div class="col-xs-6">
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


            function drawChart() {
                var data = google.visualization.arrayToDataTable([
                    ['Game Name', 'Seconds', {role: 'tooltip'}],
                    ['Overwatch', {{$overwatchSeconds}}, '{{$overwatchReadable}}'],
                    ['FFXIV', {{$ffxivSeconds}}, '{{$ffxivReadable}}'],
                    ['Hearthstone', {{$hearthstoneSeconds}}, '{{$hearthstoneReadable}}'],
                    ['Heroes of the Storm', {{$hotsSeconds}}, '{{$hotsReadable}}'],
                    ['Guild Wars 2', {{$gw2Seconds}}, '{{$gw2Readable}}']
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

        }, 100);
    </script>

@endsection