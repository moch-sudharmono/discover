@extends($link_type . '.default._layouts._layout')

@section('content')
    <div id="page" class="dashboard">
        <h1>Welcome</h1>
        <div class="row-fluid">

            <div class="span3">
                <div class="board-widgets black small-widget">
                    <span class="widget-icon icon-user"></span>

                    <span class="widget-label">{!! trans('cms.user_manager') !!}</span>

                    <ul class="board-sub">
                        @if (can_access_menu($current_user, array('user-groups')))
                            <li><a href="{!! URL::to('backend/user-groups') !!}">{!! trans('cms.all_user_groups') !!}</a></li>
                        @endif
                        @if (can_access_menu($current_user, array('users')))
                            <li><a href="{!! URL::to('backend/users') !!}">{!! trans('cms.all_users') !!}</a></li>
                        @endif
                    </ul>
                </div>
            </div>

        <!--    <div class="span3">
                <div class="board-widgets black small-widget">
                    <span class="widget-icon icon-th-list"></span>

                    <span class="widget-label">{!! trans('cms.menu_manager') !!}</span>

                    <ul class="board-sub">
                        @if (can_access_menu($current_user, array('menu-positions')))
                            <li><a href="{!! URL::to('backend/menu-positions') !!}">{!! trans('cms.all_menu_positions') !!}</a></li>
                        @endif
                        @if (can_access_menu($current_user, array('menu-categories')))
                            <li><a href="{!! URL::to('backend/menu-categories') !!}">{!! trans('cms.all_menu_categories') !!}</a></li>
                        @endif
                        @if (can_access_menu($current_user, array('menu-manager')))
                            <li><a href="{!! URL::to('backend/menu-manager') !!}">All Menu Entries</a></li>
                        @endif
                    </ul>
                </div>
            </div>  -->
            <div class="span3">
                <div class="board-widgets black small-widget">
                    <span class="widget-icon icon-book"></span>

                    <span class="widget-label">{!! trans('cms.pages') !!}</span>

                    <ul class="board-sub">
                        @if (can_access_menu($current_user, array('pages')))
                            <li class="{!! Request::is('backend/pages') ? 'active' : null !!}">
                               <a href="{!! URL::to('backend/pages') !!}">
                                   {!! trans('cms.pages') !!}
                               </a>
                            </li>
                        @endif
                        @if (can_access_menu($current_user, array('page-categories')))
                            <li class="{!! Request::is('backend/page-categories') ? 'active' : null !!}">
                               <a href="{!! URL::to('backend/page-categories') !!}">
                                   {!! trans('cms.page_categories') !!}
                               </a>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>

          <div class="span3">
                <div class="board-widgets black small-widget">
                    <a href="{!! url('backend/media-manager') !!}">
                        <span class="widget-icon icon-camera"></span>

                        <span class="widget-label">{!! trans('cms.media_manager') !!}</span>
                    </a>
                </div>
            </div>
		
        </div>

        <div class="row-fluid">

            

            <div class="span3">
                <div class="board-widgets black small-widget">
                    <span class="widget-icon icon-group"></span>

                    <span class="widget-label">{!! trans('cms.contact_manager') !!}</span>

                    <ul class="board-sub">
                        @if (can_access_menu($current_user, array('contact-manager')))
                            <li class="{!! Request::is('backend/contact-manager') ? 'active' : null !!}">
                               <a href="{!! URL::to('backend/contact-manager') !!}">
                                   {!! trans('cms.contact_manager') !!}
                               </a>
                            </li>
                        @endif
                        @if (can_access_menu($current_user, array('contact-categories')))
                            <li class="{!! Request::is('backend/contact-categories') ? 'active' : null !!}">
                               <a href="{!! URL::to('backend/contact-categories') !!}">
                                   {!! trans('cms.contact_categories') !!}
                               </a>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>
			 <div class="span3">
                <div class="board-widgets black small-widget">
                    <a href="{!! url('backend/config') !!}">
                        <span class="widget-icon icon-cogs"></span>

                        <span class="widget-label">{!! trans('cms.settings') !!}</span>
                    </a>
                </div>
            </div>

        </div>
 
    </div>
@stop

@section('scripts')
    @parent

    <script>
        $('.board-widgets').mouseover(function(e) {
            $(this).find('.board-sub').show();
        }).mouseout(function(e) {
            $(this).find('.board-sub').hide();
        });
    </script>
@stop
