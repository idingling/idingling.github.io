<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

class plugin_nimba_romotepic {
}

class plugin_nimba_romotepic_forum extends plugin_nimba_romotepic{
	function post_middle_output(){//��������ҳ,�ظ�ҳ
		global $_G;
		loadcache('plugin');
		$var = $_G['cache']['plugin']['nimba_romotepic'];
		$open=$var['open'];
		$selection=$var['selection'];
		$forum=unserialize($var['forum']);
		$group=unserialize($var['group']);
		if($open==1&&in_array($_G['groupid'],$group)&&in_array($_G['fid'],$forum)){
		    $return = '';
		    if(!$_G['uid']){
			    return $return;
		    }
		    $tips='���ػ�Զ��ͼƬ';
			if(strtolower(CHARSET) == 'utf-8') $tips=iconv('GB2312', 'UTF-8',$tips);//utf-8
		    include template('nimba_romotepic:post_newthread');
		    return $return;
		}
	}
	
	function forumdisplay_fastpost_btn_extra_output(){//�б�ҳ���ٷ���
		global $_G;
		loadcache('plugin');
		$var = $_G['cache']['plugin']['nimba_romotepic'];
		$open=$var['open'];
		$selection=$var['selection'];
		$forum=unserialize($var['forum']);
		$group=unserialize($var['group']);
		if($open==1&&in_array($_G['groupid'],$group)&&in_array($_G['fid'],$forum)){
		    $return = '';
		    if(!$_G['uid']){
			    return $return;
		    }
		    $tips='���ػ�Զ��ͼƬ';
			if(strtolower(CHARSET) == 'utf-8') $tips=iconv('GB2312', 'UTF-8',$tips);//utf-8
		    include template('nimba_romotepic:post_newthread');
		    return $return;
		}
	}

	function viewthread_fastpost_btn_extra(){//����ҳ���ٻظ�
		global $_G;
		loadcache('plugin');
		$var = $_G['cache']['plugin']['nimba_romotepic'];
		$open=$var['open'];
		$selection=$var['selection'];
		$forum=unserialize($var['forum']);
		$group=unserialize($var['group']);
		if($open==1&&in_array($_G['groupid'],$group)&&in_array($_G['fid'],$forum)){
		    $return = '';
		    if(!$_G['uid']){//�б�ҳ���ٷ���
			    return $return;
		    }
		    $tips='���ػ�Զ��ͼƬ';
			if(strtolower(CHARSET) == 'utf-8') $tips=iconv('GB2312', 'UTF-8',$tips);//utf-8
		    include template('nimba_romotepic:post_newthread');
		    return $return;
		}
	}

	
    function post_getromotepic(){
	    global $_G;
		loadcache('plugin');
		$var = $_G['cache']['plugin']['nimba_romotepic'];
		$open=$var['open'];
		$selection=$var['selection'];
		$down=$_GET['romotepic'];		
		$siteurl=empty($var['siteurl'])? $_G['siteurl']:trim($var['siteurl']);
		$minsize=$var['minsize']*1024;//ͼƬ��С�ߴ�
		$forum=unserialize($var['forum']);
		$group=unserialize($var['group']);
		if($down==1&&$open==1&&in_array($_G['groupid'],$group)&&in_array($_G['fid'],$forum)){
		   if(preg_match_all("/\[img[^\]]*\](.*)\[\/img\]/isU",$_GET['message'],$result)){//ƥ��[img]
			    foreach ($result[1] as $key=>$src){
				    $src=trim($src);
				    if((stripos($src,$siteurl)==true)||substr($src,0,7)!='http://') continue;//stripos($src,$siteurl)==true���˱�վͼƬ
					$romote=get_headers($src,true);
					$picsize=$romote['Content-Length'];//ͼƬ��С �ж�ȡ��ο�
				    if($picsize>=$minsize){//��СͼƬ���˵�
					    $ym=date('Ym');
						$d=date('d');
						$his=date('His');
						require_once libfile('class/image');
						$romoteimage = new image;
				        $this->checkattachdir($ym,$d);
					    $localattachment=$ym."/".$d."/".$his.strtolower(random(16)).".jpg";
					    $localurl=$_G['setting']['attachdir']."/forum/".$localattachment;//���·��
					    $attachsaved=$this->downloadpic($src,$localurl);
					    if($attachsaved){
						    $watermarkstatus=unserialize($_G['setting']['watermarkstatus']);
						    if($watermarkstatus['forum'] && empty($_G['forum']['disablewatermark'])) {
							    $romoteimage->Watermark($localurl);
						    }
                            //��֯����							
						    $width=$attachsaved;
						    $path_parts = pathinfo($src);
						    $picname=$path_parts['filename'];
						    $filename=$picname.'.jpg';//ԭʼ�ļ���
						    $filesize=filesize($localurl);
						    $isimage=1;
							$remote=0;
							if(!$remote){
						    $thumb = $romoteimage->Thumb($localurl, '', $_G['setting']['thumbwidth'], $_G['setting']['thumbheight'], $_G['setting']['thumbstatus'], $_G['setting']['thumbsource']) ? 1 : 0;
							if(!$_G['setting']['thumbsource']) $width = $romoteimage->imginfo['width'];
							}

							//���븽��
						    $aid=C::t('forum_attachment')->insert(array('aid'=>NULL,'tid'=>'0','pid'=>'0','uid'=>$_G['uid'],'tableid'=>'127','downloads'=>'0'),true);
						    C::t('forum_attachment_unused')->insert(array('aid'=>$aid,'uid'=>$_G['uid'],'dateline'=>$_G['timestamp'],'filename'=>$filename,'filesize'=>$filesize,'attachment'=>$localattachment,'remote'=>$remote,'isimage'=>$isimage,'width'=>$width,'thumb'=>$thumb));
						    $_GET['attachnew'][$aid]=array();
							$strfirst = strpos($_GET['message'],$result[0][$key]);
							if ($strfirst === false){
							}else $_GET['message']=substr_replace($_GET['message'],'[attachimg]'.$aid.'[/attachimg]', $strfirst, strlen($result[0][$key]));
						
					    }				
				    }
			    }			
			
		    }
		}//Ȩ�޿���
	}//function getromotepic	
	function checkattachdir($ym,$d) {
	    global $_G;
	    if(!is_dir($_G['setting']['attachdir']."/forum/".$ym."/")) mkdir($_G['setting']['attachdir']."/forum/".$ym."/");
		if(!is_dir($_G['setting']['attachdir']."/forum/".$ym."/".$d."/")) mkdir($_G['setting']['attachdir']."/forum/".$ym."/".$d."/");
	}
	
	function downloadpic($src,$localurl){
	    if(file_exists($localurl)||copy($src,$localurl)){
    	    }elseif ($content=file_get_contents($src)){
		        $fp=fopen($localurl,"w");
		        fwrite($fp,$content);
		        fclose($fp);
	        }
	    $size=getimagesize($localurl);
	    if(!$size[0]||!$size[2]){
		    unlink($localurl);
		    return false;
	    }else return $size[0];
	    return false;
    }
}
class plugin_nimba_romotepic_group extends plugin_nimba_romotepic_forum{
	function post_middle_output(){//��������ҳ,�ظ�ҳ
		global $_G;
		loadcache('plugin');
		$var = $_G['cache']['plugin']['nimba_romotepic'];
		$open=$var['open'];
		$selection=$var['selection'];
		$forum=unserialize($var['forum']);
		$group=unserialize($var['group']);
		if($open==1&&in_array($_G['groupid'],$group)){
		    $return = '';
		    if(!$_G['uid']){
			    return $return;
		    }
		    $tips='���ػ�Զ��ͼƬ';
			if(strtolower(CHARSET) == 'utf-8') $tips=iconv('GB2312', 'UTF-8',$tips);//utf-8
		    include template('nimba_romotepic:post_newthread');
		    return $return;
		}
	}
	
	function forumdisplay_fastpost_btn_extra_output(){//�б�ҳ���ٷ���
		global $_G;
		loadcache('plugin');
		$var = $_G['cache']['plugin']['nimba_romotepic'];
		$open=$var['open'];
		$selection=$var['selection'];
		$forum=unserialize($var['forum']);
		$group=unserialize($var['group']);
		if($open==1&&in_array($_G['groupid'],$group)){
		    $return = '';
		    if(!$_G['uid']){
			    return $return;
		    }
		    $tips='���ػ�Զ��ͼƬ';
			if(strtolower(CHARSET) == 'utf-8') $tips=iconv('GB2312', 'UTF-8',$tips);//utf-8
		    include template('nimba_romotepic:post_newthread');
		    return $return;
		}
	}

	function viewthread_fastpost_btn_extra(){//����ҳ���ٻظ�
		global $_G;
		loadcache('plugin');
		$var = $_G['cache']['plugin']['nimba_romotepic'];
		$open=$var['open'];
		$selection=$var['selection'];
		$forum=unserialize($var['forum']);
		$group=unserialize($var['group']);
		if($open==1&&in_array($_G['groupid'],$group)){
		    $return = '';
		    if(!$_G['uid']){//�б�ҳ���ٷ���
			    return $return;
		    }
		    $tips='���ػ�Զ��ͼƬ';
			if(strtolower(CHARSET) == 'utf-8') $tips=iconv('GB2312', 'UTF-8',$tips);//utf-8
		    include template('nimba_romotepic:post_newthread');
		    return $return;
		}
	}
	
    function post_getromotepic(){
	    global $_G;
		$down=$_GET['romotepic'];
		loadcache('plugin');
		$var = $_G['cache']['plugin']['nimba_romotepic'];
		$open=$var['open'];
		$selection=$var['selection'];
		$siteurl=empty($var['siteurl'])? $_G['siteurl']:trim($var['siteurl']);
		$minsize=$var['minsize']*1024;//ͼƬ��С�ߴ�
		$forum=unserialize($var['forum']);
		$group=unserialize($var['group']);
		if($down==1&&$open==1&&in_array($_G['groupid'],$group)){
		   if(preg_match_all("/\[img[^\]]*\](.*)\[\/img\]/isU",$_GET['message'],$result)){//ƥ��[img]
			    foreach ($result[1] as $key=>$src){
				    $src=trim($src);
				    if((stripos($src,$siteurl)==true)||substr($src,0,7)!='http://') continue;//stripos($src,$siteurl)==true���˱�վͼƬ
					$romote=get_headers($src,true);
					$picsize=$romote['Content-Length'];//ͼƬ��С �ж�ȡ��ο�
				    if($picsize>=$minsize){//��СͼƬ���˵�
					    $ym=date('Ym');
						$d=date('d');
						$his=date('His');
						require_once libfile('class/image');
						$romoteimage = new image;
				        $this->checkattachdir($ym,$d);
					    $localattachment=$ym."/".$d."/".$his.strtolower(random(16)).".jpg";
					    $localurl=$_G['setting']['attachdir']."/forum/".$localattachment;//���·��
					    $attachsaved=$this->downloadpic($src,$localurl);
					    if($attachsaved){
						    $watermarkstatus=unserialize($_G['setting']['watermarkstatus']);
						    if($watermarkstatus['forum'] && empty($_G['forum']['disablewatermark'])) {
							    $romoteimage->Watermark($localurl);
						    }
                            //��֯����							
						    $width=$attachsaved;
						    $path_parts = pathinfo($src);
						    $picname=$path_parts['filename'];
						    $filename=$picname.'.jpg';//ԭʼ�ļ���
						    $filesize=filesize($localurl);
						    $isimage=1;
							$remote=0;
							if(!$remote){
						    $thumb = $romoteimage->Thumb($localurl, '', $_G['setting']['thumbwidth'], $_G['setting']['thumbheight'], $_G['setting']['thumbstatus'], $_G['setting']['thumbsource']) ? 1 : 0;
							if(!$_G['setting']['thumbsource']) $width = $romoteimage->imginfo['width'];
							}

							//���븽��
						    $aid=C::t('forum_attachment')->insert(array('aid'=>NULL,'tid'=>'0','pid'=>'0','uid'=>$_G['uid'],'tableid'=>'127','downloads'=>'0'),true);
						    C::t('forum_attachment_unused')->insert(array('aid'=>$aid,'uid'=>$_G['uid'],'dateline'=>$_G['timestamp'],'filename'=>$filename,'filesize'=>$filesize,'attachment'=>$localattachment,'remote'=>$remote,'isimage'=>$isimage,'width'=>$width,'thumb'=>$thumb));
						    $_GET['attachnew'][$aid]=array();
							$strfirst = strpos($_GET['message'],$result[0][$key]);
							if ($strfirst === false){
							}else $_GET['message']=substr_replace($_GET['message'],'[attachimg]'.$aid.'[/attachimg]', $strfirst, strlen($result[0][$key]));
						
					    }				
				    }
			    }			
			
		    }
		}//Ȩ�޿���
	}//function getromotepic	
}
?>