<?php

/*********************************************
 * GM_SenmonMap2017
 *
 * GMマスタ(専門店)を読みこみ、mapの
 * リンクを生成
 *
 * 専門店用のデータを
 * $GlobalMaster['SenmonMap2017']にセットする
 * attending/senmon_kaigai/setting/master_senmon_kaigai_2017.csv
 *
 *********************************************/
define('COLUMN_dirname', 0);
define('COLUMN_naigai', 1);
define('COLUMN_page_type', 2);
define('COLUMN_right_box_type', 3);
define('COLUMN_homen', 4);
define('COLUMN_country', 5);
define('COLUMN_first_level', 6);
define('COLUMN_second_level', 7);
define('COLUMN_senmon_name_ja', 8);
define('COLUMN_senmon_name_en', 9);
define('COLUMN_URL', 10);
define('COLUMN_Dest', 11);
define('COLUMN_Country', 12);
define('COLUMN_City', 13);
define('COLUMN_page_code', 14);
define('COLUMN_page_color', 15);
define('COLUMN_page_caption', 16);
define('COLUMN_page_caption_sp', 17);
define('COLUMN_map_not_display', 18);


class GM_SenmonMap2017 extends GM_Kyoten{
	/*---定数---*/
	public $RM_FileBaseName = 'master_senmon_kokunai_2017.csv';	//ファイル名

	private $read_count = 0;

	#=======================
	#	初動
	#=======================
	function __construct() {
		global $PathSenmonCommon;
		//パスごとファイルセット
		$this->RM_MasterFileName = $PathSenmonCommon . 'setting/' . $this->RM_FileBaseName;
		// データ読み込み件数のカウントを初期化
		$this->read_count = 0;
		//ファイルの読み込んでGlobalにセット
		$this->readMyFile();
	}

	#=======================
	#	ファイルの読み込み中の処理
	#=======================
	function readMyAction($buffer){
	    global $kyotenId;

		// 始め2行は処理しない
		if ($this->read_count <= 1) {
			$this->read_count++;
			return;
		}

		$data = explode("\t", $buffer);

		//URLなし、専門店名なし、フェーズ２フラグあり＝除外
		//if(!preg_match('/([-_.!~*\'()a-zA-Z0-9;\/?:\@&=+\$,%#]+)$/', $data[7]) || empty($data[6])	|| !empty($data[8]) || !empty($data[15])){
		//	return;
		//}

		//除外する文字列の配列
		$vowels = array("/","\t","\s","\r","\n","\""," ","　");

		/*******************
		*目的地生成 ここから
		********************/
		$_naigai   = $data[COLUMN_naigai];
		$_page_type = $data[COLUMN_page_type];
		$_right_box_type = $data[COLUMN_right_box_type];
		$_homen = $data[COLUMN_homen];
		$_country = $data[COLUMN_country];
		$_first_level = $data[COLUMN_first_level];
		$_second_level = $data[COLUMN_second_level];
		$_senmon_name_ja = $data[COLUMN_senmon_name_ja];
		$_senmon_name_en = $data[COLUMN_senmon_name_en];
		$_url = $data[COLUMN_URL];
		$dest_code = $data[COLUMN_Dest];
		$country_code = $data[COLUMN_Country];
		$city_code = $data[COLUMN_City];
		$_map_not_display = $data[COLUMN_map_not_display];

		// DSページのURLには、p_hatsuをセットする
		if ($_page_type == 'DS') {
		    switch($kyotenId) {
		        case 'spk': //北海道（北海道）
		            $_url .= '&p_hatsu_sub=101011,101012,101013,101014,101015,101016,101017,101018,101319,101320,101321,101322,101323';
		            break;
		        case 'aoj': //青森発（青森）
		            $_url .= '&p_hatsu_sub=102022';
		            break;
		        case 'sdj': //東北発（岩手、宮城、秋田、山形、福島）
		            $_url .= '&p_hatsu_sub=102021,102023,102024,102025,102026';
		            break;
		        case 'ibr': //北関東発（茨城、栃木、群馬）
		            $_url .= '&p_hatsu_sub=104046,104047,104048';
		            break;
		        case 'tyo': //関東（埼玉、千葉、東京、神奈川、山梨）
		            $_url .= '&p_hatsu_sub=104041,104042,104043,104044,104045,104049,105052';
		            break;
		        case 'toy': //富山発（富山）
		            $_url .= '&p_hatsu_sub=103032';
		            break;
		        case 'hkr': //石川・福井発（石川、福井）
		            $_url .= '&p_hatsu_sub=103031,103033';
		            break;
		        case 'mmj': //長野発（長野）
		            $_url .= '&p_hatsu_sub=105053';
		            break;
		        case 'ngo': //名古屋発（岐阜、愛知、三重）
		            $_url .= '&p_hatsu_sub=111111,111113,111114';
		            break;
		        case 'szo': //静岡発（静岡）
		            $_url .= '&p_hatsu_sub=111112';
		            break;
		        case 'osa': //関西発（滋賀、京都、大阪、兵庫、奈良、和歌山）
		            $_url .= '&p_hatsu_sub=106061,106062,106063,106064,106065,106066';
		            break;
		        case 'izo': //山陰発（鳥取、島根）
		            $_url .= '&p_hatsu_sub=107074,107075';
		            break;
		        case 'okj': //岡山発（岡山）
		            $_url .= '&p_hatsu_sub=107071';
		            break;
		        case 'hij': //広島発（広島）
		            $_url .= '&p_hatsu_sub=107072';
		            break;
		        case 'ubj': //山口発（山口）
		            $_url .= '&p_hatsu_sub=107073';
		            break;
		        case 'tak': //香川・徳島発（香川、徳島）
		            $_url .= '&p_hatsu_sub=108083,108084';
		            break;
		        case 'myj': //松山発（愛媛）
		            $_url .= '&p_hatsu_sub=108081';
		            break;
		        case 'kcz': //高知発（高知）
		            $_url .= '&p_hatsu_sub=108082';
		            break;
		        case 'fuk': //福岡発（福岡、佐賀）
		            $_url .= '&p_hatsu_sub=109090,109091,109094';
		            break;
		        case 'ngs': //長崎発（長崎）
		            $_url .= '&p_hatsu_sub=109092';
		            break;
		        case 'kmj': //熊本発（熊本）
		            $_url .= '&p_hatsu_sub=109095';
		            break;
		        case 'oit': //大分発（大分）
		            $_url .= '&p_hatsu_sub=109093';
		            break;
		        case 'kmi': //宮崎発（宮崎）
		            $_url .= '&p_hatsu_sub=109096';
		            break;
		        case 'koj': //鹿児島発（鹿児島）
		            $_url .= '&p_hatsu_sub=109097';
		            break;
		        case 'oka': //沖縄発（沖縄）
		            $_url .= '&p_hatsu_sub=110000';
		            break;
		        case 'kij': // 新潟
		            $_url .= '&p_hatsu_sub=105051';
		            break;
                default:
		            $_url .= '&p_hatsu=1,2,3,4,5,6,7';
		            break;
		    }
		}

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
					$mokuteki = $homen_no . '-' . (isset($country_no) ? $country_no : '') . '-';
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

		if ($_page_type != 'DS'){
			$_url = '/'.$_url;
		}

		//地図用の配列を生成する
		if($_right_box_type == 'homen'){
			//海外トップ・国内トップ用
			if($_naigai == 'i'){
				$this->MyAry[$_naigai]['top']['top']['naigai'] = $_naigai;
				$this->MyAry[$_naigai]['top']['top']['page_type'] = $_page_type;
				$this->MyAry[$_naigai]['top']['top']['dest'] =$_naigai;
				$this->MyAry[$_naigai]['top']['top']['senmon_name'] = $_naigai;
				$this->MyAry[$_naigai]['top']['top']['senmon_name_en'] = $_naigai;
				$this->MyAry[$_naigai]['top']['top']['css_name'] = str_replace($vowels,"",'kaigai/');
				$this->MyAry[$_naigai]['top']['top']['url'] = '/kaigai/';
				$this->MyAry[$_naigai]['top']['top']['req'] = '';
				$this->MyAry[$_naigai]['top']['top']['map_type'] = $_right_box_type;
				$this->MyAry[$_naigai]['top']['top']['key'] = $country_code;
				$this->MyAry[$_naigai]['top']['top']['facet'] = '';
				$this->MyAry[$_naigai]['top']['top']['map_not_display'] = $_map_not_display;
			}else{
				$this->MyAry[$_naigai]['top']['top']['naigai'] = $_naigai;
				$this->MyAry[$_naigai]['top']['top']['page_type'] = $_page_type;
				$this->MyAry[$_naigai]['top']['top']['dest'] =$_naigai;
				$this->MyAry[$_naigai]['top']['top']['senmon_name'] = $_naigai;
				$this->MyAry[$_naigai]['top']['top']['senmon_name_en'] = $_naigai;
				$this->MyAry[$_naigai]['top']['top']['css_name'] = str_replace($vowels,"",'kokunai/');
				$this->MyAry[$_naigai]['top']['top']['url'] = '/kokunai/';
				$this->MyAry[$_naigai]['top']['top']['req'] = '';
				$this->MyAry[$_naigai]['top']['top']['map_type'] = $_right_box_type;
				$this->MyAry[$_naigai]['top']['top']['key'] = $country_code;
				$this->MyAry[$_naigai]['top']['top']['facet'] = '';
				$this->MyAry[$_naigai]['top']['top']['map_not_display'] = $_map_not_display;
			}
			$this->MyAry[$_naigai]['top']['top'][$_homen] = array(
				 'naigai' => str_replace($vowels,"",$_naigai)
				,'page_type' => $_page_type
				,'dest' => $dest_code
				,'senmon_name' => str_replace($vowels,"",$_senmon_name_ja)
				,'senmon_name_en' => str_replace($vowels,"",$_senmon_name_en)
				,'css_name' => str_replace($vowels,"",$_homen)
				,'url' => $_url
				,'req' => $mokuteki
				,'map_type' => $_right_box_type
				,'key' => $dest_code
				,'facet' => 0
				,'map_not_display' => $_map_not_display
			);

			//方面用
			$this->MyAry[$_naigai][$_homen] = array(
				 'naigai' => str_replace($vowels,"",$_naigai)
				,'page_type' => $_page_type
				,'dest' => $dest_code
				,'senmon_name' => str_replace($vowels,"",$_senmon_name_ja)
				,'senmon_name_en' => str_replace($vowels,"",$_senmon_name_en)
				,'css_name' => str_replace($vowels,"",$_homen)
				,'url' => $_url
				,'req' => $mokuteki
				,'map_type' => $_right_box_type
				,'key' => $dest_code
				,'facet' => 0
				,'map_not_display' => $_map_not_display
			);
			$this->MyAry[$_naigai][$_homen][$_homen] = array(
				 'naigai' => str_replace($vowels,"",$_naigai)
				,'page_type' => $_page_type
				,'dest' => $dest_code
				,'senmon_name' => str_replace($vowels,"",$_senmon_name_ja)
				,'senmon_name_en' => str_replace($vowels,"",$_senmon_name_en)
				,'css_name' => str_replace($vowels,"",$_first_level)
				,'url' => $_url
				,'req' => $mokuteki
				,'map_type' => $_right_box_type
				,'key' => $dest_code
				,'facet' => 0
				,'map_not_display' => $_map_not_display
			);
		}else{

			if($_right_box_type == 'city' && (!empty($city_code) || (empty($city_code) && $_page_type == 'DS') )){

			//都市・観光地用
				$this->MyAry[$_naigai][$_homen][$_first_level][$_second_level] = array(
					 'naigai' => str_replace($vowels,"",$_naigai)
				    ,'page_type' => $_page_type
					,'dest' => $dest_code
					,'senmon_name' => str_replace($vowels,"",$_senmon_name_ja)
					,'senmon_name_en' => str_replace($vowels,"",$_senmon_name_en)
					,'css_name' => str_replace($vowels,"",$_second_level)
					,'url' => $_url
					,'req' => $mokuteki
					,'map_type' => $_right_box_type
					,'key' => $city_code
					,'facet' => 0
				    ,'map_not_display' => $_map_not_display
				);
			}elseif($_homen == $_first_level){
			//東欧・北欧など
				$this->MyAry[$_naigai][$_homen][$_second_level] = array(
					 'naigai' => str_replace($vowels,"",$_naigai)
				    ,'page_type' => $_page_type
					,'dest' => $dest_code
					,'senmon_name' => str_replace($vowels,"",$_senmon_name_ja)
					,'senmon_name_en' => str_replace($vowels,"",$_senmon_name_en)
					,'css_name' => str_replace($vowels,"",$_second_level)
					,'url' => $_url
					,'req' => $mokuteki
					,'map_type' =>'country'
					,'key' => $country_code
					,'facet' => 0
				    ,'map_not_display' => $_map_not_display
				);
			}else{
			//国・都道府県
				$this->MyAry[$_naigai][$_homen][$_first_level] = array(
					 'naigai' => str_replace($vowels,"",$_naigai)
				    ,'page_type' => $_page_type
					,'dest' => $dest_code
					,'senmon_name' => str_replace($vowels,"",$_senmon_name_ja)
					,'senmon_name_en' => str_replace($vowels,"",$_senmon_name_en)
					,'css_name' => str_replace($vowels,"",$_first_level)
					,'url' => $_url
					,'req' => $mokuteki
					,'map_type' => $_right_box_type
					,'key' => $country_code
					,'facet' => 0
				    ,'map_not_display' => $_map_not_display
				);
				$this->MyAry[$_naigai][$_homen][$_homen][$_first_level] = array(
					 'naigai' => str_replace($vowels,"",$_naigai)
				    ,'page_type' => $_page_type
					,'dest' => $dest_code
					,'senmon_name' => str_replace($vowels,"",$_senmon_name_ja)
					,'senmon_name_en' => str_replace($vowels,"",$_senmon_name_en)
					,'css_name' => str_replace($vowels,"",$_first_level)
					,'url' => $_url
					,'req' => $mokuteki
					,'map_type' => $_right_box_type
					,'key' => $country_code
					,'facet' => 0
				    ,'map_not_display' => $_map_not_display
				);
			}
		}
		$this->read_count++;
	}
	#=======================
	#	ファイルの読み込み後の処理
	#=======================
	function AfterReadMyAction(){
		global $GlobalMaster;
		$GlobalMaster['SenmonMap2017'] = $this->MyAry;
	}
}



?>
