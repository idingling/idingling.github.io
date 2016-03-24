<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
if(!isset($_G['cache']['plugin'])){loadcache('plugin');}
set_time_limit(0);
$apath = DISCUZ_ROOT.'source/plugin/alias/Adz.class.php';
if(!defined('Adz_class') && file_exists($apath)) include($apath);
function Auth($str='admin'){
	global $_G;
	if($_G['groupid']!=1){
		echo 'not admin';
		exit;
	}
}
//获取上次更新时间并自动更新
function sitemap_auto(){
	//更新地图
	get_sitemap();//生成地图
	return '1';//返回值仅作调试用
}
function get_rewrite($item){
	global $_G;
	$rewritestatus = $_G['setting']['rewritestatus'];
	$rewriterule = $_G['setting']['rewriterule'];
	if(in_array($item,$rewritestatus)){
		return $rewriterule[$item];
	}else{
		return false;
	}
}

//查询后台设置的应用域名
function subdomain($item){
	global $_G;
	$domain = $_G['setting']['domain'];
	if($domain['app'][$item]){
		$return = $domain['app'][$item];
	}else{
		$return = $domain['app']['default'];
	}
	if(strlen($return)<3){
		$return = $_SERVER['HTTP_HOST'];
	}
	return $return;
}
/*
<priority>1.0</priority >此网页的优先级。有效值范围从 0.0 到 1.0 (选填项) 。0.0优先级最低、1.0最高
changefreq 有效值为：always、hourly、daily、weekly、monthly、yearly、never
*/
function sitemap_format($url,$date,$priority='1.0',$changefreq='always'){
return '<url>
<loc>'.$url.'</loc>
<lastmod>'.$date.'</lastmod>
<changefreq>'.$changefreq.'</changefreq>
<priority>'.$priority.'</priority>
</url>
';
}
function get_sitemap(){
	global $_G;
	$changefreq = array(1=>'always',2=>'hourly',3=>'daily',4=>'weekly',5=>'monthly',6=>'yearly',7=>'never');
	$xmlnum = array('tid'=>0,'fid'=>0,'aid'=>0,'domain'=>0);
	$aconfig = $_G['cache']['plugin']['sitemap'];
	$filename = DISCUZ_ROOT.$aconfig['filename'];
	if (!file_put_contents($filename,' ')) {
		echo '<br><h2 style="color:red;">'.lang('plugin/sitemap','cannot_wirte').$filename,','.lang('plugin/sitemap','chk_path').'</h2>';
		exit;
	}
	if(defined('Adz_class')) $aconfig_alias = $_G['cache']['plugin']['alias'];
	$fids = unserialize($aconfig['fids']);
	if($fids[0]>0){$fids = 'and a.fid in ('.implode(',',$fids).')';}else{$fids = '';}
	if($_SERVER['SCRIPT_NAME']) $script = $_SERVER['SCRIPT_NAME'];elseif($_SERVER['DOCUMENT_URI']) $script = $_SERVER['DOCUMENT_URI'];else $script = $_SERVER['PHP_SELF'];
	$web_root = $_G['siteroot'];
	if(in_array(CHARSET,array('gbk','gb2312','GBK','GB2312'))){
		$charsetto = "GBK";
	}else{
		$charsetto = false;
	}
	$xmli = 0;$fgi=0;
	//论坛域名
	/*if(count($_G['setting']['domain']['list'])>0)
	foreach ($_G['setting']['domain']['list'] as $k => $v){
		$sitemap .= sitemap_format($k,date('Y-m-d',time()),'1.0');
		$xmlnum['domain']++;
	}*/
	//论坛版块
	$rewrite=get_rewrite('forum_forumdisplay');
	$sql = "SELECT a.fid,a.domain FROM ".DB::table('forum_forum')." a where status=1 and a.type='forum' ORDER BY a.fid DESC  LIMIT 0,1000";
	$query = DB::query($sql);
	while($arr = DB::fetch($query)) {
		if($aconfig['num']>0 && $xmli>=$aconfig['num']){Afengge($filename,$sitemap,$fgi,$aconfig['urlset']);$fgi++;$xmli=0;}
		if(!empty($arr['domain'])){
			$turl = "http://".$arr['domain'].'.'.$_G['setting']['domain']['root']['forum'];//板块域名
		}else{
			$fid = $arr['fid'];
			if(!empty($_G['setting']['forumkeys'][$arr['fid']])) $fid = $_G['setting']['forumkeys'][$arr['fid']];//板块别名
			if($rewrite) $turl="http://".subdomain('forum').$web_root.str_replace(array('{fid}','{page}'),array($fid,1),$rewrite);//静态规则
			else $turl="http://".subdomain('forum').$web_root.'forum.php?mod=forumdisplay&amp;fid='.$arr['fid'];//动态规则,xml中&要换成&amp;
		}
		$sitemap .= sitemap_format($turl,date("Y-m-d",time()),$aconfig['fid_priority'],$changefreq[$aconfig['fid_cycle']]);
		$xmlnum['fid']++;$xmli++;
	}
	//论坛帖子
	if($aconfig_alias) $fm_trr = explode('{alias}',$aconfig_alias['fm_tid']);
	$rewrite=get_rewrite('forum_viewthread');
	$crr = explode('|',$aconfig['tid_config']);
	if($crr[1]>0){$where = 'and a.lastpost>'.(time()-$crr[1]*86400);}else{$where = '';}
	if($crr[2]>0){$limit = 'LIMIT 0,'.$crr[2];}else{$limit = '';}
	$sql = "SELECT a.tid,a.lastpost FROM ".DB::table('forum_thread')." a where (a.readperm=0 or a.readperm=1) and a.displayorder>=0 $fids $where ORDER BY a.tid DESC $limit";
	$query = DB::query($sql);
	while($arr = DB::fetch($query)) {
		if($aconfig['num']>0 && $xmli>=$aconfig['num']){Afengge($filename,$sitemap,$fgi,$aconfig['urlset']);$fgi++;$xmli=0;}
		if($rewrite){
			if($aconfig_alias){$aliasrr = Adz::fetch_con('plugin_alias_bind',array('rid'=>$arr['tid']));}
			if($aliasrr){
				if($charsetto) $aliasrr['alias'] = iconv($charsetto,"UTF-8//IGNORE",$aliasrr['alias']);
				if($aconfig_alias['urlcode']==1){$aliasrr['alias'] = rawurlencode($aliasrr['alias']);}
				$turl="http://".subdomain('forum').$web_root.$fm_trr[0].$aliasrr['alias'].$fm_trr[1];//静态规则
			}else{
				$turl="http://".subdomain('forum').$web_root.str_replace(array('{tid}','{page}','{prevpage}'),array($arr['tid'],1,1),$rewrite);//静态规则
			}
		}else{
			$turl="http://".subdomain('forum').$web_root.'forum.php?mod=viewthread&amp;tid='.$arr['tid'];//动态规则,xml中&要换成&amp;
		}
		$sitemap .= sitemap_format($turl,date("Y-m-d",$arr['lastpost']),$crr[0],$changefreq[$aconfig['tid_cycle']]);
		$xmlnum['tid']++;$xmli++;
	}
	if($aconfig['aid']==1){
		//门户文章
		$rewrite=get_rewrite('portal_article');
		$crr = explode('|',$aconfig['aid_config']);
		if($crr[1]>0){$where = 'WHERE a.dateline>'.(time()-$crr[1]*86400);}else{$where = '';}
		if($crr[2]>0){$limit = 'LIMIT 0,'.$crr[2];}else{$limit = '';}
		$sql = "SELECT a.aid,a.dateline FROM ".DB::table('portal_article_title')." a $where ORDER BY a.aid DESC $limit";
		$query = DB::query($sql);
		while($arr = DB::fetch($query)) {
			if($aconfig['num']>0 && $xmli>=$aconfig['num']){Afengge($filename,$sitemap,$fgi,$aconfig['urlset']);$fgi++;$xmli=0;}
			if($rewrite) $turl="http://".subdomain('portal').$web_root.str_replace(array('{id}','{page}'),array($arr['aid'],1),$rewrite);//静态规则
			else $turl="http://".subdomain('portal').$web_root.'portal.php?mod=view&amp;aid='.$arr['aid'];//动态规则,xml中&要换成&amp;
			$sitemap .= sitemap_format($turl,date("Y-m-d",$arr['dateline']),$crr[0],$changefreq[$aconfig['aid_cycle']]);
			$xmlnum['aid']++;$xmli++;
		}
	}
	$vippath = DISCUZ_ROOT.'source/plugin/sitemap/sitemap_vip.php';
	if(file_exists($vippath)) include($vippath);
	if($xmli>0){Afengge($filename,$sitemap,$fgi,$aconfig['urlset']);$fgi++;}
	$rs->fgi = $fgi;
	$rs->num = $xmlnum;
	$rs->xml = "http://".subdomain('default').$web_root.$aconfig['filename'];
	$rs->index = "http://".subdomain('default').$web_root.$aconfig['indexname'];
	$index = '<?xml version="1.0"  encoding="UTF-8" ?>
<sitemapindex>
';
	for($i=0;$i<$fgi;$i++){
		$url = $rs->xml;if($i>0){$url = str_replace('.xml',$i.'.xml',$rs->xml);}
		$index .= '<sitemap>
<loc>'.$url.'</loc>
<lastmod>'.date('Y-m-d').'</lastmod>
</sitemap>
';
	}
	$index .= '</sitemapindex>';
	file_put_contents(DISCUZ_ROOT.$aconfig['indexname'],$index);
	return $rs;
}
function Afengge($filename,&$note,$fgi=0,$urlset=''){
	$sitemap_star='<?xml version="1.0" encoding="UTF-8"?>
<urlset '.$urlset.'>
';
	$sitemap_end = "</urlset>\n";
	if($fgi>0){
		$filename = str_replace('.xml',$fgi.'.xml',$filename);
	}
	file_put_contents($filename,$sitemap_star.$note.$sitemap_end);
	$note = '';
}
function get_cronid(){
	global $_G;
	$name = 'cron_sitemap.php';
	if($_G['setting']['version']=='X3'){
		$name = 'sitemap:cron_sitemap.php';
	}
	$data = DB::fetch_first("SELECT cronid FROM ".DB::table('common_cron')." where filename='$name'");
	if($data) $cronid = $data['cronid'];
	return $cronid;
}
?>