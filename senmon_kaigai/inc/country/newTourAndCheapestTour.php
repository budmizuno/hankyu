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

        $blog = new blogNew('i',5,$type);

        if($type == TOUR_STRING){
            $text = ' ツアー';
            $class = 'new_tour';
            $cheap_class = 'cheap_tour';
        }
        else{
            $text = ' フリープラン';
            $class = 'new_freeplan_tour';
            $cheap_class = 'cheap_freeplan_tour';
        }

        if($blog->num) {

            echo '<li class="list-item  top '.$class.'">';
            echo '<p class="list-inline find-from-hotel mainBgClr"> <i class="mid icon icon-new-swiss"></i> <span class="mid txt-bold">新着 '.$masterCsv[KEY_MASTER_CSV_NAME_JA].$text.'</span></p>';
            echo '<ul class="find-tour-free">';

            echo $blog->html;

            echo '</ul>';
        }else{
            //0件だった場合は枠ごと非表示
            $html  = '<script>'."\n";
            $html .= '<!--'."\n";
            $html .= '$(function(){'."\n";
            $html .= 'if ($(".'.$cheap_class.'").length > 0) {$(".'.$cheap_class.'").parent().hide();}'."\n";
            $html .= '});'."\n";
            $html .= '-->'."\n";
            $html .= '</script>'."\n";
            echo $html;
        }

    }

}


class setCheapestTour{

    function __construct($type){

        $this->makeHtml($type);
    }

    public function makeHtml($type)
    {
        global $p_hatsu,$mokuteki,$masterCsv;

        // 取得する月数
        $count = 5;

        $html = '';

        $_REQUEST = null;
        $_REQUEST['p_hatsu'] = $p_hatsu;
        $_REQUEST['p_mokuteki'] = $mokuteki;
        $_REQUEST['p_rtn_data'] = 'p_conductor';
        $_REQUEST['p_data_kind'] = '2';
        $_REQUEST['p_rtn_count'] = '1';
        $_REQUEST['p_start_line'] = '1';
        // フリープランーなら
        if($type == FREE_PLAN_STRING)
        {
            $_REQUEST['p_bunrui'] = '030';
            $class = 'new_freeplan_tour';
            $cheap_class = 'cheap_freeplan_tour';
        }
        else{
            $class = 'new_tour';
            $cheap_class = 'cheap_tour';
        }

        $lowObj = new GetLowPrice;

        // オブジェクトを配列に変換
        $SFA_Obj = json_decode(json_encode($lowObj), true);

        $add_count = 0;
        $html .=<<<EOD
        <li class="list-item last top {$cheap_class}">
            <p class="list-inline find-from-hotel mainBgClr"> <i class="mid icon-main icon-price bg-85dec0"></i><span class="mid txt-bold">{$masterCsv[KEY_MASTER_CSV_NAME_JA]}{$type}最安値</span> </p>
            <ul class="find-tour-free find-tour-free-swiss list">
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
                       <span class="font-12 block-block center month_str">{$mm}</span>
                       <a href="{$herf}" class="font-14 mt5 link-day">{$p_country_name} {$p_kikan}日間</a>
                       <p class="font-15 color-red txt-bold">{$price}</p>
                   </li>
EOD;
            $add_count++;
        }

        $html .='</ul></li>';

        if ($add_count <= 0) {
            //0件だった場合は、新着を非表示
            $html  = '<script>'."\n";
            $html .= '<!--'."\n";
            $html .= '$(function(){'."\n";
            $html .= 'if ($(".'.$class.'").length > 0) {$(".'.$class.'").parent().hide();}'."\n";
            $html .= '});'."\n";
            $html .= '-->'."\n";
            $html .= '</script>'."\n";

        }
        echo $html;
    }

}
