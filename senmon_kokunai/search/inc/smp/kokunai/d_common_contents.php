<div class="search-box">
	<?php if ($SEARCH_DETAIL_FLAG):?>
		<h2><?=$TITLE;?>検索条件変更<a id="cancel" data-direction="reverse" href="#search_result_page" class="close-btn"><i></i>閉じる</a></h2>
	<?php else:?>
		<?php if ($FREE_FLAG):?>
		<h2 class="main-title mainBgClr mb10 search_title">
        	<span class="main-title-txt"><?=$masterCsv[KEY_MASTER_CSV_NAME_JA];?> フリープラン検索</span>
        </h2>
        <?php else:?>
		<h2 class="main-title mainBgClr mb10 search_title">
        	<span class="main-title-txt"><?=$masterCsv[KEY_MASTER_CSV_NAME_JA];?> ツアー検索</span>
        </h2>
        <?php endif;?>
	<?php endif;?>

	<?php // 国内ツリープランの追加項目 ?>
	<?php if ($FREE_FLAG):?>
		<div class="search_transport">
			<dl>
				<dt>交通手段を選択してください</dt>
	            <dd>
	            <table border="0" cellpadding="0" cellspacing="0">
	            	<tr>
	                	<td><label><input class="check_transport_free" type="radio" name="dselect" data-role="none" data-value="" checked> 指定なし</label></td>
	                	<td><label><input class="check_transport_free" type="radio" name="dselect" data-role="none" data-value="3"> 飛行機利用</label></td>
	              	</tr>
	              	<tr>
	                	<td><label><input class="check_transport_free" type="radio" name="dselect" data-role="none" data-value="2"> 列車利用</label></td>
	                	<td><label><input class="check_transport_free" type="radio" name="dselect" data-role="none" data-value="1,4"> バス・船利用</label></td>
	              	</tr>
	            </table>
	            </dd>
			</dl>
	    </div>
	<?php endif;?>

	<div class="search_hatsu">
		<dl>
			<dt id="modal_menu_hatsu"><a href="#modal_hatsu" class="modal-menu">出発地を選択してください</a></dt>
			<dd id="add_contents_hatsu" style="display:none;">
				<div class="ttl">出発地: </div>
				<ul id="decided_contents_hatsu">

				</ul>
			</dd>
		</dl>
		<a class="add-btn" id="add_hatsu" href="#modal_hatsu" style="display:none;"><i></i>出発地を追加する</a>
	</div>


	<?php // 国内ツリープランの追加項目 ?>
	<?php if ($FREE_FLAG):?>
	  	<div class="search_dep_airport" style="display: none;">
	 		<dl>
				<dt id="modal_menu_dep_airport"><a href="#modal_dep_airport" class="modal-menu">出発空港を選択してください</a></dt>
				<dd id="add_contents_dep_airport" style="display:none;">
					<div class="ttl">出発空港: </div>
					<ul id="decided_contents_dep_airport">
<!--
						<li><input type="button" class="del" value="削除" data-role="none">羽田空港</li>
						<li><input type="button" class="del" value="削除" data-role="none">成田国際空港</li>
 -->
					</ul>
				</dd>
			</dl>
			<a class="add-btn" id="add_dep_airport" href="#modal_dep_airport" style="display:none;"><i></i>出発空港を追加する</a>
	    </div>

	  	<div class="search_arr_airport" style="display: none;">
			<dl>
				<dt id="modal_menu_arr_airport"><a href="#modal_arr_airport" class="modal-menu">到着空港を選択してください</a></dt>
				<dd id="add_contents_arr_airport" style="display:none;">
					<div class="ttl">到着空港: </div>
					<ul id="decided_contents_arr_airport">
<!--
						<li><input type="button" class="del" value="削除" data-role="none">新千歳空港</li>
						<li><input type="button" class="del" value="削除" data-role="none">稚内空港</li>
 -->
					</ul>
				</dd>
			</dl>
			<a class="add-btn" id="add_arr_airport" href="#modal_arr_airport" style="display:none;"><i></i>到着空港を追加する</a>
		</div>
	<?php endif;?>


	<div class="search_destination">
		<dl>
			<dt id="modal_menu_destination"><a href="#modal_destination" class="modal-menu">目的地を選択してください</a></dt>
			<dd id="add_contents_destination" style="display:none;">
				<div class="ttl">目的地: </div>
				<ul id="decided_contents_destination">

				</ul>
			</dd>
		</dl>
		<a class="add-btn" id="add_destination" href="#modal_destination" style="display:none;"><i></i>目的地を追加する</a>
		<?php if ($SEARCH_DETAIL_FLAG):?>
			<div class="destination-select" style="display:none;">
				<ul>
					<li><label><input type="radio" name="destselect" data-role="none" data-value="1">周遊検索<span>選択した目的地すべてに行く</span></label></li>
					<li><label><input type="radio" name="destselect" data-role="none" data-value="2" checked>まとめて検索<span>選択した目的地のいずれかに行く</span></label></li>
				</ul>
			</div>
		<?php endif;?>
	</div>
	<div class="search_date">
		<dl>
			<dt id="modal_menu_date"><a href="#modal_date" class="modal-menu">出発日を選択してください</a></dt>
			<dd id="add_contents_date" style="display:none;">
				<div class="ttl">出発日: </div>
				<ul id="decided_contents_date">

				</ul>
			</dd>
		</dl>
	</div>
		<div class="search_kikan">
		<dl>
			<dt id="modal_menu_kikan"><a href="#modal_kikan" class="modal-menu">旅行日数を選択してください</a></dt>
			<dd id="add_contents_kikan" style="display:none;">
				<div class="ttl">旅行日数: </div>
				<ul id="decided_contents_kikan">

				</ul>
			</dd>
		</dl>
	</div>

	<?php // 国内ツアー ?>
	<?php if (!$FREE_FLAG):?>
		<div class="search_conductor">
			<dl>
				<dt id="modal_menu_conductor"><a href="#modal_conductor" class="modal-menu">添乗員を選択してください</a></dt>
				<dd id="add_contents_conductor" style="display:none;">
					<div class="ttl">添乗員: </div>
					<ul id="decided_contents_conductor">

					</ul>
				</dd>
			</dl>
		</div>
	<?php endif;?>

	<?php // 再検索なら?>
	<?php if($SEARCH_DETAIL_FLAG):?>

		<?php // 国内ツリープランの追加項目 ?>
		<?php if ($FREE_FLAG):?>
			<div class="search_stay_number no-icn">
				<dl>
					<dt id="modal_menu_stay_number"><a href="#modal_stay_number" class="modal-menu">宿泊数</a></dt>
					<dd id="add_contents_stay_number" style="display:none;">
						<div class="ttl">宿泊数: </div>
						<ul id="decided_contents_stay_number">

						</ul>
					</dd>
				</dl>
			</div>
		<?php endif;?>

		<div class="search_price">
			<div class="price-txt">旅行代金: <span>10,000～上限なし</span></div>
			<div data-role="rangeslider" data-mini="true">
		        <label for="price_min" class="ui-hidden-accessible">下限金額:</label>
		        <input type="range" name="price_min" id="price_min" min="0" max="36" step="1" value="0">
		        <label for="price_max" class="ui-hidden-accessible">上限金額:</label>
		        <input type="range" name="price_max" id="price_max" min="0" max="36" step="1" value="36">
		    </div>
		</div>

		<?php // 国内ツアー ?>
		<?php if (!$FREE_FLAG):?>
			<div class="search_transport no-icn">
				<dl>
					<dt id="modal_menu_transport"><a href="#modal_transport" class="modal-menu">交通機関</a></dt>
					<dd id="add_contents_transport" style="display:none;">
						<div class="ttl">交通機関: </div>
						<ul id="decided_contents_transport">
						</ul>
					</dd>
				</dl>
			</div>

			<div class="search_dep_airport no-icn">
				<dl>
					<dt id="modal_menu_dep_airport"><a href="#modal_dep_airport" class="modal-menu">出発空港</a></dt>
					<dd id="add_contents_dep_airport" style="display:none;">
						<div class="ttl">出発空港: </div>
						<ul id="decided_contents_dep_airport">
						</ul>
					</dd>
				</dl>
			</div>
		<?php endif;?>

		<div class="search_airline no-icn">
			<dl>
				<dt id="modal_menu_airline"><a href="#modal_airline" class="modal-menu">航空会社</a></dt>
				<dd id="add_contents_airline" style="display:none;">
					<div class="ttl">航空会社: </div>
					<ul id="decided_contents_airline">
					</ul>
				</dd>
			</dl>
		</div>

		<?php // 国内ツアー ?>
		<?php if (!$FREE_FLAG):?>
			<div class="search_bus_boarding no-icn">
				<dl>
					<dt id="modal_menu_bus_boarding"><a href="#modal_bus_boarding" class="modal-menu">バス乗車地</a></dt>
					<dd id="add_contents_bus_boarding" style="display:none;">
						<div class="ttl">バス乗車地: </div>
						<ul id="decided_contents_bus_boarding">
						</ul>
					</dd>
				</dl>
			</div>
		<?php endif;?>

		<div class="search_hotel no-icn">
			<dl>
				<dt id="modal_menu_hotel"><a href="#modal_hotel" class="modal-menu">ホテル・旅館</a></dt>
				<dd id="add_contents_hotel" style="display:none;">
					<div class="ttl">ホテル・旅館: </div>
					<ul id="decided_contents_hotel">
<!--
						<li><input type="button" class="del_hotel" value="すべて削除" data-role="none">水美温泉会館/国王大飯店（エンペラー）</li>
 -->
					</ul>
				</dd>
			</dl>
		</div>
		<div class="search_stock no-icn">
			<dl>
				<dt id="modal_menu_stock"><a href="#modal_stock" class="modal-menu">残席</a></dt>
				<dd id="add_contents_stock" style="display:none;">
					<div class="ttl">残席: </div>
					<ul id="decided_contents_stock">

					</ul>
				</dd>
			</dl>
		</div>
		<div class="search_decide no-icn">
			<dl>
				<dt id="modal_menu_decide"><a href="#modal_decide" class="modal-menu">催行状況</a></dt>
				<dd id="add_contents_decide" style="display:none;">
					<div class="ttl">催行状況: </div>
					<ul id="decided_contents_decide">
					</ul>
				</dd>
			</dl>
		</div>
		<div class="search_mainbrand no-icn">
			<dl>
				<dt id="modal_menu_mainbrand"><a href="#modal_mainbrand" class="modal-menu">ブランド</a></dt>
				<dd id="add_contents_mainbrand" style="display:none;">
					<div class="ttl">ブランド: </div>
					<ul id="decided_contents_mainbrand">
					</ul>
				</dd>
			</dl>
		</div>
		<div class="search_early_discount_flag no-icn">
			<dl>
				<dt id="modal_menu_early_discount_flag"><a href="#modal_early_discount_flag" class="modal-menu">早期割引</a></dt>
				<dd id="add_contents_early_discount_flag" style="display:none;">
					<div class="ttl">早期割引: </div>
					<ul id="decided_contents_early_discount_flag">

					</ul>
				</dd>
			</dl>
		</div>
		<div class="search_bunrui no-icn">
			<dl>
				<dt id="modal_menu_bunrui"><a href="#modal_bunrui" class="modal-menu">テーマ</a></dt>
				<dd id="add_contents_bunrui" style="display:none;">
					<div class="ttl">テーマ: </div>
					<ul id="decided_contents_bunrui">
					</ul>
				</dd>
			</dl>
		</div>
		<div class="search_keyword">
			<div class="search-box">
				<input type="search" id="free_word" placeholder="キーワードからさがす" data-role="none">
			</div>
		</div>

	<?php endif;?>

	<div class="all-delete">
		<a style="color: #000000;"><i></i>全ての条件を削除</a>
	</div>
</div>


<div class="fixed-footer colorposition" id="fix_footer" style="">
	<div class="cf">
		<div class="search-result">
			<div>
				現在の該当件数
				<span class="search_result_hit"></span>
			</div>
		</div>
		<div class="search-btn" id="searchBtn" style="">
			<div>
				<input type="button" value="検索" data-role="none">
			</div>
		</div>
		<div class="decide-btn" id="decideBtn" style="display: none;">
			<div>
				<input type="button" value="確定" data-role="none">
			</div>
		</div>
	</div>
</div>
