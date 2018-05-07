<?php
$temp_ebook = '/attending/kokunai/inc/temp_ebook_'.$kyotenId.'.php';
if ($kyotenId == 'toy') {
    $temp_ebook = '/attending/kokunai/inc/temp_ebook_hkr.php';
}
?>
<?php if ($kyotenId != 'kmi' && $kyotenId != 'koj' && file_exists($_SERVER['DOCUMENT_ROOT'] . $temp_ebook)) :?>
    <div class="list-inline journey mb20 journey-taipei bdColor">
        <i class="icon-main icon-tel-main mid mainBgClr"></i>
        <span class="mid font-25 journey-info">デジタルカタログ</span>
        <span class="mid font-14">国内旅行の情報誌をウェブ上でご覧いただけます</span>
    </div>
    <?php include_once($_SERVER['DOCUMENT_ROOT'] . $temp_ebook); ?>
<?php endif;?>
