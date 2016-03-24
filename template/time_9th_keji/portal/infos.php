<?php echo 'Theme by Time';exit;?>
       
<div class="infos">
    <!--{template home/click_num}--> 
    <a class="comment" href="portal.php?mod=view&aid=$article[aid]#comment">
        <span>评论</span>
        <span>$article[commentnum]</span>
    </a>    
    <a class="collect" href="home.php?mod=spacecp&ac=favorite&type=article&id=$article[aid]&handlekey=favoritearticlehk_{$article[aid]}" id="a_favorite" onclick="showWindow(this.id, this.href, 'get', 0);">
        <span>{lang favorite}</span>
        <span>$article[favtimes]</span>
    </a>
    <!--{if helper_access::check_module('share')}--> 
    <a href="home.php?mod=spacecp&ac=share&type=article&id=$article[aid]&handlekey=sharearticlehk_{$article[aid]}" id="a_share" onclick="showWindow(this.id, this.href, 'get', 0);" class="share">
        <span>{lang share}</span>
        <span>$article[sharetimes]</span>
    </a>
    <!--{/if}--> 
    <a href="javascript:void(0)" class="recommend bds_more bdsharebuttonbox" data-cmd="more">
        <span>分享</span>
        <span>此文</span>
    </a>
    <script>window._bd_share_config={"common":{"bdSnsKey":{},"bdText":"","bdMini":"1","bdMiniList":false,"bdPic":"","bdStyle":"0","bdSize":"16"},"share":{}};with(document)0[(getElementsByTagName('head')[0]||body).appendChild(createElement('script')).src='http://bdimg.share.baidu.com/static/api/js/share.js?v=89860593.js?cdnversion='+~(-new Date()/36e5)];</script>
</div>
    
<script type="text/javascript">
(function() {
    jQuery(window).scroll(function() {
        if (jQuery(window).scrollTop() > 100) {
            jQuery('.infos').fadeIn();
        } else if (jQuery(window).scrollTop() < 100) {
            jQuery('.infos').fadeOut();
        }
    });
    jQuery(".infos").hover(function() {
        jQuery(this).addClass("hover");
    },
    function() {
        jQuery(this).removeClass("hover");
    })

})();


</script>

