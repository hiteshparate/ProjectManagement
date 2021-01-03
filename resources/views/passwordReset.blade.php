
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="A fully featured admin theme which can be used to build CRM, CMS, etc.">
        <meta name="author" content="Coderthemes">

        <link rel="shortcut icon" href="{{ asset('images/favicon.ico') }}">

        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>@yield('title')</title>
        <script>
            window.Laravel = {!! json_encode([
                    'csrfToken' => csrf_token(),
            ]) !!}
            ;
        </script>
        <!--Morris Chart CSS -->
        <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ asset('css/core.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ asset('css/components.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ asset('css/icons.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ asset('css/pages.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ asset('css/responsive.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ asset('css/fa-svg-with-js.css') }}" rel="stylesheet" type="text/css" />



        <link href="{{ asset('css/bootstrap.css') }}" rel="stylesheet" type="text/css"/>
        <link href="{{ asset('css/icons.css') }}" rel="stylesheet" type="text/css"/>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/6.6.4/sweetalert2.min.css" />

        @yield('css')


        <script src="{{ asset('js/modernizr.min.js') }}"></script>



    </head>


    <body class="widescreen fixed-left">

        <!-- Begin page -->
        <div id="wrapper" class="forced">

            <div class="topbar">

                <div class="navbar-default">
                    <div class="row"> 
                        <div class="col-md-12 text-center m-t-15">
                            <h2 class="text-dark" style="font-size: 22px">University Student Project Management and Evaluation System</h2>
                        </div>
                    </div>
                </div>

            </div>

            <!-- ============================================================== -->
            <!-- Start right Content here -->
            <!-- ============================================================== -->
            <!--<div class="content-page">-->
            <!--Start content-->
            <div class="wrapper-page">
                <div class=" card-box">
                    <div class="panel-body">
                        <form class="form-horizontal m-t-20" method="post" action="/new_password">
                            {{ csrf_field() }}
                            <div class="row">
                                <label for="pass_1" class="font-13">
                                    New Password:
                                </label>
                                <input id="pass_1" type="password" name="password" class="form-control font-13 col-md-10"><button type="button" id="show_p1"class="btn btn-primary col-md-2"><i class="fas fa-eye"></i></button>
                            </div>

                            <div class="row m-t-15">
                                <label for="pass_2" class="font-13">
                                    Confirm New Password:
                                </label>
                                <input id="pass_2" type="password" name="confirm_password" class="form-control font-13 col-md-10"><button type="button" id="show_p2"class="btn btn-primary col-md-2"><i class="fas fa-eye"></i></button>
                            </div>
                            <div class="text-center m-t-15" style="text-align: center">
                                <button class="btn btn-primary text-uppercase waves-effect waves-light font-13" type="submit">Reset</button>
                            </div>
                        </form>
                    </div>   
                </div>
                <div class="alert alert-danger" style="font-size: 15px">
                <ul>
                    <li>Password must be at least 6 characters long.
                </ul>
            </div>
                @if ($errors->any())
                <div class="alert alert-danger" style="font-size: 15px ">
                    <ul> 
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif
            </div>




            <!-- ============================================================== -->
            <!-- End Right content here -->
            <!-- ============================================================== -->
        </div>
        <!-- END wrapper -->





        <!-- jQuery  -->

        <script src="{{ asset('js/jquery-3.3.1.min.js') }}" type="text/javascript"></script>
        <script src="{{ asset('js/detect.js') }}" type="text/javascript"></script>
        <script src="{{ asset('js/bootstrap.min.js') }}" type="text/javascript"></script>
        <script src="{{ asset('js/fastclick.js') }}" type="text/javascript"></script>
        <script src="{{ asset('js/waves.js') }}" type="text/javascript"></script>
        <script src="{{ asset('js/wow.min.js') }}" type="text/javascript"></script>
        <script src="{{ asset('js/jquery.scrollTo.min.js') }}" type="text/javascript"></script>
        <script src="{{ asset('js/jquery.slimscroll.js') }}" type="text/javascript"></script>
        <script src="{{ asset('js/jquery.blockUI.js') }}" type="text/javascript"></script>
        <script src="{{ asset('js/jquery.nicescroll.js') }}" type="text/javascript"></script>
        <script src="{{ asset('js/jquery.core.js') }}" type="text/javascript"></script>
        <script src="{{ asset('js/plugins/notifications/notify.min.js') }}" type="text/javascript"></script>
        <script src="{{ asset('js/plugins/notifications/notify-metro.js') }}" type="text/javascript"></script>
        <script src="{{ asset('js/fontawesome-all.min.js') }}" type="text/javascript"></script>
        <script src="{{ asset('js/modernizr.min.js') }}" type="text/javascript"></script>

        <!-- Jquery easing -->                                                      
        <script type="text/javascript" src="{{ asset('js/jquery.easing.1.3.min.js') }}"></script>

        <!--sticky header-->
        <script type="text/javascript" src="{{ asset('js/jquery.sticky.js') }}"></script>
        <script src="{{ asset('js/jquery.app.js') }}"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/6.6.4/sweetalert2.min.js"></script>
        @yield('js')
        <script>
        $(document).ready(function () {
            $('#show_p1').mousedown(function(){
                $('#pass_1').attr('type','text');
            });
            $('#show_p1').mouseup(function(){
                $('#pass_1').attr('type','password');
            });
            $('#show_p2').mousedown(function(){
                $('#pass_2').attr('type','text');
            });
            $('#show_p2').mouseup(function(){
                $('#pass_2').attr('type','password');
            });
        });
        </script>


        
        @if (session('sw_alert'))
        <script>
            //    $('#add_btn').click(function () {
            swal(
                    '<?php echo session('sw_alert') ?>',
                    '',
                    'error'
                    )
            //    });
        </script>
        @endif
    </body>
</html>