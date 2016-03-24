<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

class plugin_muquan_h5ysp {
	
   function __construct(){  
      global $_G;
	  $this->plugin_setting=$_G['cache']['plugin']['muquan_h5ysp'];
    } 
   
   function discuzcode() {
     global $_G;
	 $forumarr=unserialize($this->plugin_setting['forum']);
	 if(in_array($_G['fid'],$forumarr) || empty($forumarr[0])){
        if (strpos($_G['discuzcodemessage'], '[/h5audio]') !== false) {
			$_G['discuzcodemessage']=preg_replace('/\[h5audio(=1)*\]\s*([^\[\<\r\n]+?)\s*\[\/h5audio\]/ies','$this->_parseh5audio(\'\\2\', 400, \'\\1\')', $_G['discuzcodemessage']);
	    } 
	    if (strpos($_G['discuzcodemessage'], '[/h5mp4]') !== false) {
			$_G['discuzcodemessage']= preg_replace("/\[h5mp4(=(\d+),(\d+))?\]\s*([^\[\<\r\n]+?)\s*\[\/h5mp4\]/ies",'$this->_parseh5mp4(\'\\2\', \'\\3\', \'\\4\')', $_G['discuzcodemessage']);
	   }
	 }
   }
   
   function global_footer(){
	  include template('muquan_h5ysp:pc'); 
	  return $footer; 
   }
   
   function _parseh5audio($url, $width = 400, $autostart = 0) {
	 $autostart = $autostart !== '' ? 'autoplay="autoplay"' : '';
	 include template('muquan_h5ysp:pc'); 
	 return $mp3;
   }
   
   function _parseh5mp4($w, $h, $url) {
	 $w = !$w ? 550 : $w;
	 $h = !$h ? 400 : $h;
	 $H5_YSP=$this->plugin_setting;
	 include template('muquan_h5ysp:pc'); 
	 return $mp4;
   }
   
      
}


class plugin_muquan_h5ysp_forum extends plugin_muquan_h5ysp {
       
	    function post_editorctrl_left() {
		   global $_G; 
		   $forumarr=unserialize($this->plugin_setting['forum']);
		   if(in_array($_G['groupid'],unserialize($this->plugin_setting['user'])) && (in_array($_G['fid'],$forumarr) || empty($forumarr[0])) ){
			   include template('muquan_h5ysp:pc');
     	       return $btn;
		   }
		}

}
?>