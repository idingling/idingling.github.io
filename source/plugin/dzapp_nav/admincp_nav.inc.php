<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: admincp_nav.inc.php 33234 2014-11-19 16:53:35Z mpage $
 */

if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}

$lang = array_merge($lang, $scriptlang['dzapp_nav']);
require_once libfile('function/home');
loadcache('dzapp_nav');

if(empty($_GET['ac'])) {

	if(!submitcheck('listsubmit')) {

		showformheader('plugins&operation=config&do='.$plugin['pluginid'].'&identifier=dzapp_nav&pmod=admincp_nav');
		showtableheader();
		showsubtitle(array('del', 'order', 'name', 'url', 'width', 'type', 'available', ''));
		foreach($_G['cache']['dzapp_nav'] as $key => $value) {
			$parent = $value['upid'] ? '' : 'parent';
			$navtype[$value['navtype']] = ' selected="selected"';
			showtablerow('', array('class="td25"', 'class="td25"', '', 'class="td29"', 'class="td25"'), array(
				'<input type="checkbox" class="checkbox" name="delete[]" value="'.$value['nav_id'].'" />',
				'<input type="text" class="txt" name="displayorder['.$value['nav_id'].']" value="'.$value['displayorder'].'" />',
				'<div class="'.$parent.'board"><input type="text" class="txt" name="name['.$value['nav_id'].']" value="'.dhtmlspecialchars($value['name']).'" />'.($value['upid'] == 0 ? '<a href="###" onclick="addrowdirect=1;addrow(this, 1, '.$value['nav_id'].')" class="addchildboard">'.cplang('add_subnav').'</a>' : '').'</div>',
				'<input type="text" class="txt" name="url['.$value['nav_id'].']" value="'.$value['url'].'" />',
				$value['upid'] ? '' : '<input type="text" class="txt" name="width['.$value['nav_id'].']" value="'.$value['width'].'" />',
				$value['upid'] ? '' : '<select name="navtype['.$value['nav_id'].']"><option value="1"'.$navtype[1].'>'.$lang['nav_1'].'</option><option value="2"'.$navtype[2].'>'.$lang['nav_2'].'</option></select>',
				'<input type="checkbox" class="checkbox" name="available['.$value['nav_id'].']" value="1"'.($value['available'] ? 'checked="checked"' : '').' />',
				'<a href="'.ADMINSCRIPT.'?action=plugins&operation=config&do='.$plugin['pluginid'].'&identifier=dzapp_nav&pmod=admincp_nav&ac=edit&id='.$key.'" class="act">'.cplang('edit').'</a>'
			));
			$lastkey = $key;
			$navtype[$value['navtype']] = '';
		}
		echo '<tr><td></td><td colspan="20"><div><a href="###" onclick="addrow(this, 0)" class="addtr">'.$lang['add_nav'].'</a></div></td></tr>';
		showsubmit('listsubmit', 'submit', 'del');
		showtablefooter();
		showformfooter();
		echo '<script type="text/javascript">
		var rowtypedata = [
			[[1, \'\', \'td25\'], [1, \'<input type="text" class="txt" name="newdisplayorder[]" />\', \'td25\'], [1, \'<input type="text" class="txt" name="newname[]" />\', \'\'], [1, \'<input type="text" class="txt" name="newurl[]" />\', \'td29\'], [1, \'<input type="text" class="txt" name="newwidth[]" />\', \'td25\'], [1, \'<select name="newnavtype[]"><option value="1">'.$lang['nav_1'].'</option><option value="2">'.$lang['nav_2'].'</option></select>\'], [1, \'<input type="checkbox" class="checkbox" name="newavailable[]" />\', \'\'], [1, \'<input type="hidden" name="upid[]" value="0" />\']],
			[[1, \'\', \'td25\'], [1, \'<input type="text" class="txt" name="newdisplayorder[]" />\', \'td25\'], [1, \'<div class="board"><input type="text" class="txt" name="newname[]" /></div>\', \'\'], [1, \'<input type="text" class="txt" name="newurl[]" />\', \'td29\'], [1, \'\'], [1, \'\'], [1, \'<input type="checkbox" class="checkbox" name="newavailable[]" />\', \'\'], [1, \'<input type="hidden" name="upid[]" value="{1}" />\']],
		];
		</script>';

	} else {

		if(is_array($_GET['delete'])) {
			C::t('#dzapp_nav#dzapp_nav')->delete($_GET['delete']);
		}

		if(is_array($_GET['name'])) {
			foreach($_GET['name'] as $key => $value) {
				C::t('#dzapp_nav#dzapp_nav')->update($key, array(
					'displayorder' => $_GET['displayorder'][$key],
					'name' => $_GET['name'][$key],
					'url' => $_GET['url'][$key],
					'width' => $_GET['width'][$key],
					'navtype' => $_GET['navtype'][$key],
					'available' => $_GET['available'][$key],
				));
			}
		}

		if(is_array($_GET['newname'])) {
			foreach($_GET['newname'] as $key => $value) {
				if(empty($value)) continue;
				C::t('#dzapp_nav#dzapp_nav')->insert(array(
					'displayorder' => $_GET['newdisplayorder'][$key],
					'name' => $_GET['newname'][$key],
					'url' => $_GET['newurl'][$key],
					'width' => $_GET['newwidth'][$key],
					'navtype' => $_GET['newnavtype'][$key],
					'available' => $_GET['newavailable'][$key],
					'upid' => $_GET['upid'][$key],
				));
			}
		}

		update_cache_dzapp_nav();
		cpmsg('nav_update_succeed', 'action=plugins&operation=config&do='.$plugin['pluginid'].'&identifier=dzapp_nav&pmod=admincp_nav', 'succeed');
	}

} elseif($_GET['ac'] == 'edit') {

	$nav = C::t('#dzapp_nav#dzapp_nav')->fetch($_GET['id']);
	if(!$nav) {
		cpmsg('nav_nonexistence', '', 'error');
	}

	if(!submitcheck('editsubmit')) {
			
		showformheader('plugins&operation=config&do='.$plugin['pluginid'].'&identifier=dzapp_nav&pmod=admincp_nav&ac=edit&id='.$nav['nav_id'], 'enctype');
		showtableheader();
		
		showsetting('name', 'name', $nav['name'], 'text');
		showsetting('icon', 'icon', $nav['icon'], 'filetext');
		showsetting('url', 'url', $nav['url'], 'text');
		showsetting('target', 'target', $nav['target'], 'radio');
		showsetting('style', array('stylenew', array(cplang('misc_customnav_style_underline'), cplang('misc_customnav_style_italic'), cplang('misc_customnav_style_bold'))), $nav[0], 'binmcheckbox');
		showsetting('color', 'color', $nav['color'], 'color');
		showsetting('description', 'description', $nav['description'], 'textarea');
		showsubmit('editsubmit');
		showtablefooter();
		showformfooter();

	} else {

		if(!$_GET['name']) { 
			cpmsg('nav_invalid', '', 'error');
		}

		$stylebin = '';
		for($i = 3; $i >= 1; $i--) {
			$stylebin .= empty($_GET['stylenew'][$i]) ? '0' : '1';
		}
		$stylenew = bindec($stylebin);
		$attachurl = 'data/attachment/portal/';
		if($icon = pic_upload($_FILES['icon'], 'portal')) $_GET['icon'] = $attachurl.$icon['pic'];
		if($icon && $nav['icon']) pic_delete(str_replace($attachurl, '', $nav['icon']), 'portal');
		$data = array(
			'displayorder' => $_GET['displayorder'],
			'name' => $_GET['name'],
			'icon' => $_GET['icon'],
			'url' => $_GET['url'],
			'target' => $_GET['target'],
			'style' => $stylenew,
			'color' => $_GET['color'],
			'description' => $_GET['description'],
		);
		C::t('#dzapp_nav#dzapp_nav')->update($nav['nav_id'], $data);

		update_cache_dzapp_nav();
		cpmsg('nav_edit_succeed', 'action=plugins&operation=config&do='.$plugin['pluginid'].'&identifier=dzapp_nav&pmod=admincp_nav', 'succeed');
	}
}

function update_cache_dzapp_nav() {
	$query = C::t('#dzapp_nav#dzapp_nav')->fetch_all_by_displayorder();
	foreach($query as $key => $value) {
		if($value['upid']) {
			$query[$value['upid']]['children'][] = $key;
		}
	}
	foreach($query as $key => $value) {
		if($value['upid'] == 0) {
			$dzapp_nav[$key] = $value;
			foreach($value['children'] as $children) {
				$dzapp_nav[$children] = $query[$children];
			}
		}
	}
	savecache('dzapp_nav', $dzapp_nav);
}

?>