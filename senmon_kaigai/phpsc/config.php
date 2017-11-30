<?php
/*
#################################################################
	専門店共通で持つ設定ファイル 専門店
#################################################################
*/

// スマホ判定フラグ
$_is_smp = false;

// 専門店の共通define設定ファイル
include_once(dirname(__FILE__) . '/define.php');

// 専門店の共通グローバル変数設定ファイル
include_once(dirname(__FILE__) . '/global_variable.php');

include_once(dirname(__FILE__) . '/../sharing/phpsc/class_searchBox.php');

include_once(dirname(__FILE__) . '/../sharing/phpsc/class_maplink.php');

// テーマ・目的別 BOT用
include_once($PathSharing16 . 'phpsc/common2016.php');

//ブログ用
include_once(dirname(__FILE__) . '/../sharing/phpsc/getBlogList.php');

//専門店用
include_once(dirname(__FILE__) . '/../sharing/phpsc/senmon.php');

//支店情報の取得
include_once($PathSharing16 . 'phpsc/setShitenInfo.php');

// 海外TOPの処理を利用する
include_once(dirname(__FILE__) . '/kaigai.php');



?>
