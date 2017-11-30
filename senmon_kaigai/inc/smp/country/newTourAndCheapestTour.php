<?php

// 新着ツアー
include_once($PathSenmonCommon . 'phpsc/country/view.php');
// 最安値ツアー
include_once($PathSenmonCommon . 'phpsc/country/search.php');
include_once($SharingPSPath . 'func.php');

class setNewTour{

    function __construct($type){

        $this->makeHtml($type);

    }

    public function makeHtml($type)
    {
        global $masterCsv;

        // 国ページ以外では表示しない
        if ($masterCsv['right_box_type'] !== 'country') {
            return;
        }

        if($type == TOUR_STRING){
            $text = ' ツアー';
        }
        else{
            $text = ' フリープラン';
        }

        $blog = new blogNew('i',5,$type);

        $html = '';
        if($blog->num) {

            $html .= '<section class="js_moreFour mb20">';

            $html .=   '<h2 class="main-title mainBgClr mb10 new_tour_title"><span class="main-title-txt">新着 '.$masterCsv[KEY_MASTER_CSV_NAME_JA].$text.'</span> </h2>';
            $html .=   '<ul class="find-tour-free clearfix">';
            $html .=       $blog->html;
            $html .=   '</ul>';
            $html .=   '<p class="moreNewTourPls"><span>もっと見る</span></p>';
            $html .=   '<p class="moreNewTourMns" style="display: none;"><span>閉じる</span></p>';
            $html .= '</section>';

        }

        echo $html;

    }
}


class setCheapestTour{

    function __construct($type){

        $this->makeHtml($type);
    }

    public function makeHtml($type)
    {
        global $p_hatsu,$mokuteki,$masterCsv;

        // 国ページ以外では表示しない
        if ($masterCsv['right_box_type'] !== 'country') {
            return;
        }

        // 取得する月数
        $count = 5;

        $html = '';

        // フリープランーなら
        if($type == FREE_PLAN_STRING)
        {
            $_REQUEST['p_bunrui'] = '030';
        }

        $_REQUEST['p_hatsu'] = $p_hatsu;
        $_REQUEST['p_mokuteki'] = $mokuteki;
        $_REQUEST['p_rtn_data'] = 'p_conductor';
        $_REQUEST['p_data_kind'] = '2';
        $_REQUEST['p_rtn_count'] = '1';
        $_REQUEST['p_start_line'] = '1';
//      $_REQUEST['p_dep_date'] = $r_year.$r_month;//7月指定
//      $_REQUEST['p_dep_date'] = $yyyymm;
        //$SFA_Obj = new GetLowPrice;

        $lowObj = new GetLowPrice;
        // オブジェクトを配列に変換
        $SFA_Obj = json_decode(json_encode($lowObj), true);

        $add_count = 0;
        $html .=<<<EOD
<section>
    <ul class="find-tour"><li>
            <h2 class="main-title mainBgClr mb10 low_price_title"><span class="main-title-txt">{$masterCsv[KEY_MASTER_CSV_NAME_JA]} {$type}最安値</span></h2>
            <ul class="find-tour-free-swiss clearfix">
EOD;

        for($i=0;$i<$count;$i++)
        {

            if(1 < $add_count) break; // 2つまで

            $timestamp = strtotime(date('Y-m-01').'+'.($i+1).' month');
            $mm = date('Y年n月', $timestamp);

            if(empty($SFA_Obj['returnObj'][$i]['docs'][0])) continue;

            $herf = '/tour/detail_i.php?p_course_id=' . $SFA_Obj['returnObj'][$i]['docs'][0]['p_course_id'] . '&p_hei=' . $SFA_Obj['returnObj'][$i]['docs'][0]['p_hei'];
            $courseName = $SFA_Obj['returnObj'][$i]['docs'][0]['p_course_name'];
            $p_stay_city = $SFA_Obj['returnObj'][$i]['docs'][0]['p_stay_city'];
            $array = $SFA_Obj['returnObj'][$i]['docs'][0]['p_country_name'];
            $p_country_code = '';
            $p_country_name = '';
            if (is_array($array)) {
                foreach($array as $p_country_name_data) {
                    $_p_country_name = explode(",", $p_country_name_data);
                    $_p_country_code = $_p_country_name[0] . '-' . $_p_country_name[1];
                    if (strpos($p_country_code, $_p_country_code) !== false || $_p_country_code == 'AAS-JP') {
                        continue;
                    }
                    if ($p_country_name != '') {
                        $p_country_name .= ',';
                        $p_country_code .= ',';
                    }
                    $p_country_name .= $_p_country_name[2];
                    $p_country_code .= $_p_country_code;
                }
            }
            $p_kikan = $SFA_Obj['returnObj'][$i]['docs'][0]['p_kikan'];
            $price = YoriMade($SFA_Obj['returnObj'][$i]['docs'][0]['p_price_min'],$SFA_Obj['returnObj'][$i]['docs'][0]['p_price_max'],'円');

            $html .=<<<EOD
                   <li>
                       <span class="tour-date">{$mm}</span>
                       <p>
                           <a href="{$herf}" class="tour-link-date">{$p_country_name} {$p_kikan}日間</a>
                            <span class="tour-price">{$price}</span>
                       </p>
                   </li>
EOD;
            $add_count++;
        }

        $html .=<<<EOD
            </ul>
    </li></ul>
</section>
EOD;
        if ($add_count > 0) {
            echo $html;
        }
    }
}

/*

*/
