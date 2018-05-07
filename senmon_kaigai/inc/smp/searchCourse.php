<!--検索-->
<?php

/**
 *	スマホの検索枠
 */
class setSearch
{

	function __construct($type){

		global $PathSenmonCommon,$SettingData,$masterCsv;
		global $def_kyotenName,$p_hatsu,$mokuteki,$def_dest_name,$def_country_name,$def_city_name,$categoryType;	// 検索で使用するグローバル変数を定義

		// ツアー検索なら
		if($type == TOUR_STRING)
		{
			$senmonFreeFlag = false;
		}
		else
		{
			$senmonFreeFlag = true;
		}

		// スマートフォンサイトへ
		$SettingData->SameURLFlg['smp'] = 1;// smp表示用フラグ
		include( $PathSenmonCommon . 'search/i_smp_top.php');

	}
}
