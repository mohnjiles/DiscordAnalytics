@extends('layout')

@section('content')

    <div class="row">
        <div class="col-xs-12 text-center">
            <h1>Voice Channel Stats</h1>
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
        $(function() {
            if (window.location.href.indexOf("channelstats") !== -1) {
                $("a[href='channelstats']").parent().addClass('active');
            } else if (window.location.href.indexOf("voicechannels") !== -1) {
                $("a[href='voicechannels']").parent().addClass('active');
            } else if (window.location.href.indexOf("gamestats") !== -1) {
                $("a[href='gamestats']").parent().addClass('active');
            }
        });
    </script>

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
                    ['Overwatch', {{$overwatchSeconds}}, 'Overwatch:\n{{$overwatchReadable}}'],
                    ['Westworld', {{$westworldSeconds}}, 'Westworld:\n{{$westworldReadable}}'],
                    ['HotS', {{$hotsSeconds}}, 'HotS:\n{{$hotsReadable}}'],
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