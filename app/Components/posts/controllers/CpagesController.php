<?php namespace Components\Posts\Controllers;

use BaseController;
use App, Input, Redirect, Request, Sentry, Str, View;
use Post, Category;
use Services\Validation\ValidationException as ValidationException;

class CpagesController extends BaseController {

    public function __construct()
    {
		
        // Add location hinting for views
        View::addLocation(app_path().'/components/posts/views');
        View::addNamespace('posts', app_path().'/components/posts/views');
        Request::is('*');
        $this->type = 'page';		
        parent::__construct();
    }

   /**
     * Display a listing of the posts.
     *
     * @return Response
     
    public function index()
    {
			App::abort('404');       
    }
   */
    public function show()
    { 
	   
	  $permalink = Request::path();
      $post = Post::wherePermalink($permalink)->first();
  
      if (!$post) App::abort('404');

        $post->hits += 1;
        $post->save();
        $post->extras = json_decode($post->extras, true);

       if (isset($post->extras['contact_page']) && $post->extras['contact_page']) {
            $view = 'public.'.$this->current_theme.'.contact';
        } else {
            $view = 'public.'.$this->current_theme.'.posts.cshow';
        }
		 //$view = 'public.'.$this->current_theme.'.contact';
        $this->layout->title = $post->title;
        $this->layout->content = View::make($view)
                                        ->with('post', $post)
                                        ->with('type', $this->type);
    }
	   public function contactShow()
    { 
	   
	  $permalink = Request::path();
      $post = Post::wherePermalink($permalink)->first();
  
      if (!$post) App::abort('404');

        $post->hits += 1;
        $post->save();
        $post->extras = json_decode($post->extras, true);      
		 $view = 'public.'.$this->current_theme.'.contact';
        $this->layout->title = $post->title;
        $this->layout->content = View::make($view)
                                        ->with('post', $post)
                                        ->with('type', $this->type);
    }	
}