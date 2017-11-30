<?php
// 担当者おすすめを出力する
class setTantoshaOsusume
{
    public $doRender = false;
    function __construct($type){

        $this->makeHtml($type);
    }

    public function makeHtml($type)
    {
        global $osusumeCsv, $masterCsv, $senmon_func, $kyotenId;

        $html = '';

        if($type == TOUR_TANTOSHA_OSUSUME){
            $text = '旅行';
        }
        else{
            $text = 'フリープラン';
        }

        if(isset($osusumeCsv[OSUSUME_CATEGORY_NUM][$type]) && 0 < $osusumeCsv[OSUSUME_CATEGORY_NUM][$type])
        {
            $html .=<<<EOD
            <h2 class="list-inline main-title mb20 mt20 mainBgClr tantosha_title">
                <span class="mid main-title-txt">担当者おすすめ {$masterCsv[KEY_MASTER_CSV_NAME_JA]}{$text}</span>
            </h2>
            <div class="wr-block mb40">
                <div class="frame slider-sly slider-sly-normal">
                    <ul class="clearfix tantosha">
EOD;

            $num=0;
            foreach ($osusumeCsv[OSUSUME_COURSE] as $value)
            {
                if($value[KEY_Q_GROUP] == $type)
                {
                    if(10 <= $num) break; // 10記事まで

                    $p_hatsu_name = '';
                    if($kyotenId == 'index'){
                        $p_hatsu_name = '<span>'.$value['p_hatsu_sub_name'].'</span>';
                    }

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
                        <img src="{$senmon_func->imagePathConvert(IMG_TYPE_TANTOSHA_OSUSUME, $value[KEY_Q_IMG_PATH], false)}" alt="{$value[KEY_Q_IMG_CAPTION]}" class="img_tantoshaosusuem">
EOD;
                    }

                    $html .=<<<EOD
                    <li>
                        {$video_html}
                        <a href="{$value[KEY_TOUR_URL]}">
                            {$img_html}
                            <p class="sly3-ct">{$p_hatsu_name}{$value[KEY_Q_COURSE_NAME]}</p><p class="sly3-cpt">{$value[KEY_Q_POINT]}</p>
                            <p class="sly3-price">{$value[KEY_Q_PRICE]}</p>
                        </a>
                    </li>
EOD;
                    $num++;
                }
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

            if ($html !== '') {
                $this->doRender = true;
            }

            // html出力
            echo $html;
        }
    }
}
