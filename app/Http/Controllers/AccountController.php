<?php 
use Services\UserManager;
use Services\UserGroupManager;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;


class AccountController extends Controller {
	
    protected $user_manager;
    protected $usergroup_manager;

    public function __construct(UserManager $user_manager, UserGroupManager $usergroup_manager)
    {
        $this->user_manager = $user_manager;
        $this->usergroup_manager = $usergroup_manager;
    }
	
/**************************User-Profile************************************/	
	public function postAccount(Request $request)
    {
    if(Sentry::check()){
		$id 			= Sentry::getUser()->id; 
		$profile_photo 	= Sentry::getUser()->photo;
		$data 			= $request->input();		
		$validator 		= Validator::make($request->all(), [	
          'full_name' 	=> 'required|max:255',
          'email' 		=> 'required|email',
          'address' 	=> 'required',
          'country' 	=> 'required',
		  'state' 		=> 'required',
		  'city' 		=> 'required',
          'zip_code' 	=> 'required|min:6|max:6',		  
		  'phone' 		=> 'required|min:10',          
		  'photo'		=> 'mimes:png,gif,jpeg,bmp'
        ]);	
        
		if ($validator->fails()) {
          	return Redirect::back()->withErrors($validator)->withInput(); 
        } else {        	
		  	$prof_exitem = DB::table('users')
		  					->where('id', '!=', $id)
		  					->where('email', '=', $data['email'])
		  					->select('id')
		  					->get();			 	

			if(!empty($prof_exitem[0]->id)){
			  	return Redirect::back()->with('email_error', 'The email has already been taken.')->withInput();	 			 
        	} else {
        					 
        		DB::table('users')
        			->where('id', '=', $id)
        			->update([	'full_name' 	=> $data['full_name'], 
        						'email' 		=> $data['email'],
        						'updated_at' 	=> date('Y-m-d H:i:s')
        					]);		         

				DB::table('group_details')
					->where('u_id', '=', $id)
					->update([	'address' 		=> $data['address'], 
								'country' 		=> $data['country'], 
								'state' 		=> $data['state'], 
								'city' 			=> $data['city'], 
								'zip_code' 		=> $data['zip_code'], 
								'phone' 		=> $data['phone'], 
								'updated_at' 	=> date('Y-m-d H:i:s')
							]); 

				return Redirect::back()
					->with('succ_mesg', 'Your information updated successfully');       
	  		}
	  	}

     } else {

		return Redirect::to('/');

	}
    } 
	
	 public function postEvpgList(Request $request)
    {
	 if(Sentry::check()){
	     $uid = Sentry::getUser()->id; 
	    $data = $request->input();
		if(!empty($data['evntsdata'])){
	   $unfl_acc = DB::table('account_details')->where('u_id', '!=', $uid)->where('name', 'like', '%'.$data['evntsdata'].'%')->select('id','name','account_url')->orderBy('name','ASC')->get();
     if(!empty($unfl_acc[0]->id)){
	  foreach($unfl_acc as $unflw_acc){
       $unfep = DB::table('user_follows')->where('u_id', '=', $uid)->where('follow_id', '=', $unflw_acc->id)->distinct('follow_id')->select('id')->get();   
      if(empty($unfep[0]->id)){    
	   echo $asdata = '<div class="evnt-pic col-md-12 col-ms-12 col-xs-12"><div class="evnt-pic col-md-6 col-ms-6 col-xs-6">	
		 <h4>'.$unflw_acc->name.'</h4></div><div class="evnt-pic col-md-6 col-ms-6 col-xs-6">
         <a href="'.URL($unflw_acc->account_url).'" class="btn btn-primary" target="_blank">View Page</a>	
         <a onclick="followEvent('.$unflw_acc->id.')" href="javascript:void(0)" class="btn btn-primary">Follow</a>
		</div></div>';	
       } 
	  }  
	 } else {
      echo $asdata = '<div class="evnt-pic col-md-12 col-ms-12 col-xs-12"><div class="evnt-pic col-md-6 col-ms-6 col-xs-6">empty!</div></div>';
	 }
	 } else {
		 $unfl_acc = DB::table('account_details')->where('u_id', '!=', $uid)->select('id','name','account_url')->orderBy('name','ASC')->get();
     if(!empty($unfl_acc[0]->id)){
	  foreach($unfl_acc as $unflw_acc){
       $unfep = DB::table('user_follows')->where('u_id', '=', $uid)->where('follow_id', '=', $unflw_acc->id)->distinct('follow_id')->select('id')->get();   
      if(empty($unfep[0]->id)){    
	   echo $asdata = '<div class="evnt-pic col-md-12 col-ms-12 col-xs-12"><div class="evnt-pic col-md-6 col-ms-6 col-xs-6">	
		 <h4>'.$unflw_acc->name.'</h4></div><div class="evnt-pic col-md-6 col-ms-6 col-xs-6">
         <a href="'.URL($unflw_acc->account_url).'" class="btn btn-primary" target="_blank">View Page</a>	
         <a onclick="followEvent('.$unflw_acc->id.')" href="javascript:void(0)" class="btn btn-primary">Follow</a>
		</div></div>';	
       } 
	  }  
	 }
	 }
	 } else {
	  return Redirect::to('/');	 
	 }	
	}
	
	 public function postEvagList(Request $request)
    {
	 if(Sentry::check()){
	     $uid = Sentry::getUser()->id; 
	    $data = $request->input();
	 if(!empty($data['evtsdata'])){
	  if($data['evtsdata'] == 'a'){	  
       $unfl_acc = DB::table('users_events')->where('users_events.u_id', '=', $uid)->where('events.user_evdelete', '=', 'n')->join('events', 'users_events.e_id', '=', 'events.id')
	  ->select('events.id','events.event_name','events.account_id','events.account_type','events.event_url','events.event_image','events.event_venue','events.event_address',
	  'events.event_date','events.event_time','events.event_cost','events.event_price')->orderBy('event_name', 'ASC')->get();
	  } else if($data['evtsdata'] == 'z') {
		$unfl_acc = DB::table('users_events')->where('users_events.u_id', '=', $uid)->where('events.user_evdelete', '=', 'n')->join('events', 'users_events.e_id', '=', 'events.id')
	  ->select('events.id','events.event_name','events.account_id','events.account_type','events.event_url','events.event_image','events.event_venue','events.event_address',
	  'events.event_date','events.event_time','events.event_cost','events.event_price')->orderBy('event_name', 'DESC')->get();  
	  } else if($data['evtsdata'] == '1') {
		$unfl_acc = DB::table('users_events')->where('users_events.u_id', '=', $uid)->where('events.user_evdelete', '=', 'n')->join('events', 'users_events.e_id', '=', 'events.id')
	  ->select('events.id','events.event_name','events.account_id','events.account_type','events.event_url','events.event_image','events.event_venue','events.event_address',
	  'events.event_date','events.event_time','events.event_cost','events.event_price')->orderBy('event_date', 'ASC')->get();  
	  } else {
		$unfl_acc = DB::table('users_events')->where('users_events.u_id', '=', $uid)->where('events.user_evdelete', '=', 'n')->join('events', 'users_events.e_id', '=', 'events.id')
	  ->select('events.id','events.event_name','events.account_id','events.account_type','events.event_url','events.event_image','events.event_venue','events.event_address',
	  'events.event_date','events.event_time','events.event_cost','events.event_price')->orderBy('event_date', 'DESC')->get();    
	  }
	  
     if(!empty($unfl_acc[0]->id)){
	   foreach($unfl_acc as $att_evnt){  
        echo $asdata = '<div class="evnt-pic col-md-12 col-ms-12 col-xs-12">  
		  	<div class="evnt-pic col-md-6 col-ms-6 col-xs-6">	
			 <h4>'.ucfirst($att_evnt->event_name).'</h4></div>
            <div class="evnt-pic col-md-6 col-ms-6 col-xs-6">				  
			 <a href="'.URL('event/'.$att_evnt->event_url).'" class="btn btn-primary" target="_blank">View Event</a>
			 <a onclick="unattend('.$att_evnt->id.')" href="javascript:void(0)" class="btn btn-primary">Unattend</a>
		    </div></div>';	
	   }
	  } else {
		echo $asdata = '<div class="evnt-pic col-md-12 col-ms-12 col-xs-12"><div class="evnt-pic col-md-6 col-ms-6 col-xs-6">Empty...</div></div>';  
	  }
	 } 	  
	 } else {
	  return Redirect::to('/');	 
	 }	
	}
	
/**************************User-Account************************************/	
	 public function postCreatedacc(Request $request)
    {
	 if(Sentry::check()){
		 // Sentry::getUser()->id; 
		  $data = $request->input();		
		$validator = Validator::make($request->all(), [	
          'name' => 'required',
		  'account_url' => 'required',	    
		  'phone' => 'required|min:10',
          'email' => 'required|email',
          'address' => 'required',
          'city' => 'required',
		  'state' => 'required',
          'country' => 'required',
          'zip_code' => 'required|min:6|max:6',
		  'upload_file'=>'mimes:png,gif,jpeg,bmp',
        ]);		
		if ($validator->fails()) {
          return Redirect::back()->withErrors($validator)->withInput(); 
        } else {
		  $c_exitun = DB::table('account_details')->where('id', '!=', $data['acc_id'])->where('name', '=', $data['name'])->select('id')->get();	
		  $aul_exitun = DB::table('account_details')->where('id', '!=', $data['acc_id'])->where('account_url', '=', $data['account_url'])->select('id')->get();	
		 
		 if(!empty($c_exitun[0]->id) || !empty($aul_exitun[0]->id)){
		 if(!empty($c_exitun[0]->id)){
		  return Redirect::back()->with('name_error', 'The name has already been taken.')->withInput();	 
		 }	 
		 if(!empty($aul_exitun[0]->id)){
		  return Redirect::back()->with('acurl_error', 'The Account URL has already been taken.')->withInput();	 
		 }		  
         } else {			 
			$input_file = $request->file(); 
		  $get_adtls = DB::table('account_details')->where('id', '=', $data['acc_id'])->select('id','g_id','upload_file')->get();	
		 if(!empty($get_adtls[0]->id)){ 
		 if(!empty($input_file)){			 	
		 $u_filesize = $input_file['upload_file']->getClientSize();	
		  if ($u_filesize < 2000001) { 
			
		 if($get_adtls[0]->g_id == 2) {
		  $imgs_apath = 'business';
		 } elseif($get_adtls[0]->g_id == 5) { 
		  $imgs_apath = 'municipality';
         } else {
		  $imgs_apath = 'club';
		 }
		 
		  //Upload Image
			$rid = Str::random(7);		 
            $destinationPath = 'public/uploads/account_type/'.$imgs_apath.'/';	
			
	        $filename = $input_file['upload_file']->getClientOriginalName();
	        $mime_type = $input_file['upload_file']->getMimeType();
	        $extension = $input_file['upload_file']->getClientOriginalExtension();
	        $filename = basename($input_file['upload_file']->getClientOriginalName(), ".".$extension).'_'.$rid.'.'.$extension;
	        $upload_success = $input_file['upload_file']->move($destinationPath, $filename);


			/***************resize-image****************/
			    // and insert a watermark for example
                //$img->insert('public/watermark.png');
			  /*$rsfname = basename($input_file['upload_file']->getClientOriginalName(), ".".$extension).'_'.$rid.'_200x200.'.$extension;
			  $rspath = public_path('uploads/account_type/'.$imgs_apath.'/'.$rsfname);
			  Image::make($destinationPath.$filename)->resize(200, 200)->save($rspath);				 */
			/******************************************/
				
             File::delete($destinationPath.$get_adtls[0]->upload_file);		
			
			DB::table('account_details')->where('id', '=', $data['acc_id'])->update(['name' => $data['name'], 'account_url' => $data['account_url'], 'address' => $data['address'], 'city' => $data['city'],
		  'email' => $data['email'], 'state' => $data['state'], 'website' => $data['website'], 'zip_code' => $data['zip_code'], 'upload_file' => $filename, 'country' =>  $data['country'],
		  'phone' => $data['phone'], 'updated_at' => date('Y-m-d H:i:s')]);		   

		  } else {			  
			 return redirect($bladename)->with('error', 'Please upload maximum 2mb size file.')->withInput(); 
			die; 
		  }		
         } else {	 
		  DB::table('account_details')->where('id', '=', $data['acc_id'])->update(['name' => $data['name'], 'account_url' => $data['account_url'], 'address' => $data['address'], 'city' => $data['city'],
		  'email' => $data['email'], 'state' => $data['state'], 'website' => $data['website'], 'zip_code' => $data['zip_code'], 'country' =>  $data['country'], 'phone' => $data['phone'],
		  'updated_at' => date('Y-m-d H:i:s')]);
         }
		   return Redirect::to($data['account_url'].'/account')->with('succ_mesg', 'Your account information updated successfully');
        }	
          return Redirect::back()->with('error', 'Something wrong! please try again')->withInput();
		 } 
		}
     } else {
		  return Redirect::to('/');
	 }
    }
     public function postAccfollow(Request $request)
    {
	 if(Sentry::check()){
	    $uid = Sentry::getUser()->id; 
	   $data = $request->input();
       $get_ownerid = DB::table('account_details')->where('id', '=', $data['ascid'])->select('id','u_id','name','account_url','flemail_update')->get();
     if(!empty($get_ownerid[0]->u_id)){	   
	  $cflw = DB::table('user_follows')->where('follow_id', '=', $data['ascid'])->where('u_id', '=', $uid)->select('id')->get();
	 if(empty($cflw[0]->id)){ 
	  DB::table('user_follows')->insert(['follow_id' => $data['ascid'], 'u_id' => $uid, 'owner_id' => $get_ownerid[0]->u_id,
	  'follow_type' => 'account', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')]); 	  
	   $notfsub = '<span class="ntf-uname">You have an new person</span> follow a page <span class="ntf-data">"'.$get_ownerid[0]->name.'"</span>'; 	  
	  DB::table('notifications')->insert(['onr_id' => $get_ownerid[0]->u_id,'u_id' => $uid,'type' => 'page','subject' => $notfsub,
	 'object_id' => $data['ascid'],'object_type'=> 'follow','created_at' => date('Y-m-d H:i:s'),'updated_at' => date('Y-m-d H:i:s')]);
	 }
	 
  if(!empty($get_ownerid[0]->id)){
	 $onrinfo = DB::table('users')->where('id', '=', $get_ownerid[0]->u_id)->select('full_name','email')->get();	 
   if($get_ownerid[0]->flemail_update == 'y'){	 
	 /***********mail*************/ 
	 $acc_name = $get_ownerid[0]->name;
			$Subject = "You have an new person follow a page - Discover Your Event";
              $pagurl = url($get_ownerid[0]->account_url);		
             $joinurl = url('logInSignUp/');  			  
			$emdata = array(
	            'uname'      => $onrinfo[0]->full_name,
				'event_name' => $get_ownerid[0]->name.' account page',
				'un_uname'   => Sentry::getUser()->full_name,
				'mess'       => 'follow',
				'jnurl'      => $pagurl,
                'joinurl' => $joinurl,					
		    );   
	 $SendToEmail = $onrinfo[0]->email;			
	 Mail::send('email.pagenotf_email', $emdata, function($message) use ($SendToEmail, $Subject)
	{
	  $message->to($SendToEmail)->subject($Subject);
	});  
	} 
   }	 
      /*****************/	
	  return 'success';	  
	 }       	  
	 } else {
	  return 'error';	 
	 }	
	}
	
	 public function postUnfollow(Request $request)
    {
	 if(Sentry::check()){
	    $uid = Sentry::getUser()->id; 
	   $data = $request->input();
       $check_flws = DB::table('user_follows')->where('id', '=', $data['flwid'])->where('u_id', '=', $uid)->select('id','follow_id','owner_id')->get();	  
     if(!empty($check_flws[0]->id)){	   
	 // DB::delete('delete from user_follows WHERE id = '.$check_flws[0]->id);    
	  DB::table('user_follows')->where('id', '=', $check_flws[0]->id)->update(['follow' => 'n', 'updated_at' => date('Y-m-d H:i:s')]);	
	  $get_ownerid = DB::table('account_details')->where('id', '=', $check_flws[0]->follow_id)->select('id','u_id','name','account_url','flemail_update')->get();
	   $notfsub = '<span class="ntf-uname">You have an person </span> unfollow a page <span class="ntf-data">"'.$get_ownerid[0]->name.'"</span>'; 	 
	  DB::table('notifications')->insert(['onr_id' => $check_flws[0]->owner_id,'u_id' => $uid,'type' => 'page','subject' => $notfsub,
	 'object_id' => $check_flws[0]->follow_id,'object_type'=> 'follow','created_at' => date('Y-m-d H:i:s'),'updated_at' => date('Y-m-d H:i:s')]);
	 
  if(!empty($get_ownerid[0]->id)){
	 $onrinfo = DB::table('users')->where('id', '=', $get_ownerid[0]->u_id)->select('full_name','email')->get();	 
   if($get_ownerid[0]->flemail_update == 'y'){	 
	 /***********mail*************/ 
	 $acc_name = $get_ownerid[0]->name;
			$Subject = "You have an person unfollow a page - Discover Your Event";
              $pagurl = url($get_ownerid[0]->account_url);		
             $joinurl = url('logInSignUp/');  			  
			$emdata = array(
	            'uname'      => $onrinfo[0]->full_name,
				'event_name' => $get_ownerid[0]->name.' account page',
				'un_uname'   => Sentry::getUser()->full_name,
				'mess'       => 'unfollow',
				'jnurl'      => $pagurl,
                'joinurl' => $joinurl,					
		    );   
	 $SendToEmail = $onrinfo[0]->email;			
	 Mail::send('email.pagenotf_email', $emdata, function($message) use ($SendToEmail, $Subject)
	{
	  $message->to($SendToEmail)->subject($Subject);
	});  
	} 
   } 
	  return 'succ';
	 } else {
	  return 'error';	 
	 }       	  
	 } else {
	  return Redirect::to('/');	 
	 }	
	}
	
  public function getScdata($getdata, $id)
  {
	if(!empty($getdata)){
	  if($getdata == 'state'){
		 $state_data = DB::table('states')->where('country_id', '=', $id)->select('id','name')->get();  
		if(!empty($state_data[0]->id)){	
           echo '<option value="'.$state_data[0]->id.'" selected>'.$state_data[0]->name.'</option>';		
		 foreach($state_data as $sd){ 
		  if($sd->id != $state_data[0]->id){
		   echo '<option value="'.$sd->id.'">'.$sd->name.'</option>';
		  } 
		 }
		}
       die;		
	  } else {
		 $city_data = DB::table('cities')->where('state_id', '=', $id)->select('id','name')->get();  
		if(!empty($city_data[0]->id)){			
		 foreach($city_data as $cd){ 
		  echo '<option value="'.$cd->id.'">'.$cd->name.'</option>';
		 }
		} else {
		 echo '<option value="">Not available</option>';	
		}
       die;	  
	  }
	}  
  }
}