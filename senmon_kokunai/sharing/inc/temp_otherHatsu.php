<?php if ($kyotenId != '' && $kyotenId != 'index') :?>
<?php $navi = new otherKyotenNavi($SetData->dispKyotenId); //sharing/phpsc/setDispKyoten.php ?>
<div class="idx_box03 departure mb30 clearfix">
    <h3 class="idx_icn20"><?php e($navi->KyotenName);?>出発地の情報を見る</h3>
    <div class="otDeptSbBox">
        <ul id="Js_kyoten_menu" class="kyoten_menu">
            <?php echo $navi->otherKyotenNaviHtml;?>
        </ul>
    </div>
</div>
<?php endif;?>