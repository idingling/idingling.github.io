<?php echo 'DS设计 请支持正版  http://addon.discuz.com/?@5404.developer';exit;?>
<!--{subtemplate touch/common/header}-->
<!--{if $_G['setting']['mobile']['mobilehotthread'] && $_GET['forumlist'] != 1}-->
	<!--{eval dheader('Location:forum.php?mod=guide&view=hot');exit;}-->
<!--{/if}-->


<!--{if $showvisitclient}-->

<div class="visitclienttip vm" style="display:block;">
	<a href="javascript:;" id="visitclientid" class="btn_download">{lang downloadnow}</a>	
	<p>
		{lang downloadzslttoshareview}
	</p>
</div>
<script type="text/javascript">
	var visitclienthref = getvisitclienthref();
	if(visitclienthref) {
		$('#visitclientid').attr('href', visitclienthref);
		$('.visitclienttip').css('display', 'block');
	}
</script>

<!--{/if}-->



<!--{hook/index_top_mobile}-->
<!-- main forumlist start -->


<!--//版块列表//-->

<!--{loop $catlist $key $cat}-->
<div class="list-a cl dsm_forlist">
<div class="dsm_forlitit title-t subforumshow" href="#sub_forum_$cat[fid]">
	<span class="o"><img src="$_G['style']['tpldir']/touch/images/img/collapsed_<!--{if !$_G[setting][mobile][mobileforumview]}-->yes<!--{else}-->no<!--{/if}-->.png"></span>
	<h3><a href="javascript:;">$cat[name]</a></h3>
	</div>
	<div class="content list-c" >
		<ul class="dsm_forlistli" id="sub_forum_$cat[fid]" class="sub_forum ">
		<!--{loop $cat[forums] $forumid}-->
		<!--{eval $forum=$forumlist[$forumid];}-->
			<li>
            	<figure>
				
            	<!--{if $forum[icon]}-->
					$forum[icon]
				 <!--{else}-->
					<img src="$_G['style']['tpldir']/touch/images/img/forum{if $forum[folder]}_new{/if}.gif" alt="$forum[name]" />
				 <!--{/if}-->
				 
<h3 class="tit"><a href="forum.php?mod=forumdisplay&fid={$forum['fid']}">{$forum[name]}</a></h3>
               		<p class="num">$forum[todayposts]</p>
					
</figure>
            </li>
			
			 
		<!--{/loop}-->
		</ul>
	</div>
</div>
<!--{/loop}-->

<script type="text/javascript">
	(function() {
		<!--{if !$_G[setting][mobile][mobileforumview]}-->
			$('.sub_forum').css('display', 'block');
		<!--{else}-->
			$('.sub_forum').css('display', 'none');
		<!--{/if}-->
		$('.subforumshow').on('click', function() {
			var obj = $(this);
			var subobj = $(obj.attr('href'));
			if(subobj.css('display') == 'none') {
				subobj.css('display', 'block');
				obj.find('img').attr('src', '$_G['style']['tpldir']/touch/images/img/collapsed_yes.png');
			} else {
				subobj.css('display', 'none');
				obj.find('img').attr('src', '$_G['style']['tpldir']/touch/images/img/collapsed_no.png');
			}
		});
	 })();
</script>


<!-- main forumlist end -->
<!--{hook/index_middle_mobile}-->



	<!--{subtemplate touch/dsportal/discuz}-->



<!--{subtemplate touch/common/footer}-->
