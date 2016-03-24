<?php

/**
 * Copyright 2001-2099 1314学习网.
 * This is NOT a freeware, use is subject to license terms
 * $Id: baidu_search_xml.class.php 35310 2016-02-09 17:29:08Z zhuge $
 * 应用售后问题：http://www.discuz.1314study.com/services.php?mod=issue
 * 应用售前咨询：QQ 15326940
 * 应用定制开发：QQ 643306797
 * 本插件为 1314学习网（www.1314study.com） 独立开发的原创插件, 依法拥有版权。
 * 未经允许不得公开出售、发布、使用、修改，如需购买请联系我们获得授权。
 */

if (!defined('IN_DISCUZ')) {
exit('Access Denied');
}
require_once libfile('function/post');
class baidu_search_xml {

    public $sitemapxml = ''; //xml
    public $num = 0; //计数
    public $single_num; //单卷大小
    public $type; //生成哪些数据
    public $wherefid = ''; //板块查询条件
    public $siteurl; //siteurl
    public $charset = 'UTF-8'; //charset

    public function __construct($admin = 0) {
        global $_G, $pluginid;
        $splugin_setting = $_G['cache']['plugin']['addon_baidu_search'];
        $this->bbname = $this->xmlSafeStr($_G['setting']['bbname']);
        $this->siteurl = $this->xmlSafeStr($_G['siteurl']);
        $this->dir = 'data/baidu_search';
        $this->single_num = $splugin_setting['study_single_num'] ? $splugin_setting['study_single_num'] : 1000;
        $this->type = (array) unserialize($splugin_setting['study_type']);
        $this->study_thread_way = $splugin_setting['study_thread_way'] == 1 ? 'common' : 'forum';
        $this->study_thread_image = $splugin_setting['study_thread_image']? 1 : 0;
        
        if (!in_array(1314, $this->type)) {
            dmkdir(DISCUZ_ROOT . $this->dir);
            if (in_array(1, $this->type)) {
                $study_fids = (array) unserialize($splugin_setting['study_fids']);
                $infids = $this->list_array($study_fids);
                if ($infids) {
                    $this->wherefid = " AND fid IN($infids)";
                } else {
                    $this->wherefid = '';
                }
                $count = DB::result_first("SELECT count(*) FROM " . DB::table("forum_thread") . " WHERE displayorder >= 0 " . $this->wherefid);
                if ($admin) {
                    if (empty($_GET['type']) || $_GET['type'] == 1) {
                        $i = intval($_GET['i']) ? intval($_GET['i']) : 0;
                        if ($i < $count) {
                        	$t1 = microtime(true);
                            $this->createThread($i);
                            $t2 = microtime(true);
                            $date = '&#xFF0C;&#x8017;&#x65F6;'.round($t2-$t1,3).'&#x79D2;';
                            $url = 'action=plugins&operation=config&do=' . $pluginid . '&identifier=addon_baidu_search&pmod=updatexml&type=1&i=' . ($i + $this->single_num) . '&formhash=' . $_G['formhash'];
                            cpmsg('&#x8BBA;&#x575B;&#x5E16;&#x5B50; <font color=red>' . ($i + 1) . ' - ' . ($i + $this->single_num) . '</font> &#x5DF2;&#x5904;&#x7406;&#x5B8C;&#x6BD5;&#xFF0C;&#x81EA;&#x52A8;&#x8DF3;&#x8F6C;&#x5230;&#x4E0B;&#x4E00;&#x6279;&#x5E16;&#x5B50;&#xFF0C;&#x8BF7;&#x52FF;&#x5173;&#x95ED;&#x7A97;&#x53E3;'.$date, $url, 'loading');
                        } else {
                            $_GET['i'] = 0;
                            $_GET['type'] = 2;
                        }
                    }
                } else {
                    $quotients = $count / $this->single_num;
                    $i = (ceil($quotients) - 1) * $this->single_num;
                    $this->createThread($i);
                }
            }
            if (in_array(2, $this->type)) {
                $count = DB::result_first("SELECT count(*) FROM " . DB::table("portal_article_title") . " WHERE `status`=0");
                if ($admin) {
                    if (empty($_GET['type']) || $_GET['type'] == 2) {
                        $i = intval($_GET['i']) ? intval($_GET['i']) : 0;
                        if ($i < $count) {
                        	$t1 = microtime(true);
                            $this->createArticle($i);
                            $t2 = microtime(true);
                            $date = '&#xFF0C;&#x8017;&#x65F6;'.round($t2-$t1,3).'&#x79D2;';
                            $url = 'action=plugins&operation=config&do=' . $pluginid . '&identifier=addon_baidu_search&pmod=updatexml&type=2&i=' . ($i + $this->single_num) . '&formhash=' . $_G['formhash'];
                            cpmsg('&#x95E8;&#x6237;&#x6587;&#x7AE0; <font color=red>' . ($i + 1) . ' - ' . ($i + $this->single_num) . '</font> &#x5DF2;&#x5904;&#x7406;&#x5B8C;&#x6BD5;&#xFF0C;&#x81EA;&#x52A8;&#x8DF3;&#x8F6C;&#x5230;&#x4E0B;&#x4E00;&#x6279;&#x5E16;&#x5B50;&#xFF0C;&#x8BF7;&#x52FF;&#x5173;&#x95ED;&#x7A97;&#x53E3;'.$date, $url, 'loading');
                        } else {
                            $_GET['i'] = 0;
                        }
                    }
                } else {
                    $quotients = $count / $this->single_num;
                    $i = (ceil($quotients) - 1) * $this->single_num;
                    $this->createArticle($i);
                }
            }
            $this->build_index_xml();
        }
    }

    function build_index_xml() {
        global $_G;
        $splugin_setting = $_G['cache']['plugin']['addon_baidu_search'];
        $xml = array('index' => '');
        if($splugin_setting['study_thread_way'] == 2){
        	$xml['forum'] = '';
        }
        $dir = DISCUZ_ROOT . $this->dir;
        $list = $this->my_scandir($dir);
        foreach ($list as $file) {
            $file_location = $dir . "/" . $file;
            if ($file != "." && $file != ".." && $file != ".svn") {
                $file_info = pathinfo($file_location);
                if ($file_info['extension'] == 'xml' && strpos($file, 'baidu_search_xml_') !== false) {
                    if(strpos($file, 'baidu_search_xml_forum') !== false){
                    	$type = 'forum';
                    }else{
                    	$type = 'index';	
                    }
                    $filemtime = filemtime($file_location);
                    $filemtime = $filemtime ? $filemtime : $_G['timestamp'];
                    $lastmod = dgmdate($filemtime, 'Y-m-d');
                    $xml[$type] .= "\n".'<sitemap>
<loc>' . $this->siteurl . $this->dir . '/' . $file . '</loc>
<lastmod>' . $lastmod . '</lastmod>
</sitemap>';
                }
            }
        }
        foreach($xml as $key => $value){
        	$xml = "<?xml version=\"1.0\" encoding=\"" . $this->charset . "\"?>\n<sitemapindex>{$value}\n</sitemapindex>\n";
	        $fp = fopen(DISCUZ_ROOT . 'baidu_search_'.$key.'.xml', 'w');
	        fwrite($fp, $xml);
	        fclose($fp);
	    }
    }

    function my_scandir($dir) {
        $list = array();
        $list = scandir($dir);
        if (empty($list)) {
            $dh = opendir($dir);
            while (false !== ($filename = readdir($dh))) {
                $list[] = $filename;
            }
        }
        return (array) $list;
    }

    function createThread($i) {
        global $_G;
        $threadlist = array();
//		$query = DB::query("SELECT * FROM ".DB::table("forum_thread")." WHERE displayorder >= 0 ".$this->wherefid." ORDER BY tid ASC limit $i,".$this->single_num);
//		while($result = DB::fetch($query)){
//			$post = DB::fetch_first("SELECT pid,message,status AS pstatus FROM ".DB::table("forum_post")." WHERE tid = '".intval($result['tid'])."' AND first = 1");
//			$threadlist[] = array_merge($result, $post);
//		}
		$this->wherefid = str_replace('AND fid', 'AND t.fid', $this->wherefid);
		if($this->study_thread_image){
			$query = DB::query("SELECT t.*,p.pid,p.message,p.status AS pstatus,i.remote,i.attachment FROM ".DB::table("forum_thread")." t LEFT JOIN ".DB::table("forum_post")." p ON t.tid = p.tid AND p.first = 1 LEFT JOIN ".DB::table("forum_threadimage")." i ON t.tid = i.tid WHERE t.displayorder >= 0 ".$this->wherefid." ORDER BY t.tid ASC limit $i,".$this->single_num);
		}else{
			$query = DB::query("SELECT t.*,p.pid,p.message,p.status AS pstatus FROM ".DB::table("forum_thread")." t LEFT JOIN ".DB::table("forum_post")." p ON t.tid = p.tid AND p.first = 1 WHERE t.displayorder >= 0 ".$this->wherefid." ORDER BY t.tid ASC limit $i,".$this->single_num);
		}
    	while($result = DB::fetch($query)){
			$threadlist[] = $result;
		}
		$forumdomain = $this->get_appdomain('forum');
        if($this->study_thread_way == 'common'){
        	
        	$forumlist = $this->get_forum_list();
        	$homedomain = $this->get_appdomain('home');
			foreach($threadlist as $thread){
	        		$forum = $forumlist[$thread['fid']];
					if(in_array('forum_viewthread', $_G['setting']['rewritestatus'])){
						$thread['url'] = str_replace(array('{tid}','{page}','{prevpage}'), array($thread['tid'], 1, 1), $_G['setting']['rewriterule']['forum_viewthread']);
					}else{
						$thread['url'] = 'forum.php?mod=viewthread&amp;tid='.$thread['tid'];
					}
					if($forumdomain){
						$thread['url'] = 'http://'.$forumdomain.'/'.$thread['url'];
					}else{
						$thread['url'] = $this->siteurl.$thread['url'];
					}
					$thread['message'] = $thread['message'] ? messagecutstr($thread['message'], 400, '') : '';
					$thread['lastpost'] = dgmdate($thread['lastpost'], 'Y-m-d');
					$thread['pubTime'] = dgmdate($thread['dateline'], 'Y-m-d').'T'.dgmdate($thread['dateline'], 'H:i:s');
					
					if(in_array('home_space', $_G['setting']['rewritestatus'])){
						$thread['authorurl'] = str_replace(array('{user}', '{value}'), array('uid', $thread['authorid']), $_G['setting']['rewriterule']['home_space']);
					}else{
						$thread['authorurl'] = 'home.php?mod=space&uid='.$thread['authorid'];
					}
					if($homedomain){
						$thread['authorurl'] = 'http://'.$homedomain.'/'.$thread['authorurl'];
					}else{
						$thread['authorurl'] = $this->siteurl.$thread['authorurl'];
					}
					
					$thread['avatar'] = $_G['setting']['ucenterurl'] . '/avatar.php?uid=' . $thread['authorid'] . '&size=middle';
					$thread['forumname'] = $forumlist[$thread['fid']]['name'];
	            	
	            	if(in_array('forum_forumdisplay', $_G['setting']['rewritestatus'])){
						$thread['forumurl'] = str_replace(array('{fid}', '{page}'), array($thread['fid'], 1), $_G['setting']['rewriterule']['forum_forumdisplay']);
					}else{
						$thread['forumurl'] = 'forum.php?mod=forumdisplay&fid='.$thread['fid'];
					}
					if($forumdomain){
						$thread['forumurl'] = 'http://'.$forumdomain.'/'.$thread['forumurl'];
					}else{
						$thread['forumurl'] = $this->siteurl.$thread['forumurl'];
					}
	            	
//					if($this->study_thread_image){
//						$threadimage = DB::fetch_first('SELECT * FROM '.DB::table('forum_threadimage')." WHERE tid = '".intval($thread['tid'])."' ORDER BY tid DESC");
//						if ($threadimage['remote']) {
//	                        $thread['threadimage'] = $_G['setting']['ftp']['attachurl'] . 'forum/' . $threadimage['attachment'];
//	                    } else {
//	                        $thread['threadimage'] = $_G['siteurl'] . $_G['setting']['attachurl'] . 'forum/' . $threadimage['attachment'];
//	                    }
//					}
					if($this->study_thread_image){
						if ($thread['remote']) {
	                        $thread['threadimage'] = $_G['setting']['ftp']['attachurl'] . 'forum/' . $thread['attachment'];
	                    } else {
	                        $thread['threadimage'] = $_G['siteurl'] . $_G['setting']['attachurl'] . 'forum/' . $thread['attachment'];
	                    }
					}
					
					$this->sitemapxml .= $this->sitemap_thread_format($thread, array(), 'weekly', '0.8');
					$this->num++;
			}
        }else{
	        //主题分类
	        $threadtypes = array();
	        $querytmp = DB::query("SELECT * FROM " . DB::table('forum_threadclass'));
	        while ($value = DB::fetch($querytmp)) {
	            $threadtypes[$value['typeid']] = $value;
	        }
	        $forumlist = $this->get_forum_list();
	        $special_arr = array('0' => 0, '1' => 1, '2' => 0, '3' => 2, '4' => 5, '5' => 3);
	        foreach ($threadlist as $thread) {
	        	//print_r($thread);exit;
	            $forum = $forumlist[$thread['fid']];
	            if (!in_array($forum['status'], array(1, 3))) {
	                continue;
	            }
	            if ($forum['status'] == 1 && $forum['viewperm']) {
	                $_p = explode("\t", $forum['viewperm']);
	                //检查游客组
	                if (!in_array('7', $_p)) {
	                    continue;
	                }
	            }
	            if ($forum['status'] == 3 && $forum['gviewperm'] == 0) {
	                continue;
	            }
	            if ($thread['readperm'] > 1) {
	                continue;
	            }
	
	            if (in_array('forum_viewthread', $_G['setting']['rewritestatus'])) {
	                $thread['url'] = str_replace(array('{tid}', '{page}', '{prevpage}'), array($thread['tid'], 1, 1), $_G['setting']['rewriterule']['forum_viewthread']);
	            } else {
	                $thread['url'] = 'forum.php?mod=viewthread&tid=' . $thread['tid'];
	            }
	            if ($forumdomain) {
	                $thread['url'] = 'http://' . $forumdomain . '/' . $thread['url'];
	            } else {
	                $thread['url'] = $this->siteurl . $thread['url'];
	            }
	
	            $thread['lastmod'] = dgmdate($thread['lastpost'], 'Y-m-d');
	            $thread['avatar'] = $this->encode_url(trim($_G['setting']['ucenterurl'] . '/avatar.php?uid=' . $thread['authorid'] . '&size=middle'));
	            $thread['classify'] = $threadtypes[$thread['typeid']]['name'];
	            $thread['classify'] = $threadsorts[$thread['sortid']]['name'];
	            $thread['lastreplytime'] = dgmdate($thread['lastpost'], 'Y-m-d') . 'T' . dgmdate($thread['lastpost'], 'H:i:s');
	            $thread['hot'] = ($_G['setting']['heatthread']['iconlevels'][0] && $thread['heats'] > $_G['setting']['heatthread']['iconlevels'][0]) ? 1 : 0;
	            $thread['trading'] = $thread['special'] == 2 ? 1 : 0;
	            $thread['special'] = intval($special_arr[$thread['special']]);
	            $thread['forumname'] = $forumlist[$thread['fid']]['name'];
	            $thread['forumicon'] = $this->get_forumimg($forumlist[$thread['fid']]);
	            $thread['boardmaster'] = $forumlist[$thread['fid']]['moderators'];
	            
	            $postlist = array();
	            $postlist[0]['postcontent'] = $thread['message'] ? messagecutstr($thread['message'], 400, '') : '';
	            $postlist[0]['createdtime'] = dgmdate($thread['dateline'], 'Y-m-d') . 'T' . dgmdate($thread['dateline'], 'H:i:s');
	            if ($thread['price'] > 0 || $thread['pstatus'] % 2 == 1) { //主题价格 看相应主题帖需要花金币
                    $postlist[0]['viewauthority'] = 'Disallow';
                } else {
                    if (false !== stripos($thread['message'], '[/hide]')) {
                        $postlist[0]['viewauthority'] = 'PartlyAllow';
                    } else {
                        $postlist[0]['viewauthority'] = 'Allow';
                    }
                }
	            $postlist[0]['author'] = $thread['author'];
	            $postlist[0]['authoricon'] = $thread['avatar'];
	            $postlist[0]['postsequencenumber'] = 1;
//	            if($this->study_thread_image){
//	            	//附件
//        			$attachlist = $this->get_attachment_by_pids(array($thread['pid']), $thread['tid']);
//	            	if (!empty($attachlist[$thread['pid']])) {
//		                foreach ($attachlist[$thread['pid']] as $a) {
//		                	$attach = array();
//		                    $attach['attachmentName'] = trim($a['filename']);
//		        			$attach['attachmentType'] = strtolower(fileext($attach['attachmentName']));
//		                    $attach['attachmentSize'] = $this->Size($a['filesize']);
//		                    $attach['attachmentDownloadCount'] = $a['downloads'];
//		                    $ap = $this->get_attachment_authority($a);
//		                    if ($ap > 0) {
//		                        $authority = $ap;
//		                    } elseif (empty($forum['getattachperm']) || (($t = explode("\t", $forum['getattachperm'])) && in_array(7, $t))) {
//		                        $authority = 0;
//		                    } else {
//		                        $authority = 4;
//		                    }
//		                    if ($ap == 0) {
//		                        $attachurl = $_G['setting']['attachurl'] . '/forum/' . $a['attachment'];
//		                        $attachurl = str_replace(array('/./', '//'), '/', $attachurl);
//		                    } else {
//		                        $attachurl = 'forum.php?mod=attachment&aid=' . aidencode($a['aid']);
//		                    }
//		                    $attach['attachmentDownloadAuthority'] = $authority;
//		                    $attach['attachmentUrl'] = $this->encode_url(trim($_G['siteurl'] . $attachurl));
//		                   	$postlist[0]['attachment'][] = $attach;
//		                }
//		            }
//	            }
				if($this->study_thread_image && $thread['attachment']){
	            	$attach = array();
	        		$attach['attachmentType'] = strtolower(fileext($thread['attachment']));
	        		$attach['attachmentDownloadAuthority'] = 0;
	        		if ($thread['remote']) {
                        $attach['attachmentUrl'] = $_G['setting']['ftp']['attachurl'] . 'forum/' . $thread['attachment'];
                    } else {
                        $attach['attachmentUrl'] = $_G['siteurl'] . $_G['setting']['attachurl'] . 'forum/' . $thread['attachment'];
                    }
	        		$attach['attachmentUrl'] = $this->encode_url(trim($_G['siteurl'] . $attach['attachmentUrl']));
	                $postlist[0]['attachment'][] = $attach;
	            }
	            $this->sitemapxml .= $this->sitemap_thread_format($thread, $postlist, 'weekly', '0.8');
	            $this->num++;
	        }
	    }
	    unset($threadlist);
        $this->build_xml('thread', $i);
    }

    function createArticle($i) {
        global $_G;
        $articlelist = array();
        $query = DB::query("SELECT aid,title,summary,dateline FROM " . DB::table("portal_article_title") . " WHERE `status`=0 ORDER BY aid ASC limit $i," . $this->single_num);
        while ($result = DB::fetch($query)) {
            $articlelist[] = $result;
        }
        $subdomain = $this->get_appdomain('portal');
        foreach ($articlelist as $article) {
            if (in_array('portal_article', $_G['setting']['rewritestatus'])) {
                $url = str_replace(array('{id}', '{page}'), array($article['aid'], 1), $_G['setting']['rewriterule']['portal_article']);
            } else {
                $url = 'portal.php?mod=view&aid=' . $article['aid'];
            }
            if ($subdomain) {
                $url = 'http://' . $subdomain . '/' . $url;
            } else {
                $url = $this->siteurl . $url;
            }
            $this->sitemapxml .= $this->sitemap_format($url, dgmdate($article['dateline'], 'Y-m-d'), 'weekly', '0.8', $article['title'], $article['summary'], dgmdate($article['dateline'], 'Y-m-d') . 'T' . dgmdate($article['dateline'], 'H:i:s'));
            $this->num++;
        }
        $this->build_xml('article', $i);
    }

    function build_xml($type, $i) {
        if ($this->num > 0) {
        	if($type == 'thread' && $this->study_thread_way == 'forum'){
        		$type = 'forum_'.$type;
	        }else{
	        	$type = 'common_'.$type;	
	        }
            $xmlname = 'baidu_search_xml_' .$type. '_' . $i . '.xml';
            $sitemapxml = "<?xml version=\"1.0\" encoding=\"" . $this->charset . "\"?>\n<urlset>\n" . $this->sitemapxml . "\n</urlset>\n";
            if (strtolower(CHARSET) != 'utf-8') {
                $sitemapxml = diconv($sitemapxml, CHARSET, 'UTF-8');
            }
            $fp = fopen(DISCUZ_ROOT . $this->dir . '/' . $xmlname, 'w');
            fwrite($fp, $sitemapxml);
            fclose($fp);
            $this->num = 0;
            $this->sitemapxml = '';
        }
    }

    function sitemap_thread_format($thread, $postlist = array(), $changefreq = 'always', $priority = '1.0') {
        global $_G;
        $xml = '';
        $thread = $this->xmlSafeStr($thread);
        if($this->study_thread_way == 'common'){
	        $xml = "
	        <url>
			<loc>$thread[url]</loc>
			<lastmod>$thread[lastpost]</lastmod>
			<changefreq>$changefreq</changefreq>
			<priority>$priority</priority>
			<data>
			<display>
			<title>$thread[subject]</title>
			<content>$thread[message]</content>
			<pubTime>$thread[pubTime]</pubTime>
			<breadCrumb title=\"".$this->bbname."\" url=\"".$this->siteurl."\"/>
			<breadCrumb title=\"{$thread[forumname]}\" url=\"{$thread[forumurl]}\"/>
			";
			if($thread['threadimage']){
				$xml .= "<image loc=\"{$thread[threadimage]}\" title=\"{$thread[subject]}\"/>";
			}
			$xml .= "
			<author nickname=\"{$thread[author]}\" url=\"{$thread[authorurl]}\" thumbnail=\"{$thread[avatar]}\"/>
			<replyCount>{$thread[replies]}</replyCount>
			</display>
			</data>
			</url>\n";
        }else{
	        $postlist = $this->xmlSafeStr($postlist);
	        $xml = "<url>
			<loc><![CDATA[{$thread[url]}]]></loc>
			<lastmod>{$thread[lastmod]}</lastmod>
			<changefreq>$changefreq</changefreq>
			<priority>$priority</priority>
			<data>
				<thread>
					<threadUrl><![CDATA[{$thread[url]}]]></threadUrl>
					<author>{$thread[author]}</author>
					<authorIcon>{$thread[avatar]}</authorIcon>
					<threadTitle><![CDATA[{$thread[subject]}]]></threadTitle>
					<threadClassify>{$thread[classify]}</threadClassify>
					<stickyLevel>{$thread[displayorder]}</stickyLevel>
					<isDigest>{$thread[digest]}</isDigest>\n";
			//print_r($postlist);exit;
	        foreach($postlist as $post){
	        	$xml .= "
	    		<post>
				<postContent><![CDATA[{$post[postcontent]} ]]></postContent>
				<createdTime>{$post[createdtime]}</createdTime>
				<viewAuthority>{$post[viewauthority]}</viewAuthority>
				<author>{$post[author]}</author>
				<authorIcon>{$post[authoricon]}</authorIcon>
				<postSequenceNumber>{$post[postsequencenumber]}</postSequenceNumber>\n";
				foreach($post['attachment'] as $attach){
					$xml .= "
					<attachment>\n" .
	                "<attachmentName><![CDATA[{$attach[attachmentName]}]]></attachmentName>\n" .
	                "<attachmentUrl><![CDATA[{$attach[attachmentUrl]}]]></attachmentUrl>\n" .
	                "<attachmentSize><![CDATA[{$attach[attachmentSize]}]]></attachmentSize>\n" .
	                "<attachmentDownloadAuthority>{$attach[attachmentDownloadAuthority]}</attachmentDownloadAuthority>\n" .
	                "<attachmentDownloadCount>{$attach[attachmentDownloadCount]}</attachmentDownloadCount>\n" .
	                "<attachmentType><![CDATA[1]]></attachmentType>\n" .
	                "</attachment>\n";
				}
				$xml .= "</post>\n";
	        }
	        $xml .= "
	        			<replyCount>{$thread[replies]}</replyCount>
						<viewCount>{$thread[views]}</viewCount>
						<lastReplier>
							<accountName>{$thread[lastposter]}</accountName>
						</lastReplier>
						<lastReplyTime>{$thread[lastreplytime]}</lastReplyTime>
						<isHotThread>{$thread[hot]}</isHotThread>
						<specialType>{$thread[special]}</specialType>
						<isTrading>{$thread[trading]}</isTrading>
						<favorCount>{$thread[favtimes]}</favorCount>
						<sharedCount>{$thread[sharetimes]}</sharedCount>
						<supportCount>{$thread[recommend_add]}</supportCount>
						<opposeCount>{$thread[recommend_sub]}</opposeCount>
						<forumIn>
							<forumName>{$thread[forumname]}</forumName>
							<forumIcon>{$thread[forumicon]}</forumIcon>
							<boardMasterID>{$thread[boardmaster]}</boardMasterID>
						</forumIn>
					</thread>
				</data>
			</url>\n";
		}
        return $xml;
    }

    function sitemap_format($url, $date, $changefreq = 'always', $priority = '1.0', $title, $content, $pubTime) {
        $title = $this->xmlSafeStr($title);
        $content = $this->xmlSafeStr($content);
        $xml = "<url>
<loc>$url</loc>
<lastmod>$date</lastmod>
<changefreq>$changefreq</changefreq>
<priority>$priority</priority>
<data>
<display>
<title>$title</title>
<content>$content</content>
<pubTime>$pubTime</pubTime>
</display>
</data>
</url>";
        return $xml;
    }

    function list_array($fids_show) {
        $result = '';
        if (is_array($fids_show)) {
            $i = '1314';
            foreach ($fids_show as $id => $fid) {
                if (!empty($fid) && $fid) {
                    if ($i == '1314') {
                        $result .= $fid;
                        $i = 'DIY';
                    } else {
                        $result .= ',' . $fid;
                    }
                }
            }
        }
        return $result;
    }

    function get_appdomain($item) {
        global $_G;
        $domain = $_G['setting']['domain'];
        if ($domain['app'][$item]) {
            $return = $domain['app'][$item];
        } else {
            $return = $domain['app']['default'];
        }
        if (empty($return)) {
            $return = false;
        }
        return $return;
    }

    function xmlSafeStr($str) {
        if (is_array($str)) {
            foreach ($str as $key => $value) {
                $str[$key] = $this->xmlSafeStr($value);
            }
        } else {
            $str = dhtmlspecialchars($str);
            $str = str_replace(array('\'', '"', '>', '<'), array('', '', '', ''), $str);
            $str = $this->strip_invalid_xml($str);
            $str = preg_replace("/[\\x00-\\x08\\x0b-\\x0c\\x0e-\\x1f]/", '', $str);
        }
        return $str;
    }

    function strip_invalid_xml($value) {
        $ret = '';
        if (empty($value)) {
            return $value;
        }
        $length = strlen($value);
        for ($i = 0; $i < $length; $i++) {
            $current = ord($value[$i]);
            if ($current == 0x9 || $current == 0xA || $current == 0xD ||
                    ($current >= 0x20 && $current <= 0xD7FF) ||
                    ($current >= 0xE000 && $current <= 0xFFFD) ||
                    ($current >= 0x10000 && $current <= 0x10FFFF)) {
                $ret .= chr($current);
            } else {
                $ret .= ' ';
            }
        }
        return $ret;
    }

    function get_forum_list() {
        $return = array();
        $forumlist = C::t('forum_forum')->fetch_all_by_status(1);
        if (!empty($forumlist)) {
            $fids = array();
            foreach ($forumlist as $forum) {
                $fids[$forum['fid']] = $forum['fid'];
            }
            $forumfield = C::t('forum_forumfield')->fetch_all_by_fid($fids);
            $ret = array();
            foreach ($forumlist as $forum) {
                $return[$forum['fid']] = array_merge($forum, $forumfield[$forum['fid']]);
            }
        }
        return $return;
    }

    function get_forumimg($forum) {
        global $_G;
        $imgpath = '';
        if ($forum['icon']) {
            $parse = parse_url($forum['icon']);
            if (isset($parse['host'])) {
                $imgpath = $forum['icon'];
            } else {
                if ($forum['status'] != 3) {
                    $imgpath = $_G['setting']['attachurl'] . 'common/' . $forum['icon'];
                } else {
                    $imgpath = $_G['setting']['attachurl'] . 'group/' . $forum['icon'];
                }
            }
        } else {
            $imgpath = $_G['siteurl'] . $_G['style']['imgdir'] . '/forum.gif';
        }
        return $imgpath;
    }

    function get_postlist($forum, $thread) {
        global $_G;
        //$_G['ppp'
        $postlist = C::t('forum_post')->fetch_all_by_tid($thread['posttableid'], $thread['tid'], true, 'ASC', 0, 1);
        //如果没有内容
        if (empty($postlist)) {
            return false;
        }

        $attachpids = array();
        foreach ($postlist as $row) {
            $row['attachment'] > 0 && $attachpids[] = $row['pid'];
        }

        //附件
        $attachlist = empty($attachpids) ? array() : $this->get_attachment_by_pids($attachpids, $thread['tid']);
        $sequenceNumber = 1;
        foreach ($postlist as $pid => $eachpost) {
            $post = false;
            $images = array();
            //主题帖
            if (1 == $eachpost['first']) {
                if ($thread['price'] > 0 || $eachpost['status'] % 2 == 1) { //主题价格 看相应主题帖需要花金币
                    $post['viewauthority'] = 'Disallow';
                } else {
                    if (false !== stripos($eachpost['message'], '[/hide]')) {
                        $post['viewauthority'] = 'PartlyAllow';
                    } else {
                        $post['viewauthority'] = 'Allow';
                    }
                }
                $post['postsequencenumber'] = 1;
            } else {
                if (false !== stripos($eachpost['message'], '[/hide]')) {
                    $post['viewauthority'] = 'PartlyAllow';
                } else {
                    $post['viewauthority'] = 'Allow';
                }
                $post['postsequencenumber'] = $sequenceNumber;
            }
            $content = $thread['message'] ? messagecutstr($thread['message'], 400, '') : '';//$this->content_filter($eachpost, $forum, $images);
            $post['postcontent'] = $content;
            $post['createdtime'] = dgmdate($eachpost['dateline'], 'Y-m-d') . 'T' . dgmdate($eachpost['dateline'], 'H:i:s');
            $post['author'] = $eachpost['author'];
            $post['authoricon'] = $_G['setting']['ucenterurl'] . '/avatar.php?uid=' . $eachpost['authorid'] . '&size=middle';
            $sequenceNumber++;
            //如果有附件
            if ($post && !empty($attachlist[$pid])) {
                foreach ($attachlist[$pid] as $a) {
                	$attach = array();
                    $attach['attachmentName'] = trim($a['filename']);
        			$attach['attachmentType'] = strtolower(fileext($attach['attachmentName']));
                    $attach['attachmentSize'] = $this->Size($a['filesize']);
                    $attach['attachmentDownloadCount'] = $a['downloads'];
                    $ap = $this->get_attachment_authority($a);
                    if ($ap > 0) {
                        $authority = $ap;
                    } elseif (empty($forum['getattachperm']) || (($t = explode("\t", $forum['getattachperm'])) && in_array(7, $t))) {
                        $authority = 0;
                    } else {
                        $authority = 4;
                    }
                    if ($ap == 0) {
                        $attachurl = $_G['setting']['attachurl'] . '/forum/' . $a['attachment'];
                        $attachurl = str_replace(array('/./', '//'), '/', $attachurl);
                    } else {
                        $attachurl = 'forum.php?mod=attachment&aid=' . aidencode($a['aid']);
                    }
                    $attach['attachmentDownloadAuthority'] = $authority;
                    $attach['attachmentUrl'] = $this->encode_url(trim($_G['siteurl'] . $attachurl));
                   	$post['attachment'][] = $attach;
                }
            }
            //图片
            if ($post && !empty($images)) {
                foreach ($images as $x) {
                    if (intval($x) > 0)
                        continue; //不要附件
                    if (0 != strncasecmp($x, 'http://', 7))
                        continue; //非网络图片不要
                    $attach = array();
                    $attach['attachmentDownloadAuthority'] = 0;
                    $attach['attachmentUrl'] = $this->encode_url(trim($x));
                   	$post['attachment'][] = $attach;
                }
            }
            $postlist[$pid] = $post;
        }
        return $postlist;
    }

    function get_attachment_by_pids(array $pids, $tid) {
        $return = array();
        if (!empty($pids)) {
            $alist = C::t('forum_attachment_n')->fetch_all_by_id("tid:{$tid}", 'pid', $pids, '', false, false, false, 5);
            if (!empty($alist)) {
                $amlist = C::t('forum_attachment')->fetch_all_by_id('pid', $pids);
                foreach ($amlist as $row) {
                    $alist[$row['aid']] = array_merge($alist[$row['aid']], $row);
                }
                $return = array();
                foreach ($alist as $x) {
                    $return[$x['pid']][] = $x;
                }
            }
        }
        return $return;
    }

    function content_filter($post, $forum, array &$images = array()) {
        require_once libfile('function/discuzcode');
        $data = trim($post['message']);
        $attach = array();
        if (false !== stripos($data, '[attach]')) {
            preg_match_all('/\[attach\](\d+)\[\/attach\]/i', $data, $attach);
            $data = preg_replace('/\[attach\]\d+\[\/attach\]/i', '', $data); //过滤掉附件
            if (isset($attach[1])) {
                $images = array_merge($images, $attach[1]);
            }
        }
        $image1 = $image2 = array();
        if (false !== stripos($data, '[img')) {
            preg_match_all('/\[img\]\s*([^\[\<\r\n]+?)\s*\[\/img\]/ies', $data, $image1);
            preg_match_all('/\[img=\d{1,4}[x|\,]\d{1,4}\]\s*([^\[\<\r\n]+?)\s*\[\/img\]/ies', $data, $image2);
            if (isset($image1[1])) {
                $images = array_merge($images, $image1[1]);
            }
            if (isset($image2[1])) {
                $images = array_merge($images, $image2[1]);
            }
        }
        $data = discuzcode($data, $post['smileyoff'], $post['bbcodeoff'], $post['htmlon'] & 1, $forum['allowsmilies'], $forum['allowbbcode'], $forum['allowimgcode'], $forum['allowhtml'], $forum['jammer']);
        $data = strip_tags($data);
        return $data;
    }

    function get_attachment_authority(array $attach) {
        if ($attach['price'])
            return 3;
        if ($attach['readperm'] > 0 && $attach['readperm'] != 7)
            return 4;
        return 0;
    }
	public function Size($size) {
        if (preg_match('#^\d+$#', $size)) {
            if ($size > 1024 * 1024) {
                $size = round($size / 1024 / 1024, 1) . 'M';
            } elseif ($size > 1024) {
                $size = round($size / 1024, 1) . 'K';
            } else {
                $size .= 'B';
            }
        }
        return $size;
    }
    
    function encode_url($url){
	    $hexchars = '0123456789ABCDEF';
	    $i = 0;
	    $ret = '';
	    while (isset($url[$i])) {
	        $c = $url[$i];
	        $j = ord($c);
	        if ($c == ' ') {
	            $ret .= '%20';
	        } elseif ($j > 127) {
	            $ret .= '%' . $hexchars[$j >> 4] . $hexchars[$j & 15];
	        } else {
	            $ret .= $c;
	        }
	        $i++;
	    }
	    return $ret;
	}
}
