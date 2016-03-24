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
$config = $conf['seccode'];
if(submitcheck('submit')){
	$config = $_GET['sec'];
    $conf['seccode']=$config;
	$configdata = 'return '.var_export($conf, true).";\n\n";
    
	if($fp = @fopen(DISCUZ_ROOT.'./source/plugin/touclick/conf.php', 'wb')) {
		fwrite($fp, "<?php\n//plugin touclick config file, DO NOT modify me!\n//Identify: ".md5($k.$configdata)."\n\n$configdata?>");
		fclose($fp);
	}else{
		cpmsg(plang('nowrite'), 'action=plugins&operation=config&do='.$pluginid.'&identifier=touclick&pmod=secset', 'error');
	}
	cpmsg(plang('succeed'), 'action=plugins&operation=config&do='.$pluginid.'&identifier=touclick&pmod=secset', 'succeed');
}

$groupselect = array();
$query = C::t('common_usergroup')->range();
foreach($query as $group) {
	$group['type'] = $group['type'] == 'special' && $group['radminid'] ? 'specialadmin' : $group['type'];
	if(in_array($group['groupid'],$config['groupid'])) {
		$groupselect[$group['type']] .= "<option value=\"$group[groupid]\" selected>$group[grouptitle]</option>\n";
	} else {
		$groupselect[$group['type']] .= "<option value=\"$group[groupid]\">$group[grouptitle]</option>\n";
	}
}
$groupselect ='<option value="" '.(empty($config['groupid'][0])?'selected':'').'>'.$lang['plugins_empty'].'</option><optgroup label="'.$lang['usergroups_member'].'">'.$groupselect['member'].'</optgroup>'.
			($groupselect['special'] ? '<optgroup label="'.$lang['usergroups_special'].'">'.$groupselect['special'].'</optgroup>' : '').
			($groupselect['specialadmin'] ? '<optgroup label="'.$lang['usergroups_specialadmin'].'">'.$groupselect['specialadmin'].'</optgroup>' : '').
			'<optgroup label="'.$lang['usergroups_system'].'">'.$groupselect['system'].'</optgroup>';

showformheader("plugins&cp=$cp&pmod=secset&operation=$operation&do=$do", "",'actform','post');
showtableheader(plang('seccode'));
showsetting(plang('opencaptchaset'), 'sec[open]', $config['open'], 'radio','','',plang('opencaptchaset_note'));
showsetting(plang('tcmsg0001'), array('sec[mod][]',
                                array(
                                    array('reg',plang('tcmsg0042')),
                                    array('login',plang('tcmsg0043')),
                                    array('newthread',plang('tcmsg0044')),
                                    array('reply',plang('tcmsg0045')),
                                    array('edit',plang('tcmsg0046')),
                                )), $config['mod'], 'mselect');

showsetting(plang('tcmsg0002'), 'sec[groupid]', '', '<select name="sec[groupid][]" multiple="multiple" size="10">'.$groupselect.'</select>','','',plang('tcmsg0002msg'));
showtablefooter();
showsubmit('submit', 'submit');
showformfooter();

function plang($str) {
	return lang('plugin/touclick', $str);
}
?>