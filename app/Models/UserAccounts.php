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

class UserAccounts extends Model {

    /**
     * The database table used by the model.
     * @var string
     */
    protected $table = 'user_accounts';

    /**
     * The attributes excluded from the model's JSON form.
     * @var array
     */
    protected $hidden = array('password');
	
    protected $id;
	public $account_did;
	
	public $status;
	public $updated_at;
	

    // Path in the public folder to upload image and its corresponding thumbnail

	public function __construct(){
		$this->updated_at = date('Y-m-d H:i:s');
	}
	
	
	public function getAccountDetails($data = array()){
		
				$account_url = (isset($data["account_url"] ) && $data["account_url"] != "")?$data["account_url"]:false;
				
		
				$user	=	$this->where('user_accounts.u_id', '=', $this->id);
								// ->Where('user_accounts.status', '=', 'open')
								// ->orWhere('user_accounts.status', '=', 'transfer');
				$keyword = "";				 
				$user	=	 $this->where(function ($query) use ( $keyword ) {
										$query->where("user_accounts.status", '=', "open")
										->orwhere("user_accounts.status", "=" , "transfer" );
								});
								 
				if($account_url){				
					$user	=	$this->where('account_details.account_url', '=', $account_url);
				}
				
			
                            
					$user	=$user->join('account_details', 'user_accounts.account_did', '=', 'account_details.id')
                            ->select('account_details.id', 'account_details.g_id', 'account_details.account_url', 'user_accounts.status', 'user_accounts.account_urole')
							->first();
				return $user;			
	}
	
	public function closeAccount(){
		if(empty($this->account_did)){
			return false;
		}

		//DB::table('user_accounts')->where('account_did', '=', $account_did)->update(['status' => 'close']);
	return 	$this
			->where('account_did', '=', $this->account_did)
			->update(['status' => 'close' , 'updated_at' => $this->updated_at]);
	}	

}
