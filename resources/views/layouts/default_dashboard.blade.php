@extends('layouts.default_header')
<?php
$admin_common = session()->get('admin_common');
$modules = $admin_common->modules;
$reports = $admin_common->reports;
?>
@section('content')
    <!-- Dashboard Components -->

    <!-- Modules Start -->
    <style>

button.btn.btn-success.ccc.mod_btn_hed {
    width: 170px;
    height: 73px;
    font-size: 36px;
    font-family: fantasy;
    background: white;
    border: 3px solid #996418;
    font-weight: 100;
    color: #996418;
}
.tile-stats.module_tile {
    font-family: fantasy;
    font-size: 20px;
    font-weight: 100;
}
.sidebar a, .sidebar .nav-title {
    color: white;
    background: #996418;
    width: 100%;
    margin: 10px;
    border-bottom-left-radius: 10px;
    border-top-left-radius: 10px;
    font-weight: bold;
    font-size: 15px;
    padding: 10px 61px;
    margin-bottom: 0px;
}

    </style>
    <div class="row">


        <div class="pull-right language-toggle">

        </div>
        <div class="col-md-12 col-sm-12 col-xs-12">

            <div>
                <!-- <section class="dash-tile abc">
                    <h1 class="mt0">
                        Modules
                    </h1>
                </section> -->
                <section class="abc">
                    <h1 class="mt0">
                    <button type="button" class="btn btn-success ccc mod_btn_hed">Modules</button>
                    </h1>
                </section>
            </div>
            @if ($message = Session::get('error'))
        <div class="alert alert-danger alert-block">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <strong>{{ $message }}</strong>
        </div>
    @endif
        </div>
        
        @foreach($modules as $module)
        <a href="{!! asset($module['url']) !!}">
            <div class="col-md-3 col-sm-6 col-xs-12">
                <section class="dash-tile bg-success modules" style="background-image: url('{{ $module['image'] }}');">
                    <div class="tile-title">
                    </div>
                    <div class="tile-stats module_tile">{!! $module['title'] !!}
                    </div>
                    <div class="mb20"></div>
                    <div class="tile-footer">
                    </div>
                </section>
            </div>
        </a>
    @endforeach
    
    </div>

    <!-- Modules end -->
<style>
    .pael_bg{
        background-color: transparent !important
    }
    .reprt_btn{
        display: none;
    }
</style>
    <!-- reports start -->
    <div class="row">


        <div class="col-md-12 col-sm-12 col-xs-12">
            <!-- <section class="dash-tile vvv">
                <h1 class="mt0">Reports</h1>
            </section> -->
            <section class="abc">
                    <h1 class="mt0">
                    <button type="button" class="btn btn-success ccc reprt_btn">Reports</button>
                    </h1>
                </section>
        </div>
        @foreach($reports as $key => $report)
        {{-- {{dd($report[$key]['url'])}} --}}
        <a href="{!! asset($report['url']) !!}">
                <div class="col-md-3 col-sm-6 col-xs-12">
                    <section class="dash-tile bg-warning modules">
                        <div class="tile-stats">{!!$report['title']!!}
                        </div>
                        <br><br>
                    </section>
                </div>
            </a>

            @if(!($key+1 / 4))

    </div>
    <div class="row">
        @endif

        @endforeach
    </div>
    <!-- reports end  -->

    <!-- Chart -->
    <section class="panel hidden-xs obody pael_bg">
        <div class="panel-body">
            <div class="row">
                <div class="col-sm-12 mb25">
                </div>
                <div class="col-sm-4">
                    <h1 class="mt0">
                        {{-- Chart --}}
                    </h1>
                    {{-- <h3>Top Ten Vendors By Rating</h3> --}}
                    <!-- Chart here -->
{{--                    <div id="dashboard_chart">--}}
{{--                        <script>--}}
{{--                            var graph_x = [];--}}
{{--                            var graph_y = [];--}}
{{--                            var graph = [];--}}
{{--                            @foreach($chart as $c)--}}
{{--                            graph_x.push(' {!! $c->name !!}');--}}
{{--                            graph_y.push('{!!$c->rating!!}');--}}
{{--                            @endforeach--}}

{{--                                graph = {--}}
{{--                                x: graph_x,--}}
{{--                                y: graph_y,--}}
{{--                                name: 'TOP TEN',--}}
{{--                                type: 'bar'--}}
{{--                            };--}}

{{--                            var data = [graph];--}}
{{--                            var layout = {barmode: 'group', width: 1000, height: 600};--}}

{{--                            Plotly.newPlot('dashboard_chart', data, layout);--}}

{{--                        </script>--}}
{{--                    </div>--}}
                    <!-- Chart here -->

                </div>
            </div>
        </div>
    </section>
    <!-- chart end -->

    <!-- Dashboard Components  end  -->


    <!-- build:js({.tmp,app}) scripts/app.min.js -->

    <script src="{{ asset('theme/vendor/jquery/dist/jquery.js') }}"></script>
    <script src="{{ asset('theme/vendor/bootstrap/dist/js/bootstrap.js') }}"></script>
    <script src="{{ asset('theme/vendor/slimScroll/jquery.slimscroll.js') }}"></script>
    <script src="{{ asset('theme/vendor/jquery.easing/jquery.easing.js') }}"></script>
    <script src="{{ asset('theme/vendor/jquery_appear/jquery.appear.js') }}"></script>
    <script src="{{ asset('theme/vendor/jquery.placeholder.js') }}"></script>
    <script src="{{ asset('theme/vendor/fastclick/lib/fastclick.js') }}"></script>
    <!-- endbuild -->

    <!-- page level scripts -->
    <script src="{{ asset('theme/vendor/blockUI/jquery.blockUI.js') }}"></script>
    <script src="{{ asset('theme/vendor/bower-jvectormap/jquery-jvectormap-1.2.2.min.js') }}"></script>
    <script src="{{ asset('theme/data/maps/jquery-jvectormap-world-mill-en.js') }}"></script>
    <script src="{{ asset('theme/vendor/jquery.sparkline.js') }}"></script>

    <script src="{{ asset('theme/vendor/jquery-countTo/jquery.countTo.js') }}"></script>
   <!-- /page level scripts -->

    <!-- page script -->

    <!-- /page script -->

    <script>
        function lang_changed(){
            $('#lang').val('changed');
        }
    </script>


@stop
