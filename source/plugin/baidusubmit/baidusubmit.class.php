<?php

if (!defined('IN_DISCUZ')) {
    exit('Access Denied');
}

class base_baidusubmit
{

    function __construct()
    {
        define('__BDS_ROOT__', dirname(__FILE__) . DIRECTORY_SEPARATOR);
        include_once (__BDS_ROOT__ . 'function/function_baidu.php');
        include_once (__BDS_ROOT__ . 'class_schema.php');
        $opencse = baidu_get_plugin_setting('baiducse');
        $openTime = baidu_get_plugin_setting('auto_time');
        $auto = baidu_get_plugin_setting('auto');

        if ($opencse == 2 && (time() >= $openTime || $auto == 0)) {
            $this->value['header'] = $this->getHeaderHtml();
            $this->value['footer'] = $this->getFooterHtml_V2();
        } else if ($opencse == 1 && (time() >= $openTime || $auto == 0)) {
            $this->value['footer'] = $this->getFooterHtml_V1();
        }
    }

    function common()
    {
        $opencse = baidu_get_plugin_setting('baiducse');
        $openTime = baidu_get_plugin_setting('auto_time');
        $auto = baidu_get_plugin_setting('auto');
        if (CURSCRIPT == 'search' && $opencse > 0 && (time() >= $openTime || $auto == 0)) {
            $sid = baidu_get_plugin_setting('engine');
            $searchUser = baidu_get_plugin_setting('user_sitemap');
            $config = baidu_get_plugin_config();
            if (isset($_GET['mod']) && $_GET['mod']) {
                $mod = $_GET['mod'];
            } else if (isset($_POST['mod']) && $_POST['mod']) {
                $mod = $_POST['mod'];
            } else {
                $mod = '';
            }
            if (isset($_GET['kw']) && $_GET['kw']) {
                $query = trim($_GET['kw']);
            } else if (isset($_POST['srchtxt']) && $_POST['srchtxt']) {
                $query = trim($_POST['srchtxt']);
            } else if (isset($_GET['srchtxt']) && $_GET['srchtxt']) {
                $query = trim($_GET['srchtxt']);
            } else {
                $query = '';
            }
            $zn = sprintf($config['zhannei'], $sid) . "&q=" . urlencode($query) . "&partner=discuz";
            switch ($mod) {
                case 'forum':
                    header("Location:" . $zn);
                    exit;
                    break;
                case 'user':
                    if ($searchUser) {
                        header("Location:" . $zn . "&fst=2");
                        exit;
                    }
                    break;
                default :
                    break;
            }
        }
    }

}

class plugin_baidusubmit extends base_baidusubmit
{

    public $value = array();

    function deletethread($value)
    {
        if (!baidu_get_plugin_setting('openping'))
            return;
        if (baidu_senddata_error())
            return;
        $tidlist = $value['param'][0];

        $tidstr = join('_', $tidlist);
        if (defined("_bds_wew_{$tidstr}"))
            return;
        define("_bds_wew_{$tidstr}", true);

        if (!empty($tidlist)) {
            foreach ($tidlist as $tid) {
                $schema = new BaiduThreadSchema();
                $url = baidu_gen_thread_url($tid, 1, 1);
                $schema->setThreadUrl($url);
                baidu_send_data($schema, 2);
            }
        }
    }

    function __destruct()
    {
        //flush();  //个别web配置下会出错

        if (!empty($_GET['inajax']))
            return;
        if (empty($_GET['action']) || 'newthread' !== $_GET['action'])
            return;
        if ('yes' !== $_GET['topicsubmit'])
            return;
        if ($_POST['formhash'] != FORMHASH)
            return;

        global $tid;
        if (empty($tid))
            return;

        if (defined("_bds_w9x_{$tid}"))
            return;
        define("_bds_w9x_{$tid}", true);

        if (!baidu_get_plugin_setting('openping'))
            return;
        if (baidu_senddata_error())
            return;

        //发新帖
        $thread = get_thread_by_tid($tid);
        if ($thread) {
            $url = baidu_gen_thread_url($tid, 1, 1, $thread['fid']);
            $schema = new BaiduThreadSchema();
            $schema->setThreadUrl($url);
            baidu_send_data($schema, 1);
        }
    }

    function getHeaderHtml()
    {
        return <<<EOF
<style>
#bdcs{width: 630px}
#zn_baidu {width: 100%;margin:5px 0}
.ct {margin: 0 auto;min-width: 960px;width: 98%}
</style>
<div id="zn_baidu">
    <div class='ct' id="baidu_ct">
        <div id="bdcs"></div>
    </div>
</div>
<script type="text/javascript">
hideDzSearchBar();
function hideDzSearchBar(){
    var scbar = $('scbar');
    var hd = $('hd');
    var form = document.getElementsByTagName("form")[0];
    if (scbar) {
        scbar.style.display = 'none';
    } else if (form && form.action.indexOf('search.php?searchsubmit=yes') != -1){
        form.style.display = 'none';
    }
    if (hd && hd.getElementsByTagName('div')[0]){
        $('baidu_ct').style.width = getStyle(hd.getElementsByTagName('div')[0],'width');
    }        
}
function getStyle(elem, name) {
    var inlineName = name ;
    var oriName = name;
    if(elem.style.inlineName) {
        return elem.style[inlineName];
    } else if(document.defaultView && document.defaultView.getComputedStyle) {
        return document.defaultView.getComputedStyle(elem, null).getPropertyValue(oriName);
    } else if(elem.currentStyle) {
        return elem.currentStyle[inlineName];
    } else {
        return null;
    }
}        
</script>           
EOF;
    }

    function global_header()
    {
        return isset($this->value['header']) ? $this->value['header'] : array();
    }

    function getFooterHtml_V1()
    {
        $config = baidu_get_plugin_config();
        $sid = baidu_get_plugin_setting('engine');
        $searchUser = baidu_get_plugin_setting('user_sitemap');
        $zn = sprintf($config['zhannei'], $sid);
        return <<<EOF
<script type="text/javascript">(function(){var bdcs = document.createElement('script');bdcs.type = 'text/javascript';bdcs.async = true;bdcs.src = "{$config['znsv']}/customer_search/api/js?sid={$sid}" + '&plate_url=' + encodeURIComponent(window.location.href) + '&t=' + Math.ceil(new Date()/3600000) + '&callback=znForDz';var s = document.getElementsByTagName('script')[0];s.parentNode.insertBefore(bdcs, s);})();</script>
<script src="source/plugin/baidusubmit/template/jquery-1.8.3.min.js"></script>
<script type="text/javascript">
var bdjq = jQuery.noConflict(true);
function znForDz(siteConfig){
    bdjq(function(){
        var zn = '{$zn}';
        if (siteConfig && siteConfig.resultUrl){
            zn = 'http://' + siteConfig.resultUrl + '/cse/search?s={$sid}'
        }
        var btn = bdjq("#scbar_btn");
        if (btn.length == 0) {
            btn = bdjq('button[name="searchsubmit"]');
        }
        if (btn.length == 0) {
            btn = bdjq('input[name="searchsubmit"]');
        }
        if (btn.length == 0) {
            btn = bdjq('#scbar_form input[type="submit"]');
        }
        if (btn.length == 0) {
            btn = bdjq('#scbar_form button');
        }            
        if (btn.length > 0) {
            btn.click(function(event){
                var mod = bdjq('#scbar_mod').val() || bdjq('form input[name="mod"]').val()
                var st = '';
                var txt = bdjq('#scbar_txt').val() || bdjq('form input[name="srchtxt"]').val();
                var user = '$searchUser';
                if (mod === 'user' && user === '1'){
                    st = 2;
                    window.open(zn + "&q=" + encodeURIComponent(txt) + "&fst=" + st + "&partner=discuz");
                    event.preventDefault();
                } else if (mod === 'forum'){
                    window.open(zn + "&q=" + encodeURIComponent(txt) + "&partner=discuz");
                    event.preventDefault();            
                }

            });
        }
        var hot = bdjq('#scbar_hot a')
            if (hot.length > 0){
                hot.click(function(event){
                var txt = bdjq(this).text();
                window.open(zn + "&q=" + encodeURIComponent(txt) + "&partner=discuz");
                event.preventDefault();
            });
        }
    });
}               
</script>           
EOF;
    }

    function global_footer()
    {
        return isset($this->value['footer']) ? $this->value['footer'] : array();
    }

    function getFooterHtml_V2()
    {
        $config = baidu_get_plugin_config();
        $sid = baidu_get_plugin_setting('engine');
        return <<<EOF
<script type="text/javascript">(function(){hideDzSearchBar();var bdcs = document.createElement('script');bdcs.type = 'text/javascript';bdcs.async = true;bdcs.src = "{$config['znsv']}/customer_search/api/js?sid={$sid}" + '&plate_url=' + encodeURIComponent(window.location.href) + '&t=' + Math.ceil(new Date()/3600000);var s = document.getElementsByTagName('script')[0];s.parentNode.insertBefore(bdcs, s);})();</script>
EOF;
    }

}

class mobileplugin_baidusubmit extends base_baidusubmit
{

    function getHeaderHtml()
    {
        return <<<EOF
            <div style="clear:both"></div>
            <div id='bdcs' style="margin:5px 0"></div>
EOF;
    }

    function global_footer_mobile()
    {
        return isset($this->value['footer']) ? $this->value['footer'] : array();
    }

    function getFooterHtml_V1()
    {
        $config = baidu_get_plugin_config();
        $sid = baidu_get_plugin_setting('engine');
        $zn = sprintf($config['zhannei'], $sid);
        return <<<EOF
<script type="text/javascript">(function(){var bdcs = document.createElement('script');bdcs.type = 'text/javascript';bdcs.async = true;bdcs.src = "{$config['znsv']}/customer_search/api/js?sid={$sid}" + '&plate_url=' + encodeURIComponent(window.location.href) + '&t=' + Math.ceil(new Date()/3600000) + '&callback=znForDz';var s = document.getElementsByTagName('script')[0];s.parentNode.insertBefore(bdcs, s);})();</script>
<script src="source/plugin/baidusubmit/template/jquery-1.8.3.min.js"></script>
<script type="text/javascript">
var bdjq = jQuery.noConflict(true);
function znForDz(siteConfig){
    bdjq(function(){
        var zn = '{$zn}';
        if (siteConfig && siteConfig.resultUrl){
            zn = 'http://' + siteConfig.resultUrl + '/cse/search?s={$sid}'
        }
        var btn = bdjq("a[href^='search.php?mod']");         
        if (btn.length > 0) {
            btn.click(function(event){
                window.location.href = zn+ '&q=&partner=discuz';
                event.preventDefault();
            });
        }
    });
}               
</script>     
EOF;
    }

    function getFooterHtml_V2()
    {
        $config = baidu_get_plugin_config();
        $sid = baidu_get_plugin_setting('engine');
        return <<<EOF
<script src="source/plugin/baidusubmit/template/jquery-1.8.3.min.js"></script>
<script type="text/javascript">
var bdjq = jQuery.noConflict(true);
bdjq("a[href^='search.php?mod']").hide();
</script>
<script type="text/javascript">(function(){var bdcs = document.createElement('script');bdcs.type = 'text/javascript';bdcs.async = true;bdcs.src = "{$config['znsv']}/customer_search/api/js?sid={$sid}" + '&plate_url=' + encodeURIComponent(window.location.href) + '&t=' + Math.ceil(new Date()/3600000);var s = document.getElementsByTagName('script')[0];s.parentNode.insertBefore(bdcs, s);})();</script>
EOF;
    }

}

class mobileplugin_baidusubmit_forum extends mobileplugin_baidusubmit
{

    function index_top_mobile()
    {
        return isset($this->value['header']) ? $this->value['header'] : array();
    }

    function forumdisplay_top_mobile()
    {
        return isset($this->value['header']) ? $this->value['header'] : array();
    }

}
