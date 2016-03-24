<?php

if (!defined('IN_DISCUZ')) {
    exit('Access Denied');
}

if (empty($_GET['p']) || $_GET['p'] != ($sppasswd = baidu_get_plugin_setting('sppasswd'))) {
    baidu_header_status(404);
    return 1;
}

$config = baidu_get_plugin_config();

baidu_print_sitemap_index_header();

$today = strtotime(date('Y-m-d'));
$now = time();
$removeTime = $today - $config['HistoryDayCount'] * 24 * 3600;  //几天前时间
C::t('#baidusubmit#baidusubmit_sitemap')->delete_history($removeTime, 5);  //删除过期数据

$lastTime = C::t('#baidusubmit#baidusubmit_sitemap')->get_max_end(5);  //sitemap表中最后时间
if (empty($lastTime)) {
    $lastTime = $today;
}
if ($today == $lastTime) {
    addIncSitemap($now, $today);
} elseif ($now > $lastTime) {
    addIncSitemap($now, $lastTime);
}

$sitemaps = C::t('#baidusubmit#baidusubmit_sitemap')->get_sitemap_list(5);

$site = baidu_get_plugin_setting('siteurl');
if (count($sitemaps) > 0) {   //返回增量sitemap的索引文件
    baidu_print_sitemap_list($sitemaps, $site, "&p=$sppasswd");
}

baidu_print_sitemap_index_footer();

/**
 * $ti > $t2
 * @param type $t1
 * @param type $t2
 */
function addIncSitemap($t1, $t2)
{
    $overHours = floor(($t1 - $t2) / 3600);
    if ($t1 > $t2 && $overHours > 0) {
        for ($i = 1; $i <= $overHours; $i ++) {
            $st = $t2 + ($i - 1) * 3600;
            $et = $t2 + $i * 3600;
            $url = 'sitemapuserinc&start=' . $st;
            C::t('#baidusubmit#baidusubmit_sitemap')->add($url, 5, $st, $et);
        }
    }
}
