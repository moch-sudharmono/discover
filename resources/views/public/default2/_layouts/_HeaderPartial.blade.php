 <!-- BEGIN TOP BAR -->
<?php /* <div class="pre-header">
    <div class="container">
        <div class="row">
            <!-- BEGIN TOP BAR LEFT PART -->
            <div class="col-md-6 col-sm-6 additional-shop-info">
                {!! Services\MenuManager::generate('public-small-menu-left', 'list-unstyled list-inline') !!}
            </div>
            <!-- END TOP BAR LEFT PART -->
            <!-- BEGIN TOP BAR MENU -->
            <div class="col-md-6 col-sm-6 additional-nav">
                {!! Services\MenuManager::generate('public-small-menu-right', 'list-unstyled list-inline pull-right') !!}
            </div>
            <!-- END TOP BAR MENU -->
        </div>
    </div>
</div>
<!-- END TOP BAR -->
*/ ?>
 <nav class="navbar navbar-default header-main1">
    <div class="header_bg2">
      <div class="border-line">
        <div class="container">
          <div class="navigation clearfix">
            <div class="logo">
		@if(!empty($sel_accname))
		 <a class="site-logo" href="{!! url($sel_accname) !!}">
		@else
		 <a class="site-logo" href="{!! url('/') !!}">
        @endif 
        @if (Setting::value('website_logo'))
            <img src="{!!URL::to(Setting::value('website_logo'))!!}" alt="Logo"/>
        @else
            <img src="{!!URL::to('assets/public/default2/img/logo.png')!!}" alt="Logo" />
        @endif
    </a>
	</div>
            <div class="right-bar">	
			
			    @include("public/default2/_layouts._TopMenuPartial")			
           
            </div>
          </div>
        </div>
      </div>
    </div>
  </nav>  