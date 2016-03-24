<?php echo 'DS设计 请支持正版  http://addon.discuz.com/?@5404.developer';exit;?>
<!--{hook/global_footer_mobile}-->
<!--{eval $useragent = strtolower($_SERVER['HTTP_USER_AGENT']);$clienturl = ''}-->
<!--{if strpos($useragent, 'iphone') !== false || strpos($useragent, 'ios') !== false}-->
<!--{eval $clienturl = $_G['cache']['mobileoem_data']['iframeUrl'] ? $_G['cache']['mobileoem_data']['iframeUrl'].'&platform=ios' : 'http://www.discuz.net/mobile.php?platform=ios';}-->
<!--{elseif strpos($useragent, 'android') !== false}-->
<!--{eval $clienturl = $_G['cache']['mobileoem_data']['iframeUrl'] ? $_G['cache']['mobileoem_data']['iframeUrl'].'&platform=android' : 'http://www.discuz.net/mobile.php?platform=android';}-->
<!--{elseif strpos($useragent, 'windows phone') !== false}-->
<!--{eval $clienturl = $_G['cache']['mobileoem_data']['iframeUrl'] ? $_G['cache']['mobileoem_data']['iframeUrl'].'&platform=windowsphone' : 'http://www.discuz.net/mobile.php?platform=windowsphone';}-->
<!--{/if}-->

<div id="mask" style="display:none;"></div>
<!--{if !$nofooter}-->
<div id="fter">
<div class="ful">
<!--{if $_GET['mod'] != 'portal' && $_GET['mod'] != 'forum' && $_GET['mod'] != '' }-->
<a href="javascript:;" onclick="history.go(-1)" class="z" ><img src="{STATICURL}image/mobile/images/icon_back.png" /></a>
<!--{/if}-->

		<a href="javascript:;" style="color:#aaa;">{lang mobile2version}</a>
		<a href="{$_G['setting']['mobile']['nomobileurl']}">{lang nomobiletype}</a> 
        <a onclick="window.scrollTo('0','0')" href="javascript:;">TOP</a>
		<p>&copy; <a href="http://www.yizhigame.cn/" style="color:#aaa;">&#x4E00;&#x6307;</a>&#x7248;&#x6743;&#x6240;&#x6709;</p>
</div>
</div>
<!--{/if}-->
</div>

<div class="pullrefresh" style="display:none;"></div>
</body>
</html>
<!--{eval updatesession();}-->
<!--{if defined('IN_MOBILE')}-->
	<!--{eval output();}-->
<!--{else}-->
	<!--{eval output_preview();}-->
<!--{/if}-->
