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

        $href = 'http://www.hankyu-travel.com/freeplan-d/facility/'. $masterCsv[KEY_MASTER_CSV_COUNTRY_LARGE] .'/';

        $html .=<<<EOD
        <li class="list-item top">
            <p class="bg-yellow btn-heritage btn-find-tour">
                <a href="{$href}" class="btn-heritage-link  btn-find-tour-link list-inline"> <span class="txt-bold mid">{$masterCsv[KEY_MASTER_CSV_NAME_JA]}のホテル旅館から探す </span> <i class="icon icon-arr-red mid"></i> </a>
            </p>
            <p class="list-inline find-from-hotel center mt20 mainBgClr"> <i class="mid icon sprite-search"></i> <span class="mid txt-bold">ホテル・旅館から探す</span> </p>
            <form action="http://www.hankyu-travel.com/freeplan-d/facility/name/" method="GET" class="search clear search-tour mt10">
    			<input id="search-tours" name="p_accommodation_name_free" type="text" placeholder="例）アパホテル" class="search-input left search-tour-input">
                <input id="p_ins_prefecture_code" name="p_ins_prefecture_code" type="hidden" value="{$masterCsv[KEY_MASTER_CSV_COUNTRY_LARGE]}" >
        		<div id="" class="search-btn_freeword" style="">
        			<div><input id="submit" type="submit" value="検索" class=""></div>
        		</div>
    		</form>
        </li>
EOD;

        echo $html;

    }

}


// 新着ツアー
class setNewTour{

    function __construct(){

        $this->makeHtml();

    }

    public function makeHtml()
    {
        global $masterCsv;

        $html = "";
        $blog = new blogNew('d',5,TOUR_STRING);

        if($blog->num) {

?>

<section class="js_moreFour mb20">
	<ul class="find-tour">
		<li>
			<h2 class="main-title mainBgClr mb10 new_tour_title">
				<span class="main-title-txt">新着 <?php echo $masterCsv[KEY_MASTER_CSV_NAME_JA] ?> ツアー</span>
			</h2>
			<ul class="find-tour-free clearfix">
				<?php echo $blog->html ?>
			</ul>
		</li>
	</ul>
	<p class="moreNewTourPls">
	<span>もっと見る</span>
	</p>
	<p class="moreNewTourMns" style="display: none;"><span>閉じる</span></p>
</section>

<?php
        }

//        echo $html;

    }

}
?>
