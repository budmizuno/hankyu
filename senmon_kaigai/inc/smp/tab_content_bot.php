<?php
// タブの中身を出力する
class setTabContentBot
{
    function __construct($tabIndex){

        $this->makeHtml($tabIndex);
    }

    public function makeHtml($tabIndex)
    {
        global $masterCsv, $senmonNameEnLower, $kyotenId;

        $actives = array('','','');
        $actives[$tabIndex] = "active";
        $html = '';
        $html .=<<<EOD
        <li class="$actives[0]" data-link="#tab1">
            <span><span>{$masterCsv[KEY_MASTER_CSV_NAME_JA]}<br>ツアー</span></span>
        </li>
        <li class="$actives[1]" data-link="#tab2">
            <span><span>{$masterCsv[KEY_MASTER_CSV_NAME_JA]}<br>フリープラン</span></span>
        </li>
EOD;
        echo $html;
    }
}