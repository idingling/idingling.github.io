<?php
/**
 *      [kuozhan] (C)1998-2011 Zoeee Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: spider.class.php 20814 2011-06-29 17:51 zoewho $
 */


if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

class plugin_k_spider {
	function global_footer() {
		global $_G;
		$var = $_G['cache']['plugin']['k_spider'];
		$ServerPort = $_SERVER["SERVER_PORT"];
		$String = $_SERVER["QUERY_STRING"];
		$Serverip = $_SERVER["REMOTE_ADDR"];
		$URL = "http://".$_SERVER["SERVER_NAME"];
		if ($ServerPort !== "80") {
			$URL .= ":".$ServerPort;
		}
		$URL .= $_SERVER['REQUEST_URI']?$_SERVER['REQUEST_URI']:($_SERVER['PHP_SELF']?$_SERVER['PHP_SELF']:$_SERVER['SCRIPT_NAME']);

		$agent=strtolower($_SERVER["HTTP_USER_AGENT"]);

		$spider_key = array(
		0=>'bot',
		1=>'googlebot',
		2=>'mediapartners-google',
		3=>'feedfetcher-Google',
		4=>'ia_archiver',
		5=>'iaarchiver',
		6=>'sqworm',
		7=>'baiduspider',
		8=>'msnbot',
		9=>'yodaobot',
		10=>'yahoo! slurp;',
		11=>'yahoo! slurp china;',
		12=>'yahoo',
		13=>'iaskspider',
		14=>'sogou spider',
		15=>'sogou web spider',
		16=>'sogou push spider',
		17=>'sogou orion spider',
		18=>'sogou-test-spider',
		19=>'sogou+head+spider',
		20=>'sohu',
		21=>'sohu-search',
		22=>'Sosospider',
		23=>'Sosoimagespider',
		24=>'JikeSpider',
		25=>'360spider',
		26=>'qihoobot',
		27=>'tomato bot',
		28=>'bingbot',
		29=>'youdaobot',
		30=>'askjeeves/reoma',
		31=>'manbot',
		32=>'robozilla',
		33=>'MJ12bot',
		34=>'HuaweiSymantecSpider',
		35=>'Scooter',
		36=>'Infoseek',
		37=>'ArchitextSpider',
		38=>'Grabber',
		39=>'Fast',
		40=>'ArchitextSpider',
		41=>'Gulliver',
		42=>'Lycos',
		);
			
		$spider_name = array(
		0=>'Other',
		1=>'Google',
		2=>'Google Adsense',
		3=>'Google',
		4=>'Alexa',
		5=>'Alexa',
		6=>'AOL',
		7=>'Baidu',
		8=>'MSN',
		9=>'Yodao',
		10=>'Yahoo!',
		11=>'Yahoo! China',
		12=>'Yahoo!',
		13=>'Iask',
		14=>'Sogou',
		15=>'Sogou',
		16=>'Sogou',
		17=>'Sogou',
		18=>'Sogou',
		19=>'Sogou',
		20=>'Sohu',
		21=>'Sohu',
		22=>'SoSo',
		23=>'SoSo',
		24=>'Jike',
		25=>'360',
		26=>'360',
		27=>'Tomato',
		28=>'Bing',
		29=>'Yodao',
		30=>'Ask',
		31=>'Bing',
		32=>'Dmoz',
		33=>'MJ12',
		34=>'HuaweiSymantec',
		35=>'AltaVista',
		36=>'Infoseek',
		37=>'Excite',
		38=>'DirectHit',
		39=>'Fast',
		40=>'WebCrawler',
		41=>'NorthernLight',
		42=>'Lycos',
		); 

		$spider="";
		foreach ($spider_key AS $key => $value) {
			if (strpos($agent, $value) !== false) {
				$spider = $spider_name[$key]; 
			}
		}
		
		
		if (!empty($spider)) {
			$spider_data = array(
				'spider' => $spider,
				'spider_time' => $_G['timestamp'],
				'spider_url' => $URL,
				'spider_ip' => $Serverip,
			);
			DB::insert('k_spider', $spider_data);
		}
		$timefordel = '';
		$timefordel = dgmdate($_G['timestamp'], 'H');
		$expiration = '';
		if($var['timesout']==1){
			$expiration = TIMESTAMP - 86400 * 1;
			DB::delete('k_spider', "spider_time < '$expiration'");
		}elseif($var['timesout']==2){
			$expiration = TIMESTAMP - 86400 * 3;
			DB::delete('k_spider', "spider_time < '$expiration'");
		}elseif($var['timesout']==3){
			$expiration = TIMESTAMP - 86400 * 7;
			DB::delete('k_spider', "spider_time < '$expiration'");
		}elseif($var['timesout']==4){
			$expiration = TIMESTAMP - 86400 * 15;
			DB::delete('k_spider', "spider_time < '$expiration'");
		}elseif($var['timesout']==5){
			$expiration = TIMESTAMP - 86400 * 30;
			DB::delete('k_spider', "spider_time < '$expiration'");
		}
	}
}
?>