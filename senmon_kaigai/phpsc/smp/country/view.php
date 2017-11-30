<?php
/*
########################################################################
#
#  新着ツアー
#
#  @copyright  2014 BUD International
#  @version    1.0.0
########################################################################

/********
* include
*********/

include_once($SharingPSPath . 'pageArticlesInfo2014.php');

/*******************************************************
	* cmsToHtml()　CMSで入力したツアーを表示する

	* 引数 ID、ツアーMAX本数、タイトル制限文字バイト数、ポイント制限文字バイト数
	* 返り値
 ******************************************************/
function cmsToHtml($id,$tour_max='',$title_max_width='',$point_max_width='',$tmp_name=''){

	global $GlobalTourList;
	global $GlobalArticleSet;
	global $SettingData;
	global $PageAttributeNameAry;
	global $RelativeNPath;
	global $AttendingIncPath;
	global $PathSenmonCommon;
	global $GlobalMaster;

/*
echo "<pre>";
print_r($SettingData->ArticleConfig);
echo "</pre>";


echo "<pre>";
print_r($GlobalTourList);
echo "</pre>";
*/
	//一括指定
	if(empty($subtitle_max_width)){
	$subtitle_max_width = 600;
	}
	if(empty($title_max_width)){
	$title_max_width = 600;
	}
	if(empty($point_max_width)){
	$point_max_width = 600;
	}


	//記事テンプレート取得
	$incPath = $PathSenmonCommon . 'inc/country/' . $tmp_name;
	//記事タイトル取得
	$set_art_title = $SettingData->ArticleConfig["$id"]['article_title'];
	//拠点IDを取得
	$kyoten_id = $SettingData->ArticleConfig["$id"]['kyoten_di'];
	//拠点名を取得
	foreach($GlobalMaster['kencode'] as $no => $kyoten_data){
		$master_kyoten_id = $kyoten_data['kyotenId'] . '_' . $kyoten_data['naigai'];
		//マスター拠点と取得した拠点IDが同じなら名前取得
		if($master_kyoten_id == $kyoten_id){
			$kyoten_name = $kyoten_data['kyotenName'];
		}
	}

 	if(!is_array($GlobalTourList["$id"]["$set_art_title"])){
		//該当記事のデータが無いときは何もしない
		return false;
	}
	//海外・国内、タイトル毎のツアーリスト
	$tourAry = $GlobalTourList["$id"]["$set_art_title"];
	$tourCnt = count($tourAry);

	if(!is_array($tourAry)){
		return false;
	}
	$tourNum=1;
	foreach($tourAry as $key => $GlobalTourData){
		//if($GlobalTourData['q_type'] == $type){

			if(empty($GlobalTourData['tour_url'])){
				continue;
			}
			//最大表示数を超えたら終わり
			if($tourNum > $tour_max){
				break;
			}
			//タイトル文字数制限処理
			$GlobalTourData['p_course_name'] = stringControl($GlobalTourData['p_course_name'],$title_max_width);
			//ポイント文字数制限処理
			$GlobalTourData['p_point_all'] = stringControl($GlobalTourData['p_point_all'],$point_max_width);

			//ポイント１，２，３の間改行したいらしい
			/*if(!empty($GlobalTourData['p_point1'])){
				$p_point_all .= stringControl($GlobalTourData['p_point1'],$point_max_width);
			}
			//ポイント２
			if(empty($p_point_all) && !empty($GlobalTourData['p_point2'])){
				$p_point_all .= stringControl($GlobalTourData['p_point2'],$point_max_width);
			}elseif(!empty($p_point_all) && !empty($GlobalTourData['p_point2'])){
				$p_point_all .= '<br />' . stringControl($GlobalTourData['p_point2'],$point_max_width);
			}
			//ポイント３
			if(empty($p_point_all) && !empty($GlobalTourData['p_point3'])){
				$p_point_all .= stringControl($GlobalTourData['p_point3'],$point_max_width);
			}elseif(!empty($p_point_all) && !empty($GlobalTourData['p_point3'])){
				$p_point_all .= '<br />' . stringControl($GlobalTourData['p_point3'],$point_max_width);
			}
			$GlobalTourData['p_point_all'] = $p_point_all;
			*/

			if(strpos($GlobalTourData['price_min_max'],'受付終了') !== false || $GlobalTourData['price_min_max'] == 0 || $GlobalTourData['price_min_max'] == ''){
				//URLがDSかどうか判定
				if($GlobalTourData['url_type'] != 'noDS'){
					//今回は料金なしはとばす
					continue;
				}
			}

			//行き先（国）を出したい
			$destStr = NULL;
			if($GlobalTourData['p_destination_request'] == 1){
				//国内と海外、両方とも
				$TgObj = $GlobalTourData['p_ab_destinarion_info'];
				if($TgObj == NULL){
					$TgObj = $GlobalTourData['p_dome_destinarion_info'];
				}

				if(!is_array($TgObj)){
					$TgObj = array($TgObj);
				}
				foreach($TgObj as $oneDest){
					$TgCountry = $oneDest->p_country_name;
					if($TgCountry == NULL){
						$TgCountry = $oneDest->p_prefecture_name;
					}
					//出す国はふたつだけ
					if($TgCountry == $destStr){
						continue;
					}
					if($destStr == NULL){
						$destStr = $TgCountry;
					}
					else{
						$destStr .= '、' . $TgCountry;
						break;
					}
				}
			}



			//ランキング

			if($GlobalTourData['q_rank1'] == 1){
				$q_rank1 = '<li class="rank01">';
			} else if($GlobalTourData['q_rank1'] == 2){
				$q_rank1 = '<li class="rank02">';
			} else if($GlobalTourData['q_rank1'] == 3){
				$q_rank1 = '<li class="rank03">';
			} else if($GlobalTourData['q_rank1'] == 4){
				$q_rank1 = '<li class="rank04">';
			} else if($GlobalTourData['q_rank1'] == 5){
				$q_rank1 = '<li class="rank05">';
			} else {
				$q_rank1 = '<li>';
			}

			//都市表示カスタマイズ
			$ishiArray = '';
			$ishiPcityName = $GlobalTourData['p_ab_destinarion_info']->p_city_name ;
			if(is_array($GlobalTourData['p_ab_destinarion_info'])){
				foreach ($GlobalTourData['p_ab_destinarion_info'] as $ishiParam => $ishiVal) {
					if($ishiVal->p_city_name != ''){
						$ishiArray[] = $ishiVal->p_city_name ;
					}
				}
			$ishiPcityName = implode('、', $ishiArray);
			}

			//キャッチが入力されてたら
			if(!empty($GlobalTourData['p_catch'])){
				if(!empty($cap_max_width)){
					//キャッチ文字数制限
					$GlobalTourData['p_catch'] = stringControl($GlobalTourData['p_catch'],$cap_max_width);
				}
				$destStr = $GlobalTourData['p_catch'];
			}

			include($incPath);
			$tourNum++;
		}
	//}
}


function cmsToHtmlHotel($id,$tour_max='',$title_max_width='',$point_max_width='',$tmp_name=''){

	global $GlobalTourList;
	global $GlobalArticleSet;
	global $SettingData;
	global $PageAttributeNameAry;
	global $RelativeNPath;
	global $AttendingIncPath;
	global $PathSenmonCommon;
	global $GlobalMaster;

/*
echo "<pre>";
print_r($SettingData->ArticleConfig);
echo "</pre>";


echo "<pre>";
print_r($GlobalTourList);
echo "</pre>";
*/
	//一括指定
	if(empty($subtitle_max_width)){
	$subtitle_max_width = 600;
	}
	if(empty($title_max_width)){
	$title_max_width = 600;
	}
	if(empty($point_max_width)){
	$point_max_width = 600;
	}


	//記事テンプレート取得
	$incPath = $PathSenmonCommon . 'inc/country/' . $tmp_name;
	//記事タイトル取得
	$set_art_title = $SettingData->ArticleConfig["$id"]['article_title'];
	//拠点IDを取得
	$kyoten_id = $SettingData->ArticleConfig["$id"]['kyoten_di'];
	//拠点名を取得
	foreach($GlobalMaster['kencode'] as $no => $kyoten_data){
		$master_kyoten_id = $kyoten_data['kyotenId'] . '_' . $kyoten_data['naigai'];
		//マスター拠点と取得した拠点IDが同じなら名前取得
		if($master_kyoten_id == $kyoten_id){
			$kyoten_name = $kyoten_data['kyotenName'];
		}
	}

 	if(!is_array($GlobalTourList["$id"]["$set_art_title"])){
		//該当記事のデータが無いときは何もしない
		return false;
	}
	//海外・国内、タイトル毎のツアーリスト
	$tourAry = $GlobalTourList["$id"]["$set_art_title"];
	$tourCnt = count($tourAry);

	if(!is_array($tourAry)){
		return false;
	}

	foreach($tourAry as $key => $data){
		$makeGroup[$data['q_group']][]=$data;
	}

	$tourNum=1;
	$btnUrl='';
	foreach($makeGroup as $group => $groupData){
	$Html='';
	foreach($groupData as $key => $GlobalTourData){
		//if($GlobalTourData['q_type'] == $type){

			if(empty($GlobalTourData['tour_url'])){
				continue;
			}
			//最大表示数を超えたら終わり
			if(!empty($tour_max)){
				if($tourNum > $tour_max){
					break;
				}
			}
			$btnUrl=$GlobalTourData['q_btnurl'];
			//タイトル文字数制限処理
			//$GlobalTourData['p_course_name'] = stringControl($GlobalTourData['p_course_name'],$title_max_width);
			//ポイント文字数制限処理
			$GlobalTourData['p_point_all'] = stringControl($GlobalTourData['p_point_all'],$point_max_width);

			//ポイント１，２，３の間改行したいらしい
			/*if(!empty($GlobalTourData['p_point1'])){
				$p_point_all .= stringControl($GlobalTourData['p_point1'],$point_max_width);
			}
			//ポイント２
			if(empty($p_point_all) && !empty($GlobalTourData['p_point2'])){
				$p_point_all .= stringControl($GlobalTourData['p_point2'],$point_max_width);
			}elseif(!empty($p_point_all) && !empty($GlobalTourData['p_point2'])){
				$p_point_all .= '<br />' . stringControl($GlobalTourData['p_point2'],$point_max_width);
			}
			//ポイント３
			if(empty($p_point_all) && !empty($GlobalTourData['p_point3'])){
				$p_point_all .= stringControl($GlobalTourData['p_point3'],$point_max_width);
			}elseif(!empty($p_point_all) && !empty($GlobalTourData['p_point3'])){
				$p_point_all .= '<br />' . stringControl($GlobalTourData['p_point3'],$point_max_width);
			}
			$GlobalTourData['p_point_all'] = $p_point_all;
			*/
$btn =<<<EOD
<a href="{$GlobalTourData['tour_url']}"><img src="/attending/italy/images/tyo/btnFree_free.gif" alt="フリープラン一覧" class="fade" /></a>
EOD;

			if(strpos($GlobalTourData['price_min_max'],'受付終了') !== false || $GlobalTourData['price_min_max'] == 0 || $GlobalTourData['price_min_max'] == ''){
				//URLがDSかどうか判定
				if($GlobalTourData['url_type'] != 'noDS'){
					//今回は料金なしはとばす
$btn =<<<EOD
<img src="/attending/italy/images/tyo/btnFree_free_g.gif" alt="フリープラン一覧" />
EOD;
}
			}




			//都市表示カスタマイズ
			/*$ishiArray = '';
			$ishiPcityName = $GlobalTourData['p_ab_destinarion_info']->p_city_name ;
			if(is_array($GlobalTourData['p_ab_destinarion_info'])){
				foreach ($GlobalTourData['p_ab_destinarion_info'] as $ishiParam => $ishiVal) {
					if($ishiVal->p_city_name != ''){
						$ishiArray[] = $ishiVal->p_city_name ;
					}
				}
			$ishiPcityName = implode('、', $ishiArray);
			}*/

			//キャッチが入力されてたら
			/*if(!empty($GlobalTourData['p_catch'])){
				if(!empty($cap_max_width)){
					//キャッチ文字数制限
					$GlobalTourData['p_catch'] = stringControl($GlobalTourData['p_catch'],$cap_max_width);
				}
				$destStr = $GlobalTourData['p_catch'];
			}*/

			ob_start();
			include($incPath);
			$Html .= ob_get_contents();
			ob_end_clean();
			$tourNum++;
		}
$htlBoxHtml .=<<<EOD
<div class="commonBlk680">
<h6 class="noIcn">{$group}<a href="{$btnUrl}"><img src="/attending/italy/images/tyo/btnHtlLst.png" /></a></h6>
<div class="htlBlk">
{$Html}
</div>
</div>

EOD;
}

echo $htlBoxHtml;
}


/*******************************************************
	* cmsToHtmlTema()　CMSで入力したツアーを表示する

	* 引数 ID、ツアーMAX本数、タイトル制限文字バイト数、ポイント制限文字バイト数
	* 返り値
 ******************************************************/
function cmsToHtmlTema($id,$tour_max='',$title_max_width='',$point_max_width='',$tmp_name=''){

	global $GlobalTourList;
	global $GlobalArticleSet;
	global $SettingData;
	global $PageAttributeNameAry;
	global $RelativeNPath;
	global $AttendingIncPath;
	global $PathSenmonCommon;
	global $GlobalMaster;

/*
echo "<pre>";
print_r($SettingData->ArticleConfig);
echo "</pre>";


echo "<pre>";
print_r($GlobalTourList);
echo "</pre>";
*/
	//一括指定
	if(empty($subtitle_max_width)){
	$subtitle_max_width = 600;
	}
	if(empty($title_max_width)){
	$title_max_width = 600;
	}
	if(empty($point_max_width)){
	$point_max_width = 600;
	}


	//記事テンプレート取得
	$incPath = $PathSenmonCommon . 'inc/country/' . $tmp_name;
	//記事タイトル取得
	$set_art_title = $SettingData->ArticleConfig["$id"]['article_title'];
	//拠点IDを取得
	$kyoten_id = $SettingData->ArticleConfig["$id"]['kyoten_di'];
	//拠点名を取得
	foreach($GlobalMaster['kencode'] as $no => $kyoten_data){
		$master_kyoten_id = $kyoten_data['kyotenId'] . '_' . $kyoten_data['naigai'];
		//マスター拠点と取得した拠点IDが同じなら名前取得
		if($master_kyoten_id == $kyoten_id){
			$kyoten_name = $kyoten_data['kyotenName'];
		}
	}

 	if(!is_array($GlobalTourList["$id"]["$set_art_title"])){
		//該当記事のデータが無いときは何もしない
		return false;
	}
	//海外・国内、タイトル毎のツアーリスト
	$tourAry = $GlobalTourList["$id"]["$set_art_title"];
	$tourCnt = count($tourAry);

	if(!is_array($tourAry)){
		return false;
	}
	$tourNum=1;
	foreach($tourAry as $key => $GlobalTourData){
		//if($GlobalTourData['q_type'] == $type){

			if(empty($GlobalTourData['tour_url'])){
				continue;
			}
			//最大表示数を超えたら終わり
			if($tourNum > $tour_max){
				break;
			}
			//タイトル文字数制限処理
			$GlobalTourData['p_course_name'] = stringControl($GlobalTourData['p_course_name'],$title_max_width);
			//ポイント文字数制限処理
			$GlobalTourData['p_point_all'] = stringControl($GlobalTourData['p_point_all'],$point_max_width);

			//ポイント１，２，３の間改行したいらしい
			/*if(!empty($GlobalTourData['p_point1'])){
				$p_point_all .= stringControl($GlobalTourData['p_point1'],$point_max_width);
			}
			//ポイント２
			if(empty($p_point_all) && !empty($GlobalTourData['p_point2'])){
				$p_point_all .= stringControl($GlobalTourData['p_point2'],$point_max_width);
			}elseif(!empty($p_point_all) && !empty($GlobalTourData['p_point2'])){
				$p_point_all .= '<br />' . stringControl($GlobalTourData['p_point2'],$point_max_width);
			}
			//ポイント３
			if(empty($p_point_all) && !empty($GlobalTourData['p_point3'])){
				$p_point_all .= stringControl($GlobalTourData['p_point3'],$point_max_width);
			}elseif(!empty($p_point_all) && !empty($GlobalTourData['p_point3'])){
				$p_point_all .= '<br />' . stringControl($GlobalTourData['p_point3'],$point_max_width);
			}
			$GlobalTourData['p_point_all'] = $p_point_all;
			*/

			if(strpos($GlobalTourData['price_min_max'],'受付終了') !== false || $GlobalTourData['price_min_max'] == 0 || $GlobalTourData['price_min_max'] == ''){
				//URLがDSかどうか判定
				if($GlobalTourData['url_type'] != 'noDS'){
					//今回は料金なしはとばす
					continue;
				}
			}

			//行き先（国）を出したい
			$destStr = NULL;
			if($GlobalTourData['p_destination_request'] == 1){
				//国内と海外、両方とも
				$TgObj = $GlobalTourData['p_ab_destinarion_info'];
				if($TgObj == NULL){
					$TgObj = $GlobalTourData['p_dome_destinarion_info'];
				}

				if(!is_array($TgObj)){
					$TgObj = array($TgObj);
				}
				foreach($TgObj as $oneDest){
					$TgCountry = $oneDest->p_country_name;
					if($TgCountry == NULL){
						$TgCountry = $oneDest->p_prefecture_name;
					}
					//出す国はふたつだけ
					if($TgCountry == $destStr){
						continue;
					}
					if($destStr == NULL){
						$destStr = $TgCountry;
					}
					else{
						$destStr .= '、' . $TgCountry;
						break;
					}
				}
			}

		//アイコン
			if($GlobalTourData['q_icon1'] == '0芸術'){
				$q_icon1 = '<img src="/attending/italy/images/tyo/icnThem01.gif" alt="芸術" />';
			} else if($GlobalTourData['q_icon1'] == '1グルメ'){
				$q_icon1 = '<img src="/attending/italy/images/tyo/icnThem02.gif" alt="グルメ" />';
			} else if($GlobalTourData['q_icon1'] == '2イベント'){
				$q_icon1 = '<img src="/attending/italy/images/tyo/icnThem03.gif" alt="イベント" />';
			} else if($GlobalTourData['q_icon1'] == '3エンタメ'){
				$q_icon1 = '<img src="/attending/italy/images/tyo/icnThem04.gif" alt="エンタメ" />';
			} else if($GlobalTourData['q_icon1'] == '4観光'){
				$q_icon1 = '<img src="/attending/italy/images/tyo/icnThem05.gif" alt="観光" />';
			} else if($GlobalTourData['q_icon1'] == '5カルチャー'){
				$q_icon1 = '<img src="/attending/italy/images/tyo/icnThem06.gif" alt="カルチャー" />';
			} else if($GlobalTourData['q_icon1'] == '6こだわり'){
				$q_icon1 = '<img src="/attending/italy/images/tyo/icnThem07.gif" alt="こだわり" />';
			} else if($GlobalTourData['q_icon1'] == '7乗り物'){
				$q_icon1 = '<img src="/attending/italy/images/tyo/icnThem08.gif" alt="乗り物" />';
			} else {
				$q_icon1 = null;
			}


			//都市表示カスタマイズ
			$ishiArray = '';
			$ishiPcityName = $GlobalTourData['p_ab_destinarion_info']->p_city_name ;
			if(is_array($GlobalTourData['p_ab_destinarion_info'])){
				foreach ($GlobalTourData['p_ab_destinarion_info'] as $ishiParam => $ishiVal) {
					if($ishiVal->p_city_name != ''){
						$ishiArray[] = $ishiVal->p_city_name ;
					}
				}
			$ishiPcityName = implode('、', $ishiArray);
			}


			//キャッチが入力されてたら
			if(!empty($GlobalTourData['p_catch'])){
				if(!empty($cap_max_width)){
					//キャッチ文字数制限
					$GlobalTourData['p_catch'] = stringControl($GlobalTourData['p_catch'],$cap_max_width);
				}
				$destStr = $GlobalTourData['p_catch'];
			}


	if($tourNum ==5){
	$Html .=<<<EOD
</div><div class="downBlk">
EOD;
}
			ob_start();
			include($incPath);
			$Html .= ob_get_contents();
			ob_end_clean();
			$tourNum++;
		}
$HtmlAll =<<<EOD
<div class="upBlk">{$Html}</div>
EOD;

	echo $HtmlAll;
}

/*******************************************************
	* retCnt()　有効な商品の数を返す

	* 引数 $kind 何でまとめるか

	* 返り値
	* $dsurl_valid_count 有効DSの数

 ******************************************************/
function retCnt($kind){

	global $SettingData;

	$css = '';
	$dsurl_valid_count = 0;
	if(strpos($kind,'article_conf') !== false){
		return $SettingData->ArticleConfig["$kind"]['dsurl_valid_count'];
	}elseif (preg_match("/^[a-z_]+$/", $kind)){
		$judge = 'kyoten_di';
	}else{
		$judge = 'group_id';
	}
	/*if(is_numeric($kind)){
		$judge = 'group_id';
	}else{
		$judge = 'kyoten_di';
	}*/
	//きっと出発地(拠点)の数
	foreach($SettingData->ArticleConfig as $artId => $artData){
		if($kind == $artData["$judge"]){
			$dsurl_valid_count = $dsurl_valid_count + $SettingData->ArticleConfig["$artId"]['dsurl_valid_count']+ $SettingData->ArticleConfig["$artId"]['othre_url_count'];
		}
	}
	//echo '★'.$kind.':'.$dsurl_valid_count.'★';
	return $dsurl_valid_count;

}

/*******************************************************
	* retCss()　有効な商品の数を返す

	* 引数 $kind 何でまとめるか

	* 返り値
	* $dsurl_valid_count 有効DSの数

 ******************************************************/
function retCss($kind){

	global $SettingData;
	$css = '';
	$dsurl_valid_count = 0;

	if (preg_match("/^[\x21-\x7E]+$/", $kind)){
		$judge = 'kyoten_di';
	}else{
		$judge = 'group_id';
	}
	//きっと出発地(拠点)の数
	foreach($SettingData->ArticleConfig as $artId => $artData){
		if($kind == $artData["$judge"]){
			$dsurl_valid_count = $dsurl_valid_count + $SettingData->ArticleConfig["$artId"]['dsurl_valid_count'];
		}
	}
	if(empty($dsurl_valid_count)){
		$css = '_g';
	}
	return $css;
}





//最近見たツアー（閲覧履歴）
class HistoryTyo{
	//$option　初期表示本数　
	function __construct($temp = '',$option = ''){
		global $IncPath;
		$this->detailHistory = new detailHistory('all');//class_detail_history.php

		//使用のテンプレート名　=　クラス名
		if(!empty($temp)){
			$this->myTemplate = $temp;
		}
		else{
			$this->myTemplate = $IncPath . get_class($this) . '.php';
		}
		$this->DispHtmlData(10,$option);
	}

	//閲覧履歴表示html
	function DispHtmlData($num,$btndisp){
		global $GlobalMaster,$PathRelativeMyDir;

		if(empty($GlobalMaster['Senmon'])){
			new GM_Senmon;
		}

		$html ='';
		$nolflg= '';//ボタンの出し入れ用

		//媒体コード付与　専門店海外
		$baitai ='&baitai_a=remind8';

		if(is_array($this->detailHistory->historyAry)){
			$count=1;
			$allcount = count($this->detailHistory->historyAry);

			foreach($this->detailHistory->historyAry as $time => $dataAry){

				foreach($dataAry as $naigai => $data){
					if($count > $num){
						break;
					}

					if($naigai == 'i'){
						$requesturl = "http://" . htmlspecialchars($_SERVER["HTTP_HOST"]) . "/tour/detail_i.php";
					}
					elseif($naigai == 'd'){
						$requesturl = "http://" . htmlspecialchars($_SERVER["HTTP_HOST"]) . "/tour/detail_d.php";
					}
					if($count == $btndisp+1 && $allcount >= $btndisp+1){
						/*$html .=<<<EOD
							<div id="moreHistory" style="display: none;">
EOD;*/

					}
					if(strpos($data['p_img'],"http")!==false){
					}
					else{
						$data['p_img']='https://x.hankyu-travel.com/photo_db/image_search_kikan2.php?p_photo_mno='.$data['p_img'];
					}

					if($count >= $btndisp+1){

					$html2 .=<<<EOD
					<ul>\n
						<li class="rbTour_p"><img src="{$data['p_img']}" alt="{$data['p_alt']}" /></li>\n
						<li class="rbTourName"><a href='{$requesturl}?p_course_id={$data['p_corse_id']}&p_hei={$data['p_hei']}{$baitai}'>{$data['p_corse_name']}</a></li>\n
					</ul>\n
EOD;
					}
					else{

						$html .=<<<EOD
						<ul>\n
						<li class="rbTour_p"><img src="{$data['p_img']}" alt="{$data['p_alt']}" /></li>\n
						<li class="rbTourName"><a href='{$requesturl}?p_course_id={$data['p_corse_id']}&p_hei={$data['p_hei']}{$baitai}'>{$data['p_corse_name']}</a></li>\n
					</ul>\n
EOD;
					}
				}
				$count++;
			}
				if(!empty($html)){
					if($count > $btndisp+1){
						$dataHtml2 ='<div id="moreHistory" style="display: none;">'.$html2.'<div id="JShistorybtnLess" class="btnLess" style="display:none"><img src="/attending/italy/images/tyo/rbBtnMore_off.gif" alt="閉じる" /></div></div>';
					}

					else{
						$nolflg= 1;
					}
					$dataHtml =$html;
				}
				else{
					$dataHtml =$this->detailHistory->nothing;
					$nolflg= 1;
				}
		}
		else{
			$dataHtml =$this->detailHistory->nothing;
			$nolflg= 1;
		}
		include($this->myTemplate);
	}
}

/*
*******************************************************
	ニューサーチツアー
	引数：内外,拠点コード,リクエストパラメータの配列,掲載本数,テンプレート
*******************************************************
*/
class SearchActionForTourTyo extends SearchActionDefault {


	function __construct($naigai,$kyotenCode,$RqPara ='',$num='',$temp){

		global $PathSharing, $GlobalSolrReqParamAry,$kyotenId;
		$this->dispKyotenId = $kyotenCode;
		$this->MyNaigai = $naigai;
		$this->temp = $temp;
		$this->tour_max = $num;


		//p_hatsuの生成
		if($naigai == 'd'){
			if($this->dispKyotenId !='top'){
				$p_hatsuAry = new HierarchyMagoKyotenTabCode_p_hatsu_sub;
				$this->p_hatsu_sub = bindingHatsuAry($p_hatsuAry->TgDataAry[$this->MyNaigai][$this->dispKyotenId]);
			}
			else{
				$this->p_hatsu_sub ='';
			}
		}
		else{
			if($this->dispKyotenId !='top'){
				$p_hatsuAry = new HierarchyMagoKyotenTabCode_p_hatsu;
				$this->p_hatsu = bindingHatsuAry($p_hatsuAry->TgDataAry[$this->MyNaigai][$this->dispKyotenId]);
			}
			else{
				$this->p_hatsu ='';
			}
		}
		$Request = array(
			'p_hatsu' => $this->p_hatsu
			,'p_hatsu_sub' => $this->p_hatsu_sub
		);
		if(is_array($RqPara)){
			$RequestPara =$Request+$RqPara;
		}

		//ツアー情報取得
		$FasetdataAry = $this->GetSolr($RequestPara);
	}

	//ツアー情報取得
	function GetSolr($Req){
		global $GlobalSolrReqParamAry,$flg;
		$this->allFacetFlg ='';
		$GlobalSolrReqParamAry[$this->MyNaigai]['p_mokuteki'] = NULL; //元がどこかの方面をもっているから強制的に削除
		$GlobalSolrReqParamAry['i']['p_hatsu'] = NULL;
		$GlobalSolrReqParamAry['d']['p_hatsu_sub'] = NULL;

		if(!empty($Req)){

			$this->ActRequestForSolr($Req);
			/*応答データ形式を指定*/

			$GlobalSolrReqParamAry[$this->MyNaigai]['p_data_kind'] = '3';	//ツアーとファセット
			//返して欲しい項目は、内外別
			$GlobalSolrReqParamAry[$this->MyNaigai]['p_rtn_data'] = 'p_conductor';	//ファセットを返してほしい項目

			/*DB通信*/
			$SolrObj = new SolrAccess($this->MyNaigai);	//solrのレスポンス：ママ

			/*エラー処理*/
			$this->ActErr($SolrObj);

			if($SolrObj->ErrFlg == 0){	//エラーじゃなかったら
				$this->ResObj = $SolrObj->Obj->response;
				$this->hit_num = $this->ResObj->p_hit_num;
			}
			else{
				$this->hit_num ='';
			}


		}
		return $otherFacet;
	}

	//表示用
	function makeHtml(){
	global $naigai,$RealHttp;

		if(!empty($this->hit_num) && is_array($this->ResObj->docs)){
			$tourNum='';
			foreach($this->ResObj->docs as $CourseObj){
				if(!empty($this->tour_max)){
					if($tourNum >= $this->tour_max){
						break;
					}
				}

				//商品名
				$p_course_name = MyEcho($CourseObj->p_course_name);
				//URL
				if(strpos($_SERVER['HTTP_HOST'], 'www-dev.hankyu-travel.com') !== false ||strpos($_SERVER['HTTP_HOST'], 'www-cms.hankyu-travel.com') !== false){
					$URL = '/tour/detail_' . $naigai . '.php?p_course_id=' . $CourseObj->p_course_id . '&p_hei=' . $CourseObj->p_hei;
				}
				else{
					$URL = $RealHttp . 'tour/detail_' . $naigai . '.php?p_course_id=' . $CourseObj->p_course_id . '&p_hei=' . $CourseObj->p_hei;
				}
				// 料金
				$PriceMinMax = YoriMade($CourseObj->p_price_min, $CourseObj->p_price_max, '円', '0');
				/*----- 出発地 -----*/
				$Hatsu = NULL;
				$PreHatsuAry = array();
				if(is_array($CourseObj->p_hatsu_name)){
					foreach($CourseObj->p_hatsu_name as $KeyHatsu => $ValHatsu){
						$data = explode(',', $ValHatsu);
						//一意のリストへ
						$PreHatsuAry[] = $data[1];
					}
					$Hatsu = implode('、', array_unique($PreHatsuAry));
				}


				//写真
				//NoImg処理はJSでもやるけど
				$Photo = $CourseObj->p_img1_filepath;
				$PhotoAlt = $CourseObj->p_img1_caption;
				if($Photo == NULL){
					$Photo = $CourseObj->p_img2_filepath;
					$PhotoAlt = $CourseObj->p_img2_caption;
				}
				if($Photo == NULL){
					$Photo = $CourseObj->p_img3_filepath;
					$PhotoAlt = $CourseObj->p_img3_caption;
				}
				//それでもNULLならNoImg
				if($Photo == NULL){
					$Photo = '/share/noimg/140x105.jpg';
					$PhotoAlt = '';
				}
				else{
					$Photo = 'http://x.hankyu-travel.com/photo_db/image_search_kikan2.php?p_photo_mno=' . $Photo;
					$PhotoAlt = MyEcho($PhotoAlt);
				}
				//ポイント
				$Point1 = $CourseObj->p_point1;
				$Point1 = MyEcho($Point1);
				$Point1 = str_replace(array("\r\n","\n","\r"), '<br />', $Point1);
				$Point2 = $CourseObj->p_point2;
				$Point2 = MyEcho($Point2);
				$Point2 = str_replace(array("\r\n","\n","\r"), '<br />', $Point2);
				//訪問都市
				$Toshi= NULL;
				$PreToshiAry= NULL;
				if(is_array($CourseObj->p_city_cn)){
					foreach($CourseObj->p_city_cn as $toshi => $ValToshi){
						$data = explode(',', $ValToshi);
						//一意のリストへ
						$PreToshiAry[] = $data[3];
					}
					$Toshi = implode('、', array_unique($PreToshiAry));
				}

				include($this->temp);
				$tourNum++;
			}
		}
	}
}

?>
