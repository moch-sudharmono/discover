 <ul class="nav nav-tabs">
    <li><a href="{!!URL('/')!!}">Home</a></li>
    <li class="@if($post->permalink == 'about') active @endif"><a href="{!!URL('about')!!}">About</a></li>
    <li class=" @if($post->permalink == 'privacy') active @endif"><a href="{!!URL('privacy')!!}">Privacy</a></li>
	<li class="@if($post->permalink == 'userAgreement') active @endif"><a href="{!!URL('userAgreement')!!}">User Agreement</a></li>
	<li class="@if($post->permalink == 'cookies') active @endif"><a href="{!!URL('cookies')!!}">Cookies</a></li>
	<li class="@if($post->permalink == 'contact') active @endif"><a href="{!!URL('contact')!!}">Contact</a></li>
	<li class="@if($post->permalink == 'help') active @endif"><a href="{!!URL('help')!!}">Help</a></li>
  </ul>