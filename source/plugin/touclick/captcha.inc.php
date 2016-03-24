<?php
if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}

C::import('touclick_seccode','plugin/touclick/class');
$obj = new touclick_seccode();

$conf = @include DISCUZ_ROOT.'./source/plugin/touclick/conf.php';
$config = $conf['baseset'];

if(submitcheck('submit')){
    $config = $_GET['sec'];
    $config['pubkey']=trim($config['pubkey']);
    $config['prikey']=trim($config['prikey']);
    
    if($config['pubkey'] == $config['prikey'] || strlen($config['pubkey']) != 36 || strlen($config['prikey']) != 36){
        //长度不合法
        cpmsg('touclick:tcmsg0021', "", 'error');
    }
    $conf['baseset']= $config;
    $conf['active']=0;
    $set = array(
         'pubkey'=>$config['pubkey'],
         'prikey'=>$config['prikey'],
     );
    $obj->set($set);
    $back = $obj->activate('active');
	if($back=='active'){
        $conf['active']=1;
        $configdata = 'return '.var_export($conf, true).";\n\n";
        if($fp = @fopen(DISCUZ_ROOT.'./source/plugin/touclick/conf.php', 'wb')) {
            fwrite($fp, "<?php\n//plugin touclick config file, DO NOT modify me!\n//Identify: ".md5($k.$configdata)."\n\n$configdata?>");
            fclose($fp);
        }else{
            cpmsg(plang('nowrite'), 'action=plugins&operation=config&do='.$pluginid.'&identifier=touclick&pmod=captcha', 'error');
        }
		cpmsg(plang('succeed'), 'action=plugins&operation=config&do='.$pluginid.'&identifier=touclick&pmod=captcha', 'succeed');
	}elseif($back=='none'){
         cpmsg(plang('alert'), 'action=plugins&operation=config&do='.$pluginid.'&identifier=touclick&pmod=captcha', 'error');
	}else{
         cpmsg(plang('alerts'), 'action=plugins&operation=config&do='.$pluginid.'&identifier=touclick&pmod=captcha', 'error');
	}
}
$actback = 'notlaw';
if($config['pubkey'] != $config['prikey'] && strlen($config['pubkey']) == 36 && strlen($config['prikey']) == 36){
    $set = array(
         'pubkey'=>$config['pubkey'],
         'prikey'=>$config['prikey'],
     );
    $obj->set($set);
    $actback = $obj->activate();
}
switch($actback){
	case'active':
		if($conf['active'])
			$actstr = '<font color="#0099CC">'.plang('activated').'</font>';
		else
			$actstr = '<font color="#FF0000">'.plang('activating').'</font>';
		break;
	case'error':
		$actstr = '<font color="#FF0000">'.plang('srverr').'</font>';
        setactive(0);
		break;
	case'none':
		$actstr = '<font color="#FF0000">'.plang('alert').'</font>';
        setactive(0);
		break;
	case 'notactive':
		$actstr = '<font color="#FF0000">'.plang('activating').'</font>';
        setactive(0);
		break;
    case 'notlaw':
        $actstr = '<font color="#f00">'.plang('notlaw').'</font>';
        setactive(0);
        break;
	default:
        setactive(0);
		$actstr = '<font color="#FF0000">'.plang('othererror').'</font>';
}

showformheader("plugins&cp=$cp&pmod=captcha&operation=$operation&do=$do", "",'actform','post');
showtableheader(plang('baseset'));

showsetting(plang('tcmsg0003'), '', '', $actstr,'','',plang('tcmsg0003msg'));
showsetting(plang('pubkeyset'), 'sec[pubkey]', $config['pubkey'], 'text','','',plang('pubkeyset_note'));
showsetting(plang('prikeyset'), 'sec[prikey]', $config['prikey'], 'text','','',plang('prikeyset_note'));
showtablefooter();
showsubmit('submit', 'submit');
showformfooter();
function plang($str) {
	return lang('plugin/touclick', $str);
}
function setactive($active){
    $conf = @include DISCUZ_ROOT.'./source/plugin/touclickplat/conf.php';
    $conf['active']=$active;
    $configdata = 'return '.var_export($conf, true).";\n\n";
    if($fp = @fopen(DISCUZ_ROOT.'./source/plugin/touclickplat/conf.php', 'wb')) {
        fwrite($fp, "<?php\n//plugin touclickplat config file, DO NOT modify me!\n//Identify: ".md5($k.$configdata)."\n\n$configdata?>");
        fclose($fp);
    }
}
?>