<?php
/*
	[Discuz!] (C)2001-2009 Comsenz Inc.
	This is NOT a freeware, use is subject to license terms
 */

if(!defined('IN_DISCUZ')) {
       exit('Access Denied'); 
}

class plugin_freeaddon_duoshuo {
	
}

class plugin_freeaddon_duoshuo_forum extends plugin_freeaddon_duoshuo{
	function viewthread_postbottom_output(){
		global $_G,$postlist,$allowpostreply,$multipage,$fastpost;
		$rerurn = array();
		//��ȡ�������
		$freeaddon_duoshuo = $_G['cache']['plugin']['freeaddon_duoshuo'];
		$freeaddon_fids_show = (array)unserialize($freeaddon_duoshuo['freeaddon_fids']);
		if(in_array($_G['fid'],$freeaddon_fids_show)){
			$freeaddon_duoshuoid = $freeaddon_duoshuo['freeaddon_duoshuoid'] ? $freeaddon_duoshuo['freeaddon_duoshuoid'] : '1588426';
			include template('freeaddon_duoshuo:duoshuo');
			$rerurn[] = $freeaddon_duoshuo_rerurn;
			if($freeaddon_duoshuo['freeaddon_hidereplay']){
				//�رտ��ٻظ�
				$_G['setting']['fastpost'] = $fastpost = '0';
				//�ر������±ߵĻظ�
				$allowpostreply = '0';
				
			}
			if($freeaddon_duoshuo['freeaddon_hidemultipage']){
				//���ط�ҳ
				$multipage = '';
			}
			if($freeaddon_duoshuo['freeaddon_hidereplaycontent']){
				//����discuz�Ļ�������
				$postfirst = array_slice($postlist, 0, 1);
				$postlist = array();
				$postlist = $postfirst;
			}
		}
		return $rerurn;
	}
}
?>