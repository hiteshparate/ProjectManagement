
<!DOCTYPE html>
<html>
    <head>

        <link rel="shortcut icon" href="assets/images/favicon_1.ico">

        <title>Login</title>
        <link rel="stylesheet" href="{{URL::asset('/css/bootstrap.css')}}">
        <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ asset('css/core.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ asset('css/components.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ asset('css/icons.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ asset('css/pages.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ asset('css/responsive.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ asset('css/fa-svg-with-js.css') }}" rel="stylesheet" type="text/css" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/6.6.4/sweetalert2.min.css" />
        <script src="{{ asset('js/modernizr.min.js') }}"></script>
    </head>
    <body>

        <div class="account-pages"></div>
        <div class="clearfix"></div>
        <div class="wrapper-page">
            <div class=" card-box">
                <div class="panel-heading"> 
                    <h3 class="text-center"> Sign In to <strong class="text-custom">USPMES</strong> </h3>
                </div> 


                <div class="panel-body">
                    <form class="form-horizontal m-t-20" method="post" action="/login">
                        {{csrf_field()}}
                        <div class="form-group ">
                            <div class="row col-xs-12">
                                <input class="form-control" name="username" type="text" required="" placeholder="Username">
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row col-xs-12">
                                <input id="show_p" class="form-control col-md-10" name="password" type="password" required  placeholder="Password"><button id="btn_show" class="btn btn-primary col-md-2" type="button"><i class="fas fa-eye"></i></button>
                            </div>
                        </div>


                        <div class="form-group text-center m-t-40">
                            <div class="col-xs-12">
                                <button class="btn btn-pink btn-block text-uppercase waves-effect waves-light" type="submit">Log In</button>
                            </div>
                        </div>

                        <div class="form-group m-t-30 m-b-0">
                            <div class="col-sm-12">
                                <a href="{{ url('forgotPassword') }}" class="text-dark"><i class="fa fa-lock m-r-5"></i> Forgot your password?</a>
                            </div>
                        </div>
                    </form> 

                </div>   
            </div>                              


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

        @if (session('login_error'))
        <div class="alert alert-danger">
            <li>{{ session('login_error') }}</li>
        </div>
        @endif




        <script>
var resizefunc = [];
        </script>

        <!-- jQuery  -->
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

        <script>
$(document).ready(function () {
    $('#btn_show').mousedown(function () {
        $('#show_p').attr('type', 'text');
    });
    $('#btn_show').mouseup(function () {
        $('#show_p').attr('type', 'password');
    });
});
        </script>
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
