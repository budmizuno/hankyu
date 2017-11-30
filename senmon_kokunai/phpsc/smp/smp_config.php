<?php

/**
 * 下記ファイルから複製
 * /var/www/html/hankyu-travel.com/attending/senmon_kaigai/phpsc/smp/smp_config.php から複製
 * 専門店 国内 スマホ用config
 * @author leaf
 * @since  2017/02/02
 */

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
// フリープラン用
include_once(dirname(__FILE__) . '/../../sharing/phpsc/class_searchBox_dfree.php'); // ※追加

// 専門店マスターの取込関数 ※phpsc/config2.php 参考
include_once(dirname(__FILE__) . '/../GM_SenmonMap2017.php');
include_once(dirname(__FILE__) . '/../senmon_func.php');
include_once(dirname(__FILE__) . '/class_maplink.php');

// 国内
//include_once(dirname(__FILE__) . '/../kokunai.php'); // ※pc用？

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
include_once(dirname(__FILE__) . '/smp_kokunai.php'); // ※追加、新規作成 ※kokunai.php に同じクラスがある ※sp用？

// スマホ検索用に目的地名称を取得
include_once(dirname(__FILE__) . '/smp_search_mokuteki.php');
