<?php $caacount = Session::get('selaccount'); ?>
@if(!empty($caacount)) 
  <ul class="nav nav-tabs">
    <li class=" @if($active == 'acc') active @endif "><a href="{!!URL($caacount.'/account')!!}">Account Details</a></li> 	
    <li class=" @if($active == 'ce') active @endif "><a href="{!!URL($caacount.'/account/createEvent')!!}">Create Event</a></li>
    <li class=" @if($active == 'me') active @endif "><a href="{!!URL($caacount.'/account/manageEvent')!!}">Manage Event</a></li>
   @if(!empty($auser_role) && $auser_role == 'admin') 
	<li class=" @if($active == 'nf') active @endif "><a href="{!!URL($caacount.'/account/notifications')!!}">Email Notifications</a></li>
	<li class=" @if($active == 'mu') active @endif "><a href="{!!URL($caacount.'/account/users')!!}">Manage Account & Users</a></li>
   <!-- <li><a href="javascript:void(0)">Payment</a></li> -->
   @endif	
  </ul>
@else		
  <ul class="nav nav-tabs">
    <li class=" @if($active == 'acc') active @endif "><a href="{!!URL('account')!!}">Account Details</a></li>
    <li><a href="{!!URL('createPage')!!}" target="_blank">Create Page</a></li>
    <li class=" @if($active == 'ce') active @endif "><a href="{!!URL('account/createEvent')!!}">Create Event</a></li>
    <li class=" @if($active == 'me') active @endif "><a href="{!!URL('account/manageEvent')!!}">Manage Event</a></li>
	<li class=" @if($active == 'nf') active @endif "><a href="{!!URL('account/notifications')!!}">Email Notifications</a></li>
	<li class=" @if($active == 'fl') active @endif "><a href="{!!URL('account/following')!!}">Following</a></li>
	<!-- <li class=" @if($active == 'mu') active @endif "><a href="{!!URL('account/users')!!}">Manage Users</a></li> 
	<li><a href="javascript:void(0)">Payment</a></li>-->
  </ul>
@endif		