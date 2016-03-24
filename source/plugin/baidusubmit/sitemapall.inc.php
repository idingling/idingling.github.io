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

$start_tid = intval($_GET['start']);

$sitemap = baidu_get_sitemap(1, $start_tid);
if (empty($sitemap)) {
    baidu_header_status(404);
    return 1;
}

$end_tid = $sitemap['end'];

$client_etag = !empty($_SERVER['HTTP_IF_NONE_MATCH']) ? trim($_SERVER['HTTP_IF_NONE_MATCH']) : false;
if ($client_etag) {
    $client_etag_max_tid = intval($client_etag); //取整，去掉后面的无效字符
    //起始id设成上次的最大值
    if ($client_etag_max_tid > $start_tid) {
        $start_tid = $client_etag_max_tid;
    }
    if ($client_etag_max_tid >= $end_tid) { //抓取过的数据再次抓取的时候
        $etag = $client_etag;
        header('HTTP/1.1 304 Not Modified');
        header('ETag: ' . $etag);
        return 1;
    }
}

//清掉钩子
$_G['setting']['plugins']['func'] = array();

//设成最后的值
$etag = $end_tid;
header('ETag: ' . $etag);

$threadlist = C::t('#baidusubmit#forum_thread_baidu')->get_thread_by_tidrange($start_tid, $end_tid);
$itemCount = 0;
$fileSize = 0;
$urlnum = 0;
$installmaxtid = baidu_get_plugin_setting('installmaxtid');

global $_G;
header('Content-Type: text/xml');
echo '<?xml version="1.0" encoding="UTF-8"?><urlset>';

$forumlist = baidu_get_forum_list();
foreach ($threadlist as $tid => $thread) {
    $output = baidu_schema_body_build($forumlist[$thread['fid']], $thread);
    if ($output === false) {
        continue;
    }
    $fileSizeCheck = $fileSize + strlen($output);
    $itemCountCheck = $itemCount + 1;

    // split sitemap file
    if ($fileSizeCheck >= 1024 * 1024 * 8 || $itemCountCheck > 5000) {
        // 并发问题
        $sp = baidu_get_sitemap(1, $start_tid, $end_tid);
        if ($sp) {
            $new_start_tid = $thread['tid'];
            $past_tid = $new_start_tid - $start_tid - 1;
            $count = ceil(($end_tid - $new_start_tid) / $past_tid);

            for ($i = 0; $i < $count; $i++) {
                $_xstart = $new_start_tid + $past_tid * $i;
                $_xend = $_xstart + $past_tid - 1;
                if ($_xend > $end_tid) {
                    $_xend = $end_tid;
                }
                $url = "sitemapall&start={$_xstart}";
                C::t('#baidusubmit#baidusubmit_sitemap')->add($url, 1, $_xstart, $_xend);
            }

            $new_end_tid = $new_start_tid - 1;
            $new_url = "sitemapall&start={$start_tid}";
            C::t('#baidusubmit#baidusubmit_sitemap')->update_by_sid(
                    $sp['sid'], array('url' => $new_url, 'start' => $start_tid, 'end' => $new_end_tid));

            $end_tid = $new_end_tid;
        }
        break;
    }
    echo $output;

    $fileSize = $fileSizeCheck;
    $itemCount = $itemCountCheck;

    if ($tid <= $installmaxtid) {
        $urlnum ++;
    }

    flush();
}
echo '</urlset>';

$timeLost = intval(1000 * (microtime(true) - $_G['starttime']));
C::t('#baidusubmit#baidusubmit_sitemap')->update_by_sid(
        $sitemap['sid'], array('item_count' => $itemCount, 'file_size' => $fileSize, 'lost_time' => $timeLost));

baidu_update_url_stat($urlnum);
