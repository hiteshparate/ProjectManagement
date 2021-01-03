<div class="left side-menu">
    <div class="sidebar-inner slimscrollleft">
        <!--- Divider -->
        <div id="sidebar-menu">
            <ul>
                <?php 
                if(Illuminate\Support\Facades\Session::get('role') == 'student'){
                    ?>
                <li>
                    <a href="" class="waves-effect"><i class="ti-home"></i> <span style="font-size: 14px"> Projects </span> </a>
                </li>
                <?php
                }else{
                    ?>
                <li>
                    <a href="" class="waves-effect"><i class="ti-home"></i> <span style="font-size: 14px"> Programme </span> </a>
                </li>
                <?php
                }
                ?>
                
            </ul>
            <div class="clearfix"></div>
        </div>
        <div class="clearfix"></div>
    </div>
</div>