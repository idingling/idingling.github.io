<?php
if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('http://www.5477dm.com/|http://www.5477dm.com/|3F7B28B3-C8FF-EDF6-6EB6-9E69694679B0');
}
@include_once ('rewrite.php');
loadcache('plugin');
$var = $_G['cache']['plugin']['localurl'];
echo "<style>
.addon-alert {	padding: 8px 35px 8px 14px;margin-bottom: 3px;text-shadow: 0 1px 0 rgba(255, 255, 255, 0.5);background-color: #FCF8E3;border: 1px solid #FBEED5;-webkit-border-radius: 4px;-moz-border-radius: 4px;border-radius: 4px;color: #C09853;}
.addon-alert a{color: #C09853;font-weight:bold;}
.addon-alert-info {background-color: #D9EDF7;border-color: #BCE8F1;color: #3A87AD;}
.addon-alert-info a{color: #3A87AD;font-weight:bold;}
.addon-alert-success {background-color: #DFF0D8;border-color: #D6E9C6;color: #468847;}
.addon-alert-success a{color: #468847;font-weight:bold;}
</style>";

if($var['encode']=='base64')  $url = strtr(base64_encode('http://www.dzcsu.com'), '+/=', '-_,');
elseif($var['encode']=='urlencode') $url = urlencode('http://www.dzcsu.com');
if($var['rewrite']==1) $url = rewriteurl($url);
else{
	if((float)trim($_G['setting']['version'],'Xx')>=3) $url="plugin.php?id=localurl&url=".$url;
	else $url="plugin.php?id=localurl:localurl&url=".$url;
}
echo '<div class="addon-alert addon-alert-success"><a href="'.$url.'" target="_balnk" ><strong>'.lang('plugin/localurl', '0').'</strong></a></div>';
$tip = ($rewrite == 1) ? '<a href="admin.php?action=plugins&operation=config&identifier=localurl&pmod=more&add=rewrite" >'.lang('plugin/localurl', '2').'</a>' : '<a href="http://addon.discuz.com/?@localurl.plugin.50085" target="_balnk" >'.lang('plugin/localurl', '1').'</a>';
echo '<div class="addon-alert addon-alert-info"><strong>'.$tip.'</strong></a></div>';
if($rewrite==1&&$_GET['add']=='rewrite') rewriterule();

?>