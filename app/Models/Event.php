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
use Carbon\Carbon;

class Event extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
   protected $table    = 'events';
    public $st_cvs      = array("12:00am", 
                                "12:30am", 
                                "01:00am", 
                                "01:30am", 
                                "02:00am", 
                                "02:30am", 
                                "03:00am", 
                                "03:30am", 
                                "04:00am", 
                                "04:30am", 
                                "05:00am",
                                "05:30am", 
                                "06:00am", 
                                "06:30am", 
                                "07:00am", 
                                "07:30am", 
                                "08:00am", 
                                "08:30am", 
                                "09:00am", 
                                "09:30am", 
                                "10:00am", 
                                "10:30am", 
                                "11:00am", 
                                "11:30am",
                                "12:00pm", 
                                "12:30pm", 
                                "01:00pm", 
                                "01:30pm", 
                                "02:00pm", 
                                "02:30pm", 
                                "03:00pm", 
                                "03:30pm", 
                                "04:00pm", 
                                "04:30pm", 
                                "05:00pm", 
                                "05:30pm", 
                                "06:00pm",
                                "06:30pm", 
                                "07:00pm", 
                                "07:30pm", 
                                "08:00pm", 
                                "08:30pm", 
                                "09:00pm", 
                                "09:30pm", 
                                "10:00pm", 
                                "10:30pm", 
                                "11:00pm", 
                                "11:30pm");

    public $md_arr      = array("1" => '1st', 
                                "2" => '2nd', 
                                "3" => '3rd', 
                                "4" => '4th', 
                                "5" => '5th', 
                                "6" => '6th', 
                                "7" => '7th', 
                                "8" => '8th', 
                                "9" => '9th',
                                "10" => '10th', 
                                "11" => '11th', 
                                "12" => '12th', 
                                "13" => '13th', 
                                "14" => '14th', 
                                "15" => '15th', 
                                "16" => '16th', 
                                "17" => '17th',
                                "18" => '18th', 
                                "19" => '19th', 
                                "20" => '20th', 
                                "21" => '21st', 
                                "22" => '22nd', 
                                "23" => '23rd', 
                                "24" => '24th', 
                                "25" => '25th',
                                "26" => '26th', 
                                "27" => '27th', 
                                "28" => '28th', 
                                "29" => '29th', 
                                "30" => '30th', 
                                "31" => '31st');

    public $tmf         = array("12:00" => '12:00 am', 
                                "12:30" => '12:30 am', 
                                "01:00" => '01:00 am', 
                                "01:30" => '01:30 am', 
                                "02:00" => '02:00 am',
                                "02:30" => '02:30 am', 
                                "03:00" => '03:00 am', 
                                "03:30" => '03:30 am', 
                                "04:00" => '04:00 am', 
                                "04:30" => '04:30 am', 
                                "05:00" => '05:00 am',
                                "05:30" => '05:30 am', 
                                "06:00" => '06:00 am', 
                                "06:30" => '06:30 am', 
                                "07:00" => '07:00 am', 
                                "07:30" => '07:30 am', 
                                "08:00" => '08:00 am',
                                "08:30" => '08:30 am', 
                                "09:00" => '09:00 am', 
                                "09:30" => '09:30 am', 
                                "10:00" => '10:00 am', 
                                "10:30" => '10:30 am', 
                                "11:00" => '11:00 am',
                                "11:30" => '11:30 am', 
                                "24:00" => '12:00 pm', 
                                "24:30" => '12:30 pm', 
                                "13:00" => '01:00 pm', 
                                "13:30" => '01:30 pm', 
                                "14:00" => '02:00 pm',
                                "14:30" => '02:30 pm', 
                                "15:00" => '03:00 pm', 
                                "15:30" => '03:30 pm', 
                                "16:00" => '04:00 pm', 
                                "16:30" => '04:30 pm', 
                                "17:00" => '05:00 pm',
                                "17:30" => '05:30 pm', 
                                "18:00" => '06:00 pm', 
                                "18:30" => '06:30 pm', 
                                "19:00" => '07:00 pm', 
                                "19:30" => '07:30 pm', 
                                "20:00" => '08:00 pm',
                                "20:30" => '08:30 pm', 
                                "21:00" => '09:00 pm', 
                                "21:30" => '09:30 pm', 
                                "22:00" => '10:00 pm', 
                                "22:30" => '10:30 pm', 
                                "23:00" => '11:00 pm',
                                "23:30" => '11:30 pm');

    public $sdwkfmt     = array("0" => 'Sunday', 
                                "1" => 'Monday', 
                                "2" => 'Tuesday', 
                                "3" => 'Wednesday', 
                                "4" => 'Thursday', 
                                "5" => 'Friday', 
                                "6" => 'Saturday');

    public $ofday       = array("0" => 'Same day', 
                                "1" => 'Next day', 
                                "2" => '2nd day', 
                                "3" => '3rd day', 
                                "4" => '4th day', 
                                "5" => '5th day', 
                                "6" => '6th day');

    public $allowed_image = array('image/png', 
                                    'image/gif', 
                                    'image/jpeg', 
                                    'image/jpg', 
                                    'image/bmp');

    public function searchEvents($data = array(), $return = '') {

        $city_search = $category_search = $day_search = $keyword_search = $location_search = $etype_search = $free_event = false;
        $event_for_relegion = $event_for_family = $event_for_kids = $event_cost = $ac_type = $custom_date = false;

        $event_operation    = '=';
        $evdate_op          = '!=';
        $event_date         = ' ';
        $evdate_lsop        = '<=';
        $evdate_grop        = '>=';

        $cdate = date("Y-m-d");

        if (!empty($data['optional_all'])) {
            $keyword_search = true;
            $keyword = "%" . $data['optional_all'] . "%";
        }

        if (!empty($data['city'])) {
            $city_search = true;
            $rl_op = '=';
            $ev_rl = $data['city'];/** city or zipe code search * */
        }

        if (!empty($data['ref_location'])) {
            $location_search = "%" . $data['ref_location'] . "%";
        }

        if (!empty($data['eventday'])) {

            $day_search = true;
            // change statments with while loop
            if ($data['eventday'] == 'today') {
                $event_operation = $evdate_op = '=';
                $event_date = date("Y-m-d");
            } else if ($data['eventday'] == 'tomorrow') {
                $event_operation = $evdate_op = '=';
                $datetime = new \DateTime('tomorrow');
                $event_date = $datetime->format('Y-m-d');
            } else if ($data['eventday'] == 'week') {
                $event_date = date('Y-m-d', strtotime('next Sunday'));
            } else if ($data['eventday'] == 'weekend') {


                $event_operation = $evdate_op = '=';
                if (date('D') != 'Sun') {
                    $event_date = date('Y-m-d', strtotime('next sunday'));
                } else {
                    $event_date = date('Y-m-d');
                }
            } else { // month
                $event_date = date("Y-m-t", strtotime($cdate));
            }
        }

        if (!empty($data['event_date'])) {
            $custom_date = true;
            $day_search = false;
            /*$custom_date_value = "%" . date('Y-m-d', strtotime($data['event_date'])) . '%';*/
            $custom_date_value = date('Y-m-d', strtotime($data['event_date']));
        }

        if (!empty($data['category']) && is_array($data['category']) && !in_array("all", $data['category'])) {
            $category_search = true;
            $ev_cat = (is_array($data['category'])) ? implode(",", $data['category']) : $data['category'];
        }


        if (!empty($data['event_type']) && $data['event_type'] != 'all') {
            $etype_search = true;
            $event_type = $data['event_type'];
            $event_cost = "paid";
            switch ($event_type) {
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


        if (!empty($data['search_page']) && $data["search_page"] != "all") {
            $ac_type = true;
            $account_type = $data["search_page"];
        }

        /*         * **
         * *** get only paginated results
         * * */
        $refine_events = $this;
        $refine_events = $refine_events->where('events.private_event', '!=', 'y');
        $refine_events = $refine_events->where('events.user_evdelete', '=', 'n');

        if ($keyword_search == true) {
            $refine_events = $refine_events->where(function ($query) use ( $keyword ) {
                $query->where("event_name", 'like', "%" . $keyword . "%")
                        ->orwhere("event_venue", 'like', "%" . $keyword . "%");
            });
        }

        if ($location_search) {
            $refine_events = $refine_events->where('events.event_venue', "like", $location_search);
        }

        if ($city_search) {// city or zip code
            $refine_events = $refine_events->where(function ($query) use ( $ev_rl ) {
                $query->where("city", 'like', "%" . $ev_rl . "%")
                        ->orwhere("zip_code", 'like', "%" . $ev_rl . "%");
            });
        }

        if (isset($data['eventday']) && ($data['eventday'] == 'week' || $data['eventday'] == 'month') && $day_search == true) {
            $refine_events = $refine_events->where('events.event_date', $evdate_grop, $cdate);
            $refine_events = $refine_events->where('events.end_date', $evdate_lsop, $event_date);
        } else if ($day_search == true) {
            $refine_events = $refine_events->where('events.event_date', $evdate_op, $event_date);
        }

        if (!empty($data['evtype']) && $data['evtype'] != 'both') { // indoor , outdoor type
            $evtype = $data['evtype'];
            $refine_events->where("events.event_type", "=", $evtype);
        }


        if ($etype_search) { // for people type kids, family , religious or private
            $refine_events = $refine_events->whereEventCost($event_cost);

            if ($event_for_kids) {
                $refine_events = $refine_events->whereKidEvent("y");
            }
            if ($event_for_family) {
                $refine_events = $refine_events->whereFamilyEvent("y");
            }
            if ($event_for_relegion) {
                $refine_events = $refine_events->whereReligiousEvent("y");
            }
        }

        if ($category_search) { // TODO test this make it work for multiple categories
            if (is_array($data['category']) && !empty($data['category'])) {
                $refine_events = $refine_events->whereIn('event_data.id', $data['category']);
            } else {
                $refine_events = $refine_events->where('event_data.event_category', "=", $ev_cat);
            }


            $refine_events = $refine_events->join('event_data', 'events.event_catid', '=', 'event_data.id');
        }

        // $refine_events = $refine_events->join('states', 'group_details.state', '=', 'states.id');
        $refine_events = $refine_events->join('countries', 'events.country', '=', 'countries.id');

        if ($custom_date) {
            $refine_events = $refine_events->where('events.event_date', ">", $custom_date_value);
        }else{

            $mytime = Carbon::now();
            $today  = $mytime->toDateTimeString();

            $refine_events = $refine_events->where('events.event_date', ">", $today);
        }

        if ($ac_type) {
            $refine_events = $refine_events->where("events.account_type", "=", $account_type);
        }

        $refine_events = $refine_events->select('events.id', 'events.event_name', 'account_id', 'events.account_type', 'events.event_url', 'events.event_image', 'events.event_venue', 'events.event_address', 'events.address_secd', 'events.city', 'events.state', 'events.country', 'events.event_dtype', 'events.event_date', 'events.event_time', 'events.event_cost', 'events.event_price', 'countries.name as country_name', 'countries.name as country_code');

        $refine_events = $refine_events->orderBy('id', 'desc');

        if ($return == "sql") {
            $refine_events = $refine_events->toSql();
            \DB::enableQueryLog();
            return dd($refine_events);
        } else if ($return == "limit") {
            $limit = (isset($data["results"]) && $data["results"] > 0) ? $data["results"] : 5;
            return $refine_events = $refine_events->paginate($limit);
        } else {
            return $refine_events->get();
        }
    }

    public function getAllCategories() {
        $evtsdata = array();
        $event_data = \DB::table('event_data')
                ->distinct('event_data.event_category')
                ->select('event_data.id', 'event_data.event_category')
                ->get();
        foreach ($event_data as $key => $obj_arr) {
            $evtsdata[$obj_arr->id] = $obj_arr->event_category;
        }
        //$evtsdata = array_unique($et_dt);
        return $evtsdata;
    }

    public function getEventByUrl($eventurl = null) {
        if ($eventurl == null)
            return false;

        $events = $this
                        ->where('events.user_evdelete', '=', 'n')
                        ->where('events.event_url', '=', $eventurl)
                        ->join('event_data', 'events.event_catid', '=', 'event_data.id')
                        ->select('events.id as eid', 'events.account_type', 'events.account_id', 'events.event_catid', 'events.contact_person', 'events.phone_no', 'events.email_address', 'events.website', 'events.event_name', 'events.event_url', 'events.event_image', 'events.event_venue', 'events.event_address', 'events.address_secd', 'events.city', 'events.state', 'events.country', 'events.zip_code', 'events.map_show', 'events.event_dtype', 'events.event_date', 'events.event_time', 'events.end_date', 'events.end_time', 'events.event_cost', 'events.event_price', 'events.ticket_surl', 'events.event_description', 'events.fb_link', 'events.tw_link', 'events.private_event', 'events.password_estatus', 'events.pass_event', 'event_data.event_category', 'event_data.id as ed_id')->get();

        return $events;
    }

    public function getEventCountries() {
        $countries = \DB::table('countries')->select('id', 'name')->get();
        return $countries;
    }

    public function uploadEventImage($input_file = null) {

        $status = "success";
        $return = $data = array();
        $rid = \Str::random(6);
        $destinationPath = 'public/uploads/events/personal/';
        if ($input_file == null)
            return false;


        $mime_type = $input_file['event_image'][0]->getMimeType();
        $event_filesize = $input_file['event_image'][0]->getClientSize();

        if ($event_filesize > 2000001) {
            //return Redirect::back()->with('failed_upfile', 'Please upload max file size of 2mb.')->withInput();
            $status = "error";
            $message = "failed_upfile', 'Please upload max file size of 2mb.";
        }

        if (!in_array($mime_type, $this->allowed_image)) {
            $status = "error";
            $message = 'Image type not supported, please upload (png/gif/jpeg/jpg/bmp) format';
            //return Redirect::back()->with('failed_upfile', 'Image type not supported, please upload (png/gif/jpeg/jpg/bmp) format')->withInput(); 
        }

        if ($status == "success") {
            //'event_image'=>'mimes:png,gif,jpeg,jpg,bmp',
            $extension = $input_file['event_image'][0]->getClientOriginalExtension();
            $filename = basename($input_file['event_image'][0]->getClientOriginalName(), "." . $extension) . '_' . $rid . '.' . $extension;
            $upload_success = $input_file['event_image'][0]->move($destinationPath, $filename);
            $data["filename"] = $filename;
            $message = "uploaded successfully";
        }

        return array("status" => $status, "message" => $message, "data" => $data);
    }

    public function createEvent($data = null) {
        $id = $this->insertGetId($data);
        return $id;
    }

    function getAttendingEvents() {

        $lg_id = \Sentry::getUser()->id;
        // ->select('events.id as eid', 'events.account_type', 'events.account_id', 'events.event_catid', 'events.contact_person', 'events.phone_no', 'events.email_address', 'events.website', 'events.event_name', 'events.event_url', 'events.event_image', 'events.event_venue', 'events.event_address', 'events.address_secd', 'events.city', 'events.state', 'events.country', 'events.zip_code', 'events.map_show', 'events.event_dtype', 'events.event_date', 'events.event_time', 'events.end_date', 'events.end_time', 'events.event_cost', 'events.event_price', 'events.ticket_surl', 'events.event_description', 'events.fb_link', 'events.tw_link', 'events.private_event', 'events.password_estatus', 'events.pass_event', 'event_data.event_category', 'event_data.id as ed_id')->get();
        $attend_event = $this
                        ->where('user_evdelete', '=', 'n')
                        ->where('users_events.u_id', '=', $lg_id)
                        ->join('users_events', 'events.id', '=', 'users_events.e_id')
                        ->join('event_data', 'events.event_catid', '=', 'event_data.id')
                        ->join('countries', 'events.country', '=', 'countries.id')
                        ->select('events.id', 'events.account_type', 'events.account_id', 
                                'events.event_catid', 'events.contact_person', 'events.phone_no',
                                'events.email_address', 'events.website', 'events.event_name', 'events.event_url', 
                                'events.event_image', 'events.event_venue', 'events.event_address', 'events.address_secd', 
                                'events.city', 'events.state', 'events.country', 'events.zip_code', 'events.map_show', 
                                'events.event_dtype', 'events.event_date', 'events.event_time', 'events.end_date', 'events.end_time', 
                                'events.event_cost', 'events.event_price', 'events.ticket_surl', 'events.event_description', 'events.fb_link',
                                'events.tw_link', 'events.private_event', 'events.password_estatus', 'events.pass_event', 'event_data.event_category',
                                'event_data.id as ed_id', 'event_name', 'event_url', 'event_image', 'event_venue', 'event_address', 
                                'address_secd', 'city', 'state', 'country', 'event_date', 'end_date', 'countries.name as country_name',
                                'countries.name as country_code')
                        ->orderBy('event_name', 'ASC')
                        ->get();
		return $attend_event;
						
		
    }

    public function getCurrentUserEvents() {
        $id = \Sentry::getUser()->id;
        $events = $this;

        $events = $events
                ->where('user_accounts.u_id', '=', $id)
                ->Where('user_accounts.status', '=', 'open')
                ->orWhere('user_accounts.status', '=', 'transfer')
                ->join('event_data', 'events.event_catid', '=', 'event_data.id')
                ->join('user_accounts', 'user_accounts.id', '=', 'events.account_id')
                ->join('account_details', 'user_accounts.account_did', '=', 'account_details.id')
                ->select('account_details.id', 'account_details.g_id', 'account_details.account_url', 'user_accounts.account_urole')
                ->orderBy('id', 'DESC')
                ->get();
        return $events;
    }

    public function getUserPersonalEvents( $data = array(), $paginate = 10) {

        $acd_id = (isset($data["account_id"]) && $data["account_id"]) ? $data["account_id"] : false;
        $account_type = (isset($data["account_type"]) && $data["account_type"]) ? $data["account_type"] : "personal";
        $eid = (isset($data["eid"]) && $data["eid"]) ? $data["eid"] : false;
        $limit = (isset($data["limit"]) && $data["limit"]) ? $data["limit"] : false;
        $u_id = (isset($data["u_id"]) && $data["u_id"]) ? $data["u_id"] : false;



        $clid = \Sentry::getUser()->id;
        $all_event = $this;

        $all_event = $all_event->where('u_id', '=', $clid);

        if ($acd_id) {
            $all_event = $all_event->where('account_id', '=', $acd_id);
        }
        if ($eid) {
            $all_event = $all_event->where('events.id', '=', $eid);
        }
		if ($u_id) {
            $all_event = $all_event->where('events.u_id', '=', $u_id);
        }
		
		

        $all_event = $all_event->where('user_evdelete', '=', 'n');

        // $all_event = $all_event->where('account_type', '=', $account_type);
        // 
        $all_event = $all_event->join('event_data', 'events.event_catid', '=', 'event_data.id');

        $all_event = $all_event->select('events.id as id', 'events.account_type', 'events.account_id', 'events.event_catid', 'events.event_type', 'events.contact_person', 'events.phone_no', 'events.email_address', 'events.website', 'events.event_name', 'events.event_url', 'events.event_image', 'events.event_venue', 'events.event_address', 'events.address_secd', 'events.city', 'events.state', 'events.country', 'events.zip_code', 'events.map_show', 'events.event_dtype', 'events.event_date', 'events.event_time', 'events.end_date', 'events.end_time', 'events.event_cost', 'events.event_price', 'events.ticket_surl', 'events.event_description', 'events.fb_link', 'events.tw_link', 'events.private_event', 'event_data.event_category', 'event_data.id as ed_id', 'events.available_purchase', 'events.kid_event', 'events.family_event', 'events.religious_event', 'events.share_event', 'events.password_estatus', 'events.pass_event');
        $all_event = $all_event->orderBy('events.id', 'DESC');

        if ($limit)
            $all_event = $all_event->paginate($paginate);
        else
            $all_event = $all_event->get();

        return $all_event;
    }

}
