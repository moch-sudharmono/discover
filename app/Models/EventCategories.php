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

class EventCategories extends Model {
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'event_data';
	
	public function getAllCategories(){
		$evtsdata = array();
		$event_data = 
				$this
				->distinct('event_data.event_category')
				->select( 'event_data.id' , 'event_data.event_category')
				->get();
				
		foreach($event_data as $key => $obj_arr ){
			$evtsdata[$obj_arr->id] = $obj_arr->event_category; 
		}
		
		return $evtsdata;		
	}
	
	public function getEventCategories($filter = null){
		$event_categories = $this;
		
		if(isset($filter["catId"]) && $filter["catId"] != 'ndye'){
			$cat_type = $filter["catId"];
			$event_categories = $event_categories->where("event_data.id" , "=" , $cat_type);
		}	
		
		if(isset($filter["account_type"])){
			$account_type = $filter["account_type"];
			$event_categories = $event_categories->where("event_taxonomy_meta_data.event_cat_acc_type" , "=" , $account_type );
		}
		
		if(isset($filter["cat_type"])){
			$cat_type = $filter["cat_type"];
			$event_categories = $event_categories->where("event_taxonomy_meta_data.event_cat_type" , "=" , $cat_type);
		}	
		
		
		
		$event_categories = $event_categories->join('event_taxonomy_meta_data', 'event_data.id' , '=', 'event_taxonomy_meta_data.event_id');
		$event_categories = $event_categories->select('event_data.id','event_data.event_category');
		$event_categories = $event_categories->get();
		return $event_categories;
	}
}
