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

class Searchevnt extends Model {
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'events';

    public static $accessible = array('order', 'title', 'link', 'parent', 'category', 'position');

    
	public  function searchEvents( $data = array()  , $return = '' , $skip_data = null){
		
		$city_search = $category_search = $day_search = $keyword_search = $location_search = $etype_search = $free_event =  false;
		$event_for_relegion = $event_for_family = $event_for_kids =  $event_cost = $ac_type = $custom_date =  false;
		$event_operation = '=';
		
		$evdate_op = '!=';		
		$event_date = ' '; 
		$evdate_lsop = '<='; 
		$evdate_grop = '>=';
		
		
		if(!empty($data['optional_all'])){
		    $keyword_search = true;
			$keyword = "%".$data['optional_all']."%";
	   }
	  
		if(!empty($data['city'])){
			$city_search = true;
			$rl_op = '=';
			$ev_rl = $data['city']; /** city or zipe code search **/
		}
	
		if(!empty($data['ref_location'])){
		$location_search = "%".$data['ref_location']."%";
	}	
	
		if(!empty($data['eventday'])){
			
			$day_search = true;
				// change statments with while loop
			if($data['eventday'] == 'today'){
				$event_operation = $evdate_op = '=';
				$event_date = date("Y-m-d");
			 }
			else if($data['eventday'] == 'tomorrow'){
					$event_operation = $evdate_op = '=';	 
					$datetime = new \DateTime('tomorrow');
					$event_date = $datetime->format('Y-m-d');  
			} 
			else if($data['eventday'] == 'week'){
			
				$cdate = date("Y-m-d");
				 	
				$event_date = date('Y-m-d',strtotime('next Sunday'));
			}
			else if($data['eventday'] == 'weekend'){
				
				$cdate = date("Y-m-d");
				$event_operation = $evdate_op = '=';
				 if(date('D')!='Sun'){
					$event_date = date('Y-m-d',strtotime('next sunday'));	
				 } else {
					$event_date = date('Y-m-d');	
				 }
			 }
			else { // month
				   $cdate = date("Y-m-d");
				   $event_date = date("Y-m-t", strtotime($cdate));  
			}
	}
		
		 if(!empty($data['event_date'])){
			$custom_date = true;
			$day_search = false;
			$custom_date_value = "%".date('Y-m-d', strtotime($data['event_date'])).'%';		
		} 
		
		if(!empty($data['category']) && is_array($data['category']) && !in_array( "all" , $data['category'])){
			$category_search = true;
			$ev_cat = (is_array($data['category']))?implode("," , $data['category']):$data['category'];
		}
	
		
		if(!empty($data['event_type']) && $data['event_type'] != 'all'){
				$etype_search = true;
				$event_type = $data['event_type'];
				$event_cost = "paid";
				switch($event_type){
					case 'free':
						 $free_event = true;	
						 $event_cost = "free";
					break;
					case 'kids':
						 $event_for_kids = true;
						 $kid_event = "y";	
					break;
					case 'family':
						 $event_for_family = true;
						 $family_event = "y";
					break;
					case 'religious':
						$event_for_relegion = true;
						$religious_event = 'y';
					break;
						$etype_search = false;
					default:
				}
		}

		
		if(!empty($data['search_page']) && $data["search_page"] != "all"){
			$ac_type = true;
			$account_type = $data["search_page"];
		}
		
		  /****
		  **** get only paginated results
		  ***/
			$refine_events =  $this;
			$refine_events = $refine_events->where('events.private_event', '!=', 'y');
			$refine_events = $refine_events->where('events.user_evdelete', '=', 'n');
			
			if($keyword_search == true){
					$refine_events = $refine_events->where( function ($query) use ( $keyword ) {
											$query->where("event_name", 'like', "%".$keyword."%" )
											->orwhere("event_venue",  'like', "%".$keyword."%" );
								} );
			}
			
			if($location_search){
				$refine_events = $refine_events->where('events.event_venue', "like"  , $location_search);
			}
			
			if($city_search){// city or zip code
				$refine_events = $refine_events->where( function ($query) use ( $ev_rl ) {
											$query->where("city", 'like', "%".$ev_rl."%" )
											->orwhere("zip_code",  'like', "%".$ev_rl."%" );
								});
			}
			
			if(($data['eventday'] == 'week' || $data['eventday'] == 'month') && $day_search == true){
				$refine_events = $refine_events->where('events.event_date', $evdate_grop, $cdate);
				$refine_events = $refine_events->where('events.end_date', $evdate_lsop, $event_date);
			}
			else if($day_search == true){
				$refine_events = $refine_events->where('events.event_date', $evdate_op, $event_date);
			}
			
			if(!empty($data['evtype']) && $data['evtype'] != 'both'){ // indoor , outdoor type
				$evtype = $data['evtype'];
				$refine_events->where( "events.event_type" , "=" , $evtype);
			}
			
			
			if($etype_search){	// for people type kids, family , religious or private
				$refine_events = $refine_events->whereEventCost($event_cost);
				
				if($event_for_kids){
					$refine_events = $refine_events->whereKidEvent("y");
				}
				if($event_for_family){
					$refine_events = $refine_events->whereFamilyEvent("y");
				}
				if($event_for_relegion){
					$refine_events = $refine_events->whereReligiousEvent("y");
				}	
			}
			
			if($category_search){ // TODO test this make it work for multiple categories
				if(is_array($data['category']) && !empty($data['category'])){
					$refine_events = $refine_events->whereIn( 'event_data.id', $data['category'] );
				}
				else{
					$refine_events = $refine_events->where('event_data.event_category', "=" , $ev_cat);
				}
				
				
				$refine_events = $refine_events->join('event_data', 'events.event_catid', '=', 'event_data.id');
			}
			
			
			if( $custom_date ){
				$refine_events = $refine_events->where('events.event_date',  "like" , $custom_date_value);
			}
			
			if($ac_type){
				$refine_events = $refine_events->where( "events.account_type" , "=" , $account_type);
			}
			
                        if( $skip_data ){
                            $refine_events = $refine_events->skip($skip_data);
                            $refine_events = $refine_events->take(8);
                        }
                        
                        
                        $refine_events = $refine_events->join('countries', 'events.country', '=', 'countries.id');
                        
                        $refine_events = $refine_events->select('events.id','events.event_name','events.account_type','events.event_url','events.event_image','events.event_venue','events.event_address','events.address_secd','events.city','events.state','events.country','events.event_dtype','events.event_date','events.event_time','events.event_cost','events.event_price' , 'countries.name as country_name' ,'countries.name as country_code');

                        $refine_events = $refine_events->orderBy('id', 'desc');


                        if($return == "sql"){
                                $refine_events = $refine_events->toSql();
                                \DB::enableQueryLog();
                                return dd($refine_events);
                        }
                        else if($return == "limit"){
                                        $limit = (isset($data["results"]) && $data["results"] > 0)?$data["results"]:5;
                                        return 	$refine_events = $refine_events->paginate($limit);
                        }else{
                                return $refine_events->get();
                        }
	}


	public function getAllCategories(){
		$evtsdata = array();
		$event_data = 
				\DB::table('event_data')
				->distinct('event_data.event_category')
				->select( 'event_data.id' , 'event_data.event_category')
				->get();
		foreach($event_data as $key => $obj_arr ){
			$evtsdata[$obj_arr->id] = $obj_arr->event_category; 
		}
		//$evtsdata = array_unique($et_dt);
		
		return $evtsdata;		
	}	
}
