<?php
/**
 *	[极致 自由跳转(jz52_jump.{modulename})] (C)2014-2099 Powered by 极致 QQ393988832.
 *	Version: 1.0
 *	Date: 2014-4-30 17:10
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
class plugin_jz52_jump {	
	function plugin_jz52_jump() {
		global $_G;
		$this->Jz_JumpBody = $_G['cache']['plugin']['jz52_jump']['Jz_JumpBody'];
		$this->Jz_JumpMode = $_G['cache']['plugin']['jz52_jump']['Jz_JumpMode'];
		$this->Jz_Jumpyc = $_G['cache']['plugin']['jz52_jump']['Jz_Jumpyc'];
		$this->Jz_JumpUser = $_G['cache']['plugin']['jz52_jump']['Jz_JumpUser'];
		$this->group = unserialize ( $this->Jz_JumpUser );
	}
		
	public function global_header() {
	global $_G;		
	if(!in_array($_G['group']['groupid'],$this->group)){		
		$get_url = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
		$urlsz = explode("\r\n",$this->Jz_JumpBody);
		foreach($urlsz as $key => $val){
        $urlbb = explode("|",$val);
        if ($urlbb[0] == "$get_url"){
		   if ($this->Jz_JumpMode == 1){
		    header('Location: '.$urlbb[1]); 
		   }elseif($this->Jz_JumpMode == 2){
		   return "<script type=\"text/javascript\">setTimeout(function(){window.location.href='$urlbb[1]';},$this->Jz_Jumpyc);</script>";
		   }    
        }
		}	
	}
	}
}

?>