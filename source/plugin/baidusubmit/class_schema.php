<?php

if (!defined('IN_DISCUZ')) {
    exit('Access Denied');
}

class BaiduPostSchema
{

    protected $_postContent;
    protected $_createdTime;
    protected $_viewAuthority;
    protected $_isHost;
    protected $_postSequenceNumber;
    protected $_attachment = array();
    protected $_authorIcon; //用户头像
    protected $_author; //作者

    public function setAuthor($author)
    {
        $this->_author = baidu_strip_invalid_xml(baidu_diconv(trim($author), CHARSET, 'utf-8'));
    }

    public function setAuthorIcon($uid, $size = "small")
    {
        $authorIconUrl = baidu_get_avatar($uid, $size);
        $this->_authorIcon = $authorIconUrl;
    }

    public function setPostContent($content)
    {
        $this->_postContent = baidu_strip_invalid_xml(baidu_diconv(trim($content), CHARSET, 'utf-8'));
    }

    public function setCreatedTime($time)
    {
        $time = trim($time);
        $this->_createdTime = preg_match('#^\d+$#', $time) ? baidu_date_format($time) : $time;
    }

    public function setViewAuthority($string)
    {
        $this->_viewAuthority = baidu_diconv(trim($string), CHARSET, 'utf-8');
    }

    public function setIsHost($ishost)
    {
        $this->_isHost = intval($ishost);
    }

    public function setPostSequenceNumber($number)
    {
        $this->_postSequenceNumber = intval($number);
    }

    public function addAttachment(BaiduAttachmentSchema $attach)
    {
        $this->_attachment[] = $attach;
    }

    public function toXml()
    {
        $attach_xml = '';
        foreach ($this->_attachment as $x) {
            $attach_xml .= $x->toXml();
        }
        return
                "<post>" .
                "<postContent><![CDATA[{$this->_postContent}]]></postContent>" .
                "<createdTime>{$this->_createdTime}</createdTime>" .
                "<viewAuthority><![CDATA[{$this->_viewAuthority}]]></viewAuthority>" .
                "<author><![CDATA[{$this->_author}]]></author>" .
                "<authorIcon><![CDATA[{$this->_authorIcon}]]></authorIcon>" .
                //"<isHost>{$this->_isHost}</isHost>" .
                "<postSequenceNumber>{$this->_postSequenceNumber}</postSequenceNumber>" .
                $attach_xml .
                "</post>";
    }

}

class BaiduAttachmentSchema
{

    protected $_attachmentName; //<!-- attachmentName: 附件名称，含文件格式（txt、rar、mp3等），最少出现1次 最多出现1次，类型为字符串 -->
    protected $_attachmentUrl; //<!-- attachmentUrl: 附件访问地址，，最少出现1次 最多出现1次，类型为URL地址 -->
    protected $_attachmentSize; //<!-- attachmentSize: 附件大小，K、M、B等，最少出现0次 最多出现1次，类型为字符串 -->
    protected $_attachmentDownloadAuthority; //<!-- attachmentDownloadAuthority: 附件访问权限，0非登录(游客可访问）、1登录、2登录+回帖、3登录+积分、4登录+用户等级，最少出现0次 最多出现1次，类型为整数 -->
    protected $_attachmentDownloadCount; //<!-- attachmentDownloadCount: 附件下载次数，，最少出现0次 最多出现1次，类型为整数 -->
    protected $_attachmentType;

    public function setName($name)
    {
        $this->_attachmentName = baidu_strip_invalid_xml(baidu_diconv(trim($name), CHARSET, 'utf-8'));
        $this->_setType(strtolower(fileext($this->_attachmentName)));
    }

    public function setUrl($url)
    {
        $this->_attachmentUrl = baidu_encode_url(trim($url));
    }

    public function setSize($size)
    {
        if (preg_match('#^\d+$#', $size)) {
            if ($size > 1024 * 1024) {
                $size = round($size / 1024 / 1024, 1) . 'M';
            } elseif ($size > 1024) {
                $size = round($size / 1024, 1) . 'K';
            } else {
                $size .= 'B';
            }
        }
        $this->_attachmentSize = $size;
    }

    public function setDownloadAuthority($authority)
    {
        $this->_attachmentDownloadAuthority = intval($authority);
    }

    public function setDownloadCount($count)
    {
        $this->_attachmentDownloadCount = intval($count);
    }

    protected function _setType($extname)
    {
        /**
         * 0=普通
         * 1=图片
         * 2=音频
         * 3=视频
         * 4=下载
         */
        if (preg_match("/bittorrent|^torrent/", $extname)) {
            $typeid = 4;
        } elseif (preg_match("/pdf|^pdf/", $extname)) {
            $typeid = 4;
        } elseif (preg_match("/image|^(jpg|gif|png|bmp)/", $extname)) {
            $typeid = 1;
        } elseif (preg_match("/flash|^(swf|fla|flv|swi)/", $extname)) {
            $typeid = 3;
        } elseif (preg_match("/audio|video|^(wav|mid|mp3|m3u|wma|asf|asx|vqf|mpg|mpeg|avi|wmv)/", $extname)) {
            $typeid = 2;
        } elseif (preg_match("/real|^(ra|rm|rv)/", $extname)) {
            $typeid = 3;
        } elseif (preg_match("/htm|^(php|js|pl|cgi|asp)/", $extname)) {
            $typeid = 0;
        } elseif (preg_match("/text|^(txt|rtf|wri|chm)/", $extname)) {
            $typeid = 0;
        } elseif (preg_match("/word|powerpoint|^(doc|ppt)/", $extname)) {
            $typeid = 0;
        } elseif (preg_match("/^rar/", $extname)) {
            $typeid = 4;
        } elseif (preg_match("/compressed|^(zip|arj|arc|cab|lzh|lha|tar|gz)/", $extname)) {
            $typeid = 4;
        } elseif (preg_match("/octet-stream|^(exe|com|bat|dll)/", $extname)) {
            $typeid = 4;
        } else {
            $typeid = 0;
        }
        $this->_attachmentType = $typeid;
    }

    public function toXml()
    {
        return
                "<attachment>" .
                "<attachmentName><![CDATA[{$this->_attachmentName}]]></attachmentName>" .
                "<attachmentUrl><![CDATA[{$this->_attachmentUrl}]]></attachmentUrl>" .
                "<attachmentSize><![CDATA[{$this->_attachmentSize}]]></attachmentSize>" .
                "<attachmentDownloadAuthority>{$this->_attachmentDownloadAuthority}</attachmentDownloadAuthority>" .
                "<attachmentDownloadCount>{$this->_attachmentDownloadCount}</attachmentDownloadCount>" .
                "<attachmentType><![CDATA[{$this->_attachmentType}]]></attachmentType>" .
                "</attachment>";
    }

}

class BaiduThreadSchema
{

    protected $_forumName;  //版块名称
    protected $_threadUrl;  //链接地址
    protected $_threadTitle;  //主题title
    protected $_post = array();  //帖子
    protected $_replyCount;  //回复数
    protected $_viewCount;  //浏览数
    protected $_lastReplyTime;  //最后回复时间
    protected $_forumIcon; //版块图片
    protected $_moderators; //版主
    protected $_authorIcon; //用户头像
    protected $_author; //作者
    protected $_supportCount = null; //主题 支持数
    protected $_opposeCount = null; //主题 反对数
    protected $_isDigest; //是否精华
    protected $_stickyLevel; //置顶，0为不置顶，1为版面，2为分区，3为全局
    protected $_lastReplier; //最后回复人
    protected $_favorCount; //收藏数
    protected $_sharedCount; //分享数

    public function setFavorCount($favtimes)
    {
        $this->_favorCount = $favtimes;
    }

    public function setSharedCount($sharetimes)
    {
        $this->_sharedCount = $sharetimes;
    }

    public function setLastReplier($name)
    {
        $this->_lastReplier = baidu_strip_invalid_xml(baidu_diconv(trim($name), CHARSET, 'utf-8'));
    }

    public function setStickyLevel($level)
    {
        $this->_stickyLevel = $level;
    }

    public function setIsDigest($digest)
    {
        $this->_isDigest = $digest;
    }

    public function setSupportCount($count)
    {
        global $_G;
        $recommendthread = $_G['setting']['recommendthread'];
        if (isset($recommendthread['status'])) {
            $this->_supportCount = $count;
        }
    }

    public function setOpposeCount($count)
    {
        global $_G;
        $recommendthread = $_G['setting']['recommendthread'];
        if (isset($recommendthread['status'])) {
            $this->_opposeCount = $count;
        }
    }

    public function setAuthor($author)
    {
        $this->_author = baidu_strip_invalid_xml(baidu_diconv(trim($author), CHARSET, 'utf-8'));
    }

    public function setForumIcon($forumIcon)
    {
        global $_G;
        if ($forumIcon) {
            $url = $_G['siteurl'] . $_G['setting']['attachurl'] . "common/" . $forumIcon;
        } else {
            $url = $_G['siteurl'] . $_G['style']['imgdir'] . "/forum.gif";
        }
        $this->_forumIcon = $url;
    }

    public function setModerators($moderators)
    {
        $this->_moderators = baidu_strip_invalid_xml(baidu_diconv(trim($moderators), CHARSET, 'utf-8'));
    }

    public function setAuthorIcon($uid, $size = "small")
    {
        $authorIconUrl = baidu_get_avatar($uid, $size);
        $this->_authorIcon = $authorIconUrl;
    }

    public function setForumName($name)
    {
        $this->_forumName = baidu_strip_invalid_xml(baidu_diconv(trim($name), CHARSET, 'utf-8'));
    }

    public function setThreadUrl($url)
    {
        $this->_threadUrl = trim($url);
    }

    public function setThreadTitle($title)
    {
        $this->_threadTitle = baidu_strip_invalid_xml(baidu_diconv(trim($title), CHARSET, 'utf-8'));
    }

    public function setReplyCount($count)
    {
        $this->_replyCount = intval($count);
    }

    public function setViewCount($count)
    {
        $this->_viewCount = intval($count);
    }

    public function setLastReplyTime($time)
    {
        $time = trim($time);
        $this->_lastReplyTime = preg_match('#^\d+$#', $time) ? baidu_date_format($time) : $time;
    }

    public function addPost(BaiduPostSchema $post)
    {
        $this->_post[] = $post;
    }

    public function toXml()
    {
        $xml = "<url>" .
                "<loc><![CDATA[{$this->_threadUrl}]]></loc>" .
                "<lastmod><![CDATA[{$this->_lastReplyTime}]]></lastmod>" .
                "<data>" .
                "<thread>" .
                "<threadUrl><![CDATA[{$this->_threadUrl}]]></threadUrl>" .
                "<author><![CDATA[{$this->_author}]]></author>" .
                "<authorIcon><![CDATA[{$this->_authorIcon}]]></authorIcon>" .
                "<threadTitle><![CDATA[{$this->_threadTitle}]]></threadTitle>" .
                "<stickyLevel><![CDATA[{$this->_stickyLevel}]]></stickyLevel>" .
                "<isDigest><![CDATA[{$this->_isDigest}]]></isDigest>";
        foreach ($this->_post as $post) {
            $xml .= $post->toXml();
        }
        $xml .= "<replyCount>{$this->_replyCount}</replyCount>" .
                "<viewCount>{$this->_viewCount}</viewCount>" .
                "<lastReplier>" .
                "<accountName><![CDATA[{$this->_lastReplier}]]></accountName>" .
                "</lastReplier>" .
                "<lastReplyTime>{$this->_lastReplyTime}</lastReplyTime>" .
                "<favorCount>{$this->_favorCount}</favorCount>" .
                "<sharedCount>{$this->_sharedCount}</sharedCount>";
        if ($this->_supportCount != null && $this->_opposeCount != null) {
            $xml .= "<supportCount>{$this->_supportCount}</supportCount>" .
                    "<opposeCount>{$this->_opposeCount}</opposeCount>";
        }
        $xml .= "<forumIn>" .
                "<forumName><![CDATA[{$this->_forumName}]]></forumName>" .
                "<forumIcon><![CDATA[{$this->_forumIcon}]]></forumIcon>" .
                "<boardMasterID><![CDATA[{$this->_moderators}]]></boardMasterID>" .
                "</forumIn>" .
                "</thread>" .
                "</data>" .
                "</url>";
        return $xml;
    }

    public function toDeleteXml()
    {
        $xml = "<url><loc><![CDATA[{$this->_threadUrl}]]></loc></url>";
        return $xml;
    }

    public function toSitemapXml()
    {
        $date = date('Y-m-d');
        return "<url><loc><![CDATA[{$this->_threadUrl}]]></loc><lastmod>{$date}</lastmod></url>";
    }

}

class BaiduForumSchema
{

    protected $_boardName;
    protected $_boardUrl;
    protected $_logo;
    protected $_boardMaster = array();
    protected $_description;
    protected $_postCount;
    protected $_memberCount;
    protected $_activeMemberCount;
    protected $_hotPost = array();

    public function setBoardName($name)
    {
        $this->_boardName = baidu_strip_invalid_xml(baidu_diconv(trim($name), CHARSET, 'utf-8'));
    }

    public function setBoardUrl($url)
    {
        $this->_boardUrl = $url;
    }

    public function setLogo($url)
    {
        $this->_logo = $url;
    }

    public function setBoardMaster(BaiduBoardMasterSchema $boardMaster)
    {
        $this->_boardMaster[] = $boardMaster;
    }

    public function setDescripttion($description)
    {
        $this->_description = baidu_strip_invalid_xml(baidu_diconv(trim($description), CHARSET, 'utf-8'));
    }

    public function setPostCount($count)
    {
        $this->_postCount = $count;
    }

    public function setMemberCount($count)
    {
        $this->_memberCount = $count;
    }

    public function setActiveMemberCount($count)
    {
        $this->_activeMemberCount = $count;
    }

    public function setHotPost(BaiduHotPostSchema $hotPost)
    {
        $this->_hotPost[] = $hotPost;
    }

    public function toXml()
    {
        $xml = "<url>"
                . '<loc><![CDATA[' . $this->_boardUrl . ']]></loc>'
                . '<data>'
                . '<display>'
                . '<boardName><![CDATA[' . $this->_boardName . ']]></boardName>'
                . '<boardUrl><![CDATA[' . $this->_boardUrl . ']]></boardUrl>'
                . '<logo><![CDATA[' . $this->_logo . ']]></logo>';

        if ($this->_boardMaster) {
            foreach ($this->_boardMaster as $boardMaster) {
                $xml .= $boardMaster->toXml();
            }
        }
        $xml .= '<description><![CDATA[' . $this->_description . ']]></description>'
                . '<postCount>' . $this->_postCount . '</postCount>'
                . '<memberCount>' . $this->_memberCount . '</memberCount>'
                . '<activeMemberCount>' . $this->_activeMemberCount . '</activeMemberCount>';
        if ($this->_hotPost) {
            foreach ($this->_hotPost as $hotPost) {
                $xml .= $hotPost->toXml();
            }
        }
        $xml .= '</display></data></url>';
        return $xml;
    }

}

class BaiduBoardMasterSchema
{

    protected $_name;
    protected $_url;

    public function setName($name)
    {
        $this->_name = baidu_strip_invalid_xml(baidu_diconv(trim($name), CHARSET, 'utf-8'));
    }

    public function setUrl($url)
    {
        $this->_url = $url;
    }

    public function toXml()
    {
        return '<boardMaster>'
                . '<name><![CDATA[' . $this->_name . ']]></name>'
                . '<url><![CDATA[' . $this->_url . ']]></url>'
                . '</boardMaster>';
    }

}

class BaiduHotPostSchema
{

    protected $_threadUrl;
    protected $_postTitle;
    protected $_createTime;
    protected $_viewCount;
    protected $_replyCount;

    public function setThreadUrl($url)
    {
        $this->_threadUrl = $url;
    }

    public function setPostTitle($title)
    {
        $this->_postTitle = baidu_strip_invalid_xml(baidu_diconv(trim($title), CHARSET, 'utf-8'));
    }

    public function setCreateTime($time)
    {
        $this->_createTime = preg_match('#^\d+$#', $time) ? baidu_date_format($time) : $time;
    }

    public function setViewCount($count)
    {
        $this->_viewCount = $count;
    }

    public function setReplayCount($count)
    {
        $this->_replyCount = $count;
    }

    public function toXml()
    {
        return '<hotPost>'
                . '<threadUrl><![CDATA[' . $this->_threadUrl . ']]></threadUrl>'
                . '<postTitle><![CDATA[' . $this->_postTitle . ']]></postTitle>'
                . '<createTime>' . $this->_createTime . '</createTime>'
                . '<viewCount>' . $this->_viewCount . '</viewCount>'
                . '<replyCount>' . $this->_replyCount . '</replyCount>'
                . '</hotPost>';
    }

}

class BaiduForumUserSchema
{

    protected $_nickName;
    protected $_spaceUrl;
    protected $_photoUrl;
    protected $_profile;
    protected $_level;
    protected $_postCount;
    protected $_fanCount;
    protected $_friendCount;
    protected $_gender;
    protected $_location;
    protected $_constellation;
    protected $_birthday;
    protected $_registerTime;
    protected $_netAge;
    protected $_lastLoginTime;
    protected $_signatureText;
    protected $_signaturePhoto;

    public function getNickName()
    {
        return $this->_nickName;
    }

    public function setNickName($name)
    {
        $this->_nickName = baidu_strip_invalid_xml(baidu_diconv(trim($name), CHARSET, 'utf-8'));
    }

    public function setSpaceUrl($url)
    {
        $this->_spaceUrl = $url;
    }

    public function setPhotoUrl($url)
    {
        $this->_photoUrl = $url;
    }

    public function setProfile($profile)
    {
        $this->_profile = baidu_strip_invalid_xml(baidu_diconv(trim($profile), CHARSET, 'utf-8'));
    }

    public function setLevel($level)
    {
        $this->_level = baidu_diconv(trim($level), CHARSET, 'utf-8');
    }

    public function setPostCount($count)
    {

        $this->_postCount = $count;
    }

    public function setFanCount($count)
    {
        $this->_fanCount = $count;
    }

    public function setFriendCount($count)
    {
        $this->_friendCount = $count;
    }

    public function setGender($gender)
    {
        $zh_gender = array(0 => "保密", 1 => "男", 2 => "女");
        $this->_gender = $zh_gender[$gender];
    }

    public function setLocation($location)
    {
        $this->_location = baidu_diconv(trim($location), CHARSET, 'utf-8');
    }

    public function setConstellation($constellation)
    {
        $this->_constellation = baidu_diconv(trim($constellation), CHARSET, 'utf-8');
        ;
    }

    public function setBirthday($birthday)
    {
        $this->_birthday = $birthday;
    }

    public function setRegisterTime($time)
    {
        $this->_registerTime = date("Y-m-d", $time);
        ;
    }

    public function setNetAge($age)
    {
        $this->_netAge = $age;
    }

    public function setLastLoginTime($time)
    {
        $this->_lastLoginTime = preg_match('#^\d+$#', $time) ? baidu_date_format($time) : $time;
    }

    public function setSignatureText($text)
    {
        $this->_signatureText = baidu_strip_invalid_xml(baidu_diconv(trim($text), CHARSET, 'utf-8'));
        ;
    }

    public function setSignaturePhoto($photo)
    {
        $this->_signaturePhoto = $photo;
    }

    public function toXml()
    {

        return '<url>'
                . '<loc><![CDATA[' . $this->_spaceUrl . ']]></loc>'
                . '<data>'
                . '<display>'
                . '<nickName><![CDATA[' . $this->_nickName . ']]></nickName>'
                . '<spaceUrl><![CDATA[' . $this->_spaceUrl . ']]></spaceUrl>'
                . '<photoUrl><![CDATA[' . $this->_photoUrl . ']]></photoUrl>'
                . '<profile><![CDATA[' . $this->_profile . ']]></profile>'
                . '<level><![CDATA[' . $this->_level . ']]></level>'
                . '<postCount>' . $this->_postCount . '</postCount>'
                . '<fanCount>' . $this->_fanCount . '</fanCount>'
                . '<friendCount>' . $this->_friendCount . '</friendCount>'
                . '<gender>' . $this->_gender . '</gender>'
                . '<location>' . $this->_location . '</location>'
                . '<constellation>' . $this->_constellation . '</constellation>'
                . '<birthday>' . $this->_birthday . '</birthday>'
                . '<registerTime>' . $this->_registerTime . '</registerTime>'
                . '<netAge>' . $this->_netAge . '</netAge>'
                . ($this->_lastLoginTime ? '<lastLoginTime>' . $this->_lastLoginTime . '</lastLoginTime>' : "")
                . '<signatureText><![CDATA[' . $this->_signatureText . ']]></signatureText>'
                . '<signaturePhoto><![CDATA[' . $this->_signaturePhoto . ']]></signaturePhoto>'
                . '</display>'
                . '</data>'
                . '</url>';
    }

}
