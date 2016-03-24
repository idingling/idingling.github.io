<?php

if (!defined('IN_DISCUZ')) {
    exit('Access Denied');
}

if (empty($_GET['p']) || $_GET['p'] != ($sppasswd = baidu_get_plugin_setting('sppasswd'))) {
    baidu_header_status(404);
    return 1;
}

$urlsuffix = "&p=$sppasswd";

$sitemapMaxUid = (int) C::t('#baidusubmit#baidusubmit_sitemap')->get_max_end(4); //sitemap表中最大tid
$maxUid = C::t('common_member')->max_uid();  //论坛数据中最大tid
$count = $maxUid - $sitemapMaxUid;

$config = baidu_get_plugin_config();

//新数据够生成一个sitemap时，生成新的sitemap
if (!$sitemapMaxUid || $count >= $config['userItemCount']) {
    $sitemapCount = ceil($count / $config['userItemCount']);
    $next_uid = $sitemapMaxUid + 1;
    for ($i = 0; $i < $sitemapCount; $i++) {
        $start_uid = $next_uid + $i * $config['userItemCount'];
        $end_uid = $start_uid + $config['userItemCount'] - 1;
        $url = 'sitemapuserall&start=' . $start_uid;
        C::t('#baidusubmit#baidusubmit_sitemap')->add($url, 4, $start_uid, $end_uid);
    }
}

function bs_index_update_last_crawl($offset = 0)
{
    $offset = intval($offset);
    if ($offset < 0)
        return;
    if (0 == $offset || $offset != baidu_get_plugin_setting('lastuidcrawl')) {
        C::t('#baidusubmit#baidusubmit_setting')->update('lastuidcrawl', $offset, true, false);
    }
}

baidu_print_sitemap_index_header();

$site = baidu_get_plugin_setting('siteurl');
$sitemapCount = C::t('#baidusubmit#baidusubmit_sitemap')->get_sitemap_count(4);
$sitemapUrlCount = $config['sitemapUrlCount'] > 0 ? intval($config['sitemapUrlCount']) : 50000;

//全取出来
if ($sitemapCount <= $sitemapUrlCount) {
    $sitemaplist = C::t('#baidusubmit#baidusubmit_sitemap')->get_sitemap_list(4, 0, $sitemapCount);
    if (count($sitemaplist) > 0) {
        baidu_print_sitemap_list($sitemaplist, $site, $urlsuffix);
    }
    baidu_print_sitemap_index_footer();
    bs_index_update_last_crawl();
    return 1;
}


//分段取
$lastcrawl = baidu_get_plugin_setting('lastuidcrawl', true);
$time = time();

$pasttime = $time - $lastcrawl['stime'];
if ($pasttime < $config['sitemapStepTime']) { //没到一个时段则按上次的偏移量
    $offset = intval($lastcrawl['svalue']);
} else {
    $step = $config['sitemapStepLength'];
    if ($step > $sitemapUrlCount) {
        $step = $sitemapUrlCount;
    }
    $offset = $lastcrawl['svalue'] + $step * intval($pasttime / $config['sitemapStepTime']);
}

if ($offset > $sitemapCount) {
    $offset = 0;
}
$sitemaplist = C::t('#baidusubmit#baidusubmit_sitemap')->get_sitemap_list(4, $offset, $sitemapUrlCount);

if (count($sitemaplist) > 0) {
    baidu_print_sitemap_list($sitemaplist, $site, $urlsuffix);
}

//如果溢出了
$overflow = $offset + $sitemapUrlCount - $sitemapCount;
if ($overflow > 0) {
    $sitemaplist = C::t('#baidusubmit#baidusubmit_sitemap')->get_sitemap_list(4, 0, $overflow);
    if (count($sitemaplist) > 0) {
        baidu_print_sitemap_list($sitemaplist, $site, $urlsuffix);
    }
}

baidu_print_sitemap_index_footer();

bs_index_update_last_crawl($offset);

