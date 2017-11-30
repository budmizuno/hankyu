<?php
/*
#################################################################
	ホテル検索と新着ツアー
#################################################################
*/


// ホテル検索
class setHotelSearchFreeplan{

    function __construct(){

        $this->makeHtml();

    }

    public function makeHtml()
    {
        global $masterCsv;

        $html = "";

        $href = 'http://www.hankyu-travel.com/freeplan-d/facility/'. $masterCsv[KEY_MASTER_CSV_COUNTRY_LARGE] .'/';

        $html .=<<<EOD



<p class="hotel-search mb20">
	<a href="{$href}">{$masterCsv[KEY_MASTER_CSV_NAME_JA]}のホテル旅館から探す</a>
</p>

<section>
	<h2 class="main-title mainBgClr mb10 search_title">
		<span class="main-title-txt">ホテル・旅館名から探す</span>
	</h2>
	<div class="modalSrchBlk">

		<form action="http://www.hankyu-travel.com/freeplan-d/facility/name/" method="GET" class="search clear search-tour mt10">
			<input id="search-tours" name="p_accommodation_name_free" type="text" placeholder="例）アパホテル" class="search-input left search-tour-input">
            <input id="p_ins_prefecture_code" name="p_ins_prefecture_code" type="hidden" value="{$masterCsv[KEY_MASTER_CSV_COUNTRY_LARGE]}" >
    		<div id="" class="search-btn_freeword" style="">
    			<div><input id="submit" type="submit" value="検索" class=""></div>
    		</div>
		</form>
	</div>
</section>


EOD;

        echo $html;

    }

}


// 新着ツアー
class setNewTourFreeplan{

    function __construct(){

        $this->makeHtml();

    }

    public function makeHtml()
    {
        global $masterCsv;

        $html = "";
        $blog = new blogNew('d',5,FREE_PLAN_STRING);

        if($blog->num) {
?>

<section class="js_moreFour mb20">
	<ul class="find-tour">
		<li>
			<h2 class="main-title mainBgClr mb10 new_tour_title">
				<span class="main-title-txt">新着 <?php echo $masterCsv[KEY_MASTER_CSV_NAME_JA] ?> フリープラン</span>
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
