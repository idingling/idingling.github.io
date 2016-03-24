<?php

if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}
$_D['IFRAME']='http://www.cnit618.com/dz_plugin/it618_plugin.asp?ids='.$_GET['identifier']."&url=".$_G['siteurl'];
showtableheader();
	echo '<tr><td>
		<iframe src="'.$_D['IFRAME'].'" style="width:800px; height:500px; border:0;" frameborder=0 height=100%></iframe>
		</td></tr>';
showtablefooter();

?>