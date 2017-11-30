<?php
/*
#################################################################
	サーチ専用のFnc集です
#################################################################
*/
include_once($_SERVER['DOCUMENT_ROOT'] . '/sharing/phpsc/path.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/sharing/phpsc/class_memcacheSolr.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/sharing/phpsc/read_master.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/sharing/phpsc/func.php');
include_once($SharingPSPath .'CoreSolrAccess.php');	

include_once(dirname(__FILE__) . '/CourseList.php');
include_once($SharingPSPath .'FacetRewrite.php');

/*
*******************************************************
	検索ページに最初に入ってきたときに呼び出す
*******************************************************
	検索ページ読み込み時に走るクラスです。
	必要な変数は、このクラスの中にまとめます。
*/
class LoadAction {
	#=======================
	#	初期設定
	#=======================
	public $MyNaigai;	//内外判定
	public $RqParamAry_forView;	//表示に使うリクエストパラ
	public $ResObj;	//商品部分ママ返却
	public $FacetObj;	//見やすくなったFacet
	public $StatusObj;	//見やすくなった統計情報
	public $dispObj;	//表示用全データ
	public $reqPara;	//表示用全データ
	public $naigai;	//表示用全データ

	#=======================
	#	クラス読み込み時に動く
	#=======================
	function __construct() {
		global $GlobalSolrReqParamAry,$flgiFree;


		/*受け取ったリクエストパラを、solrに渡す準備をします*/
		$Set = new SetSolrParam();
		$this->naigai = $Set->naigai;
		$this->reqPara = $Set->solrReqParamAry[$this->naigai];

		if (isset($this->reqPara['p_hatsu'])) {
			$this->reqPara['p_hatsu_local'] = $this->reqPara['p_hatsu'];
		}

		/*応答データ形式を指定*/
		$this->reqPara['p_data_kind'] = '3';	//ファセット＋商品

		//返して欲しい項目は、内外別
		if($this->naigai == 'i' && empty($this->reqPara['p_rtn_data'])){
			//ファセットを返してほしい項目
			//$this->reqPara['p_rtn_data'] = 'p_hatsu_name,p_dest_name,p_country_name,p_city_cn,p_kikan,p_price_flg,p_conductor,p_carr_cn,p_timezone,p_seatclass,p_mainbrand,p_hotel_name,p_discount,p_bunrui,p_stock,p_decide,p_web_conclusion_flag';
			$this->reqPara['p_rtn_data'] = 'p_web_conclusion_flag,p_early_discount_flag';

		}
		else if($this->naigai == 'd' && empty($this->reqPara['p_rtn_data'])){
			//こっちは国内
			$this->reqPara['p_rtn_data'] = 'p_hatsu_name,p_dest_name,p_prefecture_name,p_region_cn,p_kikan,p_price_flg,p_conductor,p_carr,p_carr_cn,p_transport,p_mainbrand,p_bunrui,p_bus_boarding_name,p_stock,p_decide,p_hotel_name,p_hei,p_type,p_brand,p_web_conclusion_flag,p_dep_airport_name,p_dep_dest_name,p_dep_prefecture_name,p_dep_region_name,p_ins_prefecture_code,p_ins_area_code,p_stay_number,p_stay_term_to,p_syohaku_hotel_code,p_early_discount_flag,p_accommodation_name';
		}
		/*DB通信*/
		$SolrObj = new CoreSolrAccess($this->reqPara,$this->naigai);
		$this->FacetPlane = $SolrObj->Obj->facet_counts->facet_fields;

		//ファセット加工
		$FacetRewriteObj = new FacetRewrite($SolrObj->Obj->facet_counts->facet_fields,$this->naigai,'array');
		$facet = $FacetRewriteObj->retFacet;

		//$this->dispObj['reqPara'] = $this->reqPara;

		foreach($this->reqPara as $paramName => $paramData){
			if(empty($paramData)){
				$this->RqParamAry_forView[$paramName] = '';
				continue;
			}
			$this->RqParamAry_forView[$paramName] = $paramData;

			if(strpos($paramName,'p_hatsu_sub') === false){
				continue;
			}
			$ClassName = 'Box_' . $paramName;
			$file =  dirname(__FILE__) . '/Box/' . $ClassName . '.php';
			if(is_file($file)){
			include_once($file);
			if(class_exists($ClassName)){	//クラスが存在したら実行
				$obj = new $ClassName($facet,$this->reqPara);
				$this->dispObj['hatsuObj'] = $obj->hatsuObj;
			}
		}
	}
	/*エラー処理*/
	$this->ActErr($SolrObj);


	//表示用データ作成
	$this->makeDispData($SolrObj);


	}
	/*********************************************
 	* makeDispData()　表示用データ作成
	*
  * 引数
	* 	$SolrObj		:Solr オブジェクト
  *  返り値
	* 	：応答結果、ページャー、検索結果など
  **********************************************/
	function makeDispData($SolrObj){

		if($SolrObj->ErrFlg == false){
			//正常ルート
			if($SolrObj->Obj->response->p_hit_num > 0){
				//0件以上

				//該当数、価格帯、旅行日数、開始行、ソート条件 jsonデータ
				$this->dispObj['p_hit_num'] = $SolrObj->Obj->response->p_hit_num;
				//$this->dispObj['allPrice'] = YoriMade($SolrObj->Obj->stats->stats_field->p_all_price_min, $SolrObj->Obj->stats->stats_field->p_all_price_max, '円', '−');
				//$this->dispObj['allKikan'] = YoriMade($SolrObj->Obj->stats->stats_field->p_all_kikan_min, $SolrObj->Obj->stats->stats_field->p_all_kikan_max, '日間', '−');
				$this->dispObj['p_start_line'] = $SolrObj->solrReqParamAry[$Set->naigai]['p_start_line'];
				$this->dispObj['p_sort'] = $SolrObj->solrReqParamAry[$Set->naigai]['p_sort'];

				//商品一覧表示
				$tmp = '';

				$CourseListObj =  new CourseList($SolrObj->Obj->response->docs,$this->naigai);
				$this->dispObj['tourList'] = $SolrObj->Obj->response->docs;

			}
			else{
				//0件の場合
				$this->dispObj['p_hit_num'] = $SolrObj->Obj->response->p_hit_num;
				//$this->dispObj['allPrice'] = '−';
				//$this->dispObj['allKikan'] = '−';
				$this->dispObj['ErrTitle'] = '検索結果は0件です';
				$this->dispObj['ErrMes'] = 'お探しの条件でコースが存在しません。検索条件を変更してください。';
				ob_start();
				include(dirname(__FILE__) . '/../inc/TempCourseErr.php');
				$tmp = ob_get_contents();
				ob_end_clean();
				$this->dispObj['html'] = $tmp;
			}
		}else{
			//異常ルート
			$this->dispObj['p_hit_num'] = 0;
			//$this->dispObj['allPrice'] = '−';
			//$this->dispObj['allKikan'] = '−';
			$this->dispObj['ErrTitle'] = 'システムエラーが発生しました';
			$this->dispObj['ErrMes'] = MyEcho($this->ErrObj);
			$this->dispObj['ErrMes'] = str_replace(array("\r\n","\n","\r"), '<br />', $this->dispObj['ErrMes']);
			$this->dispObj['ErrMes'] = str_replace(',', ', ', $this->dispObj['ErrMes']);
			$this->dispObj['ErrMes'] = str_replace(array('エラーメッセージ：','パラメータ：'), array('<em>','</em>設定パラメータ：'), $this->dispObj['ErrMes']);
			ob_start();
			include(dirname(__FILE__) . '/../inc/TempCourseErr.php');
			$tmp = ob_get_contents();
			ob_end_clean();
			$this->dispObj['html'] = $tmp;
		}

		//初回リクエストパラメータjson生成 内外と、チェンジフラグも追加
		//$this->dispObj['reqJson'] = '';
		if(count($_GET) > 0 || count($_POST) > 0){
			$reqJson = $this->reqPara;
			$reqJson['MyNaigai'] = $this->naigai;
			$reqJson['Changed'] = '';
			//$this->dispObj['reqJson'] = json_encode($reqJson);
		}

		//ページャ作成
		$this->dispObj['pager'] = $this->pager($SolrObj->Obj->response->p_hit_num, $this->reqPara['p_start_line']);

		//リクエスト和名生成
		$this->makeReqName();
		$this->dispObj['criteria'] = $this->RqParamAry_forView;

	}

	function pager($hit_num, $start_line =1, $perpage=10, $range=5){
		if($start_line == NULL || $start_line == '' || empty($start_line)){
			$start_line = 1;
		}
		//第3引数：最大表示件数、第4引数：全部で何個表示するか
		$RetPager = Pager::GetPager($hit_num, $start_line, $perpage,$range);

		//1ページしか無かったらページャー非表示
		if($RetPager['total_pages'] == 1){
			return;
		}
		//前へ
		if(!empty($RetPager['prev'])){
			$PrevCnt = ($RetPager['prev'] * $perpage) - $perpage +1;
			$RetPagerAry['prev'] = $PrevCnt;
		}
		//次へ
		if(!empty($RetPager['next'])){
			$NextCnt = ($RetPager['next'] * $perpage) -$perpage +1;
			$RetPagerAry['next'] = $NextCnt;
		}
		$RetPagerAry['total_pages'] = $RetPager['total_pages'];
		$RetPagerAry['curpage'] = $RetPager['curpage'];
		return $RetPagerAry;
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
			$this->StatusObj = new SetStatus($SolrObj->Obj->stats->stats_field);
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
	#	選択条件表示用
	#=======================
	function makeReqName(){
		global $flgiFree;

		//foreach($this->RqParamAry_forView as $Param => $KeyAry){
		foreach($this->reqPara as $Param => $KeyStr){
			//つまり、何らかのパラメータがセットされているとき
			if($KeyStr || $KeyStr === 0 || $KeyStr === "0"){
				/*ファセットの名前とリクエストの名前が違うやつらは、$CheckParamNameに入れる*/
				$CheckParamName = $Param;

				//値を配列にする
				$KeyAry = explode(',',$KeyStr);

				switch ($Param) {
					/*このパターンでは、HierarchyCode_'パラメータ名'クラスから作ったマスタより取得*/
					case 'p_hatsu':
					case 'p_hatsu_sub':
						/*マスタにあるかどうか探す*/
						$this->GetNameFromCustumMaster($Param, $KeyStr, $this->naigai);
						break;
					/*↑と同じだけど、内外が無いバージョン*/
					case 'p_bunrui':
//						foreach($KeyAry as $Key => $Val){
//							$this->GetNameFromCustumMaster($Param, $Key);
//						}
						$this->RqParamAry_forView[$Param] = '';
						$this->GetNameFromCustumMaster($Param, $KeyStr);
						break;
					/*ホテルはマスタから探す（都市属性が分からないから）*/
					case 'p_accommodation_code':

						//国内 ファセットから取得
						$this->RqParamAry_forView[$Param] = '';

						foreach($this->FacetPlane->p_accommodation_name as $k => $val){
							$dataAry = explode(',',$val);
							array_pop($dataAry);
							$name = array_pop($dataAry);
							$code = array_pop($dataAry);
							$hotelAry[$code] = $name;
						}

						foreach($KeyAry as $Key => $Val){
							if($this->RqParamAry_forView[$Param]){
								$this->RqParamAry_forView[$Param] .= '、' . $hotelAry[$Val];
							}else{
								$this->RqParamAry_forView[$Param] .= $hotelAry[$Val];
							}
						}

						break;
					case 'p_hotel_code':
						if($this->naigai == 'i'){
							/*
							 //海外フリープランホテル用
							 if($flgiFree ==1 && $Param=='p_hotel_code'){
							 foreach($KeyAry as $Key => $Val){
							 $this->Act_p_hotel_name($Param,$Val);
							 }
							 break;
							 }else{
							 //海外
							 $this->GetNameFromMaster($Param, $KeyStr, $this->naigai);
							 }
							 */
							//ファセットから取得
							$this->RqParamAry_forView[$Param] = '';

							foreach($this->FacetPlane->p_hotel_name as $k => $val){
								$dataAry = explode(',',$val);
								$code = $dataAry[0];
								$name = mb_convert_kana($dataAry[1],"aKCV","utf-8");
								$hotelAry[$code] = $name;
							}

							foreach($KeyAry as $Key => $Val){
								if($this->RqParamAry_forView[$Param]){
									$this->RqParamAry_forView[$Param] .= '、' . $hotelAry[$Val];
								}else{
									$this->RqParamAry_forView[$Param] .= $hotelAry[$Val];
								}
							}

						}else{
							//国内 ファセットから取得
							$this->RqParamAry_forView[$Param] = '';

							foreach($this->FacetPlane->p_hotel_name as $k => $val){
								$dataAry = explode(',',$val);
								$name = array_pop($dataAry);
								$code = array_pop($dataAry);
								$hotelAry[$code] = $name;
							}

							foreach($KeyAry as $Key => $Val){
								if($this->RqParamAry_forView[$Param]){
									$this->RqParamAry_forView[$Param] .= '、' . $hotelAry[$Val];
								}else{
									$this->RqParamAry_forView[$Param] .= $hotelAry[$Val];
								}
							}
							/*
							 foreach($KeyAry as $Key => $Val){
							 $MyFacetFlg = NULL;
							 $MyFacetFlg = $this->GetNameFromFacet($Param, 'p_hotel_name', $Val);
							 }
							 */
						}
						break;
					case 'p_conductor':
					case 'p_decide':
						//海外フリープランホテル用
						if($flgiFree ==1 && $Param=='p_hotel_code'){
							foreach($KeyAry as $Key => $Val){
								$this->Act_p_hotel_name($Param,$Val);
							}
							break;
						}
						$this->GetNameFromMaster($Param, $KeyStr, $this->naigai);
						break;
					/*↑と同じだけど、内外が無いバージョン。てか、ちびっこマスターズ*/
					case 'p_seatclass':
					case 'p_stock':
					case 'p_discount':
					case 'p_timezone':
					case 'p_total_amount_divide':
					case 'p_mainbrand':
					case 'p_transport':
					case 'p_web_conclusion_flag':
					case 'p_early_discount_flag':
							$this->GetNameFromMaster($Param, $KeyStr);
						break;

					/*ファセットから取得して、無かったらマスタを見るパターン*/
					case 'p_carr':
						$CheckParamName = $Param . '_cn';
						$this->RqParamAry_forView[$Param] = '';
						foreach($KeyAry as $Key => $Val){
							$MyFacetFlg = NULL;
							$MyFacetFlg = $this->GetNameFromFacet($Param, $CheckParamName, $Val);
						}
						//ファセットで無かったらマスタへ
						if($MyFacetFlg == 'None'){
							$this->GetNameFromMaster($Param, $KeyStr, $this->naigai);
						}
						break;
					/*ファセットから取得して、無かったらマスタを見るパターン：目的地はチョット特別*/
					case 'p_mokuteki':
						$tmp = array();
						//内外は別だからチェック
						foreach($KeyAry as $Key => $Val){
							$addStr = '';
							list($p_dest_name, $p_country_name, $p_city_name) = explode('-', $Val);
							//***************方面***************
							$CheckParamName = 'p_dest_name';
							if(!empty($this->FacetObj->RetFacet[$CheckParamName][$p_dest_name])){
								/*まずファセット*/
								$this->RqParamAry_forView[$Param][$Val] = $this->FacetObj->RetFacet[$CheckParamName][$p_dest_name]['name'];
								if($tmp[$Param]){
									$tmp[$Param] .= '、方面：' . $this->FacetObj->RetFacet[$CheckParamName][$p_dest_name]['name'];
								}else{
									$tmp[$Param] .= '方面：' . $this->FacetObj->RetFacet[$CheckParamName][$p_dest_name]['name'];
								}
							}
							elseif(!empty($p_dest_name)){
								/*無かったらマスタにあるかどうか探す*/
								$this->GetNameFromMasterMokuteki('p_dest', $CheckParamName, $p_dest_name, $Val, $this->naigai);
							}
							//***************国***************
							if($this->naigai == 'i'){
								$CheckParamName = 'p_country_name';
								$addStr = '　国：';
							}
							else{
								$CheckParamName = 'p_prefecture_name';
								$addStr = '　都道府県：';
							}
							if(!empty($this->FacetObj->RetFacet[$CheckParamName][$p_dest_name][$p_country_name])){
								$this->RqParamAry_forView[$Param][$Val] = $this->FacetObj->RetFacet[$CheckParamName][$p_dest_name][$p_country_name]['name'];
								$tmp[$Param] .= $addStr . $this->FacetObj->RetFacet[$CheckParamName][$p_dest_name][$p_country_name]['name'];

							}
							elseif(!empty($p_country_name)){
								/*無かったらマスタにあるかどうか探す*/
								$this->GetNameFromMasterMokuteki('p_country', $CheckParamName, $p_country_name, $Val, $this->naigai);
							}
							//***************都市***************
							if($this->naigai == 'i'){
								$CheckParamName = 'p_city_cn';
								$addStr = '　都市：';
							}
							else{
								$CheckParamName = 'p_region_cn';
								$addStr = '　宿泊/観光地：';
							}
							if(!empty($this->FacetObj->RetFacet[$CheckParamName][$p_dest_name][$p_country_name][$p_city_name])){
								$this->RqParamAry_forView[$Param][$Val] = $this->FacetObj->RetFacet[$CheckParamName][$p_dest_name][$p_country_name][$p_city_name]['name'];
								$tmp[$Param] .= $addStr . $this->FacetObj->RetFacet[$CheckParamName][$p_dest_name][$p_country_name][$p_city_name]['name'];
							}
							elseif(!empty($p_city_name)){
								/*無かったらマスタにあるかどうか探す*/
								$this->GetNameFromMasterMokuteki('p_city', $CheckParamName, $p_city_name, $Val, $this->naigai);
							}
						}
						$this->RqParamAry_forView[$Param] = $tmp[$Param];
						break;

					/*ファセットから取得するパターン*/
					case 'p_bus_boarding_code':
					case 'p_dep_airport_code':
					case 'p_arr_airport_code':
						//codeはnameに変換
						$CheckParamName = str_replace('_code', '_name', $Param);

						$this->RqParamAry_forView[$Param] = '';
						foreach($KeyAry as $Key => $Val){
							$MyFacetFlg = NULL;
							if($Param == 'p_bus_boarding_code'){
								$MyFacetFlg = $this->GetNameFromFacetBus($Param, $CheckParamName, $Val);
							}
							else{
								$MyFacetFlg = $this->GetNameFromFacet($Param, $CheckParamName, $Val);
							}
						}
						break;

					/*その他のパラメータは、するぅーーーーー*/
				}
			}else{
				$this->RqParamAry_forView[$Param] = '';
			}
		}
	}


	/*--------*/
	#	p_hotel_code海外フリー用
	/*--------*/
	function Act_p_hotel_name($MyParam,$code){
		global $GlobalSolrReqParamAry,$GlobalMaster;

		new GM_p_hotel_rank_code;//ホテルランクの和名
		$FacetRetParamName = 'p_hotel_name';	//ファセットの応答パラ
		//メムキャッシュで取るパラメータ
		$MemcachePara = array(
			'p_dest_name'
			,'p_country_name'
			,'p_city_cn'
		);

		//キャッシュ取得
		$memGet = new GetMemcacheSolr($MemcachePara,'i');

		foreach($MemcachePara as $paraKey){
			if(empty($memGet->Result[$paraKey])){
				$memSet = new SetMemcacheSolr('i');
				$memSet->SetMemcache($paraKey);
			}
			${$paraKey.'_mem'} = $memGet->Result[$paraKey];
			$this->ActMakeMemData($paraKey,${$paraKey.'_mem'});
		}

		//ファセットで配列作成
		if(is_array($this->FacetPlane->$FacetRetParamName)){

			$data = $this->FacetPlane->$FacetRetParamName;
			foreach($data as $Num => $Val){
				//奇数の場合は値だから飛ばす
				if($Num % 2 == 1){
					continue;
				}
				if($_REQUEST['MTRDispFlg']){
					$SettingData->SettingAey['MTRDispFlg'] = 1;
				}
				//特定のパラメータは除外 MTRDispFlgはsettingで設定してる
				if(strpos($Val,'MTR') !== false && $SettingData->SettingAey['MTRDispFlg'] == false){
					continue;
				}
				list($Code,$Name,$Rank,$City) = explode(',', $Val);

				if(empty($Rank)){
					$Rank ='ZZZZ';
				}

				//ファセットが0じゃない
				if($data[$Num+1] !="0"){
					$this->HotelFacet[$City][$Rank][$Code] = array(
					 'name' => $Name
					,'facet' => $data[$Num+1]
					);
				}
			}

			//ホテルのコードと名前用
			foreach($data as $Num => $Val){
				if($_REQUEST['MTRDispFlg']){
					$SettingData->SettingAey['MTRDispFlg'] = 1;
				}
				//特定のパラメータは除外 MTRDispFlgはsettingで設定してる
				if(strpos($Val,'MTR') !== false && $SettingData->SettingAey['MTRDispFlg'] == false){
					continue;
				}
				$dataAry = explode(',', $Val);
				if(!empty($dataAry[0]) && $dataAry[1]){
					$hotelListAry[$dataAry[0]]=$dataAry[1];
				}
			}

			$MyParam ='p_hotel_code';

			//ホテルファセットのエリアコードから目的のファセットで配列作成

			if(is_array($this->Facet_p_city_nameAry) && is_array($this->HotelFacet) && is_array($this->Facet_p_country_nameAry)){
				$MokutekiHotelAry = array();
				$ValidHotelCnt = 0;


				foreach($this->Facet_p_city_nameAry as $destcode => $contryAry){
					foreach($contryAry as $contrycode => $cityAry){
						foreach($cityAry as $citycode => $cityname){
							foreach($this->RqParamAry_forView[$MyParam] as $Key => $Val){
								$this->RqParamAry_forView[$MyParam][$code]['name']=$hotelListAry[$code];
							}
						}
					}
				}
			}
		}
		else{
			//ファセットがない
			$this->RqParamAry_forView[$MyParam][$code]['name']='';
		}
	}

	#=======================
	#	和名を探す：グローバルマスタ編
	#		引数：パラメータ名, コード, [内外]
	#		使用パラ：p_conductor、p_carr、p_hotel
	#=======================
	function GetNameFromMaster($Param, $KeyStr, $Naigai=NULL){
		global $GlobalMaster;

		/*マスタがあるかどうかチェック*/
		if(empty($GlobalMaster[$Param])){
			$ClassName = 'GM_' . $Param;
			new $ClassName($Naigai);
		}
		/*判定開始*/
		$TgObj = $GlobalMaster[$Param];

		$KeyAry = explode(',',$KeyStr);
		$tmp = array();

		foreach($KeyAry as $Key)	{
			//内外無し
			if(!empty($TgObj[$Key])){
				if($tmp[$Param]){
					$tmp[$Param] .=  '、' . $TgObj[$Key];
				}else{
					$tmp[$Param] .=  $TgObj[$Key];
				}
			}
			//内外付き
			elseif(!empty($TgObj[$Naigai][$Key])){
				if($tmp[$Param]){
					$tmp[$Param] .=  '、' . $TgObj[$Naigai][$Key];
				}else{
					$tmp[$Param] .=  $TgObj[$Naigai][$Key];
				}
			}
		}
		$this->RqParamAry_forView[$Param] = $tmp[$Param];
	}

	#=======================
	#	和名を探す：グローバルマスタ編
	#		引数：パラメータ名, コード, [内外]
	#		使用パラ：p_hotel
	#		　でもって、これは返値へ
	#=======================
	function GetNameFromMasterRet($Param, $Key, $Naigai=NULL){
		global $GlobalMaster;
		/*マスタがあるかどうかチェック*/
		if(empty($GlobalMaster[$Param])){
			$ClassName = 'GM_' . $Param;
			new $ClassName($Naigai);
		}
		/*判定開始*/
		$TgObj = $GlobalMaster[$Param];

		//内外無し
		if(!empty($TgObj[$Key])){
			return $TgObj[$Key];
		}
		//内外付き
		elseif(!empty($TgObj[$Naigai][$Key])){
			return $TgObj[$Naigai][$Key];
		}
	}


	#=======================
	#	和名を探す：カスタムマスタ編
	#		引数：パラメータ名, コード, [内外]
	#		使用パラ：p_hatsu, p_bunrui, p_hatsu_sub
	#=======================
	function GetNameFromCustumMaster($Param, $KeyStr, $Naigai=NULL){


		/*自分用マスタがあるかどうかチェック*/
		$ObjName = 'CustumMaster_' . $Param;
		if(empty($this->$ObjName)){
			/*無かったらマスタを作る*/
			$ClassName = 'HierarchyCode_' . $Param;
			$Obj = new $ClassName;
			$this->$ObjName = $Obj->TgDataAry;
		}

		$KeyAry = explode(',',$KeyStr);
		/*判定開始*/
		$TgObj = $this->$ObjName;
		$tmp = '';
		if($Naigai == NULL){

			foreach($KeyAry as $Key)	{
				if(!empty($TgObj[$Key])){
					if($tmp){
						$tmp .=  '、' . $TgObj[$Key];
					}else{
						$tmp .=  $TgObj[$Key];
					}
				}
			}
			$this->RqParamAry_forView[$Param] = $tmp;
		}
		//内外の別があるバージョン
		else{
			foreach($KeyAry as $Key)	{
				if(!empty($TgObj[$this->naigai][$Key])){
					if($tmp){
						$tmp .=  '、' . $TgObj[$Naigai][$Key];
					}else{
						$tmp .=  $TgObj[$Naigai][$Key];
					}
				}
			}
			$this->RqParamAry_forView[$Param] = $tmp;
		}
	}

	#=======================
	#	和名を探す：マスタ編（方面だけは特別）
	#		引数：パラメータ名, コード, [内外]
	#=======================
	function GetNameFromMasterMokuteki($ParamName, $ViewParamName, $MyKey, $Key, $Naigai){
		global $GlobalMaster;
		/*マスタがあるかどうかチェック*/
		if(empty($GlobalMaster[$ParamName][$Naigai])){
			new GM_DestinationList($Naigai);
		}
		/*判定開始*/
		$TgObj = $GlobalMaster[$ParamName][$Naigai];

		if(!empty($TgObj[$MyKey])){
			//$this->RqParamAry_forView['p_mokuteki'][$Key][$ViewParamName] = $TgObj[$MyKey];
			$this->RqParamAry_forView['p_mokuteki']["'".$Key."'"] = $TgObj[$MyKey];
		}
	}

	#=======================
	#	和名を探す：ファセット編
	#=======================
	function GetNameFromFacet($Param, $CheckParamName, $Key){

	if(!empty($this->FacetObj->RetFacet[$CheckParamName][$Key])){
			//$this->RqParamAry_forView[$Param][$Key] = $this->FacetObj->RetFacet[$CheckParamName][$Key]['name'];
			if($this->RqParamAry_forView[$Param]){
				$this->RqParamAry_forView[$Param] .=  '、' . $this->FacetObj->RetFacet[$CheckParamName][$Key]['name'];
			}else{
				$this->RqParamAry_forView[$Param] = $this->FacetObj->RetFacet[$CheckParamName][$Key]['name'];
			}
		}
		else{
			return 'None';	//ファセットに無かったことを教える
		}
		//nameだけNULLのときがある
		//if(empty($this->RqParamAry_forView[$Param][$Key]) && $this->RqParamAry_forView[$Param][$Key] !== 0 && $this->RqParamAry_forView[$Param][$Key] !== '0'){
		if(empty($this->RqParamAry_forView[$Param]) && $this->RqParamAry_forView[$Param] !== 0 && $this->RqParamAry_forView[$Param] !== '0'){

			return 'None';	//ファセットに無かったことを教える
		}
	}

	#=======================
	#	和名を探す：ファセット編（バス乗車地ちょっと特殊）
	#		出発空港もここに追加になった……Busなんて名前付けるもんじゃないね。。。2012/11/15
	#=======================
	function GetNameFromFacetBus($Param, $CheckParamName, $Key){
		//ファセットが無い
		if($this->FacetObj->RetFacet[$CheckParamName] == NULL){
			return 'None';	//ファセットに無かったことを教えてサヨナラ
		}
		//ファセットある
		foreach($this->FacetObj->RetFacet[$CheckParamName] as $PrefectureCode => $BusAry){
			//見つかったらストップ
			if(!empty($BusAry[$Key])){
				if($this->RqParamAry_forView[$Param]){
					$this->RqParamAry_forView[$Param] .= '、' . $BusAry[$Key]['name'];
				}else{
					$this->RqParamAry_forView[$Param] .= $BusAry[$Key]['name'];
				}
				$Flg = 1;
				break;
			}
		}
		//やっぱり無い
		if(empty($Flg)){
			return 'None';	//ファセットに無かったことを教えてサヨナラ
		}
	}



	#=======================
	#	受け取ったリクエストパラを、solrに渡す準備をします
	#	レスポンス表示をする際の和名準備もします
	#=======================
	function ActRequestForSolr(){

		global $GlobalSolrReqParamAry,$flgiFree;

		/*処理するリクエストパラ*/
//		if((($_SERVER['HTTP_REFERER'] == NULL || strpos($_SERVER['HTTP_REFERER'], '/search/') === false) && strpos($_SERVER['SCRIPT_FILENAME'], '/naka_') === false) && empty($_GET['status'])){
//			$Request = $_POST;
//		}else{
			$Request = $_REQUEST;
	//	}

		/*自分は海外or国内*/
		if(stripos($_SERVER['SCRIPT_NAME'], 'i.php')){
			$this->MyNaigai = 'i';
		}

		elseif(stripos($_SERVER['SCRIPT_NAME'], 'ifree.php')){
			$this->MyNaigai = 'i';
			$flgiFree =1;
			$GlobalSolrReqParamAry[$this->MyNaigai]['p_bunrui'] ='030';
		}
		else{
			$this->MyNaigai = 'd';
		}
		if($Request['ifree']){
			$flgiFree =1;
		}
		//海外ホテル用にパラメータ変更
		if($Request['p_hotel_name']){
			$Request['p_hotel_code'] = $Request['p_hotel_name'];
		}

		/*グローバル変数に値を入れていく*/
		/*表示用も一緒に作る*/
		foreach($GlobalSolrReqParamAry[$this->MyNaigai] as $ParamName => $DefVal){
			if($Request[$ParamName] == NULL){
					$this->RqParamAry_forView[$ParamName]['Null'] = '未選択';
			}
			else{
				/*値があるならグローバル変数へ*/
				//checkboxで来るタイプ

				if(is_array($Request[$ParamName])){
					//NULL対策でimplodeは使わない
					$SepaValPre = NULL;
					foreach($Request[$ParamName] as $SepaVal){
						if($SepaVal !== 0 && $SepaVal !== '0' && empty($SepaVal)){
							continue;
						}
						if($SepaValPre !== NULL){
							$SepaValPre .= ',';
						}
						$SepaValPre .= $SepaVal;
					}
					$Request[$ParamName] = $SepaValPre;
				}

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

				/*表示用はカンマ区切りの処理*/
				/*カンマの値がNULLのときがある！最後の砦です*/
				$ExVal = explode(',', $Request[$ParamName]);

				$PreReqParamAry = NULL;
				foreach($ExVal as $MyExVal){
					if($MyExVal === NULL){
						continue;
					}

					$this->RqParamAry_forView[$ParamName][$MyExVal] = '';
					$PreReqParamAry[] = $MyExVal;
				}

				$GlobalSolrReqParamAry[$this->MyNaigai][$ParamName] = implode(',', $PreReqParamAry);
				//海外フリープラン用に付ける
				if($ParamName == 'p_bunrui' && $Request['ifree'] == 1){
					$GlobalSolrReqParamAry['i']['p_bunrui'] .= ',030';
				}
			}
		}
	}

	#=======================
	#	memcacheからの取得データ処理
	#=======================
	function ActMakeMemData($para,$paraDataAry){

		switch ($para){
			case 'p_dest_name':
				if(is_array($paraDataAry)){
					foreach($paraDataAry as $num => $arrayData){

						if($num % 2 == 1){
							continue;
						}
						$array =explode(',',$arrayData);
						$this->{$para.'_mem'}[$array[0]] = $array[1];

					}
					//方面
					$this->Facet_p_dest_nameAry = $this->{$para.'_mem'};
				}
				else{
					$this->Facet_p_dest_nameAry = '';
				}
			break;
			case 'p_country_name':
				if(is_array($paraDataAry)){
					foreach($paraDataAry as $num => $arrayData){

						if($num % 2 == 1){
							continue;
						}
						$array =explode(',',$arrayData);
						$this->{$para.'_mem'}[$array[0]][$array[1]] = $array[2];

					}
					//国
					$this->Facet_p_country_nameAry = $this->{$para.'_mem'};
				}
				else{
					//国
					$this->Facet_p_country_nameAry = '';
				}
			break;
			case 'p_city_cn':
				if(is_array($paraDataAry)){
					foreach($paraDataAry as $num => $arrayData){
						if($num % 2 == 1){
							continue;
						}
						$array =explode(',',$arrayData);
						$this->{$para.'_mem'}[$array[0]][$array[1]][$array[2]] = $array[3];
					}
					//都市
					$this->Facet_p_city_nameAry = $this->{$para.'_mem'};
				}else{
					$this->Facet_p_city_nameAry = '';
				}
			break;
		}
	}
}

/*
*******************************************************
	検索ページに最初に入ってきたときに、下半分を作る
	ための変数を作る。
*******************************************************
*/

include_once($_SERVER['DOCUMENT_ROOT'] . '/sharing/phpsc/class_searchCourseList.php');


?>
