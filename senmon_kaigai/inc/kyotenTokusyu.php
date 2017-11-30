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

                    $html .= '<div>';
                    // 見出しがあるなら
                    if(!empty($value2[KEY_Q_THEME]))
                    {
                        $html .=<<<EOD
                        <h2 class="list-inline main-title mb20 mt40 mainBgClr">
                            <span class="mid main-title-txt">{$value2[KEY_Q_THEME]}</span>
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
                        $YOMIMONO_2_loop_count = 0;

                        $html .= '<div class="mb20 base-group">';
                        foreach ($yomimono_list as $data)
                        {
                            // 前回追加したデータが「読み物 写真付き3列」かつ今回のデータが「読み物 写真付き3列」でない場合、ulの閉じタグを設定
                            if ($last_add_display_type === KYOTEN_TOKUSYU_DISPLAY_TYPE_YOMIMONO_2
                                    && $data['display_type'] !== KYOTEN_TOKUSYU_DISPLAY_TYPE_YOMIMONO_2) {
                                $html .= '</ul>';
                            }

                            // ツアー読み物 写真付き1列 の場合
                            if ($data['display_type'] === KYOTEN_TOKUSYU_DISPLAY_TYPE_YOMIMONO_1)
                            {
                                $YOMIMONO_2_loop_count = 0;

                                $video_html = '';
                                $img_html = '';
                                if(!empty($data[KEY_Q_BRIGHTCOVE_ID])){ // ブライトコープ動画があるなら
                                    $video_html .=<<<EOD
                                    <div class="block-banner-top">
                                        <div class="block-banner-topbox"></div>
                                        <video data-video-id="{$data[KEY_Q_BRIGHTCOVE_ID]}" data-account="5097191270001" data-player="default" data-embed="default" data-application-id class="video-js" controls width="400px" height="300px"></video>
                                        <script src="//players.brightcove.net/5097191270001/default_default/index.min.js"></script>
                                    </div>
EOD;
                                }elseif(!empty($data[KEY_Q_THETA_ID]) && !empty($data[KEY_Q_THETA_URL])){ // シータ動画があるなら
                                    $video_html .=<<<EOD
                                    <div style="width:400px; height:300px;">
                                        <div class="thum">
                                            <blockquote data-mode="click2play" data-width="400px" data-height="300px" class="ricoh-theta-spherical-image" >
                                                <a href="{$data[KEY_Q_THETA_URL]}"></a>
                                            </blockquote>
                                            <script async src="https://bud-international.theta360.biz/widgets.js" charset="utf-8"></script>
                                        </div>
                                    </div>
EOD;
                                }
                                else{
                                    $img_html .=<<<EOD
                                    <img src="{$senmon_func->imagePathConvert(IMG_TYPE_KYOTEN_SP_L, $data[KEY_Q_IMG_PATH], false)}" alt="{$data[KEY_Q_IMG_CAPTION]}">
EOD;
                                }


                                $html .=<<<EOD
                                    <ul class="list mb20 base-list">
                                       <li class="list-item top">
                                           <p class="mb20">
                                               <span class="font-14 center txt-point">Point</span>
                                               <span class="txt-bold txt-point-title">{$data[KEY_Q_COURSE_NAME]}</span>
                                           </p>
                                           <p class="font-14">{$data[KEY_Q_POINT]}</p>
                                       </li>
                                       <li class="list-item last top">
                                            {$video_html}{$img_html}
                                        </li>
                                    </ul>
EOD;
                            }

                            // ツアー読み物 写真付き3列 の場合
                            elseif ($data['display_type'] === KYOTEN_TOKUSYU_DISPLAY_TYPE_YOMIMONO_2)
                            {
                                // 初回の場合はulの開始タグ
                                if (($YOMIMONO_2_loop_count % 3) == 0)
                                {
                                    $html .= '<ul class="list base-feature">';
                                }

                                $video_html = '';
                                $img_html = '';
                                if(!empty($data[KEY_Q_BRIGHTCOVE_ID])){ // ブライトコープ動画があるなら
                                    $video_html .=<<<EOD
                                    <div class="block-banner-top">
                                        <div class="block-banner-topbox"></div>
                                        <video data-video-id="{$data[KEY_Q_BRIGHTCOVE_ID]}" data-account="5097191270001" data-player="default" data-embed="default" data-application-id class="video-js" controls width="300px" height="225px"></video>
                                        <script src="//players.brightcove.net/5097191270001/default_default/index.min.js"></script>
                                    </div>
EOD;
                                }elseif(!empty($data[KEY_Q_THETA_ID]) && !empty($data[KEY_Q_THETA_URL])){ // シータ動画があるなら
                                    $video_html .=<<<EOD
                                    <div style="width:300px; height:225px;">
                                        <div class="thum">
                                            <blockquote data-mode="click2play" data-width="300px" data-height="225px" class="ricoh-theta-spherical-image" >
                                                <a href="{$data[KEY_Q_THETA_URL]}"></a>
                                            </blockquote>
                                            <script async src="https://bud-international.theta360.biz/widgets.js" charset="utf-8"></script>
                                        </div>
                                    </div>
EOD;
                                }
                                else{
                                    $img_html .=<<<EOD
                                    <img src="{$senmon_func->imagePathConvert(IMG_TYPE_KYOTEN_SP_M, $data[KEY_Q_IMG_PATH], false)}" alt="{$data[KEY_Q_IMG_CAPTION]}">
EOD;
                                }

                                $html .=<<<EOD
                                    <li class="list-item">
                                        {$video_html}{$img_html}
                                        <p class="font-14 mt10 mb5 txt-bold">{$data[KEY_Q_COURSE_NAME]}</p>
                                        <p class="">{$data[KEY_Q_POINT]}</p>
                                    </li>
EOD;
                                // 一番最後のループの場合はulの閉じタグを設定
                                if (count($yomimono_list) == ($loop_count-1) || ($YOMIMONO_2_loop_count % 3) == 2)
                                {
                                    $html .= '</ul>';
                                }
                                $YOMIMONO_2_loop_count++;
                            }
                            $last_add_display_type = $data['display_type'];
                            $loop_count++;
                        } // endforeach
                        $html .= '</div>';
                    }

                    if (count($shohin_list) > 0)
                    {
                        $last_add_display_type = '';
                        $loop_count = 0;
                        $SHOHIN_1_loop_count = 0;
                        $SHOHIN_2_loop_count = 0;

                        foreach ($shohin_list as $data)
                        {
                            // 初回のループじゃないかつ前回追加したデータが異なる場合、ulの閉じタグを設定
                            if ($last_add_display_type !== '' && $last_add_display_type !==  $data['display_type']) {
                                $html .= '</ul>';
                            }

                            $disabled = "";
                            if(empty($data[KEY_TOUR_URL])){
                                $disabled = 'disabled';
                            }

                            // ツアー商品枠 写真付き3列 の場合
                            if ($data['display_type'] === KYOTEN_TOKUSYU_DISPLAY_TYPE_SHOHIN_1)
                            {
                                $SHOHIN_2_loop_count = 0;
                                // 前回追加したデータが異なる場合、ulの開始タグを設定
                                if (($SHOHIN_1_loop_count % 3) == 0) {
                                    $html .= '<ul class="base-special mb40">';
                                }

                                $video_html = '';
                                $img_html = '';
                                if(!empty($data[KEY_Q_BRIGHTCOVE_ID])){ // ブライトコープ動画があるなら
                                    $video_html .=<<<EOD
                                    <div class="block-banner-top">
                                        <div class="block-banner-topbox"></div>
                                        <video data-video-id="{$data[KEY_Q_BRIGHTCOVE_ID]}" data-account="5097191270001" data-player="default" data-embed="default" data-application-id class="video-js" controls width="300px" height="225px"></video>
                                        <script src="//players.brightcove.net/5097191270001/default_default/index.min.js"></script>
                                    </div>
EOD;
                                }elseif(!empty($data[KEY_Q_THETA_ID]) && !empty($data[KEY_Q_THETA_URL])){ // シータ動画があるなら
                                    $video_html .=<<<EOD
                                    <div style="width:300px; height:225px;">
                                        <div class="thum">
                                            <blockquote data-mode="click2play" data-width="300px" data-height="225px" class="ricoh-theta-spherical-image" >
                                                <a href="{$data[KEY_Q_THETA_URL]}"></a>
                                            </blockquote>
                                            <script async src="https://bud-international.theta360.biz/widgets.js" charset="utf-8"></script>
                                        </div>
                                    </div>
EOD;
                                }
                                else{
                                    $img_html .=<<<EOD
                                    <img src="{$senmon_func->imagePathConvert(IMG_TYPE_KYOTEN_SP_M, $data[KEY_Q_IMG_PATH], false)}" alt="{$data[KEY_Q_IMG_CAPTION]}">
EOD;
                                }

                                $html .=<<<EOD
                                    <li class="list-item top">
                                        {$video_html}
                                        <a href="{$data[KEY_TOUR_URL]}" class="{$disabled}">
                                            {$img_html}
                                            <p class="font-14 mt10 mb5">{$data[KEY_Q_COURSE_NAME]}</p>
                                            <p class="feature-list-content">{$data[KEY_Q_POINT]}</p>
                                            <p class="sly3-price">{$data[KEY_Q_PRICE]}</p>
                                        </a>
                                    </li>
EOD;
                                // 一番最後のループの場合はulの閉じタグを設定
                                if (count($shohin_list) == ($loop_count-1) || ($SHOHIN_1_loop_count % 3) == 2)
                                {
                                    $html .= '</ul>';
                                }
                                $SHOHIN_1_loop_count++;
                            }

                            // ツアー商品枠 写真付き4列 の場合
                            elseif ($data['display_type'] === KYOTEN_TOKUSYU_DISPLAY_TYPE_SHOHIN_2)
                            {
                                $SHOHIN_1_loop_count = 0;
                                // 前回追加したデータが異なる場合、ulの開始タグを設定
                                if (($SHOHIN_2_loop_count % 4) == 0) {
                                    $html .= '<ul class="base-place mb40">';
                                }

                                $video_html = '';
                                $img_html = '';
                                if(!empty($data[KEY_Q_BRIGHTCOVE_ID])){ // ブライトコープ動画があるなら
                                    $video_html .=<<<EOD
                                    <div class="block-banner-top">
                                        <div class="block-banner-topbox"></div>
                                        <video data-video-id="{$data[KEY_Q_BRIGHTCOVE_ID]}" data-account="5097191270001" data-player="default" data-embed="default" data-application-id class="video-js" controls width="220px" height="165px"></video>
                                        <script src="//players.brightcove.net/5097191270001/default_default/index.min.js"></script>
                                    </div>
EOD;
                                }elseif(!empty($data[KEY_Q_THETA_ID]) && !empty($data[KEY_Q_THETA_URL])){ // シータ動画があるなら
                                    $video_html .=<<<EOD
                                    <div style="width:220px; height:165px;">
                                        <div class="thum">
                                            <blockquote data-mode="click2play" data-width="220px" data-height="165px" class="ricoh-theta-spherical-image" >
                                                <a href="{$data[KEY_Q_THETA_URL]}"></a>
                                            </blockquote>
                                            <script async src="https://bud-international.theta360.biz/widgets.js" charset="utf-8"></script>
                                        </div>
                                    </div>
EOD;
                                }
                                else{
                                    $img_html .=<<<EOD
                                    <img src="{$senmon_func->imagePathConvert(IMG_TYPE_KYOTEN_SP_M, $data[KEY_Q_IMG_PATH], false)}" alt="{$data[KEY_Q_IMG_CAPTION]}">
EOD;
                                }

                                $html .=<<<EOD
                                    <li class="list-item top">
                                        {$video_html}
                                        <a href="{$data[KEY_TOUR_URL]}" class="{$disabled}">
                                            {$img_html}
                                            <p class="font-14 mt10 mb5">{$data[KEY_Q_COURSE_NAME]}</p>
                                            <p class="feature-list-content">{$data[KEY_Q_POINT]}</p>
                                            <p class="sly3-price">{$data[KEY_Q_PRICE]}</p>
                                        </a>
                                    </li>
EOD;
                                // 一番最後のループの場合はulの閉じタグを設定
                                if (count($shohin_list) == ($loop_count-1) || ($SHOHIN_2_loop_count % 4) == 3)
                                {
                                    $html .= '</ul>';
                                }
                                $SHOHIN_2_loop_count++;
                            }

                            $last_add_display_type = $data['display_type'];
                            $loop_count++;
                        } // endforeach
                    }

                    // それぞれの特集を閉じるtag
                    $html .= '</div>';
                }
            }

            // html出力
            echo $html;
        }
    }
}

?>
