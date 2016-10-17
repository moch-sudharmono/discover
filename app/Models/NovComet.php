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

class NovComet{
	
    const COMET_OK = 0;
    const COMET_CHANGED = 1;
    private $_tries;
    private $_var;
    private $_sleep;
    private $_ids = array();
    private $_callback = null;
    public function  __construct($tries = 10, $sleep = 2) 
    {
        $this->_tries = $tries;
        $this->_sleep = $sleep;
    }

    public function setVar($key, $value)
    {
        $this->_vars[$key] = $value;
    }
    
    public function setTries($tries)
    {
        $this->_tries = $tries;
    }
   
    public function setSleepTime($sleep)
    {
        $this->_sleep = $sleep;
    }
    
    public function setCallbackCheck($callback)
    {
        $this->_callback = $callback;
    }
	
    const DEFAULT_COMET_PATH =  "customAlert.comet";  
	
    public function run($lg_id){
      $notfresult = \DB::table('notifications')->where('onr_id', '=', $lg_id)->where('global_read', '=', 0)->select('id')->get();   
     if(is_null($this->_callback)){
       $defaultCometPAth =  asset(self::DEFAULT_COMET_PATH);	  
       $callback = function($id) use ($defaultCometPAth) {
         $cometFile = sprintf($defaultCometPAth, $id);				
        return (is_file($cometFile)) ? filemtime($cometFile) : 0;
       };
     } else {
        $callback = $this->_callback;
     }
      $out = array();
	 if(!empty($notfresult[0]->id)){       		 
        for($i = 0; $i < $this->_tries; $i++){
		  $p = 0;
         foreach($this->_vars as $id => $timestamp){
            if((integer) $timestamp == 0){
                    $timestamp = time();
            }
             $fileTimestamp = $callback($id);
            if($fileTimestamp > $timestamp){
                    $out[$id] = $fileTimestamp;
            }
           clearstatcache();
         }	
		
		foreach($notfresult as $getnid){
		 if($p == 0){			
		  $notfid = $getnid->id; 
		 } else {		  
		  $notfid .= ','.$getnid->id;	 
		 }	
		  $p++;
		}	
         if(count($out) > 0){				
          return json_encode(array('s' => self::COMET_CHANGED, 'k' => $out, 'ncount' => sizeof($notfresult), 'ncd' => $notfid));
         }
          sleep($this->_sleep);
        }
	   return json_encode(array('s' => self::COMET_OK, 'ncount' => sizeof($notfresult), 'ncd' => $notfid));	
	 } else { 
	  return json_encode(array('s' => 0, 'ncount' => 0, 'ncd' => 0));		 
	 }	   
    }
 }
 ?>