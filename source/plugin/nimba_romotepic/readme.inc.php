<?php
$readme=<<<EOF
<br>
<table border=0 cellspacing=1 cellpadding=5 width=72%>
<tr height=27>
<td align="center" class=p11t bgcolor="eeeecc"><b>远程图片本地化 v1.4.1 For X2.5使用说明</b></td>
</tr>
</table><br>
<table border="0" cellpadding="2" cellspacing="2" width="72%">
<tr><td><span class="p11blk"><ol>
<li>本插件由土著人宁巴开发,<a href="http://www.ailab.cn/" target="_blank">人工智能实验室</a>技术团队 出品（Made By Nimba, Team From ailab.cn ）请尊重开发者版权<br><br>
<li>开发者主页<a href="http://addon.discuz.com/?@1552.developer" target="_blank">http://addon.discuz.com/?@1552.developer</a><br><br>
<li>本插件设计的初衷是当检测到用户发帖中带有远程图片的时候自动下载为本地附件，V1.1增加前台选择项。<br><br>
<li>后台可以设置哪些用户组有权限使用，及哪些板块可以本地化图片，但是由于大量本地化图片会占用相当多的服务器资源，建议各位站长根据自身情况合理使用<br><br>
<li>在使用本插件的过程中，发帖时会有一个下载远程图片的过程，内容提交的速度明显变慢，这个属于正常情况！<br><br>
<li>本插件分为X2.0版和X2.5版，两版本互不兼容，请根据贵站版本选择安装！<br><br>
<li>v1.2版本将提供终身免费使用，如需更多功能，请联系插件作者定制开发！<br><br>
<li>由于各种难以预料的原因，插件在设计和使用上难免会有些细节问题，敬请见谅！获取插件最新动态请和反馈插件问题请至插件交流专版<a href="http://dz.open.ailab.cn/club-60-1.html" target="_blank">http://dz.open.ailab.cn/club-60-1.html</a>发帖咨询，由于工作繁忙，恕不接受免费版用户QQ咨询！<br><br>
<li>【团队招募】<br><span style="padding-left:2em;"/>人工智能实验室开发团队正处在一个快速发展的时期，先需要扩充团队力量，吸收新鲜血液，我们诚招具有以下技能之一的朋友加入我们的团队：1、熟悉PHP程序开发和discuz框架结构，2、精通算法设计或具有扎实的数学基础，3、精通mysql数据库，4、熟悉Linux服务器的搭建和配置，5、具有扎实的美工功底，精通html、DIV+CSS、js！人工智能实验室欢迎心怀梦想的朋友加入我们的团队！
<li>【技术服务】<br><span style="padding-left:2em;"/><a href="http://www.ailab.cn/" target="_blank">人工智能实验室</a>(AiLab Inc.)技术团队承接以下各种论坛外包技术业务：1、插件定制开发，2、程序问题修复，3、论坛搬家，4、数据库修复，5、GBK和UTF-8版本互转，6、论坛升级，7、其他论坛转discuz，8、其他程序与UCenter整合，9、其他个性化需求！
<li>【联系我们】<br><p style="padding-left:2em;">QQ：594941227(土著人宁巴)<br>QQ：281688302（仙剑小虾）<br>交流社区：<a href="http://dz.open.ailab.cn/" target="_blank">http://dz.open.ailab.cn/</a><br>官方网站：<a href="http://www.ailab.cn/" target="_blank">http://www.ailab.cn/</a></p>
</ol></span></td></tr></table>
EOF;
if(strtolower(CHARSET) == 'utf-8') $readme=iconv('GB2312', 'UTF-8',$readme);//utf-8
echo $readme;
?>
