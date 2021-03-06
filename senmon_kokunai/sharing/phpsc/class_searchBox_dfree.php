<?php

/***************************************************************************************************************
	※各クラス名から「Test」を消さないとダメだと思います。
	※下記のincludeが必要だと思います。
		include_once($_SERVER['DOCUMENT_ROOT'] ."/sharing/phpsc/path.php");
		include_once('/var/www/html/hankyu-travel.com/sharing/phpsc/class_solr_access.php');
		include_once($SharingPSPath . 'read_master.php');
		include_once($SharingPSPath . 'class_searchCourseList.php');
/***************************************************************************************************************
*/

include_once($_SERVER['DOCUMENT_ROOT'] . '/sharing/phpsc/solr_access.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/sharing/phpsc/read_master.php');

include_once($_SERVER['DOCUMENT_ROOT'] . '/attending/senmon_kokunai/phpsc/writeLogClass.php');

//include_once($SharingPSPath . 'class_searchCourseList.php');
/*******************************************************
 * ページに入ってきたときの初期状態を表示する。
 *
 * 引数
 * 		$Naigai				:str	：内外（必須：i or d）
 * 		$p_mokuteki			:str	：目的地　※定義書通り
 * 		$p_hatsu			:str	：出発地　※定義書通り
 * 		$SubKyotenCode		:str	：サブ拠点ID（必須）
 * 		$KyoteHatsuAry		:ary	：拠点の出発地情報（必須）
*******************************************************/

class SearchActionDefaultDfree {
	#=======================
	#	返値一覧
	#=======================
	public $MyNaigai;			//内外判定
	public $RqParamAry_forView;	//表示に使うリクエストパラ
	public $ResObj;				//商品部分ママ返却
	public $FacetObj;			//見やすくなったFacet
	public $StatusObj;			//見やすくなった統計情報
	public $Values;				//表示用に加工された配列（keyはリクエストパラと同一）

	/*使うglobal変数*/
	//$GlobalSolrReqParamAry（solr_access.phpにあります）

	#=======================
	#	初動
	#=======================
	function __construct($Naigai, $p_mokuteki=NULL, $p_hatsu=NULL, $SubKyotenCode, $KyoteHatsuAry) {
		global $PathSharing, $GlobalSolrReqParamAry, $GlobalSetStrAry,$SettingData;
		$this->MyNaigai 	 = $Naigai;
		$this->SubKyotenCode = $SubKyotenCode;
		$this->KyoteHatsuAry = $KyoteHatsuAry;
//echo "<pre>"; var_dump($this); echo "</pre>";
//		echo $Naigai;
	}

	#=======================
	#	表示用に加工します
	#=======================
	function MakeValues($fetchArray = array()){
		global $GlobalSolrReqParamAry;
		foreach($GlobalSolrReqParamAry[$this->MyNaigai] as $ParamName => $ValueAry){
			// 指定した表示値じゃなければスキップ
			if (!in_array($ParamName, $fetchArray)) continue;
			switch($ParamName){
				case 'p_hatsu':
				case 'p_hatsu_sub':
				case 'p_conductor':
				case 'p_mokuteki':
				case 'p_bunrui':
				case 'p_bus_boarding_code':
				case 'p_kikan_min':
				case 'p_dep_airport_code':
				case 'p_arr_airport_code':
				case 'p_price_flg':
				case 'p_hotel_code':
				case 'p_carr':
				case 'p_sort':

					$this->$ParamName($ParamName);
					break;

				//ちびっこマスターズ
				case 'p_timezone':
				case 'p_seatclass':
				case 'p_mainbrand':
				case 'p_discount':
				case 'p_stock':
				case 'p_total_amount_divide':
				case 'p_transport':
					$this->Params($ParamName);
					break;

				//その他のパラメータは無視する
				default:
					break;
			}
		}
	}

	#=======================
	#	受け取ったリクエストパラを、solrに渡す準備をします
	#	レスポンス表示をする際の和名準備もします
	#=======================
	function ActRequestForSolr($Request){
		global $GlobalSolrReqParamAry;
		/*グローバル変数に値を入れていく*/
		/*表示用も一緒に作る*/
//print_r($GlobalSolrReqParamAry);
		foreach($GlobalSolrReqParamAry[$this->MyNaigai] as $ParamName => $DefVal){
			if(!isset($Request[$ParamName]) || $Request[$ParamName] == NULL){
//				$this->RqParamAry_forView[$ParamName]['Null'] = '未選択';	//☆☆☆ここ後で考える
			}
			else{
				/*出発地はスラ切りで入ってくる場合がある*/
				if($ParamName == 'p_dep_date' && strpos($Request[$ParamName], '/') !== false){
					$DateAry = explode('/', $Request[$ParamName]);
					//月指定の場合
					if(empty($DateAry[2])){
						$Request[$ParamName] = sprintf("%04d%02d", $DateAry[0], $DateAry[1]);
					}
					//日付まで
					else{
						$Request[$ParamName] = sprintf("%04d%02d%02d", $DateAry[0], $DateAry[1], $DateAry[2]);
					}
				}
				/*値があるならグローバル変数へ*/
				$GlobalSolrReqParamAry[$this->MyNaigai][$ParamName] = $Request[$ParamName];
			}
		}
	}

	#=======================
	#	エラー処理まとめ
	#=======================
	function ActErr($SolrObj){
		if($SolrObj->ErrFlg == 0){	//エラーじゃなかったら
			/*ファセットを使いやすくします*/
			$this->FacetObj = new SetFacet($SolrObj->Obj->facet_counts->facet_fields);
			/*商品部分はそのまま使います*/
			$this->ResObj = $SolrObj->Obj->response;
			/*統計情報フィールドは若干整形します*/
			if (isset($SolrObj->Obj->stats)) { $this->StatusObj = new SetStatus($SolrObj->Obj->stats->stats_field); }
		}
		//エラー時の処理
		else{
			/*ヒット数入れます*/
			$this->ResObj->p_hit_num = 0;
			/*統計情報フィールドに、値を入れます*/
			$ErrObj->p_all_price_min = 0;
			$ErrObj->p_all_kikan_min = 0;
			$this->StatusObj = new SetStatus($ErrObj);

			/*エラーの内容を入れます*/
			$this->ErrObj = $SolrObj->Obj->response->p_result_detail;
		}
	}

	#=======================
	#	ファセットで1件以上のものだけに絞り込む
	#		引数：ファセットのパラメータ名
	#=======================
	function GetValidAryFromFacet($Param){
		$retAry = NULL;
		if(is_array($this->FacetObj->RetFacet[$Param])){
			foreach($this->FacetObj->RetFacet[$Param] as $val => $ary){
				//在庫があれば
				if($ary['facet'] > 0){
					$retAry[$val] = $ary;
				}
			}
		}
		return $retAry;
	}

	#=======================
	#	和名を探す：グローバルマスタ編
	#		引数：パラメータ名, コード, [内外]
	#		使用パラ：p_conductor、p_carr、p_hotel
	#=======================
	function GetNameFromMaster($Param, $Naigai=NULL){
		global $GlobalMaster;
		/*マスタがあるかどうかチェック*/
		if(empty($GlobalMaster[$Param])){
			$ClassName = 'GM_' . $Param;
			new $ClassName($Naigai);
		}
		return $GlobalMaster[$Param];
	}

	#=======================
	#	和名を探す：マスタ編（目的地だけは特別）
	#		引数：パラメータ名, コード, [内外]
	#=======================
	function GetNameFromMasterMokuteki($ParamName, $ViewParamName, $MyKey, $Naigai){
		global $GlobalMaster;
		/*マスタがあるかどうかチェック*/
		if(empty($GlobalMaster[$ParamName][$Naigai])){
			new GM_DestinationList($Naigai);
		}
		return $GlobalMaster[$ParamName][$Naigai][$MyKey];
	}

	#=======================
	#	配列の形式を、元ネタのRqParamAry_forViewと合わせます
	#=======================
	function MakeAryParam($ParamName){
		global $GlobalSolrReqParamAry;
		if($GlobalSolrReqParamAry[$this->MyNaigai][$ParamName] == NULL){
				$ResAry['Null'] = '';
		}
		else{
			$ExVal = explode(',', $GlobalSolrReqParamAry[$this->MyNaigai][$ParamName]);
			foreach($ExVal as $MyExVal){
				$ResAry[$MyExVal] = '';
			}
		}
		return $ResAry;
	}

	#=======================
	#	追加で返してほしいパラメータがある場合。
	#=======================
	function AddRetFnc($Request){
		//いくつかあるかもしれないので、カンマで区切って配列へ

		if(!$Request['AddRetType']){return;}
		$AddRetParamAry = explode(',' , $Request['AddRetType']);
		//あるなし判定一応する
		if(!is_array($AddRetParamAry)){return;}
		//ほしいパラメータごとにグルグル
		foreach($AddRetParamAry as $ParamName){
			if(!$ParamName || $ParamName == $Request['SetParam']){
				continue;
			}
			$setParamName = $ParamName;
			//キャリアは返却名称が変わる
			if($setParamName == 'p_carr'){
				$setParamName .= '_cn';
			}
			elseif($setParamName == 'p_dep_airport_code'){
				$setParamName = str_replace('_code', '_name', $setParamName);
			}

			$this->$ParamName($setParamName);
			$RetOpt = $this->Values[$setParamName];

			//改行とタブトル
			$RetOpt = str_replace(array("\r\n","\n","\r","\t"), '', $RetOpt);
			$RetJS .=<<<EOD
$('#{$this->MyNaigai}SearchBox select#{$ParamName}').append('{$RetOpt}');

EOD;

		}
		return $RetJS;
	}

	#=======================
	#	表示用に加工
	#=======================
	/*+++++++++++++++
		ソート条件
	+++++++++++++++++*/
	function p_sort($ParamName){
		global $GlobalSetStrAry, $GlobalSolrReqParamAry;
		//表示用
		$Keys = explode(',', $GlobalSolrReqParamAry[$this->MyNaigai][$ParamName]);
		if(empty($Keys) || $Keys[0] == Null){
			$Keys[0] = 1;
		}
		$Css = ' class="SR_Selected"';
		if($Keys[0] == 4){
			$Css = ' SR_Selected';
		}
		$GlobalSetStrAry['p_sort'][$Keys[0]] = $Css;
	}

	/*+++++++++++++++
		キャリア
	+++++++++++++++++*/
	function p_carr($ParamName){
		//配列だったら
		$this->Values[$ParamName] = NULL;
		//ファセットを1件以上のものだけにする
		$FacetAry = $this->GetValidAryFromFacet('p_carr_cn');
		if(empty($FacetAry)){
			return;
		}
		foreach($FacetAry as $val => $ary){
			$MyName = MyEcho($ary['name']);
			$this->Values[$ParamName] .=<<<EOD
<option value="{$val}">{$MyName}</option>

EOD;
		}
	}

	/*+++++++++++++++
		ホテル
	+++++++++++++++++*/
	function p_hotel_code($ParamName){
		//配列だったら
		$this->Values[$ParamName] = NULL;
		//ファセットを1件以上のものだけにする
		$FacetAry = $this->GetValidAryFromFacet('p_hotel_name');
		if(empty($FacetAry)){
			return;
		}
		foreach($FacetAry as $val => $ary){
			$MyName = MyEcho($ary['name']);
			$this->Values[$ParamName] .=<<<EOD
<option value="{$val}">{$MyName}</option>

EOD;
		}
	}

	/*+++++++++++++++
		旅行代金
	+++++++++++++++++*/
	function p_price_flg($ParamName){
		//配列だったら
		$this->Values['p_price_min'] = NULL;
		$this->Values['p_price_max'] = NULL;
		//ファセットを1件以上のものだけにする
		$FacetAry = $this->GetValidAryFromFacet($ParamName);
		if(empty($FacetAry)){
			return;
		}
		$ValAry = array_keys($FacetAry);
		//最小最大
		$minPrice = min($ValAry);
		$maxPrice = max($ValAry);
		//optionタグを作ります
		$i = $minPrice;	//minからスタートします
		while($i <= $maxPrice){
			$Price = number_format($i);
			if($i > 1000000 || $i > $maxPrice){
				break;
			}

			$this->Values['p_price_min'] .=<<<EOD
<option value="{$i}">{$Price}円</option>

EOD;
			//1000000万以上
			if($i >= 1000000){
				$this->Values['p_price_max'] .=<<<EOD
<option value="">1,000,000円以上</option>

EOD;
				break;
			}
			if($i < 10000){
				$i += 1000;
			}
			elseif($i < 150000){
				$i += 10000;
			}
			elseif($i < 500000){
				$i += 50000;
			}
			elseif($i < 1000000){
				$i += 100000;
			}
			$iEnd = $i-1;
			$EndPrice = number_format($iEnd);
			$this->Values['p_price_max'] .=<<<EOD
<option value="{$iEnd}">{$EndPrice}円</option>

EOD;
		}
	}

	/*+++++++++++++++
		旅行日数（minとmaxは同じ）
	+++++++++++++++++*/
	function p_kikan_min($ParamName){
		//配列だったら
		$this->Values[$ParamName] = NULL;
		//ファセットを1件以上のものだけにする
		$FacetAry = $this->GetValidAryFromFacet('p_kikan');
		if(is_array($FacetAry)){
			//minとmaxを埋めなきゃいけない
			$MinMax = array_keys($FacetAry);
			$min = min($MinMax);
			$max = max($MinMax);
			$this->Values[$ParamName] =<<<EOD
			<option value=""></option>
EOD;
			for($i=$min; $i<=$max; $i++){
				//オプションタグへ
				$this->Values[$ParamName] .=<<<EOD
<option value="{$i}">$i</option>

EOD;
			}

		}
	}

	/*+++++++++++++++
		添乗員
	+++++++++++++++++*/
	function p_conductor($ParamName=''){
		if(empty($ParamName)){
			$ParamName = 'p_conductor';
		}
		//マスタから和名ゲット
		$MasterValues = $this->GetNameFromMaster($ParamName);

		/*表示にはプルダウンにする必要があります*/
		$this->Values[$ParamName] = NULL;
		foreach($MasterValues[$this->MyNaigai] as $key => $val){
			$this->Values[$ParamName] .=<<<EOD
<option value="{$key}">$val</option>

EOD;
		}
	}

	/*+++++++++++++++
		出発地サブ（海外・国内）
	+++++++++++++++++*/
	function p_hatsu_sub($ParamName){
		$this->p_hatsu($ParamName);
	}

	/*+++++++++++++++
		バス乗車地
	+++++++++++++++++*/
	function p_bus_boarding_code($ParamName){
		$this->Values[$ParamName] = NULL;
		//ファセットを1件以上のものだけにする
		$BusBoardAry = $this->FacetObj->RetFacet['p_bus_boarding_name'];
		if(is_array($BusBoardAry)){
			//バス乗車地は都道府県ごとになっているのです
			foreach($BusBoardAry as $PrefectureCode => $BoardAry){
				foreach($BoardAry as $key =>$ary){
					if($ary['facet'] > 0){
						$MyName = MyEcho($ary['name']);
						$this->Values[$ParamName] .=<<<EOD
<option value="{$key}">{$MyName}</option>

EOD;

					}
				}
			}
		}
	}

	/*+++++++++++++++
		ちびっ子マスターズ
	+++++++++++++++++*/
	function Params($ParamName){

		//マスタから和名ゲット
		$MasterValues = $this->GetNameFromMaster($ParamName);

		/*表示はチェックボックス*/
		$this->Values[$ParamName] = NULL;
		switch($ParamName){
			case 'p_total_amount_divide':
				$MasterValuesAdd['NULL'] = '設定しない';
			case 'p_stock':
				$tagType = 'radio';
				break;

			default:
				$tagType = 'checkbox';
				break;
		}

		if(is_array($MasterValuesAdd)){
			$MasterValues = array_merge($MasterValuesAdd, $MasterValues);
		}

		foreach($MasterValues as $key => $val){
			if($key === 'NULL'){
				$key = NULL;
			}
			$this->Values[$ParamName] .=<<<EOD
<dd class="OnFLeft">
<input type="{$tagType}" name="{$ParamName}" value="{$key}" />
{$val}</dd>

EOD;
		}
	}
}




/*
########################################################################
#
#	検索BOXのclass群です。
#
########################################################################
*/

/*******************************************************
 * ページに入ってきたときの初期状態を表示する。
 *
 * 引数
 * 		$Naigai					:str	：内外（必須：i or d）
 * 		$p_mokuteki				:str	：目的地　※定義書通り
 * 		$p_hatsu					:str	：出発地　※定義書通り
 * 		$SubKyotenCode			:str	：サブ拠点ID（必須）
 * 		$KyoteHatsuAry		:ary	：拠点の出発地情報（必須）
*******************************************************/
class SearchActionForSearchBoxDfree extends SearchActionDefaultDfree {
	#=======================
	#	初動
	#=======================
	function __construct($Naigai, $Req=NULL, $p_hatsu, $SubKyotenCode, $KyoteHatsuAry) {

		global $PathSharing, $GlobalSolrReqParamAry, $GlobalSetStrAry,$SettingData,$AttendingIncPath;
		/*受け取ったリクエストパラを、solrに渡す準備をします*/

		parent::__construct($Naigai, null, null, $SubKyotenCode, $KyoteHatsuAry);
		$GlobalSolrReqParamAry[$Naigai]['p_mokuteki'] = NULL;
		$GlobalSolrReqParamAry['i']['p_hatsu'] = NULL;
		$GlobalSolrReqParamAry['d']['p_hatsu_sub'] = NULL;


		if(!empty($Req['p_mokuteki'])){
			$p_mokuteki = $Req['p_mokuteki'];
		}
		if(!empty($Req['p_bunrui'])){
			$bunrui= $Req['p_bunrui'];
		}
		if(!empty($Req['p_transport'])){
			$p_transport= $Req['p_transport'];
		}

		if($Naigai=='i'){
			/*if($p_hatsu == '134'){
				$Request = array(
					'p_hatsu' => '101,130'
					,'p_mokuteki' => $p_mokuteki
					,'p_bunrui' => $BunruiCode
					,'p_transport'=>$p_transport
					,'p_hei'=>'92'
				);
			}else{
				$Request = array(
					'p_hatsu' => $p_hatsu
					,'p_mokuteki' => $p_mokuteki
					,'p_bunrui' => $BunruiCode
					,'p_transport'=>$p_transport
				);
			}*/
			$Request = array(
				'p_hatsu' => $p_hatsu
				,'p_mokuteki' => $p_mokuteki
				,'p_bunrui' => !empty($BunruiCode) ? $BunruiCode : ''
				,'p_transport'=> !empty($p_transport) ? $p_transport : ''
			);
		}
		else{
			$Request = array(
				'p_hatsu_sub' => $p_hatsu
				,'p_mokuteki' => $p_mokuteki
				,'p_bunrui' => !empty($BunruiCode) ? $BunruiCode : '030'
				,'p_transport'=> !empty($p_transport) ? $p_transport : ''
			);
		}

		$this->ActRequestForSolr($Request);

		/*応答データ形式を指定*/
		$GlobalSolrReqParamAry[$this->MyNaigai]['p_data_kind'] = '1';	//ファセットのみ
		//返して欲しい項目は、内外別
		if($this->MyNaigai == 'i'){
			$GlobalSolrReqParamAry[$this->MyNaigai]['p_rtn_data'] = 'p_hatsu_name,p_conductor,p_dest_name,p_country_name,p_city_cn';	//ファセットを返してほしい項目
		}
		else{
			$GlobalSolrReqParamAry[$this->MyNaigai]['p_rtn_data'] = 'p_hatsu_name,p_carr_cn,p_dest_name,p_prefecture_name,p_region_cn,p_price_flg,p_bus_boarding_name,p_dep_airport_name,p_arr_airport_name,p_kikan';	//こっちは国内
		}

		/*DB通信*/
		$SolrObj = new SolrAccess($this->MyNaigai);	//solrのレスポンス：ママ

		/*エラー処理*/
		$this->ActErr($SolrObj);

		/*表示用に加工します*/
		$this->MakeValues(array('p_hatsu', 'p_hatsu_sub', 'p_conductor', 'p_mokuteki','p_dep_airport_code','p_arr_airport_code','p_kikan_min','p_kikan_max'));



		//include($temp);
	}

	/*+++++++++++++++
		目的地
	+++++++++++++++++*/
	function p_mokuteki($ParamName=''){
		global $GlobalSolrReqParamAry,$mokuteki,$categoryType;
		if(empty($ParamName)){
			$ParamName = 'p_mokuteki';
		}
		$MokuReqPara = $GlobalSolrReqParamAry[$this->MyNaigai][$ParamName];
		/*--- チェックパラ配列 ---*/
		$CheckParamNameAry = array(
			 'i' => array('p_dest_name', 'p_country_name', 'p_city_cn')
			,'d' => array('p_dest_name', 'p_prefecture_name', 'p_region_cn')
		);
	    /*リクエストパラの処理開始*/
    	$typeTopFlg = 0;
		if(empty($MokuReqPara)){
			$MokuReqPara = '--';
			$typeTopFlg = 1;
		}
		//リクエストパラの分割
		$MokutekiAry = explode(',', $MokuReqPara);

        $CountryAry = array();
        $CityAry = array();
		/*方面・国・都市の配列を作る。一度分解しないと判別できないよ*/
		foreach($MokutekiAry as $MokutekiSet){
			//分割
			list($DestCode, $CountryCode, $CityCode) = explode('-', $MokutekiSet);
			if($typeTopFlg !== 1){
				//まとめ配列
				$MatomeAry[$DestCode][$CountryCode][$CityCode] = '';
			}
			/*方面の処理*/
			if(!empty($DestCode)){
				/*ファセットから和名を探す*/
				$DestName = '';
				$DestName = $this->FacetObj->RetFacet[$CheckParamNameAry[$this->MyNaigai][0]][$DestCode]['name'];

				//無かったらマスタ
				/*
				if(empty($DestName)){
					$DestName = $this->GetNameFromMasterMokuteki('p_dest', $CheckParamNameAry[$this->MyNaigai][0], $DestCode, $this->MyNaigai);
				}
				*/

				//それでも無かったら対象外
				if(!empty($DestName)){
					$DestAry[$DestCode] = $DestName;
				}
			}
			/*国の処理*/
			if(!empty($CountryCode)){
				$CountryName = '';
				/*ファセットから和名を探す*/
				if (isset($this->FacetObj->RetFacet[$CheckParamNameAry[$this->MyNaigai][1]][$DestCode])
				        && isset($this->FacetObj->RetFacet[$CheckParamNameAry[$this->MyNaigai][1]][$DestCode][$CountryCode])) {
				    $CountryName = $this->FacetObj->RetFacet[$CheckParamNameAry[$this->MyNaigai][1]][$DestCode][$CountryCode]['name'];
				}

				//無かったらマスタ
				/*
				if(empty($CountryName)){
					$CountryName = $this->GetNameFromMasterMokuteki('p_country', $CheckParamNameAry[$this->MyNaigai][1], $CountryCode, $this->MyNaigai);
				}
				*/

				//それでも無かったら対象外
				if(!empty($CountryName)){
					$CountryAry[$CountryCode] = $CountryName;
				}
//				$CountryAry[$CountryCode] = $CountryName;
			}
			//都市
			if(!empty($CityCode)){
				$CityName = '';
				/*ファセットから和名を探す*/
				$CityName = $this->FacetObj->RetFacet[$CheckParamNameAry[$this->MyNaigai][2]][$DestCode][$CountryCode][$CityCode]['name'];

				//無かったらマスタ
				/*
				if(empty($CityName)){
					$CityName = $this->GetNameFromMasterMokuteki('p_city', $CheckParamNameAry[$this->MyNaigai][2], $CityCode, $this->MyNaigai);
				}
				*/

				//それでも無かったら対象外
				if(!empty($CityName)){
					$CityAry[$CityCode] = $CityName;
				}
			}
		}

		// 方面ページなら
		if($categoryType == CATEGORY_TYPE_DEST){
			// 何もしない
		}elseif ($categoryType == CATEGORY_TYPE_COUNTRY) {
			$CityAry = NULL;
		}
		else{
			$CityAry = NULL;
		}
		/*いよいよ見た目を作ります*/
		//空っぽの国都市をデフォルトに。
		$this->Values['Country'] =<<<EOD
<select name="preCountry" id="preCountry">
<option value="">選択してください</option>
</select>

EOD;
		$this->Values['City'] =<<<EOD
<select name="preCity" id="preCity">
<option value="">選択してください</option>
</select>

EOD;
		/*------------ 方面系 ------------*/
		$Opt = NULL;
		//トップ系の場合
		if(empty($DestAry) && $typeTopFlg == 1){
			/*プルダウンを作れる*/
			$FacetAry = $this->FacetObj->RetFacet[$CheckParamNameAry[$this->MyNaigai][0]];

			$Opt .=<<<EOD
<option value="">選択してください</option>

EOD;
			if(!empty($FacetAry)){
				foreach($FacetAry as $MyCode => $MyAry){
					$Opt .=<<<EOD
<option value="{$MyCode}">{$MyAry['name']}</option>

EOD;
				}
			}
		}
		//方面が複数の専門店
		elseif(count($DestAry) > 1){
			$OptAdd = NULL;
			$OptHead = NULL;
			$OptCnt = 0;
			foreach($DestAry as $MyCode => $MyStr){
				if($OptCnt > 0){
					$OptHead .= ',';
				}
				$OptHead .= $MyCode;
				$OptAdd .=<<<EOD
<option value="{$MyCode}">{$MyStr}</option>
EOD;
				$OptCnt++;
			}
			$Opt =<<<EOD
<option value="{$OptHead}">選択してください</option>
$OptAdd
EOD;
		}
		//方面がひとつの場合
		elseif(count($DestAry) === 1){
			foreach($DestAry as $DestCode => $DestName){
				$this->Values['Dest'] =<<<EOD
<strong>{$DestName}</strong><input type="hidden" name="preDest" id="preDest" value="{$DestCode}" />

EOD;
			}
			/*------------ 国系 ------------*/
			$OptC = NULL;

			//国が複数の専門店
			if(count($CountryAry) > 1){
				$OptAdd = NULL;
				$OptHead = NULL;
				$OptCnt = 0;
				foreach($CountryAry as $MyCode => $MyStr){
					if($OptCnt > 0){
						$OptHead .= ',';
					}
					$OptHead .= $MyCode;
					$OptAdd .=<<<EOD
<option value="{$MyCode}">{$MyStr}</option>
EOD;
					$OptCnt++;
				}
				//20140530 暫定 国内は設定しない
				if($this->MyNaigai == 'd'){
					$OptHead = '';
				}
				$OptC =<<<EOD
<option value="{$OptHead}">選択してください</option>
$OptAdd
EOD;
			}
			//国がひとつ
			else{
				//NULLってこととは、方面専門店の場合
				if(empty($CountryAry)){
				    $OptHead = '';
					//国ファセットから取得
					$OptC =<<<EOD
<option value="{$OptHead}">選択してください</option>

EOD;
					foreach($this->FacetObj->RetFacet[$CheckParamNameAry[$this->MyNaigai][1]][$DestCode] as $MyCode => $MyAry){
						if($MyAry['facet'] < 1){
							continue;
						}
						$OptC .=<<<EOD
<option value="{$MyCode}">{$MyAry['name']}</option>

EOD;
					}
				}
				//ホントに1カ国
				else{
					/*foreach($CountryAry as $CountryCode => $CountryName){
					}*/
					$this->Values['Country'] =<<<EOD
<strong>{$CountryName}</strong><input type="hidden" name="preCountry" id="preCountry" value="{$CountryCode}" />

EOD;
					/*------------ 都市系 ------------*/
					$OptCi = NULL;
					//都市が複数の専門店

					if(count($CityAry) > 1){
						foreach($CityAry as $MyCode => $MyStr){

							$selected = '';
							// 都市ページで北海道か沖縄の場合
							if($categoryType == CATEGORY_TYPE_CITY && (substr($mokuteki, 3, 2) == '01' || substr($mokuteki, 3, 2) == '47')){
								if($MyCode == substr($mokuteki, 6, 5)){
									$selected = 'selected';
								}
							}

							$OptCi .=<<<EOD
<option value="{$MyCode}" $selected>{$MyStr}</option>

EOD;
						}
					}
					//都市がひとつ
					else{
						//NULLってこととは、国専門店の場合
						if(empty($CityAry)){
							$fksCityCode = '';
							$fksMokuteki = explode(',', $mokuteki);
							if(count($fksMokuteki) > 1){
								foreach ($fksMokuteki as $value) {
									$fksCityCode .= substr($value, 6, 5).',';
								}
								$fksCityCode = rtrim($fksCityCode,', ');
							}
							//国ファセットから取得
							foreach($this->FacetObj->RetFacet[$CheckParamNameAry[$this->MyNaigai][2]][$DestCode][$CountryCode] as $MyCode => $MyAry){
								if($MyAry['facet'] < 1){
									continue;
								}
								$selected = '';
								// 都市ページで北海道か沖縄の場合
								if($categoryType == CATEGORY_TYPE_CITY && (substr($mokuteki, 3, 2) == '01' || substr($mokuteki, 3, 2) == '47')){
									if($MyCode == substr($mokuteki, 6, 5) && count($fksMokuteki) <= 1){
										$selected = 'selected';
									}
								}

								$OptCi .=<<<EOD
<option value="{$MyCode}" $selected>{$MyAry['name']}</option>

EOD;
							}
						}
						//本当に1都市
						else{
							$this->Values['City'] =<<<EOD
<strong>{$CityName}</strong><input type="hidden" name="preCity" id="preCity" value="{$CityCode}" />

EOD;
						}
					}
				}
			}
		}

		/*方面がたくさん*/
		if(!empty($Opt)){
			$this->Values['Dest'] =<<<EOD
<select name="preDest" id="preDest">
$Opt
</select>

EOD;
		}
		/*国がたくさん*/
		if(!empty($OptC)){
			$this->Values['Country'] =<<<EOD
<select name="preCountry" id="preCountry">
$OptC
</select>

EOD;
		}
		/*都市がたくさん*/
		if(!empty($OptCi)){
			$this->Values['City'] =<<<EOD
<select name="preCity" id="preCity">
<option value="">選択してください</option>
$OptCi
</select>
<input type="hidden" name="fksCity" id="fksCity" value="{$fksCityCode}" />
EOD;
		}
	}

	/*+++++++++++++++
		出発地（海外・国内）2014年10日22日時点では使用していない領域
	+++++++++++++++++*/
	function p_hatsu($ParamName){


/* echo "<pre>";
print_r($this->FacetObj->RetFacet['p_hatsu_name']);
echo "</pre>"; */

		global $GlobalMaster, $GlobalSolrReqParamAry,$kyotenId;
		/*拠点名をげっと*（北関東がいる場合があるので、孫ゲット）*/
		$KyotenListAry = new MakeKyotenSimpleGList;

		//全国の場合
		if($this->SubKyotenCode == 'top'){
			$Opt = NULL;
			foreach($this->KyoteHatsuAry as $MyKyotenCode => $MyKyotenAry){
				//サブを全部つなげる
				$Codes = array_keys($MyKyotenAry);
				$CodesStr = implode(',', $Codes);
				$Opt .=<<<EOD
<option value="{$CodesStr}" class="{$MyKyotenCode}">{$KyotenListAry->TgDataAry[$MyKyotenCode]}発</option>

EOD;
			}

			//書き出し用
			$this->Values[$ParamName] =<<<EOD
<select name="{$ParamName}" class="setDefKyoten_S" id="p_hatsu">
	<option value="">選択してください</option>
</select>


EOD;

		}
		//拠点　発をhiddenで持つパターン
		/*else{
			$MyKyotenInfo = $this->KyoteHatsuAry[$this->SubKyotenCode];
			$KyotenNameJ = $KyotenListAry->TgDataAry[$this->SubKyotenCode];
			//書き出し用
			$this->Values[$ParamName] =<<<EOD
<input type="hidden" name="{$ParamName}" value="{$GlobalSolrReqParamAry[$this->MyNaigai][$ParamName]}" id="p_hatsu" />
<strong>{$KyotenNameJ}発</strong>
EOD;
		}*/
			/*拠点タブの場合*/
		else{
			$Opt = '';

			foreach($this->KyoteHatsuAry as $MyKyotenCode => $MyKyotenAry)
			{
				$KyotenNameJ = $KyotenListAry->TgDataAry[$MyKyotenCode];
				$MyKyotenInfo = $this->KyoteHatsuAry[$MyKyotenCode];

				$num = count($MyKyotenInfo);
				$Codes = array_keys($MyKyotenAry);
				$CodesStr = implode(',', $Codes);

				$selected = '';
				if($MyKyotenCode == $kyotenId)
				{
					$selected = 'selected';
				}

				$Opt .=<<<EOD

<option value="{$CodesStr}" class="{$MyKyotenCode}" $selected>{$KyotenListAry->TgDataAry[$MyKyotenCode]}発</option>
EOD;

				if($num >1){foreach($MyKyotenInfo as $code => $name)
				{
					if(isset($this->FacetObj->RetFacet['p_hatsu_name'][$code]) && $this->FacetObj->RetFacet['p_hatsu_name'][$code]['facet'] > 0)
					{

						$Opt .=<<<EOD
<option name="{$ParamName}" value="{$code}">{$name}発</option>
EOD;

					}
				}}
			}


			$this->Values[$ParamName] =<<<EOD
<select name="p_hatsu_sub" class="setDefKyoten_S" id="p_hatsu">
	<option value="">選択してください</option>
	$Opt
</select>
EOD;

		}
	}
	/*+++++++++++++++
		出発空港
	+++++++++++++++++*/
	function p_dep_airport_code($ParamName){
		global $GlobalMaster,$MyParam,$dispKyotenId;

		$this->Values[$ParamName] = NULL;
		//ファセットを1件以上のものだけにする
		$FacetAry = $this->GetValidAryFromFacet('p_dep_airport_name');

		if(empty($FacetAry)){
			//一件も無かったら、「選択できません」でサヨナラ
			$this->Values[$ParamName] .=<<<EOD
<strong>選択できません</strong>
EOD;
			return;
		}

		$Opt = '';

		if($dispKyotenId && $dispKyotenId!=='index'){
			//拠点選択あり

			new GM_freeplan_d_p_dep_airport_code($this->MyNaigai);	//$GlobalMaster[$MyParam]に入った

			foreach($GlobalMaster['freeplan_d_p_dep_airport_code'][$dispKyotenId] as $key => $airport_code){
			    if (!isset($this->FacetObj->RetFacet['p_dep_airport_name'][$airport_code])) {
			        continue;
			    }
				$valAry = $this->FacetObj->RetFacet['p_dep_airport_name'][$airport_code];
				if($valAry['facet'] < 1){
					continue;
				}
				$MyName = MyEcho($valAry['name']);
				$key = $airport_code;
				$Opt .=<<<EOD
<option value="{$key}">{$MyName}</option>
EOD;

			}
		}else{
			//拠点選択なし
			new GM_p_dep_airport_code($this->MyNaigai);	//$GlobalMaster[$MyParam]に入った
			foreach($GlobalMaster['p_dep_airport_code'] as $val => $mAry){
			    if (!isset($this->FacetObj->RetFacet['p_dep_airport_name'][$mAry['p_dep_airport_code']])) {
			        continue;
			    }
				$valAry = $this->FacetObj->RetFacet['p_dep_airport_name'][$mAry['p_dep_airport_code']];
				if($valAry['facet'] < 1){
					continue;
				}
				$MyName = MyEcho($valAry['name']);
				$key = $mAry['p_dep_airport_code'];
				$Opt .=<<<EOD
<option value="{$key}">{$MyName}</option>
EOD;

			}
		}

		if(empty($Opt)){
			//一件も無かったら、「選択できません」でサヨナラ
			$this->Values[$ParamName] .=<<<EOD
<strong>選択できません</strong>
EOD;
			return;
		}

		$this->Values[$ParamName] .=<<<EOD
<select name="{$ParamName}" id="p_dep_airport_code">
	<option value="">選択してください</option>
	$Opt
</select>
EOD;
	//}
	}
	/*+++++++++++++++
		到着空港
	+++++++++++++++++*/
	function p_arr_airport_code($ParamName){
		global $GlobalMaster; $MyParam;
		$ClassName = 'GM_p_dep_airport_code';
		new GM_p_dep_airport_code($this->MyNaigai);	//$GlobalMaster[$MyParam]に入った

		$this->Values[$ParamName] = NULL;
		//ファセットを1件以上のものだけにする
		$FacetAry = $this->FacetObj->RetFacet['p_arr_airport_name'];

		if(empty($FacetAry)){
			//一件も無かったら、「選択できません」でサヨナラ
			$this->Values[$ParamName] .=<<<EOD
<strong>選択できません</strong>
EOD;
			return;
		}
		//とりあえずひとつはある。
		$Opt = '';
		foreach($GlobalMaster['p_dep_airport_code'] as $val => $mAry){

		$valAry = array();
		if (isset($this->FacetObj->RetFacet['p_arr_airport_name'][$mAry['p_dep_airport_code']])) {
		    $valAry = $this->FacetObj->RetFacet['p_arr_airport_name'][$mAry['p_dep_airport_code']];
		} else {
			continue;
		}

		if($valAry['facet'] < 1){
			continue;
		}

		$MyName = MyEcho($valAry['name']);


		$key = $mAry['p_dep_airport_code'];

			$Opt .=<<<EOD
<option value="{$key}">{$MyName}</option>

EOD;
		}

		if(empty($Opt)){
			//一件も無かったら、「選択できません」でサヨナラ
			$this->Values[$ParamName] .=<<<EOD
<strong>選択できません</strong>
EOD;
			return;
		}

		$this->Values[$ParamName] .=<<<EOD
<select name="{$ParamName}" id="p_arr_airport_code">
	<option value="">選択してください</option>
	$Opt
</select>
EOD;
	}
}

/*
*******************************************************
	Ajaxで呼ばれたときの動作
*******************************************************
*/
class AjaxSearchActionForSearchBoxDfree extends SearchActionDefaultDfree {
	#=======================
	#	初動
	#=======================
	function __construct(){
		global $PathSharing, $GlobalSolrReqParamAry, $GlobalSetStrAry;
		$Request = $_REQUEST;
		$DestSplit = explode(',', $Request['preDest']);
		$Request['p_mokuteki'] = NULL;

		if(count($DestSplit) > 1){	//複数方面（あんまりないはず）
			$cnt = 0;
			foreach($DestSplit as $val){
				if($cnt > 0){
					$Request['p_mokuteki'] .= ',';
				}
				$Request['p_mokuteki'] .= $val . '--';
				$cnt++;
			}
		}
		//方面はひとつ
		else{
			//国もチェックしないと
			$CountrySplit = explode(',', $Request['preCountry']);
			if(count($CountrySplit) > 1){	//複数国
				$cnt = 0;
				foreach($CountrySplit as $val){
					if($cnt > 0){
						$Request['p_mokuteki'] .= ',';
					}
					$Request['p_mokuteki'] .= $Request['preDest'] . '-' . $val . '-';
					$cnt++;
				}
			}
			else{
				$Request['p_mokuteki'] = $Request['preDest'] . '-' . $Request['preCountry'] . '-' . (isset($Request['preCity']) ? $Request['preCity'] : '');
			}
		}
		//何もないときはNULL
		if($Request['p_mokuteki'] == '--'){
			$Request['p_mokuteki'] = NULL;
		}
		/*いんくるファイルが存在する場所*/
		$this->IncDirName = str_replace('/phpsc', '/inc', dirname(__FILE__)) . '/';
		$this->IncDirName = str_replace('/__br', '', $this->IncDirName);

		//内外の判定
		$this->MyNaigai = $Request['MyNaigai'];

		/*受け取ったリクエストパラを、solrに渡す準備をします*/
		$this->ActRequestForSolr($Request);

		/*--- チェックパラ配列 ---*/
		$this->CheckParamNameAry = array(
			 'i' => array('p_dest_name', 'p_country_name', 'p_city_cn','p_dest_name')
			,'d' => array('p_dest_name', 'p_prefecture_name', 'p_region_cn', 'p_dest_name,p_arr_airport_name','p_dest_name,p_hatsu_name')
		);

		/*応答データ形式を指定*/
		$GlobalSolrReqParamAry[$this->MyNaigai]['p_data_kind'] = '1';	//ファセットのみ

		/*送る前の判定と処理*/
		$this->ActRequestForSolrJogai($Request);

		if($GlobalSolrReqParamAry[$this->MyNaigai]['p_transport'] == '3'){
			$GlobalSolrReqParamAry[$this->MyNaigai]['p_hatsu_sub'] = NULL;
		}

		//$GlobalSolrReqParamAry['d']['p_rtn_data'] = "p_dest_name,p_arr_airport_name,p_kikan";

		/*DB通信*/
		$SolrObj = new SolrAccess($this->MyNaigai);	//solrのレスポンス：ママ
		$this->ActErr($SolrObj);


        $Add = '';
		/*出発日*/
		if($Request['SetParam'] == 'p_dep_date'){
			echo $this->p_dep_date($Request['SetParam'], $SolrObj);
			return;
		}
		/*返してほしいパラがあったら返す（目的地の処理）でも、0件ならやらない*/
		elseif($Request['SetParam'] != NULL && $this->ResObj->p_hit_num > 0){
			$Add = $this->p_mokuteki($Request);
		}


		if($Request['SetParam'] == 'p_dep_airport_code' && $this->ResObj->p_hit_num > 0){
			$Add = $this->p_arr_airport_code($Request);
			$Add .= $this->p_mokuteki($Request);
		}

		/*バス専門店の場合*/
		if($Request['MyType'] == 'bus' && $this->ResObj->p_hit_num > 0 && $Request['SetParam'] != 'p_bus_boarding_name'){
			$Add .= $this->p_bus_boarding_code($Request);
		}
		/*国内フリープランの場合*/
		if($Request['MyType'] == 'freeplan-d' && $this->ResObj->p_hit_num > 0 && ($Request['SetParam'] != 'p_kikan_min' && $Request['SetParam'] != 'p_kikan_max')){
			$Add .= $this->p_kikan($Request);
		}

		if($Request['MyType'] == 'freeplan-d' && $this->ResObj->p_hit_num > 0 && $Request['SetParam'] == 'p_transport'){
			//$Add .= $this->p_hatsu_sub($Request);
		}

		/*他にも返してほしいパラがあったとき*/
		if(isset($Request['AddRetType']) && $Request['AddRetType'] != NULL && $this->ResObj->p_hit_num > 0){
			$Add .= $this->AddRetFnc($Request);
		}

		// 金額のカンマ付け
		$p_hit_num = is_numeric($this->ResObj->p_hit_num) ? number_format($this->ResObj->p_hit_num) : $this->ResObj->p_hit_num;


		/*書き出し*/
		echo <<<EOD
$('span#{$this->MyNaigai}p_hit_num_dfree').html("{$p_hit_num}");
$Add
EOD;

	}

	#=======================
	#	自分のパラメータはリクエストパラからは除外する場合がある。その判定
	#=======================
	function ActRequestForSolrJogai($Request){
		global $GlobalSolrReqParamAry;
		switch($Request['SetParam']){
			case 'p_transport':
			case 'p_hatsu':
			case 'p_hatsu_sub':
			case 'p_dep_airport_code':
			case 'p_arr_airport_code';

			//出発地が変わったら、その下を変えなきゃ
			if($Request['RetParam'] == ''){
				$GlobalSolrReqParamAry[$this->MyNaigai]['p_rtn_data'] = 'p_conductor';	//返すパラが無いときは、どーでもいい
			}
			else{
				$GlobalSolrReqParamAry[$this->MyNaigai]['p_rtn_data'] = $this->CheckParamNameAry[$this->MyNaigai][$Request['RetParam']];
			}
			break;


			case 'preDest':
				$GlobalSolrReqParamAry[$this->MyNaigai]['p_rtn_data'] = $this->CheckParamNameAry[$this->MyNaigai][1];
				break;

			case 'preCountry':
				$GlobalSolrReqParamAry[$this->MyNaigai]['p_rtn_data'] = $this->CheckParamNameAry[$this->MyNaigai][2];
				break;

			//ちょっと操作が必要
			case 'p_dep_date':
				$MyParam = $Request['SetParam'];

				//自分の値の一次置き場へ入れておく
				$this->MyParamValue = $GlobalSolrReqParamAry[$this->MyNaigai][$MyParam];
				//今月
				$ThisMonth = date('Ym');
				//デフォルト
				if(empty($_REQUEST['ViewMonth'])){
					$this->ViewTG = $this->MyParamValue;
				}
				//前へ次へだったら
				else{
					$this->ViewTG = $_REQUEST['ViewMonth'];
				}
				//最初の表示月
				if(empty($this->ViewTG)){
					//設定が無かったら今月指定
					$GlobalSolrReqParamAry[$this->MyNaigai][$MyParam] = $ThisMonth;
				}
				else{
					//設定があったら
					$SetDateY = substr($this->ViewTG, 0, 4);
					$SetDateM = substr($this->ViewTG, 4, 2);
					$SetDateYM = date('Ym', mktime(0,0,0,$SetDateM-1,1,$SetDateY));	//1ヶ月前を出しておく
					//1ヶ月前が今月より以前だったら、今月ですよ
					if($SetDateYM < $ThisMonth){
						$GlobalSolrReqParamAry[$this->MyNaigai][$MyParam] = $ThisMonth;
					}
					else{
						$GlobalSolrReqParamAry[$this->MyNaigai][$MyParam] = $SetDateYM;
					}
				}
				/*返してほしい項目について*/
				$GlobalSolrReqParamAry[$this->MyNaigai]['p_rtn_data'] = '';	//出発日のときはNULL
				/*出発日は応答データ形式を指定*/
				$GlobalSolrReqParamAry[$this->MyNaigai]['p_data_kind'] = '4';	//ファセットのみ
				break;

			//あとはどーでもいい
			default:
				$GlobalSolrReqParamAry[$this->MyNaigai]['p_rtn_data'] = 'p_conductor';
				break;
		}
		//バス専門店の場合
		if($Request['MyType'] == 'bus' && $GlobalSolrReqParamAry[$this->MyNaigai]['p_rtn_data'] !== NULL){
			$GlobalSolrReqParamAry[$this->MyNaigai]['p_rtn_data'] .= ',p_bus_boarding_name';
		}
		//フリープラン国内の場合
		if($Request['MyType'] == 'freeplan-d' && $GlobalSolrReqParamAry[$this->MyNaigai]['p_rtn_data'] !== NULL){
			$GlobalSolrReqParamAry[$this->MyNaigai]['p_rtn_data'] .= ',p_kikan';
		}
		/*他にも返してほしいパラがあったとき*/
		if(isset($Request['AddRetType']) && $Request['AddRetType'] != NULL && $GlobalSolrReqParamAry[$this->MyNaigai]['p_rtn_data'] !== NULL){
			//一度分解して_cn付けなきゃいけないのがある
			$AddRetParamAry = explode(',' , $Request['AddRetType']);
			foreach($AddRetParamAry as $AddRetParam){
				if($AddRetParam == 'p_carr'){
					$LastParamAry[] = $AddRetParam . '_cn';
				}
				elseif($AddRetParam == 'p_dep_airport_code' | $AddRetParam == 'p_arr_airport_code'){
					$LastParamAry[] = str_replace('_code', '_name', $AddRetParam);
				}
				else{
					$LastParamAry[] = $AddRetParam;
				}
			}
			//もっかいつなげる
			$AddRetParamStr = implode(',' , $LastParamAry);
			$GlobalSolrReqParamAry[$this->MyNaigai]['p_rtn_data'] .= ',' . $AddRetParamStr;
		}
	}

/*+++++++++++++++
		旅行日数（minとmaxは同じ）
	+++++++++++++++++*/
	function p_kikan($Request){

		//ファセットを1件以上のものだけにする
		$FacetAry = $this->FacetObj->RetFacet['p_kikan'];
		$RetOpt = NULL;
		if(is_array($FacetAry)){
			//minとmaxを埋めなきゃいけない
			$RetOpt =<<<EOD
			<option value=""></option>
EOD;
			foreach($FacetAry as $MinMax =>$data){
			if($data['facet'] > 0){

				//オプションタグへ
				$RetOpt .=<<<EOD
<option value="{$MinMax}">$MinMax</option>

EOD;
}
}

	//改行とタブトル
		$RetOpt = str_replace(array("\r\n","\n","\r","\t"), '', $RetOpt);
		$RetJS =<<<EOD

$('#{$this->MyNaigai}SearchBox select#p_kikan_min').html('{$RetOpt}');
$('#{$this->MyNaigai}SearchBox select#p_kikan_max').html('{$RetOpt}');
EOD;

		return $RetJS;


		}
	}

	#=======================
	#	バス乗車地もいろいろしないと。
	#=======================
	function p_bus_boarding_code($Request){
		//ファセットを1件以上のものだけにする
		$BusBoardAry = $this->FacetObj->RetFacet['p_bus_boarding_name'];
		$RetOpt = NULL;
		if(is_array($BusBoardAry)){
			//バス乗車地は都道府県ごとになっているのです
			foreach($BusBoardAry as $PrefectureCode => $BoardAry){
				foreach($BoardAry as $key =>$ary){
					if($ary['facet'] > 0){
						$MyName = MyEcho($ary['name']);
						$RetOpt .=<<<EOD
<option value="{$key}">{$MyName}</option>

EOD;
					}
				}
			}
		}
		//改行とタブトル
		$RetOpt = str_replace(array("\r\n","\n","\r","\t"), '', $RetOpt);
		$RetJS =<<<EOD
$('#{$this->MyNaigai}SearchBox select#p_bus_boarding_code').append('{$RetOpt}');

EOD;
		return $RetJS;
	}

	/*+++++++++++++++
		出発地（海外・国内）
	+++++++++++++++++*/
	function p_hatsu_sub($ParamName){
		$RetJS = NULL;

		global $GlobalMaster, $GlobalSolrReqParamAry;
		/*拠点名をげっと*（北関東がいる場合があるので、孫ゲット）*/
		$KyotenListAry = new MakeKyotenSimpleGList;
		//出発地パラメータを呼び出す
		$p_hatsuAry = new HierarchyMagoKyotenTabCode_p_hatsu_sub;
		//出発地　段落ち
		$RetOpt = '';

		foreach($p_hatsuAry->TgDataAry['d'] as $key => $arr){
			foreach($arr as $kyotenCode => $val){
				$p_hatsuAry->TgDataAry['d'][$key][$kyotenCode] = '　'.$val;
			}
		}

		foreach($p_hatsuAry->TgDataAry['d'] as $MyKyotenCode => $MyKyotenAry){
			$KyotenNameJ = $KyotenListAry->TgDataAry[$MyKyotenCode];
			$MyKyotenInfo = $p_hatsuAry->TgDataAry['d'][$MyKyotenCode];
			$num = count($MyKyotenInfo);
			$Codes = array_keys($MyKyotenAry);
			$CodesStr = implode(',', $Codes);

			$RetOpt .=<<<EOD
			<option value="{$CodesStr}" class="{$MyKyotenCode}">{$KyotenNameJ}発</option>
EOD;

			//サブ項目の件数が1件超過のものだけ表示
			if($num >1){
				foreach($MyKyotenInfo as $code => $name){
					//サブ項目のファセットの数が1件以上のものだけ表示
					if($this->FacetObj->RetFacet['p_hatsu_name'][$code]['facet'] > 0) {

						$RetOpt .=<<<EOD
						<option name="{$ParamName}" value="{$code}">{$name}発</option>
EOD;
					}
				}
			}
		}

		//改行とタブトル
		$RetOpt = str_replace(array("\r\n","\n","\r","\t"), '', $RetOpt);

		$RetJS =<<<EOD
$('#{$this->MyNaigai}SearchBox select#p_hatsu option').remove();
$('#{$this->MyNaigai}SearchBox select#p_hatsu').append('<option value="">選択してください</option>');
$('#{$this->MyNaigai}SearchBox select#p_hatsu').append('{$RetOpt}');


EOD;
		return $RetJS;
	}



	/*+++++++++++++++
		出発空港
	+++++++++++++++++*/
	function p_dep_airport_code($ParamName){
		global $GlobalMaster,$dispKyotenId;
		new GM_p_dep_airport_code($this->MyNaigai);	//$GlobalMaster[$MyParam]に入った

		$RetJS = NULL;
		//ファセットを1件以上のものだけにする
		$FacetAry = $this->GetValidAryFromFacet('p_dep_airport_name');

		$RetOpt = '';
		if(empty($FacetAry)){
			//一件も無かったら、「選択できません」
			$RetOpt .=<<<EOD
<option value="" selected="selected">選択できません</option>

EOD;
		}
		else{
			//とりあえずひとつはある。
			foreach($FacetAry as $key => $ary){
				$MyName = MyEcho($ary['name']);
				$RetOpt .=<<<EOD
<option value="{$key}">{$MyName}</option>

EOD;
			}
		}
		//改行とタブトル
		$RetOpt = str_replace(array("\r\n","\n","\r","\t"), '', $RetOpt);
		$RetJS =<<<EOD
$('#{$this->MyNaigai}SearchBox select#p_dep_airport_code').append('{$RetOpt}');

EOD;

		return $RetJS;
	}

	/*+++++++++++++++
		到着空港
	+++++++++++++++++*/
	function p_arr_airport_code($ParamName){
		global $GlobalMaster; $MyParam;
		new GM_p_dep_airport_code($this->MyNaigai);	//$GlobalMaster[$MyParam]に入った


		$RetJS = NULL;
		//ファセットを1件以上のものだけにする
		$FacetAry = $this->GetValidAryFromFacet('p_arr_airport_name');

		$RetOpt = '';
		if(empty($FacetAry)){
			//一件も無かったら、「選択できません」
			$RetOpt .=<<<EOD
<option value="" selected="selected">選択できません</option>

EOD;
		}
		else{
			//とりあえずひとつはある。
			foreach($GlobalMaster['p_dep_airport_code'] as $val => $mAry){

		$valAry = $this->FacetObj->RetFacet['p_arr_airport_name'][$mAry['p_dep_airport_code']];

		if($valAry['facet'] < 1){
			continue;
		}

		$MyName = MyEcho($valAry['name']);


		$key = $mAry['p_dep_airport_code'];
				$RetOpt .=<<<EOD
<option value="{$key}">{$MyName}</option>

EOD;
			}
		}
		//改行とタブトル
		$RetOpt = str_replace(array("\r\n","\n","\r","\t"), '', $RetOpt);
		$RetJS =<<<EOD
$('#{$this->MyNaigai}SearchBox select#p_arr_airport_code').removeAttr("disabled");
$('#{$this->MyNaigai}SearchBox').find('select#p_arr_airport_code').append('{$RetOpt}');

EOD;

		return $RetJS;
	}

	#=======================
	#	目的地はいろいろしないと。
	#=======================
	function p_mokuteki($Request){

	    $addRetJS = '';
		switch($Request['RetParam']){
			//方面
			case 0:
			case 3:	//3は、方面と出発空港両方。国内のみ
				if($Request['RetParam'] === ''){
					return;
					break;
				}
				else{
					if($Request['RetParam'] == 3){
						list($HatsuName,$HatsuAirPort) = explode(',', $this->CheckParamNameAry[$this->MyNaigai][$Request['RetParam']]);
						//出発空港処理
						/*$addRetJS = $this->p_dep_airport_code($HatsuAirPort);*/
					}
					else{
						$HatsuName = $this->CheckParamNameAry[$this->MyNaigai][$Request['RetParam']];
					}
				}
				//複数方面専門店の場合
				if(strpos($Request['preDest'], ',') !== false){
					$preDestAry = explode(',', $Request['preDest']);
					foreach($preDestAry as $DestCode){
						$DestName = $this->FacetObj->RetFacet[$HatsuName][$DestCode]['name'];

						//無かったらマスタ
						/*
						if(empty($DestName)){
							$DestName = $this->GetNameFromMasterMokuteki('p_dest', $HatsuName, $DestCode, $this->MyNaigai);
						}
						*/

						$ForEachVar[$DestCode] = array(
							 'facet' => $this->FacetObj->RetFacet[$HatsuName][$DestCode]['facet']
							,'name' => $DestName
						);
					}
				}
				//フツーの専門店
				else{
					$ForEachVar = $this->FacetObj->RetFacet[$HatsuName];
				}
				$TgAppend = 'Dest';
				break;
			//国
			case 1:
				$ForEachVar = $this->FacetObj->RetFacet[$this->CheckParamNameAry[$this->MyNaigai][$Request['RetParam']]][$Request['preDest']];
				$TgAppend = 'Country';
				break;
			//都市
			case 2:
				$ForEachVar = $this->FacetObj->RetFacet[$this->CheckParamNameAry[$this->MyNaigai][$Request['RetParam']]][$Request['preDest']][$Request['preCountry']];
				$TgAppend = 'City';
				break;
			//それ以外はサヨナラ
			default:
				return;
				break;
		}

		$RetOpt = '';
		if(!empty($ForEachVar)){
			foreach($ForEachVar as $MyCode => $MyAry){
				if($MyAry['facet'] < 1){
					continue;
				}
				$RetOpt .=<<<EOD
<option value="{$MyCode}">{$MyAry['name']}</option>
EOD;
			}
		}
		//改行とタブトル
		$RetOpt = str_replace(array("\r\n","\n","\r","\t"), '', $RetOpt);
//$('#{$this->MyNaigai}SearchBox select#pre{$TgAppend}').append('{$RetOpt}');

		$RetJS =<<<EOD
$('#{$this->MyNaigai}SearchBox').find('select#pre{$TgAppend}').removeAttr("disabled");
$('#{$this->MyNaigai}SearchBox').find('select#pre{$TgAppend}').append('{$RetOpt}');
{$addRetJS}
EOD;
		return $RetJS;
	}


	#=======================
	#	表示用に加工します（それぞれ）
	#=======================
	/*--------*/
	#	p_dep_date
	/*--------*/
	function p_dep_date($MyParam, $SolrObj){
		global $GlobalMaster, $GlobalSolrReqParamAry, $SharingMasterPath, $PathMntItecReal;	//マスター一式
		/*--------*/
		#	カレンダー作ります
		/*--------*/
		//休日ファイルの置き場
		$HolidayFile = $PathMntItecReal . 'm_holiday/m_holiday.csv';

		//休日一覧
		$Holidays = file_get_contents($HolidayFile);

		/*リクエストした月が起点*/
		$BaseY = substr($GlobalSolrReqParamAry[$this->MyNaigai]['p_dep_date'], 0, 4);
		$BaseM = substr($GlobalSolrReqParamAry[$this->MyNaigai]['p_dep_date'], 4, 2);

		/*3ヶ月分作ります*/
		$Table = NULL;
		for($j=0; $j<3; $j++){
			$BackLink = NULL;
			$NextLink = NULL;
			//表示したい年月
			$MyY = date('Y', mktime(0,0,0,$BaseM+$j,1,$BaseY));
			$MyM = date('m', mktime(0,0,0,$BaseM+$j,1,$BaseY));
			//月末
			$MyLastDay = date('j', mktime(0,0,0, $MyM+1, 0, $MyY));

			/*divが始まります*/
			if($j % 3 == 0){	//区切りは3つ
				//今が先頭で、しかも今月よりも後だったら、前へリンク表示
				if($j==0 && $MyY.$MyM>date('Ym') ){
					$TgBackMonth = date('Ym', mktime(0,0,0,$MyM-2,1,$MyY));
					$BackLink =<<<EOD
<a href="#" class="SW_CalBack" onclick="NextBackBtnAction({$TgBackMonth});void(0);return false;">←</a>
EOD;
				}

				$Table .=<<<EOD
<div class="SW_SelDate FClear">
<p class="SW_CalBtn OnFLeft">{$BackLink}</p>
EOD;
			}
			/*日にち分グルグル*/
			$Tr = NULL;
			for($i=1; $i<=$MyLastDay; $i++){
				//何曜日？
				$MyWeek = date('w', mktime(0,0,0, $MyM, $i, $MyY));
				//何日？
				$MyDay = date('Ymd', mktime(0,0,0, $MyM, $i, $MyY));
				//日曜日の場合に行開始
				if($MyWeek == 0 && $i !== 1){
					$Tr .= '<tr>';
				}
				//1日の場合
				if ($i === 1) {
					$Tr .= '<tr>'.str_repeat('<td class="non">&nbsp;</td>', $MyWeek);
				}
				$Class = NULL;
				//日曜日
				if($MyWeek == 0){
					$Class = 'sun';
				}
				//土曜日
				elseif($MyWeek == 6){
					$Class = 'sat';
				}
				//祝日
				if(stripos($Holidays, $MyDay) !== false){
					$Class = 'hol';
				}
				//出発日設定されていたら
				if(!empty($this->MyParamValue) && stripos($MyDay, $this->MyParamValue) !==false){
					$Class .= ' sel';
				}
				//クラスが存在してたら
				if(!empty($Class)){
					$Class = ' class="' . $Class . '"';
				}
				//ファセットある
				if($this->FacetObj->RetFacet['p_dep_day'][$MyDay]['facet'] > 0 && $MyDay>=date('Ymd') ){
					$WriteMyDay = date("Y",strtotime($MyDay)) . '/' . date("n",strtotime($MyDay)) . '/' . date("j",strtotime($MyDay));
					$Tr .=<<<EOD
<td{$Class}><a href="#" onclick="SWDate(\'{$WriteMyDay}\');void(0);return false;">{$i}</a></td>
EOD;
				}
				//ファセットない
				else{
					$Tr .= "<td{$Class}>{$i}</td>";
				}
				//月末の場合
				if ($i == $MyLastDay) {
					$Tr .= str_repeat('<td class="non">&nbsp;</td>', 6-$MyWeek).'</tr>';
				}
				//土曜日の場合に行終了
				if($MyWeek == 6 && $i !== $MyLastDay) {
					$Tr .= '</tr>';
				}

			}
			$DepMonth = $MyY . $MyM . '01';
			//月のファセットない
			if(empty($this->FacetObj->RetFacet['p_dep_month'][$DepMonth]['facet'])){
				$MonthView =<<<EOD
{$MyY}年{$MyM}月
EOD;
			}
			//ファセットある
			else{
				$ViewMonth = intval($MyM);
				$MonthView =<<<EOD
<a href="#" onclick="SWDate(\'{$MyY}/{$MyM}\');void(0);return false;">{$MyY}年{$ViewMonth}月</a>
EOD;
			}

			//テーブルに入れるよ
			$Table .=<<<EOD
<table class="SW_SD_Month OnFLeft JS_BtnParamSet">
<caption class="SW_SD_Caption">{$MonthView}</caption>
<tr>
<th class="sun">日</th>
<th>月</th>
<th>火</th>
<th>水</th>
<th>木</th>
<th>金</th>
<th class="sat">土</th>
</tr>
$Tr
</table>

EOD;

			/*divが終わります*/
			if($j % 3 == 2){	//区切りは3つ
				//今が最後だったら次へリンク表示
				if($j == 2){
					$TgNextMonth = date('Ym', mktime(0,0,0,$MyM+2,1,$MyY));
					$NextLink =<<<EOD
<a href="#" class="SW_CalNext" onclick="NextBackBtnAction({$TgNextMonth});void(0);return false;">→</a>
EOD;
				}

				$Table .=<<<EOD
<p class="SW_CalBtn OnFRight">{$NextLink}</p>
</div>
EOD;
			}
		}

		/*--------*/
		#	最初のとき
		/*--------*/
		$TgFile = $this->IncDirName . 'SubWin_' . $MyParam . '.php';
		//コース一覧の部分をバッファリング
		ob_start();
		include($TgFile);
		$ViewWin=ob_get_contents();
		ob_end_clean();
		//改行とタブトル
		$ViewWin = str_replace(array("\r\n","\n","\r","\t"), '', $ViewWin);
//$('span#{$this->MyNaigai}p_hit_num').html({$this->ResObj->p_hit_num});
		$Ret =<<<EOD
$('div#SubWinBox').html('{$ViewWin}');
EOD;

		return $Ret;
	}

}

/*
*******************************************************
	全拠点のファセット情報
*******************************************************
*/
class SearchActionForFacetDfree extends SearchActionDefaultDfree {
	//表示拠点のファセットフラグ '':無し、1:ある
	public $SelectFacet;
	//全拠点のファセットフラグ'':無し、1:ある
	public $allFacetFlg;
	//表示拠点以外のファセット
	public $OtherFacetHtmlEtc;

	function __construct($naigai,$kyotenCode,$RqPara ='', $flg=''){
		global $PathSharing, $GlobalSolrReqParamAry,$kyotenId;


		$this->dispKyotenId = $kyotenCode;
		$this->MyNaigai = $naigai;


		$kyotenListAry =new MakeKyotenSimpleGList;

		//出発地コード取得
		$FacetRqArray = $this->GetFacetRqArray($kyotenListAry);


		//全拠点ファセット情報取得
		$FasetdataAry = $this->GetSolr($FacetRqArray,$RqPara);

		//選択された拠点が0件かどうかの確認
		$this->SelectKyotenFacet($FasetdataAry);
		//他拠点の表示用
		$this->OtherFacetHtml($FasetdataAry);
		// その他拠点表示用
		$this->OtherFacetHtmlEtc($FasetdataAry);
	}

	//出発地コード取得
	function GetFacetRqArray($kyotenListAry = NULL) {
		// Facet用
		$FacetRqArray = array();

		// 配列作成
		if (is_object($kyotenListAry)) {
			foreach ($kyotenListAry->TgDataAry as $kyotencode => $kyotenname) {

				if ($joinStr = $this->GetFacetRqArrayChild($kyotencode)) {
					$FacetRqArray[$joinStr]['code'] = $kyotencode;
					$FacetRqArray[$joinStr]['name'] = $kyotenname;

				}
			}
		}


		return $FacetRqArray;
	}

	function GetFacetRqArrayChild($keyCode) {
		global $p_hatsuAry;

		$joinStr='';
		foreach ($p_hatsuAry->TgDataAry[$this->MyNaigai] as $kyotenCode => $hatsuAry) {
			// 孫コードと一致
			if ($keyCode == $kyotenCode) {
				foreach ($hatsuAry as $code => $subKyotenName) {
					// コード記録
					if (empty($joinStr) && !empty($keyCode)) {
						$joinStr = $code;
					}
					elseif (!empty($joinStr)) {
						$joinStr .= ',' . $code;
					}
				}
			}
		}
		return $joinStr;

	}


	// Facet分割
	/*function GetFacetSplit($KyotenDataAry = NULL, $FacetRqArray) {
		$main  = array();
		$other = array();

		if (is_array($KyotenDataAry["Sub"])) {
			foreach ($FacetRqArray as $fkey => $fvalue) {
				if (array_key_exists($fkey, $KyotenDataAry["Sub"])) {
					$main[$fkey] = $fvalue;
				}
				else {
					if ($fvalue["facet"] > 0) $other[$fkey] = $fvalue;
				}
			}
		}
		return array($main, $other);
	}*/

	// Facetその他表示用
	/*function OtherFacetHtmlEtc($dataAry){

		if(is_array($dataAry) && !empty($dataAry)){
			foreach($dataAry as $kyotenID => $data){
				if($this->dispKyotenId != $kyotenID){
				$li .=<<<EOD
<li><a onclick="SelectKyotenBtn('{$kyotenID}');return false;" href="javascript:void(0)">{$data['name']}</a></li>
EOD;
				}
			}

			$this->OtherFacetHtmlEtc =$li;
		}
		else{
			$this->OtherFacetHtmlEtc ='';
		}
	}
*/

	// Facetその他表示用
	function OtherFacetHtmlEtc($facetdataAry){
		global $GlobalMaster;

		if(empty($GlobalMaster['kyotenUse'])){
			new GM_kyotenUse;
		}
		foreach($GlobalMaster['kyotenUse'] as $dataAry){
			if($dataAry['naigai'] == 'i'){
				$this->depLinkAry[$dataAry['bigKyotenId']][$dataAry['kyotenId']]['bigKyoten']=$dataAry['bigKyoten'];
				$this->depLinkAry[$dataAry['bigKyotenId']][$dataAry['kyotenId']]['kyotenName']=$dataAry['kyotenName'];
			}
		}
			$dl='';
		foreach($this->depLinkAry as $bigKyotenId => $dataAry){
			$li ='';
			foreach($dataAry as  $kyotenId =>$data){
				if($facetdataAry[$kyotenId]['facet']>0){

					//拠点コードから県コードに当て変えてクッキーに保持
					foreach($GlobalMaster['kyotenUse'] as $kencodeAry){
						if($kencodeAry['kyotenId'] == $kyotenId && $kencodeAry['naigai'] == $this->MyNaigai){
							$id = $kencodeAry['kenCode'];
							break;
						}
					}

			$li .=<<<EOD
			<li><a href="javascript:void(0)" onclick="SelectKyotenBtn('{$id}');return false;">{$data['kyotenName']}発</a></li>
EOD;
				}
				else{
	$li .=<<<EOD
			<li class="nofacet">{$data['kyotenName']}発</li>
EOD;
				}
			}
			$dl =<<<EOD
			<dl class="depLink_{$bigKyotenId}">
			<dt>{$data['bigKyoten']}エリア</dt>

EOD;
$dlAll .=$dl.'<dd><ul>'.$li.'</ul></dd></dl>';
		}
		$this->OtherFacetHtmlEtc=$dlAll;
	}

	//全拠点ファセット取得
	function GetSolr($FacetRqAry,$Req){

		global $GlobalSolrReqParamAry,$flg;
		$this->allFacetFlg ='';
		$GlobalSolrReqParamAry[$this->MyNaigai]['p_mokuteki'] = NULL; //元がどこかの方面をもっているから強制的に削除
		$GlobalSolrReqParamAry['i']['p_hatsu'] = NULL;
		$GlobalSolrReqParamAry['d']['p_hatsu_sub'] =  NULL;

		if(!empty($Req)){

			/*$DestSplit = explode(',', $Req['preDest']);
			$p_mokuteki = NULL;
			if(count($DestSplit) > 1){	//複数方面（あんまりないはず）
				$cnt = 0;
				foreach($DestSplit as $val){
					if($cnt > 0){
						$p_mokuteki .= ',';
					}
					$p_mokuteki .= $val . '--';
					$cnt++;
				}
			}
			//方面はひとつ
			else{
				//国もチェックしないと
				$CountrySplit = explode(',', $Req['preCountry']);
				if(count($CountrySplit) > 1){	//複数国
					$cnt = 0;
					foreach($CountrySplit as $val){
						if($cnt > 0){
							$p_mokuteki .= ',';
						}
						$p_mokuteki .= $Req['preDest'] . '-' . $val . '-';
						$cnt++;
					}
				}
				else{
					$p_mokuteki = $Req['preDest'] . '-' . $Req['preCountry'] . '-' . $Req['preCity'];
				}
			}
			//何もないときはNULL
			if($p_mokuteki == '--'){
				$p_mokuteki = NULL;
			}*/
			if(!empty($Req['p_hatsu'])){
				$p_hatsu = $Req['p_hatsu'];
			}
			if(!empty($Req['p_mokuteki'])){
				$p_mokuteki = $Req['p_mokuteki'];
			}
			if(!empty($Req['p_bunrui'])){
				$BunruiCode= $Req['p_bunrui'];
			}
			if(!empty($Req['p_transport'])){
				$p_transport= $Req['p_transport'];
			}

		}

		if(!empty($FacetRqAry)){
			foreach($FacetRqAry as $p_hatsu => $kyotenNameAry){
				if($this->MyNaigai=='i'){
					$Request = array(
						'p_hatsu' => $p_hatsu
						,'p_mokuteki' => $p_mokuteki
						,'p_bunrui' => $BunruiCode
						,'p_transport'=>$p_transport
					);
				}
				else{
					$Request = array(
						'p_hatsu_sub' => $p_hatsu
						,'p_mokuteki' => $p_mokuteki
						,'p_bunrui' => $BunruiCode
						,'p_transport'=>$p_transport
					);
				}
				$this->ActRequestForSolr($Request);
				/*応答データ形式を指定*/
				$GlobalSolrReqParamAry[$this->MyNaigai]['p_data_kind'] = '1';	//ファセットのみ
				//返して欲しい項目は、内外別
				$GlobalSolrReqParamAry[$this->MyNaigai]['p_rtn_data'] = 'p_conductor,p_hatsu_name';	//ファセットを返してほしい項目

				/*DB通信*/
				$SolrObj = new SolrAccess($this->MyNaigai);	//solrのレスポンス：ママ

				/*エラー処理*/
				$this->ActErr($SolrObj);



				$otherFacet[$kyotenNameAry['code']]['name']=$kyotenNameAry['name'];
				$otherFacet[$kyotenNameAry['code']]['facet']=$this->ResObj->p_hit_num;
				//全拠点ファセットチェックフラグ
				if($this->allFacetFlg !=1 && $this->ResObj->p_hit_num >0){
					$this->allFacetFlg=1;
				}

			}

		}
		return $otherFacet;
	}
	//表示拠点のファセットあるか無しか
	function SelectKyotenFacet($dataAry){
		if(is_array($dataAry)){
			$data = $dataAry[$this->dispKyotenId];
			if($data['facet'] =="0"){
				$this->SelectFacet ='';
			}
			else{
				$this->SelectFacet ='1';
			}
		}
	}
	//マスターで管理している表示拠点
	function OtherFacetHtml($dataAry){
		global $GlobalMaster;

		if(is_array($dataAry)){

			if(empty($GlobalMaster['kyotenUse'])){
				new GM_kyotenUse;
			}
			$facetKyoten='';
			foreach($GlobalMaster['kyotenUse'] as $kencodeAry){
				if($kencodeAry['kyotenId'] == $this->dispKyotenId && $kencodeAry['naigai'] == $this->MyNaigai){
					$facetKyoten = $kencodeAry['facetKyotenId'];
					break;
				}
			}

			$facetKyotenAry = explode(',',$facetKyoten);
			foreach($dataAry as $kyotenID => $data){
				foreach($facetKyotenAry as $id){
					if($id == $kyotenID){
						$idpass = ucwords($kyotenID);
						foreach($GlobalMaster['kyotenUse'] as $kencodeAry){
							if($kencodeAry['kyotenId'] == $kyotenID && $kencodeAry['naigai'] == $this->MyNaigai){
								$kenid = $kencodeAry['kenCode'];
								break;
							}
						}
						$li .=<<<EOD
<dl>
<dt class="otBtn"><a onclick="SelectKyotenBtn('{$kenid}');return false;" href="javascript:void(0)"><img src="/sharing/common14/images/otBtn{$idpass}.png" alt="{$data['name']}発" class="imgover" /></a></dt>
<dd class="srchNum">商品数<em id="dp_hit_num">{$data['facet']}</em><span>件</span></dd>
</dl>
EOD;
					}
				}
			}
			$this->OtherFacet =$li;
		}
		else{
			$this->OtherFacet ='';
		}
	}
}

/*******************************************************
 * リクエストパラメータでsolrへアクセスし、返します
 *
 * 引数
 * 		$ReqParams	:array	：リクエストパラメータ
 *				※パラメータは基本、I/F定義書通り
 *				※自作パラメータ：UseType（そのページで使用するので返してほしい項目）
 *										値：TotalNum		：トータル件数
 *										値：TourList		：商品
 *										値：Dest				：方面のファセット
 *										値：Country			：国または都道府県のファセット
 *										値：City				：都市または観光地のファセット
 *				※自作パラメータ：MyNaigai	:str 		：内外（必須：i or d）
 *				※自作パラメータ：TourListCnt	:int 	：TourListが設定されていたときに何件返すか（デフォルト3件）
 * 返り値
 * 	$ResObj		：
*******************************************************/
class GetNumFacetTourDfree{
	#=======================
	#	初期設定
	#=======================
	public $ResObj;	//返却オブジェクト

	/*使うglobal変数*/
	//$GlobalSolrReqParamAry（solr_access.phpにあります）


	#=======================
	#	初動
	#=======================
	function __construct($ReqParams) {
		global $GlobalSolrReqParamAry;
		/*$ReqParamは配列毎に*/
		foreach($ReqParams as $AryNum => $ReqParam){
			//エラーの初期化
			$this->ErrFlg = NULL;
			/*p_rtn_dataの設定*/
			$RtnDataAry = array(
				 'Dest' => array('i'=>'p_dest_name', 'd'=>'p_dest_name')
				,'Country' => array('i'=>'p_country_name', 'd'=>'p_prefecture_name')
				,'City' => array('i'=>'p_city_cn', 'd'=>'p_region_cn')
			);

			/*受け取ったリクエストパラを、solrに渡す準備をします*/
			$UseTypeAry = $this->ActRequestForSolr($ReqParam, $RtnDataAry);	//$GlobalSolrReqParamAryに入った
			//エラーがあったら次へ
			if(!empty($this->ErrMes)){
				$this->ResObj[$AryNum]['ErrMes'] = $this->ErrMes;
				break;
			}

			/*DB通信*/
			$SolrObj = new SolrAccess($ReqParam['MyNaigai']);	//solrのレスポンスが入った
			//エラーがあったら次へ
			if($SolrObj->ErrFlg !== 0){
				$this->ResObj[$AryNum]['ErrMes'] = $SolrObj->Obj->response->p_result_detail;
				break;
			}

			/*ファセットを使いやすくします*/
			$FacetObj = new SetFacet($SolrObj->Obj->facet_counts->facet_fields);


			//トータル件数を返します
			if(in_array('TotalNum', $UseTypeAry)){
				$this->ResObj['TotalNum'] = $SolrObj->Obj->response->p_hit_num;
			}
			//ツアーを返します
			if(in_array('TourList', $UseTypeAry)){
				$this->ResObj['TourList'] = $SolrObj->Obj->response->docs;
			}
			//ファセットを返します
			foreach($RtnDataAry as $Name => $TgAry){
				//返してほしい内容があったら
				$TgFacetName = $TgAry[$ReqParam['MyNaigai']];
				if(!empty($FacetObj->RetFacet[$TgFacetName])){
					$this->ResObj[$Name] = $FacetObj->RetFacet[$TgFacetName];
				}
			}

		}


	}


	#=======================
	#	受け取ったリクエストパラを、solrに渡す準備をします
	#	レスポンス表示をする際の和名準備もします
	#=======================
	function ActRequestForSolr($ReqParam, $RtnDataAry){
		global $GlobalSolrReqParamAry;

		$MyNaigai = $ReqParam['MyNaigai'];
		/*グローバル変数に値を入れていく*/
		foreach($GlobalSolrReqParamAry[$MyNaigai] as $ParamName => $DefVal){
			/*値があるならグローバル変数へ*/
			if($ReqParam[$ParamName] !== NULL && $ReqParam[$ParamName] !== ''){
				$GlobalSolrReqParamAry[$MyNaigai][$ParamName] = $ReqParam[$ParamName];
			}
		}
		$ReturnAry = explode(',', $ReqParam['UseType']);

		/*p_data_kindの設定*/
		//ファセットがある
		if(in_array('City', $ReturnAry) || in_array('Country', $ReturnAry) || in_array('Dest', $ReturnAry)){
			//商品も返してほしい
			if(in_array('TourList', $ReturnAry)){
				$GlobalSolrReqParamAry[$MyNaigai]['p_data_kind'] = '3';
			}
			//ファセットのみ
			else{
				$GlobalSolrReqParamAry[$MyNaigai]['p_data_kind'] = '1';
			}
		}
		//ファセットは無い
		else{
			//商品を返してほしい
			if(in_array('TourList', $ReturnAry)){
				$GlobalSolrReqParamAry[$MyNaigai]['p_data_kind'] = '2';
			}
			//件数だけで良いんだけど
			elseif(in_array('TotalNum', $ReturnAry)){
				$GlobalSolrReqParamAry[$MyNaigai]['p_data_kind'] = '1';
				$GlobalSolrReqParamAry[$MyNaigai]['p_rtn_data'] = 'p_conductor';
			}
			//セットされてないじゃん
			else{
				//サヨナラ
				$this->ErrMes = 'UseTypeを正しく指定してください。';
				return $ReturnAry;
			}
		}

		//商品返すなら
		if(in_array('TourList', $ReturnAry)){
			//デフォルト3件
			$GlobalSolrReqParamAry[$MyNaigai]['p_rtn_count'] = '3';
			//カウント設定あれば
			if(!empty($ReqParam['TourListCnt'])){
				$GlobalSolrReqParamAry[$MyNaigai]['p_rtn_count'] = $ReqParam['TourListCnt'];
			}
		}

		foreach($RtnDataAry as $Name => $TgAry){
			//返してほしい内容があったら
			if(in_array($Name, $ReturnAry)){
				if(!empty($GlobalSolrReqParamAry[$MyNaigai]['p_rtn_data'])){
					$GlobalSolrReqParamAry[$MyNaigai]['p_rtn_data'] .= ',';
				}
				$GlobalSolrReqParamAry[$MyNaigai]['p_rtn_data'] .= $TgAry[$MyNaigai];
			}
		}

		return $ReturnAry;

	}
}
