<?php
// 人気キーワードを出力する
class setKeyWord
{
    function __construct($type){

        $this->makeHtml($type);
    }

    public function makeHtml($type)
    {
        global $keyWordCsv;

        $html = '';
        $keyNum = 0;

        if(0 < ($keyWordCsv))
        {
            $html .=<<<EOD
            <h2 class="list-inline main-title mb20 mt20 mainBgClr keyword_title">
                <span class="mid main-title-txt">人気のキーワードで探す</span>
            </h2>
            <div class="link-taia mb40">
EOD;
            foreach ($keyWordCsv as $value)
            {
                // ツアーかフリープランか
                if($value[KEY_Q_GROUP] != $type) continue;
                if ($value['q_keyword'] != "")
                {
                        // フォントサイズを変える
                        $fontClass = '';
                        switch ($keyNum)
                        {
                            case 0:
                            case 3:
                                $fontClass = 'big-link';
                                break;
                            case 6:
                                $fontClass = 'bigger-link';
                                break;
                            default:
                                $fontClass = '';
                                break;
                        }

                        $html .=<<<EOD
                    <a href="{$value[KEY_TOUR_URL]}" class="{$fontClass}">{$value['q_keyword']}</a>
EOD;
                    $keyNum++;
                    if(8 < $keyNum) $keyNum = 0;
                }
            }

            $html .= '</div>';

            // html出力
            if ($keyNum > 0) {
            echo $html;
            }
        }
    }
}

?>
