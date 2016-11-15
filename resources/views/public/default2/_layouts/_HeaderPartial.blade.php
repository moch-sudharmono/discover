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