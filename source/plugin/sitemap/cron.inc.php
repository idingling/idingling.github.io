<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
include 'sitemap.fun.php';
Auth();//检测是否管理员
$cronid = get_cronid();
if($cronid>0){
	$cronmsg = '<span style="color:green;">'.lang('plugin/sitemap','auto_updata').'</span>';
}else{
	$cronmsg = '<span style="color:red;">'.lang('plugin/sitemap','manual_update').'</span>';
}
if($_GET['start']==1){
	if($cronid>0){
		$msg = '<span style="color:green">'.lang('plugin/sitemap','enabled').'</span>';
	}else{
		$save['available']=1;
		$save['name']=lang('plugin/sitemap','cron_name');
		$save['filename']='cron_sitemap.php';
		if($_G['setting']['version']=='X3'){
			$save['filename'] = 'sitemap:cron_sitemap.php';
			$save['type']='plugin';
		}
		$save['nextrun']=strtotime(date("Y-m-d",time()+86400).' 00:00:00');
		$save['weekday']=-1;
		$save['day']=-1;
		$save['hour']=0;
		$save['minute']='0';
		DB::insert('common_cron',$save);
		$cronmsg = '<span style="color:green;">'.lang('plugin/sitemap','auto_updata').'</span>';
		$cronid = get_cronid();
	}
}elseif($_GET['stop']==1){
	if($cronid) DB::delete('common_cron', "cronid=$cronid");
	$cronmsg = '<span style="color:red;">'.lang('plugin/sitemap','manual_update').'</span>';
	$cronid = '';
}
$cron_url = ADMINSCRIPT.'?action=misc&operation=cron&edit='.$cronid;
$url = ADMINSCRIPT.'?action=plugins&operation=config&identifier=sitemap&pmod=cron';

include_once template("sitemap:cron");
?>