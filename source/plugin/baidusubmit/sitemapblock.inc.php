<?php

if (!defined('IN_DISCUZ')) {
    exit('Access Denied');
}
ini_set('memory_limit','512M');
if (empty($_GET['p']) || $_GET['p'] != baidu_get_plugin_setting('sppasswd')) {
    baidu_header_status(404);
    return 1;
}

$client_etag = !empty($_SERVER['HTTP_IF_NONE_MATCH']) ? trim($_SERVER['HTTP_IF_NONE_MATCH']) : false;
if ($client_etag) {
    header('HTTP/1.1 304 Not Modified');
    header('ETag: ' . $client_etag);
}
$count = baidu_get_plugin_setting('blockcount');

//清掉钩子
$_G['setting']['plugins']['func'] = array();
header('ETag: ' . $count);
header('Content-Type: text/xml');
echo '<?xml version="1.0" encoding="UTF-8"?><urlset>';
$forumlist = baidu_get_forum_list();
$itemCount = 0;
foreach ($forumlist as $forum) {
    $output = baidu_forum_body_build($forum);
    if ($output === false) {
        continue;
    }
    $itemCount ++;
    echo $output;
    flush();
}
echo '</urlset>';

$timeLost = intval(1000 * (microtime(true) - $_G['starttime']));
C::t('#baidusubmit#baidusubmit_sitemap')->update_by_sid($sitemap['sid'], array('item_count' => $itemCount, 'file_size' => $fileSize, 'lost_time' => $timeLost));
C::t('#baidusubmit#baidusubmit_setting')->update('blockcount', $itemCount);
baidu_update_url_stat($itemCount);
