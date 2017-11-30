<?php
/*
* 共通で使用する拠点特集
*
*/

class setKyotenTokusyu
{
    function __construct($type){

        // 拠点特集を出力する
        $this->makeHtml($type);
    }

    public function makeHtml($type)
    {
        global $kyotenTokusyuCsv, $senmon_func;

        $html = '';

        if(!empty($kyotenTokusyuCsv))
        {
            foreach ($kyotenTokusyuCsv as $item_type => $value)
            {
                // ツアーかフリープランか判断
                if($item_type != $type) continue;

                foreach ($value as $key2 => $value2) {

                    $html .= '<section class="js_Onemore wrapper-base-feature">';


                    // 見出しがあるなら
                    if(!empty($value2[KEY_Q_THEME]))
                    {
                        $html .=<<<EOD
                        <h2 class="main-title mainBgClr">
                            <span class="main-title-txt">{$value2[KEY_Q_THEME]}</span>
                        </h2>
EOD;
                    }

                    $yomimono_list = array();
                    $shohin_list = array();


                    // 読み物の一覧を取得
                    if (isset($value2[YOMIMONO_STRING])) {
                    	$yomimono_list = $value2[YOMIMONO_STRING];
                    }

                    // 商品の一覧を取得
                    if (isset($value2[SYOHIN_STRING])) {
                    	$shohin_list = $value2[SYOHIN_STRING];
                    }

                    if (count($yomimono_list) > 0)
                    {
                        $last_add_display_type = '';
                        $loop_count = 0;


                        $html .= '<ul class="base-feature">';

                        foreach ($yomimono_list as $data)
                        {

                            // ツアー読み物 写真付き1列 の場合
                            if ($data['display_type'] === KYOTEN_TOKUSYU_DISPLAY_TYPE_YOMIMONO_1)
                            {
                                $video_html = '';
                                $img_html = '';
                                if(!empty($data[KEY_Q_BRIGHTCOVE_ID])){ // ブライトコープ動画があるなら
                                    $video_html .=<<<EOD
                                    <div class="block-banner-top" style="width:64%;height:50vw;float:left;padding-right:10px;">
                                        <div class="block-banner-topbox"></div>
                                        <video data-video-id="{$data[KEY_Q_BRIGHTCOVE_ID]}" data-account="5097191270001" data-player="default" data-embed="default" data-application-id class="video-js" controls width="100%" height="100%"></video>
                                        <script src="//players.brightcove.net/5097191270001/default_default/index.min.js"></script>
                                    </div>
EOD;
                                }elseif(!empty($data[KEY_Q_THETA_ID]) && !empty($data[KEY_Q_THETA_URL])){ // シータ動画があるなら
                                    $video_html .=<<<EOD
                                    <div style="width:64%; height:50vw;float:left;padding-right:10px;">
                                        <div class="thum" style="height:50vw;">
                                            <blockquote data-mode="click2play" data-width="auto" data-height="100%" class="ricoh-theta-spherical-image" >
                                                <a href="{$data[KEY_Q_THETA_URL]}"></a>
                                            </blockquote>
                                            <script async src="https://bud-international.theta360.biz/widgets.js" charset="utf-8"></script>
                                        </div>
                                    </div>
EOD;
                                }
                                else{
                                    $img_html .=<<<EOD
                                    <p class="base-feature-left base-feature-img-first" style="float: left;">
                                    <img src="{$senmon_func->imagePathConvert(IMG_TYPE_KYOTEN_SP_L, $data[KEY_Q_IMG_PATH], false)}" alt="{$data[KEY_Q_IMG_CAPTION]}">
                                    </p>
EOD;
                                }


                                $html .=<<<EOD
                                    <li style="display:block;">
                                        {$video_html}
                                        {$img_html}
                                        <p style="padding-bottom: 10px;">
                                            <span class="txt-point">POINT</span><br>
                                            <span class="base-feature-text-first">{$data[KEY_Q_COURSE_NAME]}</span>
                                        </p>
                                        <span class="base-feature-content">{$data[KEY_Q_POINT]}</span><br>
                                    </li>
EOD;
                            }

                            // ツアー読み物 写真付き3列 の場合
                            elseif ($data['display_type'] === KYOTEN_TOKUSYU_DISPLAY_TYPE_YOMIMONO_2)
                            {

                                $video_html = '';
                                $img_html = '';
                                if(!empty($data[KEY_Q_BRIGHTCOVE_ID])){ // ブライトコープ動画があるなら
                                    $video_html .=<<<EOD
                                    <div class="block-banner-top" style="width:32%;height:10vw;display: table-cell;">
                                        <div class="block-banner-topbox"></div>
                                        <video data-video-id="{$data[KEY_Q_BRIGHTCOVE_ID]}" data-account="5097191270001" data-player="default" data-embed="default" data-application-id class="video-js" controls width="100%" height="100%"></video>
                                        <script src="//players.brightcove.net/5097191270001/default_default/index.min.js"></script>
                                    </div>
EOD;
                                }elseif(!empty($data[KEY_Q_THETA_ID]) && !empty($data[KEY_Q_THETA_URL])){ // シータ動画があるなら
                                    $video_html .=<<<EOD
                                    <div style="width:32%; height:10vw;display: table-cell;">
                                        <div class="thum" style="100%">
                                            <blockquote data-mode="click2play" data-width="100%" data-height="100%" class="ricoh-theta-spherical-image" >
                                                <a href="{$data[KEY_Q_THETA_URL]}"></a>
                                            </blockquote>
                                            <script async src="https://bud-international.theta360.biz/widgets.js" charset="utf-8"></script>
                                        </div>
                                    </div>
EOD;
                                }
                                else{
                                    $img_html .=<<<EOD
                                    <p class="base-feature-left base-feature-img">
                                    <img src="{$senmon_func->imagePathConvert(IMG_TYPE_KYOTEN_SP_M, $data[KEY_Q_IMG_PATH], false)}" alt="{$data[KEY_Q_IMG_CAPTION]}">
                                    </p>
EOD;
                                }


                                // 偶数番目
                                if (($loop_count%2) == 0) {
                                    $html .=<<<EOD
                                        <li>
                                            {$video_html}{$img_html}
                                            <p>
                                                <span class="base-feature-title">
                                                    {$data[KEY_Q_COURSE_NAME]}
                                                </span><br>
                                                <span class="base-feature-content">{$data[KEY_Q_POINT]}</span><br>
                                            </p>
                                        </li>
EOD;
                                } else {
                                    $html .=<<<EOD
                                        <li>
                                            <p>
                                                <span class="base-feature-title">
                                                    {$data[KEY_Q_COURSE_NAME]}
                                                </span><br>
                                                <span class="base-feature-content">{$data[KEY_Q_POINT]}</span><br>
                                            </p>
                                            {$video_html}{$img_html}
                                        </li>
EOD;
                                }

                            }
                            $last_add_display_type = $data['display_type'];
                            $loop_count++;
                        } // endforeach
                        $html .= '</ul>';
                        if (count($yomimono_list) > 0) {
                            $html .= <<<EOD
                            <p class="base-feature-btn base-feature-moreNewTourPls" style="display: table;">
                                <span>続きを読む</span>
                            </p>
                            <p class="base-feature-btn base-feature-moreNewTourMns" style="display: none;"><span>閉じる</span></p>
EOD;
                        }
                    }
                    $html .= '</section>';

                    if (count($shohin_list) > 0)
                    {
                        $last_add_display_type = '';
                        $loop_count = 0;

                        $html .= '<section class="blue photoSearchWrapper js_moreFour_video">';
                        $html .= '<ul class="clearfix" id="photoList">';

                        foreach ($shohin_list as $data)
                        {
                            $video_html = '';
                            $img_html = '';
                            if(!empty($data[KEY_Q_BRIGHTCOVE_ID])){ // ブライトコープ動画があるなら
                                $video_html .=<<<EOD
                                <div class="block-banner-top" style="width:100%;height:35vw;">
                                    <div class="block-banner-topbox"></div>
                                    <video data-video-id="{$data[KEY_Q_BRIGHTCOVE_ID]}" data-account="5097191270001" data-player="default" data-embed="default" data-application-id class="video-js" controls width="100%" height="100%"></video>
                                    <script src="//players.brightcove.net/5097191270001/default_default/index.min.js"></script>
                                </div>
EOD;
                            }elseif(!empty($data[KEY_Q_THETA_ID]) && !empty($data[KEY_Q_THETA_URL])){ // シータ動画があるなら
                                $video_html .=<<<EOD
                                <div style="width:100%; height:35vw;">
                                    <div class="thum" style="height:35vw;">
                                        <blockquote data-mode="click2play" data-width="auto" data-height="100%" class="ricoh-theta-spherical-image" >
                                            <a href="{$data[KEY_Q_THETA_URL]}"></a>
                                        </blockquote>
                                        <script async src="https://bud-international.theta360.biz/widgets.js" charset="utf-8"></script>
                                    </div>
                                </div>
EOD;
                            }
                            else{
                                $img_html .=<<<EOD
                                <dt>
                                <img src="{$senmon_func->imagePathConvert(IMG_TYPE_KYOTEN_SP_M, $data[KEY_Q_IMG_PATH], false)}" alt="{$data[KEY_Q_IMG_CAPTION]}">
                                </dt>
EOD;
                            }


                            // ツアー商品枠 写真付き3列 の場合
                            if ($data['display_type'] === KYOTEN_TOKUSYU_DISPLAY_TYPE_SHOHIN_1)
                            {

                                $html .=<<<EOD
                                    <li class="video">
                                        {$video_html}
                                        <a href="{$data[KEY_TOUR_URL]}">
                                            <dl>
                                                {$img_html}
                                                <dd>{$data[KEY_Q_COURSE_NAME]}</dd>
                                                <dd class="price">{$data[KEY_Q_PRICE]}</dd>
                                            </dl>
                                        </a>
                                    </li>
EOD;
                            }

                            // ツアー商品枠 写真付き4列 の場合
                            elseif ($data['display_type'] === KYOTEN_TOKUSYU_DISPLAY_TYPE_SHOHIN_2)
                            {

                                $html .=<<<EOD
                                    <li class="video">
                                        {$video_html}
                                        <a href="{$data[KEY_TOUR_URL]}">
                                            <dl>
                                                {$img_html}
                                                <dd>{$data[KEY_Q_COURSE_NAME]}</dd>
                                                <dd class="price">{$data[KEY_Q_PRICE]}</dd>
                                            </dl>
                                        </a>
                                    </li>
EOD;
                            }

                            $last_add_display_type = $data['display_type'];
                            $loop_count++;
                        } // endforeach
                        $html .= '</ul>';
                        if (count($shohin_list) > 0) {
                            $html .= <<<EOD
                                <p class="moreNewTourPls">
                                    <span>もっと見る</span>
                                </p>
                                <p class="moreNewTourMns" style="display: none;"><span>閉じる</span></p>
EOD;
                        }
                        $html .= '</section>';
                    }

                }

            }

            // html出力
            echo $html;
        }
    }
}

?>
