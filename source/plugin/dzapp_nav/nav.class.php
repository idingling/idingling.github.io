<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: nav.class.php 29558 2014-11-19 20:41:01Z mpage $
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

class plugin_dzapp_nav {
	
	function plugin_dzapp_nav() {
		global $_G;

		require_once libfile('function/cache');
		loadcache('dzapp_nav');
		$this->dzapp_nav = dhtmlspecialchars($_G['cache']['dzapp_nav']);
		foreach($_G['cache']['dzapp_nav'] as $value) {
			if($value['style']) {
				$stylestr = sprintf('%03b', $value['style']);
				$style = $stylestr[0] ? 'font-weight: bold;' : '';
				$style .= $stylestr[1] ? 'font-style: italic;' : '';
				$style .= $stylestr[2] ? 'text-decoration: underline;' : '';
			} else {
				$style = '';
			}
			$this->dzapp_nav[$value['nav_id']]['style'] = $style;
		}
		$this->color = $_G['cache']['plugin']['dzapp_nav']['color'];
		$this->weibo = $_G['cache']['plugin']['dzapp_nav']['weibo'];
		$this->tqq = $_G['cache']['plugin']['dzapp_nav']['tqq'];
		$this->page = dunserialize($_G['cache']['plugin']['dzapp_nav']['page']);
		$this->cur_page = $_GET['id'] ? $_GET['id'] : CURSCRIPT.'_'.CURMODULE;
	}

	function global_header(){
		global $_G;

		if(!in_array($this->cur_page, $this->page)) return;
		include template('dzapp_nav:nav_header');
		return $return;
	}

	function global_footer(){
		global $_G;
		
		if(!in_array($this->cur_page, $this->page)) return;
		include template('dzapp_nav:nav_footer');
		return $return;
	}

}


?>