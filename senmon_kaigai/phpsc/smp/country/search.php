<?php
/*
#################################################################
	サーチ専用のFnc集です
#################################################################
*/

/*
	ちなみに、XSSの処置は、出力するときに！
	solr_access.php内に、MyEcho関数を作ってあります。
*/
/*共通のFncを呼び出します*/
include_once($_SERVER['DOCUMENT_ROOT'] . '/sharing/phpsc/read_master.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/sharing/phpsc/solr_access.php');
include_once(dirname(__FILE__) . '/class_saiyasuneMemcache.php');

/*
*******************************************************
	検索ページに最初に入ってきたときに呼び出す
*******************************************************
	検索ページ読み込み時に走るクラスです。
	必要な変数は、このクラスの中にまとめます。
*/
class GetLowPrice {
	#=======================
	#	初期設定
	#=======================
	private $MyNaigai;	//内外判定
	private $ResObj;	//商品部分ママ返却
	private $FacetObj;	//見やすくなったFacet
	private $StatusObj;	//見やすくなった統計情報
	private $recentlyMonth;
	private $count;
	public $returnObj;

	#=======================
	#	クラス読み込み時に動く
	#=======================
	function __construct() {
		global $GlobalSolrReqParamAry;

		$this->MyNaigai = 'i';

//		new deleteSaiyasuneTourMemcache();
//		return;

		// メモキャッシュから取得
		$this->returnObj = new getSaiyasuneTourMemcache();

		// メモキャッシュから取得できないなら
		if($this->returnObj->returnObj == null)
		{
			// 検索する際の月
			$this->setRecentyMonth();


			// 指定された月分検索を回す
			for($i=0;$i<$this->count;$i++)
			{
				$_REQUEST['p_dep_date'] = $this->recentlyMonth[$i];

				/*受け取ったリクエストパラを、solrに渡す準備をします*/
				$this->ActRequestForSolr();

				/*DB通信*/
				$SolrObj = new SolrAccess($this->MyNaigai);	//solrのレスポンス：ママ
//				$this->FacetPlane = $SolrObj->Obj->facet_counts->facet_fields;


				$this->returnObj->$i = $SolrObj->Obj->response;
			}


			// メモキャッシュに保存する
			new setSaiyasuneTourMemcache($this->returnObj);
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
	#	受け取ったリクエストパラを、solrに渡す準備をします
	#	レスポンス表示をする際の和名準備もします
	#=======================
	function ActRequestForSolr(){
		global $GlobalSolrReqParamAry;

		/*処理するリクエストパラ*/
		$Request = $_REQUEST;

		/*グローバル変数に値を入れていく*/
		/*表示用も一緒に作る*/
		foreach($GlobalSolrReqParamAry[$this->MyNaigai] as $ParamName => $DefVal){
			if($Request[$ParamName] == NULL){

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

					$PreReqParamAry[] = $MyExVal;
				}

				$GlobalSolrReqParamAry[$this->MyNaigai][$ParamName] = implode(',', $PreReqParamAry);
			}
		}
		
		// p_hatsu_local に p_hatsu と同じ値を代入する
		if (isset($GlobalSolrReqParamAry[$this->MyNaigai]['p_hatsu'])) {
		    $GlobalSolrReqParamAry[$this->MyNaigai]['p_hatsu_local'] = $GlobalSolrReqParamAry[$this->MyNaigai]['p_hatsu'];
		}
		
	}

	// 出発月が「当月＋1」～「当月＋5」のツアーを検索する際の月
	function setRecentyMonth()
	{
		// 取得する月数
		$this->count = 5;

		$this->recentlyMonth = array();

		for($i=1;$i<=$this->count;$i++)
		{
			// 翌月から5ヶ月分
			$timestamp = strtotime(date('Y-m-01').'+'.$i. 'month');
			$this->recentlyMonth[$i-1] = date('Ym', $timestamp);
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



?>
