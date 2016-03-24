<?php
if (!defined('IN_DISCUZ'))
{
    exit('2015112921KktVY1229Y');
}
$url = $_GET['url'];
$var = $_G['cache']['plugin']['localurl'];
if($var['encode']=='base64') $url = base64_decode(strtr($url, '-_,', '+/='));
elseif($var['encode']=='urlencode') $url = urldecode($url);
include template('localurl:localurl');

?>