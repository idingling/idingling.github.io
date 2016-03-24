<?php
/**
 *	[百度云(bycall_bdy.{modulename})] (C)2014-2099 Powered by bycall.
 *	Version: 0.1
 *	Date: 2014-7-3 15:05
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
class plugin_bycall_bdy {
	//TODO - Insert your code here
	/**
	 * @Methods describe
	 * @return string type
	 */
	public function global_footer() {
		//TODO - Insert your code here
		global $_G;
		$info = '<script charset="utf-8" src="http://pan.baidu.com/res/static/thirdparty/yunfujian-end/_build/yunfujian.discuz.js" type="text/javascript"></script>

    <script type="text/javascript">

        (function() {

            if (window.yunfujian) {

				if (typeof window.yunfujian.runDiscuz === "function" && 3 == '.$_G[group][allowposturl].') {
					window.yunfujian.runDiscuz();

				}

				if (typeof window.yunfujian.render === "function") {

					window.yunfujian.render();

				}
			}

        })();

    </script>
';
		return $info;	//TODO modify your return code here
	}

}

?>