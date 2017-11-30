<?php
########################################################################
#
#  スマホ海外
#
#  @copyright  2016 BUD International
#  @version    1.0.0
########################################################################
//正しいheaderを出力しないと、IEでエラーになる
header("Content-Type: text/html; charset=UTF-8");
ini_set('display_errors', 0);
/********
* include
*********/

include_once($_SERVER['DOCUMENT_ROOT'] .'/sharing/phpsc/path.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/sharing/phpsc/read_master.php');
include_once($SharingPSPath . 'read_setting.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/sharing/phpsc/func.php');
include_once($_SERVER['DOCUMENT_ROOT'] .'/sharing/phpsc/CoreSolrAccess.php');



if($_REQUEST['reqParam'] && $_REQUEST['kyotenid'] ){

	$id = str_replace('tour','',$_REQUEST['reqParam']);
	$kyotenId = str_replace('js_','',$_REQUEST['kyotenid']);

	$p_hatsuAryI = new HierarchyMagoKyotenTabCode_p_hatsu;
	foreach($p_hatsuAryI->TgDataAry['i'] as $key => $arr){
		foreach($arr as $kyotenCode => $val){
			$p_hatsuAryI->TgDataAry['i'][$key][$kyotenCode] = '　'.$val;
		}
	}
	$p_hatsuI = bindingHatsuAry($p_hatsuAryI->TgDataAry['i'][$kyotenId]);



	$mapReq['p_hatsu'] = $p_hatsuI;
	$mapReq['p_data_kind'] = '1';
	$mapFacetAry = new mapFacet;
	$mapFacetAry->ajaxDisp($mapReq,$id);

	echo $mapFacetAry->html;
}



//目的地から探す
class mapFacet{
	public $f;
	function __construct() {
		$this->makeMasterCsv();

	}

	function defDisp($req){
		foreach($this->senmonAry['homen'] as $id => $ary){
			foreach($ary as  $data){
				$req['p_mokuteki'] = $data['req'];
				$req['p_rtn_data'] = 'p_conductor';

				$SolrObj = new CoreSolrAccess($req,'i');
				$this->f[$id] = $SolrObj->Obj->response->p_hit_num;
			}

		}
	}

	function ajaxDisp($req,$destid){

		$dd='';
		$ninkiHtml='';
		foreach($this->senmonAry['country'][$destid] as  $data){

			$req['p_mokuteki'] = $data['req'];
			$req['p_rtn_data'] = 'p_conductor';

			$SolrObj = new CoreSolrAccess($req,'i');

			$this->f[$id] = $SolrObj->Obj->response->p_hit_num;
			if($SolrObj->Obj->response->p_hit_num!=0){

				$dd .=<<<EOD
<dd><a href="javascript:void(0)" onclick="mapSubmitLink(event);return false;" data-name="{$data['country']}">{$data['senmon_name']}<span>[{$SolrObj->Obj->response->p_hit_num}]</span></a></dd>
EOD;
			}
			else{
				$dd .=<<<EOD
<dd class="ddNoFacet">{$data['senmon_name']}<span>[0]</span></dd>
EOD;
			}
		}

		$kankou = new cmsToHtmlKankouchi($this->senmonAry['csv'][$destid]['senmon_name'],$req['p_hatsu']);
		if(!empty($kankou->html)){
$ninkiHtml= <<<EOD
<dt class="modalIcon">人気の都市・観光地</dt>
{$kankou->html}
EOD;
		}

		$this->html =<<<EOD
<div class="GlMenuClose">
<a href="javascript:void(0)" onclick="js_GlMapClose();return false;">閉じる</a>
</div>
<dl>
<dt>ご希望の目的地を選択してください</dt>
{$dd}
{$ninkiHtml}
</dl>
<div class="GlMenuClose clsBtm">
<a href="javascript:void(0)" onclick="js_GlMapClose();return false;">閉じる</a>
</div>
EOD;
unset($this->senmonAry);
	}

	function makeMasterCsv(){
		$handle = fopen('/var/www/html/hankyu-travel.com/sharing/master/master_senmon.csv', "r");
		if ($handle) {
			while (!feof($handle)) {
				$buffer = rtrim(fgets($handle, 9999));
				$buffer = str_replace('"', '', $buffer);
				//空白行はサヨナラ
				if(empty($buffer)){
					continue;
				}
				$this->readMyAction($buffer);

			}
			fclose($handle);

		}

	}
	function readMyAction($buffer){

		$data = explode("\t", $buffer);

		$dest_code = $data[9];
		$country_code = $data[10];
		$city_code = $data[11];

		$tmpmokuteki = $dest_code . '-' . $country_code . '-' . $city_code;
		if(empty($dest_code) && empty($country_code) && empty($city_code)){
			//全てNull
			$tmpmokuteki = '';
		}

		$mokutekiAry = explode('-',$tmpmokuteki);
		foreach($mokutekiAry as $no => $para){

			if($no == 0 && !empty($para)){
			//方面

				$homenArySenmon = explode(',',$para);
				//方面が複数(国内の中部北陸だけでしょうbreak!
				if(count($homenArySenmon) > 1){
					foreach($homenArySenmon as $homen){
						if(empty($mokuteki)){
							$mokuteki = $homen . '--';
						}
						else{
							$mokuteki .= ',' . $homen . '--';
						}
					}
					break;
				}else{
					$homen_no = $para;
				}
			}elseif($no == 1){
			//国
				if(empty($para)){
					//国が空
					if(empty($mokuteki)){
						$mokuteki = $homen_no . '--';
						}
					else{
						$mokuteki = ',' .$homen_no . '--';
					}
					continue;
				}
				$countryAry = explode(',',$para);
				//パラメータが複数あるものはきっとこれで終わり
				if(count($countryAry) > 1){
					foreach($countryAry as $country){
						if(empty($mokuteki)){
							$mokuteki = $homen_no . '-' . $country . '-';
						}
						else{
							$mokuteki .= ',' . $homen_no . '-' . $country . '-';
						}
					}
					break;
				}
				else{
					//単体なのでまだあるかも
					$country_no = $para;
				}
			}elseif($no == 2){
			//都市

				if(empty($para)){
				//都市が空なら終了〜
					$mokuteki = $homen_no . '-' . $country_no . '-';
					break;
				}

				//パラメータが複数あるものはきっとこれで終わり
				$cityAry = explode(',',$para);
				if(count($cityAry) > 1){
					foreach($cityAry as $city){
						if(empty($mokuteki)){
							$mokuteki = $homen_no . '-' . $country_no . '-' . $city;
						}
						else{
							$mokuteki .= ',' . $homen_no . '-' . $country_no . '-' . $city;
						}
					}
				}else{
					//単体でおわり！
					$mokuteki = $homen_no . '-' . $country_no . '-' . $para;
				}
			}
		}

		//URLなし、専門店名なし、フェーズ２フラグあり＝除外
		if(!preg_match('/([-_.!~*\'()a-zA-Z0-9;\/?:\@&=+\$,%#]+)$/', $data[7]) || empty($data[6])	|| !empty($data[8])){
			return;
		}
		if(($data[2]=='homen' || $data[2]=='country' || $data[9]=='HWI') && $data[0]=='i'){
			if($data[9]=='FOC' && $data[3]=='s-pacific/'){
				$data[9]='SPC';
			}
			if($data[9]=='HWI' && $data[2]=='city'){
				$data[2]='country';
			}

			$this->senmonAry[$data[2]][$data[9]][$data[7]] = array(
				'homen'					 => $data[3]
				,'country'				 => $data[4]
				,'senmon_name'		 => $data[6]
				,'city_code'			 => $data[11]
				,'req'			 => $mokuteki
			);
			if($data[1]=='A' && $data[2]=='homen'){
				$this->senmonAry['csv'][$data[9]]['senmon_name'] = $data[6];
			}
		}


	}


}


/*******************************************************
* 人気の観光地
* cmsToHtmlKankouchi　csvで入力したツアーを表示する
******************************************************/
//@include_once($HbosSystemDir . "special.php");
class cmsToHtmlKankouchi{
	var $dspattern = "/\/tour\/search_d\.php|\/tour\/search_i\.php|\/tour\/detail_d\.php|\/tour\/detail_i\.php|\/tour_d\/|\/tour_i\//";

	function __construct($q_category,$MyHatsu){
		global $kyotenId,$GlobalMaster;

		$this->MyNaigai='i';
		$this->MyHatsu = $MyHatsu;
		$this->senmonName = $q_category;

		$this->getCsv();
		if(!empty($this->tourList)){

			if($kyotenId!='index'){
				$this->actSoap();
				$this->makeHtml();
			}
			else{
				$this->html='';
			}

		}
		else{
			$this->html='';
		}
	}

	function makeHtml(){


		$dataAry = $this->dataReduction();
		$tourAry =$dataAry['artKankou'][$this->senmonName];
		if(!is_array($tourAry)){
			$this->html='';
			return false;
		}
		$li='';

		foreach($tourAry as $ttl  =>$GlobalTourData){

			if(strpos($GlobalTourData['price_min_max'],'受付終了') !== false || $GlobalTourData['price_min_max'] == 0 || $GlobalTourData['price_min_max'] == ''){
				//URLがDSかどうか判定
				if($GlobalTourData['url_type'] != 'noDS'){
					continue;
				}
			}

			$li .=<<<EOD
<dd><a href="{$GlobalTourData['tour_url']}">{$GlobalTourData['q_title']}</a></>
EOD;
		$tourNum++;

	}
	if(!empty($li)){
		$this->html=$li;
	}



	}

	//元とSOAPデータの必用なとこだけ合体
	function dataReduction(){
		global $CmsPhotoHttp;
		global $SettingData;


		//取得データから必要なパラメータを入れていく
		//xmlデータと取得データの整理
		$mk = '円';

		//記事データをくるくる
		foreach($this->tourList as $k => $v){

			foreach($v as $k1 => $v1){
				foreach($v1 as $k2 => $v2){
					$KeyUrl = $v2['tour_url'];
					$tourAry[$k][$k1][$k2]['tour_url'] = $v2['tour_url'];
					$tourAry[$k][$k1][$k2]['q_title'] = $v2['q_title'];
					//DSのURLなら金額を入れる
					if(preg_match($this->dspattern,$v2['tour_url'])){

						//金額は最初に入れておく
						$min = $this->soapObj->special_response[$KeyUrl]->p_price_min;
						$max = $this->soapObj->special_response[$KeyUrl]->p_price_max;

						$tourAry[$k][$k1][$k2]['price_min_max'] = $this->dispPrice($min, $max, $mk);
					}
					//DS以外ならフラグ立てておく
					else{
						$tourAry[$k][$k1][$k2]['url_type'] = 'noDS';
					}
				}
			}

		}
		unset($this->soapObj);
		return $tourAry;
	}



	//SOAPアクセス
	function actSoap(){
		global $PathBudDataResSoap;
		global $SpecialWebaccess;
		global $inUrlList;
		global $HbosSystemDir;

		# ************************
		# 特集IFでSOAP情報取得
		# ************************
		//特集IFでURLから各パラメータを取得
		$UrlList='';

		foreach($this->tourList as $kyoten => $kyotenObj){
			foreach($kyotenObj as $key1 => $val1){
				foreach($val1 as $key2 => $val2){
					//DSのURLかどうか
					if(is_numeric($key2) && !empty($val2['tour_url']) && preg_match($this->dspattern,$val2['tour_url'])){
						$UrlList[$val2['tour_url']] = $val2['tour_url'];
					}
				}
			}
		}

		//特集IFをたたく
		if(is_array($UrlList)){
			$inUrlList = $UrlList;
			//SOQP情報取得
			@include_once($HbosSystemDir . "special.php");
			$this->soapObj = new SoapSpecial();
			unset($inUrlList);
		}


	}

	//csvからデータ取得
	function getCsv(){
		global $PathArticles,$naigai,$senmonName,$p_hatsu,$p_hatsuAry;

		$File = $PathArticles.'contents_'.$this->MyNaigai.'/csv/csv_popular_dest.csv';
		$dataAry=$this->ReadCsv($File);

		$cnt = 0;
		$this->tourList='';

		foreach($dataAry as $data){
			if($data['q_category'] == $this->senmonName && $data['q_group'] == '人気の観光地'){
				if(!empty($data['q_title']) && !empty($data['tour_url']) ){
					$obj2 = $data['tour_url'];
					$tmp = $obj2;
					//p　br　&nbsp;をとる
					$tmp = strip_tags($tmp,'<strong>');
					$tmp = str_replace('&nbsp;','',$tmp);
					$tmp = trim($tmp);
					if(empty($tmp)){
						$obj2 = $tmp;
					}
					$this->tourList['artKankou'][$this->senmonName][$cnt]['q_title'] = trim($data['q_title']);

					$urlpara =trim(strval(strip_tags($obj2,'<strong>')));
							//DSのURLかどうか
					if(preg_match($this->dspattern,$urlpara)){

						//発地パラ追加
						if($this->MyNaigai=='d'){
								if(empty($this->MyHatsu)){
								//全発地
								$paradata='';
								foreach($p_hatsuAry->TgDataAry['d']  as $para){
									foreach($para as $code => $name){
										if(empty($paradata)){
											$paradata =$code;
										}
										else{
											$paradata .=','.$code;
										}
									}
								}
								$para='&p_hatsu_sub='.$paradata;
							}
							else{
								//選択発地だけ
								$para ='&p_hatsu_sub='.$this->MyHatsu;
							}
						}
						else{
							if(empty($this->MyHatsu)){
								//全発地
								$paradata='';
								foreach($p_hatsuAry->TgDataAry['i']  as $para){
									foreach($para as $code => $name){
										if(empty($paradata)){
											$paradata =$code;
										}
										else{
											$paradata .=','.$code;
										}
									}
								}

								$para='&p_hatsu='.$paradata;
							}
							else{
								//選択発地だけ
								$para ='&p_hatsu='.$this->MyHatsu;
							}

						}
						$url = $urlpara.$para;
					}
					else{
						$url = $urlpara;
					}

					$this->tourList['artKankou'][$this->senmonName][$cnt]['tour_url'] = $url;
					$cnt++;
				}
			}
		}
		unset($dataAry);
	}

	//csvファイルの読み込み
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

	function dispPrice($min, $max, $str, $NgStr='受付終了'){
		//同じ場合
		if($min == $max){
			if($min == NULL)
			{
				$ret = $NgStr;
			}
			else{	//単一
				$ret = number_format($min) . $str;
			}
		}
		//金額
		elseif(strpos($min,'受付終了') !== false	){
			$ret = $NgStr;
		}
		else{
			if(!empty($min) && !empty($max)){
				$ret = number_format($min) . '〜' . number_format($max) . $str;
			}
			else{
				$ret = number_format($min) . $str;
			}
		}
		return $ret;
	}

}



?>
