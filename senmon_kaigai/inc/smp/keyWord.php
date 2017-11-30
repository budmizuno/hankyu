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
        
        // 表示フラグ
        $dispFlg = false;
        foreach ($keyWordCsv as $value) {
        	if($value[KEY_Q_GROUP] != $type) continue;
        	if ($value['q_keyword'] != "") {
        		// 表示フラグ
        		$dispFlg = true;
        		break;
        	}
        }
        if (!$dispFlg) {
        	return;
        }
?>
<section class="blue keyWordIWrapper">
<h2 class="main-title main-title-dot mainBgClr mb10 keyword_title">
    <span class="main-title-txt">人気のキーワードで探す</span>
</h2>
<div class="keyWord">
    <ul class="inner">
		<?php 
		foreach ($keyWordCsv as $value) { 
			// ツアーかフリープランか
			if($value[KEY_Q_GROUP] != $type) continue;
			if ($value['q_keyword'] != "") {
		?>
		<li>
			<a href="<?php echo $value[KEY_TOUR_URL] ?>"><?php echo $value['q_keyword'] ?></a>
		</li>
		<?php 
			}
		}
		?>
    </ul>
</div>
</section>

<?php 
    }
}
?>
