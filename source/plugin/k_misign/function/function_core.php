<?php
!defined('IN_DISCUZ') && exit('Access Denied');

$setting = $_G['cache']['plugin']['k_misign'];
$setting['extends'] = unserialize($setting['extendp']);
$setting['style'] = C::t('common_setting')->fetch('k_misignstyle', '1');
$setting['styles'] = $setting['style']['svalue'] ? unserialize($setting['style']['svalue']) : array('pc' => 'default', 'mobile' => 'mobile_default');
$setting['pluginurl'] = $setting['pluginurl'] ? $setting['pluginurl'] : 'plugin.php?id=k_misign:';

if(is_file(DISCUZ_ROOT.'./source/plugin/k_migeyan/k_migeyan.class.php')){
	$extend['k_migeyan'] = 1; 
}

//辅助扩展
if(is_file(DISCUZ_ROOT.'./source/plugin/k_misign/extend/extend_dataimport.php')){
	$extend['dataimport'] = 1; 
}
if(is_file(DISCUZ_ROOT.'./source/plugin/k_misign/extend/extend_lastdefend.php')){
	$extend['lastdefend'] = 1; 
}
if(is_file(DISCUZ_ROOT.'./source/plugin/k_misign/extend/extend_dataexport.php')){
	$extend['dataexport'] = 1; 
}
if(is_file(DISCUZ_ROOT.'./source/plugin/k_misign/extend/extend_lastrule.php')){
	$extend['lastrule'] = 1; 
}
if(is_file(DISCUZ_ROOT.'./source/plugin/k_misign/extend/extend_totalrule.php')){
	$extend['totalrule'] = 1; 
}
if(is_file(DISCUZ_ROOT.'./source/plugin/k_misign/extend/extend_leval.php')){
	$extend['leval'] = 1; 
}

//道具
if(is_file(DISCUZ_ROOT.'./source/plugin/k_misign/magic/magic_k_misign_bq.php')){
	$extend['magic']['bq'] = 1;
	$extend['magicdetail']['bq'] = C::t('common_magic')->fetch_by_identifier('k_misign:k_misign_bq');
}

//嵌入点扩展
if(is_file(DISCUZ_ROOT.'./source/plugin/k_misign/extend/extend_hook_global_usernav_extra3.php')){
	$hook['global_usernav_extra3'] = 1; 
}
if(is_file(DISCUZ_ROOT.'./source/plugin/k_misign/extend/extend_hook_viewthread_sidetop.php')){
	$hook['viewthread_sidetop'] = 1; 
}
if(is_file(DISCUZ_ROOT.'./source/plugin/k_misign/extend/extend_hook_indexside_qzone.php')){
	$hook['indexside']['qzone'] = 1; 
}


function pluginurl($mod){
	global $_G;
	$setting = $_G['cache']['plugin']['k_misign'];
	if($setting['pluginurl']){
		return $setting['pluginurl'].$mod;
	}else{
		return 'plugin.php?id=k_misign:'.($mod ? $mod : 'sign');
	}
}

function array_insert($myarray,$value,$position=0){
   		$fore=($position==0)?array():array_splice($myarray,0,$position);
   		$fore[]=$value;
   		$ret=array_merge($fore,$myarray);
   		return $ret;
}

?>