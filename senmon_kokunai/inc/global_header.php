<?php // 専門店の共通CSS ?>
<link type="text/css" rel="stylesheet" href="/sharing/common16/css/freeplan.css"/>
<link type="text/css" rel="stylesheet" href="<?=$PathSenmonLink;?>css/common.css">
<link type="text/css" rel="stylesheet" href="<?=$PathSenmonLink;?>css/jquery.bxslider.css">
<link type="text/css" rel="stylesheet" href="<?=$PathSenmonLink;?>css/magnific-popup.css">
<link type="text/css" rel="stylesheet" href="<?=$PathSenmonLink;?>css/jquery-ui.min.css">
<link type="text/css" rel="stylesheet" href="<?=$PathSenmonLink;?>css/map.css">
<link type="text/css" rel="stylesheet" href="<?php e($Sharing16);?>css/index16.css" />
<link type="text/css" rel="stylesheet" href="<?php e($Sharing16);?>css/naigai.css" />
<?php // 方面ページだけの読み込み ?>
<?php if($categoryType == CATEGORY_TYPE_DEST):?>
    <link rel="stylesheet" href="/attending/guide/sharing/css/base.css" type="text/css" media="all" />
    <link type="text/css" rel="stylesheet" href="/attending/guide/sharing/css/index.css" />
   
    <?php
    $guide_key = str_replace("/guide/", "", $masterCsv[KEY_MASTER_CSV_GUIDE_PATH]);
    $guide_key = str_replace("/", "", $guide_key);
    $csv_guide_renewal_csv_path = '/attending'.$masterCsv[KEY_MASTER_CSV_GUIDE_PATH].'css/renewal_'.$guide_key.'.css';
    if (file_exists($_SERVER['DOCUMENT_ROOT'] . $csv_guide_renewal_csv_path)) {
        //echo '<link rel="stylesheet" href="'.$csv_guide_renewal_csv_path.'" type="text/css" />';
				echo ' <link type="text/css" rel="stylesheet" href="/attending/guide/css/2017/index.css">';
    }

    $csv_guide_index_csv_path = '/attending'.$masterCsv[KEY_MASTER_CSV_GUIDE_PATH].'css/index.css';
    if (file_exists($_SERVER['DOCUMENT_ROOT'] . $csv_guide_index_csv_path)) {
        echo '<link rel="stylesheet" href="'.$csv_guide_index_csv_path.'" type="text/css" />';
    }
    ?>
<?php else:?>
    <link type="text/css" rel="stylesheet" href="<?=$PathSenmonLink;?>css/senmonTop.css" />
<?php endif;?>
<?php // 専門店の共通JS ?>
<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script type="text/javascript" src="//code.jquery.com/ui/1.10.3/jquery-ui.min.js"></script>

<link type="text/css" rel="stylesheet" href="<?=$PathSenmonLink;?>css/common_add1.css">
<link type="text/css" rel="stylesheet" href="<?=$PathSenmonLink;?>css/common_add2.css">
