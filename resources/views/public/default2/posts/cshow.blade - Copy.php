{{-- Update the Meta Description --}}
@section('meta_description')
    @if ($post->meta_description)
        <meta name="description" content="{!! $post->meta_description !!}" />
    @endif
@stop

{{-- Update the Meta Keywords --}}
@section('meta_keywords')
    @if ($post->meta_keywords)
        <meta name="keywords" content="{!! $post->meta_keywords !!}" />
    @endif
@stop

@section('content')
    <div class="container">
        <?php
            $menu = Menu::published()
                    ->where(function($query) {
                        $query->where('link', '=', Request::path())
                                ->orWhere('link_manual', '=', Request::path());
                    })
                    ->first();
            if ($menu) {
                $type = $menu->title;
            } else {
                $type = 'News';
            }
        ?>
        <ul class="breadcrumb">
            <li><a href="{!! url('/') !!}">Home</a></li>          
            <li class="current">{!! ucwords($post->permalink) !!}</li>
        </ul>
        <div class="row margin-bottom-40">
            <!-- BEGIN POST -->
            <div class="col-md-12 col-sm-12 blog-posts">

                <article class="post">

                    <!-- begin post heading -->
                    <header class="entry-header">
                        <h2 class="entry-title">
                            {!! HTML::link($post->permalink, $post->title) !!}
                        </h2>
                    </header>
                    <!-- end post heading -->

                    <!-- begin post content -->
                    <div class="entry-content">
                        <!-- begin post image -->
                        <figure class="featured-thumbnail full-width">
                            @if ($type == 'post')
                                <span class="meta-date">
                                    <span class="meta-date-inner">
                                        {!! $post->date() !!}
                                    </span>
                                </span>
                            @endif
                            @if ($post->image)
                                <img src="{!! url($post->image) !!}" alt="" width="636" height="179" border="0" />
                            @endif
                        </figure>
                        <!-- end post image -->

                        {!! $post->content !!}
                    </div>
                    <!-- end post heading -->

                </article>
            </div>
            <!-- END POST -->

            <!-- BEGIN SIDEBAR 
            @include("public/default2/posts.sidebar")
            <!-- END SIDEBAR -->
        </div>

    </div>
@stop
