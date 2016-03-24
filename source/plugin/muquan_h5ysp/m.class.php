<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

class mobileplugin_muquan_h5ysp {
	
   function __construct(){  
      global $_G;
	  $this->plugin_setting=$_G['cache']['plugin']['muquan_h5ysp'];
    } 
   
   function discuzcode() {
      global $_G;
	  $forumarr=unserialize($this->plugin_setting['forum']);
	  if(in_array($_G['fid'],$forumarr) || empty($forumarr[0])){
		if (strpos($_G['discuzcodemessage'], '[/media]') !== false) {
				$_G['discuzcodemessage'] = preg_replace('/\[media=([\w,]+)\]\s*([^\[\<\r\n]+?)\s*\[\/media\]/ies','$this->_parsemedia(\'\\2\')', $_G['discuzcodemessage']);
			} 
		if (strpos($_G['discuzcodemessage'], '[/flash]') !== false) {
				$_G['discuzcodemessage'] = preg_replace('/\[flash(=(\d+),(\d+))?\]\s*([^\[\<\r\n]+?)\s*\[\/flash\]/ies','$this->_parsemedia(\'\\4\')', $_G['discuzcodemessage']);
			} 		
        if (strpos($_G['discuzcodemessage'], '[/h5audio]') !== false) {
			$_G['discuzcodemessage']=preg_replace('/\[h5audio(=1)*\]\s*([^\[\<\r\n]+?)\s*\[\/h5audio\]/ies','$this->_parseh5audio(\'\\2\', 400, \'\\1\')', $_G['discuzcodemessage']);
	    }
	    if (strpos($_G['discuzcodemessage'], '[/h5mp4]') !== false) {
			$_G['discuzcodemessage']= preg_replace("/\[h5mp4(=(\d+),(\d+))?\]\s*([^\[\<\r\n]+?)\s*\[\/h5mp4\]/ies",'$this->_parseh5mp4(\'\\4\', \'\\2\', \'\\3\')', $_G['discuzcodemessage']);
	    }  
	  }
   }
   
   function global_header_mobile(){
	  include template('muquan_h5ysp:m'); 
	  return $footer; 
   }
   
   function _parsemedia($url) {
	$width  = $this->plugin_setting['width'];
    $height =$this->plugin_setting['height'];
	$lowerurl = strtolower($url);
	
	if (strpos($lowerurl, 'v.youku.com/v_show/') !== FALSE) {
		if (preg_match("/http:\/\/v.youku.com\/v_show\/id_([^\/]+)(.html|)/i", $url, $matches)) {
			$iframe = 'http://player.youku.com/embed/'.$matches[1];
		}
	} elseif (strpos($lowerurl, 'player.youku.com/') !== FALSE) {
		if(preg_match("/http:\/\/player.youku.com\/player.php\/sid\/([^\/]+)\/v.swf/i", $url, $matches)){
		    $iframe = 'http://player.youku.com/embed/'.$matches[1];
		}
		elseif(preg_match("/http:\/\/player.youku.com\/player.php\/(.+)\/sid\/([^\/]+)\/v.swf/i", $url, $matches)){
			$iframe = 'http://player.youku.com/embed/'.$matches[2];
		}
	} elseif(strpos($lowerurl, 'qq.com/') !== FALSE) {
		if(preg_match("/http:\/\/static.video.qq.com\/TPout.swf\?vid=([^\/]+)/i", $url, $matches)) {
			$iframe = 'http://v.qq.com/iframe/player.html?vid='.$matches[1].'&tiny=0&auto=0';
		}
	}
	include template('muquan_h5ysp:m'); 
	return $iframe;
   }
   
   function _parseh5audio($url, $width = 400, $autostart = 0) {
	 global $_G;
	 if(preg_match("/attach:\/\/(.+).mp3/i", $url, $matches)){
	   $attachid=$matches[1];
	   $tableid=getattachtablebyaid($attachid);
	   $url=DB::result_first("SELECT attachment FROM ".DB::table($tableid)." WHERE aid='$attachid'");
	   $url=$_G['setting']['attachurl'].'forum/'.$url;
	 }
	 $autostart = $autostart !== '' ? 'autoplay="autoplay"' : '';
	 include template('muquan_h5ysp:m'); 
	 return $mp3;
   }
   
   function _parseh5mp4($url,$w, $h) {
	 $H5_YSP=$this->plugin_setting;  
	 include template('muquan_h5ysp:m'); 
	 return $mp4;
   }
       
}
?>