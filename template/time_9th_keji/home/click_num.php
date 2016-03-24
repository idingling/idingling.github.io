<?php echo 'Theme by Time';exit;?>
<!--{eval $clicknum = 0;}-->
<!--{loop $clicks $key $value}-->
<!--{eval $clicknum = $clicknum + $value['clicknum'];}-->

<a class="like" href="home.php?mod=spacecp&ac=click&op=add&clickid=$key&idtype=$idtype&id=$id&hash=$hash&handlekey=clickhandle" id="click_{$idtype}_{$id}_{$key}" onclick="showWindow(this.id, this.href, 'get', 0);"> <span>$value[name]</span> 
<span id="like-num">{$value[clicknum]}</span> 
</a> 

<!--{/loop}--> 

<script type="text/javascript">
function errorhandle_clickhandle(message, values) {
if(values['id']) {
  showCreditPrompt();
  $('like-num').innerHTML = parseInt($('like-num').innerHTML) + 1;
  ajaxget('home.php?mod=spacecp&ac=click&op=show&clickid='+values['clickid']+'&idtype='+values['idtype']+'&id='+values['id'], 'click_div');
}
}
</script>