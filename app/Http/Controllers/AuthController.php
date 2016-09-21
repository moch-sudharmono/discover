<?php
/*
=================================================
CMS Name  :  DOPTOR
CMS Version :  v1.2
Available at :  www.doptor.org
Copyright : Copyright (coffee) 2011 - 2015 Doptor. All rights reserved.
License : GNU/GPL, visit LICENSE.txt
Description :  Doptor is Opensource CMS.
===================================================
*/

use Services\UserManager;
use Services\UserGroupManager;

use Illuminate\Support\Facades\Validator;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends BaseController {

    protected $user_manager;
    protected $usergroup_manager;
    public function __construct(UserManager $user_manager, UserGroupManager $usergroup_manager)
    {
        $this->user_manager = $user_manager;
        $this->usergroup_manager = $usergroup_manager;

        parent::__construct();
    }
	
     public function postRegisters(Request $request)
    { 
	
	 $data = $request->input();		
	 
		$validator = Validator::make($request->all(), [
            'full_name' => 'required|max:255',
            'email' => 'required|min:4|email',
            'password' => 'required|min:8',
        ]);			
		if ($validator->fails()) {
            return 'error';
        } else { 
		 $checkmail = DB::table('users')->where('users.email', '=', $data['email'])->select('id')->get();  
		
		 if(!empty($checkmail[0]->id)){
			 return 'exist';
		 } else {
		 $mainvsUser = DB::table('users')->insertGetId( 
			['username' => $data['email'],'full_name' => $data['full_name'], 'email' => $data['email'], 'password' => bcrypt($data['password']), 'activated' => '1', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')]  
		  ); 
		 DB::table('users_groups')->insert(['user_id' => $mainvsUser, 'group_id' => '3']); 		
		 Session::put('cspid', $mainvsUser);
		 Session::put('remail', $data['email']);
		 Session::put('rpassword', $data['password']);
		  
		 //Send Email
			/*$SendToEmail = $data['email'];
			$Subject = "You are successfully sign up - Discover Your Event";
               $eve = url('logInSignUp');	
			$emdata = array(
	            'uname'      => $data['full_name'],
	            'uemail'      => $data['email'],
				'loginurl'    => $eve
		    );

			Mail::send('email.signup_email', $emdata, function($message) use ($SendToEmail, $Subject)
			{
			    $message->to($SendToEmail)->cc('vikassharma.itcorporates@gmail.com')->subject($Subject);
			});*/
			
           return 'success';		
		}
		}
	}	

    /**
     * View for the login page
     * @return View
     */
    public function getLogin($target='admin')
    { 	
        if (Sentry::check()) {
            return Redirect::to($target);
        }
		if($target == 'backend'){
			$this->current_theme = 'default';
		}
        $this->layout = View::make($target . '.'.$this->current_theme.'._layouts._login');
        $this->layout->title = 'Login';
        $this->layout->content = View::make($target . '.'.$this->current_theme.'.login');
    }
	
	 public function getBothlr()
    {
	 if(!Sentry::check()){
		if(!empty(Session::get('persignup'))){
		  $personal = Session::get('persignup');	
		} else {
		  $personal = null;	
		} 
		 // Sentry::getUser()->id; 
        return \View::make('public/default2/signup-login')->with('title','Login or Signup')->with('personal', $personal);
     } else {
		  return Redirect::to('/');
	 }
	} 

    /**
     * Login action
     * @return Redirect
     */
    public function postLogin($target)
    {
        $input = Input::all(); 
	 
     if($target == 'admin' || $target == 'backend'){
	
		    $credentials = array(
            'login' => $input['username'],
            'password' => $input['password']
        );
        $remember = (isset($input['remember']) && $input['remember'] == 'checked') ? true : false;

        try {
            $user = Sentry::authenticate($credentials, $remember);

            if ($user) {
                if (isset($input['api'])) {
                    return Response::json(array(), 200);
                } else {
                    return Redirect::intended($target);
                }
            }
        } catch (Cartalyst\Sentry\Users\UserNotActivatedException $e) {
            if (isset($input['api'])) {
                return Response::json(array(
                                        'error' => trans('cms.check_activation_email')
                                        ), 200);
            } else {
                return Redirect::back()
                                    ->withErrors(trans('cms.check_activation_email'));
            }
        } catch (Cartalyst\Sentry\Throttling\UserSuspendedException $e) {
            if (isset($input['api'])) {
                return Response::json(array(
                                        'error' => trans('cms.account_suspended', array('minutes' => 10))
                                        ), 200);
            } else {
                return Redirect::back()
                                    ->withErrors(trans('cms.account_suspended', array('minutes' => 10)));
            }
        } catch(Exception $e) {
            if (isset($input['api'])) {
                return Response::json(array(
                                        'error' => trans('cms.invalid_username_pw')
                                        ), 200);
            } else {
                return Redirect::back()
                                    ->withErrors(trans('cms.invalid_username_pw'));
            }
        }
	  }       
    }

     public function postsLogin()
    {
        $input = Input::all(); 
      if(!empty($input['email'])){
		 
		 	 $getuser = DB::table('users')->where('users.email', '=', $input['email'])->select('username')->get();  
			 
	  if(!empty($getuser[0]->username)){		 
		     $credentials = array(
            'login' => $getuser[0]->username,
            'password' => $input['password']
        );
		   $remember = (isset($input['remember']) && $input['remember'] == 'checked') ? true : false;
		 
		try {
				
            $user = Sentry::authenticate($credentials, $remember);

            if ($user) {
			  $get_groupid = DB::table('users_groups')->where('user_id', '=', $user->id)->select('group_id')->get();
             if($get_groupid[0]->group_id == 3){ 			  
                if (isset($input['api'])) {
                    return Response::json(array(), 200);
                } else {	
                    return 'success';
                }
			 } else {
			    if (isset($input['api'])) {
                    return Response::json(array(), 200);
                } else {	
                    return 'pro_succ';
                } 
			 }	
            }
        } catch (Cartalyst\Sentry\Users\UserNotActivatedException $e) {		
            if (isset($input['api'])) {
              	return 'check_activation_email';
		 	  die;
            } else {
                return 'check_activation_email';
		 	  die;
            }
        } catch (Cartalyst\Sentry\Throttling\UserSuspendedException $e) {			
            if (isset($input['api'])) {
               return 'account_suspended';
		 	  die;
            } else {
			    return 'account_suspended';
		 	  die;
            }
        } catch(Exception $e) {
            if (isset($input['api'])) {
                 return 'invalid_username_pw';
		 	  die;
            } else {
               return 'invalid_username_pw';
		 	  die;
            }
        }   
		} else {
			return 'Somthing wrong,try again';
		}   
	  }
	}

	

    /**
     * Logout action
     * @return Redirect
     */
    public function getLogout()
    {
        Sentry::logout();
        return Redirect::to('/');
    }
	 public function getForgotPassword()
    {
     if (!Sentry::check()) {		 
	  return \View::make('public/default2/forgot-password')->with('title','Password Recovery - Discover Your Event');	
	 } 
	}	

    public function postForgotPassword()
    {
        $input = Input::all();
        $validator = User::validate_reset($input);
        if ($validator->passes()) {
            $user = User::whereEmail($input['email'])->first();
            if ($user) {
                $user = Sentry::findUserByLogin($user->username);
                $resetCode = $user->getResetPasswordCode();
                $data = array(
                            'user_id'   => $user->id,
                            'resetCode' => $resetCode
                        );
                Mail::queue('backend.default.reset_password_email', $data, function($message) use($input, $user) {
                    $message->from(get_setting('email_username'), Setting::value('website_name'))
                            ->to($input['email'], "{$user->full_name}")
                            ->subject('Password reset ');
                });
                return Redirect::back()
                                   ->with('success_message', 'Password reset code has been sent to the email address. Follow the instructions in the email to reset your password.');
            } else {
                return Redirect::back()
                                ->with('error_message', 'No user exists with the specified email address');
            }
        } else {
            return Redirect::back()
                            ->withInput()
                            ->with('error_message', implode('<br>', $validator->messages()->get('email')));
        }
    }	

    public function getResetPassword($id, $token, $target='backend')
    {
        if (Sentry::check()) {
            return Redirect::to($target);
        }
        try {
            $user = Sentry::findUserById($id);

            $this->layout = View::make($target . '.'.$this->current_theme.'._layouts._login');
            $this->layout->title = 'Reset Password';

            if ($user->checkResetPasswordCode($token)) {
                $this->layout->content = View::make($target . '.'.$this->current_theme.'.reset_password')
                                                ->with('id', $id)
                                                ->with('token', $token)
                                                ->with('target', $target)
                                                ->with('user', $user);
            } else {
                $this->layout->content = View::make($target . '.'.$this->current_theme.'.reset_password')
                                                ->withErrors(array(
                                                        'invalid_reset_code'=>'The provided password reset code is invalid'
                                                    ));
            }
        } catch (Cartalyst\Sentry\Users\UserNotFoundException $e) {
            $this->layout->content = View::make($target . '.'.$this->current_theme.'.reset_password')
                                            ->withErrors('The specified user doesn\'t exist');
        }
    }
	
	 public function getResetfPassword($id, $token)
    { $target = 'logInSignUp';
        if (Sentry::check()) {
            return Redirect::to($target);
        }
        try {
            $user = Sentry::findUserById($id);

            //$this->layout = View::make('public.default2._layouts._login');
            //$this->layout->title = 'Reset Password';

    if ($user->checkResetPasswordCode($token)) {
	 return \View::make('public/default2/reset_password')->with('title', 'Reset Password - DiscoverYourEvent')
	 ->with('id', $id)->with('token', $token)->with('target', $target)->with('user', $user);
            } else {
	return \View::make('public/default2/reset_password')->with('title', 'Reset Password - DiscoverYourEvent')
	 ->withErrors(array(
                                                        'invalid_reset_code'=>'The provided password reset code is invalid'
                                                    ));
            }
        } catch (Cartalyst\Sentry\Users\UserNotFoundException $e) {
          return \View::make('public/default2/reset_password')->with('title', 'Reset Password - DiscoverYourEvent')
                                            ->withErrors('The specified user doesn\'t exist');
        }
    }

    public function postResetPassword()
    {
        $input = Input::all();

        try {
            $user = Sentry::findUserById($input['id']);

            if ($input['email'] != $user->email) {
                return Redirect::back()->withInput()
                                    ->with('error_message', 'Either the email is incorrect');
            }

            if ($user->checkResetPasswordCode($input['token'])) {
                if ($user->attemptResetPassword($input['token'], $input['password'])) {

                    $data = array(
                            'user_id'      => $user->id,
                            'created_at' => strtotime($user->created_at) * 1000
                        );

                    Mail::queue('backend.default.reset_password_confirm_email', $data, function($message) use($input, $user) {
                        $message->from(get_setting('email_username'), Setting::value('website_name'))
                                ->to($user->email, "{$user->full_name}")
                                ->subject('Password Reset Confirmation');
                    });

                    $user->last_pw_changed = date('Y-m-d h:i:s');
                    $user->save();

                    return Redirect::to("logInSignUp")
                                        ->with('success_message', 'Password reset is successful. Now you can log in with your new password');
                } else {
                    return Redirect::back()
                                    ->with('error_message', 'Password reset failed');
                }
            } else {
                return Redirect::back()
                                    ->withErrors(array(
                                            'invalid_reset_code'=>'The provided password reset code is invalid'
                                        ));
            }
        } catch (Cartalyst\Sentry\Users\UserNotFoundException $e) {
            return Redirect::back()
                                ->with('error_message', 'The specified user doesn\'t exist');
        }
    }

    public function suspendUser($user_id, $created_at)
    {
        $user = Sentry::findUserById($user_id);

        if (strtotime($user->created_at) * 1000 == $created_at) {
            $this->user_manager->deactivateUser($user_id);

            return Redirect::to('login/backend')
                                ->with('success_message', 'The user has been suspended.');
        } else {
            return Redirect::to('login/backend')
                                ->with('error_message', 'The user cannot be suspended.');
        }
    }
}
