<?php
/**
 * 		Copyright£ºSmartCome
 * 		  WebSite£ºwww.SmartCome.com
 *       QQ:2811931192
 */
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
//
function deal_message($message,$width,$height){
	$num=preg_match_all("/\[media.*?\[\/media\]/",$message,$match);
		foreach ($match[0] as $k=>$v){
        $type=get_type($v);
		if(!$type)continue;
		$media=get_embed($type,$v,$width,$height);
		$message=preg_replace("/\[media.*?\[\/media\]/",$media,$message,1);	
		}
	$num=preg_match_all("/\[flash.*?\[\/flash\]/",$message,$match);
		foreach ($match[0] as $k=>$v){
        $type=get_type($v);
		if(!$type)continue;
		$media=get_embed($type,$v,$width,$height);
		$message=preg_replace("/\[flash.*?\[\/flash\]/",$media,$message,1);	
		}		
	
	return $message;	
}   

	function get_type($str){
		$type=array("youku"=>"youku.com","tudou"=>"tudou.com","56"=>"56.com","ku6"=>"ku6.com","sohu"=>"sohu.com");
		foreach ($type as$k=>$v){
			if(istype($v,$str))
			return $k;	
		}	
	 return 0;	
	}
	function istype($key,$str){
		  return	preg_match("/".$key."/i",$str,$match);	
	  }
	function get_embed($type,$str,$width,$height){
	switch($type){
	case 'youku':
	$vid1=explode("sid/",$str);
	$vid2=explode("/v.swf",$vid1[1]);
	$vid=$vid2[0];
	return '<iframe height="'.$height.'" width="'.$width.'" src="http://player.youku.com/embed/'.$vid.'" frameborder=0 allowfullscreen></iframe>';break;
	case 'tudou':
	$vid1=explode("]",$str);
	$vid2=explode("[",$vid1[1]);
	$vid=$vid2[0];
	return '<embed src="'.$vid.'" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" wmode="opaque" width="'.$width.'" height="'.$height.'"></embed>';break;
	case '56':
	$vid1=explode("v_",$str);
	$vid2=explode(".swf",$vid1[1]);
	$vid=$vid2[0];
	return '<iframe width="'.$width.'" height="'.$height.'" src="http://www.56.com/iframe/'.$vid.'" frameborder="0" allowfullscreen=""></iframe>';break;
	case 'ku6':	
	$vid1=explode("/v.swf",$str);
	$vid2=explode("/",$vid1[0]);
	$len=count($vid2)-1;
	$vid=$vid2[$len];
	return '<embed src="http://player.ku6.com/refer/'.$vid.'/v.swf" width="'.$width.'" height="'.$height.'" allowscriptaccess="always" allowfullscreen="true" type="application/x-shockwave-flash" flashvars="from=ku6"></embed>';break;
	case 'sohu':
	$vid1=explode("/v.swf",$str);
	$vid2=explode("/",$vid1[0]);
	$len=count($vid2)-1;
	$vid=$vid2[$len];
	return '<embed width='.$width.' height='.$height.' wmode="Transparent" allowfullscreen="true" allowscriptaccess="always" quality="high" src="http://share.vrs.sohu.com/'.$vid.'/v.swf" type="application/x-shockwave-flash"/></embed>';break;
	}		
	
	}		
?>