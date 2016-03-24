<?php


if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}

$conf = @include DISCUZ_ROOT.'./source/plugin/touclick/conf.php';
$config = $conf['baseset'];
if($config['pubkey'] == $config['prikey'] || strlen($config['pubkey']) != 36 || strlen($config['prikey']) != 36){
    //长度不合法
    cpmsg('touclick:tcmsg0021', "", 'error');
}


echo '<iframe id="iframe" src="http://admin.touclick.com/login.html?tem='.rand(0,100).'&way=sitekey&key='.$config['pubkey'].'&value='.$config['prikey'].'" style="height:700px; width: 100%;" frameborder="0" scrolling="auto"></iframe>';
?>
