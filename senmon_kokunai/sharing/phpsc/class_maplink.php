<?php
//==============================================================
/*---------- MapLink Ver1.0 wglg---------*/
//==============================================================
//FILE NAME : MapLink
//CONTENTS : 方面・国・都市・都道府県　専門店
//PROGRAM : 専門店のマップを表示　マップファセットを取得
//--------------------------------------------------------------
// 連絡事項下記へ追加してください。
########################################################################
#
#  専門店マップを表示
#
#  ◆master_senmon.csvより値を読み込み、urlに対応するマップを表示する
#  class　GM_SenmonMap  マスター生成
#  class　MapLink　　ファセット取得　リスト生成
#  　　　 display()		表示
#
#
#  @copyright  2010 BUD International
#  @version    1.0.0
########################################################################

//Fnc集
include_once($_SERVER['DOCUMENT_ROOT'] . '/sharing/phpsc/path.php');
include_once($SharingPSPath . 'read_master.php');
include_once(dirname(__FILE__) . '/../../phpsc/GM_SenmonMap2017.php');
include_once(dirname(__FILE__) . '/../../phpsc/SenmonMemCache.php');

class MapLink
{
    #=======================
    #	初期設定
    #=======================
    /*---変数---*/
    public $MasterKey = 'SenmonMap2017';  //GMマスター配列名
    public $pList;            //出力用変数
    public $liList;            //出力用変数
    public $MyNaigai;        //海外・国内
    public $MyHomen;        //方面
    public $MyPath;            //パス
    public $MyHatsu;        //出発地
    public $MyMapHatsuName;    //出発地名称
    public $MyDisplayKey;    //City・Country
    public $MyDisplayLink;    //出力用変数
    public $MyKyotenFlg;    //拠点フラグ
    public $MyKyotenId;    //拠点Id
    public $MyBunruiCode;    //分類コード
    public $MyReq;
    public $MyDest;
    public $MyMapType;

    #=======================
    #	コンストラクタ
    #=======================
    function __construct($MyNaigai, $MyHomen, $MyPath, $MyHatsu, $MyMapType, $MyMapHatsuName)
    {
        global $GlobalMaster,$categoryType;                        //マスター一式
        global $GlobalSolrReqParamAry;
        global $masterCsv;
        $GlobalSolrReqParamAry[$MyNaigai]['p_mokuteki'] = NULL;
        $GlobalSolrReqParamAry['i']['p_hatsu'] = NULL;
        $GlobalSolrReqParamAry['d']['p_hatsu_sub'] = NULL;


        /*マスターを持ってなければ、マスターを作るクラス実行*/
        if (empty($GlobalMaster[$this->MasterKey])) {
            $ClassName = 'GM_' . $this->MasterKey;
            new $ClassName;
        }

        $MyPath = $masterCsv['first_level'];

        //除外する文字列の配列
        $vowels = array("/", "\t", "\s", "\r", "\n", "\"", " ", "　");
        //display()で使用する変数
        $this->MyNaigai = $MyNaigai;    //海外・国内はdisplay()でも使用
        $this->MyHomen = $MyHomen;        //方面はdisplay()でも使用
        $this->MyPath = str_replace($vowels, "", $MyPath);        //パスはdisplay()でも使用
        $this->MyHatsu = $MyHatsu;        //出発地はdisplay()でも使用
        $this->MyMapHatsuName = $MyMapHatsuName;    //出発地名はdisplay()でも使用
        if ($MyMapHatsuName == '未選択') {
            $this->MyMapHatsuName = $MyMapHatsuName;    //出発地名はdisplay()でも使用
        } elseif (strpos($MyMapHatsuName, '発') != false) {
            $this->MyMapHatsuName = $MyMapHatsuName . '発';    //出発地名はdisplay()でも使用
        }
        $this->MyReq = $GlobalMaster[$this->MasterKey][$MyNaigai][$MyHomen][$MyPath]['req'];

        $this->MyDest = $GlobalMaster[$this->MasterKey][$MyNaigai][$MyHomen][$MyPath]['dest'];

//        echo $this->MyReq;
        if ($GlobalMaster[$this->MasterKey][$MyNaigai][$MyHomen][$MyPath]['map_type'] == 'homen') {
            $this->MyMapType = 'Country';
        } else if ($GlobalMaster[$this->MasterKey][$MyNaigai][$MyHomen][$MyPath]['map_type'] == 'country') {
            $this->MyMapType = 'City';
        }

        if ($masterCsv['dirname'] == '/hokkaido' || $masterCsv['dirname'] == '/okinawa') {
            $this->MyMapType = 'City';
        }
    }

    function getFacet()
    {
        global $categoryType;
        global $GlobalMaster;                        //マスター一式
        global $GlobalSolrReqParamAry;
        $GlobalSolrReqParamAry[$this->MyNaigai]['p_mokuteki'] = NULL;
        /*マスターを持ってなければ、マスターを作るクラス実行*/
        if (empty($GlobalMaster[$this->MasterKey])) {
            $ClassName = 'GM_' . $this->MasterKey;
            new $ClassName;
        }

        $MyDisplayLink = array();
        $tempDiffArrayKey = array(
            'naigai' => ""
        , 'dest' => ""
        , 'senmon_name' => ""
        , 'senmon_name_en' => ""
        , 'css_name' => ""
        , 'url' => ""
        , 'req' => ""
        , 'map_type' => ""
        , 'key' => ""
        , 'facet' => ""
        , 'map_not_display' => 0
        );

        if ($this->MyPath == 'top') {
            //国内トップ　海外トップ
            //表示するリンクの配列を生成
            if (!is_array($GlobalMaster[$this->MasterKey][$this->MyNaigai][$this->MyHomen][$this->MyPath])) {
                //存在しないのでリターン
                return false;
            }

            $tempDiffArray = array_diff_key($GlobalMaster[$this->MasterKey][$this->MyNaigai][$this->MyHomen][$this->MyPath], $tempDiffArrayKey);
            //20110228修正
            if (empty($tempDiffArray) && !empty($GlobalMaster[$this->MasterKey][$this->MyNaigai][$this->MyPath])) {
                $tempDiffArray = $GlobalMaster[$this->MasterKey][$this->MyNaigai][$this->MyPath];
            }
        } else {

            //専門店
            //表示するリンクの配列を生成

            //オーストリアは東欧・中欧を国としない
            if ($this->MyPath == 'austria/') {
                $this->MyHomen = 'europe/';
            }
            if (!isset($GlobalMaster[$this->MasterKey][$this->MyNaigai][$this->MyHomen][$this->MyPath . '/'])
                    || !is_array($GlobalMaster[$this->MasterKey][$this->MyNaigai][$this->MyHomen][$this->MyPath . '/'])) {
                //存在しないのでリターン
                return false;
            }

            // 方面用
            $tempDiffArray = array_diff_key($GlobalMaster[$this->MasterKey][$this->MyNaigai][$this->MyHomen][$this->MyPath . '/'], $tempDiffArrayKey);

            //階層戻りリンク用
            if ($GlobalMaster[$this->MasterKey][$this->MyNaigai][$this->MyHomen][$this->MyPath . '/']['map_type'] == 'country') {
                if ($GlobalMaster[$this->MasterKey][$this->MyNaigai][$this->MyHomen]['map_type'] == 'homen') {
                    $this->backLink['naigai'] = $GlobalMaster[$this->MasterKey][$this->MyNaigai][$this->MyHomen]['naigai'];
                    $this->backLink['dest'] = $GlobalMaster[$this->MasterKey][$this->MyNaigai][$this->MyHomen]['dest'];
                    $this->backLink['senmon_name'] = $GlobalMaster[$this->MasterKey][$this->MyNaigai][$this->MyHomen]['senmon_name'];
                    $this->backLink['css_name'] = $GlobalMaster[$this->MasterKey][$this->MyNaigai][$this->MyHomen]['css_name'];
                    $this->backLink['url'] = $GlobalMaster[$this->MasterKey][$this->MyNaigai][$this->MyHomen]['url'];
                    $this->backLink['req'] = $GlobalMaster[$this->MasterKey][$this->MyNaigai][$this->MyHomen]['req'];
                    $this->backLink['map_type'] = $GlobalMaster[$this->MasterKey][$this->MyNaigai][$this->MyHomen]['map_type'];
                    $this->backLink['key'] = $GlobalMaster[$this->MasterKey][$this->MyNaigai][$this->MyHomen]['key'];
                    $this->backLink['facet'] = $GlobalMaster[$this->MasterKey][$this->MyNaigai][$this->MyHomen]['facet'];
                    $this->backLink['map_not_display'] = $GlobalMaster[$this->MasterKey][$this->MyNaigai][$this->MyHomen]['map_not_display'];
                } else {
                    $this->backLink = '';
                }
            }

            //20110228修正
            if (empty($tempDiffArray) && !empty($GlobalMaster[$this->MasterKey][$this->MyNaigai][$this->MyPath . '/'])) {
                $tempDiffArray = $GlobalMaster[$this->MasterKey][$this->MyNaigai][$this->MyPath . '/'];
            }
        }


        if (!empty($tempDiffArray)) {
            foreach ($tempDiffArray as $key => $arr) {
                if (is_array($arr) && isset($arr['key'])) {
                    $MyDisplayLink[$arr['req']] = array(
                        'senmon_name' => $arr['senmon_name']
                    , 'senmon_name_en' => $arr['senmon_name_en']
                    , 'css_name' => $arr['css_name']
                    , 'url' => $arr['url']
                    , 'req' => $arr['req']
                    , 'map_type' => $arr['map_type']
                    , 'facet' => 0
                    , 'map_not_display' => $arr['map_not_display']
                    );
                }
            }
        }


        //一括でファセットの取得
        $TabAllParams_Req = array(
            array(
             'p_hatsu_sub' => $this->MyHatsu                    //関東発
            ,'p_mokuteki' => $this->MyReq                    //目的地はイタリア
            , 'UseType' => 'TotalNum,' . $this->MyMapType    //全件と商品枠と都市のファセットが欲しい
            , 'MyNaigai' => $this->MyNaigai                //内外は絶対指定してください
            , 'p_bunrui' => $this->MyBunruiCode        //分類コード
            )
        );

        // memcacheからデータ取得
        $SenmonMemCache = new SenmonMemCache();

        $TabAllFacetObj = $SenmonMemCache->getMapFacetData(serialize($TabAllParams_Req));
        if ($TabAllFacetObj != false && !empty($TabAllFacetObj)) {
            $TabAllFacetObj = unserialize($TabAllFacetObj);
        }

        if (!isset($TabAllFacetObj->ResObj[$this->MyMapType][$this->MyDest])) {
            $TabAllFacetObj = new GetNumFacetTour($TabAllParams_Req);
            $SenmonMemCache->setMapFacetData(serialize($TabAllParams_Req), serialize($TabAllFacetObj));
        }

        //まったくかえって来なかった時のために
        $diffArray = $MyDisplayLink;

        if (isset($TabAllFacetObj->ResObj[$this->MyMapType][$this->MyDest])) {
            if ($this->MyMapType == 'City') {
                //一括で取得した分をセット
                foreach ($TabAllFacetObj->ResObj[$this->MyMapType][$this->MyDest] as $country_code => $tempArr) {
                    foreach ($tempArr as $key => $dispArr) {
                        $key = $this->MyDest . '-' . $country_code . '-' . $key;
                        //facetにセット
                        if (!empty($MyDisplayLink[$key]['senmon_name'])) {
                            $MyDisplayLink[$key]['facet'] = $dispArr['facet'];
                        }
                    }
                }
                $diffArray = array_diff_key($MyDisplayLink, $TabAllFacetObj->ResObj[$this->MyMapType][$this->MyDest]);
            } elseif ($this->MyMapType == 'Country') {
                //一括で取得した分をセット
                foreach ($TabAllFacetObj->ResObj[$this->MyMapType][$this->MyDest] as $key => $dispArr) {
                    $key = $this->MyDest . '-' . $key . '-';
                    //facetにセット
                    if (!empty($MyDisplayLink[$key]['senmon_name'])) {
                        $MyDisplayLink[$key]['facet'] = $dispArr['facet'];
                    }
                }
                $diffArray = array_diff_key($MyDisplayLink, $TabAllFacetObj->ResObj[$this->MyMapType][$this->MyDest]);
            }
        }



        //足りない分のファセット取得
        foreach ($MyDisplayLink as $key => $arr) {
            if ($arr['facet'] != 0) {
                continue;
            }
            if ($this->MyNaigai == 'i') {
                $TabAllParams_Req = array(
                    array(
                        'p_hatsu' => $this->MyHatsu                    //関東発
                    , 'p_mokuteki' => $arr['req']            //目的地はイタリア
                    , 'UseType' => 'TotalNum'                //全件と商品枠と都市のファセットが欲しい
                    , 'MyNaigai' => $this->MyNaigai                //内外は絶対指定してください
                    , 'p_bunrui' => $this->MyBunruiCode        //分類コード
                    )
                );
            } else {
                $TabAllParams_Req = array(
                    array(
                    'p_hatsu_sub' => $this->MyHatsu                    //関東発
                    , 'p_mokuteki' => $arr['req']            //目的地はイタリア
                    , 'UseType' => 'TotalNum'                //全件と商品枠と都市のファセットが欲しい
                    , 'MyNaigai' => $this->MyNaigai                //内外は絶対指定してください
                    , 'p_bunrui' => $this->MyBunruiCode        //分類コード
                    )
                );
            }

            $TabAllFacetObj = $SenmonMemCache->getMapFacetData(serialize($TabAllParams_Req));
            if ($TabAllFacetObj != false && !empty($TabAllFacetObj)) {
                $TabAllFacetObj = unserialize($TabAllFacetObj);
            }

            if (!isset($TabAllFacetObj->ResObj['TotalNum'])) {
                $TabAllFacetObj = new GetNumFacetTour($TabAllParams_Req);
                $SenmonMemCache->setMapFacetData(serialize($TabAllParams_Req), serialize($TabAllFacetObj));
            }


            //選択拠点の全てのファセット数
            $this->selectKyotenFacet = $TabAllFacetObj->ResObj['TotalNum'];
            //facetにセット
            if (!empty($MyDisplayLink[$key]['senmon_name'])) {
                $MyDisplayLink[$key]['facet'] = $TabAllFacetObj->ResObj['TotalNum'];
            }
        }


        $this->MyDisplayLink = $MyDisplayLink;
    }

    //表示用メソッド
    function display()
    {
        global $categoryType,$contetns_header_bool;


        if ($categoryType == CATEGORY_TYPE_DEST || $contetns_header_bool == true) {
            // 方面ページ用リンク作成
            $this->liList = $this->_displayHomenLink();
        } else if ($categoryType == CATEGORY_TYPE_COUNTRY) {
            // 国ページ用リンク作成
            $this->liList = $this->_displayCountryLink();
        } else if ($categoryType == CATEGORY_TYPE_CITY) {
            // 都市※現状、都市ではリンクを作らない
            //$this->liList = $this->_displayCityLink();
        }

        return $this->liList;
    }

    //表示用メソッド
    function _displayHomenLink()
    {
        global $GlobalMaster, $PathSharing14,$masterCsv,$senmonNameEnLower;

        //マスター一式
        $MyHatsuEnc = rawurlencode($this->MyMapHatsuName);

        // 小文字にする
        $senmonName = mb_strtolower($masterCsv[KEY_MASTER_CSV_NAME_EN]);

        $this->pList = '';
        $this->liList = '';
        $this->liList .= <<<EOF
			<div class="Map Map_{$this->MyPath}Base" id="senmonMap" >
				<div class="mapSachInHeight">
				<ul class="MapList">
EOF;
        /*必要な項目を配列よりセット リスト生成*/
        if (isset($this->MyDisplayLink)) {
            $this->liList .= <<<EOF
<ul class="map">
EOF;

            $count = 1;

            $tokyo_kind = '';
            $tokyo_full_array = array(
                "MusashinoTachikawaFuchuMitakaChofu",
                "TamaMachida",
                "izu-Oshima",
                "OkutamaOme",
                "OgasawaraIslands",
                "Hachijojima",
            );


//            var_dump($this->MyDisplayLink);exit;
            foreach ($this->MyDisplayLink as $key => $arr) {
                if ($arr['map_not_display'] == 1) {
                    continue;
                }

                // それぞれの県or観光都市コード
                $uniqueCode = str_replace("/", "", $arr['css_name']);
                $uniqueCode = str_replace(",", "", $uniqueCode);
                $uniqueCode = str_replace("·", "", $uniqueCode);

                if($senmonNameEnLower == 'tokyo'){
                    if(in_array($uniqueCode,$tokyo_full_array)){
                        $tokyo_kind = 'tokyo_full';
                    }else{
                        $tokyo_kind = 'tokyo_detail';
                    }
                }

                if (empty($this->MyKyotenFlg)) {
                    if ($arr['facet'] != 0) {

                        $this->liList .= <<<EOF
					<li class="mainBgClr {$senmonName}-map {$uniqueCode} {$tokyo_kind}" data="map$count"><a href="{$arr['url']}" id="{$arr['req']}_{$this->MyHatsu}" title="{$this->MyKyotenId}" class="senmonSubmitFn" name="{$arr['url']}">{$arr['senmon_name']}<span>[{$arr['facet']}]</span></a></li>
EOF;
                    } else {
                        $this->liList .= <<<EOF
					<li class="mainBgClr {$senmonName}-map {$uniqueCode} {$tokyo_kind} bgGray" data="map$count"><a href="" id="{$arr['req']}_{$this->MyHatsu}" title="{$this->MyKyotenId}" class="senmonSubmitFn disabled" name="{$arr['url']}" disabled>{$arr['senmon_name']}<span>[{$arr['facet']}]</span></a></li>
EOF;

                    }
                } else {
                    if ($arr['facet'] != 0) {
                        $this->liList .= <<<EOF
					<li class="mainBgClr {$senmonName}-map {$uniqueCode} {$tokyo_kind}" data="map$count"><a href="{$arr['url']}" id="{$arr['req']}_{$this->MyHatsu}" title="{$this->MyKyotenId}" class="senmonSubmitFn" name="{$arr['url']}">{$arr['senmon_name']}<span>[{$arr['facet']}]</span></a></li>
EOF;
                    } else {
                        $this->liList .= <<<EOF
					<li class="mainBgClr {$senmonName}-map {$uniqueCode} bgGray {$tokyo_kind}" data="map$count"><a href="" id="{$arr['req']}_{$this->MyHatsu}" title="{$this->MyKyotenId}" class="senmonSubmitFn disabled" name="{$arr['url']}">{$arr['senmon_name']}<span>[{$arr['facet']}]</span></a></li>

EOF;
                    }

                }
                $this->liList .= "\n";
                $count++;
            }
            $this->liList .= <<<EOF
</ul>
EOF;
        }
        if($senmonNameEnLower == 'tokyo'){
            $this->pList = <<<EOF
            <a class="wr-banner2-a change_tokyo_detail">東京23区から探す</a>
            <a class="wr-banner2-a change_tokyo_full">東京の全域から探す</a>
            <a href="/kanto/" class="wr-banner2-a back_kanto">関東方面から探す</a>
EOF;
        }elseif (!empty($this->backLink)) {
            $this->pList = <<<EOF
            <p class="list-afri-east"><a href="/{$this->backLink['css_name']}/" class="wr-banner2-c">{$this->backLink['senmon_name']}から探す</a></p>
EOF;
        }

        $this->liList .= <<<EOF
				</ul>
EOF;
        if ($this->MyPath == 'hokkaido') {
            $this->liList .= <<<EOF
            <ul class="list-afri-east clear">
                <li><a href="/tohoku/" class="wr-banner2-b">東北方面から探す</a></li>
            </ul>
EOF;
        }
        if ($this->MyPath == 'tohoku') {
            $this->liList .= <<<EOF
			<ul class="list-afri-east clear">
                <li><a href="/hokkaido/" class="wr-banner2-b">北海道から探す</a></li>
                <li><a href="/kanto/" class="wr-banner2-b">関東方面から探す</a></li>
                <li><a href="/chubu-hokuriku/" class="wr-banner2-b">中部・北陸方面から探す</a></li>
            </ul>
EOF;
        }
        if ($this->MyPath == 'kanto') {
            $this->liList .= <<<EOF
			<ul class="list-afri-east clear">
                <li><a href="/tohoku/" class="wr-banner2-b">東北方面から探す</a></li>
                <li><a href="/chubu-hokuriku/" class="wr-banner2-b">中部・北陸方面から探す</a></li>
            </ul>
EOF;
        }
        if ($this->MyPath == 'chubu-hokuriku') {
            $this->liList .= <<<EOF
			<ul class="list-afri-east clear">
                <li><a href="/tohoku/" class="wr-banner2-b">東北方面から探す</a></li>
                <li><a href="/kanto/" class="wr-banner2-b">関東方面から探す</a></li>
                <li><a href="/kinki/" class="wr-banner2-b">近畿方面から探す</a></li>
            </ul>
EOF;
        }
         if ($this->MyPath == 'kinki') {
            $this->liList .= <<<EOF
            <ul class="list-afri-east clear">
                <li><a href="/chubu-hokuriku/" class="wr-banner2-b">中部・北陸方面から探す</a></li>
                <li><a href="/sanin-sanyo/" class="wr-banner2-b">山陰・山陽方面から探す</a></li>
                <li><a href="/shikoku/" class="wr-banner2-b">四国方面から探す</a></li>
            </ul>
EOF;
        }
        if ($this->MyPath == 'sanin-sanyo') {
            $this->liList .= <<<EOF
            <ul class="list-afri-east clear">
                <li><a href="/kinki/" class="wr-banner2-b">近畿方面から探す</a></li>
                <li><a href="/shikoku/" class="wr-banner2-b">四国方面から探す</a></li>
                <li><a href="/kyushu/" class="wr-banner2-b">九州方面から探す</a></li>
            </ul>
EOF;
        }
        if ($this->MyPath == 'shikoku') {
            $this->liList .= <<<EOF
            <ul class="list-afri-east clear">
                <li><a href="/kinki/" class="wr-banner2-b">近畿方面から探す</a></li>
                <li><a href="/sanin-sanyo/" class="wr-banner2-b">山陰・山陽方面から探す</a></li>
            </ul>
EOF;
        }
        if ($this->MyPath == 'kyushu') {
            $this->liList .= <<<EOF
            <ul class="list-afri-east clear">
                <li><a href="/sanin-sanyo/" class="wr-banner2-b">山陰・山陽方面から探す</a></li>
            </ul>
EOF;
        }

        $this->liList .= <<<EOF
$this->pList
</div>
</div>
EOF;
        return $this->liList;
    }

    private function _displayCountryLink() {
        global $GlobalMaster, $PathSharing14,$masterCsv,$senmonNameEnLower;

        //マスター一式
        $MyHatsuEnc = rawurlencode($this->MyMapHatsuName);

        $backLink  = '';
        $liList  = '';

        // バックリンク
        if(!empty($this->backLink)) {
            $backLink = <<<EOF
                <a href="/{$this->backLink['css_name']}/" class="wr-banner2-a">{$this->backLink['senmon_name']}方面から探す</a>
EOF;
        }

        $liList .= '<ul class="map">';

        //必要な項目を配列よりセット リスト生成
        if (isset($this->MyDisplayLink)) {

            $count = 1;

            $senmonName = $masterCsv[KEY_MASTER_CSV_HOMEN];

            foreach ($this->MyDisplayLink as $key => $arr) {
                if ($arr['map_not_display'] == 1) {
                    continue;
                }

                // それぞれの都市のコード
                $uniqueCode = str_replace("/", "", $arr['css_name']);
                $uniqueCode = str_replace(",", "", $uniqueCode);
                $uniqueCode = str_replace("·", "", $uniqueCode);
                $uniqueCode = str_replace(".", "", $uniqueCode);

                if ($arr['facet'] != 0) {
                    $liList .= <<<EOF
                    <li class="{$uniqueCode}"><a href="{$arr['url']}">{$arr['senmon_name']}<span>[{$arr['facet']}]</span></a></li>
EOF;
                    } else {
                        $liList .= <<<EOF
                    <li class="{$uniqueCode}"><a href="{$arr['url']}" class="disabled">{$arr['senmon_name']}<span>[{$arr['facet']}]</span></a></li>
EOF;

                }
                $liList .= "\n";
                $count++;
            }
        }

        $liList .= '</ul>';

        return $backLink.$liList;
    }
    private function _displayCityLink() {
        return '';
    }

    public function backLink()
    {
        global $GlobalMaster;                        //マスター一式

        /*マスターを持ってなければ、マスターを作るクラス実行*/
        if (empty($GlobalMaster[$this->MasterKey])) {
            $ClassName = 'GM_' . $this->MasterKey;
            new $ClassName;
        }


    }

    public function setKyotenFlg($MyKyotenFlg, $MyKyotenId)
    {
        $this->MyKyotenFlg = $MyKyotenFlg;
        $this->MyKyotenId = $MyKyotenId;
    }

    public function setBunruiCode($MyBunruiCode)
    {
        $this->MyBunruiCode = $MyBunruiCode;
    }
}


?>
