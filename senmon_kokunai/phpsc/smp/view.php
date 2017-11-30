<?php
/*
########################################################################
#
#  フリープランSALE用
#
#  @copyright  2014 BUD International
#  @version    1.0.0
########################################################################

/********
* include
*********/
include_once($SharingPSPath . 'pageArticlesInfo.php');
//include_once('pageArticlesInfo.php');

/*******************************************************
	* cmsToHtmlnew()　CMSで入力したツアーをランダムで表示する

	* 引数 ID、ツアーMAX本数、タイトル制限文字バイト数、ポイント制限文字バイト数
	* 返り値
 ******************************************************/
function cmsToHtmlnew($id,$tour_max='',$title_max_width='',$tmp_name=''){

	global $GlobalTourList;
	global $GlobalArticleSet;
	global $SettingData;
	global $PageAttributeNameAry;
	global $RelativeNPath;
	global $AttendingIncPath;
	global $landSharePath;
	global $HttpAttendingPath;
	global $kyotend;

/*
echo "<pre>";
print_r($GlobalTourList);
echo "</pre>";
*/

	//一括指定
	if(empty($title_max_width)){
		$title_max_width = 600;
	}

	//記事テンプレート取得
	if(!empty($landSharePath)){
		$incPath = $landSharePath.'inc/' . $tmp_name;
	}
	else{
		$incPath = $AttendingIncPath . $tmp_name;
	}

	//記事タイトル取得
	$set_art_title = $SettingData->ArticleConfig["$id"]['article_title'];

	//拠点IDを取得
	$kyoten_id = $SettingData->ArticleConfig["$id"]['kyoten_di'];

 	if(!is_array($GlobalTourList["$id"]["$set_art_title"])){
		//該当記事のデータが無いときは何もしない
		return false;
	}

	//海外・国内、タイトル毎のツアーリスト
	$tourAry = $GlobalTourList["$id"]["$set_art_title"];

	if(!is_array($tourAry)){
		return false;
	}
	foreach($tourAry as $num =>$GlobalTourData){

		if(empty($GlobalTourData['tour_url'])){
			continue;
		}

		if(strpos($GlobalTourData['price_min_max'],'受付終了') !== false || $GlobalTourData['price_min_max'] == 0 || $GlobalTourData['price_min_max'] == ''){
			//URLがDSかどうか判定
			if($GlobalTourData['url_type'] != 'noDS'){
				//料金なしはとばす
				continue;
			}
		}

		//タイトル文字数制限処理
		if(!empty($GlobalTourData['p_course_name'])){
			$GlobalTourData['p_course_name']= stringControl($GlobalTourData['p_course_name'],$title_max_width);
		}
		include($incPath);

	}

}



/*******************************************************
	* cmsToHtmlnews()　CMSで入力したツアーを表示する

	* 引数 ID、ツアーMAX本数、タイトル制限文字バイト数、ポイント制限文字バイト数
	* 返り値
 ******************************************************/
function cmsToHtmlranking($id,$tour_max='',$title_max_width='',$tmp_name=''){

	global $GlobalTourList;
	global $GlobalArticleSet;
	global $SettingData;
	global $PageAttributeNameAry;
	global $RelativeNPath;
	global $AttendingIncPath;

/*
echo "<pre>";
print_r($SettingData->ArticleConfig);
echo "</pre>";
*/
/*
echo "<pre>";
print_r($GlobalTourList);
echo "</pre>";
*/
	//一括指定
	if(empty($cap_max_width)){
		$cap_max_width = 600;
	}
	if(empty($title_max_width)){
	$title_max_width = 600;
	}
	if(empty($point_max_width)){
	$point_max_width = 600;
	}


	//記事テンプレート取得
	$incPath = $AttendingIncPath . $tmp_name;
	//記事タイトル取得
	$set_art_title = $SettingData->ArticleConfig["$id"]['article_title'];
	//拠点IDを取得
	$kyoten_id = $SettingData->ArticleConfig["$id"]['kyoten_di'];

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

			if(empty($GlobalTourData['tour_url'])){
				continue;
			}
			//最大表示数を超えたら終わり
			if(!empty($tour_max)){
				if($tourNum > $tour_max){
					break;
				}
			}
			if($tourNum ==1){
				$css =1;
			}
			elseif($tourNum ==2){
				$css =2;
			}
			elseif($tourNum ==3){
				$css =3;
			}
			//タイトル文字数制限処理
			$GlobalTourData['p_course_name'] = stringControl($GlobalTourData['p_course_name'],$title_max_width);

			if(strpos($GlobalTourData['price_min_max'],'受付終了') !== false || $GlobalTourData['price_min_max'] == 0 || $GlobalTourData['price_min_max'] == ''){
				//URLがDSかどうか判定
				if($GlobalTourData['url_type'] != 'noDS'){
					//今回は料金なしはとばす
					continue;
					//$GlobalTourData['price_min_max'] = '受付終了';
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

			include($incPath);
			$tourNum++;
		}
}


/*******************************************************
	* cmsToHtml()　CMSで入力したツアーを表示する

	* 引数 ID、ツアーMAX本数、タイトル制限文字バイト数、ポイント制限文字バイト数
	* 返り値
 ******************************************************/
function cmsToHtml($id,$tour_max='',$title_max_width='',$point_max_width='',$tmp_name='',&$display_tour_num = 0){

	global $GlobalTourList;
	global $GlobalArticleSet;
	global $SettingData;
	global $PageAttributeNameAry;
	global $RelativeNPath;
	global $AttendingIncPath;
	global $landSharePath;
	global $HttpAttendingPath;
	global $PathSenmonCommon;
	global $PathSenmonCommon;
/*
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
	if(empty($cap_max_width)){
		$cap_max_width = 600;
	}

	//記事テンプレート取得
	if(!empty($landSharePath)){
		$incPath = $landSharePath.'inc/' . $tmp_name;
	}
	else{
		$incPath = $PathSenmonCommon. 'inc/smp/genchiHacchaku/'.$tmp_name;
	}

	//記事タイトル取得
	$set_art_title = $SettingData->ArticleConfig["$id"]['article_title'];

	//拠点IDを取得
	$kyoten_id = $SettingData->ArticleConfig["$id"]['kyoten_di'];

 	if(!isset($GlobalTourList["$id"]["$set_art_title"]) || !is_array($GlobalTourList["$id"]["$set_art_title"])){
		//該当記事のデータが無いときは何もしない
		return false;
	}

	//海外・国内、タイトル毎のツアーリスト
	$tourAry = $GlobalTourList["$id"]["$set_art_title"];
	$tourCnt = count($tourAry);

	if(!is_array($tourAry)){
		return false;
	}
	//グループで配列作り直し
	/*$q_group='';
	foreach($tourAry as $key => $GlobalTourData){
		$q_group=rtrim($GlobalTourData['q_group']);
		if(!empty($q_group)){
			$GroupAry[$q_group][]=$GlobalTourData;
		}
	}
*/

    $tourNum = 0;
	foreach($tourAry as $num =>$GlobalTourData){

		$p_point_all ='';
		$p_point ='';
		$icon ='';

		//最大表示数を超えたら終わり
		if(!empty($tour_max)){
			if($tourNum > $tour_max){
				break;
			}
		}

		if(empty($GlobalTourData['tour_url'])){
			continue;
		}

		//タイトル文字数制限処理
		if(!empty($GlobalTourData['p_course_name'])){
			$GlobalTourData['p_course_name']= stringControl($GlobalTourData['p_course_name'],$title_max_width);
		}

		//ポイント文字数制限処理
		$GlobalTourData['p_point_all'] = stringControl($GlobalTourData['p_point_all'],$point_max_width);

		//ポイント１，２，３の間改行したいらしい
		/*if(!empty($GlobalTourData['p_point1'])){
			$p_point_all .= '<li>'.stringControl($GlobalTourData['p_point1'],$point_max_width).'</li>';
		}
		//ポイント２
		if(!empty($GlobalTourData['p_point2'])){
			$p_point_all .= '<li>'.stringControl($GlobalTourData['p_point2'],$point_max_width).'</li>';
		}
		//ポイント３
		if(!empty($GlobalTourData['p_point3'])){
			$p_point_all .= '<li>'.stringControl($GlobalTourData['p_point3'],$point_max_width).'</li>';
		}
		if(!empty($p_point_all)){
			$p_point = $p_point_all;
		}*/

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
		$icon ='';
		$iconNum='';
		if(isset($GlobalTourData['q_icon1']) && ($GlobalTourData['q_icon1']==1 || $GlobalTourData['q_icon1']=='１')){
			$icon .=<<<EOD
			<li><img src="/attending/kokunai/jiyuhjin/images/Icon01.gif" alt="昼食付き" /></li>
EOD;
			$iconNum++;
		}
		if(isset($GlobalTourData['q_icon2']) && ($GlobalTourData['q_icon2']==1 || $GlobalTourData['q_icon2']=='１')){
			$icon .=<<<EOD
			<li><img src="/attending/kokunai/jiyuhjin/images/Icon02.gif" alt="昼食（お弁当）付き" /></li>
EOD;
			$iconNum++;
		}
		elseif(isset($GlobalTourData['q_icon3']) && ($GlobalTourData['q_icon3']==1 || $GlobalTourData['q_icon3']=='１')){
			$icon .=<<<EOD
			<li><img src="/attending/kokunai/jiyuhjin/images/Icon03.gif" alt="夕食付き" /></li>
EOD;
			$iconNum++;
		}
		if(isset($GlobalTourData['q_icon4']) && ($GlobalTourData['q_icon4']==1 || $GlobalTourData['q_icon4']=='１')){
			$icon .=<<<EOD
			<li><img src="/attending/kokunai/jiyuhjin/images/Icon04.gif" alt="専門ガイド付き" /></li>
EOD;
			$iconNum++;
		}
		if(isset($GlobalTourData['q_icon5']) && ($GlobalTourData['q_icon5']==1 || $GlobalTourData['q_icon5']=='１')){
			$icon .=<<<EOD
			<li><img src="/attending/kokunai/jiyuhjin/images/Icon05.gif" alt="歩行距離1km未満" /></li>
EOD;
		$iconNum++;
		}
		if(isset($GlobalTourData['q_icon6']) && ($GlobalTourData['q_icon6']==1 || $GlobalTourData['q_icon6']=='１')){
			$icon .=<<<EOD
			<li><img src="/attending/kokunai/jiyuhjin/images/Icon06.gif" alt="歩行距離約1〜3km" /></li>
EOD;
			$iconNum++;
		}
		if(isset($GlobalTourData['q_icon7']) && ($GlobalTourData['q_icon7']==1 || $GlobalTourData['q_icon7']=='１' )&& $iconNum!=6){
			$icon .=<<<EOD
			<li><img src="/attending/kokunai/jiyuhjin/images/Icon07.gif" alt="歩行距離約3〜5km" /></li>
EOD;
			$iconNum++;
		}
		if(isset($GlobalTourData['q_icon8']) && ($GlobalTourData['q_icon8']==1 || $GlobalTourData['q_icon8']=='１')&& $iconNum!=6){
			$icon .=<<<EOD
			<li><img src="/attending/kokunai/jiyuhjin/images/Icon08.gif" alt="歩行距離約5km〜" /></li>
EOD;
			$iconNum++;
		}


		if(!empty($icon)){
			$icon='<ul>'.$icon.'</ul>';
		}

		if(strpos($GlobalTourData['price_min_max'],'受付終了') !== false || $GlobalTourData['price_min_max'] == 0 || $GlobalTourData['price_min_max'] == ''){
			//URLがDSかどうか判定
//			if(isset($GlobalTourData['url_type']) && $GlobalTourData['url_type'] != 'noDS'){
				//料金なしはありません表示
				continue;
				//$GlobalTourData['price_min_max'] = '受付終了';
//			}
		}
		include($incPath);
		$tourNum++;
	}
	$display_tour_num = $tourNum;

}

/*******************************************************
	* retCnt()　有効な商品の数を返す

	* 引数 $kind 何でまとめるか

	* 返り値
	* $dsurl_valid_count 有効DSの数

 ******************************************************/
function retCnt($kind,$type){

	global $SettingData;

	$css = '';
	$dsurl_valid_count = 0;

//	if(stripos($kind, 'article_conf') !== false){
	if($type == BUS_STRING){
		//busの場合は、ここで終わらす。
		return  $SettingData->ArticleConfig["$kind"]['dsurl_valid_count']+$SettingData->ArticleConfig["$kind"]['othre_url_count'];
	}
	elseif (preg_match("/^[a-z_]+$/", $kind)){
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
//		if($kind == $artData["$judge"]){
		if($artId == $kind){
			$dsurl_valid_count = $dsurl_valid_count + $SettingData->ArticleConfig["$artId"]['dsurl_valid_count'];
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


?>
