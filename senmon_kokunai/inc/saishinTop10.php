<?php
include_once(dirname(__FILE__) . '/../phpsc/recommend_search/RecommendCourseServiceBot.php');

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
        if(strstr($this->groupType, 'イチオシ'))
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
            <div class="tab-content-slider left">
                <div class="frame mb10 slider-sly" id="sly1">
                    <ul class="clearfix osusume">
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
                    <div class="block-banner-top">
                        <div class="block-banner-topbox"></div>
                        <video data-video-id="{$value[KEY_Q_BRIGHTCOVE_ID]}" data-account="5097191270001" data-player="default" data-embed="default" data-application-id class="video-js" controls width="380px" height="285px"></video>
                        <script src="//players.brightcove.net/5097191270001/default_default/index.min.js"></script>
                    </div>
EOD;
                }elseif(!empty($value[KEY_Q_THETA_ID]) && !empty($value[KEY_Q_THETA_URL])){ // シータ動画があるなら
                    $video_html .=<<<EOD
                    <div style="width:380px; height:285px;">
                        <div class="thum">
                            <blockquote data-mode="click2play" data-width="380px" data-height="285px" class="ricoh-theta-spherical-image" >
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
                <li>
                    {$video_html}
                    <a href="{$value[KEY_TOUR_URL]}">
                        {$img_html}
                        <div class="slider-wr-content">
                            <p class="slider-title"><span>{$value['p_hatsu_sub_name']}</span>{$courseName}</p>
                            <p class="slider-content">{$point}</p>
                            <p class="slider-price">{$value['p_price']}</p>
                        </div>
                    </a>
                </li>
EOD;
            }

            $html .=<<<EOD
                    </ul>
                </div>
                <div class="btn-group">
                    <a href="#" class="prev"><i class="sprite sprite-slider-prev"></i></a>
                    <a href="#" class="next"><i class="sprite sprite-slider-next"></i></a>
                </div>
                <ul class="pages osusume-pages"></ul>
            </div>
EOD;
        }

        if(!empty($html)){
            $this->ichioshi_flg = true;
        }

        // html出力
        echo $html;
    }

    // 担当者おすすめ
    public function makeHtmlOsusume()
    {
        global $masterCsv;
        $osusumeCsv = $this->getCsvContents($this->groupType);

        $values = array();
        foreach((array)$osusumeCsv[OSUSUME_COURSE] as $value) {
            if($value[KEY_Q_GROUP] == $this->groupType) {
                $values[] = $value;
            }
        }

        if($this->groupType == TOUR_TANTOSHA_OSUSUME){
            $text = '旅行';
        }
        else{
            $text = 'フリープラン';
        }

        $html = '';
        if (count($values) >= 1) {
            $html .=<<<EOD
            <h2 class="list-inline main-title mb20 mt20 mainBgClr tantosha_title">
                <span class="mid main-title-txt">担当者おすすめ {$masterCsv[KEY_MASTER_CSV_NAME_JA]}{$text}</span>
            </h2>
            <div class="wr-block mb20">
                <div class="frame slider-sly slider-sly-normal">
                    <ul class="clearfix tantosha">
EOD;
            $num=0;
            foreach ($values as $value)
            {
                $video_html = '';
                $img_html = '';
                if(!empty($value[KEY_Q_BRIGHTCOVE_ID])){ // ブライトコープ動画があるなら
                    $video_html .=<<<EOD
                    <div class="block-banner-top">
                        <div class="block-banner-topbox"></div>
                        <video data-video-id="{$value[KEY_Q_BRIGHTCOVE_ID]}" data-account="5097191270001" data-player="default" data-embed="default" data-application-id class="video-js" controls width="300px" height="225px"></video>
                        <script src="//players.brightcove.net/5097191270001/default_default/index.min.js"></script>
                    </div>
EOD;
                }elseif(!empty($value[KEY_Q_THETA_ID]) && !empty($value[KEY_Q_THETA_URL])){ // シータ動画があるなら
                    $video_html .=<<<EOD
                    <div style="width:300px; height:225px;">
                        <div class="thum">
                            <blockquote data-mode="click2play" data-width="300px" data-height="225px" class="ricoh-theta-spherical-image" >
                                <a href="{$value[KEY_Q_THETA_URL]}"></a>
                            </blockquote>
                            <script async src="https://bud-international.theta360.biz/widgets.js" charset="utf-8"></script>
                        </div>
                    </div>
EOD;
                }
                else{
                    $img_html .=<<<EOD
                    <img src="{$value[KEY_Q_IMG_PATH]}" alt="{$value[KEY_Q_IMG_CAPTION]}" class="img_tantoshaosusuem">
EOD;
                }



                $html .=<<<EOD
                <li>
                    {$video_html}
                    <a href="{$value[KEY_TOUR_URL]}">
                        {$img_html}
                        <p class="sly3-ct"><span>{$value['p_hatsu_sub_name']}</span>{$value[KEY_Q_COURSE_NAME]}</p>
                        <p class="sly3-price">{$value['p_price']}</p>
                    </a>
                </li>
EOD;
            }
            $html .=<<<EOD
                    </ul>
                </div>
                <div class="btn-group">
                    <a href="#" class="prev"><i class="sprite sprite-slider-prev"></i></a>
                    <a href="#" class="next"><i class="sprite sprite-slider-next"></i></a>
                </div>
                <ul class="pages tantosha-pages"></ul>
            </div>
EOD;
        }
        // html出力
        echo $html;
    }
}
