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
            <div id="hafTime">
                {{-- google chart goes here --}}
            </div>
        </div>
    </div>


    <script type="text/javascript">
        setTimeout(function () {
            google.charts.load("43", {packages: ["corechart"]});
            google.charts.setOnLoadCallback(drawChart);

            function drawChart() {
                var data = google.visualization.arrayToDataTable([
                    ['Channel Name', 'Seconds', {role: 'tooltip'}],
                    ['High Air Flow', {{$hafSeconds}}, 'High Air Flow:\n{{$hafReadable}}'],
                    ['General', {{$generalSeconds}}, 'General:\n{{$generalReadable}}'],
                    ['FFXIV', {{$ffxivSeconds}}, 'FFXIV:\n{{$ffxivReadable}}'],
                    ['AFK', {{$afkSeconds}}, 'AFK:\n{{$afkReadable}}'],
                ]);

                var options = {
                    title: 'Channel Time',
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

                var chart = new google.visualization.PieChart(document.getElementById('hafTime'));
                chart.draw(data, options);
            }

        }, 100);
    </script>

@endsection