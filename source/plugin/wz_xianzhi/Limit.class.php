<?php


if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

class plugin_wz_xianzhi{
	function plugin_wz_xianzhi(){
		global $_G;
$this->cvars=$_G['cache']['plugin']['wz_xianzhi'];
	}
}


class plugin_wz_xianzhi_forum extends plugin_wz_xianzhi {
	function viewthread_bottom_output(){
		global $_G,$postlist;
		//echo '<pre>';
		//print_r($postlist);
		$wxfid = (array)unserialize($_G['cache']['plugin']['wz_xianzhi']['confinefid']);
		$attachments_frist = 1;$out = '';
		$wzad=$_G['cache']['plugin']['wz_xianzhi']['logadtop'];
		$lang_1 = lang('plugin/wz_xianzhi', '1');
		$lang_2 = lang('plugin/wz_xianzhi', '2');
		$lang_3 = lang('plugin/wz_xianzhi', '3');
		$lang_4 = lang('plugin/wz_xianzhi', '4');
		//游客部分开始；
		if(!$_G['uid']){
				if(in_array($_G['fid'],$wxfid)){
					foreach($postlist as $id => $postvalue){
						$message=$postvalue['message'];
						if($postvalue['attachment']){$attachment_ts = $lang_4;}
						if($postvalue['first']==1){
							$ah=$_G['cache']['plugin']['wz_xianzhi']['tophigh'];
						}
						else{
							$ah=$_G['cache']['plugin']['wz_xianzhi']['otherhigh'];
						}
						if($ah==-1){$messageot=$message;}
						$message = '<div style="height:'.$ah.'px; overflow:hidden;">'.$message.'</div><br>'.$wzad.'<br><div class="locked"><EM><STRONG><FONT color=#ff0000>'.$lang_1.'</FONT></STRONG><A  style="text-decoration:none;"'; 
						$message .= 'onclick="showWindow(\'login\', this.href);hideWindow(\'register\');" href="member.php?mod=logging&action=login"><STRONG>'.$lang_2.'</STRONG></A>';
						$message .= '&nbsp;<STRONG><FONT color=#ff0000>|'.$lang_3.'</FONT></STRONG><A style="text-decoration:none;" onclick="showWindow(\'register\', this.href);hideWindow(\'login\');" href="member.php?mod='.$_G['setting']['regname'].'"><STRONG>'.$_G['setting']["reglinkname"].'</STRONG></A><a href="connect.php?mod=login&op=init&referer=index.php&statfrom=login_simple"><img src="static/image/common//qq_login.gif" class="vm" alt="QQ登录" /></a><STRONG><FONT color=#ff0000>'.$attachment_ts.'</FONT></STRONG></EM></div>';
						$postlist[$id]['message']=$message;
						$postlist[$id]['attachment'] = '';
						if($ah==-1){$postlist[$id]['message']=$messageot;}
					}
				}
		}
		return $out;
	}
}

?>