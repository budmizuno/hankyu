<?php
/*********************************************
 * SetSolrParamCustom　要求パラメータのカスタム
 *
 * ライトBOX等で選択させる場合、セットされた要求
 * パラメータ内に自パラメータがある場合、自パラメータリセット
 * 
 *********************************************/
class SetSolrParamCustom{
	public $naigai;			//内外
	public $request;		//$_REQUEST
	public $ReqPara;		//セットされた要求パラメータ

	function __construct($Request,$ReqPara,$naigai) {
		$this->init($Request,$ReqPara,$naigai);
		$this->main();
	}
	function init($Request,$ReqPara,$naigai){
		$this->naigai = $naigai;
		$this->request = $Request;
		$this->ReqPara = $ReqPara;

	}
	function main(){
	
		//応答データ形式 "4":国内特殊応答（出発日ファセット）特殊
		if($this->request['p_data_kind'] == 4){
			$this->p_dep_date();
			return true;
		}
		//onload時は何も除去しない
		if(isset($this->request['kindSub']) && $this->request['kindSub'] == 'ReqOnly'){
			return true;
		}
		
		if (isset($_REQUEST['p_hatsu']) && $_REQUEST['p_hatsu'] != "") {
			$this->ReqPara['p_hatsu_local'] = $_REQUEST['p_hatsu'];
		}

		switch($this->request['kind']){
			
			case 'Box_p_hatsu_sub':
					$this->ReqPara['p_hatsu_sub'] = NULL;
				break;
				
			case 'Box_p_kikan':
					$this->ReqPara['p_kikan_min'] = NULL;
					$this->ReqPara['p_kikan_max'] = NULL;
				break;
				
			case 'Box_p_mokuteki':
					//$this->ReqPara['p_mokuteki'] = NULL;
				break;
				
			case 'Detail':
				//応答データ項目
				$rtn_data = $this->request['p_rtn_data'];
	
				//必要なファセットは1パラメータ
				if(strpos($rtn_data,',') === false){

					switch($rtn_data){
						case 'p_transport':
						case 'p_stock':
						case 'p_decide':
						case 'p_mainbrand':
						case 'p_price_flg':
						case 'p_web_conclusion_flag':
						case 'p_conductor':
						case 'p_discount':
						case 'p_timezone':
						case 'p_total_amount_divide':
						case 'p_seatclass':
						case 'p_early_discount_flag':
						case 'p_stay_number':
							$this->ReqPara[$rtn_data] = NULL;
							break;
						case 'p_hatsu_name':
							$this->ReqPara['p_hatsu_sub'] = NULL;
							$this->ReqPara['p_hatsu'] = NULL;
						case 'p_dep_airport_name':
							$this->ReqPara['p_dep_airport_code'] = NULL;
							break;
						case 'p_arr_airport_name':
							$this->ReqPara['p_arr_airport_code'] = NULL;
							break;						
						case 'p_carr_cn':
							$this->ReqPara['p_carr'] = NULL;
							break;
						case 'p_bus_boarding_name':
							$this->ReqPara['p_bus_boarding_code'] = NULL;
							break;
						case 'p_hotel_name':
							$this->ReqPara['p_hotel_code'] = NULL;
							break;
						case 'p_accommodation_name':
							$this->ReqPara['p_accommodation_code'] = NULL;
							break;							
						case 'p_kikan':
							$this->ReqPara['p_kikan_min'] = NULL;
							$this->ReqPara['p_kikan_max'] = NULL;
							break;
							
						case 'p_dest_name':
						case 'p_prefecture_name':
						case 'p_region_cn':
							//いずれかでkindSendの指定がない場合、p_mokutekiを空にする
							if($this->request['p_mokuteki_kind'] == 2 && (!($this->request['kindSend']) || strpos($this->request['kindSend'],'p_mokuteki') == false)){
								$this->ReqPara['p_mokuteki'] = NULL;
							}
							break;
							
					}
				}else{
					
					if(strpos($rtn_data,'p_hotel_name') !== false){
						$this->ReqPara['p_hotel_code'] = NULL;
					}
					
					//目的地 変更ボタン
					if($this->request['p_mokuteki_kind'] == 2){
						
						if($this->request['kindSend'] && strpos($this->request['kindSend'],'p_mokuteki') !== false){
							//パラメータ送る
							
						}else{
							//パラメータ送らない
							if( strpos($rtn_data,'p_dest_name') !== false || strpos($rtn_data,'p_prefecture_name') !== false || strpos($rtn_data,'p_region_cn') !== false ){
								$this->ReqPara['p_mokuteki'] = NULL;
							}
						}
					}
				}
				
		}
	}
	
	//応答データ形式 "4":国内特殊応答（出発日ファセット）特殊
	function p_dep_date(){
		//今月
		$date = new DateTime();
		$date->setTime(0, 0, 0);
		$ThisMonth = $date->format("Ym");
		
		//表示開始月
		$vMonth = $this->request['ViewMonth'];
		if(empty($vMonth)){
			//選択状態
			$vMonth = $this->request['p_dep_date'];
		}
		//設定があったら
		if(strpos($vMonth, '/') !== false){
			$DateAry = explode('/', $vMonth);
			//月指定の場合
			if(empty($DateAry[2])){
				$vMonth = sprintf("%04d%02d", $DateAry[0], $DateAry[1]);
			}
			//日付まで
			else{
				$vMonth = sprintf("%04d%02d%02d", $DateAry[0], $DateAry[1], $DateAry[2]);
			}
		}
		$SetDateY = substr($vMonth, 0, 4);
		$SetDateM = substr($vMonth, 4, 2);
		$date->setDate($SetDateY, $SetDateM, 1);
		$SetDateYM = $date->format("Ym");
		
		$this->ReqPara['p_dep_date'] = $ThisMonth;
		
		//今月より以前だったら、今月
		if($SetDateYM < $ThisMonth){
			$this->ReqPara['p_dep_date'] = $ThisMonth;
		}
		else{
			$this->ReqPara['p_dep_date'] = $SetDateYM;
		}
		
	}
}


?>