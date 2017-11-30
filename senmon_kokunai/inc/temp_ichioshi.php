<!-- イチオシ -->
<?php if(!empty($html)):?>
	<?php if($kyotenId != 'index'): //拠点用 ?>
		<div class="idx_box02 recommended mb30 OnFR">
			<h2 class="idx_icn08 idx_bar01">担当者イチオシ国内旅行</h2>
			<div class="idx_box02 recommended_d">
			<?php echo $html; ?>
			</div>
		</div>
	<?php else: //bot用　?>
		<div class="idx_box03 recommended mb30 FClear">
			<h2 class="idx_icn08 idx_bar01">担当者イチオシ国内旅行<span>旬の旅行をご紹介♪</span></h2>
			<div class="idx_box03 recommended_i recommended_i_bot">
			<?php echo $html; ?>
			</div>
		</div>
	<?php endif;?>
<?php endif;?>
<!-- イチオシ -->
