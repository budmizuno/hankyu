<?php
/*
#################################################################
	ホテル検索と新着ツアー
#################################################################
*/


// ホテル検索
class setHotelSearch{

    function __construct(){

        $this->makeHtml();

    }

    public function makeHtml()
    {
        global $masterCsv;

        $html = "";

        $href = 'http://www.hankyu-travel.com/freeplan-d/facility/' .$masterCsv[KEY_MASTER_CSV_COUNTRY_LARGE] . '/';

        $html .=<<<EOD
        <li class="list-item top">
            <p class="bg-yellow btn-heritage btn-find-tour">
                <a href="{$href}" class="btn-heritage-link  btn-find-tour-link list-inline"> <span class="txt-bold mid">{$masterCsv[KEY_MASTER_CSV_NAME_JA]}のホテル旅館から探す </span> <i class="icon icon-arr-red mid"></i> </a>
            </p>
            <p class="list-inline find-from-hotel center mt20 mainBgClr"> <i class="mid icon sprite-search"></i> <span class="mid txt-bold">ホテル・旅館名から探す</span> </p>
            <form action="http://www.hankyu-travel.com/freeplan-d/facility/name/" method="GET" class="search clear search-tour mt10">
                <input id="search-tour" name="p_accommodation_name_free" type="text" placeholder="例）アパホテル" class="search-input left search-tour-input">
                <input id="p_ins_prefecture_code" name="p_ins_prefecture_code" type="hidden" value="{$masterCsv[KEY_MASTER_CSV_COUNTRY_LARGE]}" >
                <input id="submit" type="submit" value="" class="search-btn icon icon-btn-search left search-tour-btn">
            </form>
        </li>
EOD;

        echo $html;

    }

}


// 新着ツアー
class setNewTour{

    function __construct($type=''){

        $this->makeHtml($type);

    }

    public function makeHtml($type)
    {
        global $masterCsv;

        $html = "";
        $blog = new blogNew('d',5,$type);

        if($blog->num) {

            $html .=<<<EOD
            <li class="list-item last top">
                <p class="list-inline find-from-hotel mainBgClr"> <i class="mid icon-main icon-new-hokkaido"></i> <span class="mid txt-bold">新着 {$masterCsv[KEY_MASTER_CSV_NAME_JA]} {$type}</span> </p>
                <ul class="find-tour-free">
                    {$blog->html}
                </ul>
            </li>
EOD;
        }

        echo $html;

    }

}
