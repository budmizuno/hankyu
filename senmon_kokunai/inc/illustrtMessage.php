<?php // イラストメッセージ枠 ?>
<?php
$_display_message = array();

if (count($illustMessageCsv) > 0)
{
    foreach ($illustMessageCsv as $item)
    {
        if ($item['q_category'] == $masterCsv[KEY_MASTER_CSV_NAME_JA] && $item['q_point'] != "")
        {
            $_display_message[] = $item['q_point'];
        }
    }
}
?>
<?php  if (count($_display_message) > 0) :?>
<div class="ct-comment mb20 FClear">
    <i class="sprite sprite-comment"></i>
    <p>
        <?php
            foreach ($_display_message as $_message)
            {
                echo $_message;
                }
        ?>
    </p>
</div>
<?php endif;?>
