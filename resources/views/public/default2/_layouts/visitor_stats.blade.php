<?php
 $pv_count = DB::table('page_views')->select('count')->get(); 
 $new_page_view = $pv_count[0]->count + 1;
 DB::table('page_views')->update(['count' => $new_page_view]); 
 //Check unique visits
$computer_name = gethostname();
$ips = $_SERVER['REMOTE_ADDR'];
$uvdate = date('Y-m-d');
 $unq_vist = DB::table('unique_visits')->Where('ip', '=', $ips)->get();
  if(isset($_SERVER['HTTP_REFERER'])){
   $http_ref = $_SERVER['HTTP_REFERER']; 
  } else {
   $http_ref = null;	 
  }	 
 if(sizeof($unq_vist) < 1) {
  DB::table('unique_visits')->insert(['ip' => $ips,'computer_name' => $computer_name,'referer_url' => $http_ref,'browser_info' => $_SERVER['HTTP_USER_AGENT'],'date' => $uvdate]); 
 } else {
   DB::table('unique_visits')->insert(['ip' => $ips,'computer_name' => $computer_name,'referer_url' => $http_ref,'browser_info' => $_SERVER['HTTP_USER_AGENT'],'date' => $uvdate]); 
 }  
?>