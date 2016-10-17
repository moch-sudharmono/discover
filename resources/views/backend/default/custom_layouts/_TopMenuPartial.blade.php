<ul class="nav pull-right" id="top_menu">
    <li><a target="_blank" href="{!! URL::to('/') !!}">Public</a></li>	
    <li><a target="_blank" href="{!! URL::to('/admin') !!}">Admin</a></li>
    <li class="dropdown"><a href="{!! url('backend/config') !!}" class="dropdown-toggle"><i class="icon-wrench"></i>&nbsp;&nbsp; </a></li>
    <li class="divider-vertical hidden-phone hidden-tablet"></li>
    <!-- BEGIN USER LOGIN DROPDOWN -->
    <li class="dropdown">
     <a href="#" class="dropdown-toggle" data-toggle="dropdown">
            {!! $bcuname !!}
            <b class="caret"></b>
        </a> 
        <ul class="dropdown-menu">
            <li>
                <a href="{!! url('backend/profile') !!}">
                    <i class="icon-user"></i> Profile
                </a>
            </li>
            <li class="divider"></li>
            <li><a href="{!! url('logout') !!}">
			<i class="icon-key"></i> Log Out</a></li>
        </ul>
    </li>
    <!-- END USER LOGIN DROPDOWN -->
</ul>
