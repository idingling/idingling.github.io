<?php

if (!defined('IN_DISCUZ')) {
    exit('Access Denied');
}
header("Content-type: application/json");
$token = baidu_get_plugin_setting('pingtoken');
$site = baidu_get_plugin_setting('siteurl');
$sign = $_GET['sign'];
if ($sign != md5($site . $token)) {
    echo json_encode(array('status' => 0));
    exit;
}
switch ($_GET['type']) {
    case "mobile":
        C::t('#baidusubmit#baidusubmit_setting')->update('mobile', 1);
        echo json_encode(array('status' => 1));
        break;
    case "update":
        C::t('#baidusubmit#baidusubmit_setting')->update('auto', 1);
        C::t('#baidusubmit#baidusubmit_setting')->update('auto_time', time() + 48 * 3600);
        C::t('#baidusubmit#baidusubmit_setting')->update('baiducse', 1);
        C::t('#baidusubmit#baidusubmit_setting')->update('engine', $_GET['sid']);

        $resultJson = userSitemap();
        $result = json_decode($resultJson, true);
        if ($result['status'] == 1) {
            echo json_encode(array('status' => 1));
        } else {
            echo json_encode(array('status' => 0));
        }
        break;
    case "close":
        C::t('#baidusubmit#baidusubmit_setting')->update('baiducse', 0);
        echo json_encode(array('status' => 1));
        break;
    case "open":
        if (isset($_GET['cse_type']) && $_GET['cse_type'] == 2) {
            C::t('#baidusubmit#baidusubmit_setting')->update('baiducse', 2);
        } else if (isset($_GET['cse_type']) && $_GET['cse_type'] == 1) {
            C::t('#baidusubmit#baidusubmit_setting')->update('baiducse', 1);
        } else {
            C::t('#baidusubmit#baidusubmit_setting')->update('auto', 1);
            C::t('#baidusubmit#baidusubmit_setting')->update('baiducse', 1);
        }
        echo json_encode(array('status' => 1));
        break;
    case "openUserSitemap":
        $siteurl = baidu_get_plugin_setting('siteurl');
        baidu_search_user($siteurl, $sign,'open');
        echo userSitemap();
        break;
    case "closeUserSitemap":
        $sppasswd = baidu_get_plugin_setting('sppasswd');
        $siteurl = baidu_get_plugin_setting('siteurl');
        baidu_submit_sitemap_index('del', 4, $siteurl, $sppasswd, $sign);
        baidu_submit_sitemap_index('del', 5, $siteurl, $sppasswd, $sign);
        baidu_search_user($siteurl, $sign,'close');
        C::t('#baidusubmit#baidusubmit_setting')->update('user_sitemap', 0);
        C::t('#baidusubmit#baidusubmit_sitemap')->deleteByType(4);
        C::t('#baidusubmit#baidusubmit_sitemap')->deleteByType(5);
        echo json_encode(array('status' => 1));
        break;
    case "closeAuto":
        C::t('#baidusubmit#baidusubmit_setting')->update('auto', 0);
        echo json_encode(array('status' => 1));
        break;
    case "manual":
        C::t('#baidusubmit#baidusubmit_setting')->update('auto', 2);
        echo json_encode(array('status' => 1));
        break;
    default :
        $status = baidu_get_plugin_setting('baiducse');
        echo json_encode(array('status' => $status));
        break;
}

function userSitemap()
{
    global $sign;
    //提交用户索引 全量
    $sppasswd = baidu_get_plugin_setting('sppasswd');
    $siteurl = baidu_get_plugin_setting('siteurl', false, true);

    $allUserReturnJson = baidu_submit_sitemap_index('add', 4, $siteurl, $sppasswd, $sign);
    $allUserResult = json_decode($allUserReturnJson['json'], true);
    if (!isset($allUserResult['status']) || (isset($allUserResult['status']) && $allUserResult['status'])) {
        return json_encode(array('status' => 4));
    }
    //提交用户索引 增量
    $incUserReturnJson = baidu_submit_sitemap_index('add', 5, $siteurl, $sppasswd, $sign);
    $incUserResult = json_decode($incUserReturnJson['json'], true);
    if (!isset($incUserResult['status']) || (isset($incUserResult['status']) && $incUserResult['status'])) {
        return json_encode(array('status' => 5));
    }
    C::t('#baidusubmit#baidusubmit_setting')->update('user_sitemap', 1);
    return json_encode(array('status' => 1));
}

exit;
