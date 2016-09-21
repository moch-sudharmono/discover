<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
<!--<![endif]-->
<!-- BEGIN HEAD -->
<head>
    <meta charset="utf-8" />
    <title>{{ $title }}</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <meta content="" name="description" />
    <meta content="" name="author" />
    <meta name="csrf-token" content="{!! csrf_token() !!}">
    <!-- BEGIN STYLES -->
    @include("backend.default.custom_layouts._StylesPartial")
    <link rel="shortcut icon" href="{!!URL::to("assets/favicon.ico")!!}">
    <!-- END STYLES -->
	<?php	  
	 if(Sentry::check()){
	  $bcuid = Sentry::getUser()->id; 
	  $bcuname = Sentry::getUser()->full_name;
	   $user_photo = Sentry::getUser()->photo; 
	   $auto_logout_time = Sentry::getUser()->auto_logout_time;	   
	   $user_group = DB::table('users_groups')->where('user_id', '=', $bcuid)->select('group_id')->get();
	  if(!isset($user_group[0]->group_id) && $user_group[0]->group_id != 1){
		header("Location:  ".url('/'));
	   exit;
	  } 
	 } 
	?>   
</head>
<!-- END HEAD -->
<!-- BEGIN BODY -->
<body class="fixed-top">
    <!-- BEGIN HEADER -->
    @include("backend.default.custom_layouts._HeaderPartial")
    <!-- END HEADER -->
    <!-- BEGIN CONTAINER -->
    <div id="container" class="row-fluid">
        <!-- BEGIN SIDEBAR -->
        @include("backend.default.custom_layouts._MainMenuPartial")
        <!-- END SIDEBAR -->
        <!-- BEGIN PAGE -->
        <div id="body">
            <!-- BEGIN SAMPLE PORTLET CONFIGURATION MODAL FORM-->
         
            <!-- END SAMPLE PORTLET CONFIGURATION MODAL FORM-->
            <!-- BEGIN PAGE CONTAINER-->
            <div class="container-fluid">
                <!-- BEGIN PAGE HEADER-->
                <div class="row-fluid">
                    <div class="span12">
                      
                        <!-- END STYLE CUSTOMIZER-->
                        <!-- BEGIN PAGE TITLE & BREADCRUMB-->
                        <h3 class="page-title">
                            {!! $title !!}
                        </h3>
                        <ul class="breadcrumb">
                            <li>
                                <i class="icon-home"></i>
                                <a href="{!! URL::to('backend') !!}">Dashboard</a>
                                <i class="icon-angle-right"></i>
                            </li>
                            <li><a href="#">{!! $title !!}</a></li>                         
                        </ul>
                        <!-- END PAGE TITLE & BREADCRUMB-->
                    </div>
                </div>
                <!-- END PAGE HEADER-->
                <!-- BEGIN PAGE CONTENT-->
                <div id="errors-div">
                    @if (Session::has('error_message'))
                        <div class="alert alert-error">
                            <button data-dismiss="alert" class="close">×</button>
                            <strong>Error!</strong> {!! Session::get('error_message') !!}
                        </div>
                    @endif
                    @if (Session::has('success_message'))
                        <div class="alert alert-success">
                            <button data-dismiss="alert" class="close">×</button>
                            <strong>Success!</strong> {!! Session::get('success_message') !!}
                        </div>
                    @endif
                </div>
                @yield('content')

                <div id="ajax-insert-modal" class="modal hide fade page-container" tabindex="-1"></div>
                <div id="ajax-add-modal" class="modal hide fade page-container" tabindex="-1"></div>
                <!-- END PAGE CONTENT-->
            </div>
            <!-- END PAGE CONTAINER-->
        </div>
        <!-- END PAGE -->
    </div>
    <!-- END CONTAINER -->
    <!-- BEGIN FOOTER -->
    @include("backend.default.custom_layouts._FooterPartial")
    <!-- END FOOTER -->
    <!-- BEGIN JAVASCRIPTS(Load javascripts at bottom, this will reduce page load time) -->
    @include("backend.default.custom_layouts._ScriptsPartial")
    {{-- @RenderSection("scripts", required: false) --}}
    <!-- END JAVASCRIPTS -->
</body>
<!-- END BODY -->
</html>
