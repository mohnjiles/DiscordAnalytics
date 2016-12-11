@extends('layout')

@section('content')

    <div class="row">
        <div class="col-xs-12 text-center">
            <h1>Discord Analytics</h1>
            <h6>JT be creepin'</h6>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 text-center">
            <h3>Channel Stats | <a href="gamestats">Game Stats</a></h3>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12">
            <div id="mostposts">
                {{-- google chart goes here --}}
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12 text-center">
            <h1>Channel Stats</h1>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-6 text-center">
            <h3>General</h3>
            <div id="generalChart">

            </div>
        </div>
        <div class="col-xs-6 text-center">
            <h3>NSFW</h3>
            <div id="nsfwChart">

            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-6 text-center">
            <h3>Westworld</h3>
            <div id="westworldChart">

            </div>
        </div>
        <div class="col-xs-6 text-center">
            <h3>FFXIV</h3>
            <div id="ffxivChart">

            </div>
        </div>
    </div>








    <script type="text/javascript">
        setTimeout(function () {
            google.charts.load("43", {packages: ["corechart"]});
            google.charts.setOnLoadCallback(drawChart);
            google.charts.setOnLoadCallback(drawGeneralChart);
            google.charts.setOnLoadCallback(drawNsfwChart);
            google.charts.setOnLoadCallback(drawFfxivChart);
            google.charts.setOnLoadCallback(drawWestworldChart);


            function drawChart() {
                var data = google.visualization.arrayToDataTable([
                    ['Username', 'Count', {role: 'style'}],
                        @foreach($channelStats as $stat)
                    ['{{$stat->user}}', {{$stat->theCount}}, 'color: #2D882D'],
                    @endforeach
                ]);

                var options = {
                    title: 'Most Posts Overall',
                    bar: {groupWidth: '75%'},
                    height: 500,
                    legend: {position: 'none'},
                    backgroundColor: "#252525",
                    titleTextStyle: {
                        color: '#ffffff',
                        fontName: 'Roboto'
                    },
                    vAxis: {
                        textStyle: {
                            color: '#ffffff',
                            fontName: 'Roboto'
                        }
                    },
                    hAxis: {
                        textStyle: {
                            color: '#ffffff',
                            fontName: 'Roboto'
                        }
                    }
                };

                var chart = new google.visualization.BarChart(document.getElementById('mostposts'));
                chart.draw(data, options);
            }

            function drawGeneralChart() {
                var data = google.visualization.arrayToDataTable([
                    ['Username', 'Count', {role: 'style'}],
                        @foreach($general as $stat)
                    ['{{$stat->user}}', {{$stat->count}}, 'color: #2D882D'],
                    @endforeach
                ]);

                var options = {
                    title: 'Most Posts',
                    bar: {groupWidth: '75%'},
                    height: 600,
                    legend: {position: 'none'},
                    backgroundColor: "#252525",
                    titleTextStyle: {
                        color: '#ffffff',
                        fontName: 'Roboto'
                    },
                    vAxis: {
                        textStyle: {
                            color: '#ffffff',
                            fontName: 'Roboto'
                        }
                    },
                    hAxis: {
                        textStyle: {
                            color: '#ffffff',
                            fontName: 'Roboto'
                        }
                    }
                };

                var chart = new google.visualization.ColumnChart(document.getElementById('generalChart'));
                chart.draw(data, options);
            }

            function drawNsfwChart() {
                var data = google.visualization.arrayToDataTable([
                    ['Username', 'Count', {role: 'style'}],
                    @foreach($nsfw as $key=>$stat)
                        @if ($key == $nsfw->count() -1)
                            ['{{$stat->user}}', {{$stat->count}}, 'color: #AA3838'],
                        @else
                            ['{{$stat->user}}', {{$stat->count}}, 'color: #2D882D'],
                        @endif
                    @endforeach
                ]);

                var options = {
                    title: 'Most Posts',
                    bar: {groupWidth: '75%'},
                    height: 500,
                    legend: {position: 'none'},
                    backgroundColor: "#252525",
                    titleTextStyle: {
                        color: '#ffffff',
                        fontName: 'Roboto'
                    },
                    vAxis: {
                        textStyle: {
                            color: '#ffffff',
                            fontName: 'Roboto'
                        }
                    },
                    hAxis: {
                        textStyle: {
                            color: '#ffffff',
                            fontName: 'Roboto'
                        }
                    }
                };

                var chart = new google.visualization.ColumnChart(document.getElementById('nsfwChart'));
                chart.draw(data, options);
            }

            function drawFfxivChart() {
                var data = google.visualization.arrayToDataTable([
                    ['Username', 'Count', {role: 'style'}],
                        @foreach($ffxiv as $stat)
                    ['{{$stat->user}}', {{$stat->count}}, 'color: #2D882D'],
                    @endforeach
                ]);

                var options = {
                    title: 'Most Posts',
                    bar: {groupWidth: '75%'},
                    height: 600,
                    legend: {position: 'none'},
                    backgroundColor: "#252525",
                    titleTextStyle: {
                        color: '#ffffff',
                        fontName: 'Roboto'
                    },
                    vAxis: {
                        textStyle: {
                            color: '#ffffff',
                            fontName: 'Roboto'
                        }
                    },
                    hAxis: {
                        textStyle: {
                            color: '#ffffff',
                            fontName: 'Roboto'
                        }
                    }
                };

                var chart = new google.visualization.ColumnChart(document.getElementById('ffxivChart'));
                chart.draw(data, options);
            }

            function drawWestworldChart() {
                var data = google.visualization.arrayToDataTable([
                    ['Username', 'Count', {role: 'style'}],
                        @foreach($westworld as $stat)
                    ['{{$stat->user}}', {{$stat->count}}, 'color: #2D882D'],
                    @endforeach
                ]);

                var options = {
                    title: 'Most Posts',
                    bar: {groupWidth: '75%'},
                    height: 600,
                    legend: {position: 'none'},
                    backgroundColor: "#252525",
                    titleTextStyle: {
                        color: '#ffffff',
                        fontName: 'Roboto'
                    },
                    vAxis: {
                        textStyle: {
                            color: '#ffffff',
                            fontName: 'Roboto'
                        }
                    },
                    hAxis: {
                        textStyle: {
                            color: '#ffffff',
                            fontName: 'Roboto'
                        }
                    }
                };

                var chart = new google.visualization.ColumnChart(document.getElementById('westworldChart'));
                chart.draw(data, options);
            }
        }, 100);
    </script>

@endsection