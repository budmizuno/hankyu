<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/sharing/phpsc/read_master.php');
include_once($SharingPSPath .'FacetRewrite.php');



/*********************************************
 * GetFacet　必要な応答パラメータのファセットデータを加工
 *********************************************/
class GetFacet{
	public $naigai;					//内外
	public $request;				//$_REQUEST
	public $reqPara;				//セットされた要求パラメータ
	public $facet;					//使いやすいファセット
	public $rtnData;				//ファセットが必要なパラメータ
	public $reqMyAry;				//リクエストの値 複数の場合もあるので配列

	public $out;							//キー、日本語名、ファセット、選択状態 exe $out['p_mainbrand'][0]->'key'=>'01','name'=>'トラピックス','facet'=>1,'check'=>false
	public $outTFAry;					//項目毎にファセットがあるか exe $this->outTFAry['p_conductor'] -> 'TF'=>true　（添乗員のファセットがある場合）
	public $outSelectOnlyAry;	//選択された項目のみ

	function __construct($Request,$SolrObj,$naigai) {
		$this->init($Request,$SolrObj,$naigai);
		$this->makeRtnReqAry();
		$this->main();
	}
	function init($Request,$SolrObj,$naigai){
		$this->naigai = $naigai;
		$this->request = $Request;
		$this->reqPara = $SolrObj->solrReqParamAry;
		//必要なファセットのみ加工
		$Fr = new FacetRewrite($SolrObj->Obj->facet_counts->facet_fields,$this->naigai,'array');
		$this->facet = $Fr->retFacet;




	}
	/*********************************************
	* makeRtnReqAry()
	* $this->rtnData	：ファセットが必要なパラメータ配列
	* $this->reqMyAry	：要求された値
	* 								exe $this->reqMyAry['p_hotel_name'] = array('05888','05946','05958');
  **********************************************/
	function makeRtnReqAry(){
		$myParam = $this->reqPara[$this->naigai]['p_rtn_data'];
		if(strpos($myParam,',') !== false){
			$this->rtnData = explode(',',$myParam);
		}else{
			$this->rtnData[] = $myParam;
		}
		foreach($this->rtnData as $rtnParam){
			//応答パラメータから要求パラメータ取得
			$reqParam = $this->respToReqParam($rtnParam);
			$tmpAry[$rtnParam] = $this->request[$reqParam];
			//複数もあるので配列へ
			if(strpos($tmpAry[$rtnParam],',') !== false){
				$this->reqMyAry[$rtnParam] = explode(',',$tmpAry[$rtnParam]);
			}else{
				$this->reqMyAry[$rtnParam][] = $tmpAry[$rtnParam];
			}
		}
		foreach($this->rtnData as $rtnP){
			if( empty($this->facet[$rtnP]) || !is_array($this->facet[$rtnP]) ){
				//ファセットない 0件
				$this->outAry['info'] = 'none';
			}
		}
	}

	function main(){
		global $GlobalMaster;

		foreach($this->rtnData as $myParam){

			$this->outTFAry[$myParam]['TF'] = false;
			switch($myParam){

				case 'p_hatsu_name':
						//出発地
						//拠点で出発地をまとめる為、拠点情報取得
						$MasterKey = 'Kyoten';
						if(empty($GlobalMaster[$MasterKey])){
							$ClassName = 'GM_' . $MasterKey;
							new $ClassName;
						}
						//メインの拠点だけ
						foreach($GlobalMaster[$MasterKey] as $MyKyotenAry){
							$this->kyotenAry[$MyKyotenAry['MainID']] = $MyKyotenAry['MainName'];
						}
						//出発地マスター取得
						if($this->naigai == "i"){
							$masterHatsuObj = new HierarchyKyotenCode_p_hatsu;
							$req_name = 'p_hatsu';
						}else{
							$masterHatsuObj = new HierarchyKyotenCode_p_hatsu_sub;
							$req_name = 'p_hatsu_sub';
						}


						foreach($masterHatsuObj->TgDataAry[$this->naigai] as $kyotenCode => $hatsuAry) {

							//出発拠点名
							$kyoten_name = $this->kyotenAry[$kyotenCode];


							foreach($hatsuAry as $hatsuKey => $hatsuVal) {



								$check = false;
								if($this->request[$req_name] !== NULL && $this->request[$req_name] !== ''){
									$check = (count($this->reqMyAry[$myParam]) > 0 && in_array($hatsuKey,$this->reqMyAry[$myParam]) == true)?true:false;
								}
								$facet_val = (!empty($this->facet[$myParam][$hatsuKey]['facet']))?$this->facet[$myParam][$hatsuKey]['facet']:0;
								if($this->outTFAry[$myParam]['TF'] == false && $facet_val > 0){
									$this->outTFAry[$myParam]['TF'] = true;
								}
								//$this->outAry[$myParam][] = array('key'=> $hatsuKey, 'name'=>$hatsuVal , 'facet'=> $facet_val ,'check'=>$check);
								$this->outAry[$myParam][$kyotenCode][] = array('key'=> $hatsuKey, 'name'=>$hatsuVal , 'facet'=> $facet_val ,'check'=>$check);

								if($check){
									$this->outSelectOnlyAry[$myParam][] = array('key'=> $hatsuKey, 'name'=>$hatsuVal);
								}
							}
						}
					break;

				case 'p_hatsu_local_name':
						//出発地
						//拠点で出発地をまとめる為、拠点情報取得
						$MasterKey = 'Kyoten';
						if(empty($GlobalMaster[$MasterKey])){
							$ClassName = 'GM_' . $MasterKey;
							new $ClassName;
						}
						//メインの拠点だけ
						foreach($GlobalMaster[$MasterKey] as $MyKyotenAry){
							$this->kyotenAry[$MyKyotenAry['MainID']] = $MyKyotenAry['MainName'];
						}
						//出発地マスター取得
						if($this->naigai == "i"){
							$masterHatsuObj = new HierarchyKyotenCode_p_hatsu;
							$req_name = 'p_hatsu';
						}else{
							$masterHatsuObj = new HierarchyKyotenCode_p_hatsu_sub;
							$req_name = 'p_hatsu_sub';
						}

						foreach($masterHatsuObj->TgDataAry[$this->naigai] as $kyotenCode => $hatsuAry) {

							//出発拠点名
							$kyoten_name = $this->kyotenAry[$kyotenCode];

							$_num = 0;
							foreach($hatsuAry as $hatsuKey => $hatsuVal) {
								$check = false;
								if($this->request[$req_name] !== NULL && $this->request[$req_name] !== ''){
									$check = (count($this->reqMyAry[$myParam]) > 0 && in_array($hatsuKey,$this->reqMyAry[$myParam]) == true)?true:false;
								}
								$facet_val = (!empty($this->facet[$myParam][$hatsuKey]['facet']))?$this->facet[$myParam][$hatsuKey]['facet']:0;
								if($this->outTFAry[$myParam]['TF'] == false && $facet_val > 0){
									$this->outTFAry[$myParam]['TF'] = true;
								}
								//$this->outAry[$myParam][] = array('key'=> $hatsuKey, 'name'=>$hatsuVal , 'facet'=> $facet_val ,'check'=>$check);
								$this->outAry[$myParam][$kyotenCode][] = array('key'=> $hatsuKey, 'name'=>$hatsuVal , 'facet'=> $facet_val ,'check'=>$check);

								if (isset($this->outAry['p_hatsu_name'][$kyotenCode][$_num]['facet'])) {

									$facet_val = $this->outAry['p_hatsu_name'][$kyotenCode][$_num]['facet'] + $facet_val;

									$this->outAry['p_hatsu_name'][$kyotenCode][$_num] = array('key'=> $hatsuKey, 'name'=>$hatsuVal , 'facet'=> $facet_val ,'check'=>$check);

									if($check){
										$this->outSelectOnlyAry[$myParam][] = array('key'=> $hatsuKey, 'name'=>$hatsuVal);
									}
								}

								$_num++;
							}
						}
					break;

				case 'p_conductor':
					//添乗員
					$ClassName = 'GM_' . $myParam;
					new $ClassName($this->naigai);	//$GlobalMaster[$MyParam]に入った
					foreach($GlobalMaster[$myParam][$this->naigai] as $val => $jname){
						$check = false;
						if($this->request[$myParam] !== NULL && $this->request[$myParam] !== ''){

							$check = (count($this->reqMyAry[$myParam]) > 0 && in_array($val,$this->reqMyAry[$myParam]) == true)?true:false;
						}
						$facet_val = (!empty($this->facet[$myParam][$val]['facet']))?$this->facet[$myParam][$val]['facet']:0;
						if($this->outTFAry[$myParam]['TF'] == false && $facet_val > 0){
							$this->outTFAry[$myParam]['TF'] = true;
						}
						$this->outAry[$myParam][] = array('key'=> $val, 'name'=>$jname , 'facet'=> $facet_val ,'check'=>$check);
						if($check){
							$this->outSelectOnlyAry[$myParam][] = array('key'=> $val, 'name'=>$jname);
						}
					}
					break;

				case 'p_timezone':
				case 'p_total_amount_divide':
				case 'p_mainbrand':
				case 'p_transport':
				case 'p_seatclass':
				case 'p_transport':
				case 'p_early_discount_flag':
					//交通機関
					$ClassName = 'GM_' . $myParam;
					new $ClassName($this->naigai);	//$GlobalMaster[$MyParam]に入った
					foreach($GlobalMaster[$myParam] as $val => $jname){
						$check = false;
						if($this->request[$myParam] !== NULL && $this->request[$myParam] !== ''){
							$check = (count($this->reqMyAry[$myParam]) > 0 && in_array($val,$this->reqMyAry[$myParam]) == true)?true:false;
						}
						$facet_val = (!empty($this->facet[$myParam][$val]['facet']))?$this->facet[$myParam][$val]['facet']:0;
						if($this->outTFAry[$myParam]['TF'] == false && $facet_val > 0){
							$this->outTFAry[$myParam]['TF'] = true;
						}
						$this->outAry[$myParam][] = array('key'=> $val, 'name'=>$jname , 'facet'=> $facet_val ,'check'=>$check);
						if($check){
							$this->outSelectOnlyAry[$myParam][] = array('key'=> $val, 'name'=>$jname);
						}
					}
					break;

				case 'p_discount':

					$ClassName = 'GM_' . $myParam;
					new $ClassName($this->naigai);	//$GlobalMaster[$MyParam]に入った
					foreach($GlobalMaster[$myParam] as $val => $jname){
						$check = false;

						if($this->request[$myParam] !== NULL && $this->request[$myParam] !== ''){
							$check = (count($this->reqMyAry[$myParam]) > 0 && in_array($val,$this->reqMyAry[$myParam]) == true)?true:false;
						}
						if($val !="1"){
							$facet_val = (!empty($this->facet[$myParam][$val]['facet']))?$this->facet[$myParam][$val]['facet']:0;
							if($this->outTFAry[$myParam]['TF'] == false && $facet_val > 0){
								$this->outTFAry[$myParam]['TF'] = true;
							}
							$this->outAry[$myParam][] = array('key'=> $val, 'name'=>$jname , 'facet'=> $facet_val ,'check'=>$check);
							if($check){
								$this->outSelectOnlyAry[$myParam][] = array('key'=> $val, 'name'=>$jname);
							}
						}
					}
					break;

				case 'p_stay_number':
						//宿泊数
						ksort($this->facet[$myParam]);

						foreach($this->facet[$myParam] as $key => $value){
							$check = false;
							if($this->request[$myParam] !== NULL && $this->request[$myParam] !== ''){

								$check = (count($this->reqMyAry[$myParam]) > 0 && in_array($key,$this->reqMyAry[$myParam]) == true)?true:false;
							}
							$facet_val = (!empty($this->facet[$myParam][$key]['facet']))?$this->facet[$myParam][$key]['facet']:0;
							if($this->outTFAry[$myParam]['TF'] == false && $facet_val > 0){
								$this->outTFAry[$myParam]['TF'] = true;
							}
							$this->outAry[$myParam][] = array('key'=> $key, 'name'=>$key , 'facet'=> $facet_val ,'check'=>$check);
							if($check){
								$this->outSelectOnlyAry[$myParam][] = array('key'=> $key, 'name'=>$key);
							}
						}
						break;

				case 'p_carr_cn':
					//航空会社
					if(count($this->facet[$myParam]) > 0){
						$jal['JL'] = array();
						$ana['NH'] = array();
						foreach ($this->facet[$myParam] as $key => $value){
							if($key == 'JL'){
								$jal[$key] = $value;
							}elseif($key == 'NH'){
								$ana[$key] = $value;
							}else{
								$key_id_other[$key] = $value['facet'];
								$other[$key] = $value;
							}
						}
						//JAL ANA以外ファセット多い順でソート
						array_multisort ( $key_id_other , SORT_DESC , $other);
						$this->facet[$myParam] = array();
						$this->facet[$myParam] = array_merge($jal,$ana,$other);

						foreach($this->facet[$myParam] as $val => $valAry){
							$check = false;
							if($this->request['p_carr'] !== NULL && $this->request['p_carr'] !== ''){
								$check = (count($this->reqMyAry[$myParam]) > 0 && in_array($val,$this->reqMyAry[$myParam]) == true)?true:false;
							}
							if($this->outTFAry[$myParam]['TF'] == false && $check == true){
								$this->outTFAry[$myParam]['TF'] = true;
							}
							$facet_val = (!empty($valAry['facet']))?$valAry['facet']:0;
							if($this->outTFAry[$myParam]['TF'] == false && $facet_val > 0){
								$this->outTFAry[$myParam]['TF'] = true;
							}
							$this->outAry[$myParam][] = array('key'=> $val, 'name'=>$valAry['name'] , 'facet'=> $facet_val ,'check'=>$check);
							if($check){
								$this->outSelectOnlyAry[$myParam][] = array('key'=> $val, 'name'=>$valAry['name']);
							}
						}
					}
					break;

				case 'p_dep_airport_name':
					//出発空港
					$ClassName = 'GM_p_dep_airport_code';
					new $ClassName($this->naigai);	//$GlobalMaster[$MyParam]に入った
					foreach($GlobalMaster['p_dep_airport_code'] as $val => $mAry){
					//foreach($this->facet[$myParam] as $val => $valAry){
						$valAry = $this->facet[$myParam][$mAry['p_dep_airport_code']];
						if(empty($valAry)){
							continue;
						}
						$check = false;
						if($this->request['p_dep_airport_code'] !== NULL && $this->request['p_dep_airport_code'] !== ''){
							$check = (count($this->reqMyAry[$myParam]) > 0 && in_array($mAry['p_dep_airport_code'],$this->reqMyAry[$myParam]) == true)?true:false;
						}
						$facet_val = (!empty($valAry['facet']))?$valAry['facet']:0;
						if($this->outTFAry[$myParam]['TF'] == false && $facet_val > 0){
							$this->outTFAry[$myParam]['TF'] = true;
						}
						$this->outAry[$myParam][] = array('key'=> $mAry['p_dep_airport_code'], 'name'=>$valAry['name'] , 'facet'=> $facet_val ,'check'=>$check);
						if($check){
							$this->outSelectOnlyAry[$myParam][] = array('key'=> $mAry['p_dep_airport_code'], 'name'=>$valAry['name']);
						}
					}
					break;
				case 'p_arr_airport_name':

					// 到着空港
					$ClassName = 'GM_p_dep_airport_code';
					new $ClassName($this->naigai);	//$GlobalMaster[$MyParam]に入った
					foreach($GlobalMaster['p_dep_airport_code'] as $val => $mAry){
						//foreach($this->facet[$myParam] as $val => $valAry){
						$valAry = $this->facet[$myParam][$mAry['p_dep_airport_code']];
						if(empty($valAry)){
							continue;
						}
						$check = false;
						if($this->request['p_arr_airport_code'] !== NULL && $this->request['p_arr_airport_code'] !== ''){
							$check = (count($this->reqMyAry[$myParam]) > 0 && in_array($mAry['p_dep_airport_code'],$this->reqMyAry[$myParam]) == true)?true:false;
						}
						$facet_val = (!empty($valAry['facet']))?$valAry['facet']:0;
						if($this->outTFAry[$myParam]['TF'] == false && $facet_val > 0){
							$this->outTFAry[$myParam]['TF'] = true;
						}
						$this->outAry[$myParam][] = array('key'=> $mAry['p_dep_airport_code'], 'name'=>$valAry['name'] , 'facet'=> $facet_val ,'check'=>$check);
						if($check){
							$this->outSelectOnlyAry[$myParam][] = array('key'=> $mAry['p_dep_airport_code'], 'name'=>$valAry['name']);
						}
					}
					break;

				case 'p_bus_boarding_name':

					$myParam = 'p_bus_boarding_name';

					$this->outAry[$myParam] = '';
					//バス乗車地
					$PrefectureListObj = new PrefectureCode_p_hatsu_sub;
					//出発地が設定されていない
					if( empty($this->request['p_hatsu_sub']) ){
						$this->outAry['info'] = 'Please specify a p_hatsu_sub';
						$this->outAry[$myParam] = '';
						break;
					}
					foreach($this->facet[$myParam] as $prefNo => $ary){
					    if (!isset($PrefectureListObj->TgDataAry[$prefNo])) {
					        continue;
					    }
						$prefName = $PrefectureListObj->TgDataAry[$prefNo];

						foreach($ary as $val => $valAry){
							$check = false;
							if($this->request['p_bus_boarding_code'] !== NULL && $this->request['p_bus_boarding_code'] !== ''){
								$check = (count($this->reqMyAry[$myParam]) > 0 && in_array($val,$this->reqMyAry[$myParam]) == true)?true:false;
							}
							$facet_val = (!empty($valAry['facet']))?$valAry['facet']:0;
							if($this->outTFAry[$myParam]['TF'] == false && $facet_val > 0){
								$this->outTFAry[$myParam]['TF'] = true;
							}
							//ファセットあるものしか返さない
							if($facet_val > 0){
								$this->outAry[$myParam][$prefName][$val] = array('key'=> $val, 'name'=>$valAry['name'] , 'facet'=> $facet_val ,'check'=>$check);
							}
							if($check){
								$this->outSelectOnlyAry[$myParam][] = array('key'=> $val, 'name'=>$valAry['name']);
							}
						}
					}

					if(count($this->outAry) <1){
						$this->outAry['info'] = 'none';
					}
					break;


			case 'p_price_flg':
					//ご予算

					ksort($this->facet[$myParam]);
					foreach($this->facet[$myParam] as $val => $valAry){
						//0件は削除
						if($valAry['facet'] < 1){
							continue;
						}
						//最大料金計算
						$priceFrom = $val;
						if($val >= 1000000){
							$jname = number_format($priceFrom) . '円以上';
						}else{
							$i = $val;
							if($val < 10000){
								$i += 1000;
							}
							elseif($val < 150000){
								$i += 10000;
							}
							elseif($val < 500000){
								$i += 50000;
							}
							elseif($val < 1000000){
								$i += 100000;
							}
							$priceTo = $i-1;
							$priceRange = $priceFrom . '_' . $priceTo;
							$jname = number_format($priceFrom) . '〜' . number_format($priceTo) .'円';
						}
						$check = false;
						if($this->request[$myParam] !== NULL && $this->request[$myParam] !== ''){
							$check = (count($this->reqMyAry[$myParam]) > 0 && in_array($val,$this->reqMyAry[$myParam]) == true)?true:false;
						}
						$facet_val = (!empty($valAry['facet']))?$valAry['facet']:0;
						if($this->outTFAry[$myParam]['TF'] == false && $facet_val > 0){
							$this->outTFAry[$myParam]['TF'] = true;
						}
						if($facet_val > 0){
							$priceAry[] = array('key'=> $val, 'name'=>$jname);
						}
						$this->outAry[$myParam][] = array('key'=> $val, 'name'=>$jname , 'facet'=> $facet_val ,'check'=>$check);
						if($check){
							$this->outSelectOnlyAry[$myParam][] = array('key'=> $val, 'name'=>$jname);
						}
					}
					if($priceAry[0]){
						$this->outTFAry[$myParam]['minmax'][] = reset($priceAry);
					}elseif(count($priceAry) >= 2){
						$this->outTFAry[$myParam]['minmax'][] = end($priceAry);
					}else{
						$this->outTFAry[$myParam]['minmax'][] = '';
					}

					break;

				case 'p_bunrui':
					//テーマ
					//カスタムマスタ
					$MasterObj = new HierarchyCodeNaigaiView_p_bunrui;
					$MasterAry = $MasterObj->TgDataAry[$this->naigai];
					foreach($MasterAry as $val => $jname){
						$check = false;
						if($this->request[$myParam] !== NULL && $this->request[$myParam] !== ''){
							$check = (count($this->reqMyAry[$myParam]) > 0 && in_array($val,$this->reqMyAry[$myParam]) == true)?true:false;
						}
						$facet_val = (!empty($this->facet[$myParam][$val]['facet']))?$this->facet[$myParam][$val]['facet']:0;
						if($this->outTFAry[$myParam]['TF'] == false && $facet_val > 0){
							$this->outTFAry[$myParam]['TF'] = true;
						}
						$this->outAry[$myParam][] = array('key'=> $val, 'name'=>$jname , 'facet'=> $facet_val ,'check'=>$check);
						if($check){
							$this->outSelectOnlyAry[$myParam][] = array('key'=> $val, 'name'=>$jname);
						}
					}
					break;

				case 'p_stock':
					//残席
					/*
						キー（残席数）単位で返す。
						例）残席１のファセット5件
						　　　残席2のファセット10件・・・
						※残席あり・指定なしで画面表示する場合は
						　　以下編集を行う
						　残席あり＝残席1以上のファセット合計値
					*/
					//ファセット編集
					$facet_org = array();
					foreach ($this->facet[$myParam] as $key => $val) {
						if ($key != 0) {
							$facet_org[1] += $val['facet'];
						}
							$facet_org[0] += $val['facet'];
					}
					$ClassName = 'GM_' . $myParam;
					new $ClassName($this->naigai);	//$GlobalMaster[$MyParam]に入った
					foreach($GlobalMaster[$myParam] as $val => $jname){
						$check = false;

						if($val == 0){
							continue;
						}
						if($this->request[$myParam] !== NULL && $this->request[$myParam] !== ''){
							$check = (count($this->reqMyAry[$myParam]) > 0 && in_array($val,$this->reqMyAry[$myParam]) == true)?true:false;
						}
						$facet_val = (!empty($facet_org[$val]))?$facet_org[$val]:0;
						if($this->outTFAry[$myParam]['TF'] == false && $facet_val > 0){
							$this->outTFAry[$myParam]['TF'] = true;
						}
						$this->outAry[$myParam][] = array('key'=> $val, 'name'=>$jname , 'facet'=> $facet_val ,'check'=>$check);
						if($check){
							$this->outSelectOnlyAry[$myParam][] = array('key'=> $val, 'name'=>$jname);
						}
					}
					break;

				case 'p_decide':
					//催行
					/*
					キー（催行確定/未確定）単位で返す。
					※催行確定・指定なしで画面表示する場合は以下編集を行う
					　催行確定＝催行確定（"1"）のファセット
					*/
					//ファセット編集
					$facet_org = array(0, 0, 0, 0);
					foreach ($this->facet[$myParam] as $key => $val) {
						if ($key != 0) {
							$facet_org[$key] += $val['facet'];
						}
					}

					$ClassName = 'GM_' . $myParam;
					new $ClassName($this->naigai);	//$GlobalMaster[$MyParam]に入った
					foreach($GlobalMaster[$myParam][$this->naigai] as $val => $jname){
						$check = false;

						if($val == 0){
							continue;
						}
						if($this->request[$myParam] !== NULL && $this->request[$myParam] !== ''){
							$check = (count($this->reqMyAry[$myParam]) > 0 && in_array($val,$this->reqMyAry[$myParam]) == true)?true:false;
						}
						$facet_val = (!empty($facet_org[$val]))?$facet_org[$val]:0;
						if($this->outTFAry[$myParam]['TF'] == false && $facet_val > 0){
							$this->outTFAry[$myParam]['TF'] = true;
						}
						$this->outAry[$myParam][] = array('key'=> $val, 'name'=>$jname , 'facet'=> $facet_val ,'check'=>$check);
						if($check){
							$this->outSelectOnlyAry[$myParam][] = array('key'=> $val, 'name'=>$jname);
						}
					}
					break;

				case 'p_mainbrand':
					//メインブランド
					$ClassName = 'GM_' . $myParam;
					new $ClassName($this->naigai);	//$GlobalMaster[$MyParam]に入った
					foreach($GlobalMaster[$myParam] as $val => $jname){
						$check = false;
						if($this->request[$myParam] !== NULL && $this->request[$myParam] !== ''){
							$check = (count($this->reqMyAry[$myParam]) > 0 && in_array($val,$this->reqMyAry[$myParam]) == true)?true:false;
						}
						$facet_val = (!empty($this->facet[$myParam][$val]['facet']))?$this->facet[$myParam][$val]['facet']:0;
						if($this->outTFAry[$myParam]['TF'] == false && $facet_val > 0){
							$this->outTFAry[$myParam]['TF'] = true;
						}
						$this->outAry[$myParam][] = array('key'=> $val, 'name'=>$jname , 'facet'=> $facet_val ,'check'=>$check);
						if($check){
							$this->outSelectOnlyAry[$myParam][] = array('key'=> $val, 'name'=>$jname);
						}
					}
					break;

				case 'p_web_conclusion_flag':
					//カード決済
					/*
					キー（する／しない）単位で返す。
					※カード決済・指定なしで画面表示する場合は以下編集を行う
					　カード決済＝する（"0"）のファセット
					*/
					$facet_org = array(0, 0);
					foreach ($this->facet[$myParam] as $key => $val) {
						if ($key == 0) {
							$facet_org[1] += $val['facet'];
						}
						$facet_org[0] += $val['facet'];
					}
					$ClassName = 'GM_' . $myParam;
					new $ClassName($this->naigai);	//$GlobalMaster[$MyParam]に入った
					foreach($GlobalMaster[$myParam] as $val => $jname){
						$check = false;

						if($val == 0){
							continue;
						}
						if($this->request[$myParam] !== NULL && $this->request[$myParam] !== ''){
							$check = (count($this->reqMyAry[$myParam]) > 0 && in_array($val,$this->reqMyAry[$myParam]) == true)?true:false;
						}
						$facet_val = (!empty($facet_org[$val]))?$facet_org[$val]:0;
						if($this->outTFAry[$myParam]['TF'] == false && $facet_val > 0){
							$this->outTFAry[$myParam]['TF'] = true;
						}
						$this->outAry[$myParam][] = array('key'=> $val, 'name'=>$jname , 'facet'=> $facet_val ,'check'=>$check);
						if($check){
							$this->outSelectOnlyAry[$myParam][] = array('key'=> $val, 'name'=>$jname);
						}
					}
					break;

				case 'p_kikan':
					//期間

					//要求と応答パラメータ名が異なる
					$min = $this->request['p_kikan_min'];
					$max = $this->request['p_kikan_max'];

					foreach($this->facet[$myParam] as $val => $valAry) {
						//選択済みならチェック
						$check = false;
						if($min && $max){
							$check = ($val >= $min && $val <= $max)?true:false;
						}
						else if($min && empty($max)){
							$check = ($val >= $min)?true:false;
						}
						else if(empty($min) && $max){
							$check = ($val <= $max)?true:false;
						}
						$jname = $val;
						$dno = sprintf('%02d',$val);
						$facet_val = (!empty($this->facet[$myParam][$val]['facet']))?$this->facet[$myParam][$val]['facet']:0;
						if($this->outTFAry[$myParam]['TF'] == false && $facet_val > 0){
							$this->outTFAry[$myParam]['TF'] = true;
						}
						$this->outAry[$myParam][] = array('key'=> $val, 'name'=>$jname , 'facet'=> $facet_val ,'check'=>$check);
						if($check){
							$this->outSelectOnlyAry[$myParam][] = array('key'=> $val, 'name'=>$jname);
						}
					}
					break;

				case 'p_accommodation_name':
					$p_hotel_codeAry = array();
					$p_hotel_codeAry = $this->GetHotelInfo();

					if(count($this->facet[$myParam]) < 1){
						$this->outAry['info'] = 'none';
						break;
					}
					$valid_cnt = 0;
					foreach($this->facet[$myParam] as $pCode => $hotelData){
						if($hotelData['facet'] > 0){
							$valid_cnt++;
						}
					}

					//ホテル
					ksort($this->facet[$myParam]);
					foreach($this->facet[$myParam] as $prefNo => $cityAry){
						foreach($cityAry as $cityCode => $ary){
							foreach($ary as $val => $valAry){
								$info_link = false;
								$info_code='';
								foreach($this->facet['p_accommodation_name'][$prefNo][$cityCode] as $accommodationCode => $accommodationAry){
//									if($valAry['name']==$accommodationAry['name'] && in_array($accommodationCode,$p_hotel_codeAry)){
									if($valAry['name']==$accommodationAry['name']){
										$info_link = true;
										$info_code = $accommodationCode;
									}
								}
								$check = false;
								if($this->request['p_accommodation_code'] !== NULL && $this->request['p_accommodation_code'] !== ''){
									$check = (count($this->reqMyAry[$myParam]) > 0 && in_array($val,$this->reqMyAry[$myParam]) == true)?true:false;
								}
								$facet_val = (!empty($valAry['facet']))?$valAry['facet']:0;

								if($this->outTFAry[$myParam]['TF'] == false && $facet_val > 0){
									$this->outTFAry[$myParam]['TF'] = true;
								}
								if($facet_val > 0){
									$this->outAry[$myParam][$valAry['cityCode'] . '_' .$valAry['prefectureName'] . '_'. $valAry['cityName']][] = array('key'=> $val, 'name'=>$valAry['name'] , 'facet'=> $facet_val ,'check'=>$check ,'info_link' =>$info_link,'info_code'=>$val);
								}
								if($check){
									$this->outSelectOnlyAry[$myParam][] = array('key'=> $val, 'name'=>$valAry['name']);
								}
							}
						}
					}
					if(count($this->outAry) <1){
						$this->outAry['info'] = 'none';
					}
					break;
				case 'p_hotel_name':
					if($this->naigai == 'd'){
						$p_hotel_codeAry = array();
						/*if($this->request['kindSub'] !== 'ReqOnly'){
							$p_hotel_codeAry = $this->GetHotelInfo();
						}*/
						if(count($this->facet[$myParam]) < 1){
							$this->outAry['info'] = 'none';
							break;
						}
						$valid_cnt = 0;
						foreach($this->facet[$myParam] as $pCode => $hotelData){
							if($hotelData['facet'] > 0){
								$valid_cnt++;
							}
						}
						//ホテル
						ksort($this->facet[$myParam]);
						foreach($this->facet[$myParam] as $prefNo => $cityAry){
							foreach($cityAry as $cityCode => $ary){


								foreach($ary as $val => $valAry){
									$info_link = false;
									$info_code='';
									foreach($this->facet['p_accommodation_name'][$prefNo][$cityCode] as $accommodationCode => $accommodationAry){

										if($valAry['name']==$accommodationAry['name']){
											$info_link = true;
											$info_code = $accommodationCode;
											break;
										}
									}

									$check = false;
									if($this->request['p_hotel_code'] !== NULL && $this->request['p_hotel_code'] !== ''){
										$check = (count($this->reqMyAry[$myParam]) > 0 && in_array($val,$this->reqMyAry[$myParam]) == true)?true:false;
									}
									$facet_val = (!empty($valAry['facet']))?$valAry['facet']:0;
									if($this->outTFAry[$myParam]['TF'] == false && $facet_val > 0){
										$this->outTFAry[$myParam]['TF'] = true;
									}
									if($facet_val > 0){
										$this->outAry[$myParam][$valAry['cityCode'] . '_' .$valAry['prefectureName'] . ' ＞ ' . $valAry['cityName']][] = array('key'=> $val, 'name'=>$valAry['name'] , 'facet'=> $facet_val ,'check'=>$check ,'info_link' =>$info_link,'info_code'=>$info_code);
									}
									//$this->outAry[$myParam][$valAry['cityCode'] . '_' .$valAry['prefectureName'] . ' ＞ ' . $valAry['cityName']][] = array('key'=> $val, 'name'=>$valAry['name'] , 'facet'=> $facet_val ,'check'=>$check ,'info_link' =>$info_link);
									if($check){
										$this->outSelectOnlyAry[$myParam][] = array('key'=> $val, 'name'=>$valAry['name']);
									}
								}
							}
						}
						if(count($this->outAry) <1){
							$this->outAry['info'] = 'none';
						}

					}else{
						//ホテル数をカウント
						$valid_cnt = 0;
						foreach($this->facet[$myParam] as $cityCode => $hotelData){
							if($hotelData['facet'] > 0){
								$valid_cnt++;
							}
						}
//						if($valid_cnt > 50){
//							$this->outAry['info'] = 'TooMany';//'該当件数が多すぎます。他の項目で絞り込んでください。';
//						}elseif($valid_cnt < 1){
//							$this->outAry['info'] = 'none';//'指定できるホテルが存在しません';
//						}else{
							$p_hotel_codeAry = array();
							if($this->request['kindSub'] !== 'ReqOnly'){
								$p_hotel_codeAry = $this->GetHotelInfoI();
							}
							foreach($this->facet['p_country_name'] as $destCode => $countryAry){
								foreach($countryAry as $countryCode => $countryDataAry){
									$countryName = mb_convert_kana($countryDataAry['name'],"aKCV","utf-8");
									foreach($this->facet['p_city_cn'][$destCode][$countryCode] as $cityCode => $cityAry){
										$info_link = false;
										$cityName = mb_convert_kana($cityAry['name'],"aKCV","utf-8");
										if(count($this->facet[$myParam][$cityCode]) > 0){
											foreach($this->facet[$myParam][$cityCode] as $hotelCode => $hotelData){
												$hotelName = mb_convert_kana($hotelData['name'],"aKCV","utf-8");
												if(in_array($hotelCode,$p_hotel_codeAry)){
													$info_link = true;
												}else{
													$info_link = false;
												}
												$check = false;
												if($this->request['p_hotel_code'] !== NULL && $this->request['p_hotel_code'] !== ''){
													$check = (count($this->reqMyAry[$myParam]) > 0 && in_array($hotelCode,$this->reqMyAry[$myParam]) == true)?true:false;
												}
												$facet_val = (!empty($hotelData['facet']))?$hotelData['facet']:0;
												if($this->outTFAry[$myParam]['TF'] == false && $facet_val > 0){
													$this->outTFAry[$myParam]['TF'] = true;
												}
												if($facet_val > 0){
													$this->outAry[$myParam][$countryCode . '_' .$countryName . '_'.$cityCode.'_'. $cityName][] = array('key'=> $hotelCode, 'name'=>$hotelName , 'facet'=> $facet_val ,'check'=>$check ,'info_link' =>$info_link);
												}
												if($check){
													$this->outSelectOnlyAry[$myParam][] = array('key'=> $hotelCode, 'name'=>$hotelName);
												}
											}
										}
									}
								}
							}
							if(count($this->outAry) <1){
								$this->outAry['info'] = 'none';
							}
						//}
					}

					break;

				//方面
				case 'p_dest_name':
					$myParam = 'p_dest_name';
					$this->reqMyAry['p_mokuteki'] = explode(",",$this->request['p_mokuteki']);
					$pattern = '/^([0-9A-Z]*)-([0-9A-Z]*)-([0-9A-Z]*)$/';
					foreach($this->reqMyAry['p_mokuteki'] as $arr){
						preg_match($pattern, $arr, $matches);
						$this->reqMyAry['p_dest_name'][] = $matches[1];
					}
					foreach($this->facet[$myParam] as $val => $valAry){
						$check = false;

						$check = (count($this->reqMyAry[$myParam]) > 0 && in_array($val,$this->reqMyAry[$myParam]) == true)?true:false;
						$facet_val = (!empty($valAry['facet']))?$valAry['facet']:0;
						if($this->outTFAry[$myParam]['TF'] == false && $facet_val > 0){
							$this->outTFAry[$myParam]['TF'] = true;
						}
						$valName = $valAry['name'];
						if(strpos($valName,'中国地方') !== false){
							$valName = str_replace('中国地方','中国',$valName);
						}
						$this->outAry[$myParam][] = array('key'=> $val, 'name'=>$valName , 'facet'=> $facet_val ,'check'=>$check);
						if($check){
								$this->outSelectOnlyAry[$myParam][] = array('key'=> $val, 'name'=>$valName);
						}

					}
					break;

				//都道府県
				case 'p_prefecture_name':
					$myParam = 'p_prefecture_name';

					$this->reqMyAry['p_mokuteki'] = explode(",",$_REQUEST['p_mokuteki']);
					$pattern = '/^([0-9]*)-([0-9]*)-([0-9]*)$/';
					foreach($this->reqMyAry['p_mokuteki'] as $arr){
						preg_match($pattern, $arr, $matches);
						$this->reqMyAry['p_prefecture_name'][] = $matches[2];
					}

					//都道府県名称
					foreach($this->facet[$myParam] as $destNo => $prefAry){
						foreach($prefAry as $prefCode => $valAry){
							$check = false;
							$check = (count($this->reqMyAry[$myParam]) > 0 && in_array($valAry['key'],$this->reqMyAry[$myParam]) == true)?true:false;
							$this->facet_val = (!empty($valAry['facet']))?$valAry['facet']:0;
							if($this->outTFAry[$myParam]['TF'] == false && $this->facet_val > 0){
								$this->outTFAry[$myParam]['TF'] = true;
							}
							$this->outAry[$myParam][] = array('key'=> $valAry['key'], 'name'=>$valAry['name'] , 'facet'=> $this->facet_val ,'check'=>$check,'parentKey'=>$destNo);
							if($check){
								$this->outSelectOnlyAry[$myParam][] = array('key'=> $valAry['key'], 'name'=>$valAry['name'],'parentKey'=>$destNo);
							}
						}
					}
					break;

				//国
				case 'p_country_name':
					$myParam = 'p_country_name';

					$this->reqMyAry['p_mokuteki'] = explode(",",$_REQUEST['p_mokuteki']);
					$pattern = '/^([0-9A-Z]*)-([0-9A-Z]*)-([0-9A-Z]*)$/';
					foreach($this->reqMyAry['p_mokuteki'] as $arr){
						preg_match($pattern, $arr, $matches);
						$this->reqMyAry[$myParam][] = $matches[2];
					}

					foreach($this->facet[$myParam] as $destNo => $cAry){
						foreach($cAry as $cCode => $valAry){
							$check = false;
							$check = (count($this->reqMyAry[$myParam]) > 0 && in_array($cCode,$this->reqMyAry[$myParam]) == true)?true:false;
							$this->facet_val = (!empty($valAry['facet']))?$valAry['facet']:0;
							if($this->outTFAry[$myParam]['TF'] == false && $this->facet_val > 0){
								$this->outTFAry[$myParam]['TF'] = true;
							}
							$this->outAry[$myParam][] = array('key'=> $cCode, 'name'=>$valAry['name'] , 'facet'=> $this->facet_val ,'check'=>$check,'parentKey'=>$destNo);
							if($check){
								$this->outSelectOnlyAry[$myParam][] = array('key'=> $cCode, 'name'=>$valAry['name'],'parentKey'=>$destNo);
							}
						}
					}
					break;

				//都道府県
				case 'p_region_cn':
					$myParam = 'p_region_cn';

					$this->reqMyAry['p_mokuteki'] = explode(",",$_REQUEST['p_mokuteki']);
					$pattern = '/^([0-9]*)-([0-9]*)-([0-9]*)$/';
					foreach($this->reqMyAry['p_mokuteki'] as $arr){
						preg_match($pattern, $arr, $matches);
						$this->reqMyAry['p_region_cn'][] = $matches[3];
					}

					//地域名称
					foreach($this->facet[$myParam] as $destNo => $prefAry){
						foreach($prefAry as $prefCode => $cityAry){
							foreach($cityAry as $cityCode => $valAry){
								$check = false;
								$check = (count($this->reqMyAry[$myParam]) > 0 && in_array($valAry['key'],$this->reqMyAry[$myParam]) == true)?true:false;
								$this->facet_val = (!empty($valAry['facet']))?$valAry['facet']:0;
								if($this->outTFAry[$myParam]['TF'] == false && $this->facet_val > 0){
									$this->outTFAry[$myParam]['TF'] = true;
								}
								$this->outAry[$myParam][] = array('key'=> $valAry['key'], 'name'=>$valAry['name'] , 'facet'=> $this->facet_val ,'check'=>$check,'parentKey'=>$prefCode);
								if($check){
									$this->outSelectOnlyAry[$myParam][] = array('key'=> $valAry['key'], 'name'=>$valAry['name'],'parentKey'=>$prefCode);
								}
							}
						}
					}
					break;

				//都市
				case 'p_city_cn':
					$myParam = 'p_city_cn';

					$this->reqMyAry['p_mokuteki'] = explode(",",$_REQUEST['p_mokuteki']);
					$pattern = '/^([0-9A-Z]*)-([0-9A-Z]*)-([0-9A-Z]*)$/';
					foreach($this->reqMyAry['p_mokuteki'] as $arr){
						preg_match($pattern, $arr, $matches);
						$this->reqMyAry['p_city_cn'][] = $matches[3];
					}

					//地域名称
					foreach($this->facet[$myParam] as $destNo => $cAry){
						foreach($cAry as $cCode => $cityAry){
							foreach($cityAry as $cityCode => $valAry){
								$check = false;
								$check = (count($this->reqMyAry[$myParam]) > 0 && in_array($cityCode,$this->reqMyAry[$myParam]) == true)?true:false;
								$this->facet_val = (!empty($valAry['facet']))?$valAry['facet']:0;
								if($this->outTFAry[$myParam]['TF'] == false && $this->facet_val > 0){
									$this->outTFAry[$myParam]['TF'] = true;
								}
								$this->outAry[$myParam][] = array('key'=> $cityCode, 'name'=>$valAry['name'] , 'facet'=> $this->facet_val ,'check'=>$check,'parentKey'=>$cCode);
								if($check){
									$this->outSelectOnlyAry[$myParam][] = array('key'=> $cityCode, 'name'=>$valAry['name'],'parentKey'=>$cCode);
								}
							}
						}
					}
					break;

				}//end switch
		}
	}

	function curl_get_contents( $url, $timeout = 60 ){
		$ch = curl_init();
		curl_setopt( $ch, CURLOPT_URL, $url );
		curl_setopt( $ch, CURLOPT_HEADER, false );
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
		curl_setopt( $ch, CURLOPT_TIMEOUT, $timeout );
		$result = curl_exec( $ch );
		curl_close( $ch );
		return $result;
	}

	//xhankyuのデータベースからホテル詳細取得
	function GetHotelInfo(){

		//キャッシュから取得
		$memKey = 'hotelDetail_d';
		$memGet = $this->GetMemcache($memKey);
		if(!empty($memGet)){
			$p_hotel_code = $memGet;
		}else{
			//りーふさん外部APIから取得
			if(strpos($_SERVER['HTTP_HOST'],'-dev') !== false || strpos($_SERVER['HTTP_HOST'],'-cms') !== false){
				$url = 'http://x-test.hankyu-travel.com/lf/freeplan-d-hotel/api/getHotelList.php';
			}else{
				$url = 'http://x.hankyu-travel.com/lf/freeplan-d-hotel/api/getHotelList.php';
			}
			$obj = $this->curl_get_contents( $url, 120 );
			$xml = simplexml_load_string( $obj );
			foreach($xml->list->hotel as $oneObj):
				$p_hotel_code[] = strval($oneObj->P_HOTEL_CODE);
			endforeach;

			//キャッシュへ
			$this->SetMemcache($memKey,$p_hotel_code);
		}
		return $p_hotel_code;
	}

	function GetHotelInfoI(){
		//キャッシュから取得
		$memKey = 'hotelDetail_i';
		$memGet = $this->GetMemcache($memKey);
		if(!empty($memGet)){
			$p_hotel_code = $memGet;
		}else{
			//りーふさん外部APIから取得
			if(strpos($_SERVER['HTTP_HOST'],'-dev') !== false || strpos($_SERVER['HTTP_HOST'],'-cms') !== false){
				$url = 'http://x-test.hankyu-travel.com/lf/api/hotelsearch/hotel_detail_list_i.php?p_data_source=1';
			}else{
				$url = 'http://x.hankyu-travel.com/lf/api/hotelsearch/hotel_detail_list_i.php?p_data_source=1';
			}
			$json = $this->curl_get_contents( $url, 120 );
			$obj = json_decode($json);
			foreach($obj->response->docs as $oneObj):
				$p_hotel_code[] = strval($oneObj->P_HOTEL_CODE);
			endforeach;

			//キャッシュへ
			$this->SetMemcache($memKey,$p_hotel_code);
		}
		return $p_hotel_code;
	}

	function GetMemcache($key){
		if(!$key){
			return false;
		}
		$memcache = new Memcache;
		$memPort = $this->GetMemport();
		//memを使える場合のみ処理
		if(@$memcache->connect('localhost', $memPort)){
			$result = $memcache->get($key);
		}
		else{
			return false;
		}
		$memcache->close();

		return $result;
	}


	function SetMemcache($key,$setObj){
		if(!$key){
			return;
		}
		$memPort = $this->GetMemport();
		$memcache = new Memcache();
		//memを使える場合のみ処理
		if(@$memcache->addServer('localhost', $memPort)){
			//保存するのは6時間
			$setTime = 60 * 60 * 6;
			$memcache->set($key, $setObj, false, $setTime);
		}
		else{
			return;
		}
		$memcache->close();
	}

	function GetMemport(){
		//Memcacheのポート設定
		if(strpos($_SERVER['HTTP_HOST'],'-test') !== false){
			$MemPort = 11212;
		}elseif(strpos($_SERVER['HTTP_HOST'],'-cms') !== false){
			$MemPort = 11212;
		}elseif(strpos($_SERVER['HTTP_HOST'],'-dev') !== false){
			$MemPort = 11212;
		}else{
			$MemPort =11211;
		}
		return $MemPort;
	}

	/*********************************************
	* respToReqParam()
	* 応答パラメータに対応する要求パラメータを返す
	* $this->reqMyAry	：要求された値
  *  引数
	* 	$p_rtn_param	:応答パラメータ
  *  返り値
	* 	$req_param		：要求パラメータ
	**********************************************/
	function respToReqParam($p_rtn_param){

		$req_param = $p_rtn_param;

		//応答と要求パラメータ違うもの
		switch($p_rtn_param){
			case 'p_hatsu_name':
				if($this->naigai == 'i'){
					$req_param = 'p_hatsu';
				}else{
					$req_param = 'p_hatsu_sub';
				}
				break;
			case 'p_carr_cn':
				$req_param = 'p_carr';
				break;
			case 'p_dep_airport_name':
				$req_param = 'p_dep_airport_code';
				break;
			case 'p_arr_airport_name':
				$req_param = 'p_arr_airport_code';
				break;
			case 'p_bus_boarding_name':
				$req_param = 'p_bus_boarding_code';
				break;
			case 'p_accommodation_name':
				$req_param = 'p_accommodation_code';
				break;
			case 'p_hotel_name':
				$req_param = 'p_hotel_code';
				break;
			case 'p_dest_name':
			case 'p_prefecture_name':
			case 'p_region_cn':
				$req_param = 'p_mokuteki';
				break;
		}
		return $req_param;
	}


}
?>
