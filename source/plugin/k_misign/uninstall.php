<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
DB::query("DROP TABLE IF EXISTS ".DB::table('plugin_k_misign'));
DB::query("DROP TABLE IF EXISTS ".DB::table('plugin_k_misignset'));
DB::query("DROP TABLE IF EXISTS ".DB::table('plugin_k_misign_prize'));
DB::query("DROP TABLE IF EXISTS ".DB::table('plugin_k_misign_prize_kami'));
DB::query("DROP TABLE IF EXISTS ".DB::table('plugin_k_misign_prize_log'));
DB::query("DROP TABLE IF EXISTS ".DB::table('plugin_k_misign_push'));
DB::query("DROP TABLE IF EXISTS ".DB::table('plugin_k_misign_bq'));
$finish = TRUE;
?>