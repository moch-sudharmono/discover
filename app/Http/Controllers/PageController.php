<?php

use Services\UserManager;
use Services\UserGroupManager;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;
use App\Models\Searchevnt;
use App\Models\AccountDetails;
use App\Models\UserAccounts;
use App\Models\UsersFollow;
//use Services\Validation\ValidationException as ValidationException;

class PageController extends Controller {

    protected $user_manager;
    protected $usergroup_manager;

    public function __construct(UserManager $user_manager, UserGroupManager $usergroup_manager) {
        $this->user_manager = $user_manager;
        $this->usergroup_manager = $usergroup_manager;
    }

    public function getCreatevent() {
        if (Sentry::check()) {
            return \View::make('public/default2/create-page')
                        ->with('title', 'Create Your Event - DiscoverYourEvent')
                        ->with('active', 'ce');
        } else {
            return Redirect::to('logInSignUp');
        }
    }

    public function getSCreatevent($xword) {
        if (Sentry::check()) {
            
            return \View::make('public/default2/create-page')
                        ->with('title', 'Create Your Event - DiscoverYourEvent')
                        ->with('active', 'ce');
        } else {
            if (!empty($xword)) {
                Session::forget('persignup');
                return Redirect::to('logInSignUp')->with('persignup', $xword);
            } else {
                return Redirect::to('logInSignUp');
            }
        }
    }

    public function getStarted() {
       
        $cspid = Session::get('cspid');
        if (!empty($cspid)) {
            $coun_data = DB::table('countries')
                                ->select('id', 'name')
                                ->where('id', '=', 38)
                                ->orWhere('id', '=', 231)
                                ->get();

            return \View::make('public/default2/get-started')
                        ->with('title', 'getStarted - DiscoverYourEvent')
                        ->with('cou_data', $coun_data)
                        ->with('signupid', $cspid);

        } else {
            return Redirect::to('/');
        }
    }

    public function postgetStarted(Request $request) {
        $cspid = Session::get('cspid');
        $city = NULL;
        
        if (!empty($cspid)) {
            $data = $request->input();
            $validator = Validator::make($request->all(), [
                        'phone'     => 'required|min:10',
                        'address'   => 'required',
                        'country'   => 'required',
                        'state'     => 'required',
                        'city'      => 'required',
                        'zip_code'  => 'required|min:6|max:6',
            ]);
            if ($validator->fails()) {
                return redirect('getStarted')->withErrors($validator)->withInput();
            }
           
            if (!empty($data['city']) && isset($data['city'])) {
                $city = $data['city'];
            }
                
                $gd_id = DB::table('group_details')
                            ->insertGetId(['g_id'           => 3, 
                                            'u_id'          => $cspid, 
                                            'address'       => $data['address'], 
                                            'city'          => $city,
                                            'state'         => $data['state'], 
                                            'zip_code'      => $data['zip_code'], 
                                            'country'       => $data['country'], 
                                            'phone'         => $data['phone'], 
                                            'created_at'    => date('Y-m-d H:i:s'), 
                                            'updated_at'    => date('Y-m-d H:i:s')]
                );

                DB::table('mail_notifications')
                    ->insertGetId(['user_id' => $cspid, 
                                    'created_at' => date('Y-m-d H:i:s'), 
                                    'updated_at' => date('Y-m-d H:i:s')]);

                Session::forget('cspid');
                
                return Redirect::to('set-up-account')->with('grd_id', $gd_id);
           
        } else {
            return Redirect::to('/');
        }
    }

    public function getBusinessform() {
        if (Sentry::check()) {
            $coun_data = DB::table('countries')
                            ->select('id', 'name')
                            ->where('id', '=', 38)
                            ->orWhere('id', '=', 231)
                            ->get();

            return \View::make('public/default2/business-form')
                        ->with('title', 'Business Form - DiscoverYourEvent')
                        ->with('cou_data', $coun_data);

        } else {
            return Redirect::to('logInSignUp');
        }
    }

    public function getMunicipalityform() {
        if (Sentry::check()) {
            $coun_data = DB::table('countries')
                            ->select('id', 'name')
                            ->where('id', '=', 38)
                            ->orWhere('id', '=', 231)
                            ->get();

            return \View::make('public/default2/municipality-form')
                        ->with('title', 'Municipality Form - DiscoverYourEvent')
                        ->with('cou_data', $coun_data);

        } else {
            return Redirect::to('logInSignUp');
        }
    }

    /*Club or Organization*/
    public function getCluborgform() {
        if (Sentry::check()) {
            $coun_data = DB::table('countries')
                            ->select('id', 'name')
                            ->where('id', '=', 38)
                            ->orWhere('id', '=', 231)
                            ->get();

            return \View::make('public/default2/club_organization')
                        ->with('title', 'Club/Organization Form - DiscoverYourEvent')
                        ->with('cou_data', $coun_data);

        } else {
            return Redirect::to('logInSignUp');
        }
    }

    public function postAllform(Request $request) {
        
        $data = $request->input();
        
        if (!empty($data['actype'])) {
            if ($data['actype'] == 'business') {
                $acid       = 2;
                $bladename  = 'business-form';
            } else if ($data['actype'] == 'municipality') {
                $acid       = 5;
                $bladename  = 'municipality-form';
            } else {
                $acid = 6;
                $bladename  = 'club-organization-form';
            }
            
            $validator = Validator::make($request->all(), [
                        'name'          => 'required|unique:account_details',
                        'phone'         => 'required|min:10',
                        'email'         => 'required|email',
                        'address'       => 'required',
                        'city'          => 'required',
                        'state'         => 'required',
                        'country'       => 'required',
                        'zip_code'      => 'required|min:6|max:6',
                        'upload_file'   => 'mimes:png,gif,jpeg,bmp',
            ]);
            
            
            if ($validator->fails()) {
                    return redirect($bladename)
                            ->withErrors($validator)
                            ->withInput();

            } else {
                $clid       = Sentry::getUser()->id;
                $input_file = $request->file();
                if (!empty($data['city'])) {
                    $dcity  = $data['city'];
                } else {
                    $dcity  = null;
                }
                if (!empty($input_file)) {
                    $u_filesize = $input_file['upload_file']->getClientSize();
                    if ($u_filesize < 2000001) {
                        //Upload Image
                        $rid = Str::random(7);
                        $destinationPath    = 'public/uploads/account_type/' . $data['actype'] . '/';
                        $filename           = $input_file['upload_file']->getClientOriginalName();
                        $mime_type          = $input_file['upload_file']->getMimeType();
                        $extension          = $input_file['upload_file']->getClientOriginalExtension();
                        $filename           = basename($input_file['upload_file']
                                                ->getClientOriginalName(), "." . $extension) . '_' . $rid . '.' . $extension;
                        $upload_success     = $input_file['upload_file']->move($destinationPath, $filename);
                        
                        $grd_id             = DB::table('account_details')
                                                ->insertGetId(['g_id'           => $acid, 
                                                                'u_id'          => $clid, 
                                                                'name'          => $data['name'], 
                                                                'address'       => $data['address'], 
                                                                'city'          => $dcity,
                                                                'email'         => $data['email'], 
                                                                'state'         => $data['state'], 
                                                                'website'       => $data['website'], 
                                                                'zip_code'      => $data['zip_code'], 
                                                                'upload_file'   => $filename,
                                                                'country'       => $data['country'], 
                                                                'phone'         => $data['phone'], 
                                                                'created_at'    => date('Y-m-d H:i:s'), 
                                                                'updated_at'    => date('Y-m-d H:i:s')]
                        );
                    } else {
                        return redirect($bladename)
                                ->with('failed_upfile', 'Please upload max file size of 2mb.')
                                ->withInput();
                        die;
                    }
                } else {
                    $grd_id = DB::table('account_details')
                                ->insertGetId(['g_id'           => $acid, 
                                                'u_id'          => $clid, 
                                                'name'          => $data['name'], 
                                                'address'       => $data['address'], 
                                                'city'          => $dcity,
                                                'email'         => $data['email'], 
                                                'state'         => $data['state'], 
                                                'website'       => $data['website'], 
                                                'zip_code'      => $data['zip_code'], 
                                                'country'       => $data['country'],
                                                'phone'         => $data['phone'], 
                                                'created_at'    => date('Y-m-d H:i:s'), 
                                                'updated_at'    => date('Y-m-d H:i:s')]
                    );
                }

                DB::table('user_accounts')
                    ->insert(['u_id' => $clid, 
                                'account_did' => $grd_id, 
                                'status' => 'open', 
                                'account_urole' => 'admin', 
                                'created_at' => date('Y-m-d H:i:s'), 
                                'updated_at' => date('Y-m-d H:i:s')]);

                return Redirect::to('account-user/' . $data['actype'])
                                ->with('saccid', $grd_id);
            }
        } else {
            return Redirect::to('/');
        }
    }

    public function getSetaccount() {
        
        $grd_id = Session::get('grd_id');
        if (!empty($grd_id)) {
            $get_ctyd   = DB::table('group_details')
                            ->where('id', '=', $grd_id)
                            ->select('country')->get();
            
            $event_data = DB::table('event_data')
                            ->distinct('event_category')
                            ->select('event_category')
                            ->get();

            foreach ($event_data as $obj_arr) {
                $et_dt[] = $obj_arr->event_category;
            }
            
            $evtsdata = array_unique($et_dt);

            return \View::make('public/default2/setup-account')
                        ->with('title', 'Set up Personal Account - DiscoverYourEvent')
                        ->with('evtsdata', $evtsdata)
                        ->with('grd_id', $grd_id)
                        ->with('get_ctyd', $get_ctyd);
        }
        else {
            return Redirect::to('/');
        }
    }

    public function postSetaccount(Request $request) {
        $data = $request->input();
        if (!empty($data['grd_id'])) {
            $validator = Validator::make($request->all(), [
            ]);
            if ($validator->fails()) {
                return redirect('set-up-account')->withErrors($validator)->withInput();
            } else {

                if (isset($data['enotification_status'])) {
                    $enf_status = 'y';
                    if (isset($data['interested_catagories'])) {
                        $c_ic = count($data['interested_catagories']);
                        if ($c_ic > 1) {
                            $int_cat = NULL;
                            for ($c = 0; $c < $c_ic; $c++) {
                                $int_cat .= $data['interested_catagories'][$c] . '~';
                            }
                            $int_cat = rtrim($int_cat, "~");
                        } else {
                            $int_cat = $data['interested_catagories'][0];
                        }
                    } else {
                        $int_cat = NULL;
                    }
                    if (isset($data['selected_event'])) {
                        $selected_event = $data['selected_event'];
                    } else {
                        $selected_event = NULL;
                    }
                } else {
                    $enf_status = 'n';
                    $int_cat = NULL;
                    $selected_event = NULL;
                }

                if (isset($data['email_nbusiness'])) {
                    $em_nb = 'y';
                } else {
                    $em_nb = 'n';
                }
                if (isset($data['email_nupdate'])) {
                    $em_up = 'y';
                } else {
                    $em_up = 'n';
                }
            
                $getCu = DB::table('group_details')
                            ->where('id', '=', $data['grd_id'])
                            ->select('id', 'u_id')
                            ->get();
            
                DB::table('group_details')
                    ->where('id', '=', $data['grd_id'])
                    ->update(['enotification_status'    => $enf_status, 
                                'interested_catagories' => $int_cat,
                                'selected_event'        => $selected_event, 
                                'email_nbusiness'       => $em_nb, 
                                'email_nupdate'         => $em_up, 
                                'updated_at'            => date('Y-m-d H:i:s')]);

                if (!empty($getCu[0]->u_id)) {
                    DB::table('mail_notifications')
                        ->where('user_id', '=', $getCu[0]->u_id)
                        ->update(['enotification_status' => $enf_status, 
                                    'yfollow_page' => $em_nb,
                                    'dye_notf' => $em_up, 
                                    'updated_at' => date('Y-m-d H:i:s')]);

                }

                $dbnuser = DB::table('users')
                                ->where('id', '=', $getCu[0]->u_id)
                                ->select('full_name', 'email')
                                ->get();

                //Send Email
                $eve            = url('logInSignUp');
                $SendToEmail    = $dbnuser[0]->email;
                $Subject        = "You are successfully sign up - Discover Your Event";
                $emdata         = array(
                                    'uname'     => $dbnuser[0]->full_name,
                                    'uemail'    => $dbnuser[0]->email,
                                    'loginurl'  => $eve
                                );

                Mail::send('email.signup_email', $emdata, function($message) use ($SendToEmail, $Subject) {
                    $message->to($SendToEmail)
                            ->cc('nick@discoveryourevent.com')
                            ->subject($Subject);
                });

                $credentials = array(
                    'login'     => Session::get('remail'),
                    'password'  => Session::get('rpassword')
                );
                if (Sentry::authenticate($credentials)) {
                    Session::forget('remail');
                    Session::forget('rpassword');
                    return Redirect::to('/');
                } else {
                    Session::forget('remail');
                    Session::forget('rpassword');
                    return Redirect::to('logInSignUp')->with('persignup', 'done-signup');
                }
            }
        } else {
            return Redirect::to('/');
        }
    }

    public function getAccountu($actype) {
        $cspid      = Session::get('saccid');
        if (!empty($cspid) && Sentry::check()) {
            $atype  = ucfirst($actype);
            $account_name   = DB::table('account_details')
                                ->where('id', '=', $cspid)
                                ->select('name', 'account_url')
                                ->get();

            $ac_murl        = $account_name[0]->account_url;
            return \View::make('public/default2/account_user')
                            ->with('title', 'Set up ' . $atype . ' Account User - DiscoverYourEvent')
                            ->with('accountyp', $actype)
                            ->with('acid', $cspid)
                            ->with('account_url', $ac_murl);
        } else {
            return Redirect::to('/');
        }
    }

    public function postAccountu($actype, Request $request) {
        $data = $request->input();
        if (!empty($actype)) {
            if ($actype == 'business') {
                $acid       = 2;
                $bladename  = 'business-form';
            } else if ($actype == 'municipality') {
                $acid       = 5;
                $bladename  = 'municipality-form';
            } else {
                $acid       = 6;
                $bladename  = 'club_organization';
            }
            $clid = Sentry::getUser()->id;
            $validator = Validator::make($request->all(), [
                                        'email'         => 'email',
                                        'account_url'   => 'unique:account_details',
                            ]);

            if ($validator->fails()) {
                return redirect('account-user/' . $bladename)
                        ->withErrors($validator)
                        ->withInput();

            } else {
                if (empty($data['account_url'])) {
                    $cu_number  = mt_rand(1000000000, 9999999999);
                    $ac_murl    = $data['account'] . $cu_number . '_ref';
                    DB::table('account_details')
                        ->where('id', '=', $data['account'])
                        ->update(['account_url' => $ac_murl, 
                                    'updated_at' => date('Y-m-d H:i:s')]);

                } else {
                    DB::table('account_details')
                        ->where('id', '=', $data['account'])
                        ->update(['account_url' => $data['account_url'], 
                                    'updated_at' => date('Y-m-d H:i:s')]);

                    $ac_murl = $data['account_url'];
                }
                $atype = ucfirst($actype);
                if (isset($data['email_nupdate'])) {
                    $enupd = 'y';
                } else {
                    $enupd = 'n';
                }
                $url_rand = Str::random(12);
                $lformat_url = 'dye' . strtolower($url_rand);
                if (isset($data['evtmg_count']) && $data['evtmg_count'] != '0') {
                    $mail_request   = DB::table('mail_request')
                                        ->where('sender_uid', '=', $clid)
                                        ->where('account_did', '=', $data['account'])
                                        ->select('id', 'addition_accmail')
                                        ->orderBy('id', 'desc')
                                        ->take($data['evtmg_count'])
                                        ->get();

                    $account_name   = DB::table('account_details')
                                        ->where('id', '=', $data['account'])
                                        ->select('name')
                                        ->get();

                    $macc_name      = $account_name[0]->name;
                    //Send Email	
                    $Subject        = "Join the " . $macc_name . " Account - Discover Your Event";
                    $joinurl        = url('logInSignUp/' . $lformat_url);
                    $emdata         = array(
                                        'uname'     => Sentry::getUser()->full_name,
                                        'uemail'    => Sentry::getUser()->email,
                                        'jaccount'  => $macc_name,
                                        'jnurl'     => $joinurl
                                    );

                    foreach ($mail_request as $mrst) {
                        $SendToEmail = $mrst->addition_accmail;
                        Mail::send('email.account_email', $emdata, function($message) use ($SendToEmail, $Subject) {
                            $message->to($SendToEmail)
                                    ->subject($Subject);
                        });
                    }
                    $email_status = 'sent';
                } else {
                    $email_status = 'nsend';
                }

                DB::table('mail_request')
                    ->where('account_did', '=', $data['account'])
                    ->where('email_nupdate', '=', 'n')
                    ->update(['email_status' => $email_status, 
                                'sdurl_code' => $lformat_url, 
                                'email_nupdate' => $enupd, 
                                'created_at' => date('Y-m-d H:i:s'),
                                'updated_at' => date('Y-m-d H:i:s')]);

                return Redirect::to($ac_murl . '/account')
                                ->with('succ_mesg', 'Account created successfully!');
            }
        } else {
            return Redirect::to('/');
        }
    }

    public function postAddeventuser(Request $request) {
        $data = $request->input();
        if (Sentry::check() && !empty($data['user_type'])) {
            $clid       = Sentry::getUser()->id;
            $validator  = Validator::make($request->all(), [
                            'email' => 'email'
                        ]);

            if ($validator->fails()) {
                return 'error';
            } else {
                if (!empty($data['account'])) {
                    $account_name = DB::table('account_details')
                                        ->where('id', '=', $data['account'])
                                        ->select('id', 'name')
                                        ->get();

                    if (!empty($data['email']) && !empty($account_name[0]->id)) {
                        $check_exist = DB::table('mail_request')
                                            ->where('sender_uid', '=', $clid)
                                            ->where('account_did', '=', $data['account'])
                                            ->where('addition_accmail', '=', $data['email'])
                                            ->select('id')
                                            ->get();

                        if (empty($check_exist[0]->id) || !isset($check_exist[0]->id)) {
                            $getmr_id = DB::table('mail_request')
                                            ->insertGetId(['sender_uid'         => $clid, 
                                                            'account_did'       => $data['account'], 
                                                            'addition_accmail'  => $data['email'],
                                                            'user_type'         => 'event', 
                                                            'status'            => 'nresponed', 
                                                            'created_at'        => date('Y-m-d H:i:s'), 
                                                            'updated_at'        => date('Y-m-d H:i:s')]);

                            return array('success' => 'success', 
                                            'aid' => $getmr_id, 
                                            'email' => $data['email']);

                        } else {
                            return 'exist_mail';
                        }
                    } else {
                        return 'error';
                    }
                } else {
                    return Redirect::to('/');
                }
            }
        } else {
            return Redirect::to('/');
        }
    }

    public function removeEventuser($id) {
        if (!empty($id) && Sentry::check()) {
            $uid = Sentry::getUser()->id;
            DB::delete('delete from mail_request WHERE id = ' . $id . ' && sender_uid = ' . $uid);
            return 'success';
        }
    }

    public function postSendrequest(Request $request) {
        $data = $request->input();
        if (Sentry::check() && !empty($data['acid'])) {
            $account_name   = DB::table('account_details')
                                    ->where('id', '=', $data['acid'])
                                    ->select('name')
                                    ->get();

            $mrq_dataget    = DB::table('mail_request')
                                    ->where('account_did', '=', $data['acid'])
                                    ->where('email_status', '=', 'nsend')
                                    ->select('id', 'user_type', 'addition_accmail', 'sdurl_code')
                                    ->get();

            if (!empty($mrq_dataget[0]->id)) {
                $macc_name = $account_name[0]->name;
                foreach ($mrq_dataget as $mr_all) {
                    DB::table('mail_request')
                        ->where('id', '=', $mr_all->id)
                        ->update(['email_status' => 'sent', 'updated_at' => date('Y-m-d H:i:s')]);

                    //Send Email
                    $SendToEmail    = $mr_all->addition_accmail;
                    $Subject        = "Join the " . $macc_name . " Account - Discover Your Event";
                    $joinurl        = url('logInSignUp/' . $mr_all->sdurl_code); 
                    $emdata = array(
                                    'uname'     => Sentry::getUser()->full_name,
                                    'uemail'    => Sentry::getUser()->email,
                                    'jaccount'  => $macc_name,
                                    'jnurl'     => $joinurl,
                                );

                    Mail::send('email.account_email', $emdata, function($message) use ($SendToEmail, $Subject) {
                        $message->to($SendToEmail)->subject($Subject);
                    });
                }
                return 'success';
            } else {
                return 'error';
            }
        } else {
            return Redirect::to('/');
        }
    }

    public function getCreatedacc($aname) {
        if (!empty($aname) && Sentry::check()) {
            $ids = Sentry::getUser()->id;
            $accountdetail  = DB::table('user_accounts')
                                    ->where('user_accounts.u_id', '=', $ids)
                                    ->Where('user_accounts.status', '=', 'open')
                                    ->orWhere('user_accounts.status', '=', 'transfer')
                                    ->join('account_details', 'user_accounts.account_did', '=', 'account_details.id')
                                    ->select('account_details.id', 
                                                'account_details.name', 
                                                'account_details.account_url', 
                                                'user_accounts.status', 
                                                'user_accounts.account_urole')
                                    ->get();

            if (sizeof($accountdetail) > 0) {
                foreach ($accountdetail as $acdls) {
                    $ac_murl = $acdls->account_url;
                    if ($aname == $ac_murl) {
                        Session::put('selaccount', $aname);
                        $acd_name       = $acdls->name;
                        $account_status = $acdls->status;
                        $auser_role     = $acdls->account_urole;
                        $accdetails     = DB::table('account_details')
                                                ->where('id', '=', $acdls->id)
                                                ->get();

                    }
                }
                if (!empty($accdetails[0]->u_id)) {
                    $get_admindetail = DB::table('users')
                                            ->where('id', '=', $accdetails[0]->u_id)
                                            ->select('full_name', 'email')
                                            ->get();

                    $coun_data = DB::table('countries')
                                    ->select('id', 'name')
                                    ->get();

                    return \View::make('public/default2/account/accindex')
                                    ->with('title', $acd_name . ' Account - DiscoverYourEvent')
                                    ->with('event_details', $accdetails)
                                    ->with('acc_status', $account_status)
                                    ->with('auser_role', $auser_role)
                                    ->with('gadmin_detail', $get_admindetail)
                                    ->with('cou_data', $coun_data)
                                    ->with('active', 'acc');

                } else {
                    return Redirect::to('/');
                }
            } else {
                return Redirect::to('/');
            }
        } else {
            return Redirect::to('/');
        }
    }

    public function getDyesearch() {
        $Searchevnt = new Searchevnt();
        $evtsdata = $Searchevnt->getAllCategories();

        $al_event = DB::table('events')
                ->where('events.private_event', '!=', 'y')
                ->where('events.user_evdelete', '=', 'n')
                ->select('events.id', 
                            'events.event_name', 
                            'events.account_type', 
                            'events.event_url', 
                            'events.event_image', 
                            'events.event_venue', 
                            'events.event_address', 
                            'events.address_secd', 
                            'events.city', 
                            'events.state', 
                            'events.country', 
                            'events.event_dtype', 
                            'events.event_date', 
                            'events.event_time', 
                            'events.event_cost', 
                            'events.event_price')
                ->orderBy('id', 'desc')
                ->paginate(5);

        /***********************left-map***************************/
        if (!empty($al_event[0]->id)) {
            $x = 1;
            foreach ($al_event as $uce) {
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
                if (!empty($uce->state) && !empty($uce->country)) {

                    $getsssdata = DB::table('states')
                                    ->where('country_id', '=', $uce->country)
                                    ->where('name', '=', $uce->state)
                                    ->select('id', 'name')->get();

                    if (!empty($getsssdata[0]->id)) {
                        $staten = $getsssdata[0]->name;
                    } else {
                        $staten = $uce->state;
                    }

                    if (!empty($staten)) {
                        if (!empty($lfal_add)) {
                            $lfal_add .= ',' . $staten;
                        } else {
                            $lfal_add = $staten;
                        }
                    }
                }

                if (!empty($uce->country)) {

                    $getcccdata = DB::table('countries')
                                        ->where('id', '=', $uce->country)
                                        ->select('id', 'name')
                                        ->get();

                    if (!empty($lfal_add)) {
                        $lfal_add .= ',' . $getcccdata[0]->name;
                    } else {
                        $lfal_add = $getcccdata[0]->name;
                    }
                }
                $addressArray[]     = $lfal_add;
                $orgTitleArray[]    = $uce->event_name;
                $org_surlArray[]    = $uce->event_url;
                $x++;
            }
        } else {
            $addressArray = null;
            $orgTitleArray = null;
            $org_surlArray = null;
        }

        return \View::make('public/default2/dye_search')
                        ->with('title', 'Search - DiscoverYourEvent')
                        ->with('evtsdata', $evtsdata)
                        ->with('al_event', $al_event)
                        ->with('addressArray', $addressArray)
                        ->with('orgTitleArray', $orgTitleArray)
                        ->with('org_surlArray', $org_surlArray);
    }

    public function dyeSearch(Request $request) {
        $Searchevnt = new Searchevnt();
        $evtsdata = $Searchevnt->getAllCategories();

        $data = $request->input();

        $Searchevnt = new Searchevnt();
        $refine_events = $Searchevnt->searchEvents($data, "limit");
        $rs_events = $Searchevnt->searchEvents($data);

        /*         * **********************left-map************************** */

        if (isset($rs_events[0]) && !empty($rs_events[0]->id)) {

            $x = 1;

            foreach ($rs_events as $uce) {
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
                if (!empty($uce->state) && !empty($uce->country)) {

                    $getsssdata = DB::table('states')
                                    ->where('country_id', '=', $uce->country)
                                    ->where('name', '=', $uce->state)
                                    ->select('id', 'name')->get();

                    if (!empty($getsssdata[0]->id)) {
                        $staten = $getsssdata[0]->name;
                    } else {
                        $staten = $uce->state;
                    }

                    if (!empty($staten)) {
                        if (!empty($lfal_add)) {
                            $lfal_add .= ',' . $staten;
                        } else {
                            $lfal_add = $staten;
                        }
                    }
                }

                if (!empty($uce->country)) {
                    $getcccdata = DB::table('countries')
                            ->where('id', '=', $uce->country)
                            ->select('id', 'name')
                            ->get();

                    if (!empty($lfal_add)) {
                        $lfal_add .= ',' . $getcccdata[0]->name;
                    } else {
                        $lfal_add = $getcccdata[0]->name;
                    }
                }
                $addressArray[] = $lfal_add;
                $orgTitleArray[] = $uce->event_name;
                $org_surlArray[] = $uce->event_url;
                $x++;
            }
        } else {
            $addressArray = null;
            $orgTitleArray = null;
            $org_surlArray = null;
        }



        /*         * *********************end-lmap**************************** */

        return \View::make('public/default2/dye_search')
                        ->with('title', 'Search - DiscoverYourEvent')
                        ->with('search_data', $data)
                        ->with('addressArray', $addressArray)
                        ->with('orgTitleArray', $orgTitleArray)
                        ->with('org_surlArray', $org_surlArray)
                        ->with('al_event', $refine_events)
                        ->with('evtsdata', $evtsdata);
    }

    public function SearchEvent(Request $request) {
        $data = $request->input();
        if (!empty($data['city'])) {
            $rl_op = '=';
            $ev_rl = $data['city'];
            $chk_c = DB::table('events')
                        ->where('user_evdelete', '=', 'n')
                        ->where('city', $rl_op, $ev_rl)
                        ->select('id')
                        ->get();

            if (!empty($chk_c[0]->id)) {
                $cy_poid = 'city';
            } else {
                $chk_pid = DB::table('events')
                                ->where('user_evdelete', '=', 'n')
                                ->where('zip_code', $rl_op, $ev_rl)
                                ->select('id')
                                ->get();

                if (!empty($chk_pid[0]->id)) {
                    $cy_poid = 'zip_code';
                } else {
                    $cy_poid = 'city';
                }
            }
        } else {
            $cy_poid = 'city';
            $rl_op = '!=';
            $ev_rl = 'zxp';
        }
        if (!empty($data['eventday'])) {
            if ($data['eventday'] == 'today') {
                $evdate_op  = '=';
                $event_date = date("Y-m-d");
            } else if ($data['eventday'] == 'tomorrow') {
                $evdate_op  = '=';
                $datetime   = new DateTime('tomorrow');
                $event_date = $datetime->format('Y-m-d');
            } else if ($data['eventday'] == 'week') {
                $evdate_grop    = '>=';
                $cdate          = date("Y-m-d");
                $evdate_lsop    = '<=';
                $event_date     = date('Y-m-d', strtotime('next Sunday'));
            } else if ($data['eventday'] == 'weekend') {
                $cdate      = date("Y-m-d");
                $evdate_op  = '=';
                if (date('D') != 'Sun') {
                    $event_date = date('Y-m-d', strtotime('next sunday'));
                } else {
                    $event_date = date('Y-m-d');
                }
            } else {
                $evdate_grop    = '>=';
                $cdate          = date("Y-m-d");
                $evdate_lsop    = '<=';
                $event_date     = date("Y-m-t", strtotime($cdate));
            }
        } else {
            $evdate_op = '!=';
            $event_date = ' ';
        }

        $event_data = DB::table('event_data')
                            ->distinct('event_data.event_category')
                            ->select('event_data.event_category')
                            ->get();

        foreach ($event_data as $obj_arr) {
            $et_dt[] = $obj_arr->event_category;
        }

        $evtsdata = array_unique($et_dt);
        if (!empty($data['category']) && $data['category'] != 'Category') {
            $ent_op = '=';
            $ev_cat = $data['category'];
        } else {
            $ent_op = '!=';
            $ev_cat = ' ';
        }
        if (!empty($data['eventday'])) {
            if ($data['eventday'] == 'week' || $data['eventday'] == 'month') {
                $refine_events = DB::table('events')
                                        ->where('events.user_evdelete', '=', 'n')
                                        ->where('events.' . $cy_poid, $rl_op, $ev_rl)
                                        ->where('event_data.event_category', $ent_op, $ev_cat)
                                        ->where('events.private_event', '!=', 'y')
                                        ->where('events.event_date', $evdate_grop, $cdate)
                                        ->where('events.event_date', $evdate_lsop, $event_date)
                                        ->join('event_data', 'events.event_catid', '=', 'event_data.id')
                                        ->select('events.id', 
                                                    'events.event_name', 
                                                    'events.account_type', 
                                                    'events.event_url', 
                                                    'events.event_image', 
                                                    'events.event_venue', 
                                                    'events.event_address', 
                                                    'events.address_secd', 
                                                    'events.city', 
                                                    'events.state', 
                                                    'events.country', 
                                                    'events.event_dtype', 
                                                    'events.event_date', 
                                                    'events.event_time', 
                                                    'events.event_cost', 
                                                    'events.event_price')
                                        ->orderBy('id', 'desc')
                                        ->paginate(5);

            } else {
                $refine_events = DB::table('events')
                                    ->where('events.user_evdelete', '=', 'n')
                                    ->where('events.' . $cy_poid, $rl_op, $ev_rl)
                                    ->where('event_data.event_category', $ent_op, $ev_cat)
                                    ->where('events.private_event', '!=', 'y')
                                    ->where('events.event_date', $evdate_op, $event_date)
                                    ->join('event_data', 'events.event_catid', '=', 'event_data.id')
                                    ->select('events.id', 
                                                'events.event_name', 
                                                'events.account_type', 
                                                'events.event_url', 
                                                'events.event_image', 
                                                'events.event_venue', 
                                                'events.event_address', 
                                                'events.address_secd', 
                                                'events.city', 
                                                'events.state', 
                                                'events.country', 
                                                'events.event_dtype', 
                                                'events.event_date', 
                                                'events.event_time', 
                                                'events.event_cost', 
                                                'events.event_price')
                                    ->orderBy('id', 'desc')
                                    ->paginate(5);

            }
        } else {
            $refine_events = DB::table('events')
                                ->where('events.user_evdelete', '=', 'n')
                                ->where('events.' . $cy_poid, $rl_op, $ev_rl)
                                ->where('event_data.event_category', $ent_op, $ev_cat)
                                ->where('events.private_event', '!=', 'y')
                                ->join('event_data', 'events.event_catid', '=', 'event_data.id')
                                ->select('events.id', 
                                            'events.event_name', 
                                            'events.account_type', 
                                            'events.event_url', 
                                            'events.event_image', 
                                            'events.event_venue', 
                                            'events.event_address', 
                                            'events.address_secd', 
                                            'events.city', 
                                            'events.state', 
                                            'events.country', 
                                            'events.event_dtype', 
                                            'events.event_date', 
                                            'events.event_time', 
                                            'events.event_cost', 
                                            'events.event_price')
                                ->orderBy('id', 'desc')
                                ->paginate(5);
        }
        return \View::make('public/default2/ajex/search')
                        ->with('al_event', $refine_events)
                        ->with('evtsdata', $evtsdata);
    }

    public function getAccountpage($aname) {
        //if(Sentry::check()){	
        $accountdetail = DB::table('user_accounts')->where('user_accounts.status', '=', 'open')
                        ->orWhere('user_accounts.status', '=', 'transfer')->join('account_details', 'user_accounts.account_did', '=', 'account_details.id')
                        ->select('account_details.id', 'account_details.g_id', 'account_details.name', 'account_details.account_url', 'account_details.website', 'user_accounts.account_urole')->get();
        if (sizeof($accountdetail) > 0) {
            foreach ($accountdetail as $acdls) {
                $ac_murl = $acdls->account_url;
                if ($aname == $ac_murl) {
                    if (Sentry::check()) {
                        $id = Sentry::getUser()->id;
                        $chkme = DB::table('account_details')->where('u_id', '=', $id)->where('account_url', '=', $ac_murl)->select('id')->get();
                        if (!empty($chkme[0]->id)) {
                            Session::put('selaccount', $aname);
                        }
                    }
                    $acd_gid = $acdls->g_id;
                    $acd_website = $acdls->website;
                    $acd_name = $acdls->name;
                    $accdetails = DB::table('account_details')->where('id', '=', $acdls->id)->get();
                }
            }
            if (Sentry::check() && isset($accdetails[0]->id)) {
                $id = Sentry::getUser()->id;
                $followu = DB::table('user_follows')->where('follow_id', '=', $accdetails[0]->id)->where('u_id', '=', $id)->where('follow_type', '=', 'account')->select('id', 'follow')->get();
            } else {
                $followu = null;
            }
            if (!empty($acd_gid) && isset($accdetails[0]->id)) {
                $acc_event = DB::table('events')->where('user_evdelete', '=', 'n')->where('account_id', '=', $accdetails[0]->id)->where('private_event', '!=', 'y')
                                ->select('id', 'account_type', 'event_name', 'event_url', 'event_image', 'event_dtype', 'event_date', 'event_time', 'event_cost', 'event_price', 'event_venue', 'event_address')->get();
                if ($acd_gid == 2) {
                    $cimg_apath = 'business';
                } elseif ($acd_gid == 5) {
                    $cimg_apath = 'municipality';
                } else {
                    $cimg_apath = 'club';
                }
                $event_detailsstr = $acd_website;
                $event_detailsstr = preg_replace('#^https?://#', '', $event_detailsstr);
                /*                 * **********map-data********** */
                $lfalc_add = null;
                $baddress = null;
                $bcity = null;
                $bstates = null;
                $bcountry = null;
                if (!empty($accdetails[0]->address)) {
                    $lfalc_add = $accdetails[0]->address;
                    $baddress = $accdetails[0]->address;
                }
                if (!empty($accdetails[0]->city) && !empty($accdetails[0]->state)) {
                    $getcidata = DB::table('cities')->where('id', '=', $accdetails[0]->city)->where('state_id', '=', $accdetails[0]->state)->select('id', 'name')->get();
                    if (isset($getcidata[0]->id)) {
                        if (!empty($lfalc_add)) {
                            $lfalc_add .= ',' . $getcidata[0]->name;
                        } else {
                            $lfalc_add = $getcidata[0]->name;
                        }
                        $bcity = $getcidata[0]->name;
                    }
                }
                if (!empty($accdetails[0]->state) && !empty($accdetails[0]->country)) {
                    $getssdata = DB::table('states')->where('country_id', '=', $accdetails[0]->country)->where('name', '=', $accdetails[0]->state)->select('id', 'name')->get();
                    if (!empty($getssdata[0]->id)) {
                        if (!empty($lfalc_add)) {
                            $lfalc_add .= ',' . $getssdata[0]->name;
                        } else {
                            $lfalc_add = $getssdata[0]->name;
                        }
                        $bstates = $getssdata[0]->name;
                    }
                }
                if (!empty($accdetails[0]->country)) {
                    $getccdata = DB::table('countries')->where('id', '=', $accdetails[0]->country)->select('id', 'name')->get();
                    if (!empty($lfalc_add)) {
                        $lfalc_add .= ', ' . $getccdata[0]->name;
                    } else {
                        $lfalc_add = $getccdata[0]->name;
                    }
                    $bcountry = $getccdata[0]->name;
                }
                $mapddress = $lfalc_add;
                /*                 * ******************end-mmap************* */
                return \View::make('public/default2/event-select')->with('title', $acd_name . ' - DiscoverYourEvent')->with('maddArr', $mapddress)
                                ->with('acc_event', $acc_event)->with('event_details', $accdetails)->with('cimg_apath', $cimg_apath)
                                ->with('baddress', $baddress)->with('bcity', $bcity)->with('bstates', $bstates)->with('bcountry', $bcountry)->with('ev_dtstr', $event_detailsstr)
                                ->with('ufollow', $followu);
            } else {
                return \View::make('public/default2/404')->with('title', '404 Page Not Found - DiscoverYourEvent');
            }
        } else {
            return Redirect::to('/');
        }

        /* } else {
          return Redirect::to('/');
          } */
    }

    public function getAccount() {
        if (Sentry::check()) {
            $c_aname = Session::get('selaccount');
            if (empty($c_aname)) {
                $id = Sentry::getUser()->id;
                $u_pdata = DB::table('users')->where('users.id', '=', $id)->join('group_details', 'users.id', '=', 'group_details.u_id')->get();
                $cuntd = DB::table('countries')->select('id', 'name')->where('id', '=', 38)->orWhere('id', '=', 231)->get();
                return \View::make('public/default2/account/index')->with('title', 'Account - DiscoverYourEvent')->with('cuntd', $cuntd)->with('user_data', $u_pdata)->with('active', 'acc');
            } else {
                return Redirect::to($c_aname . '/account');
            }
        } else {
            return Redirect::to('/');
        }
    }

    public function getNotifications() {
        if (Sentry::check()) {
            $id = Sentry::getUser()->id;
            $getmn_data = DB::table('mail_notifications')->where('user_id', '=', $id)->get();
            return \View::make('public/default2/account/email-notifications')->with('title', 'Email Notifications - DiscoverYourEvent')->with('active', 'nf')->with('getmn_data', $getmn_data);
        } else {
            return Redirect::to('/');
        }
    }

    public function postNotifications(Request $request) {
        if (Sentry::check()) {
            $uid = Sentry::getUser()->id;
            $data = $request->input();
            $validator = Validator::make($request->all(), []);
            if ($validator->fails()) {
                return Redirect::back()->withErrors($validator)->withInput();
            } else {
                if (isset($data['enotification_status'])) {
                    $enotification_status = 'y';
                } else {
                    $enotification_status = 'n';
                }
                if (isset($data['event_attend'])) {
                    $event_attend = 'y';
                } else {
                    $event_attend = 'n';
                }
                if (isset($data['dye_notf'])) {
                    $dye_notf = 'y';
                } else {
                    $dye_notf = 'n';
                }
                if (isset($data['yfollow_page'])) {
                    $yfollow_page = 'y';
                } else {
                    $yfollow_page = 'n';
                }
                DB::table('mail_notifications')->where('user_id', '=', $uid)->update(['enotification_status' => $enotification_status,
                    'event_attend' => $event_attend, 'yfollow_page' => $yfollow_page, 'dye_notf' => $dye_notf, 'updated_at' => date('Y-m-d H:i:s')]);

                $getmn_data = DB::table('mail_notifications')->where('user_id', '=', $uid)->get();
                return \View::make('public/default2/account/email-notifications')->with('title', 'Email Notifications - DiscoverYourEvent')->with('active', 'nf')->with('getmn_data', $getmn_data)->with('succ_mesg', 'Successfully updated');
            }
        } else {
            return Redirect::to('/');
        }
    }

    public function getFollowing() {
        if (Sentry::check()) {
            $clid = Sentry::getUser()->id;
            $yr_follow = DB::table('user_follows')->where('user_follows.u_id', '=', $clid)->where('user_follows.follow_type', '=', 'account')->where('user_follows.follow', '=', 'y')
                    ->join('account_details', 'user_follows.follow_id', '=', 'account_details.id')
                    ->select('user_follows.id', 'user_follows.follow_id', 'account_details.g_id', 'account_details.name', 'account_details.account_url', 'account_details.upload_file', 'account_details.email', 'account_details.website')
                    ->paginate(10);
            if (empty($yr_follow[0]->id)) {
                $unfep = DB::table('account_details')->where('account_details.u_id', '!=', $clid)->select('id', 'name', 'account_url')->get();
            } else {
                $unfep = 'gc';
            }
            return \View::make('public/default2/account/following-event')->with('title', 'Following - DiscoverYourEvent')->with('follow_acc', $yr_follow)->with('active', 'fl')->with('unfolow', $unfep);
        } else {
            return Redirect::to('/');
        }
    }

    public function getManageuser() {
        if (Sentry::check()) {
            return \View::make('public/default2/account/manage-users')->with('title', 'Manage Users - DiscoverYourEvent')->with('active', 'mu');
        } else {
            return Redirect::to('/');
        }
    }

    public function getAccnotifications($aname) {
        if (Sentry::check() && !empty($aname)) {
            $id = Sentry::getUser()->id;
            $accountdetail = DB::table('user_accounts')->where('user_accounts.u_id', '=', $id)->Where('user_accounts.status', '=', 'open')
                            ->orWhere('user_accounts.status', '=', 'transfer')->join('account_details', 'user_accounts.account_did', '=', 'account_details.id')
                            ->select('account_details.id', 'account_details.account_url', 'user_accounts.status', 'user_accounts.account_urole')->get();
            if (sizeof($accountdetail) > 0) {
                foreach ($accountdetail as $acdls) {
                    $ac_murl = $acdls->account_url;
                    if ($aname == $ac_murl) {
                        $account_status = $acdls->status;
                        $auser_role = $acdls->account_urole;
                        $accdetails = DB::table('account_details')->where('id', '=', $acdls->id)->get();
                    }
                }
                return \View::make('public/default2/account/accemail-notifications')->with('title', 'Email Notifications - DiscoverYourEvent')->with('event_details', $accdetails)->with('acc_status', $account_status)->with('auser_role', $auser_role)->with('active', 'nf');
            } else {
                return Redirect::to('/');
            }
        } else {
            return Redirect::to('/');
        }
    }

    public function postAccnotifications($aname, Request $request) {
        if (Sentry::check() && !empty($aname)) {
            $id = Sentry::getUser()->id;
            $data = $request->input();
            $validator = Validator::make($request->all(), []);
            if ($validator->fails()) {
                return Redirect::back()->withErrors($validator)->withInput();
            } else {
                $accountdetail = DB::table('user_accounts')->where('user_accounts.u_id', '=', $id)->Where('user_accounts.status', '=', 'open')
                                ->orWhere('user_accounts.status', '=', 'transfer')->join('account_details', 'user_accounts.account_did', '=', 'account_details.id')
                                ->select('account_details.id', 'account_details.account_url', 'user_accounts.status', 'user_accounts.account_urole')->get();
                if (sizeof($accountdetail) > 0) {
                    if (isset($data['flemail_update'])) {
                        $flemail_update = 'y';
                    } else {
                        $flemail_update = 'n';
                    }
                    foreach ($accountdetail as $acdls) {
                        $ac_murl = $acdls->account_url;
                        if ($aname == $ac_murl) {
                            $account_status = $acdls->status;
                            $auser_role = $acdls->account_urole;
                            DB::table('account_details')->where('id', '=', $acdls->id)->update(['flemail_update' => $flemail_update, 'updated_at' => date('Y-m-d H:i:s')]);
                            $accdetails = DB::table('account_details')->where('id', '=', $acdls->id)->get();
                        }
                    }
                    return \View::make('public/default2/account/accemail-notifications')->with('title', 'Email Notifications - DiscoverYourEvent')->with('event_details', $accdetails)->with('acc_status', $account_status)->with('auser_role', $auser_role)->with('active', 'nf')->with('succ_mesg', 'Successfully updated');
                } else {
                    return Redirect::to('/');
                }
            }
        } else {
            return Redirect::to('/');
        }
    }

    public function getAccmuser($aname) {
        if (Sentry::check() && !empty($aname)) {
            $id = Sentry::getUser()->id;
            $accountdetail = DB::table('user_accounts')->where('user_accounts.u_id', '=', $id)->Where('user_accounts.status', '=', 'open')
                            ->orWhere('user_accounts.status', '=', 'transfer')->join('account_details', 'user_accounts.account_did', '=', 'account_details.id')
                            ->select('account_details.id', 'account_details.account_url', 'user_accounts.status', 'user_accounts.account_urole')->get();
            if (sizeof($accountdetail) > 0) {
                foreach ($accountdetail as $acdls) {
                    $ac_murl = $acdls->account_url;
                    if ($aname == $ac_murl) {
                        $account_status = $acdls->status;
                        $auser_role = $acdls->account_urole;
                        $accdetails = DB::table('account_details')->where('id', '=', $acdls->id)->get();
                    }
                }
                return \View::make('public/default2/account/accmanage-users')->with('title', 'Manage Users - DiscoverYourEvent')->with('event_details', $accdetails)->with('acc_status', $account_status)->with('auser_role', $auser_role)->with('active', 'mu');
            } else {
                return Redirect::to('/');
            }
        } else {
            return Redirect::to('/');
        }
    }

    public function closeAccount($aname, Request $request) {
		
            $AccountDetails = new AccountDetails;
            $UserAccounts = new UserAccounts;
            $UsersFollow = new UsersFollow;
			
			
        if (!empty($aname) && Sentry::check()) {
            
            $id = Sentry::getUser()->id;
            $data = $request->input();
            $acc_did = $data['acc_did'];
            
                $AccountDetails->id = $acc_did;
                $checkAccount = $AccountDetails->getAccountById();
                
                
            if (sizeof($checkAccount) > 0) {
                
                $Subject = $checkAccount[0]->name . " account closed by  - Discover Your Event"; //Send Email

                $SendToEmail = Sentry::getUser()->email; // $data['email'];

                $emdata = array(
                    'uname' => Sentry::getUser()->full_name,
                    'uemail' => $SendToEmail,
                    'caccount_name' => $checkAccount[0]->name,
                    'redirect_url' => URL('logInSignUp')
                );
                
                Mail::send(
                        'email.account_close', 
                        $emdata, 
                        function($message) use ($SendToEmail, $Subject) {
				$admin_email = config('app.email' , 'info@discoveryourevent.com'); 
                                $message->to($SendToEmail)->cc( $admin_email )->subject($Subject);        
                        });

                        $UserAccounts->account_did = $checkAccount[0]->id;
                        $accountStatus = $UserAccounts->closeAccount();
                        
                        $UsersFollow->follow_id = $checkAccount[0]->id;
                        $UsersFollow->u_id = $UserAccounts->account_did;
                        
                        
                        
                            $UsersFollow->where('follow_id', $checkAccount[0]->id)
                                ->where( 'u_id', $id )
                                ->where( "follow_type" ,"account" )
                                ->delete();
                        
                        //DB::delete('delete from user_follows WHERE follow_id = ' . $checkAccount[0]->id. . ' && u_id = ' . $id . ' && follow_type = account');
                        
                    return Redirect::to('/');
            } 
            else {
                return Redirect::back()->with('error_mesg', 'Something wrong! please try again');
            }
        } else {
            return Redirect::to('/');
        }
    }

    public function transferAccount($aname, Request $request) {
        if (!empty($aname) && Sentry::check()) {
            $id = Sentry::getUser()->id;
            $data = $request->input();
            $checkAccount = DB::table('account_details')->where('account_details.id', '=', $data['acc_did'])->where('user_accounts.u_id', '=', $id)
                            ->join('user_accounts', 'account_details.id', '=', 'user_accounts.account_did')
                            ->select('account_details.id', 'account_details.name')->get();
            if (sizeof($checkAccount) > 0 && !empty($data['email'])) {
                if (isset($data['future-access']) && !empty($data['future-access'])) {
                    $url_rand = Str::random(12);
                    $lformat_url = 'dye' . strtolower($url_rand);
                    DB::table('mail_request')->insert(['sender_uid' => $id, 'account_did' => $checkAccount[0]->id, 'addition_accmail' => $data['email'],
                        'user_type' => 'admin', 'status' => 'nresponed', 'email_status' => 'sent', 'sdurl_code' => $lformat_url, 'email_nupdate' => 'n', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')]);
                    $accept_url = URL('logInSignUp') . '/transfer/' . $lformat_url;
                    
                    if ($data['future-access'] == 'y') {
                        DB::table('user_accounts')->where('u_id', '=', $id)->where('account_did', '=', $checkAccount[0]->id)->update(['status' => 'transfer', 'account_urole' => 'event', 'updated_at' => date('Y-m-d H:i:s')]);
                        return Redirect::to($aname . '/account')->with('succ_mesg', 'Your user role changed and Request sent successfully');
                    } else {
                        DB::table('user_accounts')->where('u_id', '=', $id)->where('account_did', '=', $checkAccount[0]->id)->update(['status' => 'close', 'updated_at' => date('Y-m-d H:i:s')]);
                        return Redirect::to('/')->with('succ_mesg', 'Request sent successfully');
                    }
                    //return Redirect::back();
                } else {
                    return Redirect::back()->with('error_mesg', 'Change user role for Account or close the account access yes/no option required');
                }
            } else {
                return Redirect::back()->with('error_mesg', 'Something wrong! please try again');
            }
        } else {
            return Redirect::to('/');
        }
    }

    public function postContact(Request $request) {
        $data = $request->input();
        $validator = Validator::make($request->all(), [
                    'name'      => 'required',
                    'email'     => 'required|email',
                    'phone'     => 'required|numeric|min:10|min:12',
                    'subject'   => 'required',
                    'message'   => 'required'
        ]);

        if ($validator->fails()) {
            return redirect('contact')->withErrors($validator)->withInput();
        } else {
            $Subject = "Contact form  - Discover Your Event";
            //Send Email
            $SendToEmail = $data['email'];
            $emdata = array(
                'uname'         => $data['name'],
                'uemail'        => $SendToEmail,
                'uphone'        => $data['phone'],
                'usubject'      => $data['subject'],
                'umessage'      => $data['message'],
                'redirect_url'  => URL('/')
            );
            Mail::send('email.contact_form', $emdata, function($message) use ($SendToEmail, $Subject) {
                $message->to('info@discoveryourevent.com')->subject($Subject);
            });
            
        }
        return Redirect('contact')->with('success_message', 'Thank you for your message.');
    }

    public function getAnotification() {
        if (Sentry::check()) {
            $clid = Sentry::getUser()->id;
            $allNotf = DB::table('notifications')->where('onr_id', '=', $clid)->orderBy('id', 'desc')->paginate(10);
            return \View::make('public/default2/account/all-notifications')->with('title', 'Your Notifications - DiscoverYourEvent')->with('all_notf', $allNotf);
        } else {
            return Redirect::to('/');
        }
    }

    public function getajexNotf() {
        if (Sentry::check()) {
            $clid = Sentry::getUser()->id;
            $allNotf = DB::table('notifications')->where('onr_id', '=', $clid)->orderBy('id', 'desc')->paginate(10);
            return \View::make('public/default2/ajex/all-notifications')->with('all_notf', $allNotf);
        } else {
            return Redirect::to('/');
        }
    }

}
