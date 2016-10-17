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

class AccountDetails extends Model {
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'account_details';
	
	//public $id;
	
	public function getAccountById(){
		
		
		if(empty($this->id)){
				return false;
		}
		
		$result = $this
				->where('id', '=', $this->id)
				->select('id', 'name')
				->get();
				
		return $result;		
	}	
	
	
        public function getCurrentUserAccountDetails(){
            $id = \Sentry::getUser()->id;
           return $this
                 ->where('account_details.u_id', '!=', $id)
                 ->select('account_details.id', 'account_details.name', 'account_details.account_url')
                 ->orderBy('account_details.name', 'ASC')
                 ->get();
        }
	
}
