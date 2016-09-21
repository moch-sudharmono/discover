@if (Sentry::check())
    <div id="sidebar" class="nav-collapse collapse">
        <!-- BEGIN SIDEBAR TOGGLER BUTTON -->
        <div class="sidebar-toggler hidden-phone"></div>
        <!-- BEGIN SIDEBAR TOGGLER BUTTON -->
        <!-- BEGIN RESPONSIVE QUICK SEARCH FORM -->
        <div class="navbar-inverse demo">
            <form class="navbar-search visible-phone">
                <input type="text" class="search-query" placeholder="Search" />
            </form>
        </div>
        <!-- END RESPONSIVE QUICK SEARCH FORM -->
        <!-- BEGIN SIDEBAR MENU -->
	 <ul>
        <li class="start">
         <a href="{!! url('backend') !!}">
            <i class="icon-home"></i>
          <span class="title">Dashboard</span>
         </a>
        </li>
		<li class="@if(isset($active) && $active == 'stats') active @endif ">
         <a href="{!! url('backend/stats') !!}">
            <i class="icon-bar-chart"></i>
          <span class="title">Statistics</span>
         </a>
        </li>
                            <li class="has-sub   ">
                    <a href="javascript:;">
                        <i class="icon-user"></i>
                        <span class="title">User Manager</span>
                        <span class="arrow "></span>
                    </a>
                    <ul class="sub">
                                                    <li><a href="{!! url('backend/user-groups') !!}">All User Groups</a></li>
                                                                            <li><a href="{!! url('backend/users') !!}">All Users</a></li>
                                            </ul>
                </li>
                   
                        <li class="has-sub  ">
                <a href="javascript:;">
                    <i class="icon-book"></i>
                    <span class="title">Pages</span>
                    <span class="arrow "></span>
                </a>
                <ul class="sub">
                                            <li class="">
                           <a href="{!! url('backend/pages') !!}">
                               Pages
                           </a>
                        </li>
                                                                <li class="">
                           <a href="{!! url('backend/page-categories') !!}">
                               Page Categories
                           </a>
                        </li>
                                    </ul>
            </li>
            			
		 			 <li class="has-sub @if(isset($active) && $active == 'event') active @endif ">
                <a href="javascript:;">
                    <i class="icon-book"></i>
                    <span class="title">Event</span>
                    <span class="arrow "></span>
                </a>
                <ul class="sub">                   
                        <li class="@if(isset($active) && $active == 'event') active @endif">
                           <a href="{!! url('backend/eventList') !!}">
                               Event List
                           </a>
                        </li>
                </ul>
            </li>
		  		  
            
            <li class="">
               <a href="{!! url('backend/media-manager') !!}">
                   <i class="icon-camera"></i>
                   <span class="title">Media Manager</span>
               </a>
            </li>
		<!--	 <li class="has-sub @if(Session::has('active') && Session::get('active') == 'email') active @endif ">
                <a href="javascript:;">
                    <i class="icon-book"></i>
                    <span class="title">Email List/Template</span>
                    <span class="arrow "></span>
                </a>
                <ul class="sub">                   
                        <li class=" @if(Session::has('active') && Session::get('active') == 'email') active @endif ">
                           <a href="{!! url('backend/emailList') !!}">
                               Email List
                           </a>
                        </li>
                </ul>
            </li> -->
			
             <li class="has-sub  ">
                <a href="javascript:;">
                    <i class="icon-book"></i>
                    <span class="title">Contact Manager</span>
                    <span class="arrow "></span>
                </a>
                <ul class="sub">
                                            <li class="">
                           <a href="{!! url('backend/contact-manager') !!}">
                               Contact Manager
                           </a>
                        </li>
                                                                <li class="">
                           <a href="{!! url('backend/contact-catgories') !!}">
                               Contact Categories
                           </a>
                        </li>
                                    </ul>
            </li>
               
              
               
                        <li class="">
               <a href="{!! url('backend/config') !!}">
                   <i class="icon-cogs"></i>
                   <span class="title">Settings</span>
               </a>
            </li>
                    </ul>
	 
	 
        <!-- END SIDEBAR MENU -->
    </div>
@endif
