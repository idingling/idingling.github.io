<?php

if (!defined('IN_DISCUZ')) {
    exit('Access Denied');
}

ini_set('memory_limit', '1024M');
set_time_limit(300);

if (empty($_GET['p']) || $_GET['p'] != baidu_get_plugin_setting('sppasswd')) {
    baidu_header_status(404);
    return 1;
}

$start_uid = intval($_GET['start']);

$sitemap = baidu_get_sitemap(4, $start_uid);
if (empty($sitemap)) {
    baidu_header_status(404);
    return 1;
}

$end_uid = $sitemap['end'];

$client_etag = !empty($_SERVER['HTTP_IF_NONE_MATCH']) ? trim($_SERVER['HTTP_IF_NONE_MATCH']) : false;
if ($client_etag) {
    $client_etag_max_uid = intval($client_etag); //取整，去掉后面的无效字符
    //起始id设成上次的最大值
    if ($client_etag_max_tid > $start_uid) {
        $start_uid = $client_etag_max_uid;
    }
    if ($client_etag_max_uid >= $end_uid) { //抓取过的数据再次抓取的时候
        $etag = $client_etag;
        header('HTTP/1.1 304 Not Modified');
        header('ETag: ' . $etag);
        return 1;
    }
}

//清掉钩子
$_G['setting']['plugins']['func'] = array();

//设成最后的值
$etag = $end_uid;
header('ETag: ' . $etag);
$itemCount = 0;
header('Content-Type: text/xml');
echo '<?xml version="1.0" encoding="UTF-8"?><urlset>';

$userList = baidu_get_user($start_uid);
$groupDict = C::t('#baidusubmit#common_usergroup_baidu')->fetch_dict();
foreach ($userList as $user) {
    $body = baidu_forum_user_body_build($user, $groupDict);
    if ($body) {
        $itemCount++;
    }
    echo $body;
    flush();
}
echo '</urlset>';

global $_G;
$timeLost = intval(1000 * (microtime(true) - $_G['starttime']));
C::t('#baidusubmit#baidusubmit_sitemap')->update_by_sid(
        $sitemap['sid'], array('item_count' => $itemCount, 'lost_time' => $timeLost));

baidu_update_url_stat($itemCount);
