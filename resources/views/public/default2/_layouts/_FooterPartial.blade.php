<!-- BEGIN PRE-FOOTER -->
<footer>
<div class="ftr-bg">
  <div class="container text-center">
  <ul class="nav-bootom">
   <li><a href="{!!URL('about')!!}">About</a></li>
   <li><a href="{!!URL('privacy')!!}">Privacy</a></li>
   <li><a href="{!!URL('userAgreement')!!}">User Agreement</a></li>
   <li class="active"><a href="{!!URL('cookies')!!}">Cookies</a></li>
   <li><a href="{!!URL('contact')!!}">Contact</a></li>
   <li><a href="{!!URL('help')!!}">Help</a></li>
  </ul>  
  <!-- {!! Services\MenuManager::generate('public-bottom-menu','nav-bootom', '', '', '', '') !!} -->
  </div>
  <div class="copy-right">
    <div class="container text-center"> {!! Setting::value('footer_text') !!} </div>
  </div>
</div>
</footer>