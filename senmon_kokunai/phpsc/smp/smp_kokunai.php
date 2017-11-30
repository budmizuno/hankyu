<?php
/**
 * 下記ファイルから複製
 * /var/www/html/hankyu-travel.com/attending/senmon_kaigai/phpsc/smp/smp_kokunai.php
 * 専門店 国内 スマホ用config
 * @author leaf
 * @since  2017/02/02
 */

/*
#################################################################
	各ページで持つ設定ファイル 海外トップ
#################################################################
*/

date_default_timezone_set('Asia/Tokyo');

//include_once('class_maplink.php');
//include_once($PathSharing14.'phpsc/class_searchBox.php');
////include_once($PathSharing. '__br/phpsc/class_searchBox.php');
//include_once($_SERVER['DOCUMENT_ROOT'] .'/sharing/common16/phpsc/kyotenArticlesInfo.php');

//レコメンド用発地
if(empty($GlobalMaster['GM_kencode'])){
	new GM_kencode;
}
$recoHatsu = '';
$recoHatsuComma = '';
$recoHatsuAry = array();
foreach($GlobalMaster['kencode'] as $GM_kencode){
	if($GM_kencode['kyotenId']==$kyotenId){
		$recoHatsuAry[$GM_kencode['kenCode']]=$GM_kencode['kenCode'];
	}
}
if(is_array($recoHatsuAry)){
    $recoHatsu =implode("_", $recoHatsuAry);
	$recoHatsuComma = implode(",", $recoHatsuAry);
}

/**************************************
*  表示拠点　
*  $SetData->dispKyotenId  setDispKyoten.phpで判定している
***************************************/
if(!empty($SetData->dispKyotenId)){
	$kyotenId =$SetData->dispKyotenId;
}
else{
	$kyotenId = preg_replace('/\.php.*/','',basename($_SERVER["SCRIPT_NAME"]));
}

/**************************************
* 検索ボックスの設定
***************************************/
//sharing/common14/phpsc/class_searchBox.php
if($kyotenId =='index'){
	$KyotenID ='top';
}
else{
	$KyotenID = $kyotenId;
}

//$rqPara='';
//$searchBoxtemp = $PathSharing14.'inc/Searchbox_i2015.php';


/**************************************
* 拠点名
***************************************/
if(empty($GlobalMaster['kyotenUse'])){
	new GM_kyotenUse;
}
if($kyotenId !='index'){
	foreach($GlobalMaster['kyotenUse'] as $kyotendata){
		if($kyotendata['kyotenId']==$kyotenId && $naigai==$kyotendata['naigai']){
			$kyotenName = $kyotendata['kyotenName'].'発';
			break;
		}
	}
}
else{
	$kyotenName ='';
}

/**************************************
* ファセットの数
***************************************/
//sharing/common14/phpsc/class_searchBox.php
//$facet=new SearchActionForFacet($naigai,$KyotenID);//拠点のファセット数

/**************************************
* map
***************************************/
if(empty($GlobalMaster['kyotenUse'])){
	new GM_kyotenUse;
}
//拠点名取得
if($kyotenId !='index'){
	foreach($GlobalMaster['kyotenUse'] as $dataAry){
		if($dataAry['kyotenId'] == $kyotenId &&  $dataAry['naigai'] == 'i'){
			$mapKyotenName = $dataAry['kyotenName'];

		}
	}
}

/*
//p_hatsuの生成
$p_hatsu = bindingHatsuAry($p_hatsuAry->TgDataAry['i'][$kyotenId]);
//内外
$msNaigai = 'i';
//url
$msPath = 'top';
//方面
$msHomen = 'top';
//出発地（101,130,133）
$msHatsu = $p_hatsu;
//引数　国内海外　方面　パス　出発地パラメータ　マップタイプ　拠点名
$MapDisplay = new MapLink($msNaigai,$msHomen,$msPath,$msHatsu,$msType[$page_type],$mapKyotenName);
$MapDisplay->setKyotenFlg("",$KyotenMainId);
//ファセット取得
$MapDisplay->getFacet();
$mapHtml = $MapDisplay->display();
$mapAllFacet = $MapDisplay->selectKyotenFacet;
*/

//$SearchHatsuAry = $p_hatsuAry;
//$SearchHatsu = $p_hatsu;

/**************************************
*  setting情報のartとcsvのデータ取得
*	 戻り値：$GlobalTourList
***************************************/

$myPath ='/kaigai/';
getGlobalTourList($myPath,$kyotenId);


/**************************************
*  ローテンションバナー
***************************************/

class cmsToHtmlrotationBn{
	public $Flg;//自動・手動の判定 本体のjsに記載
	function __construct($art='',$temp) {
		global $GlobalTourList,$SettingData,$AttendingPath;
		if(!empty($art)){
			$this->artI = $art;
		}
		$this->temp = $AttendingPath.'/inc/'.$temp;
		$this->getSettingData();
		if($this->artI){
			$this->getCms();
		}
		$this->getCsv();
		$this->makeHtml();
}
	function makeHtml(){
		global $kyotenId;
		$count ='';

		if($kyotenId == 'index'){
			$num='';

			foreach($this->bnWeb as $data){
				if(!empty($data['tour_url'] )){
					$data['p_img1_filepath'] = str_replace(array("http://x.hankyu-travel.com/cms_photo_image/","http://x.hankyu-travel.com/photo_db/"), "//x.hankyu-travel.com/cms_photo_image/", $data['p_img1_filepath']);
					$this->imgLi .= '<p><a href="'.$data['tour_url'].'"><img src="'.$data['p_img1_filepath'].'" alt="'.$data['p_img1_caption'].'" /></a></p>';
					$this->btnLi .= '<li><span>'.$data['q_title'].'</span></li>';
					$num++;
				}
			}

			$count= $num;
		}
		//拠点用
		else{
			//拠点とweb課のデータがある時
			if(is_array($this->bnKyoten) && is_array($this->bnWeb)){
				$this->bnKyoten =array_merge($this->bnKyoten,$this->bnWeb);
			}
			//weg課のしかない時
			elseif(empty($this->bnKyoten) && is_array($this->bnWeb)){
				$num='';
				foreach($this->bnWeb as $data){
					if(!empty($data['tour_url'] )){
						$data['p_img1_filepath'] = str_replace(array("http://x.hankyu-travel.com/cms_photo_image/","http://x.hankyu-travel.com/photo_db/"), "//x.hankyu-travel.com/cms_photo_image/", $data['p_img1_filepath']);
						$this->imgLi = '<p><a href="'.$data['tour_url'].'"><img src="'.$data['p_img1_filepath'].'" alt="'.$data['p_img1_caption'].'" /></a></p>';
						$this->btnLi = '<li><span>'.$data['q_title'].'</span></li>';
						$num++;
					}
				}
				$count= $num;
			}

			$num='';
			if(is_array($this->bnKyoten)){
				foreach($this->bnKyoten as $data){
					if(!empty($data['tour_url'] )){
						$data['p_img1_filepath'] = str_replace(array("http://x.hankyu-travel.com/cms_photo_image/","http://x.hankyu-travel.com/photo_db/"), "//x.hankyu-travel.com/cms_photo_image/", $data['p_img1_filepath']);
						$this->imgLi .= '<p><a href="'.$data['tour_url'].'"><img src="'.$data['p_img1_filepath'].'" alt="'.$data['p_img1_caption'].'" /></a></p>';
						$this->btnLi .= '<li><span>'.$data['q_title'].'</span></li>';
						$num++;
					}
				}
				$count= $num;
			}
		}

		if(!empty($count)){
			include_once($this->temp);
		}
		else{
			//1個もない時は非表示
			return false;
		}
	}

	//csvからデータ取得
	function getCsv(){
		global $PathArticles,$artDir,$kyotenId,$GlobalMaster;

		$File = $PathArticles.'contents_x/csv/csv_rotation_bn2014.csv';
		$dataAry=$this->ReadCsv($File);

		if(empty($GlobalMaster['kyotenUse'])){
			new GM_kyotenUse;
		}

		foreach($dataAry as $data){
			if($data['q_category'] == '海外トップ（550×190）'){
				if($kyotenId =='index'){
					$kyotenName ='bot';
				}
				else{
					//拠点idから拠点名取得
					foreach($GlobalMaster['kyotenUse'] as $kyotendata){
						if($kyotendata['kyotenId']==$kyotenId){
							$kyotenName = $kyotendata['kyotenName'];
							break;
						}
					}
				}

				if($data['q_group'] == $kyotenName){
					$bnWeb[]=$data;
				}
			}
		}
		$this->bnWeb = $bnWeb;
	}

	//csvファイルの読み込み
	function ReadCsv($File){

		$handle = fopen($File, "r");
		if ($handle){
			$num = 0;
			while (!feof($handle)) {
				$buffer = rtrim(fgets($handle, 9999));	//日本語ファイルはfgetcsv使うのやめておく
				$buffer = str_replace('"', '', $buffer);	//ダブルクォーテーション不要
				//空白行はサヨナラ
				if(empty($buffer)){
					continue;
				}
				//1行目も日本語名なのでいらない
				if($num == 1){
					++$num;
					continue;
				}

				$data = explode("\t", $buffer);
				if($num == 0){
					$keyAry = array();
					foreach($data as $no => $val){
						if(empty($val)){
							continue;
						}
						//web課と拠点でタイトルが違うので統一する
						if($val == 'q_banner_title'){
							$val = 'q_title';
						}
						$keyAry[$no] = $val;
					}
					++$num;
				}
				else{
					foreach($keyAry as $no => $key){
						$csvdata[$key] = $data[$no];
					}
					$CsvAry[]=$csvdata;
				}
			}
			fclose($handle);
		}
		return $CsvAry;
	}

	//cmsからデータ取得
	function getCms(){
		global $GlobalTourList,$SettingData;

		//記事タイトル取得
		$set_art_titleI = $SettingData->ArticleConfig["$this->artI"]['article_title'];

		if(!is_array($GlobalTourList["$this->artI"]["$set_art_titleI"])){
			return false;
		}
		else{
			$bnAry = $GlobalTourList["$this->artI"]["$set_art_titleI"];
		}
		$this->bnKyoten = $bnAry;
	}

	//セッティングから設定取得
	function getSettingData(){
		global $SettingData;

		//ローテーションバナー 手動:1自動:0
		if(empty($SettingData->SettingAey['スライドバナー'])){
			$this->Flg = 0;
		}
		else{
			$this->Flg = 1;
		}
		//ローテーションバナー順序
		if($SettingData->SettingAey['スライドバナー順番']){
			$this->BnTurnary =explode('=',$SettingData->SettingAey['スライドバナー順番']);
		}
	}

}

/*******************************************************
	*売れ筋ランキング
 * cmsToHtmlRankingTop　csvで入力したツアーを表示する
 * 	(海外用)
 ******************************************************/

class cmsToHtmlRanking{
	function __construct($csv='',$tour_max,$temp) {
		global $GlobalTourList,$SettingData,$AttendingPath;

		if(!empty($csv)){
			$this->csv = $csv;
		}

		$this->temp = $AttendingPath.'/inc/'.$temp;

		if($this->csv){
			$this->getCms();
			$this->makeHtml($tour_max);
		}
	}

	function makeHtml($tour_max){
		global $kyotenId,$GlobalMaster,$HttpAttendingImagesPath,$kyotenName;

		if(!empty($this->csvdata)){
			$tourAry = $this->csvdata;
		}
		//$tourNum=0;
		$imgNo ='';
		$htmlI ='';
		$htmlD ='';
		$imgALT ='';

		if(is_array($tourAry)){

			$tourNum='';
			$html='';
			foreach($tourAry as $key => $GlobalTourData){

				if(empty($GlobalTourData['tour_url'])){
					continue;
				}
				//最大表示数を超えたら終わり
				if($tourNum >= $tour_max){
					break;
				}

				if(strpos($GlobalTourData['price_min_max'],'受付終了') !== false || $GlobalTourData['price_min_max'] == 0 || $GlobalTourData['price_min_max'] == ''){
					//URLがDSかどうか判定
					if($GlobalTourData['url_type'] != 'noDS'){
						//料金なしはとばす
						continue;
						//$GlobalTourData['price_min_max'] = '受付終了';
					}
				}
				$imgNo = sprintf('%02d', $tourNum+1);
				$imgALT = $tourNum+1;
				$GlobalTourData['p_course_name']=mb_convert_kana($GlobalTourData['p_course_name'],'KVa','UTF-8');
				$GlobalTourData['p_img1_filepath'] = str_replace(array("http://x.hankyu-travel.com/cms_photo_image/","http://x.hankyu-travel.com/photo_db/"), "//x.hankyu-travel.com/cms_photo_image/", $GlobalTourData['p_img1_filepath']);
				$html .=<<<EOD
				<a href="{$GlobalTourData['tour_url']}">
				<dl>
				<dd class="pht"><img src="{$GlobalTourData['p_img1_filepath']}" alt="{$GlobalTourData['p_img1_caption']}" /></dd>
				<dd class="rank{$imgNo}"><span>{$imgALT}位</span></dd>
				<dt class="">{$GlobalTourData['p_course_name']}</dt>
				<dd class="fee">{$GlobalTourData['price_min_max']}</dd>
				</dl>
				</a>
EOD;
				$tourNum++;
			}
			include_once($this->temp);
		}
	}


	//cmsからデータ取得
	function getCms(){
		global $GlobalTourList,$SettingData;

		//記事タイトル取得
		$set_art_title = $SettingData->ArticleConfig["$this->csv"]['article_title'];
		if(!is_array($GlobalTourList["$this->csv"]["$set_art_title"])){
			$this->csvdata='';
		}
		else{
			//海外ツアーリスト
			$this->csvdata = $GlobalTourList["$this->csv"]["$set_art_title"];
		}
	}

}

/*******************************************************
 * 旅行説明会
 *
 ******************************************************/

class Setsumeikai{
	//$naigai:allまたはiまたはd　
	function __construct($naigai,$kyotenid=''){
		global $IncPath,$GlobalMaster,$kyotenId,$path16;

		$url_i='';
		$url_d='';
		$Link='';

		$this->naigai=$naigai;
		if(!empty($temp)){
			$this->myTemplate = $IncPath . $temp;
		}
		else{
			$this->myTemplate = $IncPath . get_class($this) . '.php';
		}
		if(!empty($kyotenid)){
			$kyoten = $kyotenid;
		}
		else{
			if($kyotenId =='index'){
				$kyoten = 'top';
			}
			else{
				$kyoten = $kyotenId;
			}
		}

		if($kyoten !='top'){
			if(empty($GlobalMaster['kyotenUse'])){
				new GM_kyotenUse;
			}

			if($this->naigai == 'all'){
				foreach($GlobalMaster['kyotenUse'] as $kyotenDataAry){
					if($kyotenDataAry['naigai'] == 'i' && $kyotenDataAry['kyotenId'] == $kyoten){
						if(!empty($kyotenDataAry['setsumeikaiURL'])){
							$url_i = $kyotenDataAry['setsumeikaiURL'];
						}
					}
					elseif($kyotenDataAry['naigai'] == 'd' && $kyotenDataAry['kyotenId'] == $kyoten){
						if(!empty($kyotenDataAry['setsumeikaiURL'])){
							$url_d = $kyotenDataAry['setsumeikaiURL'];
						}
					}
				}

			}
			elseif($this->naigai == 'i'){
				foreach($GlobalMaster['kyotenUse'] as $kyotenDataAry){
					if($kyotenDataAry['naigai'] == $this->naigai && $kyotenDataAry['kyotenId'] == $kyoten){
						if(!empty($kyotenDataAry['setsumeikaiURL'])){
							$url_i = $kyotenDataAry['setsumeikaiURL'];

						}
					}
				}
			}
			elseif($this->naigai == 'd'){
				foreach($GlobalMaster['kyotenUse'] as $kyotenDataAry){
					if($kyotenDataAry['naigai'] == $this->naigai && $kyotenDataAry['kyotenId'] == $kyoten){
						if(!empty($kyotenDataAry['setsumeikaiURL'])){
							$url_d = $kyotenDataAry['setsumeikaiURL'];
						}
					}
				}
			}
		}


		if($this->naigai == 'i' && $kyoten == 'top'){
			$this->Link =<<<EOD
			<p class="btn_i"><a href="{$path16->HttpTop}/setsumeikai/">一覧を見る</a></p>
EOD;
		}
		elseif($this->naigai == 'i' && $kyoten != 'top' && !empty($url_i)){
			$this->Link =<<<EOD
			<p class="btn_i"><a href="{$path16->HttpTop}{$url_i}">一覧を見る</a></p>
EOD;
		}


	}
}

/**************************************
* イチオシ
***************************************/

class cmsToHtmlIchioshi{
	function __construct($art='',$num,$temp) {
		global $GlobalTourList,$SettingData,$AttendingPath;

		if(!empty($art)){
			$this->art = $art;
		}
		$this->temp = $temp;

		if($this->art){
			$this->getCms();

/*
echo "<br> -------test101 <br>";
echo "<pre>";
print_r($GlobalTourList);
echo "</pre>";
echo "<br> -------test102<br>";
*/


		}
		$this->makeHtml($num);
	}

	function makeHtml($tour_max){
		global $kyotenId,$GlobalMaster;

		if(!empty($this->cmsdata)){
			$tourAry = $this->cmsdata;
		}

/*
echo "<br> -------test101 <br>";
echo "<pre>";
print_r($tourAry);
echo "</pre>";
echo "<br> -------test102<br>";
*/

		if(is_array($tourAry)){
			$tourNum='';
			$html='';
			foreach($tourAry as $key => $GlobalTourData){
				if(empty($GlobalTourData['tour_url'])){
					continue;
				}
				//最大表示数を超えたら終わり
				if($tourNum >= $tour_max){
					break;
				}
				/*if(strpos($GlobalTourData['price_min_max'],'受付終了') !== false || $GlobalTourData['price_min_max'] == 0 || $GlobalTourData['price_min_max'] == ''){
					//URLがDSかどうか判定
					if($GlobalTourData['url_type'] != 'noDS'){
						//料金なしはとばす
						continue;
						//$GlobalTourData['price_min_max'] = '受付終了';
					}
				}*/
				$GlobalTourData['p_img1_filepath'] = str_replace(array("http://x.hankyu-travel.com/cms_photo_image/","http://x.hankyu-travel.com/photo_db/"), "//x.hankyu-travel.com/cms_photo_image/", $GlobalTourData['p_img1_filepath']);

/*
$html.=<<<EOD
				<a href="{$GlobalTourData['tour_url']}">
				<dl>
				<dd class="pht"><img src="{$GlobalTourData['p_img1_filepath']}" alt="{$GlobalTourData['p_img1_caption']}" /></dd>
				<dt>{$GlobalTourData['p_course_name']}</dt>
				<dd class="txt">{$GlobalTourData['p_point1']}</dd>
				</dl>
				</a>
EOD;
*/
$html.=<<<EOD
<li class="tour swiper-slide swiper-slide-active" style="margin-right: 10px;">
	<a href="{$GlobalTourData['tour_url']}" >
	<p>
		<img src="{$GlobalTourData['p_img1_filepath']}" alt="{$GlobalTourData['p_img1_caption']}">
	</p>
	<dl>
		<dt>{$GlobalTourData['p_course_name']}</dt>
		<dd>{$GlobalTourData['price_min_max']}</dd>
	</dl>
	</a>
</li>
EOD;

$tourNum++;
			}

			if(empty($GlobalMaster['kyotenUse'])){
				new GM_kyotenUse;
			}
			//拠点名取得
			if($kyotenId !='index'){
				foreach($GlobalMaster['kyotenUse'] as $data){
					if($data['kyotenId'] ==$kyotenId  && $data['naigai'] == 'i'){
						$kyotenname = $data['kyotenName'];
						break;
					}
				}
			}
			include_once($this->temp);
		}
	}

	//cmsからデータ取得
	function getCms(){
		global $GlobalTourList,$SettingData;

		//記事タイトル取得
		$set_art_title = $SettingData->ArticleConfig["$this->art"]['article_title'];
		$this->cmsdata='';

		if(!is_array($GlobalTourList["$this->art"]["$set_art_title"])){
			return false;
		}
		else{
			//海外ツアーリスト
			$this->cmsdata = $GlobalTourList["$this->art"]["$set_art_title"];
		}
	}

}

/**************************************
* 格安
***************************************/

class cmsToHtmlKakuyasu{
	function __construct($art='',$num,$temp) {
		global $GlobalTourList,$SettingData,$AttendingPath;

		if(!empty($art)){
			$this->art = $art;
		}
		$this->temp = $AttendingPath.'/inc/'.$temp;

		if($this->art){
			$this->getCms();
		}
		$this->makeHtml($num);
	}

	function makeHtml($tour_max){
		global $kyotenId,$GlobalMaster;

		if(!empty($this->cmsdata)){
			$tourAry = $this->cmsdata;
		}

		if(is_array($tourAry)){
			$tourNum='';
			$html='';
			foreach($tourAry as $key => $GlobalTourData){
				if(empty($GlobalTourData['tour_url'])){
					continue;
				}
				//最大表示数を超えたら終わり
				if($tourNum >= $tour_max){
					break;
				}
				if(strpos($GlobalTourData['price_min_max'],'受付終了') !== false || $GlobalTourData['price_min_max'] == 0 || $GlobalTourData['price_min_max'] == ''){
					//URLがDSかどうか判定
					if($GlobalTourData['url_type'] != 'noDS'){
						//料金なしはとばす
						continue;
						//$GlobalTourData['price_min_max'] = '受付終了';
					}
				}

				$GlobalTourData['p_img1_filepath'] = str_replace(array("http://x.hankyu-travel.com/cms_photo_image/","http://x.hankyu-travel.com/photo_db/"), "//x.hankyu-travel.com/cms_photo_image/", $GlobalTourData['p_img1_filepath']);

				if($GlobalTourData['url_type'] == 'noDS'){
			$html.=<<<EOD
<a href="{$GlobalTourData['tour_url']}">
    <dl>
        <dd class="pht"><img src="{$GlobalTourData['p_img1_filepath']}" alt="{$GlobalTourData['p_img1_caption']}"></dd>
        <!--<dd class="sale"><span>激安価格!!</span></dd> -->
        <dd class="fee">{$GlobalTourData['price_min_max']}</dd>
        <dt>{$GlobalTourData['p_course_name']}</dt>
    </dl>
 </a>
EOD;
				}
				else{

				$html.=<<<EOD
<a href="{$GlobalTourData['tour_url']}">
    <dl>
        <dd class="pht"><img src="{$GlobalTourData['p_img1_filepath']}" alt="{$GlobalTourData['p_img1_caption']}"></dd>
        <!--<dd class="sale"><span>激安価格!!</span></dd> -->
        <dd class="fee">{$GlobalTourData['price_min_max']}</dd>
        <dt>{$GlobalTourData['p_course_name']}</dt>
    </dl>
 </a>
EOD;
				}
$tourNum++;
			}


			include_once($this->temp);
		}
	}

	//cmsからデータ取得
	function getCms(){
		global $GlobalTourList,$SettingData;

		//記事タイトル取得
		$set_art_title = $SettingData->ArticleConfig["$this->art"]['article_title'];

		$this->cmsdata='';
		foreach($set_art_title as $art_title){
			if(is_array($GlobalTourList["$this->art"]["$art_title"])){
				foreach($GlobalTourList["$this->art"]["$art_title"] as $tour){
					$this->cmsdata[] = $tour;
				}
			}
		}
	}

}

/*******************************************************
 * もっと見るボタン
 * 	(海外用)	 引数:ボタン名,内外
 ******************************************************/

class getBtnUrl{
	function __construct($key,$naigai){
		global $kyotenId;
		$this->kyoten = $kyotenId;
		$this->$key($naigai);
	}
	//新聞掲載
	function newspaper($naigai){
		global $GlobalMaster,$path16;

		if(empty($GlobalMaster['kyotenUse'])){
			new GM_kyotenUse;
		}

		if($this->kyoten !='index'){
			foreach($GlobalMaster['kyotenUse'] as $data){
				if($data['kyotenId'] ==$this->kyoten  && $data['naigai'] == $naigai){
					echo $newspaperURL = $path16->HttpTop.$data['newspaperURL'];
				}
			}
		}
		else{
			echo $newspaperURL = $path16->HttpTop.'/newspaper_ad/';
		}
	}
	//催行確定
	function saikoukakutei($naigai){
		global $GlobalMaster,$path16;

		if(empty($GlobalMaster['kyotenUse'])){
			new GM_kyotenUse;
		}
		if($this->kyoten !='index'){
			foreach($GlobalMaster['kyotenUse'] as $data){
				if($data['kyotenId'] ==$this->kyoten  && $data['naigai'] == $naigai){
					echo $saikoukakuteiURL = $path16->HttpTop.$data['saikoukakuteiURL'];
				}
			}
		}
		else{
			echo $saikoukakuteiURL = $path16->HttpTop.'/saikou_kakutei/';
		}
	}
	//売れ筋ランキング
	function best10($naigai){
		global $GlobalMaster,$path16;

		if(empty($GlobalMaster['kyotenUse'])){
			new GM_kyotenUse;
		}
		if($this->kyoten !='index'){
			foreach($GlobalMaster['kyotenUse'] as $data){
				if($data['kyotenId'] ==$this->kyoten  && $data['naigai'] == $naigai){
					echo $saikoukakuteiURL = $path16->HttpTop.$data['best10URL'];
				}
			}
		}
		else{
			echo $saikoukakuteiURL = $path16->HttpTop.'/best10/kaigai/';
		}
	}
	//格安
	function kakuyasu($naigai){
		global $GlobalMaster,$path16;

		if(empty($GlobalMaster['kyotenUse'])){
			new GM_kyotenUse;
		}
		if($this->kyoten !='index'){
			foreach($GlobalMaster['kyotenUse'] as $data){
				if($data['kyotenId'] ==$this->kyoten  && $data['naigai'] == $naigai){
					echo $kakuyasuURLURL = $path16->HttpTop.$data['kakuyasuURL'];
				}
			}
		}
		else{
			echo $kakuyasuURL = $path16->HttpTop.'/kakuyasu/kaigai/';
		}
	}
	//新着ブログ
	function blogNew($naigai){
		global $GlobalMaster;

		if(empty($GlobalMaster['kyotenUse'])){
			new GM_kyotenUse;
		}
		if($this->kyoten !='index'){
			foreach($GlobalMaster['kyotenUse'] as $data){
				if($data['kyotenId'] ==$this->kyoten  && $data['naigai'] == $naigai){
					$url=str_replace('xml','php',$data['blognewURL']);
					echo $saikoukakuteiURL = 'http://blog.hankyu-travel.com'.$url;
				}
			}
		}
		else{
			echo $this->saikoukakuteiURL = 'http://blog.hankyu-travel.com/main/kaigai/';
		}
	}
}

/*******************************************************
 * 新着ブログ
 * 	(海外用)	 引数:内外,本数
 ******************************************************/

class blogNew{
	public $num;    //本数（有る無し判定用）
	public $btn; //topならボタン表示用
	public $html;   //表示用

	function __construct($naigai,$num,$type){
		global $kyotenId,$BlogHttp,$GlobalMaster,$masterCsv;
		//Blogドメイン
		$BlogDom = rtrim($BlogHttp, '/');
		if(empty($GlobalMaster['kyotenUse'])){
			new GM_kyotenUse;
		}

		$url_type = '';
		// ツアーなら
		if($type == TOUR_STRING){
			$url_type = '/main/tour';
		}else{
			$url_type = '/main/freeplan';
		}

		// 開発用
		if($masterCsv[KEY_MASTER_CSV_NEW_TOUR] == '/main_demo/inc/test.xml'){
			$XmlUrl = 'http://hei:heibud@blog.hankyu-travel.com'.$masterCsv[KEY_MASTER_CSV_NEW_TOUR];
		}
		else{
			$XmlUrl = $BlogDom.$url_type.$masterCsv[KEY_MASTER_CSV_NEW_TOUR];
		}

		$file_headers = @get_headers($XmlUrl);
		if($file_headers[0] == 'HTTP/1.0 404 Not Found' || $file_headers[0] == 'HTTP/1.1 404 Not Found'){
			return false;
		}

		$XmlParse = RssParser::RetAry($XmlUrl);

		//内外ブログ取得
		if($XmlParse !== false || !empty($XmlParse) || !empty($XmlParse->channel->item)){
			$data = $this->MakeData($XmlParse,$num);
		}

		if(!empty($data)){
			$blogAry =  $data;
		}
		else{
			$blogAry = '';
		}

		$this->makeHtml($blogAry,$num);
	}

	//xmlを配列に
	function MakeData($XmlParse,$num){
		global $kyotenId,$def_kyotenName;
		$count = 0;
		$xmlData = '';
		foreach($XmlParse->channel->item as $Obj){
			if($kyotenId != 'index'){
				if(!empty($Obj->category)){
					// 該当の拠点でない場合
					if(strpos($Obj->category,$def_kyotenName) === false){
						continue;
					}
				}else{
					continue;
				}
			}
			if($num <= $count){
				break;
			}
			if($Obj->link){
				$Time = date("Y-m-d H:i:s", strtotime($Obj->pubDate));
				$xmlData[$Time]['ttl'] = MyEcho($Obj->title);
				$xmlData[$Time]['link'] =MyEcho($Obj->link);
				$count++;
			}
			else{
				$xmlData ='';
			}
		}
		return $xmlData;
	}

	function makeHtml($dataAry,$num){
			//ブログがなかったら表示しない
		if(empty($dataAry)){
			$this->num ='';
			return false;
		}
		$count = 0;
		$li = '';
		foreach($dataAry as $data){
			if($count >= $num){
				break;
			}

		    $li .=<<<EOD
	<li><a href="{$data['link']}">{$data['ttl']}</a></li>
EOD;
		    $count++;
		    $this->num =$count;
		}
		$this->html = $li;
//new depAreaLink;
	}
}

/*******************************************************
 * 目的地から探す
 * 	 引数:内外
 ******************************************************/

class mokutekiLink{
	public $mokutekiLinkAry; //専門店[方面]国までの配列（例外：cityのハワイ入っている）
	function __construct($naigai){
		global $GlobalMaster;

		if(empty($GlobalMaster['Senmon'])){
			new GM_Senmon;
		}

		foreach($GlobalMaster['Senmon'] as $dataAry =>$data){
			if($data['naigai'] == $naigai &&	$data['right_box_type']== 'homen'){
				$houmenARry[$dataAry]=$data['senmon_name'];
			}
			elseif($data['naigai'] == $naigai &&	$data['right_box_type']== 'country'){
				foreach($houmenARry as $houmenPath => $houmenName){
					if($houmenPath == $data['homen']){
						$this->mokutekiLinkAry[$houmenPath]['houmenName']=$houmenName;
						$this->mokutekiLinkAry[$houmenPath][$dataAry]=$data['senmon_name'];
					}
				}
			}
			elseif($data['naigai'] == $naigai &&	$data['right_box_type']== 'city' && $data['dest_code'] == 'HWI'){
				foreach($houmenARry as $houmenPath => $houmenName){
					if($houmenPath == $data['homen']){
						$this->mokutekiLinkAry[$houmenPath]['houmenName']=$houmenName;
						$this->mokutekiLinkAry[$houmenPath][$dataAry]=$data['senmon_name'];
					}
				}
			}
		}
		$this->makeHtml();
	}

	function makeHtml(){

		foreach($this->mokutekiLinkAry as $houmenPath => $dataAry){
			$li ='';
			foreach($dataAry as  $key =>$data){
				if($key !='houmenName'){
			$li .=<<<EOD
			<li><a href="/{$key}">{$data}</a></li>
EOD;
				}

			}
			$dl =<<<EOD
			<dl class="depLink_{$bigKyotenId}">
			<dt><a href="/{$houmenPath}">{$dataAry['houmenName']}</a></dt>

EOD;
$dlAll .=$dl.'<dd><ul>'.$li.'</ul></dd></dl>';
		}
		echo $dlAll;

	}
}

/**************************************
*  ローテンションバナー
***************************************/

class cmsToHtmlrotationBnTop{
	public $Flg;//自動・手動の判定 本体のjsに記載
	function __construct($artI='',$temp) {
		global $GlobalTourList,$SettingData,$AttendingPath;
		if(!empty($artI)){
			$this->artI = $artI;
		}
		$this->temp = $AttendingPath.'/inc/'.$temp;

		//$this->getSettingData();
		if($this->artI){
			$this->getCms();
		}
		$this->getCsv();
		$this->makeHtml();
	}
	function makeHtml(){
		global $kyotenId;
		$count ='';
		if($kyotenId == 'index'){
			$num='';
			foreach($this->bnWeb as $data){
				if(!empty($data['tour_url'] )){

					$data['p_img1_filepath'] = str_replace(array("http://x.hankyu-travel.com/cms_photo_image/","http://x.hankyu-travel.com/photo_db/"), "//x.hankyu-travel.com/cms_photo_image/", $data['p_img1_filepath']);

					if ($num=='') {
						$this->imgLi .= '<div class="slide current">';
						$this->thumbLi .= '<li class="active"><a href="javascript:void(0);"><img src="/sharing/common16/images/thumb_frm.png"></a></li>';
					} else {
						$this->imgLi .= '<div class="slide">';
						$this->thumbLi .= '<li><a href="javascript:void(0);"><img src="/sharing/common16/images/thumb_frm.png"></a></li>';
					}
					$this->imgLi .= '<a href="'.$data['tour_url'].'">';
					$this->imgLi .= '<p class="slide_tgt"><img src="'.$data['p_img1_filepath'].'" alt="'.$data['p_img1_caption'].'" /></p>';
					if(!empty($data['q_title'])){
						$this->imgLi .= '<p class="lead">'.$data['q_title'].'</p>';
					}
					$this->imgLi .= '</a></div>';
					$this->btnLi .= '<li><img src="'.$data['p_img1_filepath'].'" alt="'.$data['p_img1_caption'].'" /></li>';
					$num++;
				}
			}
			$count = $num;
		} else {
			//拠点とweb課のデータがある時
			if(is_array($this->bnKyoten) && is_array($this->bnWeb)){
				$this->bnKyoten =array_merge($this->bnKyoten,$this->bnWeb);
			}

			//weg課のしかない時
			elseif(empty($this->bnKyoten) && is_array($this->bnWeb)){
				$num='';
				foreach($this->bnWeb as $data){
					if(!empty($data['tour_url'] )){
						$data['p_img1_filepath'] = str_replace(array("http://x.hankyu-travel.com/cms_photo_image/","http://x.hankyu-travel.com/photo_db/"), "//x.hankyu-travel.com/cms_photo_image/", $data['p_img1_filepath']);
						if ($num=='') {
							$this->imgLi .= '<div class="slide current">';
							$this->thumbLi .= '<li class="active"><a href="javascript:void(0);"><img src="/sharing/common16/images/thumb_frm.png"></a></li>';
						} else {
							$this->imgLi .= '<div class="slide">';
							$this->thumbLi .= '<li><a href="javascript:void(0);"><img src="/sharing/common16/images/thumb_frm.png"></a></li>';
						}
						$this->imgLi .= '<a href="'.$data['tour_url'].'">';
						$this->imgLi .= '<p class="slide_tgt"><img src="'.$data['p_img1_filepath'].'" alt="'.$data['p_img1_caption'].'" /></p>';
						if(!empty($data['q_title'])){
							$this->imgLi .= '<p class="lead">'.$data['q_title'].'</p>';
						}
						$this->imgLi .= '</a></div>';
						$this->btnLi .= '<li><img src="'.$data['p_img1_filepath'].'" alt="'.$data['p_img1_caption'].'" /></li>';
						$num++;
					}
				}
				$count= $num;
			}

			$num='';
			if(is_array($this->bnKyoten)){
				foreach($this->bnKyoten as $data){
					if(!empty($data['tour_url'] )){
						$data['p_img1_filepath'] = str_replace(array("http://x.hankyu-travel.com/cms_photo_image/","http://x.hankyu-travel.com/photo_db/"), "//x.hankyu-travel.com/cms_photo_image/", $data['p_img1_filepath']);
						if ($num=='') {
							$this->imgLi .= '<div class="slide current">';
							$this->thumbLi .= '<li class="active"><a href="javascript:void(0);"><img src="/sharing/common16/images/thumb_frm.png"></a></li>';
						} else {
							$this->imgLi .= '<div class="slide">';
							$this->thumbLi .= '<li><a href="javascript:void(0);"><img src="/sharing/common16/images/thumb_frm.png"></a></li>';
						}
						$this->imgLi .= '<a href="'.$data['tour_url'].'">';
						$this->imgLi .= '<p class="slide_tgt"><img src="'.$data['p_img1_filepath'].'" alt="'.$data['p_img1_caption'].'" /></p>';
						if(!empty($data['q_title'])){
							$this->imgLi .= '<p class="lead">'.$data['q_title'].'</p>';
						}
						$this->imgLi .= '</a></div>';
						$this->btnLi .= '<li><img src="'.$data['p_img1_filepath'].'" alt="'.$data['p_img1_caption'].'" /></li>';
						$num++;
					}
				}
				$count= $num;
			}
		}

		if(!empty($count)){
			include_once($this->temp);
		}
		else{
			//1個もない時は非表示
			return false;
		}
	}

	//contents_xからデータ取得
	function getCsv(){
		global $PathArticles,$artDir,$kyotenId,$GlobalMaster;

		$File = '/var/www/html/cms/articles/contents_x/csv/csv_rotation_bn2016.csv';

		$dataAry=$this->ReadCsv($File);
		if(empty($GlobalMaster['kyotenUse'])){
			new GM_kyotenUse;
		}

		foreach($dataAry as $data){
			if($data['q_category'] == '海外トップ（640×320）'){
				if($kyotenId =='index'){
					$kyotenName ='bot';
				}
				else{
					//拠点idから拠点名取得
					foreach($GlobalMaster['kyotenUse'] as $kyotendata){
						if($kyotendata['kyotenId']==$kyotenId){
							$kyotenName = $kyotendata['kyotenName'];
							break;
						}
					}
				}
				if($data['q_group'] == $kyotenName){
					$bnWeb[]=$data;
				}
			}
		}
		$this->bnWeb = $bnWeb;
	}

	//csvファイルの読み込み
	function ReadCsv($File){

		$handle = fopen($File, "r");
		if ($handle){
			$num = 0;
			while (!feof($handle)) {
				$buffer = rtrim(fgets($handle, 9999));	//日本語ファイルはfgetcsv使うのやめておく
				$buffer = str_replace('"', '', $buffer);	//ダブルクォーテーション不要
				//空白行はサヨナラ
				if(empty($buffer)){
					continue;
				}
				//1行目も日本語名なのでいらない
				if($num == 1){
					++$num;
					continue;
				}

				$data = explode("\t", $buffer);
				if($num == 0){
					$keyAry = array();
					foreach($data as $no => $val){
						if(empty($val)){
							continue;
						}
						//web課と拠点でタイトルが違うので統一する
						if($val == 'q_banner_title'){
							$val = 'q_title';
						}
						$keyAry[$no] = $val;
					}
					++$num;
				}
				else{
					foreach($keyAry as $no => $key){
						$csvdata[$key] = $data[$no];
					}
					$CsvAry[]=$csvdata;
				}
			}
			fclose($handle);
		}
		return $CsvAry;
	}

	//各拠点からcsvデータ取得
	function getCms(){
		global $GlobalTourList,$SettingData;

		//記事タイトル取得
		$set_art_titleI = $SettingData->ArticleConfig["$this->artI"]['article_title'];

		if(is_array($GlobalTourList["$this->artI"]["$set_art_titleI"])){
			//海外ツアーリスト
			$bnIdata = $GlobalTourList["$this->artI"]["$set_art_titleI"];
		}


		/***************
		セッティングで順番変えない　海外国内の順で上から5個　+　戦略課バナー
		***************/
		$i="0";
		for ($x=0; $x<5; $x++) {
			if(is_array($bnIdata[$i])){
				$bnAry[] = $bnIdata[$i];
				$i++;
			}
		}

		$this->bnKyoten = $bnAry;
	}

	//セッティングから設定取得
	function getSettingData(){
		global $SettingData;

		//ローテーションバナー 手動:1自動:0
		if(empty($SettingData->SettingAey['スライドバナー'])){
			$this->Flg = 0;
		}
		else{
			$this->Flg = 1;
		}
		//ローテーションバナー順序
		if($SettingData->SettingAey['スライドバナー順番']){
			$this->BnTurnary =explode('=',$SettingData->SettingAey['スライドバナー順番']);
		}
	}

}


class footMark{
	public $footMark;
	function __construct(){
		$data = $this->getCsv();
		$this->footMark = $data;
	}

	//csvからデータ取得
	function getCsv(){
		global $PathArticles,$artDir,$kyotenId,$GlobalMaster;

		$File = $PathArticles.'contents_x/csv/csv_foot_bn2016.csv';
		$dataAry = $this->ReadCsv($File);

		return $dataAry;
	}

		//csvファイルの読み込み
	function ReadCsv($File){

		$handle = fopen($File, "r");
		if ($handle){
			$num = 0;
			while (!feof($handle)) {
				$buffer = rtrim(fgets($handle, 9999));	//日本語ファイルはfgetcsv使うのやめておく
				$buffer = str_replace('"', '', $buffer);	//ダブルクォーテーション不要
				//空白行はサヨナラ
				if(empty($buffer)){
					continue;
				}
				//1行目も日本語名なのでいらない
				if($num == 1){
					++$num;
					continue;
				}

				$data = explode("\t", $buffer);
				if($num == 0){
					$keyAry = array();
					foreach($data as $no => $val){
						if(empty($val)){
							continue;
						}
						//web課と拠点でタイトルが違うので統一する
						if($val == 'q_banner_title'){
							$val = 'q_title';
						}
						$keyAry[$no] = $val;
					}
					++$num;
				}
				else{
					foreach($keyAry as $no => $key){
						$csvdata[$key] = $data[$no];
					}
					$CsvAry[]=$csvdata;
				}
			}
			fclose($handle);
		}
		return $CsvAry;
	}
}

/**************************************
*  関連リンク
*全拠点共通にしてある。拠点で出し分けるならコンストラクタのコメントアウト取ってその下の$bnWeb=$data;削除
***************************************/

class cmsToHtmlKanrenLinks{

	function __construct($temp){
		global $PathArticles,$AttendingPath,$PathSharing16;

		$File = '/var/www/html/cms/articles/contents_x/csv/csv_kanren_link2016.csv';
		$dataAry=$this->ReadCsv($File);

		$this->temp = $PathSharing16.'/inc/'.$temp;

		$this->makeHtml($dataAry);

		include_once($this->temp);
	}
	//csvファイルの読み込み
	function ReadCsv($File){

		$handle = fopen($File, "r");
		if ($handle){
			$num = 0;
			while (!feof($handle)) {
				$buffer = rtrim(fgets($handle, 9999));	//日本語ファイルはfgetcsv使うのやめておく
				$buffer = str_replace('"', '', $buffer);	//ダブルクォーテーション不要
				//空白行はサヨナラ
				if(empty($buffer)){
					continue;
				}
				//1行目も日本語名なのでいらない
				if($num == 1){
					++$num;
					continue;
				}

				$data = explode("\t", $buffer);
				if($num == 0){
					$keyAry = array();
					foreach($data as $no => $val){
						if(empty($val)){
							continue;
						}
						//web課と拠点でタイトルが違うので統一する
						if($val == 'q_banner_title'){
							$val = 'q_title';
						}
						$keyAry[$no] = $val;
					}
					++$num;
				}
				else{
					foreach($keyAry as $no => $key){
						$csvdata[$key] = $data[$no];
					}
					$CsvAry[]=$csvdata;
				}
			}
			fclose($handle);
		}
		return $CsvAry;
	}
	function makeHtml($dataAry){
		$this->kanrenLinkHtml="";
		$this->categoryLinkHtml="";
		foreach($dataAry as $data){
			if($data['q_category'] == '関連リンク'){

				$data['p_img1_filepath'] = str_replace(array("http://x.hankyu-travel.com/cms_photo_image/","http://x.hankyu-travel.com/photo_db/"), "//x.hankyu-travel.com/cms_photo_image/", $data['p_img1_filepath']);
				$this->kanrenLinkHtml .= <<<EOD
				<a href="{$data['tour_url']}">
					<ul>
						<li class="bn"><img src="{$data['p_img1_filepath']}" alt="{$data['p_img1_caption']}" width="135px"></li>
						<li class="tx">{$data['q_title']}</li>
					</ul>
				</a>
EOD;

			}elseif($data['q_category'] == 'カテゴリーリンク'){

				$this->categoryLinkHtml .= <<<EOD
				<li class="bn"><a href="{$data['tour_url']}">{$data['q_title']}</a></li>
EOD;

			}
		}
	}
}

/**************************************
* 担当者おすすめ拠点用
***************************************/
/*
class cmsToHtmlOsusume{
	function __construct($csv='',$temp) {
		global $GlobalTourList,$SettingData,$AttendingPath;

		if(!empty($csv)){
			$this->art = $csv;
		}
		$this->temp = $AttendingPath.'/inc/'.$temp;
		if($this->art){
			$this->getCms();
		}
		$this->makeHtml();
	}

	function makeHtml(){
		global $kyotenId,$GlobalMaster;

		if(!empty($this->cmsdata)){
			$tourAry = $this->cmsdata;
		}
		//print_r($tourAry);
		if(is_array($tourAry)){
			$tourNum='';
			$html='';
			$big='';
			$bicCnt = 0;
			$middle='';
			$small='';
			$smallAllcount='';
			//大バナーがあるか先に確認

			foreach($tourAry as $key => $GlobalTourData){
				if(empty($GlobalTourData['tour_url']) && empty($GlobalTourData['p_img1_filepath'])){
					continue;
				}
				$GlobalTourData['p_img1_filepath'] = str_replace(array("http://x.hankyu-travel.com/cms_photo_image/","http://x.hankyu-travel.com/photo_db/"), "//x.hankyu-travel.com/cms_photo_image/", $GlobalTourData['p_img1_filepath']);

				if($GlobalTourData['q_group'] == '担当者オススメ上部'){
					if($bicCnt==3){
						continue;
					}
					if($bicCnt < 2){

						$big.=<<<EOD
<div class="idx_box15 js_HeightAlignChild OnFL">
<a href="{$GlobalTourData['tour_url']}">
<dl>
<dd class="pht"><img src="{$GlobalTourData['p_img1_filepath']}" alt="{$GlobalTourData['p_img1_caption']}" /></dd>
<dt>{$GlobalTourData['p_course_name']}</dt>
<dd class="price">{$GlobalTourData['price_min_max']}</dd>
<dd class="txt">{$GlobalTourData['p_point1']}</dd>
</dl>
</a>
</div>
EOD;
						$bicCnt++;
					}
					else{
						$big.=<<<EOD
<div class="idx_box16 js_HeightAlignChild OnFL">
<a href="{$GlobalTourData['tour_url']}">
<dl>
<dd class="pht"><img src="{$GlobalTourData['p_img1_filepath']}" alt="{$GlobalTourData['p_img1_caption']}" /></dd>
<dt>{$GlobalTourData['p_course_name']}</dt>
<dd class="price">{$GlobalTourData['price_min_max']}</dd>
<dd class="txt">{$GlobalTourData['p_point1']}</dd>
</dl>
</a>
</div>
EOD;
$bicCnt++;
					}
				}

			}

			//小バナー本数無制限
			$smallcount='';

			foreach($tourAry as $key => $GlobalTourData){
				if(empty($GlobalTourData['tour_url']) && empty($GlobalTourData['p_img1_filepath'])){
					continue;
				}
				if($GlobalTourData['q_group'] == '担当者オススメ下部'){
					if(strpos($GlobalTourData['price_min_max'],'受付終了') !== false || $GlobalTourData['price_min_max'] == 0 || $GlobalTourData['price_min_max'] == ''){
					//URLがDSかどうか判定
					if($GlobalTourData['url_type'] != 'noDS'){
						//料金なしはとばす
						continue;
						//$GlobalTourData['price_min_max'] = '受付終了';
					}
				}
				if($smallcount==4){
					$smallcount='';
				}
				if($smallcount==''){
					$small .='<div class="recommended_more FClear">';
				}

				$GlobalTourData['p_img1_filepath'] = str_replace(array("http://x.hankyu-travel.com/cms_photo_image/","http://x.hankyu-travel.com/photo_db/"), "//x.hankyu-travel.com/cms_photo_image/", $GlobalTourData['p_img1_filepath']);

				if($smallcount<3){
				$small .=<<<EOD
<div class="idx_box17 OnFL">
<a href="{$GlobalTourData['tour_url']}">
<dl>
<dd class="pht"><img src="{$GlobalTourData['p_img1_filepath']}" alt="{$GlobalTourData['p_img1_caption']}" /></dd>
<dt>{$GlobalTourData['p_course_name']}</dt>
<dd class="price">{$GlobalTourData['price_min_max']}</dd>
<dd class="txt">{$GlobalTourData['p_point_all']}</dd>
</dl>
</a>
</div>
EOD;
				}else{
				$small .=<<<EOD
<div class="idx_box18 OnFL">
<a href="{$GlobalTourData['tour_url']}">
<dl>
<dd class="pht"><img src="{$GlobalTourData['p_img1_filepath']}" alt="{$GlobalTourData['p_img1_caption']}" /></dd>
<dt>{$GlobalTourData['p_course_name']}</dt>
<dd class="price">{$GlobalTourData['price_min_max']}</dd>
<dd class="txt">{$GlobalTourData['p_point_all']}</dd>
</dl>
</a>
</div>
EOD;

				}


				$smallcount++;
				$smallAllcount++;
				if($smallcount ==4 ){
					$small .='</div>';
				}
				}
			}
			if($smallAllcount % 4 != 0){
				$small .='</div>';
			}
			if(!empty($big)){
				$html.=<<<EOD
<div class="recommended_more js_HeightAlign bdr_btm01 mb20 pb20">
{$big}
</div>
EOD;
			}
			if(!empty($small)){
$html.=<<<EOD
<div id="osusumeBox">
{$small}
</div>
EOD;
			}
			include_once($this->temp);
		}
	}


	//cmsからデータ取得
	function getCms(){
		global $GlobalTourList,$SettingData;

		//記事タイトル取得
		$set_art_title = $SettingData->ArticleConfig["$this->art"]['article_title'];
		$this->cmsdata='';
		if(!is_array($GlobalTourList["$this->art"]["$set_art_title"])){
			return false;
		}
		else{
			//海外ツアーリスト
			foreach($GlobalTourList["$this->art"]["$set_art_title"] as $data){}
			$this->cmsdata = $GlobalTourList["$this->art"]["$set_art_title"];
		}
	}

}
*/

/*******************************************************
 * 新聞掲載
 *
 ******************************************************/

class Shinbunkeisai{
	//$naigai:allまたはiまたはd　
	function __construct($naigai,$kyotenid=''){
		global $IncPath,$GlobalMaster,$kyotenId,$path16;

		//echo '<pre>';
//print_r($GlobalMaster['kyotenUse']);
//echo '</pre>';
		$url_i='';
		$url_d='';
		$Link='';

		$this->naigai=$naigai;

		if(!empty($kyotenid)){
			$kyoten = $kyotenid;
		}
		else{
			if($kyotenId =='index'){
				$kyoten = 'top';
			}
			else{
				$kyoten = $kyotenId;
			}
		}

		if($kyoten !='top'){
			if(empty($GlobalMaster['kyotenUse'])){
				new GM_kyotenUse;
			}

			if($this->naigai == 'all'){
				foreach($GlobalMaster['kyotenUse'] as $kyotenDataAry){
					if($kyotenDataAry['naigai'] == 'i' && $kyotenDataAry['kyotenId'] == $kyoten){
						if(!empty($kyotenDataAry['newspaperURL'])){
							$url_i = $kyotenDataAry['newspaperURL'];
						}
					}
					elseif($kyotenDataAry['naigai'] == 'd' && $kyotenDataAry['kyotenId'] == $kyoten){
						if(!empty($kyotenDataAry['newspaperURL'])){
							$url_d = $kyotenDataAry['newspaperURL'];
						}
					}
				}

			}
			elseif($this->naigai == 'i'){
				foreach($GlobalMaster['kyotenUse'] as $kyotenDataAry){
					if($kyotenDataAry['naigai'] == $this->naigai && $kyotenDataAry['kyotenId'] == $kyoten){
						if(!empty($kyotenDataAry['newspaperURL'])){
							$url_i = $kyotenDataAry['newspaperURL'];
							//$this->getTxt($kyoten,$this->naigai);
						}
					}
				}
			}
			elseif($this->naigai == 'd'){
				foreach($GlobalMaster['kyotenUse'] as $kyotenDataAry){
					if($kyotenDataAry['naigai'] == $this->naigai && $kyotenDataAry['kyotenId'] == $kyoten){
						if(!empty($kyotenDataAry['newspaperURL'])){
							$url_d = $kyotenDataAry['newspaperURL'];
							//$this->getTxt($kyoten,$this->naigai);
						}
					}
				}
			}
		}

		if($this->naigai == 'i' && $kyoten == 'top'){
			$this->Link =<<<EOD
<p class="btn_i"><a href="{$path16->HttpTop}/newspaper_ad/">一覧を見る</a></p>
EOD;
		}
		elseif($this->naigai == 'i' && $kyoten != 'top' && !empty($url_i)){
			$this->Link =<<<EOD
<p class="btn_i"><a href="{$path16->HttpTop}{$url_i}">一覧を見る</a></p>
EOD;
		}
	}
}

/*******************************************************
 * 出発確定ツアー
 *
 ******************************************************/

class Saikoukakutei{
	//$naigai:allまたはiまたはd　
	function __construct($naigai,$kyotenid=''){
		global $IncPath,$GlobalMaster,$kyotenId,$path16;

		$url_i='';
		$url_d='';
		$Link='';

		if(!empty($temp)){
			$this->myTemplate = $IncPath . $temp;
		}
		else{
			$this->myTemplate = $IncPath . get_class($this) . '.php';
		}
		if(!empty($kyotenid)){
			$kyoten = $kyotenid;
		}
		else{
			if($kyotenId =='index'){
				$kyoten = 'top';
			}
			else{
				$kyoten = $kyotenId;
			}
		}
		if($kyoten !='top'){
			if(empty($GlobalMaster['kyotenUse'])){
				new GM_kyotenUse;

			}
			if($naigai == 'all'){
				foreach($GlobalMaster['kyotenUse'] as $kyotenDataAry){
					if($kyotenDataAry['naigai'] == 'i' && $kyotenDataAry['kyotenId'] == $kyoten){
						if(!empty($kyotenDataAry['saikoukakuteiURL'])){
							$url_i = $kyotenDataAry['saikoukakuteiURL'];
						}
					}
					elseif($kyotenDataAry['naigai'] == 'd' && $kyotenDataAry['kyotenId'] == $kyoten){
						if(!empty($kyotenDataAry['saikoukakuteiURL'])){
							$url_d = $kyotenDataAry['saikoukakuteiURL'];
						}
					}
				}

			}
			elseif($naigai == 'i'){
				foreach($GlobalMaster['kyotenUse'] as $kyotenDataAry){
					if($kyotenDataAry['naigai'] == $naigai && $kyotenDataAry['kyotenId'] == $kyoten){
						if(!empty($kyotenDataAry['saikoukakuteiURL'])){
							$url_i = $kyotenDataAry['saikoukakuteiURL'];
						}
					}
				}
			}
			elseif($naigai == 'd'){
				foreach($GlobalMaster['kyotenUse'] as $kyotenDataAry){
					if($kyotenDataAry['naigai'] == $naigai && $kyotenDataAry['kyotenId'] == $kyoten){
						if(!empty($kyotenDataAry['saikoukakuteiURL'])){
							$url_d = $kyotenDataAry['saikoukakuteiURL'];
						}
					}
				}
			}
		}

		if($naigai == 'i' && $kyoten == 'top'){
			$this->Link =<<<EOD
<p class="btn_i"><a href="{$path16->HttpTop}/saikou_kakutei/">一覧を見る</a></p>
EOD;
		}
		elseif($naigai == 'i' && $kyoten != 'top'&& !empty($url_i)){
			$this->Link =<<<EOD
<p class="btn_i"><a href="{$path16->HttpTop}{$url_i}">一覧を見る</a></p>
EOD;
		}
	}

}


class cmsToHtmlKeyword{
	function __construct($csv='',$tour_max,$temp) {
		global $GlobalTourList,$SettingData,$AttendingPath;
		if(!empty($csv)){
			$this->csv = $csv;
		}
		$this->temp = $AttendingPath.'/inc/'.$temp;
		if(!$tour_max){
			$this->tour_max = 30;
		}

		if($this->csv){
			$this->getCms();
			$this->makeHtml($this->tour_max);
		}
        //print_r($this->html);
		if($this->html){
			include_once($this->temp);
		}

	}

	function makeHtml($tour_max){

		global $kyotenId,$GlobalMaster,$KyotenName;
		if(!empty($this->csvdata)){
			$dataAry = $this->csvdata;
		}
		//$tourNum=0;
		$imgNo ='';
		$imgALT ='';

		if(is_array($dataAry)){

			$num=0;
			$html='';
			$classBgCount = 0;

			foreach($dataAry as $key => $GlobalTourData){
				if(empty($GlobalTourData['tour_url'])){
					continue;
				}
				//最大表示数を超えたら終わり
				if($num>$tour_max){
					break;
				}

				if($kyotenId !='index'){//除外拠点

					if(!empty($GlobalTourData['q_nodisp'])){
						 if(strpos($GlobalTourData['q_nodisp'],$kyotenId)!==false){
							continue;
						}
					}
				}


				//クラスの振り分け
				$classCount = $num+1;
				if($classCount % 3 == 1 ){
					$classBgCount++;
					if($classBgCount % 3 == 0){
						$html .=<<< EOD
						<a href="{$GlobalTourData['tour_url']}" class="bg">{$GlobalTourData['q_title']}</a>
EOD;
					} else {
					$html .=<<< EOD
					<a href="{$GlobalTourData['tour_url']}" class="md">{$GlobalTourData['q_title']}</a>
EOD;
					}
				} else {
					$html .=<<< EOD
					<a href="{$GlobalTourData['tour_url']}">{$GlobalTourData['q_title']}</a>
EOD;
				}


				$num++;
			}

			$this->html = $html;

		}
	}

	//cmsからデータ取得
	function getCms(){
		global $GlobalTourList,$SettingData;

		//記事タイトル取得
		$set_art_title = $SettingData->ArticleConfig["$this->csv"]['article_title'];
		if(!is_array($GlobalTourList["$this->csv"]["$set_art_title"])){
			$this->csvdata='';
		}
		else{
			//海外ツアーリスト
			$this->csvdata = $GlobalTourList["$this->csv"]["$set_art_title"];
		}
// 			echo '<pre>';
// print_r($this->csvdata);
// echo '</pre>';
	}

}

/*******************************************************
 * 説明・新聞・催行テキスト
 *
 ******************************************************/

class NewsSaikouSetsumeiTxt{

	public $setsumei;
	public $news;
	public $saikou;

	function __construct($naigai){
		$this->getTxt($naigai);
	}

	//csvからデータ取得
	function getTxt($naigai){
		global $PathArticles,$artDir,$kyotenId,$GlobalMaster;

		$FileX = $PathArticles.'contents_x/csv/csv_NewsSaikouSetsumei2016.csv';
		$XdataAry = $this->ReadCsv($FileX);

		foreach($XdataAry as $Xdata){

			if($Xdata['q_category'] == '説明会'){
				$this->setsumei = $Xdata['q_text'];
			}
			if($Xdata['q_category'] == '催行確定'){
				$this->saikou = $Xdata['q_text'];
			}
			if($Xdata['q_category'] == '新聞掲載'){
				$this->news = $Xdata['q_text'];
			}
		}
		if($naigai != 'all' && $kyotenId != 'index'){
			$File = $PathArticles.$kyotenId.'_'.$naigai.'/csv/csv_setsumeikai_txt2016.csv';
			$dataAry = $this->ReadCsv($File);
			foreach($dataAry as $data){
				if($data['q_category'] == '説明会'){
					if(!empty($data['q_text'])){
						$this->setsumei =  $data['q_text'];
					}
				}
			}
		}

	}

	//csvファイルの読み込み
	function ReadCsv($File){

		$handle = fopen($File, "r");
		if ($handle){
			$num = 0;
			while (!feof($handle)) {
				$buffer = rtrim(fgets($handle, 9999));	//日本語ファイルはfgetcsv使うのやめておく
				$buffer = str_replace('"', '', $buffer);	//ダブルクォーテーション不要
				//空白行はサヨナラ
				if(empty($buffer)){
					continue;
				}
				//1行目も日本語名なのでいらない
				if($num == 1){
					++$num;
					continue;
				}

				$data = explode("\t", $buffer);
				if($num == 0){
					$keyAry = array();
					foreach($data as $no => $val){
						if(empty($val)){
							continue;
						}

						$keyAry[$no] = $val;
					}
					++$num;
				}
				else{
					foreach($keyAry as $no => $key){
						$csvdata[$key] = $data[$no];
					}
					$CsvAry[]=$csvdata;
				}
			}
			fclose($handle);
		}
		return $CsvAry;
	}
}


/*******************************************************
 * 旅の情報
 *
 ******************************************************/
//csvファイルの読み込み

class cmsToHtmlTabiInfo{

	function __construct($naigai,$num='',$temp){

		global $GlobalMaster,$AttendingPath;

		$this->temp = $AttendingPath.'/inc/'.$temp;


		$File = '/var/www/html/cms/articles/contents_x/csv/csv_tabi_guide2016.csv';

		$CsvAry=$this->ReadCsv($File);

		$this->makeAry($CsvAry);
// echo '<pre>';
// print_r($dataAry);
// echo '</pre>';

    	include_once($this->temp);

	}

	function ReadCsv($File){

		$handle = fopen($File, "r");
		if ($handle){
			$num = 0;
			while (!feof($handle)) {
				$buffer = rtrim(fgets($handle, 9999));	//日本語ファイルはfgetcsv使うのやめておく
				$buffer = str_replace('"', '', $buffer);	//ダブルクォーテーション不要
				//空白行はサヨナラ
				if(empty($buffer)){
					continue;
				}
				//1行目も日本語名なのでいらない
				if($num == 1){
					++$num;
					continue;
				}

				$data = explode("\t", $buffer);
				if($num == 0){
					$keyAry = array();
					foreach($data as $no => $val){
						if(empty($val)){
							continue;
						}
						//web課と拠点でタイトルが違うので統一する
						if($val == 'q_banner_title'){
							$val = 'q_title';
						}
						$keyAry[$no] = $val;
					}
					++$num;
				}
				else{
					foreach($keyAry as $no => $key){
						$csvdata[$key] = $data[$no];
					}
					$CsvAry[]=$csvdata;
				}
			}
			fclose($handle);
		}
		return $CsvAry;
	}

	function makeAry($CsvAry){
		$this->html="";
		foreach ($CsvAry as $data){

			$data['p_img1_filepath'] = str_replace(array("http://x.hankyu-travel.com/cms_photo_image/","http://x.hankyu-travel.com/photo_db/"), "//x.hankyu-travel.com/cms_photo_image/",$data['p_img1_filepath']);

			if($data['q_category'] === '海外ガイド'){
				$this->html['i'][] =<<<EOD
				<div class="idx_box08 mb30 OnFL">
				<dl class="guide_i">
				<dd class="pht"><img src="{$data['p_img1_filepath']}" alt="{$data['p_img1_caption']}" /></dd>
				<dt><a href="{$data['q_url']}">{$data['q_title']}</a></dt>
				<dd class="txt">{$data['q_text']}</dd>
				</dl>
				</div>
EOD;
			} else if ($data['q_category'] === '国内ガイド'){
				$this->html['d'][] =<<<EOD
				<dd class="pht"><img src="{$data['p_img1_filepath']}" alt="{$data['p_img1_caption']}" /></dd>
				<dt><a href="{$data['q_url']}">{$data['q_title']}</a></dt>
				<dd class="txt">{$data['q_text']}</dd>
EOD
;
			}
		}
	}
}



/**************************************
*  snsバナー
*全拠点共通にしてある。拠点で出し分けるならコンストラクタのコメントアウト取ってその下の$bnWeb=$data;削除
***************************************/
class cmsToHtmlSnSBnTop{

	function __construct(){
		global $PathArticles,$artDir,$kyotenId,$GlobalMaster;

		$File = '/var/www/html/cms/articles/contents_x/csv/csv_sns_bn2016.csv';
		$dataAry=$this->ReadCsv($File);
		$this->makeHtml($dataAry);

	}
	//csvファイルの読み込み
	function ReadCsv($File){

		$handle = fopen($File, "r");
		if ($handle){
			$num = 0;
			while (!feof($handle)) {
				$buffer = rtrim(fgets($handle, 9999));	//日本語ファイルはfgetcsv使うのやめておく
				$buffer = str_replace('"', '', $buffer);	//ダブルクォーテーション不要
				//空白行はサヨナラ
				if(empty($buffer)){
					continue;
				}
				//1行目も日本語名なのでいらない
				if($num == 1){
					++$num;
					continue;
				}

				$data = explode("\t", $buffer);
				if($num == 0){
					$keyAry = array();
					foreach($data as $no => $val){
						if(empty($val)){
							continue;
						}
						$keyAry[$no] = $val;
					}
					++$num;
				}
				else{
					foreach($keyAry as $no => $key){
						$csvdata[$key] = $data[$no];
					}
					$CsvAry[]=$csvdata;
				}
			}
			fclose($handle);
		}
		return $CsvAry;
	}
	function makeHtml($Array){
		$html='';
		if(is_array($Array)){
			foreach($Array as $bnArray){
			    if ($bnArray['q_category'] == 'SNSバナーsmp') {
			$bnArray['p_img1_filepath'] = str_replace(array("http://x.hankyu-travel.com/cms_photo_image/","http://x.hankyu-travel.com/photo_db/"), "//x.hankyu-travel.com/cms_photo_image/",$bnArray['p_img1_filepath']);
			$html .= <<<EOD
			<p class="banner"><a href="{$bnArray['q_url']}"><img src="{$bnArray['p_img1_filepath']}" alt"{$bnArray['p_img1_caption']}"></a></p>
EOD;
                }
            }
			echo $html;

		}

	}

}

/**************************************
*  メルマガバナー
*全拠点共通にしてある。拠点で出し分けるならコンストラクタのコメントアウト取ってその下の$bnWeb=$data;削除
***************************************/
class cmsToHtmlMailBnTop{

	function __construct(){
		global $PathArticles,$artDir,$kyotenId,$GlobalMaster;

		$File = '/var/www/html/cms/articles/contents_x/csv/csv_mailbn_2016.csv';
		$dataAry=$this->ReadCsv($File);
		$this->makeHtml($dataAry);

	}
	//csvファイルの読み込み
	function ReadCsv($File){
		if (!file_exists($File)) {
			return array();
		}

		$handle = fopen($File, "r");
		if ($handle){
			$num = 0;
			while (!feof($handle)) {
				$buffer = rtrim(fgets($handle, 9999));	//日本語ファイルはfgetcsv使うのやめておく
				$buffer = str_replace('"', '', $buffer);	//ダブルクォーテーション不要
				//空白行はサヨナラ
				if(empty($buffer)){
					continue;
				}
				//1行目も日本語名なのでいらない
				if($num == 1){
					++$num;
					continue;
				}

				$data = explode("\t", $buffer);
				if($num == 0){
					$keyAry = array();
					foreach($data as $no => $val){
						if(empty($val)){
							continue;
						}
						$keyAry[$no] = $val;
					}
					++$num;
				}
				else{
					foreach($keyAry as $no => $key){
						$csvdata[$key] = isset($data[$no]) ? $data[$no] : '';
					}
					$CsvAry[]=$csvdata;
				}
			}
			fclose($handle);
		}
		return $CsvAry;
	}
	function makeHtml($Array){
		$html='';
		if(is_array($Array)){
			foreach($Array as $bnArray){
			$bnArray['p_img1_filepath'] = str_replace(array("http://x.hankyu-travel.com/cms_photo_image/","http://x.hankyu-travel.com/photo_db/"), "//x.hankyu-travel.com/cms_photo_image/",$bnArray['p_img1_filepath']);
			$html .= <<<EOD
			<dt><a href="{$bnArray['q_url']}"><img src="{$bnArray['p_img1_filepath']}" alt="{$bnArray['p_img1_caption']}" /></a></dt>
			<dd><a href="{$bnArray['q_url']}">{$bnArray['p_catch']}</a></dd>
EOD;
}
			echo $html;

		}

	}

}

$top = new top2016;

class top2016{


	public $theme;
	public $realUrlForPC;

	function __construct(){

		//テーマ
		$this->SetcatchtextTheme();
		//pcへボタンurl
		$this->pcLink();

	}

	function pcLink(){
		global $hp;

		if(isset($_COOKIE["HANKYUBAITAI"]) && $_COOKIE["HANKYUBAITAI"]!="9700" && !empty($_COOKIE["HANKYUBAITAI"])){
			$baitai = '&p_baitai='. $_COOKIE["HANKYUBAITAI"];
		}
		else{
			$baitai='';
		}
		$this->realUrlForPC = $hp.$_SERVER['SERVER_NAME'].$_SERVER['SCRIPT_NAME'].'?vpc=1';

	}

	function SetcatchtextTheme(){
		global $PathArticles;

		$file = $PathArticles . 'contents_x/csv/csv_headerTheme2016.csv';
		$li ='';
		$rtn ='';

		if (file_exists($file)){
			$ary = $this->ReadCsv($file);
			if(is_array($ary)){

				$count = 0;
				foreach($ary as $data){

					if($data['q_category']=='テーマ' && !empty($data['p_course_name'])){
						if($count == 2){
							break;
						}
						$tour_url = trim($data['tour_url']);
						$p_course_name = trim($data['p_course_name']);
						$q_catch = trim($data['q_catch']);

						$this->theme .=<<<EOD
<li><a href="{$tour_url}"><span>{$p_course_name}</span></a></li>
EOD;
						$count++;

					}
				}
			}
		}
	}

	function ReadCsv($File){

		$handle = fopen($File, "r");
		if ($handle){
			$num = 0;
			while (!feof($handle)) {
				$buffer = rtrim(fgets($handle, 9999));
				$buffer = str_replace('"', '', $buffer);
				if(empty($buffer)){
					continue;
				}
				if($num == 1){
					++$num;
					continue;
				}
				$data = explode("\t", $buffer);
				if($num == 0){
					$keyAry = array();
					foreach($data as $no => $val){
						if(empty($val)){
							continue;
						}

						$keyAry[$no] = $val;
					}
					++$num;
				}
				else{
					foreach($keyAry as $no => $key){
						$csvdata[$key] = $data[$no];
					}
					$CsvAry[]=$csvdata;
				}
			}
			fclose($handle);
		}
		return $CsvAry;
	}
}

// 現地発着タブの表示判定
function isGenchiHacchaku(){
    global $categoryType,$senmonNameEnLower,$kyotenId;

    // 方面ページなら
    if($categoryType == CATEGORY_TYPE_DEST){
        // 現地発着タブは北海道、東北、関東、関西の時でなおかつ発地が同一でない
        if(($senmonNameEnLower == 'hokkaido' && $kyotenId != 'spk') ||
            ($senmonNameEnLower == 'tohoku' && $kyotenId != 'sdj') ||
            ($senmonNameEnLower == 'kanto' && $kyotenId != 'tyo') ||
            ($senmonNameEnLower == 'kansai'&& $kyotenId != 'osa') ||
            ($senmonNameEnLower == 'kinki'&& $kyotenId != 'osa')){

            return true;
        }
    }
    // 都道府県ページなら
    elseif ($categoryType == CATEGORY_TYPE_COUNTRY) {

        // 現地発着タブは東京、神奈川、大阪、兵庫、京都、愛知、福岡、広島の時でなおかつ発地が同一でない
        if(($senmonNameEnLower == 'aichi' && $kyotenId != 'ngo') ||
            ($senmonNameEnLower == 'fukuoka' && $kyotenId != 'fuk') ||
            ($senmonNameEnLower == 'hiroshima' && $kyotenId != 'hij')){
                return true;
        }
    }

    return false;
}

function isGenchiHacchakuShow(){
    global $categoryType,$senmonNameEnLower;

    // 方面ページなら
    if($categoryType == CATEGORY_TYPE_DEST){
        // 現地発着タブは北海道、東北、関東、関西の時
        if($senmonNameEnLower == 'hokkaido' ||
            $senmonNameEnLower == 'tohoku'||
            $senmonNameEnLower == 'kanto' ||
            $senmonNameEnLower == 'kinki'){

            return true;
        }
    }
    // 都道府県ページなら
    elseif ($categoryType == CATEGORY_TYPE_COUNTRY) {

        // 現地発着タブは愛知、福岡、広島の時
        if(
            $senmonNameEnLower == 'aichi'    ||
            $senmonNameEnLower == 'fukuoka'  ||
            $senmonNameEnLower == 'hiroshima' ){

                return true;
        }
    }

    return false;
}


?>
