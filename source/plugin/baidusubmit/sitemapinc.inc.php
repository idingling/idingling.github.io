<?php
if (!defined('IN_DISCUZ')) {
    exit('Access Denied');
}

ini_set('memory_limit','512M');
set_time_limit(300);

if (empty($_GET['p']) || $_GET['p'] != baidu_get_plugin_setting('sppasswd')) {
    baidu_header_status(404);
    return 1;
}

$startTime = intval(@$_GET['start']);

$sitemap = baidu_get_sitemap(2, $startTime);
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
        header('ETag: '.$etag);
        return 1;
    }
}

//清掉钩子
$_G['setting']['plugins']['func'] = array();

$etag = time();
header('ETag: '.$etag);

define('_MAX_THREAD_COUNT_', 5000);
$threadlist = C::t('#baidusubmit#forum_thread_baidu')->get_thread_by_lastpost($startTime, $endTime, _MAX_THREAD_COUNT_);

$indexsplitsitemap = false;
$threadCount = count($threadlist);
if ($threadCount >= _MAX_THREAD_COUNT_) {
    $indexsplitsitemap = true;
}
$itemCount = 0;
$fileSize = 0;


global $_G;
header('Content-Type: text/xml');
echo '<?xml version="1.0" encoding="UTF-8"?><urlset>';

$sizesplitsitemap = false;
$forumlist = baidu_get_forum_list();
foreach ($threadlist as $tid => $thread) {
    $output = baidu_schema_body_build($forumlist[$thread['fid']], $thread);
    if($output === false){
        continue;
    }
    $fileSizeCheck = $fileSize + strlen($output);
    $itemCount += 1;

    if ($fileSizeCheck >= 1024*1024*8) {
        $sizesplitsitemap = true;
        break;
    }

    $fileSize = $fileSizeCheck;

    echo $output;
    flush();
}
echo '</urlset>';

//分裂数据
if ($sizesplitsitemap || ($indexsplitsitemap && $thread['lastpost']<$endTime)) {  //超过sitemap文件限制进行分裂
    $sp = baidu_get_sitemap(2, $startTime, $endTime);
    if ($sp) {
        //计算裂变
        $newStartTime = $thread['lastpost'];
        //裂变步长
        $stepLen = intval(($newStartTime - $startTime - 1) * 0.3);
        $curTime = time();
        //只裂变到当前时间
        $count = ceil(($curTime - $newStartTime) / $stepLen);
        for ($i=0; $i<$count; $i++) {
            $_xstart = $newStartTime + $stepLen * $i;
            $_xend = $_xstart + $stepLen - 1;
            if ($_xend > $curTime) {
                $_xend = $curTime;
            }
            $url = "sitemapinc&start={$_xstart}";
            C::t('#baidusubmit#baidusubmit_sitemap')->add($url, 2, $_xstart, $_xend);
        }
        //把最后一个加上
        $nextTime = $curTime + 1;
        C::t('#baidusubmit#baidusubmit_sitemap')->add("sitemapinc&start={$nextTime}", 2, $nextTime, $endTime);

        $newEndTime = $newStartTime - 1;
        $newUrl = "sitemapinc&start={$startTime}";
        C::t('#baidusubmit#baidusubmit_sitemap')->update_by_sid(
                    $sp['sid'],
                    array('url' => $newUrl, 'start' => $startTime, 'end' => $newEndTime));

        $endTime = $newEndTime;
    }
}


//记录相关数据
$timeLost = intval(1000 * (microtime(true) - $_G['starttime']));

C::t('#baidusubmit#baidusubmit_sitemap')->update_by_sid(
        $sitemap['sid'],
        array('item_count' => $itemCount, 'file_size' => $fileSize, 'lost_time' => $timeLost));

baidu_update_url_stat($itemCount);
