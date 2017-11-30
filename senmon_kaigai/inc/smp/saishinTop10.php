<?php
include_once($PathSenmonCommon.'phpsc/recommend_search/RecommendCourseServiceBot.php');

// 担当者おすすめを出力する
class setSaishinTop10
{
    private $getCsvCourseBot;
    private $groupType;

    public $ichioshi_flg = false;

    function __construct($type)
    {
        $this->groupType = $type;
        $this->getCsvCourseBot = new RecommendCourseServiceBot();

        // イチオシ枠なら
        if(strstr($this->groupType, TOUR_ICHIOSHI_TOUR))
        {
            $this->makeHtmlIchioshi();
        }
        // 担当者おすすめ
        else
        {
            $this->makeHtmlOsusume();
        }
    }

    public function getCsvContents()
    {
        global $p_hatsuAry, $masterCsv, $naigai;
        // 既存の26拠点+BOT用(index)の配列
        $newKyotenArray = array();
        $newKyotenArray = $p_hatsuAry->TgDataAry[$naigai];
        $newKyotenArray['index'] = null;    // valueは使用しないのでnullでOK

        $filePaths = array();
        $updatedDates = array();
        foreach($newKyotenArray as $kyotenId => $kyotenValue) {
            $filePath = $this->getCsvCourseBot->getCsvPath($masterCsv[KEY_MASTER_CSV_TOUR], $kyotenId);
            if (!file_exists($filePath)) continue;
            // CSVファイルの更新時刻を取得
            $updatedDates[] = filemtime($filePath);
            // ソート
            rsort($updatedDates);
            // 更新の順位を検索
            $sortIndex = array_search(filemtime($filePath), $updatedDates);
            // 配列の更新順位の位置に挿入する
            array_splice($filePaths, $sortIndex, 0, $filePath);
        }

        // 表示項目が出るまでループ
        for ($i=0; $i < 3; $i++) {
            $_filePaths = array();
            // 更新日上位10のファイルパスを取得
            $_filePaths = array_slice($filePaths, $i * 10, 10);

            // 取得した最新10本が全部受付終了の可能性があるので、表示可能が出るまでループ
            $return_list = array();
            for ($j=0; $j < 10; $j++) {
                // 最新CSV10本の中から、それぞれの最新を1本づつ取得して、配列にする
                $list = $this->getCsvCourseBot->createRequestList($_filePaths,$this->groupType,$masterCsv[KEY_MASTER_CSV_NAME_JA],$j);
                $return_list = $this->getCsvCourseBot->ConvertCSVtoJsonBot($list, $masterCsv[KEY_MASTER_CSV_NAME_JA]);
                // 1本以上あれば2重ループを抜ける
                if(isset($return_list['recommend_course']) && 0 < count($return_list['recommend_course'])) break 2;
            }
        }

        return $return_list;
    }

    // イチオシツアー
    public function makeHtmlIchioshi()
    {
        global $masterCsv,$senmon_func;
        $csvData = $this->getCsvContents($this->groupType);

        $values = array();
        foreach((array)$csvData[OSUSUME_COURSE] as $value) {
            if($value[KEY_Q_GROUP] == $this->groupType) {
                $values[] = $value;
            }
        }

        $html = '';
        if (count($values) >= 1) {
            $html .=<<<EOD
            <ul class="clearfix swiper-wrapper">
EOD;

            foreach ($values as $value)
            {
                $imgPath = $senmon_func->imagePathConvert(IMG_TYPE_ICHIOSHI, $value[KEY_Q_IMG_PATH], false);
                $courseName = stringControl($value['p_course_name'],STRING_LIMIT_ICHIOSHI_COURSE_NAME);
                $point = stringControl($value['p_point1'],STRING_LIMIT_ICHIOSHI_CAPTION);

                $video_html = '';
                $img_html = '';
                if(!empty($value[KEY_Q_BRIGHTCOVE_ID])){ // ブライトコープ動画があるなら
                    $video_html .=<<<EOD
                    <div class="block-banner-top" style="height:50vw;">
                        <div class="block-banner-topbox"></div>
                        <video data-video-id="{$value[KEY_Q_BRIGHTCOVE_ID]}" data-account="5097191270001" data-player="default" data-embed="default" data-application-id class="video-js" controls width="100%" height="100%"></video>
                        <script src="//players.brightcove.net/5097191270001/default_default/index.min.js"></script>
                    </div>
EOD;
                }elseif(!empty($value[KEY_Q_THETA_ID]) && !empty($value[KEY_Q_THETA_URL])){ // シータ動画があるなら
                    $video_html .=<<<EOD
                    <div style="width:auto; height:50vw;">
                        <div class="thum" style="height:50vw;">
                            <blockquote data-mode="click2play" data-width="auto" data-height="100%" class="ricoh-theta-spherical-image" >
                                <a href="{$value[KEY_Q_THETA_URL]}"></a>
                            </blockquote>
                            <script async src="https://bud-international.theta360.biz/widgets.js" charset="utf-8"></script>
                        </div>
                    </div>
EOD;
                }
                else{
                    $img_html .=<<<EOD
                    <img src="{$imgPath}" alt="{$value[KEY_Q_IMG_CAPTION]}" class="img_ichioshi">
EOD;
                }


                $html .=<<<EOD
                <li class="swiper-slide">
                    {$video_html}
                    <a href="{$value[KEY_TOUR_URL]}">
                        {$img_html}
                        <div class="slider-wr-content">
                            <p class="sly3-ct"><span>{$value['p_hatsu_name']}</span>{$courseName}</p>
                            <p class="sly3-price">{$value['p_price']}</p>
                        </div>
                    </a>
                </li>
EOD;
            }

            $html .=<<<EOD
            </ul>
            <div class="swiper-scrollbar"></div>
EOD;
        }

        if(!empty($html)){
            $this->ichioshi_flg = true;
        }

        // html出力
        echo $html;
    }

    public function makeHtmlOsusume()
    {
        global $masterCsv,$senmon_func;
        $csvData = $this->getCsvContents();

        $values = array();
        foreach((array)$csvData[OSUSUME_COURSE] as $value) {
            if($value[KEY_Q_GROUP] == $this->groupType) {
                $values[] = $value;
            }
        }

        $title = '';
        $list_id = 'tantosha_list_';
        if ($this->groupType == TOUR_TANTOSHA_OSUSUME) {
            $list_id .= 'tour';
            $title = '担当者おすすめ ' . $masterCsv[KEY_MASTER_CSV_NAME_JA] . '旅行';
        } else {
            $list_id .= 'freeplan';
            $title = '担当者おすすめ ' . $masterCsv[KEY_MASTER_CSV_NAME_JA] . 'フリープラン';
        }

        $html = '';
        if (count($values) >= 1) {
            $html .=<<<EOD
    <h2 class="main-title like-main-title mainBgClr mb10 tantosha_title">
        <span class="main-title-txt">{$title}</span>
    </h2>
    <div class="wr-block">
        <div class="frame swiper-container" id="{$list_id}">
            <ul class="clearfix tantoshaOsusume swiper-wrapper">

EOD;
            foreach ($values as $value)
            {

                $video_html = '';
                $img_html = '';
                if(!empty($value[KEY_Q_BRIGHTCOVE_ID])){ // ブライトコープ動画があるなら
                    $video_html .=<<<EOD
                    <div class="block-banner-top" style="height:50vw;">
                        <div class="block-banner-topbox"></div>
                        <video data-video-id="{$value[KEY_Q_BRIGHTCOVE_ID]}" data-account="5097191270001" data-player="default" data-embed="default" data-application-id class="video-js" controls width="100%" height="100%"></video>
                        <script src="//players.brightcove.net/5097191270001/default_default/index.min.js"></script>
                    </div>
EOD;
                }elseif(!empty($value[KEY_Q_THETA_ID]) && !empty($value[KEY_Q_THETA_URL])){ // シータ動画があるなら
                    $video_html .=<<<EOD
                    <div style="width:auto; height:50vw;">
                        <div class="thum" style="height:50vw;">
                            <blockquote data-mode="click2play" data-width="auto" data-height="100%" class="ricoh-theta-spherical-image" >
                                <a href="{$value[KEY_Q_THETA_URL]}"></a>
                            </blockquote>
                            <script async src="https://bud-international.theta360.biz/widgets.js" charset="utf-8"></script>
                        </div>
                    </div>
EOD;
                }
                else{
                    $img_html .=<<<EOD
                    <img src="{$value[KEY_Q_IMG_PATH]}" alt="{$value[KEY_Q_IMG_CAPTION]}" class="">
EOD;
                }

                $html .=<<<EOD
                <li class="swiper-slide">
                    {$video_html}
                    <a href="{$value[KEY_TOUR_URL]}">
                        {$img_html}
                        <p class="sly3-ct"><span>{$value['p_hatsu_name']}</span>{$value[KEY_Q_COURSE_NAME]}</p>
                        <p class="sly3-price">{$value['p_price']}</p>
                    </a>
                </li>
EOD;
            }
            $html .=<<<EOD
            </ul>
            <div class="swiper-scrollbar"></div>
        </div>
    </div>
EOD;
        }
        // html出力
        echo $html;
    }
}
