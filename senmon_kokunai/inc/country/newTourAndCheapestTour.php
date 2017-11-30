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

        $blog = new blogNew('d',5,$type);

        if($type == TOUR_STRING){
            $text = ' ツアー';
        }
        else{
            $text = ' フリープラン';
        }

        if($blog->num) {

            echo '<li class="list-item  top">';
            echo '<p class="list-inline find-from-hotel mainBgClr"> <i class="mid icon icon-new-swiss"></i> <span class="mid txt-bold">新着 '.$masterCsv[KEY_MASTER_CSV_NAME_JA].$text.'</span></p>';
            echo '<ul class="find-tour-free">';

            echo $blog->html;

            echo '</ul>';
        }

    }

}
