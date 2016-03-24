<?php


if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

$conf = @include DISCUZ_ROOT.'./source/plugin/touclick/conf.php';
$config = $conf['baseset'];
$pubkey=trim($_GET['pubkey']);
$prikey=trim($_GET['prikey']);
if($pubkey!=$config['pubkey']||$prikey!=$config['prikey']){
    exit('error');
}

$way = trim($_GET['way']);
$t = trim($_GET['type']);

switch($way){
	case 'getconfig':getconfig();break;
	case 'clearlog' :clearlog();break;
    case 'opendebug':setconf('isdebug',true);break;
    case 'closedebug':setconf('isdebug',false);break;
    case 'open':setconf('open');break;
    case 'close':setconf('close');break;
    case 'getlog':getlog();break;
}
function clearlog(){
    file_put_contents(DISCUZ_ROOT.'./source/plugin/touclick/log.txt','');
    echo 'ok';
}
function getlog(){
    $my_file = file_get_contents(DISCUZ_ROOT.'./source/plugin/touclick/log.txt');
    echo $my_file;
}
function setconf($item,$isdebug){
	global $t,$conf;
	switch($item){
		case 'isdebug':$conf[$item] = $isdebug;break;
		case 'open':
			if($t=='captcha')
				$conf['seccode']['open'] = '1';
			else if($t=='content')
				$conf['contmonitor']['open'] = '1';
			break;
		case 'close':
			if($t=='captcha')
				$conf['seccode']['open'] = '0';
			else if($t=='content')
				$conf['contmonitor']['open'] = '0';
			break;
		default:
                exit('default');
	}
    $configdata = 'return '.var_export($conf, true).";\n\n";
    if($fp = @fopen(DISCUZ_ROOT.'./source/plugin/touclick/conf.php', 'wb')) {
        fwrite($fp, "<?php\n//plugin touclick config file, DO NOT modify me!\n//Identify: ".md5($k.$configdata)."\n\n$configdata?>");
        fclose($fp);
    }
    echo 'ok';
}
function getconfig(){
	global $_G,$conf;
	$d = array();
	$d['siteurl']=$_G['siteurl'];
	$d['sitename']=$_G['setting']['bbname'];
	$d['seccode']=$conf['seccode'];
	$d['contmonitor']=$conf['contmonitor'];
    $d['baseset'] = $conf['baseset'];
	unset($d['seccode']['groupid']);
	unset($d['contmonitor']['groupid']);
	$query = DB::query("SELECT * FROM ".DB::table('common_usergroup'));
	while($group = DB::fetch($query)) {
		if(in_array($group['groupid'],$conf['seccode']['groupid'])) {
			$d['seccode']['group'][] = array('id'=>$group['groupid'],'name'=>diconv($group['grouptitle'],CHARSET,'UTF-8'),'stat'=>1);
		} else {
			$d['seccode']['group'][] = array('id'=>$group['groupid'],'name'=>diconv($group['grouptitle'],CHARSET,'UTF-8'),'stat'=>0);
		}
		if(in_array($group['groupid'],$conf['contmonitor']['groupid'])) {
			$d['contmonitor']['group'][] = array('id'=>$group['groupid'],'name'=>diconv($group['grouptitle'],CHARSET,'UTF-8'),'stat'=>1);
		} else {
			$d['contmonitor']['group'][] = array('id'=>$group['groupid'],'name'=>diconv($group['grouptitle'],CHARSET,'UTF-8'),'stat'=>0);
		}
	}
	if(function_exists('json_encode')){
		echo json_encode($d);
	}else{
		echo serialize($d);
	}
}
?>