<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
include 'sitemap.fun.php';
Auth();//检测是否管理员
$rs = get_sitemap();
$surl .= '<a href="'.$rs->index.'" target="_blank">'.$rs->index.'</a><br>';
if($rs->fgi>0)
	for($i=0;$i<$rs->fgi;$i++){
		$url = $rs->xml;if($i>0){$url = str_replace('.xml',$i.'.xml',$rs->xml);}
		$surl .= '<a href="'.$url.'" target="_blank">'.$url.'</a><br>';
	}
else
	$surl = '<a href="'.$rs->xml.'" target="_blank">'.$rs->xml.'</a><br>';

include_once template("sitemap:sitemap");
?>