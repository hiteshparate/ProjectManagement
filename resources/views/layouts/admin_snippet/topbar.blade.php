<div class="topbar">

    <!-- LOGO -->
    <div class="topbar-left">
        <div class="text-center">
            <div  class="logo"><span>USPMES</span></div>
            <div  class="logo">
                <i class="icon-c-logo"> <img src="/images/daiict_logo.jpg" height="42"/> </i>
            </div>
        </div>
    </div>

    <div class="navbar-default">
        <div class="row"> 
            <div class="col-md-1">
                <div class="pull-left">
                    <button class="button-menu-mobile open-left waves-effect waves-light">
                        <i class="fas fa-bars"></i>
                    </button>
                </div>

            </div>
            <div class="col-md-8 text-center m-t-15">
                <h2 class="text-dark" style="font-size: 22px">University Student Project Management and Evaluation System</h2>
            </div>
            <!--/.nav-collapse -->
            <div class="col-md-3">
                <ul class="nav navbar-right pull-right">
                    <li class="dropdown">
                        <a href="" class="dropdown-toggle profile waves-effect waves-light font-bold" style="font-size: 16px" data-toggle="dropdown" aria-expanded="true">
                            <?php echo Auth::guard("admin")->user()->username ?>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a href="/logout" style="font-size: 16px">
                                    Logout
                                </a>

                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </div>

</div>