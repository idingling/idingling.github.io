<?php
/**
 *   This is NOT a freeware, use is subject to license terms
 *   来自:1314学习网 www.1314study.com
 *   作者:诸葛晓明
 *   时间:2012-2-17 18:52
 */
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

class secqaa_study_md5cut {

	var $version = '1.0';
	var $name = 'study_name';
	var $description = 'study_desc';
	var $copyright = '<a href="http://www.1314study.com" target="_blank">1314&#x5B66;&#x4E60;&#x7F51;</a>';
	var $customname = '';

	function make(&$question) {
		$key = rand(1,1314);
		$md5 = md5(md5(TIMESTAMP).$key);
		for($i=1;$i<=6;$i++){
			$md5_1314 .= substr($md5,($i-1)*5,5).' ';
		}
		$start = rand(1, 22);
		$length = rand(1, 8);
		$answer = substr($md5,($start-1),($length+1));
		$question = '&#x8BF7;&#x8F93;&#x5165;&#x4E0B;&#x9762; <b style="color:blue;">&#x7B2C;'.$start.'-'.($start+$length).'&#x4F4D;</b> &#x7684;&#x5B57;&#x6BCD;<br><b style="color:red;">'.$md5_1314.'</b>';
		return $answer;
	}

}

?>