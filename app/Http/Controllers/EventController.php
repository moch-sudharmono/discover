<?php

use Services\UserManager;
use Services\UserGroupManager;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Searchevnt;
use App\Models\Event;
use App\Models\EventCategories;
use App\Models\UserAccounts;

class EventController extends Controller {

    protected $user_manager;
    protected $usergroup_manager;

    public function __construct(UserManager $user_manager, UserGroupManager $usergroup_manager) {
        $this->user_manager = $user_manager;
        $this->usergroup_manager = $usergroup_manager;
    }

    public function createEvent() {

        if (Sentry::check()) {

            $eventModel = new Event;
            $md_arr = $eventModel->md_arr;
            $tmf = $eventModel->tmf;
            $sdwkfmt = $eventModel->sdwkfmt;
            $ofday = $eventModel->ofday;
            $st_cvs = $eventModel->st_cvs;

            $countries = $eventModel->getEventCountries();

            return \View::make('public/default2/account/create-event')
                            ->with('cuntd', $countries)
                            ->with('title', 'Create Event - DiscoverYourEvent')
                            ->with('month_darray', $md_arr)
                            ->with('tmformat', $tmf)
                            ->with('sdwkfmt', $sdwkfmt)
                            ->with('ofheday', $ofday)
                            ->with('st_cvs', $st_cvs)
                            ->with('active', 'ce');
        } else {
            return Redirect::to('/');
        }
    }

    /*     * *
     * * create event 
     * */

    public function postCevent(Request $request) {

        $data = $request->input();
        $clid = Sentry::getUser()->id; // current user id
        $evp_regs = '/^[0-9]+$/';
        $evp_regss = '/^[0-9]+[.][0-9]+$/';
        $all_ep = $ticket_surl = $end_date = $mw_date = null;
        $kid_event = $family_event = $religious_event = $end_date = "n";
        $allowd_image = array('image/png', 'image/gif', 'image/jpeg', 'image/jpg', 'image/bmp');
        $destinationPath = 'public/uploads/events/personal/';
        $eventModel = new Event();

        $validator = Validator::make(
                        $request->all(), array(
                    'event_type' => 'required',
                    'event_catid' => 'required',
                    'email_address' => 'min:4|email',
                    'event_venue' => 'required',
                    'phone_no' => 'min:10',
                    'event_name' => 'required',                    
                    'state' => 'required',
                    'country' => 'required',
                    'city' => 'required',
                    'event_cost' => 'required',
                    'event_description' => 'required',
                    'private_event' => 'required')
        );
        /* 'event_url' => 'unique:events',	 */



        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput();
        } else {
            $rid = Str::random(6);

            if ($data['event_cost'] == 'paid') {
                
                if ($data['event_price'] != '') {

                    if (preg_match($evp_regs, $data['event_price']) || preg_match($evp_regss, $data['event_price'])) {
                        $event_price = $data['event_price'];
                        $all_ep = $event_price;
                    }
                    else {
                        return Redirect::back()->with('req_evprice', 'Cost price value not valid, please used 0-9 or 10.99 format')->withInput();
                    }
                        
                    $ticket_surl = $data['ticket_surl'];
                    if (!empty($data['ticket_surl']) && filter_var($ticket_surl, FILTER_VALIDATE_URL) === false) {
                            return Redirect::back()->with('inv_tkurl', $ticket_surl . ' is not a valid URL, http:// or https:// is required')->withInput();
                    }
                    else {
                        $ticket_surl = null;
                    }
                    
                }
                else {
                    return Redirect::back()->with('req_evprice', 'Cost price is required field')->withInput();
                }

                if (!empty($data['mevent_price'])) {
                    //$evp_reg = '/^[0-9]+[-][0-9]+$/';


                    if (preg_match($evp_regs, $data['mevent_price']) || preg_match($evp_regss, $data['mevent_price'])) {
                        $mevent_price = $data['mevent_price'];
                        $all_ep = $event_price . '-' . $mevent_price;
                    } else {
                        return Redirect::back()->with('req_evprice', 'Cost price value not valid, please used 0-9 or 10.99 format')->withInput();
                    }
                }
            }


            if (isset($data['kid_event'])) {
                $kid_event = 'y';
            }

            if (isset($data['family_event'])) {
                $family_event = 'y';
            }

            if (isset($data['religious_event'])) {
                $religious_event = 'y';
            }

            /*             * ****event-date-section******** */
            if ($data['event_dtype'] == 'single') {

                $ev_date = date('Y-m-d', strtotime($data['sevent_date']));

                if (empty($data['sevent_date'])) {
                    return Redirect::back()->with('ev_date', 'Event Start date field is required')->withInput();
                }
                if (!empty($data['event_date'])) {
                    $end_date = date('Y-m-d', strtotime($data['event_date']));
                }
            } else {

                if (empty($data['arrdate'])) {
                    return Redirect::back()->with('ev_section', 'Event date Section is required')->withInput();
                }
                $ev_date = date('Y-m-d', strtotime($data['arrdate'][0]));
            }



            if (!empty($data['event_url'])) {
                $event_url = $data['event_url'];
            } else {
                $event_url = 'dye-' . $rid;
            }

            if (isset($data['map_show'])) {
                $showmap = 'y';
            } else {
                $showmap = 'n';
            }
            if (isset($data['our_pid']) && !empty($data['our_pid'])) {
                $evid_img = $data['our_pid'];
            } else {
                $evid_img = null;
            }

            if ($data['private_event'] == 'y') {
                if (!isset($data['sharepinvt_event']) && empty($data['sharepinvt_event'])) {
                    return Redirect::back()->with('share_event', 'Please choose one option, its required')->withInput();
                }

                $share_event = $data['sharepinvt_event'];
                if ($share_event == 'n') {
                    if (isset($data['passprv_event'])) {
                        $password_estatus = $data['passprv_event'];
                    } else {
                        $password_estatus = 'n';
                    }

                    if (isset($password_estatus) && $password_estatus == 'y') {
                        $pass_event = $data['pr-passevnt'];
                        if (empty($pass_event)) {
                            return Redirect::back()->with('pev_req', 'Private event password field is required')->withInput();
                            die;
                        }
                    } else {
                        $pass_event = null;
                    }
                } else {
                    $pass_event = null;
                    $password_estatus = 'n';
                }
            } else {
                $share_event = 'n';
                $password_estatus = 'n';
                $pass_event = null;
            }

            $input_file = $request->file();


            if (!empty($input_file['event_image'][0])) {

                $imageUpload = $eventModel->uploadEventImage($input_file);

                $filename = $imageUpload["data"]["filename"];
                //Upload Image






                /*                 * *************resize-image*************** */
                /* $rsfname = basename($input_file['event_image']->getClientOriginalName(), ".".$extension).'_'.$rid.'_200x200.'.$extension;
                  $rspath = public_path('uploads/account_type/'.$imgs_apath.'/'.$rsfname);
                  Image::make($destinationPath.$filename)->resize(200, 200)->save($rspath); */
                /*                 * *************************************** */
                $inserted_array = array(
                    'u_id' => $clid,
                    'account_type' => 'personal',
                    'event_type' => $data['event_type'],
                    'event_catid' => $data['event_catid'],
                    'contact_person' => $data['contact_person'],
                    'phone_no' => $data['phone_no'],
                    'email_address' => $data['email_address'],
                    'website' => $data['website'],
                    'event_name' => $data['event_name'],
                    'event_url' => $event_url,
                    'event_image' => $filename,
                    'event_venue' => $data['event_venue'],
                    'event_address' => $data['event_address'],
                    'address_secd' => $data['address_secd'],
                    'city' => $data['city'],
                    'state' => $data['state'],
                    'country' => $data['country'],
                    'zip_code' => $data['zip_code'],
                    'map_show' => $showmap,
                    'event_dtype' => $data['event_dtype'],
                    'event_date' => $ev_date,
                    'event_time' => $data['sevent_time'],
                    'end_date' => $end_date,
                    'end_time' => $data['eevent_time'],
                    'event_cost' => $data['event_cost'],
                    'event_price' => $all_ep,
                    'ticket_surl' => $ticket_surl,
                    'event_description' => $data['event_description'],
                    'fb_link' => $data['fb_link'],
                    'tw_link' => $data['tw_link'],
                    'kid_event' => $kid_event,
                    'family_event' => $family_event,
                    'religious_event' => $religious_event,
                    'private_event' => $data['private_event'],
                    'share_event' => $share_event,
                    'password_estatus' => $password_estatus,
                    'pass_event' => $pass_event,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                );

                $nw_evid = $eventModel->createEvent($inserted_array);
            } else {
                $inserted_array = array('u_id' => $clid,
                    'account_type' => 'personal',
                    'event_type' => $data['event_type'],
                    'event_catid' => $data['event_catid'],
                    'contact_person' => $data['contact_person'],
                    'phone_no' => $data['phone_no'],
                    'email_address' => $data['email_address'],
                    'website' => $data['website'],
                    'event_name' => $data['event_name'],
                    'event_url' => $event_url,
                    'event_image' => $evid_img,
                    'event_venue' => $data['event_venue'],
                    'event_address' => $data['event_address'],
                    'address_secd' => $data['address_secd'],
                    'city' => $data['city'],
                    'state' => $data['state'],
                    'country' => $data['country'],
                    'zip_code' => $data['zip_code'],
                    'map_show' => $showmap,
                    'event_dtype' => $data['event_dtype'],
                    'event_date' => $ev_date,
                    'event_time' => $data['sevent_time'],
                    'end_date' => $end_date,
                    'end_time' => $data['eevent_time'],
                    'event_cost' => $data['event_cost'],
                    'event_price' => $all_ep,
                    'ticket_surl' => $ticket_surl,
                    'event_description' => $data['event_description'],
                    'fb_link' => $data['fb_link'],
                    'tw_link' => $data['tw_link'],
                    'kid_event' => $kid_event,
                    'family_event' => $family_event,
                    'religious_event' => $religious_event,
                    'private_event' => $data['private_event'],
                    'share_event' => $share_event,
                    'password_estatus' => $password_estatus,
                    'pass_event' => $pass_event,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                );


                $insertedID = $eventModel->createEvent($inserted_array);
                $nw_evid = $insertedID;
            }


            /*             * ****event-date-multi-section******** */
            if (isset($nw_evid) && $data['event_dtype'] == 'multi') {

                $xlx = 0;
                if (sizeof($data['arrdate']) > 1) {

                    foreach ($data['arrdate'] as $stdt) {
                        $startdt = date('Y-m-d', strtotime($stdt));
                        $itcendate = date('Y-m-d', strtotime($data['enddate'][$xlx]));

                        if (!empty($data['mw_date'][$xlx]) && $data['mw_date'][$xlx] != 'Month Date') {
                            $mw_date = $data['mw_date'][$xlx];
                        }


                        DB::table('event_multidate')->insert(array('ev_id' => $nw_evid, 'event_datetype' => $data['nspd'][$xlx], 'start_date' => $startdt, 'enddate' => $itcendate, 'ofthe' => $data['ofthe'][$xlx], 'start_time' => $data['startime'][$xlx], 'st_format' => $data['st_format'][$xlx], 'endtime' => $data['endtime'][$xlx], 'et_format' => $data['et_format'][$xlx], 'total_day' => $data['total_day'][$xlx], 'multype_wm' => $data['wgtdaysvl'][$xlx], 'mw_date' => $mw_date, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')));
                        $xlx++;
                    }
                } else {
                    if (!empty($data['mw_date'][$xlx]) && $data['mw_date'][$xlx] != 'Month Date') {
                        $mw_date = $data['mw_date'][$xlx];
                    }
                    $startdt = date('Y-m-d', strtotime($data['arrdate'][$xlx]));
                    $itcendate = date('Y-m-d', strtotime($data['enddate'][$xlx]));

                    DB::table('event_multidate')->insert(['ev_id' => $nw_evid, 'event_datetype' => $data['nspd'][$xlx], 'start_date' => $startdt, 'enddate' => $itcendate, 'ofthe' => $data['ofthe'][$xlx], 'start_time' => $data['startime'][$xlx], 'st_format' => $data['st_format'][$xlx], 'endtime' => $data['endtime'][$xlx], 'et_format' => $data['et_format'][$xlx], 'total_day' => $data['total_day'][$xlx], 'multype_wm' => $data['wgtdaysvl'][$xlx], 'mw_date' => $mw_date, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')]);
                }
            }
            return Redirect::to('account/manageEvent')->with('succ_mesg', 'Event created successfully');
        }
    }

    /*     * *
     * ** get event categories
     * * */

    public function getEventdata($atype, $cat_type, $etid) {

        $filter = array();
        $return = '';

        //cat_type todo $event_type
        if (!empty($atype) && !empty($cat_type)) {

            if ($atype == 'business') {
                $account_type = 'business';
            } else if ($atype == 'municipality') {
                $account_type = 'municipality';
            } else if ($atype == 'club') {
                $account_type = 'municipality';
            } else {
                $account_type = 'personal';
            }


            $filter["account_type"] = $account_type;
            $filter["cat_type"] = $cat_type;
            $filter["catId"] = $etid;

            $catModel = new EventCategories;
            $eventCategories = $catModel->getEventCategories($filter);

            if (count($eventCategories) <= 0) {
                unset($filter["catId"]);
                $eventCategories = $catModel->getEventCategories($filter);
            }


            if (sizeof($eventCategories) > 0) {
                $return .= '<select name="event_catid" class="event_category">';
                foreach ($eventCategories as $key => $category) {
                    $return .= '<option value="' . $category->id . '">' . $category->event_category . '</option>';
                }
                $return .= '</select>';
            } else {
                $return .= 'empty';
            }

            echo $return;
        }
    }

    public function manageEvent() {
        
        if (Sentry::check()) {
            $Event = new Event;
			 
			 $data = array();
			 $u_id = Sentry::getUser()->id;
            // $data["account_id"] = $clid;
             $data["u_id"] = $u_id;
             //$data["account_type"] = $acc_type;
             $data["limit"] = 10;
				
            $all_event = $Event->getUserPersonalEvents($data , 10);
            
            return \View::make('public/default2/account/manage-event')
                    ->with('title', 'Manage Event - DiscoverYourEvent')
                    ->with('all_event', $all_event )->with('active', 'me');
        } else {
            return Redirect::to('/');
        }
    }

    public function getMevent() {
        
        if (Sentry::check()) {
            $clid = Sentry::getUser()->id;
            $all_event = DB::table('events')
                    ->where('u_id', '=', $clid)
                    ->where('user_evdelete', '=', 'n')
                    ->where('account_type', '=', 'personal')
                    ->select('id', 'account_id', 'account_type', 'event_name', 'event_url', 'event_image', 'event_type', 'event_dtype', 'event_date', 'event_cost', 'event_venue')
                    ->paginate(10);
            
            $Event = new Event;
            
            
            $all_event = $Event->getUserPersonalEvents( 10 );
            return \View::make('public/default2/ajex/get-mevent')->with('all_event', $all_event);
        } else {
            return Redirect::to('/');
        }
    }

    public function getAllevent() {

        if (Sentry::check()) {
            $clid = Sentry::getUser()->id;
            $uaccount_dts = DB::table('group_details')
                    ->where('u_id', '=', $clid)
                    ->select('address', 'city', 'state', 'country', 'zip_code')
                    ->get();

            $hoevdat_grop = '>=';
            $hocdate = date("Y-m-d");
            $hoevdate_lsop = '<=';
            $hoevent_dat = date('Y-m-d', strtotime('next Sunday'));

            if (!empty($uaccount_dts[0]->city)) {
                $all_events = DB::table('events')
                                ->where('user_evdelete', '=', 'n')
                                ->where('private_event', '!=', 'y')
                                ->where('city', '=', $uaccount_dts[0]->city)
                                ->where('event_date', $hoevdat_grop, $hocdate)
                                ->where('event_date', $hoevdate_lsop, $hoevent_dat)
                                ->select('id', 'event_name', 'account_id', 'account_type', 'event_url', 'event_image', 'event_venue', 'event_address', 'address_secd', 'city', 'state', 'country', 'event_dtype', 'event_date', 'event_time', 'event_cost', 'event_price')
                                ->orderBy('id', 'desc')->paginate(8);

                if (sizeof($all_events) < 1) {
                    $all_events = DB::table('events')
                            ->where('user_evdelete', '=', 'n')
                            ->where('private_event', '!=', 'y')
                            ->select('id', 'event_name', 'account_id', 'account_type', 'event_url', 'event_image', 'event_venue', 'event_address', 'address_secd', 'city', 'state', 'country', 'event_dtype', 'event_date', 'event_time', 'event_cost', 'event_price')
                            ->orderBy('id', 'desc')
                            ->paginate(8);
                }
            } else {

                $all_events = DB::table('events')->where('user_evdelete', '=', 'n')->where('private_event', '!=', 'y')->select('id', 'event_name', 'account_id', 'account_type', 'event_url', 'event_image', 'event_venue', 'event_address', 'address_secd', 'city', 'state', 'country', 'event_dtype', 'event_date', 'event_time', 'event_cost', 'event_price')->orderBy('id', 'desc')->paginate(8);
            }
            $womicon = 8;
            return \View::make('public/default2/ajex/lghome-event')
                            ->with('al_event', $all_events)
                            ->with('clid', $clid)
                            ->with('wo', $womicon);
        } else {
            $all_events = DB::table('events')
                    ->where('user_evdelete', '=', 'n')
                    ->where('private_event', '!=', 'y')
                    ->select('id', 'event_name', 'account_id', 'account_type', 'event_url', 'event_image', 'event_venue', 'event_address', 'address_secd', 'city', 'state', 'country', 'event_dtype', 'event_date', 'event_time', 'event_cost', 'event_price')
                    ->orderBy('id', 'desc')
                    ->paginate(8);

            return \View::make('public/default2/ajex/home-event')
                            ->with('al_event', $all_events);
        }
    }

    public function getCeventdata($address) {
        if (!empty($address)) {
            $cyt = explode(",_", $address);

            /*Hide past event*/
            $mytime = Carbon::now();
            $today  = $mytime->toDateTimeString();                                

            if (isset($cyt[1]) && !empty($cyt[1])) {
                $cunt_id = DB::table('countries')->where('sortname', '=', $cyt[1])->select('id')->get();
            } else {
                $cunt_id = DB::table('countries')->where('sortname', '=', $address)->select('id')->get();
            }
            if (Sentry::check()) {
                if (!empty($cunt_id[0]->id)) {
                    if (isset($cyt[1]) && !empty($cyt[1])) {
                        $all_events = DB::table('events')->where('user_evdelete', '=', 'n')->where('state', '=', $cyt[0])->where('country', '=', $cunt_id[0]->id)->where('private_event', '!=', 'y')->where('events.event_date', ">", $today)->select('id', 'event_name', 'account_id', 'account_type', 'event_url', 'event_image', 'event_dtype', 'event_venue', 'event_address', 'country', 'event_date', 'event_cost', 'event_price')->orderBy('id', 'desc')
                                ->paginate(2);
                        if (empty($all_events[0]->id)) {
                            $all_events = DB::table('events')->where('events.event_date', ">", $today)->where('user_evdelete', '=', 'n')->where('private_event', '!=', 'y')->select('id', 'event_name', 'account_id', 'account_type', 'event_url', 'event_image', 'event_venue', 'event_address', 'country', 'event_date', 'event_cost', 'event_dtype', 'event_price')->orderBy('id', 'desc')->paginate(2);
                        }
                    } else {
                        $all_events = DB::table('events')->where('events.event_date', ">", $today)->where('user_evdelete', '=', 'n')->where('country', '=', $cunt_id[0]->id)->where('private_event', '!=', 'y')->select('id', 'event_name', 'account_id', 'account_type', 'event_url', 'event_image', 'event_venue', 'event_address', 'country', 'event_date', 'event_dtype', 'event_cost', 'event_price')->orderBy('id', 'desc')
                                ->paginate(2);
                        if (empty($all_events[0]->id)) {
                            $all_events = DB::table('events')->where('events.event_date', ">", $today)->where('user_evdelete', '=', 'n')->where('private_event', '!=', 'y')->select('id', 'event_name', 'account_id', 'account_type', 'event_url', 'event_image', 'event_venue', 'event_address', 'country', 'event_date', 'event_dtype', 'event_cost', 'event_price')->orderBy('id', 'desc')->paginate(2);
                        }
                    }
                } else {
                    $all_events = DB::table('events')->where('events.event_date', ">", $today)->where('user_evdelete', '=', 'n')->where('private_event', '!=', 'y')->select('id', 'event_name', 'account_id', 'account_type', 'event_url', 'event_image', 'event_venue', 'event_address', 'country', 'event_dtype', 'event_date', 'event_cost', 'event_price')->orderBy('id', 'desc')->paginate(2);
                }
                $go_homent = 'public/default2/ajex/lfeature-event';
            } else {
                if (!empty($cunt_id[0]->id)) {
                    if (isset($cyt[1]) && !empty($cyt[1])) {
                        $all_events = DB::table('events')->where('events.event_date', ">", $today)->where('user_evdelete', '=', 'n')->where('state', '=', $cyt[0])->where('country', '=', $cunt_id[0]->id)->where('private_event', '!=', 'y')
                                        ->select('id', 'event_name', 'account_id', 'account_type', 'event_url', 'event_image', 'event_dtype', 'event_venue', 'event_address', 'event_date', 'event_cost', 'event_price')
                                        ->orderBy('id', 'desc')->paginate(8);
                        if (empty($all_events[0]->id)) {
                            $all_events = DB::table('events')->where('user_evdelete', '=', 'n')->where('events.event_date', ">", $today)->where('private_event', '!=', 'y')->select('id', 'event_name', 'account_id', 'account_type', 'event_url', 'event_image', 'event_venue', 'event_address', 'event_date', 'event_dtype', 'event_cost', 'event_price')->orderBy('id', 'desc')->paginate(8);
                        }
                    } else {
                        $all_events = DB::table('events')->where('events.event_date', ">", $today)->where('user_evdelete', '=', 'n')->where('country', '=', $cunt_id[0]->id)->where('private_event', '!=', 'y')->select('id', 'event_name', 'account_id', 'account_type', 'event_url', 'event_image', 'event_venue', 'event_address', 'event_date', 'event_dtype', 'event_cost', 'event_price')->orderBy('id', 'desc')->paginate(8);
                        if (empty($all_events[0]->id)) {
                            $all_events = DB::table('events')->where('events.event_date', ">", $today)->where('user_evdelete', '=', 'n')->where('private_event', '!=', 'y')->select('id', 'event_name', 'account_id', 'account_type', 'event_url', 'event_image', 'event_venue', 'event_address', 'event_date', 'event_dtype', 'event_cost', 'event_price')->orderBy('id', 'desc')->paginate(8);
                        }
                    }
                } else {
                    $all_events = DB::table('events')->where('events.event_date', ">", $today)->where('user_evdelete', '=', 'n')->where('private_event', '!=', 'y')->select('id', 'event_name', 'account_id', 'account_type', 'event_url', 'event_image', 'event_venue', 'event_address', 'event_date', 'event_dtype', 'event_cost', 'event_price')->orderBy('id', 'desc')->paginate(8);
                }
                if (!isset($all_events[0]->id)) {
                    $all_events = DB::table('events')->where('events.event_date', ">", $today)->where('user_evdelete', '=', 'n')->where('private_event', '!=', 'y')->select('id', 'event_name', 'account_id', 'account_type', 'event_url', 'event_image', 'event_venue', 'event_address', 'event_date', 'event_dtype', 'event_cost', 'event_price')->orderBy('id', 'desc')->paginate(8);
                }
                $go_homent = 'public/default2/ajex/home-event';
            }
            return \View::make($go_homent)->with('al_event', $all_events)->with('geo_loc', 'geo');
        }
    }

    public function getCstatedata($country, $state) {
        if (!empty($country)) {
            /*Hide past event*/
            $mytime = Carbon::now();
            $today  = $mytime->toDateTimeString();
            
            if ($country == 'y' && $state == 'n') {
                $all_events = DB::table('events')->where('events.event_date', ">", $today)->where('user_evdelete', '=', 'n')->where('private_event', '!=', 'y')->select('id', 'event_name', 'account_id', 'account_type', 'event_url', 'event_image', 'event_venue', 'event_address', 'event_date', 'event_cost', 'event_price')->orderBy('id', 'desc')->paginate(8);
            } else {
                if (!empty($state)) {
                    $state_name = DB::table('states')->where('id', '=', $state)->where('country_id', '=', $country)->select('id', 'name')->get();
                    if (!empty($state_name[0]->id)) {
                        $all_events = DB::table('events')->where('events.event_date', ">", $today)->where('user_evdelete', '=', 'n')->where('state', '=', $state_name[0]->name)->where('country', '=', $country)->where('private_event', '!=', 'y')
                                        ->select('id', 'event_name', 'account_id', 'account_type', 'event_url', 'event_image', 'event_venue', 'event_address', 'event_date', 'event_cost', 'event_price')->orderBy('id', 'desc')->paginate(8);
                    } else {
                        $all_events = DB::table('events')->where('events.event_date', ">", $today)->where('user_evdelete', '=', 'n')->where('country', '=', $country)->where('private_event', '!=', 'y')->select('id', 'event_name', 'account_id', 'account_type', 'event_url', 'event_image', 'event_venue', 'event_address', 'event_date', 'event_cost', 'event_price')->orderBy('id', 'desc')
                                ->paginate(8);
                    }
                } else {
                    $all_events = DB::table('events')->where('events.event_date', ">", $today)->where('user_evdelete', '=', 'n')->where('country', '=', $country)->where('private_event', '!=', 'y')->select('id', 'event_name', 'account_id', 'account_type', 'event_url', 'event_image', 'event_venue', 'event_address', 'event_date', 'event_cost', 'event_price')->orderBy('id', 'desc')->paginate(8);
                }
                if (!isset($all_events[0]->id)) {
                    $all_events = DB::table('events')->where('events.event_date', ">", $today)->where('user_evdelete', '=', 'n')->where('private_event', '!=', 'y')->select('id', 'event_name', 'account_id', 'account_type', 'event_url', 'event_image', 'event_venue', 'event_address', 'event_date', 'event_cost', 'event_price')->orderBy('id', 'desc')->paginate(8);
                }
            }
            return \View::make('public/default2/ajex/home-event')->with('al_event', $all_events)->with('geo_loc', 'geo');
        }
    }

    public function updateEvent($eid) {

        if (Sentry::check() && !empty($eid)) {
            $eventModel = new Event;

            $clid = Sentry::getUser()->id;
            $md_arr = $eventModel->md_arr;
            $tmf = $eventModel->tmf;
            $sdwkfmt = $eventModel->sdwkfmt;
            $ofday = $eventModel->ofday;
            $st_cvs = $eventModel->st_cvs;

            $countries = $eventModel->getEventCountries();

          
            
            $data["eid"] = $eid;
            $edit_event = $eventModel->getUserPersonalEvents($data);
            
            
            return \View::make('public/default2/account/update-event')
                    ->with('title', 'Update Event - DiscoverYourEvent')
                    ->with('evid', $eid)
                    ->with('st_cvs', $st_cvs)
                    ->with('month_darray', $md_arr)
                    ->with('tmformat', $tmf)
                    ->with('sdwkfmt', $sdwkfmt)
                    ->with('ofheday', $ofday)
                    ->with('edit_event', $edit_event)
                    ->with('active', 'not')
                    ->with('cuntd', $countries);
        } else {
            return Redirect::to('/');
        }
    }

    public function getDeletevent($eid) {
        if (Sentry::check() && !empty($eid)) {
            $clid = Sentry::getUser()->id;
            $check_data = DB::table('events')->where('u_id', '=', $clid)->where('id', '=', $eid)->select('id', 'account_type', 'event_image')->get();
            if (!empty($check_data[0]->id)) {
                if (!empty($check_data[0]->event_image)) {
                    $destinationPath = 'public/uploads/events/' . $check_data[0]->account_type . '/';
                    File::delete($destinationPath . $check_data[0]->event_image);
                }

                DB::table('events')->where('id', '=', $eid)->update(['user_evdelete' => 'y', 'updated_at' => date('Y-m-d H:i:s')]);
                /* DB::delete('delete from events WHERE id = '.$eid);
                  DB::delete('delete from users_events WHERE e_id = '.$eid);
                  DB::delete('delete from event_multidate WHERE ev_id = '.$eid); */
                DB::delete('delete from notifications WHERE type = "event" && object_id = ' . $eid);
                return Redirect::to('account/manageEvent')->with('succ_mesg', 'Event deleted successfully');
            }
        } else {
            return Redirect::to('/');
        }
    }

    public function getAccdeletevent($bcmurl, $eid) {
        if (Sentry::check() && !empty($eid)) {
            $clid = Sentry::getUser()->id;
            $check_data = DB::table('events')->where('user_evdelete', '=', 'n')->where('u_id', '=', $clid)->where('id', '=', $eid)->select('id', 'account_type', 'event_image')->get();
            if (!empty($check_data[0]->id)) {
                if (!empty($check_data[0]->event_image)) {
                    $destinationPath = 'public/uploads/events/' . $check_data[0]->account_type . '/';
                    File::delete($destinationPath . $check_data[0]->event_image);
                }
                DB::table('events')->where('id', '=', $eid)->update(['user_evdelete' => 'y', 'updated_at' => date('Y-m-d H:i:s')]);
                /* DB::delete('delete from events WHERE id = '.$eid);      
                  DB::delete('delete from users_events WHERE e_id = '.$eid);
                  DB::delete('delete from event_multidate WHERE ev_id = '.$eid); */
                DB::delete('delete from notifications WHERE type = "event" && object_id = ' . $eid);
                return Redirect::to($bcmurl . '/account/manageEvent')->with('succ_mesg', 'Event deleted successfully');
            }
        } else {
            return Redirect::to('/');
        }
    }

    public function postUpdatevent($eid, Request $request) {
        $data = $request->input();
        $clid = Sentry::getUser()->id;
        $check_data = DB::table('events')->where('events.user_evdelete', '=', 'n')->where('events.u_id', '=', $clid)->where('events.id', '=', $eid)->select('id', 'event_image')->get();
        if (!empty($check_data[0]->id)) {
            $validator = Validator::make($request->all(), [
                        'event_type' => 'required',
                        'event_catid' => 'required',
                        'email_address' => 'min:4|email',
                        'event_venue' => 'required',
                        'phone_no' => 'min:10',
                        'event_name' => 'required',
                        // 'sevent_date' => 'required|date',
                        // 'sevent_time' => 'required',
                        'state' => 'required',
                        'country' => 'required',
                        'city' => 'required',
                        'event_cost' => 'required',
                        'event_description' => 'required',
                        'private_event' => 'required',
            ]);
            if ($validator->fails()) {
                return Redirect::back()->withErrors($validator)->withInput();
            } else {
                $rid = Str::random(6);
                if ($data['event_cost'] == 'paid') {
                    if ($data['event_price'] != '') {
                        
                        $evp_regs = '/^[0-9]+$/';
                        $evp_regss = '/^[0-9]+[.][0-9]+$/';
                        
                        if (preg_match($evp_regs, $data['event_price']) || preg_match($evp_regss, $data['event_price'])) {
                            $event_price = $data['event_price'];
                            $all_ep = $event_price;
                        } else {
                            return Redirect::back()->with('req_evprice', 'Cost price value not valid, please used 0-9 or 10.99 format')->withInput();
                        }
                        
                        $ticket_surl = $data['ticket_surl'];
                        if (!empty($ticket_surl) && filter_var($ticket_surl , FILTER_VALIDATE_URL) === false) {
                                return Redirect::back()->with('inv_tkurl', $ticket_surl . ' is not a valid URL, http:// or https:// is required')->withInput();
                        } else {
                            $ticket_surl = null;
                            /* return Redirect::back()->with('inv_tkurl', 'Ticket Sales URL is required field')->withInput(); 
                              die; */
                        }
                    } else {
                        return Redirect::back()->with('req_evprice', 'Cost price is required field')->withInput();
                        die;
                    }
                    if (!empty($data['mevent_price'])) {
                        $mevp_regs = '/^[0-9]+$/';
                        $mevp_reg = '/^[0-9]+[.][0-9]+$/';
                        if (preg_match($mevp_regs, $data['mevent_price']) || preg_match($mevp_reg, $data['mevent_price'])) {
                            $mevent_price = $data['mevent_price'];
                            $all_ep = $event_price . '-' . $mevent_price;
                        } else {
                            return Redirect::back()->with('req_evprice', 'Cost price value not valid, please used 0-9 or 10.99 format')->withInput();
                            die;
                        }
                    }
                } else {
                    $all_ep = null;
                    $ticket_surl = null;
                }
                if (isset($data['kid_event'])) {
                    $kid_event = 'y';
                } else {
                    $kid_event = 'n';
                }
                if (isset($data['family_event'])) {
                    $family_event = 'y';
                } else {
                    $family_event = 'n';
                }
                if (isset($data['religious_event'])) {
                    $religious_event = 'y';
                } else {
                    $religious_event = 'n';
                }

                /*                 * *****event-date-section******** */
                if ($data['event_dtype'] == 'single') {
                    $ev_date = date('Y-m-d', strtotime($data['sevent_date']));
                    if (empty($data['sevent_date'])) {
                        return Redirect::back()->with('ev_date', 'Event Start date field is required')->withInput();
                        die;
                    }
                    if (!empty($data['event_date'])) {
                        $end_date = date('Y-m-d', strtotime($data['event_date']));
                    } else {
                        $end_date = null;
                    }
                } else {
                    if (empty($data['arrdate'])) {
                        return Redirect::back()->with('ev_section', 'Event date Section is required')->withInput();
                        die;
                    }
                    $ev_date = date('Y-m-d', strtotime($data['arrdate'][0]));
                    $end_date = null;
                }

                if (isset($data['map_show'])) {
                    $showmap = 'y';
                } else {
                    $showmap = 'n';
                }
                if (isset($data['our_pid']) && !empty($data['our_pid'])) {
                    $evid_img = $data['our_pid'];
                } else {
                    if (!empty($check_data[0]->event_image)) {
                        $evid_img = $check_data[0]->event_image;
                    } else {
                        $evid_img = null;
                    }
                }
                if ($data['private_event'] == 'y') {
                    if (!isset($data['sharepinvt_event']) && empty($data['sharepinvt_event'])) {
                        return Redirect::back()->with('share_event', 'Please choose one option, its required')->withInput();
                        die();
                    }
                    $share_event = $data['sharepinvt_event'];
                    if ($share_event == 'n') {
                        if (isset($data['passprv_event'])) {
                            $password_estatus = $data['passprv_event'];
                        } else {
                            $password_estatus = 'n';
                        }

                        if (isset($password_estatus) && $password_estatus == 'y') {
                            $pass_event = $data['pr-passevnt'];
                            if (empty($pass_event)) {
                                return Redirect::back()->with('pev_req', 'Private event password field is required')->withInput();
                                die;
                            }
                        } else {
                            $pass_event = null;
                        }
                    } else {
                        $pass_event = null;
                        $password_estatus = 'n';
                    }
                } else {
                    $share_event = 'n';
                    $password_estatus = 'n';
                    $pass_event = null;
                }
                //$check_eurl = DB::table('events')->where('id', '!=', $eid)->where('event_url', '=', $data['event_url'])->select('id')->get();			
                //if(empty($check_eurl[0]->id)){		 
                $input_file = $request->file();
                if (!empty($input_file['event_image'][0])) {
                    $event_filesize = $input_file['event_image'][0]->getClientSize();
                    if ($event_filesize < 2000001) {
                        //Upload Image		 
                        $destinationPath = 'public/uploads/events/personal/';
                        $filename = $input_file['event_image'][0]->getClientOriginalName();
                        $mime_type = $input_file['event_image'][0]->getMimeType();
                        if ($mime_type == 'image/png' || $mime_type == 'image/gif' || $mime_type == 'image/jpeg' || $mime_type == 'image/jpg' || $mime_type == 'image/bmp') {
                            $extension = $input_file['event_image'][0]->getClientOriginalExtension();
                            $filename = basename($input_file['event_image'][0]->getClientOriginalName(), "." . $extension) . '_' . $rid . '.' . $extension;
                            $upload_success = $input_file['event_image'][0]->move($destinationPath, $filename);
                            /*                             * *************resize-image*************** */
                            /* $rsfname = basename($input_file['event_image']->getClientOriginalName(), ".".$extension).'_'.$rid.'_200x200.'.$extension;
                              $rspath = public_path('uploads/account_type/'.$imgs_apath.'/'.$rsfname);
                              Image::make($destinationPath.$filename)->resize(200, 200)->save($rspath); */
                            /*                             * *************************************** */
                            if (!empty($check_data[0]->event_image)) {
                                File::delete($destinationPath . $check_data[0]->event_image);
                            }
                            DB::table('events')->where('events.u_id', '=', $clid)->where('events.id', '=', $eid)->update(['event_type' => $data['event_type'], 'event_catid' => $data['event_catid'],
                                'contact_person' => $data['contact_person'], 'phone_no' => $data['phone_no'], 'email_address' => $data['email_address'], 'website' => $data['website'],
                                'event_name' => $data['event_name'], 'event_image' => $filename, 'event_venue' => $data['event_venue'], 'event_address' => $data['event_address'],
                                'address_secd' => $data['address_secd'], 'city' => $data['city'], 'state' => $data['state'], 'country' => $data['country'], 'zip_code' => $data['zip_code'],
                                'map_show' => $showmap, 'event_dtype' => $data['event_dtype'], 'event_date' => $ev_date, 'event_time' => $data['sevent_time'], 'end_date' => $end_date, 'end_time' => $data['eevent_time'],
                                'event_cost' => $data['event_cost'], 'event_price' => $all_ep, 'ticket_surl' => $ticket_surl, 'event_description' => $data['event_description'],
                                'fb_link' => $data['fb_link'], 'tw_link' => $data['tw_link'], 'kid_event' => $kid_event, 'family_event' => $family_event, 'religious_event' => $religious_event,
                                'private_event' => $data['private_event'], 'share_event' => $share_event, 'password_estatus' => $password_estatus, 'pass_event' => $pass_event,
                                'updated_at' => date('Y-m-d H:i:s')]);
                        } else {
                            return Redirect::back()->with('failed_upfile', 'Image tupe not supported, please upload (png/gif/jpeg/jpg/bmp) format')->withInput();
                        }
                    } else {
                        return Redirect::back()->with('failed_upfile', 'We recommend using at least a 2160x1080px (2:1 ratio) image that no larger than 10MB')->withInput();
                        die;
                    }
                } else {
                    DB::table('events')->where('events.u_id', '=', $clid)->where('events.id', '=', $eid)->update(['event_type' => $data['event_type'],
                        'event_catid' => $data['event_catid'], 'contact_person' => $data['contact_person'], 'phone_no' => $data['phone_no'],
                        'email_address' => $data['email_address'], 'website' => $data['website'], 'event_name' => $data['event_name'], 'event_image' => $evid_img,
                        'event_venue' => $data['event_venue'], 'event_address' => $data['event_address'], 'address_secd' => $data['address_secd'],
                        'city' => $data['city'], 'state' => $data['state'], 'country' => $data['country'], 'zip_code' => $data['zip_code'], 'map_show' => $showmap,
                        'event_dtype' => $data['event_dtype'], 'event_date' => $ev_date, 'event_time' => $data['sevent_time'], 'end_date' => $end_date, 'end_time' => $data['eevent_time'],
                        'event_cost' => $data['event_cost'], 'event_price' => $all_ep, 'ticket_surl' => $ticket_surl, 'event_description' => $data['event_description'],
                        'fb_link' => $data['fb_link'], 'tw_link' => $data['tw_link'], 'kid_event' => $kid_event, 'family_event' => $family_event,
                        'religious_event' => $religious_event, 'private_event' => $data['private_event'], 'share_event' => $share_event, 'password_estatus' => $password_estatus,
                        'pass_event' => $pass_event, 'updated_at' => date('Y-m-d H:i:s')]);
                }
                /*                 * ****event-date-multi-section******** */
                if ($data['event_dtype'] == 'multi') {
                    $xlx = 0;
                    $emdcount = DB::table('event_multidate')->where('ev_id', '=', $eid)->select('id')->get();
                    if (sizeof($emdcount) > 0) {
                        $gcmdateval = sizeof($emdcount);
                        if ($gcmdateval > sizeof($data['arrdate'])) {
                            $getmdval = $gcmdateval - 1;
                            DB::delete('delete from event_multidate WHERE id = ' . $emdcount[$getmdval]->id);
                        }
                    }
                    foreach ($data['arrdate'] as $stdt) {
                        if (!empty($data['mw_date'][$xlx]) && $data['mw_date'][$xlx] != 'Month Date') {
                            $mw_date = $data['mw_date'][$xlx];
                        } else {
                            $mw_date = null;
                        }
                        $startdt = date('Y-m-d', strtotime($stdt));
                        $itcendate = date('Y-m-d', strtotime($data['enddate'][$xlx]));
                        if (isset($data['multieid'][$xlx]) && !empty($data['multieid'][$xlx])) {
                            DB::table('event_multidate')->where('id', '=', $data['multieid'][$xlx])->where('ev_id', '=', $eid)
                                    ->update(['event_datetype' => $data['nspd'][$xlx], 'start_date' => $startdt, 'enddate' => $itcendate, 'ofthe' => $data['ofthe'][$xlx],
                                        'start_time' => $data['startime'][$xlx], 'st_format' => $data['st_format'][$xlx], 'endtime' => $data['endtime'][$xlx],
                                        'et_format' => $data['et_format'][$xlx], 'total_day' => $data['total_day'][$xlx], 'multype_wm' => $data['wgtdaysvl'][$xlx],
                                        'mw_date' => $mw_date, 'updated_at' => date('Y-m-d H:i:s')]);
                        } else {
                            DB::table('event_multidate')->insert(['ev_id' => $eid, 'event_datetype' => $data['nspd'][$xlx], 'start_date' => $startdt,
                                'enddate' => $itcendate, 'ofthe' => $data['ofthe'][$xlx], 'start_time' => $data['startime'][$xlx],
                                'st_format' => $data['st_format'][$xlx], 'endtime' => $data['endtime'][$xlx], 'et_format' => $data['et_format'][$xlx],
                                'total_day' => $data['total_day'][$xlx], 'multype_wm' => $data['wgtdaysvl'][$xlx], 'created_at' => date('Y-m-d H:i:s'),
                                'mw_date' => $mw_date, 'updated_at' => date('Y-m-d H:i:s')]);
                        }
                        $xlx++;
                    }
                } else {
                    DB::delete('delete from event_multidate WHERE ev_id = ' . $eid);
                }
                return Redirect::to('account/manageEvent')->with('succ_mesg', 'Event updated successfully');
                /* } else {
                  return redirect::back()->with('invl_url', 'The event URL has already been taken.')->withInput();
                  die;
                  } */
            }
        }
    }

    public function getEvents($eventurl) {
        if (!empty($eventurl)) {

            $Model = new Event;
            $event_data = $Model->getEventByUrl($eventurl);

            if (!empty($event_data[0]->eid)) {
                $ev_id = DB::table('events')
                                ->where('user_evdelete', '=', 'n')
                                ->where('event_url', '=', $eventurl)
                                ->select('id')->get();

                $count_eattpeopl = DB::table('users_events')
                        ->where('e_id', '=', $ev_id[0]->id)
                        ->count();

                if (Sentry::check()) {
                    $clid = Sentry::getUser()->id;
                    $ur_event = DB::table('users_events')
                                    ->where('e_id', '=', $ev_id[0]->id)
                                    ->where('u_id', '=', $clid)
                                    ->select('e_id')->get();
                } else {
                    $ur_event = null;
                }
                /*                 * **********map-data********** */
                $lfalc_add = null;
                if (!empty($event_data[0]->event_venue)) {
                    $lfalc_add .= $event_data[0]->event_venue;
                }
                if (!empty($event_data[0]->event_address)) {
                    if (!empty($lfalc_add)) {
                        $lfalc_add .= ',' . $event_data[0]->event_address;
                    } else {
                        $lfalc_add = $event_data[0]->event_address;
                    }
                }
                if (!empty($event_data[0]->address_secd)) {
                    if (!empty($lfalc_add)) {
                        $lfalc_add .= ',' . $event_data[0]->address_secd;
                    } else {
                        $lfalc_add = $event_data[0]->address_secd;
                    }
                }
                if (!empty($event_data[0]->city)) {
                    if (!empty($lfalc_add)) {
                        $lfalc_add .= ',' . $event_data[0]->city;
                    } else {
                        $lfalc_add = $event_data[0]->city;
                    }
                }

                if (!empty($event_data[0]->state) && !empty($event_data[0]->country)) {
                    $getssdata = DB::table('states')->where('country_id', '=', $event_data[0]->country)->where('name', '=', $event_data[0]->state)->select('id', 'name')->get();
                    if (!empty($getssdata[0]->id)) {
                        $staten = $getssdata[0]->name;
                    } else {
                        $staten = $event_data[0]->state;
                    }
                    if (!empty($staten)) {
                        if (!empty($lfalc_add)) {
                            $lfalc_add .= ',' . $staten;
                        } else {
                            $lfalc_add = $staten;
                        }
                    }
                }
                if (!empty($event_data[0]->country)) {
                    $getccdata = DB::table('countries')->where('id', '=', $event_data[0]->country)->select('id', 'name')->get();
                    if (!empty($lfalc_add)) {
                        $lfalc_add .= ',' . $getccdata[0]->name;
                    } else {
                        $lfalc_add = $getccdata[0]->name;
                    }
                }
                $maddressArray[] = $lfalc_add;
                /*                 * ******************end-mmap************* */
                if (Sentry::check()) {
                    $clid = Sentry::getUser()->id;
                } else {
                    $clid = null;
                }
                $bcity = null;
                $bstates = null;
                $bcountry = null;
                if (!empty($event_data[0]->account_id)) {
                    $accdata = DB::table('account_details')->where('id', '=', $event_data[0]->account_id)->get();
                    $acd_gid = $accdata[0]->g_id;
                    if ($acd_gid == 2) {
                        $cimg_apath = 'business';
                    } elseif ($acd_gid == 5) {
                        $cimg_apath = 'municipality';
                    } else {
                        $cimg_apath = 'club';
                    }

                    if (!empty($accdata[0]->city)) { // && !empty($accdata[0]->state)
                        //$getcidata = DB::table('cities')->where('id', '=', $accdata[0]->city)->where('state_id', '=', $accdata[0]->state)->select('id','name')->get();
                        $bcity = $accdata[0]->city;
                    }
                    if (!empty($accdata[0]->state) && !empty($accdata[0]->country)) {
                        $getssdata = DB::table('states')->where('country_id', '=', $accdata[0]->country)->where('name', '=', $accdata[0]->state)->select('id', 'name')->get();
                        if (!empty($getssdata[0]->id)) {
                            $bstates = $getssdata[0]->name;
                        }
                    }
                    if (!empty($accdata[0]->country)) {
                        $getccdata = DB::table('countries')->where('id', '=', $accdata[0]->country)->select('id', 'name')->get();
                        $bcountry = $getccdata[0]->name;
                    }
                } else {
                    $accdata = null;
                    $cimg_apath = null;
                }
                return \View::make('public/default2/events/index')->with('title', $event_data[0]->event_name . ' Event - DiscoverYourEvent')
                                ->with('clid', $clid)->with('maddArr', $maddressArray)->with('event_data', $event_data)->with('accdata', $accdata)
                                ->with('attending_people', $count_eattpeopl)->with('your_event', $ur_event)->with('cimg_apath', $cimg_apath)->with('bcity', $bcity)
                                ->with('bstates', $bstates)->with('bcountry', $bcountry);
            } else {
                return \View::make('public/default2/404')->with('title', '404 - DiscoverYourEvent');
            }
        } else {
            return Redirect::to('/');
        }
    }

    public function getEventpopup($eventurl) {
        if (!empty($eventurl)) {
            $event_data = DB::table('events')->where('events.user_evdelete', '=', 'n')->where('events.event_url', '=', $eventurl)->join('event_data', 'events.event_catid', '=', 'event_data.id')->get();
            if (!empty($event_data[0]->id)) {
                $ev_id = DB::table('events')->where('events.user_evdelete', '=', 'n')->where('event_url', '=', $eventurl)->select('id')->get();
                $count_eattpeopl = DB::table('users_events')->where('e_id', '=', $ev_id[0]->id)->count();

                if (Sentry::check()) {
                    $clid = Sentry::getUser()->id;
                    $ur_event = DB::table('users_events')->where('e_id', '=', $ev_id[0]->id)->where('u_id', '=', $clid)->select('e_id')->get();
                } else {
                    $ur_event = null;
                }
                return \View::make('public/default2/ajex/event-ajex')->with('event_data', $event_data)->with('attending_people', $count_eattpeopl)->with('your_event', $ur_event);
            } else {
                return \View::make('public/default2/404')->with('title', '404 - DiscoverYourEvent');
            }
        } else {
            return Redirect::to('/');
        }
    }

    public function postEventpopup(Request $request) {
        $data = $request->input();
        if (!empty($data['event'])) {
            $eventurl = $data['event'];
            $event_data = DB::table('events')->where('events.user_evdelete', '=', 'n')->where('events.event_url', '=', $eventurl)->where('events.private_event', '!=', 'y')->join('event_data', 'events.event_catid', '=', 'event_data.id')
                            ->select('events.id as eid', 'events.account_type', 'events.account_id', 'events.event_catid', 'events.contact_person', 'events.phone_no', 'events.email_address', 'events.website', 'events.event_name', 'events.event_url', 'events.event_image', 'events.event_venue', 'events.event_address', 'events.address_secd', 'events.city', 'events.state', 'events.country', 'events.zip_code', 'events.map_show', 'events.event_dtype', 'events.event_date', 'events.event_time', 'events.end_date', 'events.end_time', 'events.event_cost', 'events.event_price', 'events.ticket_surl', 'events.event_description', 'events.fb_link', 'events.tw_link', 'events.private_event', 'event_data.event_category', 'event_data.id as ed_id')->get();
            if (!empty($event_data[0]->eid)) {
                $ev_id = DB::table('events')->where('user_evdelete', '=', 'n')->where('event_url', '=', $eventurl)->select('id')->get();
                $count_eattpeopl = DB::table('users_events')->where('e_id', '=', $ev_id[0]->id)->count();

                if (Sentry::check()) {
                    $clid = Sentry::getUser()->id;
                    $ur_event = DB::table('users_events')->where('e_id', '=', $ev_id[0]->id)->where('u_id', '=', $clid)->select('e_id')->get();
                } else {

                    $clid = null;
                    $ur_event = null;
                }
                /*                 * **********map-data********** */
                $lfalc_add = null;
                if (!empty($event_data[0]->event_venue)) {
                    $lfalc_add .= $event_data[0]->event_venue;
                }
                if (!empty($event_data[0]->event_address)) {
                    if (!empty($lfalc_add)) {
                        $lfalc_add .= ',' . $event_data[0]->event_address;
                    } else {
                        $lfalc_add = $event_data[0]->event_address;
                    }
                }
                if (!empty($event_data[0]->address_secd)) {
                    if (!empty($lfalc_add)) {
                        $lfalc_add .= ',' . $event_data[0]->address_secd;
                    } else {
                        $lfalc_add = $event_data[0]->address_secd;
                    }
                }
                if (!empty($event_data[0]->city)) {
                    if (!empty($lfalc_add)) {
                        $lfalc_add .= ',' . $event_data[0]->city;
                    } else {
                        $lfalc_add = $event_data[0]->city;
                    }
                }

                if (!empty($event_data[0]->state) && !empty($event_data[0]->country)) {
                    $getssdata = DB::table('states')->where('country_id', '=', $event_data[0]->country)->where('name', '=', $event_data[0]->state)->select('id', 'name')->get();
                    if (!empty($getssdata[0]->id)) {
                        $staten = $getssdata[0]->name;
                    } else {
                        $staten = $event_data[0]->state;
                    }
                    if (!empty($staten)) {
                        if (!empty($lfalc_add)) {
                            $lfalc_add .= ',' . $staten;
                        } else {
                            $lfalc_add = $staten;
                        }
                    }
                }
                if (!empty($event_data[0]->country)) {
                    $getccdata = DB::table('countries')->where('id', '=', $event_data[0]->country)->select('id', 'name')->get();
                    if (!empty($lfalc_add)) {
                        $lfalc_add .= ',' . $getccdata[0]->name;
                    } else {
                        $lfalc_add = $getccdata[0]->name;
                    }
                }
                $maddressArray[] = $lfalc_add;
                /*                 * ******************end-mmap************* */
                return \View::make('public/default2/ajex/pevent-ajex')->with('event_data', $event_data)->with('maddArr', $maddressArray)->with('attending_people', $count_eattpeopl)->with('your_event', $ur_event)->with('clid', $clid);
            } else {
                return \View::make('public/default2/404')->with('title', '404 - DiscoverYourEvent');
            }
        } else {
            return Redirect::to('/');
        }
    }

    public function accEventpopup($eventurl) {
        if (!empty($eventurl)) {

            $event_data = DB::table('events')->where('events.user_evdelete', '=', 'n')->where('events.event_url', '=', $eventurl)->join('event_data', 'events.event_catid', '=', 'event_data.id')->get();
            if (!empty($event_data[0]->id)) {
                $ev_id = DB::table('events')->where('user_evdelete', '=', 'n')->where('event_url', '=', $eventurl)->select('id')->get();
                $count_eattpeopl = DB::table('users_events')->where('e_id', '=', $ev_id[0]->id)->count();

                if (Sentry::check()) {
                    $clid = Sentry::getUser()->id;
                    $ur_event = DB::table('users_events')->where('e_id', '=', $ev_id[0]->id)->where('u_id', '=', $clid)->select('e_id')->get();
                } else {
                    $ur_event = null;
                }

                return \View::make('public/default2/ajex/account_event')->with('event_data', $event_data)->with('attending_people', $count_eattpeopl)->with('your_event', $ur_event);
            } else {
                return \View::make('public/default2/404')->with('title', '404 - DiscoverYourEvent');
            }
        } else {
            return Redirect::to('/');
        }
    }

    /*     * *
     * loggeed in user cerate event
     */

    public function createAccevent($aname) {

        $UserAccounts = new UserAccounts;
        $eventModel = new Event;
        $acd_gid = $accountdetail = $accdetails = $account_status = $auser_role = false;

        if (Sentry::check() && !empty($aname)) {
            $id = Sentry::getUser()->id;
            $UserAccounts->id = $id;
            $data["account_url"] = $aname;
            $accountdetail = $UserAccounts->getAccountDetails($data);


            $md_arr = $eventModel->md_arr;
            $tmf = $eventModel->tmf;
            $sdwkfmt = $eventModel->sdwkfmt;
            $ofday = $eventModel->ofday;
            $st_cvs = $eventModel->st_cvs;
            
            $account_status = $accountdetail->status;
            $auser_role = $accountdetail->account_urole;
            $acd_gid = $accountdetail->g_id;
            $ac_murl = $accountdetail->account_url;
            
            if (sizeof($accountdetail) > 0) {
                
               $accdetails = DB::table('account_details')->where('id', '=', $accountdetail->id)->select('g_id', 'name')->get(); 
                
                
                
                
                
                if ($acd_gid == 2) {
                    $cimg_apath = 'business';
                } elseif ($acd_gid == 5) {
                    $cimg_apath = 'municipality';
                } else {
                    $cimg_apath = 'club';
                }
                $cuntd = DB::table('countries')->select('id', 'name')->get();
                return \View::make('public/default2/account/acccreate-event')
                                ->with('title', 'Create Event - DiscoverYourEvent')
                                ->with('st_cvs', $st_cvs)
                                ->with('month_darray', $md_arr)
                                ->with('tmformat', $tmf)
                                ->with('sdwkfmt', $sdwkfmt)
                                ->with('ofheday', $ofday)
                                ->with('cuntd', $cuntd)
                                ->with('event_details', $accdetails)
                                ->with('acc_status', $account_status)
                                ->with('account_type', $cimg_apath)
                                ->with('auser_role', $auser_role)
                                ->with('active', 'ce');
            } else {
                return Redirect::to('/');
            }
        } else {
            return Redirect::to('/');
        }
    }

    public function postAccevent($aname, Request $request) {

        $data = $request->input();
        $clid = Sentry::getUser()->id;

        $accountdetail = DB::table('account_details')
                ->where('u_id', '=', $clid)
                ->Where('account_url', '=', $aname)
                ->select('id', 'g_id', 'name', 'account_url')
                ->get();

        if (!empty($accountdetail[0]->g_id)) {
            $validator = Validator::make($request->all(), [
                        'event_type' => 'required',
                        'event_catid' => 'required',
                        'event_venue' => 'required',
                        'event_name' => 'required',
                        /* 'sevent_date' => 'required|date',
                          'sevent_time' => 'required', */
                        'state' => 'required',
                        'country' => 'required',
                        'event_cost' => 'required',
                        'city' => 'required',
                        'event_description' => 'required',
                        'private_event' => 'required',
            ]);
            if ($validator->fails()) {
                return Redirect::back()->withErrors($validator)->withInput();
            } else {
                $acd_gid = $accountdetail[0]->g_id;
                if ($acd_gid == 2) {
                    $acc_type = 'business';
                } elseif ($acd_gid == 5) {
                    $acc_type = 'municipality';
                } else {
                    $acc_type = 'club';
                }
                $rid = Str::random(6);
                if ($data['event_cost'] == 'paid') {
                    if ($data['event_price'] != '') {
                        $evp_regs = '/^[0-9]+$/';
                        $evp_regss = '/^[0-9]+[.][0-9]+$/';
                        
                        if (preg_match($evp_regs, $data['event_price']) || preg_match($evp_regss, $data['event_price'])) {
                            $event_price = $data['event_price'];
                            $all_ep = $event_price;
                        }
                        else {
                            return Redirect::back()->with('req_evprice', 'Cost price value not valid, please used 0-9 or 10.99 format')->withInput();
                        }
                        $ticket_surl = $data['ticket_surl'];
                        if (!empty($data['ticket_surl']) && filter_var($ticket_surl, FILTER_VALIDATE_URL) === false) {
                                return Redirect::back()->with('inv_tkurl', $ticket_surl . ' is not a valid URL, http:// or https:// is required')->withInput();
                        } else {
                            $ticket_surl = null;
                        }
                    } else {
                        return Redirect::back()->with('req_evprice', 'Cost price is required field')->withInput();
                        die;
                    }
                    if (!empty($data['mevent_price'])) {
                        $evp_regs = '/^[0-9]+$/';
                        $evp_regss = '/^[0-9]+[.][0-9]+$/';
                        if (preg_match($evp_regs, $data['mevent_price']) || preg_match($evp_regss, $data['mevent_price'])) {
                            $mevent_price = $data['mevent_price'];
                            $all_ep = $event_price . '-' . $mevent_price;
                        } else {
                            return Redirect::back()->with('req_evprice', 'Cost price value not valid, please used 0-9 or 10.99 format')->withInput();
                            die;
                        }
                    }
                } else {
                    $all_ep = null;
                    $ticket_surl = null;
                }
                if (isset($data['available_purchase'])) {
                    $av_purchase = 'y';
                } else {
                    $av_purchase = 'n';
                }
                if (isset($data['kid_event'])) {
                    $kid_event = 'y';
                } else {
                    $kid_event = 'n';
                }
                if (isset($data['family_event'])) {
                    $family_event = 'y';
                } else {
                    $family_event = 'n';
                }
                if (isset($data['religious_event'])) {
                    $religious_event = 'y';
                } else {
                    $religious_event = 'n';
                }
                /*                 * ****event-date-section******** */
                if ($data['event_dtype'] == 'single') {
                    $ev_date = date('Y-m-d', strtotime($data['sevent_date']));
                    if (empty($data['sevent_date'])) {
                        return Redirect::back()->with('ev_date', 'Event Start date field is required')->withInput();
                        die;
                    }
                    if (!empty($data['event_date'])) {
                        $end_date = date('Y-m-d', strtotime($data['event_date']));
                    } else {
                        $end_date = null;
                    }
                } else {
                    if (empty($data['arrdate'])) {
                        return Redirect::back()->with('ev_section', 'Event date Section is required')->withInput();
                        die;
                    }
                    $ev_date = date('Y-m-d', strtotime($data['arrdate'][0]));
                    $end_date = null;
                }

                $input_file = $request->file();
                if (!empty($data['event_url'])) {
                    $event_url = $data['event_url'];
                } else {
                    $event_url = 'dye-acc' . $rid;
                }
                if (isset($data['map_show'])) {
                    $showmap = 'y';
                } else {
                    $showmap = 'n';
                }
                if (isset($data['our_pid']) && !empty($data['our_pid'])) {
                    $evid_img = $data['our_pid'];
                } else {
                    $evid_img = null;
                }
                if ($data['private_event'] == 'y') {
                    if (!isset($data['sharepinvt_event']) && empty($data['sharepinvt_event'])) {
                        return Redirect::back()->with('share_event', 'Please choose one option, its required')->withInput();
                        die();
                    }
                    $share_event = $data['sharepinvt_event'];
                    if ($share_event == 'n') {
                        if (isset($data['passprv_event'])) {
                            $password_estatus = $data['passprv_event'];
                        } else {
                            $password_estatus = 'n';
                        }
                        if (isset($password_estatus) && $password_estatus == 'y') {
                            $pass_event = $data['pr-passevnt'];
                            if (empty($pass_event)) {
                                return Redirect::back()->with('pev_req', 'Private event password field is required')->withInput();
                                die;
                            }
                        } else {
                            $pass_event = null;
                        }
                    } else {
                        $pass_event = null;
                        $password_estatus = 'n';
                    }
                } else {
                    $share_event = 'n';
                    $password_estatus = 'n';
                    $pass_event = null;
                }
                if (!empty($input_file['event_image'][0])) {
                    $event_filesize = $input_file['event_image'][0]->getClientSize();
                    if ($event_filesize < 2000001) {
                        //Upload Image				 
                        $destinationPath = 'public/uploads/events/' . $acc_type . '/';
                        $filename = $input_file['event_image'][0]->getClientOriginalName();
                        $mime_type = $input_file['event_image'][0]->getMimeType();
                        if ($mime_type == 'image/png' || $mime_type == 'image/gif' || $mime_type == 'image/jpeg' || $mime_type == 'image/jpg' || $mime_type == 'image/bmp') {
                            $extension = $input_file['event_image'][0]->getClientOriginalExtension();
                            $filename = basename($input_file['event_image'][0]->getClientOriginalName(), "." . $extension) . '_' . $rid . '.' . $extension;
                            $upload_success = $input_file['event_image'][0]->move($destinationPath, $filename);
                            /*                             * *************resize-image*************** */
                            /* $rsfname = basename($input_file['event_image']->getClientOriginalName(), ".".$extension).'_'.$rid.'_200x200.'.$extension;
                              $rspath = public_path('uploads/account_type/'.$imgs_apath.'/'.$rsfname);
                              Image::make($destinationPath.$filename)->resize(200, 200)->save($rspath); */
                            /*                             * *************************************** */
                            $nw_evid = DB::table('events')->insertGetId(['u_id' => $clid, 'account_id' => $accountdetail[0]->id, 'account_type' => $acc_type, 'event_type' => $data['event_type'],
                                'event_catid' => $data['event_catid'], 'event_name' => $data['event_name'], 'event_url' => $event_url, 'event_image' => $filename,
                                'event_venue' => $data['event_venue'], 'event_address' => $data['event_address'], 'address_secd' => $data['address_secd'], 'city' => $data['city'],
                                'state' => $data['state'], 'country' => $data['country'], 'zip_code' => $data['zip_code'], 'map_show' => $showmap, 'event_dtype' => $data['event_dtype'],
                                'event_date' => $ev_date, 'event_time' => $data['sevent_time'], 'end_date' => $end_date, 'end_time' => $data['eevent_time'], 'event_cost' => $data['event_cost'],
                                'event_price' => $all_ep, 'ticket_surl' => $ticket_surl, 'event_description' => $data['event_description'], 'fb_link' => $data['fb_link'],
                                'tw_link' => $data['tw_link'], 'available_purchase' => $av_purchase, 'kid_event' => $kid_event, 'family_event' => $family_event,
                                'religious_event' => $religious_event, 'private_event' => $data['private_event'], 'share_event' => $share_event,
                                'password_estatus' => $password_estatus, 'pass_event' => $pass_event, 'created_at' => date('Y-m-d H:i:s'),
                                'updated_at' => date('Y-m-d H:i:s')]);
                        } else {
                            return Redirect::back()->with('failed_upfile', 'Image tupe not supported, please upload (png/gif/jpeg/jpg/bmp) format')->withInput();
                            die;
                        }
                    } else {
                        return Redirect::back()->with('failed_upfile', 'Please upload max file size of 2mb.')->withInput();
                        die;
                    }
                } else {
                    $nw_evid = DB::table('events')->insertGetId(['u_id' => $clid, 'account_id' => $accountdetail[0]->id, 'account_type' => $acc_type, 'event_type' => $data['event_type'],
                        'event_catid' => $data['event_catid'], 'event_name' => $data['event_name'], 'event_url' => $event_url, 'event_image' => $evid_img,
                        'event_venue' => $data['event_venue'], 'event_address' => $data['event_address'], 'address_secd' => $data['address_secd'], 'city' => $data['city'],
                        'state' => $data['state'], 'country' => $data['country'], 'zip_code' => $data['zip_code'], 'map_show' => $showmap, 'event_dtype' => $data['event_dtype'],
                        'event_date' => $ev_date, 'event_time' => $data['sevent_time'], 'end_date' => $end_date, 'end_time' => $data['eevent_time'], 'event_cost' => $data['event_cost'],
                        'event_price' => $all_ep, 'ticket_surl' => $ticket_surl, 'event_description' => $data['event_description'], 'fb_link' => $data['fb_link'],
                        'tw_link' => $data['tw_link'], 'available_purchase' => $av_purchase, 'kid_event' => $kid_event, 'family_event' => $family_event,
                        'religious_event' => $religious_event, 'private_event' => $data['private_event'], 'share_event' => $share_event,
                        'password_estatus' => $password_estatus, 'pass_event' => $pass_event, 'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s')]);
                }
                /*                 * ****event-date-multi-section******** */
                if (isset($nw_evid) && $data['event_dtype'] == 'multi') {
                    $xlx = 0;
                    if (sizeof($data['arrdate']) > 1) {
                        foreach ($data['arrdate'] as $stdt) {
                            $startdt = date('Y-m-d', strtotime($stdt));
                            $itcendate = date('Y-m-d', strtotime($data['enddate'][$xlx]));
                            if (!empty($data['mw_date'][$xlx]) && $data['mw_date'][$xlx] != 'Month Date') {
                                $mw_date = $data['mw_date'][$xlx];
                            } else {
                                $mw_date = null;
                            }
                            DB::table('event_multidate')->insert(['ev_id' => $nw_evid, 'event_datetype' => $data['nspd'][$xlx], 'start_date' => $startdt,
                                'enddate' => $itcendate, 'ofthe' => $data['ofthe'][$xlx], 'start_time' => $data['startime'][$xlx], 'st_format' => $data['st_format'][$xlx],
                                'endtime' => $data['endtime'][$xlx], 'et_format' => $data['et_format'][$xlx], 'total_day' => $data['total_day'][$xlx],
                                'multype_wm' => $data['wgtdaysvl'][$xlx], 'mw_date' => $mw_date, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')]);
                            $xlx++;
                        }
                    } else {
                        $startdt = date('Y-m-d', strtotime($data['arrdate'][$xlx]));
                        $itcendate = date('Y-m-d', strtotime($data['enddate'][$xlx]));
                        if (!empty($data['mw_date'][$xlx]) && $data['mw_date'][$xlx] != 'Month Date') {
                            $mw_date = $data['mw_date'][$xlx];
                        } else {
                            $mw_date = null;
                        }
                        DB::table('event_multidate')->insert(['ev_id' => $nw_evid, 'event_datetype' => $data['nspd'][$xlx], 'start_date' => $startdt,
                            'enddate' => $itcendate, 'ofthe' => $data['ofthe'][$xlx], 'start_time' => $data['startime'][$xlx], 'st_format' => $data['st_format'][$xlx],
                            'endtime' => $data['endtime'][$xlx], 'et_format' => $data['et_format'][$xlx], 'total_day' => $data['total_day'][$xlx],
                            'multype_wm' => $data['wgtdaysvl'][$xlx], 'mw_date' => $mw_date, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')]);
                    }
                }

                if (!empty($accountdetail[0]->id)) {
                    /*                     * *********mail************ */
                    $memail_data = DB::table('mail_notifications')->where('yfollow_page', '=', 'y')->select('id', 'user_id', 'yfollow_page')->get();
                    if ($memail_data[0]->yfollow_page == 'y') {
                        foreach ($memail_data as $med_uid) {
                            $mud_data = DB::table('users')->where('id', '=', $med_uid->user_id)->select('id', 'full_name', 'email')->get();
                            $acc_name = $accountdetail[0]->name;
                            //Send Email	
                            $Subject = "Event managers created new event for " . $acc_name . " Account - Discover Your Event";
                            $pagurl = url($accountdetail[0]->account_url);
                            $joinurl = url('logInSignUp/');
                            $emdata = array(
                                'uname' => $mud_data[0]->full_name,
                                'event_name' => $data['event_name'],
                                'event_dmess' => 'and event date',
                                'event_date' => $ev_date,
                                'mess' => 'Event managers created new event',
                                'jnurl' => $pagurl,
                                'joinurl' => $joinurl,
                            );
                            $SendToEmail = $mud_data[0]->email;
                            Mail::send('email.event-real_email', $emdata, function($message) use ($SendToEmail, $Subject) {
                                $message->to($SendToEmail)->subject($Subject);
                            });
                        }
                        /*                         * ************** */
                    }
                }

                return Redirect::to($aname . '/account/manageEvent')->with('succ_mesg', 'Event created successfully');
            }
        } else {
            return Redirect::back()->withInput();

        }
    }

    public function manageAccevent($aname) {
        
        if (Sentry::check() && !empty($aname)) {
            $id = Sentry::getUser()->id;
            $acd_gid = $acd_id = $auser_role = 0;
            $Event = new Event;
            $UserAccounts = new UserAccounts;
            $getAccountEvents = $Event->getCurrentUserEvents();
            
            $UserAccounts->id = $id; 
            $data["account_url"] = $aname;
            $accountdetail = $UserAccounts->getAccountDetails($data);
           
            $acd_gid = $accountdetail->g_id;
            $acd_id = $accountdetail->id;
            $auser_role = $accountdetail->account_urole;
             $ac_murl = $accountdetail->account_url;
            
            /*
            $accountdetail = DB::table('user_accounts')->where('user_accounts.u_id', '=', $id)->Where('user_accounts.status','=', 'open')
      ->orWhere('user_accounts.status','=', 'transfer')->join('account_details', 'user_accounts.account_did', '=', 'account_details.id')
	    ->select('account_details.id','account_details.g_id','account_details.account_url','user_accounts.account_urole')->get();
             */
             
             
            if (sizeof($accountdetail) > 0) {
               
               
                
                if ($acd_gid == 2) {
                    $acc_type = 'business';
                } elseif ($acd_gid == 5) {
                    $acc_type = 'municipality';
                } else {
                    $acc_type = 'club';
                }
                 
                
                $data = array();
                $data["account_id"] = $acd_id;
                $data["account_type"] = $acc_type;
                $data["limit"] = 10;
                
                $all_event = $Event->getUserPersonalEvents( $data , 10 );
                
                return \View::make('public/default2/account/manage-event')
                        ->with('title', 'Manage Event - DiscoverYourEvent')
                        ->with('all_event', $all_event)
                        ->with('acc_url', $aname)
                        ->with('auser_role', $auser_role)
                        ->with('active', 'me');
            } else {
                return Redirect::to('/');
            }
        } else {
            return Redirect::to('/');
        }
    }

    public function updateAccevent($aname, $eid) {

        if (Sentry::check() && !empty($aname) && !empty($eid)) {

            $eventModel = new Event;
            $UserAccounts = new UserAccounts;
            $acd_gid = 0;
            $clid = Sentry::getUser()->id;
            $md_arr = $eventModel->md_arr;
            $tmf = $eventModel->tmf;
            $sdwkfmt = $eventModel->sdwkfmt;
            $ofday = $eventModel->ofday;
            $st_cvs = $eventModel->st_cvs;
            $auser_role = false;
            $countries = $eventModel->getEventCountries();
            
             $id = Sentry::getUser()->id;
             
            $UserAccounts->id = $id;
            $accountdetail = $UserAccounts->getAccountDetails();
            /*
                    DB::table('user_accounts')
                    ->where('user_accounts.u_id', '=', $id)
                    ->Where('user_accounts.status', '=', 'open')
                     ->orWhere('user_accounts.status', '=', 'transfer')
                    ->join('account_details', 'user_accounts.account_did', '=', 'account_details.id')
                    ->select('account_details.id', 'account_details.g_id', 'account_details.account_url', 'user_accounts.account_urole')
                    ->get();
            */
            
            if (sizeof($accountdetail) > 0) {
                
                foreach ($accountdetail as $acdls) {
                    $ac_murl = $acdls->account_url;
                   
                    if ($aname == $ac_murl) {
                        $acd_id = $acdls->id;
                        $acd_gid = $acdls->g_id;
                        $auser_role = $acdls->account_urole;
                    }
                }
                
                if ($acd_gid == 2) {
                    $acc_type = 'business';
                } elseif ($acd_gid == 5) {
                    $acc_type = 'municipality';
                } else {
                    $acc_type = 'club';
                }
                
                $cuntdata = DB::table('countries')->select('id', 'name')->get();
                $data['eid'] =$eid;
                //$data['acd_id'] =$acd_id;
                
                $edit_event = $eventModel->getUserPersonalEvents($data);
                /*
                        DB::table('events')
                        //->where('events.user_evdelete', '=', 'n')
                        ->where('events.u_id', '=', $id)
                        ->where('events.id', '=', $eid)
                        //->where('events.account_id', '=', $acd_id)
                        ->join('event_data', 'events.event_catid', '=', 'event_data.id')
                        ->select('events.id as eid', 'events.account_type', 'events.account_id', 'events.event_catid', 'events.event_type', 'events.contact_person', 'events.phone_no', 'events.email_address', 'events.website', 'events.event_name', 'events.event_url', 'events.event_image', 'events.event_venue', 'events.event_address', 'events.address_secd', 'events.city', 'events.state', 'events.country', 'events.zip_code', 'events.map_show', 'events.event_dtype', 'events.event_date', 'events.event_time', 'events.end_date', 'events.end_time', 'events.event_cost', 'events.event_price', 'events.ticket_surl', 'events.event_description', 'events.fb_link', 'events.tw_link', 'events.private_event', 'event_data.event_category', 'event_data.id as ed_id', 'events.available_purchase', 'events.kid_event', 'events.family_event', 'events.religious_event', 'events.private_event', 'events.share_event', 'events.password_estatus', 'events.pass_event')
                        ->get();
                */
               
                return \View::make('public/default2/account/acc-update-event')
                        ->with('title', 'Update Event - DiscoverYourEvent')
                        ->with('st_cvs', $st_cvs)
                        ->with('month_darray', $md_arr)
                        ->with('tmformat', $tmf)
                        ->with('sdwkfmt', $sdwkfmt)
                        ->with('ofheday', $ofday)
                        ->with('edit_event', $edit_event)
                        ->with('auser_role', $auser_role)
                        ->with('cuntd', $countries)
                        ->with('active', 'not');
            } else {
                return Redirect::to('/');
            }
        } else {
            return Redirect::to('/');
        }
    }

    public function postAccupdatevent($aname, $eid, Request $request) {
        $data = $request->input();
        $clid = Sentry::getUser()->id;
        $accountdetail = DB::table('account_details')->where('account_details.u_id', '=', $clid)->where('account_details.account_url', '=', $aname)
                        ->Where('user_accounts.status', '=', 'open')->orWhere('user_accounts.status', '=', 'transfer')->where('account_details.account_url', '=', $aname)
                        ->join('user_accounts', 'account_details.id', '=', 'user_accounts.account_did')->select('account_details.id', 'account_details.g_id')->get();
        if (empty($accountdetail[0]->g_id)) {
            return Redirect::back()->withInput();
            die;
        }
        $check_data = DB::table('events')->where('events.user_evdelete', '=', 'n')->where('events.u_id', '=', $clid)->where('events.id', '=', $eid)->select('id', 'event_image')->get();
        if (!empty($check_data[0]->id)) {
            $validator = Validator::make($request->all(), [
                        'event_type' => 'required',
                        'event_catid' => 'required',
                        'event_venue' => 'required',
                        'event_name' => 'required',
                        // 'sevent_date' => 'required|date',
                        // 'sevent_time' => 'required',
                        'state' => 'required',
                        'country' => 'required',
                        'city' => 'required',
                        'event_cost' => 'required',
                        'event_description' => 'required',
                        'private_event' => 'required',
            ]);
            if ($validator->fails()) {
                return Redirect::back()->withErrors($validator)->withInput();
            } else {
                $rid = Str::random(6);

                if ($data['event_cost'] == 'paid') {
                    
                    if ($data['event_price'] != '') {
                        
                        $evp_regs = '/^[0-9]+$/';
                        $evp_regss = '/^[0-9]+[.][0-9]+$/';
                        
                        if (preg_match($evp_regs, $data['event_price']) || preg_match($evp_regss, $data['event_price'])) {
                            $event_price = $data['event_price'];
                            $all_ep = $event_price;
                        }
                        else {
                            return Redirect::back()->with('req_evprice', 'Cost price value not valid, please used 0-9 or 10.99 format')->withInput();
                        }
                        $ticket_surl = $data['ticket_surl'];
                        
                        if (!empty($data['ticket_surl']) && filter_var($ticket_surl, FILTER_VALIDATE_URL) === false) {
                                return Redirect::back()->with('inv_tkurl', $ticket_surl . ' is not a valid URL, http:// or https:// is required')->withInput();
                        }
                        else {
                            $ticket_surl = null;
                        }
                        
                    } else {
                        return Redirect::back()->with('req_evprice', 'Cost price is required field')->withInput();
                        die;
                    }
                    if (!empty($data['mevent_price'])) {
                        $evp_regs = '/^[0-9]+$/';
                        $evp_regss = '/^[0-9]+[.][0-9]+$/';
                        if (preg_match($evp_regs, $data['mevent_price']) || preg_match($evp_regss, $data['mevent_price'])) {
                            $mevent_price = $data['mevent_price'];
                            $all_ep = $event_price . '-' . $mevent_price;
                        } else {
                            return Redirect::back()->with('req_evprice', 'Cost price value not valid, please used 0-9 or 10.99 format')->withInput();
                            die;
                        }
                    }
                } else {
                    $all_ep = null;
                    $ticket_surl = null;
                }
                $acd_gid = $accountdetail[0]->g_id;
                $acd_id = $accountdetail[0]->id;
                if ($acd_gid == 2) {
                    $acc_type = 'business';
                } elseif ($acd_gid == 5) {
                    $acc_type = 'municipality';
                } else {
                    $acc_type = 'club';
                }
                if (isset($data['available_purchase'])) {
                    $av_purchase = 'y';
                } else {
                    $av_purchase = 'n';
                }
                if (isset($data['kid_event'])) {
                    $kid_event = 'y';
                } else {
                    $kid_event = 'n';
                }
                if (isset($data['family_event'])) {
                    $family_event = 'y';
                } else {
                    $family_event = 'n';
                }
                if (isset($data['religious_event'])) {
                    $religious_event = 'y';
                } else {
                    $religious_event = 'n';
                }

                /*                 * *****event-date-section******** */
                if ($data['event_dtype'] == 'single') {
                    $ev_date = date('Y-m-d', strtotime($data['sevent_date']));
                    if (empty($data['sevent_date'])) {
                        return Redirect::back()->with('ev_date', 'Event Start date field is required')->withInput();
                        die;
                    }
                    if (!empty($data['event_date'])) {
                        $end_date = date('Y-m-d', strtotime($data['event_date']));
                    } else {
                        $end_date = null;
                    }
                } else {
                    if (empty($data['arrdate'])) {
                        return Redirect::back()->with('ev_section', 'Event date Section is required')->withInput();
                        die;
                    }
                    $ev_date = date('Y-m-d', strtotime($data['arrdate'][0]));
                    $end_date = null;
                }

                if (isset($data['map_show'])) {
                    $showmap = 'y';
                } else {
                    $showmap = 'n';
                }
                if (isset($data['our_pid']) && !empty($data['our_pid'])) {
                    $evid_img = $data['our_pid'];
                } else {
                    if (!empty($check_data[0]->event_image)) {
                        $evid_img = $check_data[0]->event_image;
                    } else {
                        $evid_img = null;
                    }
                }
                if ($data['private_event'] == 'y') {
                    if (!isset($data['sharepinvt_event']) && empty($data['sharepinvt_event'])) {
                        return Redirect::back()->with('share_event', 'Please choose one option, its required')->withInput();
                        die();
                    }
                    $share_event = $data['sharepinvt_event'];
                    if ($share_event == 'n') {
                        if (isset($data['passprv_event'])) {
                            $password_estatus = $data['passprv_event'];
                        } else {
                            $password_estatus = 'n';
                        }

                        if (isset($password_estatus) && $password_estatus == 'y') {
                            $pass_event = $data['pr-passevnt'];
                            if (empty($pass_event)) {
                                return Redirect::back()->with('pev_req', 'Private event password field is required')->withInput();
                                die;
                            }
                        } else {
                            $pass_event = null;
                        }
                    } else {
                        $pass_event = null;
                        $password_estatus = 'n';
                    }
                } else {
                    $share_event = 'n';
                    $password_estatus = 'n';
                    $pass_event = null;
                }
                //$check_eurl = DB::table('events')->where('id', '!=', $eid)->where('event_url', '=', $data['event_url'])->select('id')->get();
                //if(empty($check_eurl[0]->id)){		 
                $input_file = $request->file();
                if (!empty($input_file['event_image'][0])) {
                    $event_filesize = $input_file['event_image'][0]->getClientSize();
                    if ($event_filesize < 2000001) {
                        //Upload Image		 
                        $destinationPath = 'public/uploads/events/' . $acc_type . '/';
                        $filename = $input_file['event_image'][0]->getClientOriginalName();
                        $mime_type = $input_file['event_image'][0]->getMimeType();
                        if ($mime_type == 'image/png' || $mime_type == 'image/gif' || $mime_type == 'image/jpeg' || $mime_type == 'image/jpg' || $mime_type == 'image/bmp') {
                            $extension = $input_file['event_image'][0]->getClientOriginalExtension();
                            $filename = basename($input_file['event_image'][0]->getClientOriginalName(), "." . $extension) . '_' . $rid . '.' . $extension;
                            $upload_success = $input_file['event_image'][0]->move($destinationPath, $filename);
                            /*                             * *************resize-image*************** */
                            /* $rsfname = basename($input_file['event_image']->getClientOriginalName(), ".".$extension).'_'.$rid.'_200x200.'.$extension;
                              $rspath = public_path('uploads/account_type/'.$imgs_apath.'/'.$rsfname);
                              Image::make($destinationPath.$filename)->resize(200, 200)->save($rspath); */
                            /*                             * *************************************** */
                            if (!empty($check_data[0]->event_image)) {
                                File::delete($destinationPath . $check_data[0]->event_image);
                            }
                            DB::table('events')->where('events.user_evdelete', '=', 'n')->where('events.u_id', '=', $clid)->where('events.id', '=', $eid)->where('events.account_id', '=', $acd_id)
                                    ->update(['event_type' => $data['event_type'], 'event_catid' => $data['event_catid'], 'event_name' => $data['event_name'],
                                        'event_image' => $filename, 'event_venue' => $data['event_venue'], 'event_address' => $data['event_address'],
                                        'address_secd' => $data['address_secd'], 'city' => $data['city'], 'state' => $data['state'], 'country' => $data['country'],
                                        'zip_code' => $data['zip_code'], 'map_show' => $showmap, 'event_dtype' => $data['event_dtype'], 'event_date' => $ev_date,
                                        'event_time' => $data['sevent_time'], 'end_date' => $end_date, 'end_time' => $data['eevent_time'], 'event_cost' => $data['event_cost'],
                                        'event_price' => $all_ep, 'ticket_surl' => $ticket_surl, 'event_description' => $data['event_description'], 'fb_link' => $data['fb_link'],
                                        'tw_link' => $data['tw_link'], 'available_purchase' => $av_purchase, 'kid_event' => $kid_event, 'family_event' => $family_event,
                                        'religious_event' => $religious_event, 'private_event' => $data['private_event'], 'share_event' => $share_event,
                                        'password_estatus' => $password_estatus, 'pass_event' => $pass_event, 'updated_at' => date('Y-m-d H:i:s')]);
                        } else {
                            return Redirect::back()->with('failed_upfile', 'Image tupe not supported, please upload (png/gif/jpeg/jpg/bmp) format')->withInput();
                            die;
                        }
                    } else {
                        return Redirect::back()->with('failed_upfile', 'Please upload max file size of 2mb.')->withInput();
                        die;
                    }
                } else {
                    DB::table('events')->where('events.user_evdelete', '=', 'n')->where('events.u_id', '=', $clid)->where('events.id', '=', $eid)->where('events.account_id', '=', $acd_id)
                            ->update(['event_type' => $data['event_type'], 'event_catid' => $data['event_catid'], 'event_name' => $data['event_name'],
                                'event_image' => $evid_img, 'event_venue' => $data['event_venue'], 'event_address' => $data['event_address'],
                                'address_secd' => $data['address_secd'], 'city' => $data['city'], 'state' => $data['state'], 'country' => $data['country'],
                                'zip_code' => $data['zip_code'], 'map_show' => $showmap, 'event_dtype' => $data['event_dtype'], 'event_date' => $ev_date, 'event_time' => $data['sevent_time'],
                                'end_date' => $end_date, 'end_time' => $data['eevent_time'], 'event_cost' => $data['event_cost'], 'event_price' => $all_ep,
                                'ticket_surl' => $ticket_surl, 'event_description' => $data['event_description'], 'fb_link' => $data['fb_link'], 'tw_link' => $data['tw_link'],
                                'available_purchase' => $av_purchase, 'kid_event' => $kid_event, 'family_event' => $family_event, 'religious_event' => $religious_event,
                                'private_event' => $data['private_event'], 'share_event' => $share_event, 'password_estatus' => $password_estatus, 'pass_event' => $pass_event,
                                'updated_at' => date('Y-m-d H:i:s')]);
                }
                /*                 * ****event-date-multi-section******** */
                if ($data['event_dtype'] == 'multi') {
                    $xlx = 0;
                    $emdcount = DB::table('event_multidate')->where('ev_id', '=', $eid)->select('id')->get();
                    if (sizeof($emdcount) > 0) {
                        $gcmdateval = sizeof($emdcount);
                        if ($gcmdateval > sizeof($data['arrdate'])) {
                            $getmdval = $gcmdateval - 1;
                            DB::delete('delete from event_multidate WHERE id = ' . $emdcount[$getmdval]->id);
                        }
                    }
                    foreach ($data['arrdate'] as $stdt) {
                        if (!empty($data['mw_date'][$xlx]) && $data['mw_date'][$xlx] != 'Month Date') {
                            $mw_date = $data['mw_date'][$xlx];
                        } else {
                            $mw_date = null;
                        }
                        $startdt = date('Y-m-d', strtotime($stdt));
                        $itcendate = date('Y-m-d', strtotime($data['enddate'][$xlx]));
                        if (isset($data['multieid'][$xlx]) && !empty($data['multieid'][$xlx])) {
                            DB::table('event_multidate')->where('id', '=', $data['multieid'][$xlx])->where('ev_id', '=', $eid)
                                    ->update(['event_datetype' => $data['nspd'][$xlx], 'start_date' => $startdt, 'enddate' => $itcendate, 'ofthe' => $data['ofthe'][$xlx],
                                        'start_time' => $data['startime'][$xlx], 'st_format' => $data['st_format'][$xlx], 'endtime' => $data['endtime'][$xlx],
                                        'et_format' => $data['et_format'][$xlx], 'total_day' => $data['total_day'][$xlx], 'multype_wm' => $data['wgtdaysvl'][$xlx],
                                        'mw_date' => $mw_date, 'updated_at' => date('Y-m-d H:i:s')]);
                        } else {
                            DB::table('event_multidate')->insert(['ev_id' => $eid, 'event_datetype' => $data['nspd'][$xlx], 'start_date' => $startdt,
                                'enddate' => $itcendate, 'ofthe' => $data['ofthe'][$xlx], 'start_time' => $data['startime'][$xlx],
                                'st_format' => $data['st_format'][$xlx], 'endtime' => $data['endtime'][$xlx], 'et_format' => $data['et_format'][$xlx],
                                'total_day' => $data['total_day'][$xlx], 'multype_wm' => $data['wgtdaysvl'][$xlx], 'created_at' => date('Y-m-d H:i:s'),
                                'mw_date' => $mw_date, 'updated_at' => date('Y-m-d H:i:s')]);
                        }
                        $xlx++;
                    }
                } else {
                    DB::delete('delete from event_multidate WHERE ev_id = ' . $eid);
                }
                return Redirect::to($aname . '/account/manageEvent')->with('succ_mesg', 'Event updated successfully');
                /* } else {
                  return redirect::back()->with('invl_url', 'The event URL has already been taken.')->withInput();
                  die;
                  } */
            }
        }
    }

    public function saveEvent($eventurl) {
        if (Sentry::check() && !empty($eventurl)) {
            $clid = Sentry::getUser()->id;
            $check_data = DB::table('events')->where('user_evdelete', '=', 'n')->where('event_url', '=', $eventurl)->select('id', 'u_id', 'account_id', 'event_name', 'event_url')->get();

            if (!empty($check_data[0]->id)) {
                $check_esaved = DB::table('users_events')->where('e_id', '=', $check_data[0]->id)->where('u_id', '=', $clid)->select('id')->get();
                if (!empty($check_esaved[0]->id)) {
                    return Redirect::to('event/' . $eventurl)->with('succ_mesg', 'Event already added on your account');
                } else {
                    DB::table('users_events')->insertGetId(['e_id' => $check_data[0]->id, 'u_id' => $clid, 'eowner_id' => $check_data[0]->u_id, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')]);
                    $notfsub = '<span class="ntf-uname">You have an new person</span> attending the <span class="ntf-data">"' . $check_data[0]->event_name . '"</span> event';
                    DB::table('notifications')->insert(['onr_id' => $check_data[0]->u_id, 'u_id' => $clid, 'type' => 'event', 'subject' => $notfsub, 'object_id' => $check_data[0]->id, 'object_type' => 'attend', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')]);
                    //return Redirect::to('event/'.$eventurl)->with('succ_mesg', 'Event successfully saved on your account');

                    if (!empty($check_data[0]->account_id)) {
                        $account_data = DB::table('account_details')->where('account_details.id', '=', $check_data[0]->account_id)->join('users', 'account_details.u_id', '=', 'users.id')
                                        ->select('account_details.id', 'account_details.name', 'account_details.account_url', 'account_details.flemail_update', 'users.full_name', 'users.email')->get();
                        if (isset($account_data[0]->id) && $account_data[0]->flemail_update == 'y') {
//echo 12312;
//die;	   
                            /*                             * *********mail************ */
                            $acc_name = $account_data[0]->name;
                            /* , 'uemail'     => Sentry::getUser()->email, */
                            //Send Email	
                            $Subject = "You have an person attending the event for " . $acc_name . " Account - Discover Your Event";
                            $pagurl = url($account_data[0]->account_url);
                            $joinurl = url('logInSignUp/');
                            $emdata = array(
                                'uname' => $account_data[0]->full_name,
                                'event_name' => $check_data[0]->event_name . ' event',
                                'un_uname' => Sentry::getUser()->full_name,
                                'mess' => 'attending',
                                'jnurl' => $pagurl,
                                'joinurl' => $joinurl,
                            );
                            $SendToEmail = $account_data[0]->email;
                            Mail::send('email.pagenotf_email', $emdata, function($message) use ($SendToEmail, $Subject) {
                                $message->to($SendToEmail)->subject($Subject);
                            });
                            /*                             * ************** */
                        }
                    }
                    if (empty($check_data[0]->account_id)) {
                        $mailevent_data = DB::table('mail_notifications')->where('mail_notifications.user_id', '=', $check_data[0]->u_id)->join('users', 'mail_notifications.user_id', '=', 'users.id')
                                        ->select('mail_notifications.id', 'mail_notifications.event_attend', 'users.full_name', 'users.email')->get();
                        if (isset($mailevent_data[0]->id) && $mailevent_data[0]->event_attend == 'y') {
                            /*                             * *********mail************ */
                            $acc_name = $mailevent_data[0]->name;
                            /* , 'uemail'     => Sentry::getUser()->email, */
                            //Send Email	
                            $Subject = "You have an person attending the event for " . $acc_name . " Account - Discover Your Event";
                            $pagurl = url('event/' . $check_data[0]->event_url);
                            $joinurl = url('logInSignUp/');
                            $emdata = array(
                                'uname' => $mailevent_data[0]->full_name,
                                'event_name' => $check_data[0]->event_name . ' event',
                                'un_uname' => Sentry::getUser()->full_name,
                                'mess' => 'attending',
                                'jnurl' => $pagurl,
                                'joinurl' => $joinurl,
                            );
                            $SendToEmail = $mailevent_data[0]->email;
                            Mail::send('email.pagenotf_email', $emdata, function($message) use ($SendToEmail, $Subject) {
                                $message->to($SendToEmail)->subject($Subject);
                            });
                            /*                             * ************** */
                        }
                    }

                    return 'succ';
                }
            } else {
                return 'error';
            }
        } else {
            return 'not-log';
            // return Redirect::to('logInSignUp')->with('persignup','lnot-saved');
        }
    }

    public function unattendEvent(Request $request) {
        if (Sentry::check()) {
            $uid = Sentry::getUser()->id;
            $data = $request->input();
            $chk_uent = DB::table('users_events')->where('e_id', '=', $data['evntid'])->where('u_id', '=', $uid)->select('id', 'eowner_id')->get();
            if (!empty($chk_uent[0]->id)) {
                DB::delete('delete from users_events WHERE id = ' . $chk_uent[0]->id);
                $event_data = DB::table('events')->where('id', '=', $data['evntid'])->where('user_evdelete', '=', 'n')->select('id', 'u_id', 'account_id', 'event_name', 'event_url')->get();
                $notfsub = '<span class="ntf-uname"> You have an person</span> unattend the <span class="ntf-data">"' . $event_data[0]->event_name . '"</span> event';
                DB::table('notifications')->insert(['onr_id' => $chk_uent[0]->eowner_id, 'u_id' => $uid, 'type' => 'event', 'subject' => $notfsub, 'object_id' => $data['evntid'], 'object_type' => 'unattend', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')]);

                if (!empty($event_data[0]->account_id)) {
                    $account_data = DB::table('account_details')->where('account_details.id', '=', $event_data[0]->account_id)->join('users', 'account_details.u_id', '=', 'users.id')
                                    ->select('account_details.id', 'account_details.name', 'account_details.account_url', 'account_details.flemail_update', 'users.full_name', 'users.email')->get();

                    if (isset($account_data[0]->id) && $account_data[0]->flemail_update == 'y') {
                        /*                         * *********mail************ */
                        $acc_name = $account_data[0]->name;
                        /* , 'uemail'     => Sentry::getUser()->email, */
                        //Send Email	
                        $Subject = "You have an person unattend the event for " . $acc_name . " Account - Discover Your Event";
                        $pagurl = url($account_data[0]->account_url);
                        $joinurl = url('logInSignUp/');
                        $emdata = array(
                            'uname' => $account_data[0]->full_name,
                            'event_name' => $event_data[0]->event_name . ' event',
                            'un_uname' => Sentry::getUser()->full_name,
                            'mess' => 'unattend',
                            'jnurl' => $pagurl,
                            'joinurl' => $joinurl,
                        );
                        $SendToEmail = $account_data[0]->email;
                        Mail::send('email.pagenotf_email', $emdata, function($message) use ($SendToEmail, $Subject) {
                            $message->to($SendToEmail)->subject($Subject);
                        });
                        /*                         * ************** */
                    }
                }
                if (empty($event_data[0]->account_id)) {
                    $mailevent_data = DB::table('mail_notifications')->where('mail_notifications.user_id', '=', $event_data[0]->u_id)->join('users', 'mail_notifications.user_id', '=', 'users.id')
                                    ->select('mail_notifications.id', 'mail_notifications.event_attend', 'users.full_name', 'users.email')->get();
                    if (isset($mailevent_data[0]->id) && $mailevent_data[0]->event_attend == 'y') {
                        /*                         * *********mail************ */
                        $acc_name = $mailevent_data[0]->name;
                        /* , 'uemail'     => Sentry::getUser()->email, */
                        //Send Email	
                        $Subject = "You have an person unattend the event for " . $acc_name . " Account - Discover Your Event";
                        $pagurl = url('event/' . $event_data[0]->event_url);
                        $joinurl = url('logInSignUp/');
                        $emdata = array(
                            'uname' => $mailevent_data[0]->full_name,
                            'event_name' => $event_data[0]->event_name . ' event',
                            'un_uname' => Sentry::getUser()->full_name,
                            'mess' => 'unattend',
                            'jnurl' => $pagurl,
                            'joinurl' => $joinurl,
                        );
                        $SendToEmail = $mailevent_data[0]->email;
                        Mail::send('email.pagenotf_email', $emdata, function($message) use ($SendToEmail, $Subject) {
                            $message->to($SendToEmail)->subject($Subject);
                        });
                        /*                         * ************** */
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

    public function postRefinesearch(Request $request) {

        $data = $request->input();

        if (Sentry::check()) { // check authentication
            $lg_id = Sentry::getUser()->id;

            if (!empty($data['location_range'])) {
                $location_range = $data['location_range'];
            } else {
                $location_range = 0;
            }
            $Searchevnt = new Searchevnt();
            $data["limit"] = 8;
            $refine_events = $Searchevnt->searchEvents($data, "limit");


            if (isset($refine_events[0]) && !empty($refine_events[0]->id)) {
                
                $xx = 1;
                foreach ($refine_events as $flce) {
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

                    if (!empty($flce->state) && !empty($flce->country)) {
                        $getssdata = DB::table('states')->where('country_id', '=', $flce->country)->where('name', '=', $flce->state)->select('id', 'name')->get();
                        if (!empty($getssdata[0]->id)) {
                            $staten = $getssdata[0]->name;
                        } else {
                            $staten = $flce->state;
                        }
                        if (!empty($staten)) {
                            if (!empty($lfalc_add)) {
                                $lfalc_add .= ',' . $staten;
                            } else {
                                $lfalc_add = $staten;
                            }
                        }
                    }
                    
                    if (!empty($flce->country)) {
                        $getccdata = DB::table('countries')->where('id', '=', $flce->country)->select('id', 'name')->get();
                        if (!empty($lfalc_add)) {
                            $lfalc_add .= ',' . $getccdata[0]->name;
                        } else {
                            $lfalc_add = $getccdata[0]->name;
                        }
                    }
                    
                    $maddressArray[] = $lfalc_add;
                    $morgTitleArray[] = $flce->event_name;
                    $morg_surlArray[] = $flce->event_url;
                    $xx++;
                }
            } else {
                $maddressArray = null;
                $morgTitleArray = null;
                $morg_surlArray = null;
            }

            $custom_class = 'all-events';
            $womapclick = 0;

            return \View::make('public/default2/ajex/refine-search')
                            ->with('refine_events', $refine_events)
                            ->with('location_range', $location_range)
                            ->with('ucntyname', $data['ucntyname'])
                            ->with('rs_maarray', $maddressArray)
                            ->with('rs_mTiAr', $morgTitleArray)
                            ->with('rs_mog_slar', $morg_surlArray)
                            ->with('cust_class', $custom_class)
                            ->with('clid', $lg_id)
                            ->with('wo', $womapclick);
        } else {
            return Redirect::to('/');
        }
    }

    public function ajexRefinesearch($page, Request $request) {
		
        $data = $request->input();	
        if (Sentry::check() && !empty($page)) {
            $cpage = $page - 1;
            $skip_data = $cpage * 3;
            $lg_id = Sentry::getUser()->id;
            
			$Searchevnt = new Searchevnt();
			$data["limit"] = 8;
			
			$refine_events = $Searchevnt->searchEvents(  $data , "limit" , $skip_data );
			
            $custom_class = 'all-events';
			
            return \View::make('public/default2/ajex/ajex-rsearch')->with('refine_events', $refine_events)->with('cust_class', $custom_class)->with('clid', $lg_id);
			
        }
		else {
			
            return Redirect::to('/');
        }
    }

    public function postEvntfollow(Request $request) {
        if (Sentry::check()) {
            $uid = Sentry::getUser()->id;
            $data = $request->input();
            $get_ownerid = DB::table('events')->where('user_evdelete', '=', 'n')->where('id', '=', $data['ascid'])->select('u_id')->get();
            if (!empty($get_ownerid[0]->u_id)) {
                $cflw = DB::table('user_follows')->where('follow_id', '=', $data['ascid'])->where('u_id', '=', $uid)->where('follow_type', '=', 'event')->select('id')->get();
                if (empty($cflw[0]->id)) {
                    DB::table('user_follows')->insert(['follow_id' => $data['ascid'], 'u_id' => $uid, 'owner_id' => $get_ownerid[0]->u_id,
                        'follow_type' => 'event', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')]);
                }
                return 'success';
            }
        } else {
            return 'error';
        }
    }

    public function getCountry($code) {
        if (Sentry::check() && !empty($code)) {
            $cuntd = DB::table('countries')->where('sortname', '=', $code)->select('id', 'name')->get();
            $allcuntd = DB::table('countries')->select('id', 'name')->get();
            if (!empty($cuntd[0]->id)) {
                foreach ($cuntd as $gsc) {
                    echo '<option value="' . $gsc->id . '" selected>' . $gsc->name . '</option>';
                }
            }
            if (!empty($allcuntd[0]->id)) {
                foreach ($allcuntd as $allgsc) {
                    echo '<option value="' . $allgsc->id . '">' . $allgsc->name . '</option>';
                }
            }
            die;
        } else {
            echo 'error';
        }
    }

    public function postSfriend(Request $request) {
        $data = $request->input();
        if (!empty($data['eurl'])) {
            $event_data = DB::table('events')->where('events.user_evdelete', '=', 'n')->where('events.event_url', '=', $data['eurl'])
                            ->select('events.id', 'events.contact_person', 'events.email_address', 'events.event_name', 'events.event_image', 'events.event_date', 'events.event_time', 'events.end_date', 'events.end_time', 'events.event_description', 'events.fb_link', 'events.tw_link')->get();
            if (!empty($event_data[0]->id)) {
                //Send Email
                $SendToEmail = $data['femail'];
                $Subject = "Your friend invite you  - Discover Your Event";
                $eve = url('event/' . $data['eurl']);
                $evname = ucfirst($event_data[0]->event_name);
                $frdmsg = $data['fmsg'];
                $emdata = array(
                    'fwmssg' => $frdmsg,
                    'evname' => $evname,
                    'eventurl' => $eve,
                );

                Mail::send('email.share_this', $emdata, function($message) use ($SendToEmail, $Subject) {
                    $message->from('info@discoveryourevent.com');
                    $message->to($SendToEmail)->subject($Subject);
                });
                return 'success';
            } else {
                return 'error';
            }
        } else {
            return 'error';
        }
    }

    public function getEventimg($gname) {
        if (!empty($gname)) {
            if ($gname == 'Clubs_(Hockey,_Basketball,_Volleyball,_Yoga_etc.)') {
                $fldname = 'clubs';
            } else if ($gname == 'Races_(Horse,_Vehicles,_Bike_etc.)') {
                $fldname = 'races';
            } else if ($gname == 'Sporting_(Hockey,_Basketball,_Volleyball_etc.)') {
                $fldname = 'sporting';
            } else {
                $fldname = strtolower($gname);
            }
            $ecimg = DB::table('event_catimages')->where('ecat_name', 'LIKE', $fldname . '%')->get();
            echo '<div class="modal-header text-center"><button type="button" class="close" data-dismiss="modal" id="csimg">&times;</button>';
            if (!empty($ecimg[0]->id)) {
                foreach ($ecimg as $getecg) {
                    $ec_img = asset("uploads/event_category_images/" . $getecg->ecat_name . "/" . $getecg->ecat_image);
                    echo '<div class="col-md-4 our-event"><a class="ourimg_tag" href="javascript:void(0)" onClick="selimg(' . $getecg->id . ');"><img class="img-responsive" src="' . $ec_img . '"></a></div>';
                }
            } else {
                echo '<div class="col-md-6">Sorry, image not available</div>';
            }
            echo '</div>';
        }
    }

    /*
    public function saveEventdate(Request $request)
      {
      $data = $request->input();
      $ev_imgid = DB::table('event_image')->insertGetId(['fieldname' => $data['inputname'],'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')]);
      echo $ev_imgid;
      die;
      }
      public function removeEventdate($eid)
      {
      if(Sentry::check() && !empty($eid)){
      $uid = Sentry::getUser()->id;
      DB::delete('delete from event_image WHERE id = '.$eid.' && user_id = '.$uid );
      echo 'delete succ';
      }
      die;
      }
      public function editEventdate($eid,Request $request)
      {
      if(Sentry::check() && !empty($eid)){
      $uid = Sentry::getUser()->id;
      $data = $request->input();
      $edit_event = DB::table('event_image')->where('id', '=', $eid)->where('user_id', '=', $uid)->update(['fieldname' => $data['inputname'],
      'updated_at' => date('Y-m-d H:i:s')]);
      }
      echo 'update succ';
      die;
      } */
}
