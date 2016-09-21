<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
<!--<![endif]-->
<head prefix="og: http://ogp.me/ns#">
    <!-- Basic Page Needs
  ================================================== -->
    <meta charset="utf-8">
    <title>{!! Services\MenuManager::getTitle($title) !!}</title>
    @section('meta_description')
        {{-- Here goes the meta_description --}}
    @show
    @section('meta_keywords')
        {{-- Here goes the meta_keywords --}}
    @show
  <meta property="og:image:width" content="3500" >
  <meta property="og:image:height" content="2300" >
  <meta property="og:site_name" content="DiscoverYourEvent" />
  <meta property="fb:app_id" content="599754386855822" >
  <meta property="og:description" content="" > 
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

  <!-- CSS================================================== -->
    @include("public/default2/_layouts._StylesPartial")

    <!-- Favicons  ================================================== -->
    <link rel="shortcut icon" href="{!!URL::to('assets/favicon.ico')!!}">

</head>
<body class="corporate">
<?php if(Sentry::check()){
	  $bcuid = Sentry::getUser()->id; 
	  $bcuname = Sentry::getUser()->full_name;
	   $user_photo = Sentry::getUser()->photo; 
    $an_dye = DB::table('user_accounts')->where('user_accounts.u_id', '=', $bcuid)->where('user_accounts.account_urole', '=', 'admin')->Where('user_accounts.status','=', 'open')
      ->orWhere('user_accounts.status','=', 'transfer')->where('user_accounts.u_id', '=', $bcuid)->where('user_accounts.account_urole', '=', 'admin')
	  ->join('account_details', 'user_accounts.account_did', '=', 'account_details.id')
	 ->select('account_details.g_id','account_details.name','account_details.account_url','account_details.upload_file')->get();	
		$vs = 'yes';
	  $sel_accname = Session::get('selaccount');	
	  /*******Notification*******/  
	   $wanotfresult = DB::table('notifications')->where('onr_id', '=', $bcuid)->where('global_read', '=', 0)->count(); 
	  $allntfCount = DB::table('notifications')->where('onr_id', '=', $bcuid)->where('is_read', '=', 0)->count(); 
	  $wanotfdata = DB::table('notifications')->where('onr_id', '=', $bcuid)->where('is_read', '=', 0)->orderBy('id', 'desc')->take(10)->get(); 	  
     } else {
	   $vs = 'no'; 
     }	 
?>	 
    <!-- Primary Page Layout  ================================================== -->
    <!-- BEGIN HEADER -->
	<header>
    @include("public/default2/_layouts._HeaderPartial")
	</header>
    <!-- END HEADER -->

    @section('slider')
        {{-- Here goes the slider --}}
    @show

    @section('heading')
        {{-- Here goes the heading --}}
    @show

    <!-- BEGIN CONTENT HOLDER -->  
        @yield('content') 
    <!-- END CONTENT HOLDER -->

    @include("public/default2/_layouts._FooterPartial")

    <!-- Javascript Files
    ================================================== -->
    @include("public/default2/_layouts._ScriptsPartial")

</body>
 @include("public/default2/_layouts.visitor_stats")
</html>