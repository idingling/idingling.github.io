<?php
if(!defined('IN_DISCUZ')) {
   exit('Access Deined');
}

include ("function.inc.php");
$water=new waterFallPlugin();
$fid=strval($_GET['fid']);
$filter=strval($_GET['filter']);
$orderby=strval($_GET['orderby']);

//生成sql语句
$sql="SELECT a.tid,a.author,a.authorid,a.subject,a.dateline,a.lastpost,a.attachment,a.views,a.replies,a.digest,a.heats,a.recommends,b.attachment as image from ".DB::table('forum_thread')." as a left join ".DB::table('forum_threadimage')." as b on a.tid=b.tid ".$water->getWhereString($fid,$filter)." ".$water->getOrderbyString($orderby);

//设置页、段
$eachload=$_G['cache']['plugin']['waterfall']['eachload'];
$loadsperpage=$_G['cache']['plugin']['waterfall']['loadsperpage'];

$perpage=$eachload*$loadsperpage;//每页显示数

$page=intval($_GET['page']);
$loads=intval($_GET['loads']);

if ($page<1) $page=1;
if ($loads<1) $loads=1;
if  ($loads>$loadsperpage) $loads=$loadsperpage;

$start = $page * $perpage - $perpage+$loads*$eachload-$eachload;
$sql=$sql." LIMIT $start, $eachload";

//$lengthforpost=$_G['cache']['plugin']['waterfall']['lengthforpost'];
$picwidth=$_G['cache']['plugin']['waterfall']['picwidth'];
$picmaxheight=$_G['cache']['plugin']['waterfall']['picmaxheight'];

//获取主题数据
$query=DB::query($sql);
$thread = $threads = array();
while($thread = DB::fetch($query))
{
	if($thread['image'])//判断是否已有主题封面的图片	
	{
		$thread['image'] = 'data/attachment/forum/'.$thread['image']; 
	}elseif ($thread['attachment']==2)//若主题中有图片附件，则找其第一个图片附件,有可能不是第一楼的图片
	{
	  	$img = DB::fetch_first("SELECT attachment FROM ".DB::table(getattachtablebytid($thread['tid']))." WHERE tid=".$thread['tid']);
		if ($img['attachment']) 
		{
			$thread['image'] = 'data/attachment/forum/'.$img['attachment'];
		}
		else 
		{
			$thread['image'] = 'static/image/common/nophoto.gif';		
		}
	}
	else
	{
		$thread['image'] = 'static/image/common/nophoto.gif';
	}
	
	if (!empty($thread['authorid'])) $thread['avatar']=get_avatar($thread['authorid'], 'small');
	else $thread['avatar']='';
	$thread['dateline']=date('Y-m-d h:m',$thread['dateline']);
	$threads[]=$thread;			
}
$noavatar=get_noavatar('small');

include template('waterfall:threads');

function get_avatar($uid, $size = 'middle', $type = '') {
	global $_G;
	$ucenterurl =  $_G['setting']['ucenterurl'];
	$size = in_array($size, array('big', 'middle', 'small')) ? $size : 'middle';
	$uid = abs(intval($uid));
	$uid = sprintf("%09d", $uid);
	$dir1 = substr($uid, 0, 3);
	$dir2 = substr($uid, 3, 2);
	$dir3 = substr($uid, 5, 2);
	$typeadd = $type == 'real' ? '_real' : '';
	$avatarfile=$ucenterurl.'/data/avatar/'.$dir1.'/'.$dir2.'/'.$dir3.'/'.substr($uid, -2).$typeadd."_avatar_$size.jpg";
	return $avatarfile;
}
function get_noavatar($size='middle')
{
	global $_G;
	$ucenterurl =  $_G['setting']['ucenterurl'];
	return 	$ucenterurl.'/images/noavatar_'.$size.'.gif';
}
?>