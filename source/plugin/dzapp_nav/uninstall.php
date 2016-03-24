<?php
  
  /**
   *      [Discuz!] (C)2001-2099 Comsenz Inc.
   *      This is NOT a freeware, use is subject to license terms
   *
   *      $Id: uninstall.php 24473 2014-11-19 22:17:03Z mpage $
   */
  
  if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
  }
  
  $sql = <<<EOF
  DROP TABLE IF EXISTS `pre_dzapp_nav`;
  EOF;
  
  runquery($sql);
  
  $finish = TRUE;