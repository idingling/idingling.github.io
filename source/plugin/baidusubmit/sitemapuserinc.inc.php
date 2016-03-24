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

$startTime = intval(@$_GET['start']);

$sitemap = baidu_get_sitemap(5, $startTime);
if (empty($sitemap)) {
    baidu_header_status(404);
    return 1;
}
$endTime = $sitemap['end'];

$client_etag = !empty($_SERVER['HTTP_IF_NONE_MATCH']) ? trim($_SERVER['HTTP_IF_NONE_MATCH']) : false;
if ($client_etag) {
    //获取etag中的时间
    $client_etag_max_time = intval($client_etag); //取整，去掉后面的无效字符
    //起始设成上次的最大值
    if ($client_etag_max_time > $startTime) {
        $startTime = $client_etag_max_time;
    }
    if ($client_etag_max_time > $endTime) { //抓取过的数据再次抓取的时候
        $etag = $client_etag_max_time;
        header('HTTP/1.1 304 Not Modified');
        header('ETag: ' . $etag);
        return 1;
    }
}

//清掉钩子
$_G['setting']['plugins']['func'] = array();

$etag = time();
header('ETag: ' . $etag);

$config = baidu_get_plugin_config();

$uids = C::t('#baidusubmit#common_member_status_baidu')->get_uids_by_lastvisit($startTime, $endTime, $config['userItemCount']);

$indexsplitsitemap = false;
$uidCount = count($uids);
if ($uidCount == $config['userItemCount']) {
    $indexsplitsitemap = true;
}
$itemCount = 0;
global $_G;
header('Content-Type: text/xml');
echo '<?xml version="1.0" encoding="UTF-8"?><urlset>';

$sizesplitsitemap = false;
$userList = baidu_get_user($uids);
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

//记录相关数据
$timeLost = intval(1000 * (microtime(true) - $_G['starttime']));

C::t('#baidusubmit#baidusubmit_sitemap')->update_by_sid(
        $sitemap['sid'], array('item_count' => $itemCount, 'lost_time' => $timeLost));

baidu_update_url_stat($itemCount);
