<?php 
use Services\UserManager;
use Services\UserGroupManager;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;
//use DateTime;

class CustomAdminController extends Controller {	
    protected $user_manager;
    protected $usergroup_manager;
     public function __construct(UserManager $user_manager, UserGroupManager $usergroup_manager)
    {
        $this->user_manager = $user_manager;
        $this->usergroup_manager = $usergroup_manager;
    }
   public function eventList()
  {
    $all_event = DB::table('events')->join('users', 'events.u_id', '=', 'users.id')->select('events.id','events.event_name','users.full_name',
	'events.account_type','events.event_image','events.event_date','events.event_cost','events.private_event','events.event_url','events.event_block')->orderBy('events.id', 'desc')->get();
   return \View::make('backend/default/events/show')->with('all_event', $all_event)->with('title','Event List - DiscoverYourEvent')->with('active','event');
  }
    public function eventBlock($evid, $bl)
  { 
	if(!empty($evid) && !empty($bl)){	
	  $gevuid = DB::table('events')->where('events.id', '=', $evid)->join('users', 'events.u_id', '=', 'users.id')
	  ->select('events.u_id','events.event_name','users.id','users.full_name','users.email')->get();
	   DB::table('events')->where('id', '=', $evid)->update(['event_block' => $bl,'updated_at' => date('Y-m-d H:i:s')]);
	   
	   	$SendToEmail = $gevuid[0]->email;
		if($bl == 'y'){
		 $event_bstatus = 'Blocked';	
		} else {
		 $event_bstatus = 'UnBlocked';		
		}
			$Subject = "Admin ".$event_bstatus." Your ".$gevuid[0]->event_name."event - Discover Your Event";
		
              $eve = url('logInSignUp');	
              $evname = ucfirst($gevuid[0]->event_name);
			  $frdmsg = 'Admin '.$event_bstatus.' your event';
			  $euname = $gevuid[0]->full_name;
			$emdata = array(
			      'fwmssg' => $frdmsg,
				  'evname' => $evname,
				'eventurl' => $eve,
				'euname' => $euname,				
														
		    );
       $cmntf = DB::table('mail_notifications')->where('user_id', '=', $gevuid[0]->id)->select('enotification_status')->get();			
	    if($cmntf[0]->enotification_status == 'y'){
			Mail::send('email.event_mail', $emdata, function($message) use ($SendToEmail, $Subject)
			{  $message->from('info@discoveryourevent.com');
			  $message->to($SendToEmail)->subject($Subject);
			});
	    }
	   
      return Redirect::to('backend/eventList')->with('succ_mesg', 'Event updated successfully');
	} else {
     return Redirect::to('backend/eventList');
	} 
  }
   public function eventReview($evid)
  {
    if(!empty($evid)){	
	   $single_event = DB::table('events')->where('id', '=', $evid)->get();
	  return \View::make('backend/default/events/review')->with('sing_event', $single_event)->with('title','Event Review - DiscoverYourEvent')->with('active','event');
	} else {
      return Redirect::to('backend/eventList');
	} 	
  }  
    public function getStats()
  {
     $allEventc = DB::table('events')->count();
	 $alladc = DB::table('account_details')->count();
	 $pv_count = DB::table('page_views')->select('count')->get();
	 $unvist = DB::table('unique_visits')->select(DB::raw('count(distinct IP) as uvd'))->get(); 
	 $tvist = DB::table('unique_visits')->count(); 
	 $drcount = DB::table('unique_visits')->where('referer_url', '=', null)->count(); 
	 $rucount = $tvist-$drcount;
	return \View::make('backend/default/stats')->with('title','Statistics - DiscoverYourEvent')->with('wvcount', $pv_count)->with('eventCount', $allEventc)
	->with('drcount',$drcount)->with('rucount',$rucount)->with('unvistr',$unvist)->with('acc_count', $alladc)->with('active','stats');
		
  } 
     public function getStatGraph()
  {
	 $getval = DB::table('unique_visits')->select(DB::raw('id,date, count(*) as alldc, count(distinct IP) as dip'))
	 ->orderBy('date')->groupBy('date')->get(); 
	 $data = array();
    $data2 = array();
   if(!empty($getval[0]->id)){
	foreach($getval as $gvl){
		$alldc = $gvl->alldc;
		$dip = $gvl->dip;
      $data[date('Y-m-d',strtotime($gvl->date))]=(string)$alldc;     
       $data2[date('Y-m-d',strtotime($gvl->date))]=(string)$dip; 		
	}	
   } 
	 include("graph/phpgraphlib.php"); 
		$graph=new PHPGraphLib(600,250);
		$graph->addData($data,$data2);
		$graph->setTitle("DiscoverYourEvent Site Statistics");
		$graph->setBars(false);
		$graph->setLine(true);
		$graph->setLineColor("black","light_blue");

		$graph->setDataPoints(true);
		$graph->setDataPointColor("maroon");
		$graph->setDataValues(true);
		$graph->setDataValueColor("maroon");
		$graph->setGoalLine(.0025);
		$graph->setGoalLineColor("red");
		$graph->setXValuesHorizontal(true);
		$graph->createGraph();
	 die; 
  }
  
 /*public function emailList()
  {
    $all_etemp = DB::table('email_template')->get();	
   return \View::make('backend/default/email_template/show')->with('all_etemp', $all_etemp)->with('title','Email Template List')->with('active','email');
  }*/
}	