<?php

if (!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
    exit('Access Denied');
}

global $_G;

require_once dirname(__FILE__) . '/function/function_baidu.php';

if (!baidu_table_exists('baidusubmit_setting')) {
    cpmsg_error(lang('plugin/baidusubmit', 'tablenotexists'));
}

$url = $_G['siteurl'] . substr($_SERVER['REQUEST_URI'], (int) strrpos($_SERVER['REQUEST_URI'], '/') + 1);

if (!$_G['uid']) {
    showmessage('not_loggedin', NULL, array(), array('login' => 1));
}
if (isset($_POST['ping'])) {
    C::t('#baidusubmit#baidusubmit_setting')->update('openping', intval((bool) $_POST['openping']));
    cpmsg('setting_update_succeed', $url, 'succeed');
} else if (isset($_POST['auth'])) {
    include dirname(__FILE__) . '/auth.inc.php';
} else if (empty($_GET['inajax'])) {
    if (!($siteUrl = baidu_get_plugin_setting('siteurl'))) {
        $siteUrl = $_G['siteurl'];
    }
    $keyExist = baidu_get_plugin_setting('pingtoken') ? true : false;
    $mobileUpgrade = baidu_get_plugin_setting('mobile');
    $openping = baidu_get_plugin_setting('openping');
    $baiducse = baidu_get_plugin_setting('baiducse');
    $token = baidu_get_plugin_setting('pingtoken');
    $site = baidu_get_plugin_setting('siteurl');
    $sign = md5($site . $token);
    $closeUrl = $siteUrl . 'plugin.php?id=baidusubmit:baiducse&type=close&sign=' . $sign;
    $openUrl = $siteUrl . 'plugin.php?id=baidusubmit:baiducse&type=open&sign=' . $sign;

    $config = baidu_get_plugin_config();
    $u = $config['zzplatform'] . "/discuz/setcse?sign=$sign&site=$site&status=$mobileUpgrade";
    $login = sprintf($config['passport'], urlencode($u));

    $engine = baidu_get_plugin_setting('engine');

    $msgType = intval($_GET['rst']);
    if ($msgType) {
        C::t('#baidusubmit#baidusubmit_setting')->update('baiducse', 0);
        C::t('#baidusubmit#baidusubmit_setting')->update('engine', '');
    }
    unset($_GET['rst']);
    $userSitemap = baidu_get_plugin_setting('user_sitemap');
    $openUserSitemapUrl = $siteUrl . 'plugin.php?id=baidusubmit:baiducse&type=openUserSitemap&sign=' . $sign;
    $closeUserSitemapUrl = $siteUrl . 'plugin.php?id=baidusubmit:baiducse&type=closeUserSitemap&sign=' . $sign;
    $baseUrl = $siteUrl . 'plugin.php?id=baidusubmit:baiducse&sign=' . $sign;
    $auto_time = baidu_get_plugin_setting('auto_time');
    $auto = baidu_get_plugin_setting('auto');
    if ($auto_time && $auto_time - time() > 0) {
        $lH = floor(($auto_time - time()) / 3600);
        $lm = floor(($auto_time - time()) % 3600 / 60);
    }
    $znUrl = sprintf($config['zhannei'], $engine);
    include template('baidusubmit:admin');
}