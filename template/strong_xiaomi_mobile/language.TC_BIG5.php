<?php

/*
 *   strong 2015.01.16
 *
 * 我們致力於為每壹位用戶, 打造獨立個性的產品, 提升用戶體驗, 讓妳的網站更加的接近 Web 2.0 標準.
 *
 * QQ:  695023684
 * Email: 695023684@qq.com
 * Http://365jiqiao.com/demo/xiaomi/

 *
 */
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}



//語言, 如修改註意標點符號

$lang = array(
	'home' => '首頁',
	'forum' => '論壇',
	'group' => '群組',
	'mygroup' => '我的群組',
	'joingroup'=> '加入群組',
	'group_exit'=>'退出群組',
	'mygroup'=>'我的群組',
	'nogroup'=>'您還沒有創建過群組，請用電腦訪問創建',
	'nojoingroup' => '您需要加入該群組才能正常訪問',
	'jianjie'=>'簡介',
	'posts'=>'主題',
	'chengyuan'=>'成員',
	'paiming'=>'排名',
	'forumlist' => '版塊列表',
	'shequjiaodian' => '社區焦點',
	'rementupian' => '熱門圖片',
	'zuixinfabu' => '最新發布',
	'zuixinhuifu' => '最新回復',
	'zuixinremen' => '最新熱門',
	'jingcaituijian' => '精彩推薦',
	'searchgroupt'=>'搜索帖子',
	'pic' => '美圖',
	'guide' => '導讀',
	'classify' =>'分類信息',
	'mdoing' => '記錄',
	'mfollow' => '廣播',
	'mfriend' => '好友',
	'pindao' => '頻道',
	'jiazai' => '點擊加載更多',
	'meiyouneirong' => '沒有更多內容',
	'mfriendall' => '全部',
	'gengduo' => '更多',
	'shouqi' => '收起',
	'stop'=>'↑',
	'mfriendol' => '在線好友',
	'mfriendbl' => '黑名單',
	'mfriendrq' => '好友請求',
	'mfriendprofile' => '資料',
	'userinfo' => '用戶信息',
	'mfriendgroup' => '分組',
	'mblog' => '日誌',
	'mfeed' => '動態',
	'photo' => '相冊',
	'more' => '更多',
	'fup' => '上級',
	'flist' => '列表',
	'plist' => '所在頻道',
	'res' => '刷新',
	'prev' => '上壹頁',
	'next' => '下壹頁',
	'load' => '加載更多...',
	'load_pic' => '拼命加載中...',
	'load_nopic' => '沒有更多內容了',
	'newth' => '新帖',
	'dfth' => '默認',
	'subfrm' => '子版塊',
	'thtys' => '分類',
	'othtys' => '展開分類',
	'pta' => '發表於',
	'reply' => '發表回復',
	'srp' => '查看回復',
	'rcom' => '發表評論',
	'acom' => '查看評論',
	'opt' => '選填',
	'od' => '條',
	'de' => '的',
	'tt' => '共有',
	'thread' => '主題',
	'reply' => '回復',
	'views' => '查看',
	'tietu' => '貼圖',
	'mods' => '管理',
	'nomessage' => '此貼無文字內容',
	'mthread' => '帖子',
	'mforum' => '版塊',
	'profile' => '個人資料',
	'gerenzhongxin' => '個人中心',
	'tuchuang' => '圖床',
	'profilet' => '資料',
	'bankuaishoucang' => '版塊收藏',
	'bankuai' => '版塊',
	'chakanxiaoxi' => '查看消息',
	'wodezhuti' => '我的主題',
	'favorite' => '我的收藏',
	'mypm' => '我的消息',
	'psubject' => '帖子標題',
	'arc' => '文章',
	'lz' => '樓主',
	'kanlouzhu' => '只看樓主',
	'kanquanbu' => '看全部',
	'cldate' => '日期格式:2010-12-01 10:50',
	'pcf' => '重復',
	'back' => '返回',
	'search' => '搜索',
	'searchthread' => '輸入帖子關鍵字',
	'searchportal' => '輸入文章關鍵字',
	'wenzhangshoucang' => '文章收藏',
	'4hr' => '4小時',
	'24hr' => '24小時',
	'168hr' => '壹周',
	'720hr' => '壹月',
	'new_remind' => '有新提醒',
	'remind' => '提醒',
	'xiaoxi' => '消息',
	'new_xiaoxi' => '有新消息',
	'gerenzhongxin' => '個人中心', 
	'mefriend_doing' => '我和好友',
	'tishi'=> '提示',
	'friend_feed' => '好友動態',
	'wenzhang' => '門戶',
	'tupianqiang' => '圖片瀑布流',
	'wodezhuti' => '我的主題',
	'zhutishouchang' => '主題收藏',
	'wodezilaio' => '我的資料',
	'wodexiaoxi' => '我的消息',
	'wodehaoyou' => '我的好友',
	'newjinghua' => '最新精華',
	'newhuifu' => '最新回復',
	'newfabu' => '最新發表',
	'qiangshafa' => '搶沙發',
	'fanhuidingbu' => '返回頂部↑',
	'fanhuiliebiao' => '返回列表',
	'biaoqian' => '標簽',
	'dengru' => '登入',
	'qqdengru' => 'QQ登入',
	'dengruzhongxin' => '登入中心',
	'tuichu' => '退出',
	'zuixin' => '最新',
	'remen' => '熱門',
	'retie' => '熱帖',
	'jinghua' => '精華',
	'fabuyu' => '發布於',
	'touch' => '觸屏版',
	'tthread' => '正文',
	'butubukuai' => '不吐不快！',
	'kuaijie' => '快捷>>',
	'zhutil' => '主題：',
	'paimingl' => '排名：',
	'zhengxu' =>'正序',
	'daoxu' => '倒序',
	'loginaq' => '安全提問?',
	'nosearch' => '暫無',
	'gohome' => '返回首頁',
	'regmes' => '原因',
	'upload_pic' => '上傳圖片',
	'reg' => '註冊',
	'zhucezhongxin' =>'註冊中心',
	'qq' => '登錄',
	'noid' => '沒有賬號?',
	'yesid' => '已有賬號?',
	'yuehot' => '本月熱帖',
	'zhuohot' => '本周熱帖',
	'shoucang' => '收藏',
	'paixu' => '排序',
	'hotforum' => '熱門版塊',
	'hottz' => '熱門帖子',
	'username' => '賬號',
	'jingcaituijian' => '推薦主題',
	'zuixinfabu' =>'最新發布',
	'pc'=>'電腦板',
	'gonggao'=> '公告：',
	'querenshangpin'=> '確認商品',
	'xinwen' => '新聞',
	'guonei' => '國內',
	'guoji' => '國際',
	'shishi' => '實事',
	'shequ' => '社區',	
	'jinrupindao' => '進入頻道',
	'shequjiaodian' => '社區焦點',
	'rementupian' => '熱門圖片',
	'zuixinfabu' => '最新發布',
	'zhutituijian' => '主題推薦',
	'tuijianbankuai' => '版塊推薦'

    );



?>