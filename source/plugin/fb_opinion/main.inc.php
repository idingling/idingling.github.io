<?php

/**
 * 
 * Collect members feedback opinion
 * $Id: main.inc.php 2013-9-11 bing $
 * 
 */
if (!defined('IN_DISCUZ')) {
    exit('Access Denied');
}

require_once libfile("function/profile");
require_once libfile('function/home');
$option = $_G['cache']['plugin']['fb_opinion'];
$switch = $option['offset'];
$tips_contents = $option['tips_contents'];
$admins = explode(',', trim($option['admins']));
$links_type = $option['links_type'];
$site_name = $option['site_name'];
$site_url = $option['site_url'];
$site_logo = $option['site_logo'];
$allow_nologin = $option['allow_nologin'];
$nologin_notice = $option['nologin_notice'];

$showopinionlist = $option['showopinionlist'];

$navtitle = $option['title'];
$metakeywords = $option['keyword'];
$metadescription = $option['content'];
/* fields required */
$fieldsrequired = unserialize($option['fieldsrequired']);
$fieldsrequired_json = json_encode($fieldsrequired);
if ($_G['uid'] == '' && $allow_nologin != 1) {
    showmessage(lang('plugin/fb_opinion', 'gotologin'), '', array(), array('login' => true));
}
if ($_G['uid']) {
    $profile = DB::fetch_first("SELECT * FROM " . DB::table('common_member_profile') . " WHERE uid='" . $_G['uid'] . "'");
    $_G['member'] = array_merge($_G['member'], $profile);
}

$seccodecheck = $_G['group']['seccode'] ? $_G['setting']['seccodestatus'] & 4 : 0;
$secqaacheck = $_G['group']['seccode'] ? $_G['setting']['secqaa']['status'] & 2 : 0;

//check whether have the right to manage : reply or delete
$allowmanage = false;
if (in_array($_G['uid'], $admins)) {
    $allowmanage = true;
}

if ($switch != 1) {
    showmessage(lang('plugin/fb_opinion', 'offset1'));
}
if ($_GET['mod'] == 'apply' || empty($_G['mod'])) {
    $plugin_nav = lang('plugin/fb_opinion', 'tab_nav_apply');
    if (submitcheck('applysubmit', 0, $seccodecheck, $secqaacheck)) {

        //required check
        foreach($fieldsrequired as $field) {
             if (trim($_POST[$field]) == '') {
                showmessage(lang('plugin/fb_opinion', 'need'. $field), dreferer());
              } 
        }
        if (trim($_POST['email']) && !preg_match('/^\w{3,}@\w+(\.\w+)+$/', $_POST['email'])) {
            showmessage(lang('plugin/fb_opinion', 'needemail'), dreferer());
        } elseif (trim($_POST['phone']) && !preg_match('/^[-+]?\d+$/', $_POST['phone'])) {
            showmessage(lang('plugin/fb_opinion', 'needphone'), dreferer());
        }
        
        $_POST['name'] = censor($_POST['name']);
        $_POST['name'] = addslashes(dhtmlspecialchars($_POST['name']));
        $_POST['name'] = getstr($_POST['name'], 15, 1, 1);
        $_POST['message'] = censor($_POST['message']);
        $_POST['message'] = addslashes(dhtmlspecialchars($_POST['message']));
        $_POST['message'] = getstr($_POST['message'], 1000, 1, 1);
        $_POST['email'] = addslashes(dhtmlspecialchars($_POST['email']));
        $_POST['phone'] = addslashes(dhtmlspecialchars($_POST['phone']));
        $_POST['resideprovince'] = addslashes(dhtmlspecialchars($_POST['resideprovince']));
        $_POST['residecity'] = addslashes(dhtmlspecialchars($_POST['residecity']));
        $_POST['residedist'] = addslashes(dhtmlspecialchars($_POST['residedist']));
        $_POST['residecommunity'] = addslashes(dhtmlspecialchars($_POST['residecommunity']));

        DB::insert('fb_opinion', array('name' => $_POST['name'], 'username' => $_G['username'], 'email' => $_POST['email'], 'phone' => $_POST['phone'], 'uid' => $_G['uid'], 'dateline' => time(), 'updatetime' => time(), 'status' => '0', 'resideprovince' => $_POST['resideprovince'], 'residecity' => $_POST['residecity'], 'residedist' => $_POST['residedist'], 'residecommunity' => $_POST['residecommunity'], 'message' => $_POST['message']));
        $message = lang('plugin/fb_opinion', 'pm_content1') . $_POST['siteurl'] . lang('plugin/fb_opinion', 'pm_content2');
        if (!empty($admins)) {
            if ($_G['uid']) {
                $message = $message . "\n" . $_POST['message'];
                sendpm(implode(',', $admins), '', $message);
            } else {
                foreach ($admins as $adminid) {
                    notification_add($adminid, 'system', $message);
                }
            }
        }
        showmessage(lang('plugin/fb_opinion', 'tips_content'), 'plugin.php?id=fb_opinion:main');
    } else {
        $values = array(0, 0, 0, 0);
        $elems = array('resideprovince', 'residecity', 'residedist', 'residecommunity');
        $residehtml = '<p id="residedistrictbox">' . showdistrict($values, $elems, 'residedistrictbox', 1) . '</p>';
    }
} elseif ($_GET['mod'] == 'log') {

    if ($_G['uid'] == '') {
        showmessage(lang('plugin/fb_opinion', 'gotologin'), '', array(), array('login' => true));
    }
    $plugin_nav = lang('plugin/fb_opinion', 'tab_nav_log');
    $page = empty($_G['page']) ? 1 : $_G['page'];
    $perpage = 10;
    $lognum = DB::result_first("SELECT COUNT(*) FROM " . DB::table('fb_opinion') . " WHERE `uid` = '" . $_G['uid'] . "'");
    $start_limit = ($page - 1) * $perpage;
    $multipage = multi($lognum, $perpage, $page, 'plugin.php?id=fb_opinion:main&mod=log', 0, 10);
    $sql = "SELECT * FROM " . DB::table('fb_opinion') . " WHERE `uid` = '" . $_G['uid'] . "' ORDER BY dateline DESC LIMIT $start_limit, $perpage";
    $query = DB::query($sql);
    $loglist = $loglists = array();

    while ($loglist = DB::fetch($query)) {
        $loglist['dateline'] = dgmdate($loglist['dateline']);
        if ($loglist['status'] == 1) {
            $loglist['status'] = "<font color=green>" . lang('plugin/fb_opinion', 'passverify') . "</font>";
        } elseif ($loglist['status'] == 0) {
            $loglist['status'] = "<font color='#ff6600'>" . lang('plugin/fb_opinion', 'unverify') . "</font>";
        }
        if ($loglist['status'] == -1) {
            $loglist['status'] = "<font color=red>" . lang('plugin/fb_opinion', 'nopassverify') . "</font>";
        }
        $loglist['reside'] = profile_show('residecity', $loglist);
        $loglists[] = $loglist;
    }
} elseif ($_GET['mod'] == 'reply' || $_GET['mod'] == 'view') {

    $fbid = isset($_GET['fbid']) ? intval($_GET['fbid']) : 0;
    $upid = isset($_GET['upid']) ? intval($_GET['upid']) : 0;
    if (!$fbid) {
        showmessage('parameters_error', 'plugin.php?id=fb_opinion:main');
    }
    if ($_GET['mod'] == 'reply') {
        $plugin_nav = lang('plugin/fb_opinion', 'tab_nav_reply');
    } else {
        $plugin_nav = lang('plugin/fb_opinion', 'tab_nav_view');
    }
    $fbopinion = DB::fetch_first("SELECT * FROM " . DB::table('fb_opinion') . " WHERE id='$fbid'");
    if (empty($fbopinion)) {
        showmessage(lang('plugin/fb_opinion', 'fb_opinion_not_exists'));
    }

    //check the user hava the right to reply
    if ($_GET['mod'] == 'reply' && !$allowmanage) {
        showmessage('message_can_not_send_12', dreferer());
    }
    //whether can see this single opinion,now just the author can see
    if ($_GET['mod'] == 'view' && $_G['uid'] != $fbopinion['uid']) {
        showmessage('message_can_not_send_12', dreferer());
    }

    if ($_GET['mod'] == 'reply' && $upid) {
        $replyquestion = DB::fetch_first("SELECT message, dateline FROM " . DB::table('fb_opinion_comment') . " WHERE cid='$upid'");
        if (empty($replyquestion)) {
            showmessage('parameters_error', 'plugin.php?id=fb_opinion:main');
        }
        $replyquestion['message'] = cutstr($replyquestion['message'], 30);
        $replyquestion['dateline'] = dgmdate($replyquestion['dateline']);
    }

    $fbopinion['dateline'] = dgmdate($fbopinion['dateline']);

    list($firstanswerlist, $commentlist) = get_fb_opinion_comment($fbid);

    if (submitcheck('replysubmit', 1)) {
        if (trim($_POST['message']) == '') {
            showmessage(lang('plugin/fb_opinion', 'needreplymessage'), dreferer());
        }
        $_POST['message'] = censor($_POST['message']);
        $_POST['message'] = addslashes(dhtmlspecialchars($_POST['message']));
        $_POST['message'] = getstr($_POST['message'], 1000, 1, 1);
        $_G['username'] = addslashes($_G['username']);
        $setarr = array(
            'fbid' => $fbid,
            'upid' => $upid,
            'message' => $_POST['message'],
            'uid' => $_G['uid'],
            'username' => $_G['username'],
            'dateline' => time(),
            'type' => '0',
        );
        DB::insert('fb_opinion_comment', $setarr);
        if (!$upid) {
            DB::update('fb_opinion', array('isreply' => '1', 'status' => '1', 'updatetime' => time()), array('id' => $fbid));
        } else {
            DB::update('fb_opinion_comment', array('isreply' => '1'), array('cid' => $upid));
        }
        if ($fbopinion['uid'] && $fbopinion['uid'] != $_G['uid']) {
            $message = '<a href="home.php?mod=space&uid=' . $fbopinion['uid'] . '" target="_blank">' . $fbopinion['username'] . '</a>' .
                    lang('plugin/fb_opinion', 'admin_reply_fb_opinion') .
                    ': <a href="plugin.php?id=fb_opinion:main&mod=view&fbid=' . $fbid . '">' . lang('plugin/fb_opinion', 'viewintro') . '</a>';
            notification_add($fbopinion['uid'], 'system', $message);
        }
        showmessage(lang('plugin/fb_opinion', 'tips_reply_succeed'), 'plugin.php?id=fb_opinion:main&mod=reply&fbid=' . $fbid);
    }

    if (submitcheck('questionsubmit', 1)) {
        if (trim($_POST['message']) == '') {
            showmessage(lang('plugin/fb_opinion', 'needreplymessage'), dreferer());
        }
        require_once libfile('function/home');
        $_POST['message'] = censor($_POST['message']);
        $_POST['message'] = addslashes(dhtmlspecialchars($_POST['message']));
        $_POST['message'] = getstr($_POST['message'], 1000, 1, 1);
        $_G['username'] = addslashes($_G['username']);
        $fbid = $fbopinion['id'];
        $setarr = array(
            'fbid' => $fbid,
            'upid' => '0',
            'message' => $_POST['message'],
            'uid' => $_G['uid'],
            'username' => $_G['username'],
            'dateline' => time(),
            'type' => '1',
        );
        $cid = DB::insert('fb_opinion_comment', $setarr, true);
        $message = '<a href="home.php?mod=space&uid=' . $_G['uid'] . '" target="_blank">' . $_G['username'] . '</a>' .
                lang('plugin/fb_opinion', 'question_fb_opinion') .
                ': <a href="plugin.php?id=fb_opinion:main&mod=reply&fbid=' . $fbid . '&upid=' . $cid . '">' . lang('plugin/fb_opinion', 'reply') . '</a>';
        if (!empty($admins)) {
            foreach ($admins as $adminid) {
                if ($adminid != $_G['uid']) {
                    notification_add($adminid, 'system', $message);
                }
            }
        }
        showmessage(lang('plugin/fb_opinion', 'question_fb_opinion_succeed'), 'plugin.php?id=fb_opinion:main&mod=view&fbid=' . $fbid);
    }
} elseif ($_GET['mod'] == 'list') {
    $plugin_nav = lang('plugin/fb_opinion', 'tab_nav_list');

    $perpage = 20;

    $page = empty($_GET['page']) ? 0 : intval($_GET['page']);
    if ($page < 1)
        $page = 1;
    $start = ($page - 1) * $perpage;

    $fblist = array();
    $count = 0;

    $theurl = 'plugin.php?id=fb_opinion:main&mod=list';
    $wheresql = "1";

//    if ($searchkey = stripsearchkey($_GET['searchkey'])) {
//        $wheresql .= " AND message LIKE '%$searchkey%'";
//        $searchkey = dhtmlspecialchars($searchkey);
//    }
    $count = DB::result(DB::query("SELECT COUNT(*) FROM " . DB::table('fb_opinion') . " WHERE $wheresql"), 0);

    if ($count) {
        $query = DB::query("SELECT id,uid,username,message,dateline,status FROM " . DB::table('fb_opinion') . "
		WHERE $wheresql
		ORDER BY updatetime DESC
		LIMIT $start,$perpage");
        while ($value = DB::fetch($query)) {
            if ($value['status'] == 2 || $value['status'] < 0) {
                continue;
            }
            if ($value['status'] == 1 || $value['uid'] == $_G['uid'] || $_G['adminid'] == 1) {
                $value['dateline'] = dgmdate($value['dateline']);
                $fbids[] = $value['id'];
                $fblist[] = $value;
            } else {
                $pricount++;
            }
        }
    }
    if ($fbids) {
        $firstanswerlist = array();
        $commentlist = array();
        foreach ($fbids as $fbid) {
            list($firstanswerlist[$fbid], $commentlist[$fbid]) = get_fb_opinion_comment($fbid);
        }
    }
    $multi = multi($count, $perpage, $page, $theurl);
} elseif ($_GET['mod'] == 'deletecomment') {
    if (!$allowmanage) {
        showmessage('message_can_not_send_12', dreferer());
    }
    $cid = isset($_GET['cid']) ? intval($_GET['cid']) : 0;
    if (!$cid) {
        showmessage('parameters_error');
    }
    DB::query("DELETE FROM " . DB::table('fb_opinion_comment') . " WHERE cid='$cid' OR upid='$cid'");
    showmessage("article_delete_success", dreferer());
} else {
    showmessage('undefined_action');
}
include template('fb_opinion:main');

/**
 * get one feedback opinion comment list
 * @param type $fbid
 * $firstanswerlist the admin answer the first question
 * $commentlist all opinions and answers but first opinion and answer
 */
function get_fb_opinion_comment($fbid) {
    $commentlist = array(); //comment list
    $opinionlist = array();
    $answerlist = array();
    $firstanswerlist = array(); //reply the first opinion
    $count = DB::result(DB::query("SELECT COUNT(*) FROM " . DB::table('fb_opinion_comment') . " WHERE fbid='$fbid'"), 0);
    if (empty($count)) {
        return array();
    }
    $query = DB::query("SELECT * FROM " . DB::table('fb_opinion_comment') . " WHERE fbid='$fbid' ORDER BY dateline");
    while ($value = DB::fetch($query)) {
        $value['dateline'] = dgmdate($value['dateline']);
        if (!$value['upid'] && $value['type']) {
            $opinionlist[] = $value;
        } else {
            if (!$value['upid']) {
                $firstanswerlist[] = $value;
            } else {
                $answerlist[$value['upid']][] = $value;
            }
        }
    }
    if (!empty($opinionlist)) {
        foreach ($opinionlist as $value) {
            $commentlist[] = $value;
            if (!empty($answerlist[$value['cid']])) {
                foreach ($answerlist[$value['cid']] as $val) {
                    $commentlist[] = $val;
                }
            }
        }
    }
    return array($firstanswerlist, $commentlist);
}

?>
