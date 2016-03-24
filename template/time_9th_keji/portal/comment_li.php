<?php echo 'Theme by Time';exit;?>
<li> <a name="comment_anchor_$comment[cid]"></a>
  <dl id="comment_{$comment[cid]}_li" class="ptm pbm cl">
    <dt class="mbm">
      <div class="portrait"> <a href="home.php?mod=space&uid=$comment[uid]" c="1"><!--{avatar($comment[uid],small)}--></a> </div>
      <!--{if !empty($comment['uid'])}--> 
      <a href="home.php?mod=space&uid=$comment[uid]" class="username">$comment[username]</a> 
      <!--{else}--> 
      {lang guest} 
      <!--{/if}--> 
      <span class="xg1 xw0"><!--{date($comment[dateline])}--></span> 
      <span class="y"> 
      <!--{if !isset($_G[makehtml])}--><a href="javascript:;" onclick="portal_comment_requote($comment[cid], '$article[aid]');">{lang quote}</a> <!--{/if}--> 
      <!--{if ($_G['group']['allowmanagearticle'] || $_G['uid'] == $comment['uid']) && $_G['groupid'] != 7 && !$article['idtype']}--> 
      <a href="portal.php?mod=portalcp&ac=comment&op=edit&cid=$comment[cid]" id="c_$comment[cid]_edit" onclick="showWindow(this.id, this.href, 'get', 0);">{lang edit}</a> <a href="portal.php?mod=portalcp&ac=comment&op=delete&cid=$comment[cid]" id="c_$comment[cid]_delete" onclick="showWindow(this.id, this.href, 'get', 0);">{lang delete}</a> 
      <!--{/if}--> 
      </span>
      <!--{if $comment[status] == 1}--><b>({lang moderate_need})</b><!--{/if}--> 
    </dt>
    <dd><!--{if $_G[adminid] == 1 || $comment[uid] == $_G[uid] || $comment[status] != 1}-->$comment[message]<!--{else}--> {lang moderate_not_validate}<!--{/if}--></dd>
  </dl>
</li>
