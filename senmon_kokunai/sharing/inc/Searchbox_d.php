<form class="JS_SearchBox" id="dSearchBoxTour" name="dSearchBoxTour" action="http://www.hankyu-travel.com/search/d.php"
      method="post">
    <input type="hidden" id="MyNaigai" name="MyNaigai" value="d">
    <input type="hidden" id="MyType" name="MyType" value="senmon_kokunai">
    <table>
        <tr>
            <th><span class="IconBg">出発地</span></th>
            <td>
                <?= $this->Values['p_hatsu_sub'] ?>
            </td>
        </tr>
        <tr>
            <th><span class="IconBg">目的エリア</span></th>
            <td>
                <?php echo $this->Values['Dest']; ?>
            </td>
        </tr>
        <tr>
            <th><span class="th-arrow IconFont">都道府県</span></th>
            <td>
                <?= $this->Values['Country'] ?>
            </td>
        </tr>
        <tr>
            <th><span class="th-arrow IconFont">観光地</span></th>
            <td>
                <?= $this->Values['City'] ?>
            </td>
        </tr>
        <tr>
            <th><span class="IconBg">出発日</span></th>
            <td>
                <input type="text" id="p_dep_date" name="p_dep_date" placeholder="" value=""/><img src="/sharing/common16/images/searchCal.png" class="js_dep_date_cal">
            </td>
        </tr>
        <tr>
            <th><span class="IconBg">添乗員</span></th>
            <td>
                <select id="p_conductor" name="p_conductor">
                    <option value="">選択してください</option>
                    <?= $this->Values['p_conductor'] ?>
                </select>
            </td>
        </tr>

        <tr>
            <th><span class="IconBg">旅行日数</span></th>
            <td>
                <select name="p_kikan_min" id="p_kikan_min" class="p_min_day w55">
                    <?=$this->Values['p_kikan_min']?>
                </select>
                〜
                <select name="p_kikan_max" id="p_kikan_max" class="p_max_day w55">
                    <?=$this->Values['p_kikan_min']?>
                </select>
                日
            </td>
        </tr>

        </tr>
        <tr>
            <td colspan="2" class="txt-center pb0">
                <p class="pt5 font-14">現在の該当件数<span class="txt-red" id="dp_hit_num"><?php echo (is_numeric($this->ResObj->p_hit_num) ? number_format($this->ResObj->p_hit_num) : $this->ResObj->p_hit_num);?></span>件です
                </p>
                <button type="button" class="bt-search btn_simpleSrch"><i
                        class="sprite sprite-search"></i>検索
                </button>
            </td>
        </tr>
    </table>
	<?php if(!empty($SettingData->SettingAey['MTRDispFlg'])):?>
	<input type="hidden" name="MTRDispFlg" value="<?=$SettingData->SettingAey['MTRDispFlg']?>" />
	<?php endif; ?>
</form>
