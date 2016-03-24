 <?php echo 'Theme by Time';exit;?>

</div>

<!--{if empty($topic) || ($topic[usefooter])}-->

<!--{eval $focusid = getfocus_rand($_G[basescript]);}-->

<!--{if $focusid !== null}-->

<!--{eval $focus = $_G['cache']['focus']['data'][$focusid];}-->

<!--{eval $focusnum = count($_G['setting']['focus'][$_G[basescript]]);}-->



<div class="focus" id="sitefocus">

  <div class="bm">

    <div class="bm_h cl"> <a href="javascript:;" onclick="setcookie('nofocus_$_G[basescript]', 1, $_G['cache']['focus']['cookie']*3600);$('sitefocus').style.display='none'" class="y" title="{lang close}">{lang close}</a>

      <h2> 

        <!--{if $_G['cache']['focus']['title']}-->{$_G['cache']['focus']['title']}<!--{else}-->{lang focus_hottopics}<!--{/if}--> 

        <span id="focus_ctrl" class="fctrl"><img src="{IMGDIR}/pic_nv_prev.gif" alt="{lang footer_previous}" title="{lang footer_previous}" id="focusprev" class="cur1" onclick="showfocus('prev');" /> <em><span id="focuscur"></span>/$focusnum</em> <img src="{IMGDIR}/pic_nv_next.gif" alt="{lang footer_next}" title="{lang footer_next}" id="focusnext" class="cur1" onclick="showfocus('next')" /></span> </h2>

    </div>

    <div class="bm_c" id="focus_con"> </div>

  </div>

</div>

<!--{eval $focusi = 0;}--> 

<!--{loop $_G['setting']['focus'][$_G[basescript]] $id}-->

<div class="bm_c" style="display: none" id="focus_$focusi">

  <dl class="xld cl bbda">

    <dt><a href="{$_G['cache']['focus']['data'][$id]['url']}" class="xi2" target="_blank">$_G['cache']['focus']['data'][$id]['subject']</a></dt>

    <!--{if $_G['cache']['focus']['data'][$id][image]}-->

    <dd class="m"><a href="{$_G['cache']['focus']['data'][$id]['url']}" target="_blank"><img src="{$_G['cache']['focus']['data'][$id]['image']}" alt="$_G['cache']['focus']['data'][$id]['subject']" /></a></dd>

    <!--{/if}-->

    <dd>$_G['cache']['focus']['data'][$id]['summary']</dd>

  </dl>

  <p class="ptn cl"><a href="{$_G['cache']['focus']['data'][$id]['url']}" class="xi2 y" target="_blank">{lang focus_show} &raquo;</a></p>

</div>

<!--{eval $focusi ++;}--> 

<!--{/loop}--> 

<script type="text/javascript">

			var focusnum = $focusnum;

			if(focusnum < 2) {

				$('focus_ctrl').style.display = 'none';

			}

			if(!$('focuscur').innerHTML) {

				var randomnum = parseInt(Math.round(Math.random() * focusnum));

				$('focuscur').innerHTML = Math.max(1, randomnum);

			}

			showfocus();

			var focusautoshow = window.setInterval('showfocus(\'next\', 1);', 5000);

		</script> 

<!--{/if}--> 

<!--{if $_G['uid'] && $_G['member']['allowadmincp'] == 1 && $_G['setting']['showpatchnotice'] == 1}-->

<div class="focus patch" id="patch_notice"></div>

<!--{/if}--> 



<!--{ad/footerbanner/wp a_f/1}--><!--{ad/footerbanner/wp a_f/2}--><!--{ad/footerbanner/wp a_f/3}--> 

<!--{ad/float/a_fl/1}--><!--{ad/float/a_fr/2}--> 

<!--{ad/couplebanner/a_fl a_cb/1}--><!--{ad/couplebanner/a_fr a_cb/2}--> 

<!--{ad/cornerbanner/a_cn}--> 



<!--{hook/global_footer}-->



<div id="footer" class="footer cl">

  <div id="ft" class="wp cl">

      <div class="footer-left cl">&copy; 2013-2016 <a href="http://www.comsenz.com" target="_blank">Comsenz Inc. </a>  Design by 竹子/丁丁/子明  <a href="http://www.5477dm.com" target="_blank"> 一万光年动漫站</a> <em>5477dm.com</em><!--{if !empty($_G['setting']['boardlicensed'])}--> <a href="http://www.5477dm.com" target="_blank">Licensed</a><!--{/if}--> 

        <!--{if $_G['setting']['icp']}--> 备案：<a href="http://www.miitbeian.gov.cn/" target="_blank">$_G['setting']['icp']</a><!--{/if}--> 

        <!--{hook/global_footerlink}--> 

        <!--{if $_G['setting']['statcode']}-->$_G['setting']['statcode']<!--{/if}--></div>

        <div class="rgt">
        
           <a href="http://www.5477dm.com/ours/" target="_blank" title="">关于我们</a><span></span>

           <a href="http://www.5477dm.com/forum.php?mod=viewthread&tid=3218&extra=page%3D1" target="_blank" title="">免责声明</a><span></span>
           
           <a href="http://www.5477dm.com/misc.php?mod=mobile" target="_blank" title="">手机版</a><span></span>

           <a href="http://www.5477dm.com/sitemap.xml" target="_blank" title="">地图</a>

        </div>

  </div>

<!--{eval updatesession();}--> 

<!--{if $_G['uid'] && $_G['group']['allowinvisible']}--> 

<script type="text/javascript">

			var invisiblestatus = '<!--{if $_G['session']['invisible']}-->{lang login_invisible_mode}<!--{else}-->{lang login_normal_mode}<!--{/if}-->';

			var loginstatusobj = $('loginstatusid');

			if(loginstatusobj != undefined && loginstatusobj != null) loginstatusobj.innerHTML = invisiblestatus;

			</script> 

<!--{/if}--> 



<!--{/if}--> 



<!--{if !$_G['setting']['bbclosed'] && !$_G['member']['freeze'] && !$_G['member']['groupexpiry']}--> 

<!--{if $_G[uid] && !isset($_G['cookie']['checkpm'])}--> 

<script type="text/javascript" src="home.php?mod=spacecp&ac=pm&op=checknewpm&rand=$_G[timestamp]"></script> 

<!--{/if}--> 



<!--{if $_G[uid] && helper_access::check_module('follow') && !isset($_G['cookie']['checkfollow'])}--> 

<script type="text/javascript" src="home.php?mod=spacecp&ac=follow&op=checkfeed&rand=$_G[timestamp]"></script> 

<!--{/if}--> 



<!--{if !isset($_G['cookie']['sendmail'])}--> 

<script type="text/javascript" src="home.php?mod=misc&ac=sendmail&rand=$_G[timestamp]"></script> 

<!--{/if}--> 



<!--{if $_G[uid] && $_G['member']['allowadmincp'] == 1 && !isset($_G['cookie']['checkpatch'])}--> 

<script type="text/javascript" src="misc.php?mod=patch&action=checkpatch&rand=$_G[timestamp]"></script> 

<!--{/if}--> 

<!--{/if}--> 



<!--{if $_GET['diy'] == 'yes'}--> 

<!--{if check_diy_perm($topic) && (empty($do) || $do != 'index')}--> 

<script type="text/javascript" src="{$_G[setting][jspath]}common_diy.js?{VERHASH}"></script> 

<script type="text/javascript" src="{$_G[setting][jspath]}portal_diy{if !check_diy_perm($topic, 'layout')}_data{/if}.js?{VERHASH}"></script> 

<!--{/if}--> 

<!--{if $space['self'] && CURMODULE == 'space' && $do == 'index'}--> 

<script type="text/javascript" src="{$_G[setting][jspath]}common_diy.js?{VERHASH}"></script> 

<script type="text/javascript" src="{$_G[setting][jspath]}space_diy.js?{VERHASH}"></script> 

<!--{/if}--> 

<!--{/if}--> 

<!--{if $_G['uid'] && $_G['member']['allowadmincp'] == 1 && $_G['setting']['showpatchnotice'] == 1}--> 

<script type="text/javascript">patchNotice();</script> 

<!--{/if}--> 

<!--{if $_G['uid'] && $_G['member']['allowadmincp'] == 1 && empty($_G['cookie']['pluginnotice'])}-->

<div class="focus plugin" id="plugin_notice"></div>

<script type="text/javascript">pluginNotice();</script> 

<!--{/if}--> 

<!--{if !$_G['setting']['bbclosed'] && !$_G['member']['freeze'] && !$_G['member']['groupexpiry'] && $_G['setting']['disableipnotice'] != 1 && $_G['uid'] && !empty($_G['cookie']['lip'])}-->

<div class="focus plugin" id="ip_notice"></div>

<script type="text/javascript">ipNotice();</script> 

<!--{/if}--> 

<!--{if $_G['member']['newprompt'] && (empty($_G['cookie']['promptstate_'.$_G[uid]]) || $_G['cookie']['promptstate_'.$_G[uid]] != $_G['member']['newprompt']) && $_GET['do'] != 'notice'}--> 

<script type="text/javascript">noticeTitle();</script> 

<!--{/if}--> 



<!--{if ($_G[member][newpm] || $_G[member][newprompt]) && empty($_G['cookie']['ignore_notice'])}--> 

<script type="text/javascript" src="{$_G[setting][jspath]}html5notification.js?{VERHASH}"></script> 

<script type="text/javascript">

	var h5n = new Html5notification();

	if(h5n.issupport()) {

		<!--{if $_G[member][newpm] && $_GET[do] != 'pm'}-->

		h5n.shownotification('pm', '$_G[siteurl]home.php?mod=space&do=pm', '<!--{avatar($_G[uid],small,true)}-->', '{lang newpm_subject}', '{lang newpm_notice_info}');

		<!--{/if}-->

		<!--{if $_G[member][newprompt] && $_GET[do] != 'notice'}-->

				<!--{loop $_G['member']['category_num'] $key $val}-->

					<!--{eval $noticetitle = lang('template', 'notice_'.$key);}-->

					h5n.shownotification('notice_$key', '$_G[siteurl]home.php?mod=space&do=notice&view=$key', '<!--{avatar($_G[uid],small,true)}-->', '$noticetitle ($val)', '{lang newnotice_notice_info}');

				<!--{/loop}-->

		<!--{/if}-->

	}

	</script> 

<!--{/if}--> 

<!--{eval userappprompt();}--> 

<!--{if $_G['basescript'] != 'userapp'}-->

<div id="share">


</div>

<script type="text/javascript">

jQuery.noConflict();

jQuery(function(){

        //首先将#back-to-top隐藏

        jQuery("#totop").hide();

        //当滚动条的位置处于距顶部100像素以下时，跳转链接出现，否则消失

        jQuery(function () {

            jQuery(window).scroll(function(){

                if (jQuery(window).scrollTop()>100){

                    jQuery("#totop").fadeIn();

                }

                else

                {

                    jQuery("#totop").fadeOut();

                }

            });

            //当点击跳转链接后，回到页面顶部位置

            jQuery("#totop").click(function(){

                jQuery('body,html').animate({scrollTop:0},500);

                return false;

            });

        });

    }); 

</script>

<!--{/if}--> 

<!--{if isset($_G['makehtml'])}--> 

<script type="text/javascript" src="{$_G[setting][jspath]}html2dynamic.js?{VERHASH}"></script> 

<script type="text/javascript">

		var html_lostmodify = {TIMESTAMP};

		htmlGetUserStatus();

		<!--{if isset($_G['htmlcheckupdate'])}-->

		htmlCheckUpdate();

		<!--{/if}-->

	</script> 

<!--{/if}--> 

<!--{eval output();}-->

</div>

</div>

</body></html>