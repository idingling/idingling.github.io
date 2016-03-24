<?php echo 'DS设计 请支持正版  http://addon.discuz.com/?@5404.developer';exit;?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312">
<meta http-equiv="Cache-control" content="{if $_G['setting']['mobile'][mobilecachetime] > 0}{$_G['setting']['mobile'][mobilecachetime]}{else}no-cache{/if}" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">
<meta name="format-detection" content="telephone=no" />
<meta name="keywords" content="{if !empty($metakeywords)}{echo dhtmlspecialchars($metakeywords)}{/if}" />
<meta name="description" content="{if !empty($metadescription)}{echo dhtmlspecialchars($metadescription)} {/if},$_G['setting']['bbname']" />
<!--{if $_G['basescript'] == 'portal' && CURMODULE=='list'}--><base href="{$_G['siteurl']}" /><!--{/if}-->
<title><!--{if !empty($navtitle)}-->$navtitle - <!--{/if}--><!--{if empty($nobbname)}--> $_G['setting']['bbname'] - <!--{/if}--> {lang waptitle} - Powered by Discuz!</title>
<link rel="stylesheet" href="{STATICURL}image/mobile/style.css" type="text/css" media="all">
<script src="{STATICURL}js/mobile/jquery-1.8.3.min.js?{VERHASH}"></script>

<script type="text/javascript">var STYLEID = '{STYLEID}', STATICURL = '{STATICURL}', IMGDIR = '{IMGDIR}', VERHASH = '{VERHASH}', charset = '{CHARSET}', discuz_uid = '$_G[uid]', cookiepre = '{$_G[config][cookie][cookiepre]}', cookiedomain = '{$_G[config][cookie][cookiedomain]}', cookiepath = '{$_G[config][cookie][cookiepath]}', showusercard = '{$_G[setting][showusercard]}', attackevasive = '{$_G[config][security][attackevasive]}', disallowfloat = '{$_G[setting][disallowfloat]}', creditnotice = '<!--{if $_G['setting']['creditnotice']}-->$_G['setting']['creditnames']<!--{/if}-->', defaultstyle = '$_G[style][defaultextstyle]', REPORTURL = '$_G[currenturl_encode]', SITEURL = '$_G[siteurl]', JSPATH = '$_G[setting][jspath]';</script>

<script src="{STATICURL}js/mobile/common.js?{VERHASH}" charset="{CHARSET}"></script>
<link rel="stylesheet" href="$_G['style']['tpldir']/touch/images/style.css" type="text/css">


</head>

<body {if $_G['basescript'] == 'home' && $_GET['mod'] == 'space' && $_GET['do'] == 'notice'}id="zds_hnotice"{/if}>

<div id="dsm_wp">





<div id="dsm_content" class="cl dsm_content">
<div id="hd">
	<div class="header-logo">
	<!--{if CURMODULE=='viewthread' || CURMODULE=='view'  || CURMODULE=='forumdisplay'  || CURMODULE=='post'  || CURMODULE=='misc'}-->
	<a href="javascript:;" onclick="history.go(-1)" style="line-height: 43px;" class="dsm_ttui" />&nbsp;</a>
	<!--{else}-->
		<a href="{$_G[siteurl]}"><img src="$_G['style']['tpldir']/touch/images/img/logo.png" width="74" height="35" /></a>
		<!--{/if}-->
	</div>
	<div class="header-title dsmnv_toptit">
		<span>
		
		<!--{if $_G['basescript'] == 'forum' && $_GET['mod'] == ''}-->
		&#x8BBA;&#x575B;
		<!--{elseif $_G['basescript'] == 'forum' && $_GET['mod'] == 'viewthread'}-->
		&#x5E16;&#x5B50;&#x8BE6;&#x60C5;
		<!--{elseif  $_G['basescript'] == 'forum' && $_GET['mod'] == 'forumdisplay'}-->
		{$_G['forum'][name]}
		<!--{elseif  $_G['basescript'] == 'forum' && $_GET['mod'] == 'guide'}-->
		&#x5BFC;&#x8BFB;
		<!--{else}-->
		$navtitle
		<!--{/if}--></span>
		
	</div>
	<div style="width: 80px;">
		<a href="search.php?mod=forum&mobile=2" class="so">&nbsp;</a>
		
		<div class="y user" id="dsm_side_pr">
		<!--{if $_G['basescript'] == 'forum' && $_GET['mod'] == 'forumdisplay'}-->
		<a  href="forum.php?mod=post&action=newthread&fid=$_G[fid]" title="{lang send_threads}  class="pic_no" style="display: block;
width: 28px;
height: 28px;
margin-top: 5px;
background: url(static/image/mobile/images/icon.png) no-repeat;
line-height: 2000px;
overflow: hidden;
background-position: -84px 0;"><span class="">
		&nbsp;</span></a>
		
		<!--{else}-->
		<a href="home.php?mod=space&uid={$_G[uid]}" id="dsm_side_open" class="pic"><!--{if $_G[member][newpm] || $_G[member][newprompt] || $_G[member][newprompt_num][follower] || $_G[member][newprompt_num][follow]  }--><span class="pm"></span><!--{/if}--><!--{avatar($_G[uid],small)}--></a>
		
		
		<!--{/if}-->
		
		</div>
		
	</div>
</div>




<script type="text/javascript">

function mys(id){
return !id ? null : document.getElementById(id);
}

function dbox(id,classname){
if(mys(id+'_menu').style.display =='block'){
mys(id+'_menu').style.display ='none'
mys(id).className = '';
}else{
mys(id+'_menu').style.display ='block'
mys(id).className = classname;
}
}

if(window.onload!=null){   
    window.onload=function(){auto_height();};  
}
</script>

<div class="dsm_tfixheight">&nbsp;</div>

<div id="nv" class="cl">
	<a {if $_G['basescript'] == 'forum' && $_GET['mod'] == ''}class="a"{/if} href="forum.php">{lang mobilehome}</a>


<a {if $_GET['mod'] == 'guide'}class="a"{/if} href="forum.php?mod=guide">&#x5BFC;&#x8BFB;</a>
<a {if $_G['basescript'] == 'forum' && $_GET['mod'] == 'forum'}class="a"{/if} href="home.php?mod=space&do=favorite&view=me">{lang favorite}</a>
<a {if $_G['basescript'] == 'search' }class="a"{/if} href="home.php?mod=space&do=pm">&#x6D88;&#x606F;</a>

<a {if $_G['basescript'] == 'forum' && $_GET['mod'] == '9gonge'}class="a"{/if} href="forum.php?mod=guide&view=my">wo&#x5E16;&#x5B50;</a>

</div>

<div id="hd_bot"></div>

