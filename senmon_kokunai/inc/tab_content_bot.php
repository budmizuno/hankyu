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

        $genchiHtml = '';
        if(isGenchiHacchakuShow()){
            $genchiHtml = '<li class="'.$actives[2].'"><span>'.$masterCsv[KEY_MASTER_CSV_NAME_JA].'発着ツアー</span></li>';
        }

        $html = '';
        $html .=<<<EOD
        <li class="$actives[0]">
            <span>{$masterCsv[KEY_MASTER_CSV_NAME_JA]} ツアー</span>
        </li>
        <li class="$actives[1]">
            <span>{$masterCsv[KEY_MASTER_CSV_NAME_JA]} フリープラン</span>
        </li>
        {$genchiHtml}
EOD;
        echo $html;
    }
}
