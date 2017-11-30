<?php
// 拠点自由枠を出力する
class setKyotenFree
{
    function __construct($type){

        $this->makeHtml($type);
    }

    public function makeHtml($type)
    {
        global $kyotenFreeCsv, $senmon_func;

        $html = '';

        if(!empty($kyotenFreeCsv))
        {
            foreach ($kyotenFreeCsv as $key => $largeValue)
            {
                // ツアーかフリープランか
                if($key != $type) continue;

                foreach ($largeValue as $key2 => $value) {

                    $html .= '<div>';
                    // 見出しがあるなら
                    if(!empty($value[KEY_Q_THEME]))
                    {
                        $html .=<<<EOD
                        <h2 class="list-inline main-title mb20 mt20 mainBgClr">
                            <span class="mid main-title-txt">{$value[KEY_Q_THEME]}</span>
                        </h2>
EOD;
                    }


                    $html .= '<ul class="mb20 box_kyoten_free">';
                    for($i=0;$i<count($value[KEY_TOUR_URL]);$i++)
                    {

                        $video_html = '';
                        $img_html = '';
                        if(!empty($value[KEY_Q_BRIGHTCOVE_ID][$i])){ // ブライトコープ動画があるなら
                            $video_html .=<<<EOD
                            <div class="block-banner-top">
                                <div class="block-banner-topbox"></div>
                                <video data-video-id="{$value[KEY_Q_BRIGHTCOVE_ID][$i]}" data-account="5097191270001" data-player="default" data-embed="default" data-application-id class="video-js" controls width="960px" height="200px"></video>
                                <script src="//players.brightcove.net/5097191270001/default_default/index.min.js"></script>
                            </div>
EOD;
                        }elseif(!empty($value[KEY_Q_THETA_ID][$i]) && !empty($value[KEY_Q_THETA_URL][$i])){ // シータ動画があるなら
                            $video_html .=<<<EOD
                            <div style="width:960px; height:200px;">
                                <div class="thum">
                                    <blockquote data-mode="click2play" data-width="960px" data-height="200px" class="ricoh-theta-spherical-image" >
                                        <a href="{$value[KEY_Q_THETA_URL][$i]}"></a>
                                    </blockquote>
                                    <script async src="https://bud-international.theta360.biz/widgets.js" charset="utf-8"></script>
                                </div>
                            </div>
EOD;
                        }
                        else{
                            $img_html .=<<<EOD
                            <img src="{$senmon_func->imagePathConvert(IMG_TYPE_KYOTEN_FREE, $value[KEY_Q_IMG_PATH][$i], false)}" alt="{$value[KEY_Q_IMG_CAPTION][$i]}">
EOD;
                        }


                        if(empty($value[KEY_TOUR_URL][$i])){
                            $html .=<<<EOD
                            <li class="mb20">{$video_html}{$img_html}</li>
EOD;
                        } else {
                            $html .=<<<EOD
                            <li class="mb20">{$video_html}<a href="{$value[KEY_TOUR_URL][$i]}">{$img_html}</a></li>
EOD;
                        }
                    }

                    $html .= '</ul></div>';
                }
            }

            // html出力
            echo $html;
        }
    }


}

?>
