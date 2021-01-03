
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
        <!--<link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />-->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
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

            <!-- Top Bar Start -->
            @include('layouts.student_snippet.topbar')
            <!-- Top Bar End -->


            <!-- ========== Left Sidebar Start ========== -->
            @include('layouts.student_snippet.leftsidebar')

            <!-- Left Sidebar End -->



            <!-- ============================================================== -->
            <!-- Start right Content here -->
            <!-- ============================================================== -->
            <div class="content-page">
                <!--Start content--> 
                <div class="content">
                <div class="container" >

                        @yield('content')
                    </div>  
                    <!--container--> 

                </div>  
                <!--content--> 


                @include('layouts.student_snippet.footer')

            </div>


            <!-- ============================================================== -->
            <!-- End Right content here -->
            <!-- ============================================================== -->
        </div>
        <!-- END wrapper -->





        <!-- jQuery  -->
        <script>
            var resizefunc = [];
        </script>

        <script src="{{ asset('js/jquery-3.3.1.min.js') }}" type="text/javascript"></script>
        <script src="{{ asset('js/detect.js') }}" type="text/javascript"></script>
        <!--<script src="{{ asset('js/bootstrap.min.js') }}" type="text/javascript"></script>-->
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
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>

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
        @if (session('error'))
        <script>
            //    $('#add_btn').click(function () {
            swal(
                    '<?php echo session('error') ?>',
                    '',
                    'error'
                    )
            //    });
        </script>
        @endif
    </body>
</html>