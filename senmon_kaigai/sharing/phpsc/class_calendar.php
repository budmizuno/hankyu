<?php
/*
########################################################################
#
#	普通の専門店で使う検索BOXのclass群です。
#
########################################################################
*/

/*元ネタは都市型です*/
include_once($SharingPSPath . 'class_search_forTypeCity.php');


/*******************************************************
 * ページに入ってきたときの初期状態を表示する。
 *
 * 引数
 *        $Naigai                    :str    ：内外（必須：i or d）
 *        $p_mokuteki                :str    ：目的地　※定義書通り
 *        $p_hatsu                    :str    ：出発地　※定義書通り
 *        $SubKyotenCode            :str    ：サブ拠点ID（必須）
 *        $KyoteHatsuAry        :ary    ：拠点の出発地情報（必須）
 *******************************************************/
class SearchActionForCountry extends SearchActionForCity
{
    #=======================
    #	返値一覧
    #=======================
    public $MyNaigai;    //内外判定
//	public $RqParamAry_forView;	//表示に使うリクエストパラ
    public $ResObj;    //商品部分ママ返却
    public $FacetObj;    //見やすくなったFacet
    public $StatusObj;    //見やすくなった統計情報
    public $Values;    //表示用に加工された配列（keyはリクエストパラと同一）

    /*使うglobal変数*/
    //$GlobalSolrReqParamAry（solr_access.phpにあります）

    #=======================
    #	初動
    #=======================
    function __construct($Naigai, $p_mokuteki = NULL, $p_hatsu = NULL, $SubKyotenCode, $KyoteHatsuAry)
    {

        global $PathSharing, $GlobalSolrReqParamAry, $GlobalSetStrAry, $SettingData;
        /*受け取ったリクエストパラを、solrに渡す準備をします*/
        $this->MyNaigai = $Naigai;
        $this->SubKyotenCode = $SubKyotenCode;
        $this->KyoteHatsuAry = $KyoteHatsuAry;
        $GlobalSolrReqParamAry[$Naigai]['p_mokuteki'] = NULL;
        if ($Naigai == 'i') {
            $Request = array(
                'p_mokuteki' => $p_mokuteki
            , 'p_hatsu' => $p_hatsu
            );
        } elseif ($Naigai == 'd') {
            $Request = array(
                'p_mokuteki' => $p_mokuteki
            , 'p_hatsu_sub' => $p_hatsu
            );
        } else {
            $Request = array(
                'p_mokuteki' => $p_mokuteki
            , 'p_hatsu' => $p_hatsu
            );
        }

        $this->ActRequestForSolr($Request);

        /*応答データ形式を指定*/
        $GlobalSolrReqParamAry[$this->MyNaigai]['p_data_kind'] = '1';    //ファセットのみ
        //返して欲しい項目は、内外別
        if ($this->MyNaigai == 'i') {
            $GlobalSolrReqParamAry[$this->MyNaigai]['p_rtn_data'] = 'p_hatsu_name,p_conductor,p_dest_name,p_country_name,p_city_cn';    //ファセットを返してほしい項目
        } else {
            $GlobalSolrReqParamAry[$this->MyNaigai]['p_rtn_data'] = 'p_carr_cn,p_dest_name,p_prefecture_name,p_region_cn,p_price_flg,p_bus_boarding_name';    //こっちは国内
        }
        /*DB通信*/
        $SolrObj = new SolrAccess($this->MyNaigai);    //solrのレスポンス：ママ
        /*エラー処理*/
        $this->ActErr($SolrObj);

        /*表示用に加工します*/
        $this->MakeValues();

        /*上の部分表示*/
        include($PathSharing . 'inc/kyotentab_searchbox_' . $Naigai . '.php');
    }



    #=======================
    #	表示用に加工します
    #=======================
    function MakeValues()
    {
        global $GlobalSolrReqParamAry;
        foreach ($GlobalSolrReqParamAry[$this->MyNaigai] as $ParamName => $ValueAry) {
            switch ($ParamName) {
                case 'p_hatsu':
                case 'p_hatsu_sub':
                case 'p_conductor':
                case 'p_mokuteki':
                    $this->$ParamName($ParamName);
                    break;

                //その他のパラメータは無視する
                default:
                    break;
            }
        }

    }


    /*+++++++++++++++
        目的地
    +++++++++++++++++*/
    function p_mokuteki($ParamName = '')
    {
        global $GlobalSolrReqParamAry;
        if (empty($ParamName)) {
            $ParamName = 'p_mokuteki';
        }
        $MokuReqPara = $GlobalSolrReqParamAry[$this->MyNaigai][$ParamName];
        /*--- チェックパラ配列 ---*/
        $CheckParamNameAry = array(
            'i' => array('p_dest_name', 'p_country_name', 'p_city_cn')
        , 'd' => array('p_dest_name', 'p_prefecture_name', 'p_region_cn')
        );
        /*リクエストパラの処理開始*/
        if (empty($MokuReqPara)) {
            $MokuReqPara = '--';
            $typeTopFlg = 1;
        }
        //リクエストパラの分割
        $MokutekiAry = explode(',', $MokuReqPara);

        /*方面・国・都市の配列を作る。一度分解しないと判別できないよ*/
        foreach ($MokutekiAry as $MokutekiSet) {
            //分割
            list($DestCode, $CountryCode, $CityCode) = explode('-', $MokutekiSet);
            if ($typeTopFlg !== 1) {
                //まとめ配列
                $MatomeAry[$DestCode][$CountryCode][$CityCode] = '';
            }
            /*方面の処理*/
            if (!empty($DestCode)) {
                /*ファセットから和名を探す*/
                $DestName = '';
                $DestName = $this->FacetObj->RetFacet[$CheckParamNameAry[$this->MyNaigai][0]][$DestCode]['name'];
                //無かったらマスタ
                if (empty($DestName)) {
                    $DestName = $this->GetNameFromMasterMokuteki('p_dest', $CheckParamNameAry[$this->MyNaigai][0], $DestCode, $this->MyNaigai);
                }
                //それでも無かったら対象外
                if (!empty($DestName)) {
                    $DestAry[$DestCode] = $DestName;
                }
            }
            /*国の処理*/
            if (!empty($CountryCode)) {
                $CountryName = '';
                /*ファセットから和名を探す*/
                $CountryName = $this->FacetObj->RetFacet[$CheckParamNameAry[$this->MyNaigai][1]][$DestCode][$CountryCode]['name'];
                //無かったらマスタ
                if (empty($CountryName)) {
                    $CountryName = $this->GetNameFromMasterMokuteki('p_country', $CheckParamNameAry[$this->MyNaigai][1], $CountryCode, $this->MyNaigai);
                }
                //それでも無かったら対象外
                if (!empty($CountryName)) {
                    $CountryAry[$CountryCode] = $CountryName;
                }
//				$CountryAry[$CountryCode] = $CountryName;
            }
            //都市
            if (!empty($CityCode)) {
                $CityName = '';
                /*ファセットから和名を探す*/
                $CityName = $this->FacetObj->RetFacet[$CheckParamNameAry[$this->MyNaigai][2]][$DestCode][$CountryCode][$CityCode]['name'];
                //無かったらマスタ
                if (empty($CityName)) {
                    $CityName = $this->GetNameFromMasterMokuteki('p_city', $CheckParamNameAry[$this->MyNaigai][2], $CityCode, $this->MyNaigai);
                }
                //それでも無かったら対象外
                if (!empty($CityName)) {
                    $CityAry[$CityCode] = $CityName;
                }
            }
        }


        /*いよいよ見た目を作ります*/
        //空っぽの国都市をデフォルトに。
        $this->Values['Country'] = <<<EOD
<select name="preCountry" id="preCountry">
<option value="">選択してください</option>
</select>

EOD;
        $this->Values['City'] = <<<EOD
<select name="preCity" id="preCity">
<option value="">選択してください</option>
</select>

EOD;
        /*------------ 方面系 ------------*/
        $Opt = NULL;
        //トップ系の場合
        if (empty($DestAry) && $typeTopFlg == 1) {
            /*プルダウンを作れる*/
            $FacetAry = $this->GetValidAryFromFacet($CheckParamNameAry[$this->MyNaigai][0]);
            $Opt .= <<<EOD
<option value="">選択してください</option>

EOD;
            if (!empty($FacetAry)) {
                foreach ($FacetAry as $MyCode => $MyAry) {
                    $Opt .= <<<EOD
<option value="{$MyCode}">{$MyAry['name']}</option>

EOD;
                }
            }
        } //方面が複数の専門店
        elseif (count($DestAry) > 1) {
            $OptAdd = NULL;
            $OptHead = NULL;
            $OptCnt = 0;
            foreach ($DestAry as $MyCode => $MyStr) {
                if ($OptCnt > 0) {
                    $OptHead .= ',';
                }
                $OptHead .= $MyCode;
                $OptAdd .= <<<EOD
<option value="{$MyCode}">{$MyStr}</option>
EOD;
                $OptCnt++;
            }
            $Opt = <<<EOD
<option value="{$OptHead}">選択してください</option>
$OptAdd
EOD;
        } //方面がひとつの場合
        elseif (count($DestAry) === 1) {
            foreach ($DestAry as $DestCode => $DestName) {
                $this->Values['Dest'] = <<<EOD
<strong>{$DestName}</strong><input type="hidden" name="preDest" id="preDest" value="{$DestCode}" />

EOD;
            }
            /*------------ 国系 ------------*/
            $OptC = NULL;

            //国が複数の専門店
            if (count($CountryAry) > 1) {
                $OptAdd = NULL;
                $OptHead = NULL;
                $OptCnt = 0;
                foreach ($CountryAry as $MyCode => $MyStr) {
                    if ($OptCnt > 0) {
                        $OptHead .= ',';
                    }
                    $OptHead .= $MyCode;
                    $OptAdd .= <<<EOD
<option value="{$MyCode}">{$MyStr}</option>
EOD;
                    $OptCnt++;
                }
                $OptC = <<<EOD
<option value="{$OptHead}">選択してください</option>
$OptAdd
EOD;
            } //国がひとつ
            else {
                //NULLってこととは、方面専門店の場合
                if (empty($CountryAry)) {
                    //国ファセットから取得
                    $OptC = <<<EOD
<option value="{$OptHead}">選択してください</option>

EOD;
                    foreach ($this->FacetObj->RetFacet[$CheckParamNameAry[$this->MyNaigai][1]][$DestCode] as $MyCode => $MyAry) {
                        if ($MyAry['facet'] < 1) {
                            continue;
                        }
                        $OptC .= <<<EOD
<option value="{$MyCode}">{$MyAry['name']}</option>

EOD;
                    }
                } //ホントに1カ国
                else {
                    foreach ($CountryAry as $CountryCode => $CountryName) {
                    }
                    $this->Values['Country'] = <<<EOD
<strong>{$CountryName}</strong><input type="hidden" name="preCountry" id="preCountry" value="{$CountryCode}" />

EOD;
                    /*------------ 都市系 ------------*/
                    $OptCi = NULL;
                    //都市が複数の専門店
                    if (count($CityAry) > 1) {
                        foreach ($CityAry as $MyCode => $MyStr) {
                            $OptCi .= <<<EOD
<option value="{$MyCode}">{$MyStr}</option>

EOD;
                        }
                    } //都市がひとつ
                    else {
                        //NULLってこととは、国専門店の場合
                        if (empty($CityAry)) {
                            //国ファセットから取得
                            foreach ($this->FacetObj->RetFacet[$CheckParamNameAry[$this->MyNaigai][2]][$DestCode][$CountryCode] as $MyCode => $MyAry) {
                                if ($MyAry['facet'] < 1) {
                                    continue;
                                }
                                $OptCi .= <<<EOD
<option value="{$MyCode}">{$MyAry['name']}</option>

EOD;
                            }
                        } //本当に1都市
                        else {
                            $this->Values['City'] = <<<EOD
<strong>{$CityName}</strong><input type="hidden" name="preCity" id="preCity" value="{$CityCode}" />

EOD;
                        }
                    }
                }

            }

        }

        /*方面がたくさん*/
        if (!empty($Opt)) {
            $this->Values['Dest'] = <<<EOD
<select name="preDest" id="preDest">
$Opt
</select>

EOD;
        }
        /*国がたくさん*/
        if (!empty($OptC)) {
            $this->Values['Country'] = <<<EOD
<select name="preCountry" id="preCountry">
$OptC
</select>

EOD;
        }
        /*都市がたくさん*/
        if (!empty($OptCi)) {
            $this->Values['City'] = <<<EOD
<select name="preCity" id="preCity">
<option value="">選択してください</option>
$OptCi
</select>

EOD;
        }
    }


    /*+++++++++++++++
        出発地（海外・国内）
    +++++++++++++++++*/
    function p_hatsu($ParamName)
    {
        global $GlobalMaster, $GlobalSolrReqParamAry;
        /*拠点名をげっと*（北関東がいる場合があるので、孫ゲット）*/
        $KyotenListAry = new MakeKyotenSimpleGList;
        /*全国タブの場合*/
        if ($this->SubKyotenCode == 'contents') {
            $Opt = NULL;
            foreach ($this->KyoteHatsuAry as $MyKyotenCode => $MyKyotenAry) {
                //サブを全部つなげる
                $Codes = array_keys($MyKyotenAry);
                $CodesStr = implode(',', $Codes);
                $Opt .= <<<EOD
<option value="{$CodesStr}" class="{$MyKyotenCode}">{$KyotenListAry->TgDataAry[$MyKyotenCode]}発</option>

EOD;
            }

            //書き出し用
            $this->Values[$ParamName] = <<<EOD
<select name="{$ParamName}" class="setDefKyoten_S" id="p_hatsu">
	<option value="">選択してください</option>
	$Opt
</select>
<span class="SachRequire" id="RQp_hatsu">必須</span>

EOD;

        } /*拠点タブの場合*/
        else {
            $MyKyotenInfo = $this->KyoteHatsuAry[$this->SubKyotenCode];
            $KyotenNameJ = $KyotenListAry->TgDataAry[$this->SubKyotenCode];
            //書き出し用
            $this->Values[$ParamName] = <<<EOD
<input type="hidden" name="{$ParamName}" value="{$GlobalSolrReqParamAry[$this->MyNaigai][$ParamName]}" id="p_hatsu" />
<strong>{$KyotenNameJ}発</strong>
EOD;
        }
    }

    function p_hatsu_sub($ParamName)
    {
        $this->p_hatsu($ParamName);
    }


    /*+++++++++++++++
        出発空港
    +++++++++++++++++*/
    function p_dep_airport_code($ParamName)
    {
        $this->Values[$ParamName] = NULL;
        //ファセットを1件以上のものだけにする
        $FacetAry = $this->GetValidAryFromFacet('p_dep_airport_name');
        if (empty($FacetAry)) {
            //一件も無かったら、「選択できません」でサヨナラ
            $this->Values[$ParamName] .= <<<EOD
<strong>選択できません</strong>
EOD;
            return;
        }
        //とりあえずひとつはある。
        $Opt = '';
        foreach ($FacetAry as $key => $ary) {
            $MyName = MyEcho($ary['name']);
            $Opt .= <<<EOD
<option value="{$key}">{$MyName}</option>

EOD;
        }
        $this->Values[$ParamName] .= <<<EOD
<select name="{$ParamName}" id="p_dep_airport_code">
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

class AjaxSearchActionForSearchBox extends SearchActionForCountry
{

//	public $MyNaigai;	//内外判定
//	public $ResObj;	//商品部分ママ返却
//	public $FacetObj;	//見やすくなったFacet
//	public $StatusObj;	//見やすくなった統計情報
//	public $Values;	//表示用に加工された配列（keyはリクエストパラと同一）


    #=======================
    #	初動
    #=======================
    function __construct()
    {
        global $PathSharing, $GlobalSolrReqParamAry, $GlobalSetStrAry;
        $Request = $_REQUEST;
        /*
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
                    $Request['p_mokuteki'] = $Request['preDest'] . '-' . $Request['preCountry'] . '-' . $Request['preCity'];
                }
            }
            //何もないときはNULL
            if($Request['p_mokuteki'] == '--'){
                $Request['p_mokuteki'] = NULL;
            }
    */
        /*いんくるファイルが存在する場所*/
        $this->IncDirName = $PathSharing . 'inc/';

        //内外の判定
        $this->MyNaigai = $Request['MyNaigai'];
        /*受け取ったリクエストパラを、solrに渡す準備をします*/
        $this->ActRequestForSolr($Request);

        /*--- チェックパラ配列 ---*/
        $this->CheckParamNameAry = array(
            'i' => array('p_dest_name', 'p_country_name', 'p_city_cn', 'p_dest_name')
        , 'd' => array('p_dest_name', 'p_prefecture_name', 'p_region_cn', 'p_dest_name,p_dep_airport_name')
        );


        /*応答データ形式を指定*/
        $GlobalSolrReqParamAry[$this->MyNaigai]['p_data_kind'] = '1';    //ファセットのみ
        /*送る前の判定と処理*/
        $this->ActRequestForSolrJogai($Request);
        /*DB通信*/
        $SolrObj = new SolrAccess($this->MyNaigai);    //solrのレスポンス：ママ
        $this->ActErr($SolrObj);
        /*出発日*/
        if ($Request['SetParam'] == 'p_dep_date') {
            echo $this->p_dep_date($Request['SetParam'], $SolrObj);
            return;
        } /*返してほしいパラがあったら返す（目的地の処理）でも、0件ならやらない*/
        elseif ($Request['SetParam'] != NULL && $this->ResObj->p_hit_num > 0) {
            $Add = $this->p_mokuteki($Request);
        }
        /*バス専門店の場合*/
        if ($Request['MyType'] == 'bus' && $this->ResObj->p_hit_num > 0 && $Request['SetParam'] != 'p_bus_boarding_name') {
            $Add .= $this->p_bus_boarding_code($Request);
        }
        /*他にも返してほしいパラがあったとき*/
        if ($Request['AddRetType'] != NULL && $this->ResObj->p_hit_num > 0) {
            $Add .= $this->AddRetFnc($Request);
        }

        /*書き出し*/
        echo <<<EOD
$('span#{$this->MyNaigai}p_hit_num').html({$this->ResObj->p_hit_num});
$Add
EOD;

    }

    #=======================
    #	追加で返してほしいパラメータがある場合。p_carrしか検証してないよ！追加したい人は自分で見てね！
    #=======================
    function AddRetFnc($Request)
    {
        //いくつかあるかもしれないので、カンマで区切って配列へ
        if (!$Request['AddRetType']) {
            return;
        }
        $AddRetParamAry = explode(',', $Request['AddRetType']);
        //あるなし判定一応する
        if (!is_array($AddRetParamAry)) {
            return;
        }
        //ほしいパラメータごとにグルグル
        foreach ($AddRetParamAry as $ParamName) {
            if (!$ParamName || $ParamName == $Request['SetParam']) {
                continue;
            }
            $setParamName = $ParamName;
            //キャリアは返却名称が変わる
            if ($setParamName == 'p_carr') {
                $setParamName .= '_cn';
            } elseif ($setParamName == 'p_dep_airport_code') {
                $setParamName = str_replace('_code', '_name', $setParamName);
            }
            $this->$ParamName($setParamName);
            $RetOpt = $this->Values[$setParamName];

            //改行とタブトル
            $RetOpt = str_replace(array("\r\n", "\n", "\r", "\t"), '', $RetOpt);
            $RetJS .= <<<EOD
$('#{$this->MyNaigai}SearchBox select#{$ParamName}').append('{$RetOpt}');

EOD;


        }
        return $RetJS;
    }


    #=======================
    #	自分のパラメータはリクエストパラからは除外する場合がある。その判定
    #=======================
    function ActRequestForSolrJogai($Request)
    {
        global $GlobalSolrReqParamAry;
        switch ($Request['SetParam']) {
            case 'p_hatsu':
            case 'p_hatsu_sub':
            case 'p_dep_airport_code':
                //出発地が変わったら、その下を変えなきゃ
                if ($Request['RetParam'] == '') {
                    $GlobalSolrReqParamAry[$this->MyNaigai]['p_rtn_data'] = 'p_conductor';    //返すパラが無いときは、どーでもいい
                } else {
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
                if (empty($_REQUEST['ViewMonth'])) {
                    $this->ViewTG = $this->MyParamValue;
                } //前へ次へだったら
                else {
                    $this->ViewTG = $_REQUEST['ViewMonth'];
                }
                //最初の表示月
                if (empty($this->ViewTG)) {
                    $today = date('j');
                    if ($today > 20) {
                        //21日以降は来月を表示
                        $GlobalSolrReqParamAry[$this->MyNaigai][$MyParam] = date('Ym', strtotime(date('Y-m-1') . '+1 month'));
                    } else {
                        //設定が無かったら今月指定
                        $GlobalSolrReqParamAry[$this->MyNaigai][$MyParam] = $ThisMonth;
                    }
                } else {
                    //設定があったら
                    $SetDateY = substr($this->ViewTG, 0, 4);
                    $SetDateM = substr($this->ViewTG, 4, 2);
                    $SetDateYM = date('Ym', mktime(0, 0, 0, $SetDateM - 1, 1, $SetDateY));    //1ヶ月前を出しておく
                    //1ヶ月前が今月より以前だったら、今月ですよ
                    if ($SetDateYM < $ThisMonth) {
                        $GlobalSolrReqParamAry[$this->MyNaigai][$MyParam] = $ThisMonth;
                    } else {
                        $GlobalSolrReqParamAry[$this->MyNaigai][$MyParam] = $SetDateYM;
                    }
                }
                /*返してほしい項目について*/
                $GlobalSolrReqParamAry[$this->MyNaigai]['p_rtn_data'] = '';    //出発日のときはNULL
                /*出発日は応答データ形式を指定*/
                $GlobalSolrReqParamAry[$this->MyNaigai]['p_data_kind'] = '4';    //ファセットのみ
                break;

            //あとはどーでもいい
            default:
                $GlobalSolrReqParamAry[$this->MyNaigai]['p_rtn_data'] = 'p_conductor';
                break;
        }
        //バス専門店の場合
        if ($Request['MyType'] == 'bus' && $GlobalSolrReqParamAry[$this->MyNaigai]['p_rtn_data'] !== NULL) {
            $GlobalSolrReqParamAry[$this->MyNaigai]['p_rtn_data'] .= ',p_bus_boarding_name';
        }
        /*他にも返してほしいパラがあったとき*/
        if ($Request['AddRetType'] != NULL && $GlobalSolrReqParamAry[$this->MyNaigai]['p_rtn_data'] !== NULL) {
            //一度分解して_cn付けなきゃいけないのがある
            $AddRetParamAry = explode(',', $Request['AddRetType']);
            foreach ($AddRetParamAry as $AddRetParam) {
                if ($AddRetParam == 'p_carr') {
                    $LastParamAry[] = $AddRetParam . '_cn';
                } elseif ($AddRetParam == 'p_dep_airport_code') {
                    $LastParamAry[] = str_replace('_code', '_name', $AddRetParam);
                } else {
                    $LastParamAry[] = $AddRetParam;
                }
            }
            //もっかいつなげる
            $AddRetParamStr = implode(',', $LastParamAry);
            $GlobalSolrReqParamAry[$this->MyNaigai]['p_rtn_data'] .= ',' . $AddRetParamStr;
        }
    }


    #=======================
    #	バス乗車地もいろいろしないと。
    #=======================
    function p_bus_boarding_code($Request)
    {
        //ファセットを1件以上のものだけにする
        $BusBoardAry = $this->FacetObj->RetFacet['p_bus_boarding_name'];
        $RetOpt = NULL;
        if (is_array($BusBoardAry)) {
            //バス乗車地は都道府県ごとになっているのです
            foreach ($BusBoardAry as $PrefectureCode => $BoardAry) {
                foreach ($BoardAry as $key => $ary) {
                    if ($ary['facet'] > 0) {
                        $MyName = MyEcho($ary['name']);
                        $RetOpt .= <<<EOD
<option value="{$key}">{$MyName}</option>

EOD;
                    }
                }
            }
        }
        //改行とタブトル
        $RetOpt = str_replace(array("\r\n", "\n", "\r", "\t"), '', $RetOpt);
        $RetJS = <<<EOD
$('#{$this->MyNaigai}SearchBox select#p_bus_boarding_code').append('{$RetOpt}');

EOD;
        return $RetJS;
    }

    /*+++++++++++++++
        出発空港
    +++++++++++++++++*/
    function p_dep_airport_code($ParamName)
    {
        $RetJS = NULL;
        //ファセットを1件以上のものだけにする
        $FacetAry = $this->GetValidAryFromFacet('p_dep_airport_name');
        $RetOpt = '';
        if (empty($FacetAry)) {
            //一件も無かったら、「選択できません」
            $RetOpt .= <<<EOD
<option value="" selected="selected">選択できません</option>

EOD;
        } else {
            //とりあえずひとつはある。
            foreach ($FacetAry as $key => $ary) {
                $MyName = MyEcho($ary['name']);
                $RetOpt .= <<<EOD
<option value="{$key}">{$MyName}</option>

EOD;
            }
        }
        //改行とタブトル
        $RetOpt = str_replace(array("\r\n", "\n", "\r", "\t"), '', $RetOpt);
        $RetJS = <<<EOD
$('#{$this->MyNaigai}SearchBox select#p_dep_airport_code').append('{$RetOpt}');

EOD;
        return $RetJS;
    }


    #=======================
    #	目的地はいろいろしないと。
    #=======================
    function p_mokuteki($Request)
    {
        switch ($Request['RetParam']) {
            //方面
            case 0:
            case 3:    //3は、方面と出発空港両方。国内のみ
                if ($Request['RetParam'] === '') {
                    return;
                    break;
                } else {
                    if ($Request['RetParam'] == 3) {
                        list($HatsuName, $HatsuAirPort) = explode(',', $this->CheckParamNameAry[$this->MyNaigai][$Request['RetParam']]);
                        //出発空港処理
                        $addRetJS = $this->p_dep_airport_code($HatsuAirPort);
                    } else {
                        $HatsuName = $this->CheckParamNameAry[$this->MyNaigai][$Request['RetParam']];
                    }
                }
                //複数方面専門店の場合
                if (strpos($Request['preDest'], ',') !== false) {
                    $preDestAry = explode(',', $Request['preDest']);
                    foreach ($preDestAry as $DestCode) {
                        $DestName = $this->FacetObj->RetFacet[$HatsuName][$DestCode]['name'];
                        //無かったらマスタ
                        if (empty($DestName)) {
                            $DestName = $this->GetNameFromMasterMokuteki('p_dest', $HatsuName, $DestCode, $this->MyNaigai);
                        }
                        $ForEachVar[$DestCode] = array(
                            'facet' => $this->FacetObj->RetFacet[$HatsuName][$DestCode]['facet']
                        , 'name' => $DestName
                        );
                    }
                } //フツーの専門店
                else {
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
        if (!empty($ForEachVar)) {
            foreach ($ForEachVar as $MyCode => $MyAry) {
                if ($MyAry['facet'] < 1) {
                    continue;
                }
                $RetOpt .= <<<EOD
<option value="{$MyCode}">{$MyAry['name']}</option>

EOD;
            }
        }
        //改行とタブトル
        $RetOpt = str_replace(array("\r\n", "\n", "\r", "\t"), '', $RetOpt);
//$('#{$this->MyNaigai}SearchBox select#pre{$TgAppend}').append('{$RetOpt}');
        $RetJS = <<<EOD
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
    function p_dep_date($MyParam, $SolrObj)
    {
        global $GlobalMaster, $GlobalSolrReqParamAry, $SharingMasterPath, $PathMntItecReal;    //マスター一式

        /*--------*/
        #	カレンダー作ります
        /*--------*/
        //休日ファイルの置き場
        $HolidayFile = $PathMntItecReal . 'm_holiday/m_holiday.csv';
        //休日一覧
        //$Holidays = file_get_contents($HolidayFile);
        /*リクエストした月が起点*/
        $BaseY = substr($GlobalSolrReqParamAry[$this->MyNaigai]['p_dep_date'], 0, 4);
        $BaseM = substr($GlobalSolrReqParamAry[$this->MyNaigai]['p_dep_date'], 4, 2);

        /*3ヶ月分作ります*/
        $Table = NULL;
        for ($j = 0; $j < 3; $j++) {
            $BackLink = NULL;
            $NextLink = NULL;
            //表示したい年月
            $MyY = date('Y', mktime(0, 0, 0, $BaseM + $j, 1, $BaseY));
            $MyM = date('m', mktime(0, 0, 0, $BaseM + $j, 1, $BaseY));
            //月末
            $MyLastDay = date('j', mktime(0, 0, 0, $MyM + 1, 0, $MyY));

            /*divが始まります*/
            if ($j % 3 == 0) {    //区切りは3つ
                //今が先頭で、しかも今月よりも後だったら、前へリンク表示
                if ($j == 0 && $MyY . $MyM > date('Ym')) {
                    $TgBackMonth = date('Ym', mktime(0, 0, 0, $MyM - 2, 1, $MyY));
                    $BackLink = <<<EOD
<a href="#" class="SW_CalBack" onclick="NextBackBtnAction({$TgBackMonth});void(0);return false;">←</a>
EOD;
                }

                $Table .= <<<EOD
<div class="SW_SelDate FClear">
<p class="SW_CalBtn OnFLeft">{$BackLink}</p>
EOD;
            }
            /*日にち分グルグル*/
            $Tr = NULL;
            for ($i = 1; $i <= $MyLastDay; $i++) {
                //何曜日？
                $MyWeek = date('w', mktime(0, 0, 0, $MyM, $i, $MyY));
                //何日？
                $MyDay = date('Ymd', mktime(0, 0, 0, $MyM, $i, $MyY));
                //日曜日の場合に行開始
                if ($MyWeek == 0 && $i !== 1) {
                    $Tr .= '<tr>';
                }
                //1日の場合
                if ($i === 1) {
                    $Tr .= '<tr>' . str_repeat('<td class="non">&nbsp;</td>', $MyWeek);
                }
                $Class = NULL;
                //日曜日
                if ($MyWeek == 0) {
                    $Class = 'sun';
                } //土曜日
                elseif ($MyWeek == 6) {
                    $Class = 'sat';
                }
                //祝日
                if (stripos($Holidays, $MyDay) !== false) {
                    $Class = 'hol';
                }
                //出発日設定されていたら
                //$Class = '';
                if (!empty($this->MyParamValue) && stripos($MyDay, $this->MyParamValue) !== false) {
                    $Class .= ' sel';
                }
                //クラスが存在してたら
                if (!empty($Class)) {
                    $Class = ' class="' . $Class . '"';
                }
                //ファセットある
                if ($this->FacetObj->RetFacet['p_dep_day'][$MyDay]['facet'] > 0 && $MyDay >= date('Ymd')) {
                    $WriteMyDay = date("Y", strtotime($MyDay)) . '/' . date("n", strtotime($MyDay)) . '/' . date("j", strtotime($MyDay));
                    $Tr .= <<<EOD
<td{$Class}><a href="#" onclick="SWDateFp(\'{$WriteMyDay}\',this);void(0);return false;">{$i}</a></td>
EOD;
                } //ファセットない
                else {
                    $Tr .= "<td{$Class}>{$i}</td>";
                }
                //月末の場合
                if ($i == $MyLastDay) {
                    $Tr .= str_repeat('<td class="non">&nbsp;</td>', 6 - $MyWeek) . '</tr>';
                }
                //土曜日の場合に行終了
                if ($MyWeek == 6 && $i !== $MyLastDay) {
                    $Tr .= '</tr>';
                }

            }
            $DepMonth = $MyY . $MyM . '01';
            //月のファセットない
            if (empty($this->FacetObj->RetFacet['p_dep_month'][$DepMonth]['facet'])) {
                $MonthView = <<<EOD
{$MyY}年{$MyM}月
EOD;
            } //ファセットある
            else {
                $ViewMonth = intval($MyM);
                $MonthView = <<<EOD
<a href="#" onclick="SWDateFp(\'{$MyY}/{$MyM}\',this);void(0);return false;">{$MyY}年{$ViewMonth}月<span style="color:#ffffff">　すべてを選択する</span></a>
EOD;
            }

            //テーブルに入れるよ
            $Table .= <<<EOD
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
            if ($j % 3 == 2) {    //区切りは3つ
                //今が最後だったら次へリンク表示
                if ($j == 2) {
                    $TgNextMonth = date('Ym', mktime(0, 0, 0, $MyM + 2, 1, $MyY));
                    $NextLink = <<<EOD
<a href="#" class="SW_CalNext" onclick="NextBackBtnAction({$TgNextMonth});void(0);return false;">→</a>
EOD;
                }

                $Table .= <<<EOD
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
        $ViewWin = ob_get_contents();
        ob_end_clean();
        //改行とタブトル
        $ViewWin = str_replace(array("\r\n", "\n", "\r", "\t"), '', $ViewWin);
//$('span#{$this->MyNaigai}p_hit_num').html({$this->ResObj->p_hit_num});
        $Ret = <<<EOD
$('div#SubWinBox-Fp').html('{$ViewWin}');
EOD;
        return $Ret;
    }

}


/*******************************************************
 * バス専門店用
 *
 * 引数
 *        $Naigai                    :str    ：内外（必須：i or d）
 *        $p_mokuteki                :str    ：目的地　※定義書通り
 *        $p_hatsu                    :str    ：出発地　※定義書通り
 *        $SubKyotenCode            :str    ：サブ拠点ID（必須）
 *        $KyoteHatsuAry        :ary    ：拠点の出発地情報（必須）
 *******************************************************/
class SearchActionForBus extends SearchActionForCountry
{
    #=======================
    #	初動
    #=======================
    function __construct($Naigai, $p_mokuteki = NULL, $p_hatsu = NULL, $SubKyotenCode, $KyoteHatsuAry)
    {
        global $PathSharing, $GlobalSolrReqParamAry, $GlobalSetStrAry;

        /*受け取ったリクエストパラを、solrに渡す準備をします*/
        $this->MyNaigai = $Naigai;
        $this->SubKyotenCode = $SubKyotenCode;
        $this->KyoteHatsuAry = $KyoteHatsuAry;
        $this->BusValue = '813';
        $GlobalSolrReqParamAry[$Naigai]['p_mokuteki'] = NULL;

        $Request = array(
            'p_mokuteki' => $p_mokuteki
        , 'p_hatsu' => $p_hatsu
        , 'p_transport' => 1    //バスはこれデフォルト
        , 'p_bunrui' => $this->BusValue    //バスはこれもデフォルト
        );
        $this->ActRequestForSolr($Request);
        /*応答データ形式を指定*/
        $GlobalSolrReqParamAry[$this->MyNaigai]['p_data_kind'] = '1';    //ファセットのみ
        //返して欲しい項目は、内外別
        if ($this->MyNaigai == 'i') {
            $GlobalSolrReqParamAry[$this->MyNaigai]['p_rtn_data'] = 'p_hatsu_name,p_conductor,p_dest_name,p_country_name,p_city_cn';    //ファセットを返してほしい項目
        } else {
            $GlobalSolrReqParamAry[$this->MyNaigai]['p_rtn_data'] = 'p_kikan,p_carr_cn,p_dest_name,p_prefecture_name,p_region_cn,p_price_flg,p_bus_boarding_name,p_bunrui';    //こっちは国内
        }
        /*DB通信*/
        $SolrObj = new SolrAccess($this->MyNaigai);    //solrのレスポンス：ママ

        /*エラー処理*/
        $this->ActErr($SolrObj);

        /*表示用に加工します*/
        $this->MakeValues();

        /*上の部分表示*/
        if ($SubKyotenCode == 'contents') {
            include($PathSharing . 'inc/kyotentab_searchbox_busTop.php');
        } else {
            include($PathSharing . 'inc/kyotentab_searchbox_bus.php');
        }
    }

    #=======================
    #	表示用に加工します
    #=======================
    function MakeValues()
    {
        global $GlobalSolrReqParamAry;
        foreach ($GlobalSolrReqParamAry[$this->MyNaigai] as $ParamName => $ValueAry) {
            switch ($ParamName) {
                case 'p_hatsu':
                case 'p_hatsu_sub':
                case 'p_conductor':
                case 'p_mokuteki':
                case 'p_bunrui':
                case 'p_bus_boarding_code':
                case 'p_kikan_min':
                    $this->$ParamName($ParamName);
                    break;

                //その他のパラメータは無視する
                default:
                    break;
            }
        }

    }


    /*+++++++++++++++
        テーマ
    +++++++++++++++++*/
    function p_bunrui($ParamName)
    {
        //配列だったら
        $this->Values[$ParamName] = NULL;
        //ファセットを1件以上のものだけにする
        $FacetAry = $this->GetValidAryFromFacet('p_bunrui');
        if (empty($FacetAry)) {
            return;
        }
        //カスタムマスタ登場
        $MasterObj = new HierarchyCodeNaigaiView_p_bunrui;
        $MasterAry = $MasterObj->TgDataAry[$this->MyNaigai];

        /*表示はチェックボックス*/
        $Opt = NULL;
        foreach ($MasterAry as $key => $val) {
            //バスのときは隠す
            if ($key == $this->BusValue) {
                $Opt .= <<<EOD
<li class="SachInputBusSt" style="display:none;"><input type="checkbox" name="{$ParamName}[]" value="{$key}" checked="checked" />{$val}</li>

EOD;
            } //ファセット無いものは出さない
            elseif (!empty($FacetAry[$key]['facet'])) {
                $Opt .= <<<EOD
<li class="SachInputBusSt"><input type="checkbox" name="{$ParamName}[]" value="{$key}" />{$val}</li>

EOD;
            }
        }
        //表示するものが無かったらサヨナラ
        if (empty($Opt)) {
            return;
        }
        $this->Values[$ParamName] = <<<EOD
<dl class="SachBusStep6 FClear">
	<dt class="SachBusStep6Title">step6</dt>
	<dd class="SachBusStep6SubTtle">テーマ</dd>
	<dd class="SachInputBusChk">
		<ul>
			<li>
				<ul>
					$Opt
				</ul>
			</li>
		</ul>
	</dd>
</dl>

EOD;
    }

}


/*******************************************************
 * フリープラン専門店用
 *
 * 引数
 *        $Naigai                    :str    ：内外（必須：i or d）
 *        $p_mokuteki                :str    ：目的地　※定義書通り
 *        $p_hatsu                    :str    ：出発地　※定義書通り
 *        $SubKyotenCode            :str    ：サブ拠点ID（必須）
 *        $KyoteHatsuAry        :ary    ：拠点の出発地情報（必須）
 *******************************************************/
class SearchActionForFreePlan extends SearchActionForCountry
{
    #=======================
    #	初動
    #=======================
    function __construct($Naigai, $p_mokuteki = NULL, $p_hatsu = NULL, $SubKyotenCode, $KyoteHatsuAry, $BunruiCode = '030')
    {
        global $PathSharing, $GlobalSolrReqParamAry, $GlobalSetStrAry;

        /*受け取ったリクエストパラを、solrに渡す準備をします*/
        $this->MyNaigai = $Naigai;
        $this->SubKyotenCode = $SubKyotenCode;
        $this->KyoteHatsuAry = $KyoteHatsuAry;
        $GlobalSolrReqParamAry[$Naigai]['p_mokuteki'] = NULL;

        $Request = array(
            'p_mokuteki' => $p_mokuteki
        , 'p_hatsu' => $p_hatsu
        , 'p_bunrui' => $BunruiCode
        );
        $this->ActRequestForSolr($Request);
        /*応答データ形式を指定*/
        $GlobalSolrReqParamAry[$this->MyNaigai]['p_data_kind'] = '1';    //ファセットのみ
        //返して欲しい項目は、内外別
        if ($this->MyNaigai == 'i') {
            $GlobalSolrReqParamAry[$this->MyNaigai]['p_rtn_data'] = 'p_hatsu_name,p_conductor,p_dest_name,p_country_name,p_city_cn';    //ファセットを返してほしい項目
        } else {
            $GlobalSolrReqParamAry[$this->MyNaigai]['p_rtn_data'] = 'p_kikan,p_dest_name,p_prefecture_name,p_region_cn,p_transport,p_dep_airport_name';    //こっちは国内
        }
        /*DB通信*/
        $SolrObj = new SolrAccess($this->MyNaigai);    //solrのレスポンス：ママ

        /*エラー処理*/
        $this->ActErr($SolrObj);
        /*表示用に加工します*/
        $this->MakeValues();

        /*上の部分表示*/
        if ($SubKyotenCode == 'contents') {
            include($PathSharing . 'inc/kyotentab_searchbox_freeplanTop.php');
        } else {
            include($PathSharing . 'inc/kyotentab_searchbox_freeplan.php');
        }
    }


    #=======================
    #	表示用に加工します
    #=======================
    function MakeValues()
    {
        global $GlobalSolrReqParamAry;
        foreach ($GlobalSolrReqParamAry[$this->MyNaigai] as $ParamName => $ValueAry) {
            switch ($ParamName) {
                case 'p_hatsu':
                case 'p_hatsu_sub':
                case 'p_conductor':
                case 'p_mokuteki':
                case 'p_kikan_min':
                case 'p_dep_airport_code':
                    $this->$ParamName($ParamName);
                    break;

                //ちびっこマスターズ
                case 'p_transport':
                    $this->Params($ParamName);
                    break;
                //その他のパラメータは無視する
                default:
                    break;
            }
        }

    }
}

?>
