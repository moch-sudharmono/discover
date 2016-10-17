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
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class UsersFollow extends Model {

    /**
     * The database table used by the model.
     * @var string
     */
    protected $table = 'user_follows';

    /**
     * The attributes excluded from the model's JSON form.
     * @var array
     */
    protected $hidden = array('password');

    // Path in the public folder to upload image and its corresponding thumbnail
    public static $images_path = 'uploads/users/';

    public function deleteFollow(){
			//$this->
//DB::delete('delete from user_follows WHERE follow_id = ' . $id . ' && u_id = ' . $id . ' && follow_type = account');	
	}
   public function getUserFollow(){
       $lg_id = \Sentry::getUser()->id;    
       $result =  $this  
                  ->where('user_follows.u_id', '=', $lg_id)
                  ->where('user_follows.follow', '=', 'y')
                  ->join('account_details', 'user_follows.follow_id', '=', 'account_details.id')
                  ->distinct('user_follows.follow_id')
                  ->select('user_follows.id', 'account_details.name', 'account_details.account_url')
                  ->orderBy('account_details.name', 'ASC')
                  ->get();
       return $result;
   }     
}
