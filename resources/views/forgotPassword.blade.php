
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

        <!--        <link href="{{ asset('css/admin/components.css') }}" rel="stylesheet" type="text/css"/>
                <link href="{{ asset('css/admin/core.css') }}" rel="stylesheet" type="text/css"/>
                <link href="{{ asset('css/admin/pages.css') }}" rel="stylesheet" type="text/css"/>
                <link href="{{ asset('css/admin/responsive.css') }}" rel="stylesheet" type="text/css"/>-->

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
                <div class="content" style="padding-top: 10%">


                    <div class="card-box">
                        <div class="panel-heading"> 
                            <h3 class="text-center"> Password Reset</h3>
                        </div>
                        <div class="panel-body">
                            <form class="form form-horizontal" action="/reset_password" method="post">
                                {{ csrf_field() }}
                                <div class="form-group ">
                                    <div class="col-xs-12">
                                        <input  style="font-size: 15px" name="email" placeholder="Enter daiict's email" required class="form form-control">
                                    </div>
                                </div>

                                <div class="form-group ">
                                    <div class="col-xs-12" style="text-align: center">
                                        <button  style="font-size: 15px" class="btn btn-primary center-block" type="submit">Send Reset Password Link</button>
                                    </div>
                                </div>


                            </form>
                        </div>



                    </div>  
                    <!--container--> 

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


        @if (session('status'))
        <script>
            //    $('#add_btn').click(function () {
            swal(
                    '<?php echo session('status') ?>',
                    '',
                    'success'
                    )
            //    });
        </script>
        @endif
    </body>
</html>