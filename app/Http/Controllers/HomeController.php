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

use Modules\Doptor\Slideshow\Models\Slideshow;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;
use App\Models\Cgroups;
use App\Models\Searchevnt;
use App\Models\Event;
use App\Models\UsersFollow;
use App\Models\AccountDetails;
use App\Models\NovComet;

class HomeController extends BaseController {

    public function index() {

        $page                   = Post::where('permalink', '=', 'welcome')->first();
        $user_account_city      = '';
        $this->layout->title    = 'Welcome To Discover Your Event';
        $cdate                  = date('Y-m-d');
        $hoevdat_grop           = '>=';
        $hocdate                = date("Y-m-d");
        $hoevdate_lsop          = '<=';
        $hoevent_dat            = date('Y-m-d', strtotime('next Sunday'));

        $usergroup              = new Cgroups;
        $Event                  = new Event;
        $UsersFollow            = new UsersFollow;
        $AccountDetails         = new AccountDetails;
        $searchModel            = new Searchevnt();
        $all_address            = null;
        $ucuntry                = null;
        $rsearch_detail         = null;
        $maddressArray          = null;
        $morgTitleArray         = null;
        $morg_surlArray         = null;
		
		$evtsdata               = $searchModel->getAllCategories();
		
        if (Sentry::check()) {

            Session::forget('selaccount');
            $lg_id = Sentry::getUser()->id;

            $uaccount_dts = $usergroup->getUserAccountDetails();
            if ($uaccount_dts) {
                $user_account_city = $uaccount_dts->city;
            }

            $event_filter               = array();
            $event_filter['results']    = 8;

            if (!empty($user_account_city) && $user_account_city != '') {

                $event_filter["city"]       = $user_account_city;
                $event_filter['eventday']   = 'week';

                $all_events = $Event->searchEvents($event_filter, "limit");
                $full_event = $Event->searchEvents($event_filter);

                if (sizeof($all_events) < 1) {
                    $event_filter["city"] = "";
                    $event_filter['eventday'] = "";
                    $all_events = $Event->searchEvents($event_filter, "limit");
                    $full_event = $Event->searchEvents($event_filter);
                }
            } else {

                $all_events = $Event->searchEvents($event_filter, "limit");
                $full_event = $Event->searchEvents($event_filter);
            }

            $attend_event = $Event->getAttendingEvents();
			
            /** get users following * */
            $fl_acc     = $UsersFollow->getUserFollow();
            $unfl_acc   = $AccountDetails->getCurrentUserAccountDetails();
            
            $home_blade = 'index_login';


            /**************
             * main-map
             * ************ */


            if ($uaccount_dts != null) {

                if (!empty($user_account_city)) {
                    if (!empty($all_address)) {
                        $all_address .= ',' . $user_account_city;
                    } else {
                        $all_address = $user_account_city;
                    }
                    $rsearch_detail = $user_account_city;
                }

                if (!empty($uaccount_dts->statename)) {

                    

                    if (!empty($all_address)) {
                        $all_address .= ',' . $uaccount_dts->statename;
                    } else {
                        $all_address = $uaccount_dts->statename;
                    }
                  
                }
                if (!empty($uaccount_dts->country)) {

                    if (!empty($all_address)) {
                        $all_address .= ',' . $uaccount_dts->country;
                    } else {
                        $all_address = $uaccount_dts->country;
                    }

                    $ucuntry = $uaccount_dts->country;
                    // }
                }

                if (isset($full_event[0]) && !empty($full_event[0]->id)) {

                    $xx = 1;

                    foreach ($full_event as $key => $flce) {

                        $lfalc_add = null;

                        if (!empty($flce->event_venue)) {
                            $lfalc_add .= $flce->event_venue;
                        }

                        if (!empty($flce->event_address)) {
                            if (!empty($lfalc_add)) {
                                $lfalc_add .= ',' . $flce->event_address;
                            } else {
                                $lfalc_add = $flce->event_address;
                            }
                        }
                        if (!empty($flce->address_secd)) {
                            if (!empty($lfalc_add)) {
                                $lfalc_add .= ',' . $flce->address_secd;
                            } else {
                                $lfalc_add = $flce->address_secd;
                            }
                        }
                        if (!empty($flce->city)) {
                            if (!empty($lfalc_add)) {
                                $lfalc_add .= ',' . $flce->city;
                            } else {
                                $lfalc_add = $flce->city;
                            }
                        }

                        if (!empty($flce->state)) {

                            $staten = $flce->state;
                            if (!empty($lfalc_add)) {
                                $lfalc_add .= ',' . $staten;
                            } else {
                                $lfalc_add = $staten;
                            }
                        }

                        if (!empty($flce->country_name)) {

                            if (!empty($lfalc_add)) {
                                $lfalc_add .= ',' . $flce->country_name;
                            } else {
                                $lfalc_add = $flce->country_name;
                            }
                        }



                        $maddressArray[] = $lfalc_add;
                        $morgTitleArray[] = $flce->event_name;
                        $morg_surlArray[] = $flce->event_url;
                        $xx++;
                    }
                }
            }
            
            /********************end-mmap************* */

            
            $u_upcevent = $Event->getAttendingEvents();

            $addressArray = null;
            $orgTitleArray = null;
            $org_surlArray = null;
                
            /************************left-map************************** */
            if (isset($u_upcevent[0]) && !empty($u_upcevent[0]->id)) {
                $x = 1;
                foreach ($u_upcevent as $key => $uce) {
                    $lfal_add = null;
                    if (!empty($uce->event_venue)) {
                        $lfal_add .= $uce->event_venue;
                    }
                    if (!empty($uce->event_address)) {
                        if (!empty($lfal_add)) {
                            $lfal_add .= ',' . $uce->event_address;
                        } else {
                            $lfal_add = $uce->event_address;
                        }
                    }
                    if (!empty($uce->address_secd)) {
                        if (!empty($lfal_add)) {
                            $lfal_add .= ',' . $uce->address_secd;
                        } else {
                            $lfal_add = $uce->address_secd;
                        }
                    }
                    if (!empty($uce->city)) {
                        if (!empty($lfal_add)) {
                            $lfal_add .= ',' . $uce->city;
                        } else {
                            $lfal_add = $uce->city;
                        }
                    }

                    if (!empty($uce->state) && !empty($uce->country_name)) {
                        $staten = $uce->state;
                        if (!empty($staten)) {
                            if (!empty($lfal_add)) {
                                $lfal_add .= ',' . $staten;
                            } else {
                                $lfal_add = $staten;
                            }
                        }
                    }
                    if (!empty($uce->country_name)) {
                        
                            if (!empty($uce->country_name)) {
                                $lfal_add .= ',' . $uce->country_name;
                            } else {
                                $lfal_add = $uce->country_name;
                            }
                    }
                    
                    $addressArray[] = $lfal_add;
                    $orgTitleArray[] = $uce->event_name;
                    $org_surlArray[] = $uce->event_url;
                    $x++;
                }
            } 
			
            /*             
            * 
            *********************end-lmap***************************
            * 
            */
					$this->layout->content = 
							View::make('public.' . $this->current_theme . '.' . $home_blade)
        							->with('page', $page)
        							->with('attend_event', $attend_event)
        							->with('al_event', $all_events)
                                    ->with('clid', $lg_id)
        							->with('fl_acc', $fl_acc)
        							->with('unfl_acc', $unfl_acc)
        							->with('evtsdata', $evtsdata)
                                    ->with('all_address', $all_address)
        							->with('upcevnt', $u_upcevent)
        							->with('addressArray', $addressArray)
        							->with('ucuntry', $ucuntry)
        							->with('rsearch_detail', $rsearch_detail)
                                    ->with('orgTitleArray', $orgTitleArray)
        							->with('org_surlArray', $org_surlArray)
        							->with('full_event', $full_event)
                                    ->with('maarray', $maddressArray)
        							->with('mTiAr', $morgTitleArray)
        							->with('mog_slar', $morg_surlArray);
        }
        else {
            
            $date["result"] = 8;
            $all_events = $Event->searchEvents($date , "limit");
            $event_date = $Event->getAllCategories();
            $home_blade = 'index';
            $this->layout->content = 
							View::make('public.' . $this->current_theme . '.' . $home_blade)
							->with('page', $page)
							->with('al_event', $all_events)
							->with('evtsdata', $evtsdata);
        }
    }

    public function postClocation(Request $request) {
        $data = $request->input();
        if (!empty($data['curlocation'])) {
            $lat_long = $data['curlocation'];
            $url = "http://maps.googleapis.com/maps/api/geocode/json?latlng=" . $lat_long . "&sensor=false";
            // Make the HTTP request
            $data = @file_get_contents($url);
            // Parse the json response
            $jsondata = json_decode($data, true);

            function check_status($jsondata) {
                if ($jsondata["status"] == "OK")
                    return true;
                return false;
            }

            // If the json data is invalid, return empty array
            if (!check_status($jsondata))
                return array();
            //$LatLng = $jsondata["results"][0]["formatted_address"];
            $LatLng = $jsondata["results"][0]['address_components'];
            $current_add = null;
            $sca = null;
            foreach ($LatLng as $add_comp) {
                if ($add_comp['types'][0] == 'administrative_area_level_2') {
                    $current_add .= $add_comp['long_name'];
                    // $sca .= $add_comp['short_name'];
                }
                if ($add_comp['types'][0] == 'administrative_area_level_1') {
                    $sca .= $add_comp['short_name'];
                    if (!empty($current_add)) {
                        $current_add .= ', ' . $add_comp['long_name'];
                    } else {
                        $current_add .= $add_comp['long_name'];
                    }
                }
                if ($add_comp['types'][0] == 'country') {
                    if (!empty($current_add)) {
                        // $current_add .= ', '.$add_comp['long_name'];  
                        $sca .= ', ' . $add_comp['short_name'];
                    } else {
                        //$current_add .= $add_comp['long_name']; 
                        $sca .= $add_comp['short_name'];
                    }
                }
            }
            return $current_add . '~' . $sca;
            die;
        }
    }

    public function getImglocation($latlong) {
        if (!empty($latlong)) {
            $url = "http://maps.googleapis.com/maps/api/geocode/json?latlng=" . $latlong . "&sensor=false";
            $data = @file_get_contents($url);
            $jsondata = json_decode($data, true);

            function check_status($jsondata) {
                if ($jsondata["status"] == "OK")
                    return true;
                return false;
            }

            if (!check_status($jsondata))
                return array();
            $LatLng = $jsondata["results"][0]['address_components'];
            foreach ($LatLng as $add_comp) {
                if ($add_comp['types'][0] == 'administrative_area_level_1') {
                    $simg = strtolower($add_comp['short_name']);
                }
                if ($add_comp['types'][0] == 'country') {
                    $cimg = strtolower($add_comp['short_name']);
                }
            }
            $home_sdata = DB::table('home_slide')->where('country_code', '=', $cimg)->where('city_name', '=', $simg)->get();
            // 'uploads/city_photos/'.$cimg.'ca_ab.jpg';
            if (!empty($home_sdata[0]->id)) {
                $xx = 0;
                foreach ($home_sdata as $hsd) {
                    $hs_img = asset("uploads/city_photos/" . $hsd->country_code . "/" . $hsd->image);
                    if ($xx == 0) {
                        echo '<li style="background-image: url(' . $hs_img . '); opacity: 1;"><img id="default-' . $hsd->id . '" src="' . $hs_img . '"></li>';
                    } else {
                        echo '<li style="opacity: 0;"><img id="default-' . $hsd->id . '" src="' . $hs_img . '"></li>';
                    }
                    $xx++;
                }
            } else {
                $vdefault = array('ca_ab.jpg', 'ca_on_ottawa.jpg', 'ca_on_toronto.jpg', 'us_ca_la.jpg', 'us_ct.jpg', 'us_nv_vegas.jpg');
                $x = 0;
                foreach ($vdefault as $vdf) {
                    $burl = asset('uploads/city_photos/' . $vdf);
                    if ($x == 0) {
                        echo '<li style="background-image: url(' . $burl . '); opacity: 1;"><img id="default-' . $x . '" src="' . $burl . '"></li>';
                    } else {
                        echo '<li style="opacity: 0;"><img id="default-' . $x . '" src="' . $burl . '"></li>';
                    }
                    $x++;
                }
            }
            die;
        }
    }

    public function globalComet($dyetimestamp) {
        if (Sentry::check()) {
            $lg_id = Sentry::getUser()->id;
            $dtstamp = explode("&", $dyetimestamp);
            if (!empty($dtstamp[0])) {
                $comet = new NovComet();
                $comet->setVar('notificationAlert', $dtstamp[0]);
                echo $comet->run($lg_id);
            }
        }
        die;
    }

    public function globalNotification($ids) {
        if (Sentry::check() && !empty($ids)) {
            $lg_id = Sentry::getUser()->id;
            $dataid = explode(",", $ids);
            if (!empty($dataid[0])) {
                $xs = 0;
                foreach ($dataid as $dsid) { //->where('id', '=', $dsid)
                    $notfdata = DB::table('notifications')->where('onr_id', '=', $lg_id)->where('is_read', '=', 0)->orderBy('id', 'desc')->take(10)->get();
                    if (!empty($notfdata[0]->id)) {
                        foreach ($notfdata as $nd) {
                            if ($nd->global_read == 1) {
                                $rd_stats = 'notif-acc';
                            } else {
                                $rd_stats = 'notif-acc active';
                            }
                            if ($nd->type == 'event') {
                                $evntlu = DB::table('events')->where('user_evdelete', '=', 'n')->where('id', '=', $nd->object_id)->select('event_url')->get();
                                $nldurl = url("/event/" . $evntlu[0]->event_url);
                                if ($xs == 0) {
                                    $arvs = '<li id="notf-' . $nd->id . '"><div class="' . $rd_stats . '"><a href="' . $nldurl . '">' . $nd->subject . '</a>
			  <a class="notf-close" href="javascript:void(0)" onClick="sRead(' . $nd->id . ');"><span class="fa fa-times"></span></a></div></li>';
                                } else {
                                    $arvs .= '<li id="notf-' . $nd->id . '"><div class="' . $rd_stats . '"><a href="' . $nldurl . '">' . $nd->subject . '</a>
			  <a class="notf-close" href="javascript:void(0)" onClick="sRead(' . $nd->id . ');"><span class="fa fa-times"></span></a></div></li>';
                                }
                            } else if ($nd->type == 'page') {
                                $evntlu = DB::table('account_details')->where('id', '=', $nd->object_id)->select('account_url')->get();
                                $nldurl = url("/" . $evntlu[0]->account_url);
                                if ($xs == 0) {
                                    $arvs = '<li id="notf-' . $nd->id . '"><div class="' . $rd_stats . '"><a href="' . $nldurl . '">' . $nd->subject . '</a>
			  <a class="notf-close" href="javascript:void(0)" onClick="sRead(' . $nd->id . ');"><span class="fa fa-times"></span></a></div></li>';
                                } else {
                                    $arvs .= '<li id="notf-' . $nd->id . '"><div class="' . $rd_stats . '"><a href="' . $nldurl . '">' . $nd->subject . '</a>
			  <a class="notf-close" href="javascript:void(0)" onClick="sRead(' . $nd->id . ');"><span class="fa fa-times"></span></a></div></li>';
                                }
                            } else {
                                if ($xs == 0) {
                                    $arvs = '<li id="notf-' . $nd->id . '"><div class="' . $rd_stats . '"><a href="#">' . $nd->subject . '</a>
			  <a class="notf-close" href="javascript:void(0)" onClick="sRead(' . $nd->id . ');"><span class="fa fa-times"></span></a></div></li>';
                                } else {
                                    $arvs .= '<li id="notf-' . $nd->id . '"><div class="' . $rd_stats . '"><a href="#">' . $nd->subject . '</a>
			  <a class="notf-close" href="javascript:void(0)" onClick="sRead(' . $nd->id . ');"><span class="fa fa-times"></span></a></div></li>';
                                }
                            }
                            $xs++;
                        }
                    }
                }
            } else { //->where('id', '=', $dsid)
                $notfdata = DB::table('notifications')->where('onr_id', '=', $lg_id)->where('is_read', '=', 0)->orderBy('id', 'desc')->take(10)->get();
                if (!empty($notfdata[0]->id)) {
                    if ($notfdata[0]->global_read == 1) {
                        $rd_stats = 'notif-acc';
                    } else {
                        $rd_stats = 'notif-acc active';
                    }
                    if ($notfdata[0]->type == 'event') {
                        $evntlu = DB::table('events')->where('user_evdelete', '=', 'n')->where('id', '=', $notfdata[0]->object_id)->select('event_url')->get();
                        $nldurl = url("/event/" . $evntlu[0]->event_url);
                        $arvs = '<li id="notf-' . $notfdata[0]->id . '"><div class="' . $rd_stats . '"><a href="' . $nldurl . '">' . $notfdata[0]->subject . '</a>
	   <a class="notf-close" href="javascript:void(0)" onClick="sRead(' . $notfdata[0]->id . ');"><span class="fa fa-times"></span></a></div></li>';
                    } else if ($notfdata[0]->type == 'page') {
                        $evntlu = DB::table('account_details')->where('id', '=', $notfdata[0]->object_id)->select('account_url')->get();
                        $nldurl = url("/" . $evntlu[0]->account_url);
                        $arvs = '<li id="notf-' . $notfdata[0]->id . '"><div class="' . $rd_stats . '"><a href="' . $nldurl . '">' . $notfdata[0]->subject . '</a>
	   <a class="notf-close" href="javascript:void(0)" onClick="sRead(' . $notfdata[0]->id . ');"><span class="fa fa-times"></span></a></div></li>';
                    } else {
                        $arvs = '<li id="notf-' . $notfdata[0]->id . '"><div class="' . $rd_stats . '"><a href="#">' . $notfdata[0]->subject . '</a>
	    <a class="notf-close" href="javascript:void(0)" onClick="sRead(' . $notfdata[0]->id . ');"><span class="fa fa-times"></span></a></div></li>';
                    }
                }
            }
            $anct = DB::table('notifications')->where('onr_id', '=', $lg_id)->where('is_read', '=', 0)->count();
            if (isset($anct)) {
                $acvs = $anct;
            } else {
                $acvs = 0;
            }
            if (isset($arvs)) {
                return array('nlist' => $arvs, 'ncount' => $acvs);   //json_encode(array('n' => $ids, 'svdata' => $arvs));	
            } else {
                return 0;
            }
        }
        die;
    }

    public function readNotification($ids) {
        if (Sentry::check() && !empty($ids)) {
            $lg_id = Sentry::getUser()->id;
            DB::delete('delete from notifications WHERE id = ' . $ids . ' && onr_id = ' . $lg_id);
            return 1;
        }
        return 2;
        die;
    }

    public function globalRdNotif($counts) {
        if (Sentry::check() && !empty($counts)) {
            $lg_id = Sentry::getUser()->id;
            $notfdata = DB::table('notifications')->where('onr_id', '=', $lg_id)->where('global_read', '=', 0)->orderBy('id', 'desc')->take($counts)->select('id')->get();
            if (!empty($notfdata[0]->id)) {
                foreach ($notfdata as $ntfd) {
                    DB::table('notifications')->where('id', '=', $ntfd->id)->update(['global_read' => 1, 'updated_at' => date('Y-m-d H:i:s')]);
                }
            }
            return 1;
        }
        return 2;
        die;
    }

    public function wrapper($menu_id) {
        $menu = Menu::findOrFail($menu_id);
        $this->layout->title = $menu->title;
        $this->layout->content = View::make('public.' . $this->current_theme . '.wrapper')
                ->with('menu', $menu);
    }

    public function getContact() {
        $contact = Post::type('page')
                ->where('permalink', 'contact')
                ->first();

        $this->layout->title = 'Contact Us';

        $this->layout->content = View::make('public.' . $this->current_theme . '.contact')
                ->with('contact', $contact);
    }

    public function postContact() {
        $input = Input::all();

        $rules = array(
            'email' => 'required|min:5|email',
            'name' => 'required|alpha|min:5',
        );

        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {
            return Redirect::back()
                            ->withErrors($validator)
                            ->withInput();
        }

        try {
            Mail::send('public.' . $this->current_theme . '.email', $input, function($message) use($input) {
                $message->from($input['email'], $input['name']);
                $message->to(Setting::value('email_username'), $input['name'])
                        ->subject($input['subject']);
            });
        } catch (Exception $e) {
            return Redirect::back()
                            ->withInput()
                            ->with('error_message', $e->getMessage());
        }

        return Redirect::back()
                        ->with('success_message', 'The mail was sent.');
    }

}
