<div id="Slide" class="frame swiper-container">
    <ul class="swiper-wrapper">
        <?php echo $this->imgLi;?>
    </ul>
	<?php if($count > 1):?>
    <button class="swiper-button-next">next</button>
    <button class="swiper-button-prev">prev</button>   
    <ol class="swiper-pagination"></ol>
    <?php endif;?>
</div>
