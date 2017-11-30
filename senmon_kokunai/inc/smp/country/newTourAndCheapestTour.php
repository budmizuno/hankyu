<?php

// 新着ツアー
include_once($PathSenmonCommon . 'phpsc/country/view.php');
include_once($SharingPSPath . 'func.php');

class setNewTour{

    function __construct($type){

        $this->makeHtml($type);

    }

    public function makeHtml($type)
    {
        global $masterCsv;

        if($type == TOUR_STRING){
            $text = ' ツアー';
        }
        else{
            $text = ' フリープラン';
        }

        $blog = new blogNew('d',5,$type);

        $html = '';
        if($blog->num) {

            $html .= '<section class="js_moreFour mb20">';

            $html .=     '<h2 class="main-title mainBgClr mb10 new_tour_title"><span class="main-title-txt">新着 '.$masterCsv[KEY_MASTER_CSV_NAME_JA].$text.'</span> </h2>';
            $html .=     '<ul class="find-tour-free clearfix">';
            $html .=       $blog->html;
            $html .=     '</ul>';
            $html .=   '<p class="moreNewTourPls"><span>もっと見る</span></p>';
            $html .=   '<p class="moreNewTourMns" style="display: none;"><span>閉じる</span></p>';
            $html .= '</section>';

        }

        echo $html;

    }
}
