<?php
// ホテルランキングを出力する
class setHotelRanking
{
    function __construct($type){

        $this->makeHtml($type);
    }

    public function makeHtml($type)
    {
        global $osusumeCsv, $masterCsv, $senmon_func, $kyotenId;

        $html = '';

        if(isset($osusumeCsv[OSUSUME_CATEGORY_NUM][$type]) && 0 < $osusumeCsv[OSUSUME_CATEGORY_NUM][$type])
        {
            $html .=<<<EOD
            <h2 class="list-inline main-title mb20 mt20 mainBgClr">
                <span class="mid main-title-txt">{$masterCsv[KEY_MASTER_CSV_NAME_JA]}おすすめホテルランキング</span>
            </h2>
            <ul class="list-inline list-hotel">
EOD;

            $num=1;
            foreach ($osusumeCsv[OSUSUME_COURSE] as $value)
            {
                if($value[KEY_Q_GROUP] == $type)
                {
                    if(10 < $num) break; // 10記事まで

                    $p_hatsu_name = '';
                    if($kyotenId == 'index'){
                        $p_hatsu_name = '<span>'.$value['p_hatsu_name'].'</span>';
                    }

                    // 全部並べてソースが汚いが、レイアウト差があるためこのように書いた
                    switch ($num) {
                        case 1:

                            $video_html = '';
                            $img_html = '';
                            if(!empty($value[KEY_Q_BRIGHTCOVE_ID])){ // ブライトコープ動画があるなら
                                $video_html .=<<<EOD
                                <div class="block-banner-top">
                                    <div class="block-banner-topbox"></div>
                                    <video data-video-id="{$value[KEY_Q_BRIGHTCOVE_ID]}" data-account="5097191270001" data-player="default" data-embed="default" data-application-id class="video-js" controls width="360px" height="270px"></video>
                                    <script src="//players.brightcove.net/5097191270001/default_default/index.min.js"></script>
                                </div>
EOD;
                            }elseif(!empty($value[KEY_Q_THETA_ID]) && !empty($value[KEY_Q_THETA_URL])){ // シータ動画があるなら
                                $video_html .=<<<EOD
                                <div style="width:360px; height:270px;">
                                    <div class="thum">
                                        <blockquote data-mode="click2play" data-width="360px" data-height="270px" class="ricoh-theta-spherical-image" >
                                            <a href="{$value[KEY_Q_THETA_URL]}"></a>
                                        </blockquote>
                                        <script async src="https://bud-international.theta360.biz/widgets.js" charset="utf-8"></script>
                                    </div>
                                </div>
EOD;
                            }
                            else{
                                $img_html .=<<<EOD
                                <img src="{$senmon_func->imagePathConvert(IMG_TYPE_KYOTEN_SP_M, $value[KEY_Q_IMG_PATH], false)}" alt="{$value[KEY_Q_IMG_CAPTION]}">
EOD;
                            }

                            $_mr0 = '';
                            $html .=<<<EOD
                            <li class="top list-hotel-{$num}{$_mr0}">
                                {$video_html}
                                <a href="{$value[KEY_TOUR_URL]}">
                                    {$img_html}
                                    <i class="icon icon-num icon-num{$num}"></i>
                                    <p class="font-14 txt-bold mt10 mb10">{$p_hatsu_name}{$value[KEY_Q_COURSE_NAME]}</p>
                                    <p class="mb10 feature-list-content">{$value[KEY_Q_POINT]}</p>
                                    <p class="font-16 color-red txt-bold">{$value['p_price']}</p>
                                </a>
                            </li>
EOD;
                            break;

                        case 2:
                        case 3:

                            $video_html = '';
                            $img_html = '';
                            if(!empty($value[KEY_Q_BRIGHTCOVE_ID])){ // ブライトコープ動画があるなら
                                $video_html .=<<<EOD
                                <div class="block-banner-top">
                                    <div class="block-banner-topbox"></div>
                                    <video data-video-id="{$value[KEY_Q_BRIGHTCOVE_ID]}" data-account="5097191270001" data-player="default" data-embed="default" data-application-id class="video-js" controls width="280px" height="210px"></video>
                                    <script src="//players.brightcove.net/5097191270001/default_default/index.min.js"></script>
                                </div>
EOD;
                            }elseif(!empty($value[KEY_Q_THETA_ID]) && !empty($value[KEY_Q_THETA_URL])){ // シータ動画があるなら
                                $video_html .=<<<EOD
                                <div style="width:280px; height:210px;">
                                    <div class="thum">
                                        <blockquote data-mode="click2play" data-width="280px" data-height="210px" class="ricoh-theta-spherical-image" >
                                            <a href="{$value[KEY_Q_THETA_URL]}"></a>
                                        </blockquote>
                                        <script async src="https://bud-international.theta360.biz/widgets.js" charset="utf-8"></script>
                                    </div>
                                </div>
EOD;
                            }
                            else{
                                $img_html .=<<<EOD
                                <img src="{$senmon_func->imagePathConvert(IMG_TYPE_KYOTEN_SP_M, $value[KEY_Q_IMG_PATH], false)}" alt="{$value[KEY_Q_IMG_CAPTION]}">
EOD;
                            }

                            $_mr0 = '';
                            if ($num == 3) $_mr0 = ' mr0';
                            $html .=<<<EOD
                            <li class="top list-hotel-2{$_mr0}">
                                {$video_html}
                                <a href="{$value[KEY_TOUR_URL]}">
                                    {$img_html}
                                    <i class="icon icon-num icon-num{$num}"></i>
                                    <p class="font-14 txt-bold mt10 mb10">{$p_hatsu_name}{$value[KEY_Q_COURSE_NAME]}</p>
                                    <p class="mb10 feature-list-content">{$value[KEY_Q_POINT]}</p>
                                    <p class="font-16 color-red txt-bold">{$value['p_price']}</p>
                                </a>
                            </li>
EOD;
                            break;

                        case 4:
                        case 5:
                        case 6:
                        case 7:

                            $video_html = '';
                            $img_html = '';
                            $a_style = '';
                            if(!empty($value[KEY_Q_BRIGHTCOVE_ID])){ // ブライトコープ動画があるなら
                                $a_style = "display: table-cell; vertical-align: top;";
                                $video_html .=<<<EOD
                                <div class="block-banner-top list-item">
                                    <div class="block-banner-topbox"></div>
                                    <video data-video-id="{$value[KEY_Q_BRIGHTCOVE_ID]}" data-account="5097191270001" data-player="default" data-embed="default" data-application-id class="video-js" controls width="240px" height="180px"></video>
                                    <script src="//players.brightcove.net/5097191270001/default_default/index.min.js"></script>
                                </div>
EOD;
                            }elseif(!empty($value[KEY_Q_THETA_ID]) && !empty($value[KEY_Q_THETA_URL])){ // シータ動画があるなら
                                $a_style = "display: table-cell; vertical-align: top;";
                                $video_html .=<<<EOD
                                <div class="list-item" style="width:240px; height:180px;">
                                    <div class="thum">
                                        <blockquote data-mode="click2play" data-width="240px" data-height="180px" class="ricoh-theta-spherical-image" >
                                            <a href="{$value[KEY_Q_THETA_URL]}"></a>
                                        </blockquote>
                                        <script async src="https://bud-international.theta360.biz/widgets.js" charset="utf-8"></script>
                                    </div>
                                </div>
EOD;
                            }
                            else{
                                $img_html .=<<<EOD
                                <p class="list-item top img"><img src="{$senmon_func->imagePathConvert(IMG_TYPE_KYOTEN_SP_M, $value[KEY_Q_IMG_PATH], false)}" alt="{$value[KEY_Q_IMG_CAPTION]}"></p>
EOD;
                            }

                            $_mr0 = '';
                            if ($num % 2 == 1) $_mr0 = ' mr0';
                            $html .=<<<EOD
                            <li class="top list-hotel-4 {$_mr0}">
                                {$video_html}
                                <a href="{$value[KEY_TOUR_URL]}" style="{$a_style}">
                                    <div class="list">
                                        {$img_html}
                                        <div class="list-item top txt">
                                            <p class="font-14 txt-bold mb8">{$p_hatsu_name}{$value[KEY_Q_COURSE_NAME]}</p>
                                            <p class="feature-list-content">{$value[KEY_Q_POINT]}</p>
                                            <p class="font-16 color-red txt-bold">{$value['p_price']}</p>
                                        </div>
                                    </div>
                                    <i class="icon icon-num icon-num{$num}"></i>
                                </a>
                            </li>
EOD;
                            break;

                        case 8:
                        case 9:
                        case 10:

                            $video_html = '';
                            $img_html = '';
                            if(!empty($value[KEY_Q_BRIGHTCOVE_ID])){ // ブライトコープ動画があるなら
                                $video_html .=<<<EOD
                                <div class="block-banner-top">
                                    <div class="block-banner-topbox"></div>
                                    <video data-video-id="{$value[KEY_Q_BRIGHTCOVE_ID]}" data-account="5097191270001" data-player="default" data-embed="default" data-application-id class="video-js" controls width="160px" height="120px"></video>
                                    <script src="//players.brightcove.net/5097191270001/default_default/index.min.js"></script>
                                </div>
EOD;
                            }elseif(!empty($value[KEY_Q_THETA_ID]) && !empty($value[KEY_Q_THETA_URL])){ // シータ動画があるなら
                                $video_html .=<<<EOD
                                <div style="width:160px; height:120px;">
                                    <div class="thum">
                                        <blockquote data-mode="click2play" data-width="160px" data-height="120px" class="ricoh-theta-spherical-image" >
                                            <a href="{$value[KEY_Q_THETA_URL]}"></a>
                                        </blockquote>
                                        <script async src="https://bud-international.theta360.biz/widgets.js" charset="utf-8"></script>
                                    </div>
                                </div>
EOD;
                            }
                            else{
                                $img_html .=<<<EOD
                                <p class="list-item top img"><img src="{$senmon_func->imagePathConvert(IMG_TYPE_KYOTEN_SP_M, $value[KEY_Q_IMG_PATH], false)}" alt="{$value[KEY_Q_IMG_CAPTION]}"></p>
EOD;
                            }

                            $_mr0 = '';
                            if ($num == 10) $_mr0 = ' mr0';
                            $html .=<<<EOD
                            <li class="top list-hotel-5 {$_mr0}">
                                <a href="{$value[KEY_TOUR_URL]}">
                                    <p class="font-14 txt-bold mb8">{$value[KEY_Q_COURSE_NAME]}</p>
                                    <div class="list pos-rel">
                                        {$video_html}{$img_html}
                                        <div class="list-item top txt">
                                            <p class="feature-list-content">{$p_hatsu_name}{$value[KEY_Q_POINT]}</p>
                                            <p class="font-16 color-red txt-bold">{$value['p_price']}</p>
                                        </div>
                                        <i class="icon icon-num icon-num{$num}"></i>
                                    </div>
                                </a>
                            </li>
EOD;
                            break;

                        default:
                            break;
                    }

                    $num++;
                }
            }

            $html .= '</ul>';

            // html出力
            echo $html;
        }
    }
}
