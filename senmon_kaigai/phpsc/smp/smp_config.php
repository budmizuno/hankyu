<?php

// スマホ判定フラグ
$_is_smp = true;

$naigai = $SettingData->PageAttribute;
$PathSenmonLink = '/attending/senmon_kaigai/';
if ($naigai == 'd') {
	$PathSenmonLink = '/attending/senmon_kokunai/';
}

// タグラインを削除する
$SettingData->Tagline16 = '';

// 専門店の共通define設定ファイル
include_once(dirname(__FILE__) . '/../define.php');

// 専門店の共通グローバル変数設定ファイル
include_once(dirname(__FILE__) . '/../global_variable.php');

include_once(dirname(__FILE__) . '/../../sharing/phpsc/class_searchBox.php');

include_once(dirname(__FILE__) . '/class_maplink.php');

//フリープラン用
//include_once(dirname(__FILE__) . '/freeplan-i.php');

// テーマ・目的別 BOT用
//include_once($PathSharing16 . 'phpsc/common2016.php');

//ブログ用
include_once(dirname(__FILE__) . '/getBlogList.php');

//専門店用
include_once(dirname(__FILE__) . '/../../sharing/phpsc/senmon.php');


//支店情報の取得
include_once($PathSharing16 . 'phpsc/setShitenInfo.php');

// 海外TOPの処理を利用する
include_once(dirname(__FILE__) . '/smp_kaigai.php');

// スマホ検索用に目的地名称を取得
include_once(dirname(__FILE__) . '/smp_search_mokuteki.php');
