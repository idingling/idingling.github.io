<?php

if(!defined('IN_DISCUZ')) {
   exit('Access Deined');
}

include ("function.inc.php");
$water=new waterFallPlugin();
$fid=strval($_GET['fid']);
$filter=strval($_GET['filter']);
$orderby=strval($_GET['orderby']);

//添加分页
$eachload=$_G['cache']['plugin']['waterfall']['eachload'];
$loadsperpage=$_G['cache']['plugin']['waterfall']['loadsperpage'];
$perpage=$eachload*$loadsperpage;//每页显示数
$page=intval($_GET['page']);
if($page<1) $page=1;

$query=DB::fetch_first("select count(*) as num from ".DB::table('forum_thread').' as a '.$water->getWhereString($fid,$filter));
$num=$query['num'];
$pagesnum=ceil($num/$perpage);//总页数
if($page>$pagesnum) $page=$pagesnum;	
$mpurl = "plugin.php?id=waterfall:waterfall&fid=$fid&filter=$filter&orderby=$orderby";
$mulpage=multi($num, $perpage, $page, $mpurl);

//所有已设置论坛版块
$sql="select fid,name from ".DB::table('forum_forum')." where fid in (".$water->getForumIDlistString().")";
$query=DB::query($sql);
$forum=$forums=array();
while($forum = DB::fetch($query))
{
	$forums[]=$forum;
}

//可选择的筛选和排序方式
$filters=$water->filterArray;
$orderbys=$water->orderByArray;
//其他参数
$picwidth=$_G['cache']['plugin']['waterfall']['picwidth'];
$orderbydefault=$_G['cache']['plugin']['waterfall']['orderbydefault'];//默认的排序方式

include template('waterfall:waterfall');

?>