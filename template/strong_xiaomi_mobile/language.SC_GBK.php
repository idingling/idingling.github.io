<?php

/*
 *   strong 2015.01.16
 *
 * 我们致力于为每一位用户, 打造独立个性的产品, 提升用户体验, 让你的网站更加的接近 Web 2.0 标准.
 *
 * QQ:  695023684
 * Email: 695023684@qq.com
 * Http://365jiqiao.com/demo/xiaomi/

 *
 */
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}



//语言, 如修改注意标点符号

$lang = array(
	'home' => '首页',
	'forum' => '论坛',
	'group' => '群组',
	'mygroup' => '我的群组',
	'joingroup'=> '加入群组',
	'group_exit'=>'退出群组',
	'mygroup'=>'我的群组',
	'nogroup'=>'您还没有创建过群组，请用电脑访问创建',
	'nojoingroup' => '您需要加入该群组才能正常访问',
	'jianjie'=>'简介',
	'posts'=>'主题',
	'chengyuan'=>'成员',
	'paiming'=>'排名',
	'forumlist' => '版块列表',
	'shequjiaodian' => '社区焦点',
	'rementupian' => '热门图片',
	'zuixinfabu' => '最新发布',
	'zuixinhuifu' => '最新回复',
	'zuixinremen' => '最新热门',
	'jingcaituijian' => '精彩推荐',
	'searchgroupt'=>'搜索帖子',
	'pic' => '美图',
	'guide' => '导读',
	'classify' =>'分类信息',
	'mdoing' => '记录',
	'mfollow' => '广播',
	'mfriend' => '好友',
	'pindao' => '频道',
	'jiazai' => '点击加载更多',
	'meiyouneirong' => '没有更多内容',
	'mfriendall' => '全部',
	'gengduo' => '更多',
	'shouqi' => '收起',
	'stop'=>'↑',
	'mfriendol' => '在线好友',
	'mfriendbl' => '黑名单',
	'mfriendrq' => '好友请求',
	'mfriendprofile' => '资料',
	'userinfo' => '用户信息',
	'mfriendgroup' => '分组',
	'mblog' => '日志',
	'mfeed' => '动态',
	'photo' => '相册',
	'more' => '更多',
	'fup' => '上级',
	'flist' => '列表',
	'plist' => '所在频道',
	'res' => '刷新',
	'prev' => '上一页',
	'next' => '下一页',
	'load' => '加载更多...',
	'load_pic' => '拼命加载中...',
	'load_nopic' => '没有更多内容了',
	'newth' => '新帖',
	'dfth' => '默认',
	'subfrm' => '子版块',
	'thtys' => '分类',
	'othtys' => '展开分类',
	'pta' => '发表于',
	'reply' => '发表回复',
	'srp' => '查看回复',
	'rcom' => '发表评论',
	'acom' => '查看评论',
	'opt' => '选填',
	'od' => '条',
	'de' => '的',
	'tt' => '共有',
	'thread' => '主题',
	'reply' => '回复',
	'views' => '查看',
	'tietu' => '贴图',
	'mods' => '管理',
	'nomessage' => '此贴无文字内容',
	'mthread' => '帖子',
	'mforum' => '版块',
	'profile' => '个人资料',
	'gerenzhongxin' => '个人中心',
	'tuchuang' => '图床',
	'profilet' => '资料',
	'bankuaishoucang' => '版块收藏',
	'bankuai' => '版块',
	'chakanxiaoxi' => '查看消息',
	'wodezhuti' => '我的主题',
	'favorite' => '我的收藏',
	'mypm' => '我的消息',
	'psubject' => '帖子标题',
	'arc' => '文章',
	'lz' => '楼主',
	'kanlouzhu' => '只看楼主',
	'kanquanbu' => '看全部',
	'cldate' => '日期格式:2010-12-01 10:50',
	'pcf' => '重复',
	'back' => '返回',
	'search' => '搜索',
	'searchthread' => '输入帖子关键字',
	'searchportal' => '输入文章关键字',
	'wenzhangshoucang' => '文章收藏',
	'4hr' => '4小时',
	'24hr' => '24小时',
	'168hr' => '一周',
	'720hr' => '一月',
	'new_remind' => '有新提醒',
	'remind' => '提醒',
	'xiaoxi' => '消息',
	'new_xiaoxi' => '有新消息',
	'gerenzhongxin' => '个人中心', 
	'mefriend_doing' => '我和好友',
	'tishi'=> '提示',
	'friend_feed' => '好友动态',
	'wenzhang' => '门户',
	'tupianqiang' => '图片瀑布流',
	'wodezhuti' => '我的主题',
	'zhutishouchang' => '主题收藏',
	'wodezilaio' => '我的资料',
	'wodexiaoxi' => '我的消息',
	'wodehaoyou' => '我的好友',
	'newjinghua' => '最新精华',
	'newhuifu' => '最新回复',
	'newfabu' => '最新发表',
	'qiangshafa' => '抢沙发',
	'fanhuidingbu' => '返回顶部↑',
	'fanhuiliebiao' => '返回列表',
	'biaoqian' => '标签',
	'dengru' => '登入',
	'qqdengru' => 'QQ登入',
	'dengruzhongxin' => '登入中心',
	'tuichu' => '退出',
	'zuixin' => '最新',
	'remen' => '热门',
	'retie' => '热帖',
	'jinghua' => '精华',
	'fabuyu' => '发布于',
	'touch' => '触屏版',
	'tthread' => '正文',
	'butubukuai' => '不吐不快！',
	'kuaijie' => '快捷>>',
	'zhutil' => '主题：',
	'paimingl' => '排名：',
	'zhengxu' =>'正序',
	'daoxu' => '倒序',
	'loginaq' => '安全提问?',
	'nosearch' => '暂无',
	'gohome' => '返回首页',
	'regmes' => '原因',
	'upload_pic' => '上传图片',
	'reg' => '注册',
	'zhucezhongxin' =>'注册中心',
	'qq' => '登录',
	'noid' => '没有账号?',
	'yesid' => '已有账号?',
	'yuehot' => '本月热帖',
	'zhuohot' => '本周热帖',
	'shoucang' => '收藏',
	'paixu' => '排序',
	'hotforum' => '热门版块',
	'hottz' => '热门帖子',
	'username' => '账号',
	'jingcaituijian' => '推荐主题',
	'zuixinfabu' =>'最新发布',
	'pc'=>'电脑板',
	'gonggao'=> '公告：',
	'querenshangpin'=> '确认商品',
	'xinwen' => '新闻',
	'guonei' => '国内',
	'guoji' => '国际',
	'shishi' => '实事',
	'shequ' => '社区',	
	'jinrupindao' => '进入频道',
	'shequjiaodian' => '社区焦点',
	'rementupian' => '热门图片',
	'zuixinfabu' => '最新发布',
	'zhutituijian' => '主题推荐',
	'tuijianbankuai' => '版块推荐'

    );



?>