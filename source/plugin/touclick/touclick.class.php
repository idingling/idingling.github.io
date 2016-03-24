<?php


if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

class plugin_touclick {
	var $identifier = 'touclick';
	var $version = 'v3.2';
	var $cvar;
	var $secallow=false;
	var $secmod=array();
	var $config=array();
	public function plugin_touclick(){
		global $_G;
		
		$this->config = @include DISCUZ_ROOT.'./source/plugin/touclick/conf.php';
        
        $this->cvar= $this->config['baseset'];
        
		if(in_array($_G['groupid'], $this->config['seccode']['groupid'])&&$this->config['seccode']['open']&&$this->config['active']){
			$this->secallow=true;
		}

		$this->secmod = $this->config['seccode']['mod'];
	
		if($this->secallow){
			if($this->cvar['pubkey'] == $this->cvar['prikey'] || strlen($this->cvar['pubkey']) != 36 || strlen($this->cvar['prikey']) != 36){
				$this->secallow=false;
			}
		}
	}

	public function _check(){
		global $_G;
		$charset = (CHARSET=="utf-8"?"UTF8":CHARSET);
		C::import('touclick_seccode','plugin/touclick/class',false);
		$_obj='touclick_seccode';
		if (class_exists($_obj)){
			$obj = new $_obj();
			$set = array(
				'pubkey'=>$this->cvar['pubkey'],
				'prikey'=>$this->cvar['prikey'],
				'chkkey'=>getcookie('check_key'),
				'chkurl'=>getcookie('check_address'),
				'ip'=>$_G['clientip'],
				'username'=>diconv($_G['username'], CHARSET, "UTF-8"),
				'uid'=>$_G['uid'],
			);
			$this->log('_check  $set["chkkey"] = '.$set['chkkey'].' ; $set["chkurl"] = '.$set['chkurl']);
			$obj->set($set);
            $resRep;
			$back=$obj->execute($resRep);
            $this->log($resRep);
			if($back===true||$back===false){
				return true;
			}
		}
	}
	public function _show($handle,$codeid=0){
        $this->log('_show  $handle = '.$handle);
		global $_G;
		$pre = $_G['config']['cookie']['cookiepre'];
		include template('common/header_ajax');
		if(!$codeid){
			switch($handle){
				case 'postform':
				case 'fastnewpost':
				case 'vfastpost':
				case 'fastpost':
				case 'vfastpostform':
				case 'fastpostform':
					$codeid='11';
					break;
				case 'lsform':
				case (substr($handle,0,9)=='loginform'):
					$codeid='10';
					break;
				case 'registerform':
					$codeid='14';
					break;
				case 'commentform':
					$codeid='15';
					break;
				default:
					$codeid='99';
			}
		}
		echo '<script type="text/javascript" reload="1">if(typeof succeedhandle_touclick==\'function\') {succeedhandle_touclick(\''.$handle.'\','.$codeid.');}</script>';
		include template('common/footer_ajax');
		dexit();
	}
	public function clearcookie(){
		global $_G;
		$pre = $_G['config']['cookie']['cookiepre'];
		setcookie($pre.'check_key');
		setcookie($pre.'check_address');
        
	}
    public function global_footer(){
        global $_G;
        
        if(!$this->config['seccode']['open']||!$this->config['active']){
            return;
        }
        
        $show = 0;
        
        if(!$_G['uid']&&in_array('login',$this->secmod)){
		    $show=1;
	    }elseif(!$_G['uid']&&in_array('reg',$this->secmod)&&$_G['setting']['regname']==$_GET['mod']&&CURSCRIPT=='member'){
		    $show=1;
        }elseif(!$this->secallow){
             return;//ÓÃ»§×é
        }elseif(CURSCRIPT=='forum'&&in_array(CURMODULE,array('post','viewthread','forumdisplay'))&&(in_array('newthread',$this->secmod)||in_array('reply',$this->secmod)||in_array('edit',$this->secmod))){
		    $show=1;
        }
        
		$pre = $_G['config']['cookie']['cookiepre'];
		if($show){
			return '<script src="http://js.touclick.com/js.touclick?b='.$this->cvar['pubkey'].'&v=v3-0&pf=discuz" type="text/javascript"></script><script type="text/javascript">var touclick_clientname=\''.($_G['username']?$_G['username']:'').'\';var touclick_clientid='.$_G['uid'].';document.cookie="'.$pre.'check_key=";document.cookie="'.$pre.'check_address=";function succeedhandle_touclick(handle,codeid){var the_form = $(handle);var oldsubmit = the_form.onsubmit;window.TouClick.Start({website_key: \''.$this->cvar['pubkey'].'\',position_code: codeid,args: {\'_this\': the_form },onSuccess: function (args, check_obj){document.cookie="'.$pre.'check_key="+check_obj.check_key;document.cookie="'.$pre.'check_address="+check_obj.check_address;postpt=0;oldsubmit.call(args._this);}});}</script>';
	    }
    }
    
	public function log($word='') {
          if($this->config['isdebug']){
                if($fp = @fopen(DISCUZ_ROOT.'./source/plugin/touclick/log.txt',"a")){
					flock($fp, LOCK_EX) ;
					fwrite($fp,date("Y-m-d H:i:s")."\t\t".$word."\n");
					flock($fp, LOCK_UN);
					fclose($fp);
				}
			}
    }
}
class plugin_touclick_forum extends plugin_touclick {
    ///´ýÐÞ¸´
	function viewthread_bottom_output(){
		global $_G,$allowfastpost;
		$js='';
        if(!$this->secallow){
			return;
		}
        $pre = $_G['config']['cookie']['cookiepre'];
		if(in_array('reply',$this->secmod)){
		    if($_G['setting']['version']=='X3'){
			    $js = 'var old_seditor_ctlent = seditor_ctlent;
                       seditor_ctlent = function (event, js_code) {
	                        if(event.ctrlKey && event.keyCode == 13 || event.altKey && event.keyCode == 83) {
		                        if(js_code==\'$(\\\'vfastpostform\\\').submit()\'){
			                        ajaxpost(\'vfastpostform\', \'return_reply\', \'return_reply\', \'onerror\');
			                        return;
		                        }
	                        }
                              old_seditor_ctlent.call(this,event,js_code);
                        };';
		    }
            if($allowfastpost){
		        $js .= ' var touclickfastpostflag = 0;
                        if(typeof fastpostvalidateextra != \'function\') {
	                        function fastpostvalidateextra(){
		                        return touclickfastpost();
	                        }
                        }
                        function touclickfastpost(){
	                        if(getcookie(\'check_key\')&&getcookie(\'check_address\')&&touclickfastpostflag==1){
		                        return true;
	                        }else{
		                        window.TouClick.Start({website_key: \''.$this->cvar['pubkey'].'\',position_code: 11,args: {},onSuccess: function (args, check_obj){document.cookie="'.$pre.'check_key="+check_obj.check_key;document.cookie="'.$pre.'check_address="+check_obj.check_address;touclickfastpostflag=1;postpt=0;fastpostvalidate($(\'fastpostform\'));}});
			                        return false;
	                        }
                        } ';
             }else{
                 dsetcookie('tou_not_allow', authcode($this->cvar['pubkey'].'_'.time(), 'ENCODE'), 0, 1, true);
             }
        }
		return '<script type="text/javascript">'.$js.'</script>';
	}
	function post_bottom_output(){
		global $_G;
	
        if(!$this->secallow){
			return;
		}
        
		if(!in_array($_GET['action'],$this->secmod))
			return;
		$pre = $_G['config']['cookie']['cookiepre'];
		return '
			<script type="text/javascript">
				EXTRAFUNC[\'validator\'][\'touclick\'] = \'touclickextra\';
				var touclickchk=false;
				function touclickextra(){
					if(!touclickchk){
						var the_form = $(\'postform\');
						var oldsubmit = the_form.onsubmit;
						window.TouClick.Start({
							website_key: \''.$this->cvar['pubkey'].'\',
							position_code: '.($_GET['action']=='edit'?13:($_GET['action']=='reply'?11:12)).',
							args: {\'_this\': the_form },
							onSuccess: function (args, check_obj){
								document.cookie="'.$pre.'check_key="+check_obj.check_key;
								document.cookie="'.$pre.'check_address="+check_obj.check_address;
								touclickchk=true;
								oldsubmit.call(args._this);
							}
						});
						return false;
					}else{
						return true;
					}
				}
			</script>
		';
	}
	function post_check() {
		global $_G;
		
        if(!$this->secallow){
			return;
		}
        
        $cookie_str =  getcookie('tou_not_allow');
        if($cookie_str){
            $cookie_str= explode('_', authcode($cookie_str));
            dsetcookie('tou_not_allow', '', 0, 1, true);
            if($this->cvar['pubkey']==$cookie_str[0]){
                $time = intval($cookie_str[1]);
                if(time()-$time<3600){
                    return;
                }
            }
        }
        
        
		if(submitcheck('topicsubmit', 0)||submitcheck('editsubmit', 0)||submitcheck('replysubmit', 0)){
			if(in_array($_GET['action'],$this->secmod)){
				if(getcookie('check_key')&&getcookie('check_address')){
					if(!$this->_check()){
						$this->clearcookie();
						$this->log('post_check  error  touclick:secerr');
						showmessage('touclick:secerr');
					}else{
						$this->clearcookie();
						$this->log('post_check  check ok');
						$chk = true;
					}
				}else{
					switch ($_GET['handlekey']){
						case 'fastnewpost':
							if($_G['group']['allowpost'])
								$this->_show('fastpostform',12);
							break;
						case 'newthread':$this->_show('postform',12);break;
						case 'vfastpost':$this->_show('vfastpostform');break;
						case 'fastpost':$this->_show('fastpostform');break;
						case 'reply':$this->_show('postform');break;
						case 'comment':$this->_show('commentform');break;
						default:
                            if($_GET['handlekey']=='vfastpost_'.$_GET['tid']){
                                $this->_show('vfastpostform_'.$_GET['tid']);
                            }else{
                                $this->log('post_check  error touclick:secerror->handlekey:'.$_GET['handlekey']);
						        showmessage('touclickplat:secerror');
                            }
						break;
					}
				}
			}
		}
	}
}

class plugin_touclick_member extends plugin_touclick {
	function logging_check() {
    
        if(!$this->config['seccode']['open']||!$this->config['active']||!in_array('login',$this->secmod)){
		    return;
        }
		if(submitcheck('loginsubmit',1)){
			if($_GET['auth']){
				$auth = authcode($_GET['auth']);
				if(count(explode("\t",$auth))==3)
					return;
			}
			if(getcookie('check_key')&&getcookie('check_address')){
				if(!$this->_check()){
					$this->clearcookie();
					$this->log('logging_check  error  touclick:secerr');
					showmessage('touclick:secerr');
				}else{
					$this->clearcookie();
					$this->log('logging_check  check ok');
				}
			}else{
				if($_GET['lssubmit']=='yes')
					$this->_show('lsform');
				else{
					$this->_show('loginform_'.$_GET['loginhash']);
				}
			}
		}
	}
	function register_check() {
        if(!$this->config['seccode']['open']||!$this->config['active']||!in_array('reg',$this->secmod)){
		    return;
        }
		if(submitcheck('regsubmit', 0)){
			if(getcookie('check_key')&&getcookie('check_address')){
				if(!$this->_check()){
					$this->clearcookie();
					$this->log('register_check  error  touclick:secerr');
					showmessage('touclick:secerr');
				}else{
					$this->clearcookie();
					$this->log('register_check  check ok');
				}
			}else{
				$this->_show('registerform');
			}
		}
	}
}


?>