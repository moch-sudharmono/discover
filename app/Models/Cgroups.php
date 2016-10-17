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


class Cgroups extends Model {
	
	//protected $guarded = array();

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'groups';

    public static $rules = array(
            'name'     => 'alpha_spaces|required|unique:groups,name'
        );

    /**
     * Validation during create/update of groups
     * @param  array $input Input received from the form
     * @return Validator
     */
    public static function validate($input, $id=false)
    {
        if ($id) {
            static::$rules['name'] .= ','.$id;
        }
        return Validator::make($input, static::$rules);
    }

	/**
     * Relation with the menus table
     * A user group can have many menus
     */
    public function menus()
    {
        return $this->belongsToMany('Menu');
    }
	
	
	public function getUserAccountDetails(){

		$lg_id = \Sentry::getUser()->id;
                
		$groupDetails =	$this
			->where('u_id','=', $lg_id)
			->join('group_details', 'groups.id', '=', 'group_details.g_id')
                        ->join('states', 'group_details.state', '=', 'states.id')
                        ->join('countries', 'group_details.country', '=', 'countries.id')
			->select('group_details.address','group_details.city','group_details.state','group_details.country','group_details.zip_code' , 'states.name as statename' , 'countries.name as country' ,'countries.name as country_code')
			->first();
               
		return $groupDetails;
		//DB::table('group_details')->where('u_id','=', $lg_id)
	}
}
