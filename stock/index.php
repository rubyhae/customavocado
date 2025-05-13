<?php
include_once('./_common.php');
$g5['title'] = "주식페이지";
include_once('./_head.php');

add_stylesheet('<link rel="stylesheet" href="'.G5_URL.'/stock/css/style.css">', 0);


$sql_common = " from {$g5['offer_table']} ";
$sql_search = " where of_use = '1' ";
$sql_order = " order by of_edate asc ";

// 1. 진열 기간 사용 시
$sql_search .= " AND (of_sdate <= '".date('Y-m-d')."' OR of_sdate = '') "; 
$sql_search .= " AND (of_edate >= '".date('Y-m-d')."' OR of_edate = '') "; 

$offer_sql = " select * {$sql_common} {$sql_search} {$sql_order} ";
$offer_result = sql_query($offer_sql);

?>


<div class="offerWrap">
	<div class="offer-area"><div>
		<? for($i=0; $offer = sql_fetch_array($offer_result); $i++) {
			if($offer['of_id']) {
				// 2. 갱신 처리
				$item = get_item($offer['it_id']);
				if($item['it_id']) {
					// 시세 변동 체크 및 업데이트
					$offer = set_reset_offer($offer);
					// 현재 소지중인 아이템일 경우 갯수 가져오기
					$inven = sql_fetch("select count(*) as cnt from {$g5['inventory_table']} where ch_id = '{$character['ch_id']}' and it_id = '{$item['it_id']}'");
		?>
		
			<div class="itemArea theme-box">
				<div class="inner">
					<div class="item">
						<? if($item['it_img']) {?>
							<div class="it">
								<img src="<?=$item['it_img']?>" />
							</div>
							<p class="ui-btn">보유 : <i id="inven_count"><?=$inven['cnt']?></i></p>
							<strong><?=$item['it_name']?></strong>
						<? } ?>
					</div>
					<div class="con">
						<?=nl2br($offer['of_content'])?>
					</div>

					<? if($offer['of_id']) { ?>
						<div class="control">
							<div class="cost">
								<span>현재 <?=$config['cf_money']?></span>
								<strong><em id="now_cost"><?=$offer['of_cost']?></em> <i><?=$config['cf_money_pice']?></i></strong>
							</div>
							<? if($character['ch_state'] == '승인') { ?>
							<ul>
								<li><a class="ui-btn point" href="./stock_update.php?of_id=<?=$offer['of_id']?>&type=구매" onclick='return confirm("정말 구매하시겠습니까?");'>구매하기</a></li>
								<li><a class="ui-btn point" href="./stock_update.php?of_id=<?=$offer['of_id']?>&type=판매" onclick='return confirm("정말 판매하시겠습니까?");'>판매하기</a></li>
							</ul>
							<? } ?>
						</div>
					<? } ?>

				</div>
			</div>

		<?
				} else {
					// 아이템이 존재하지 않음
					$sql = " delete from {$g5['offer_table']} where of_id = '{$offer['of_id']}' ";
					sql_query($sql);
				}
			}	
		} ?>
	</div></div>
</div>

<script>
function fn_update_item(of_id, type) {
	if(confirm("정말 "+type+"하시겠습니까?")) {
		var sendData = {of_id:of_id, type:type};
		var url = g5_url + "/stock/stock_update.php";
		$.ajax({
			type: 'post'
			, url : url
			, data: sendData
			, dataType:"json"
			, success : function(data) {
				/*$('#now_cost').text(data.cost);
				$('#inven_count').text(data.count);
				if(data.talk) {
					fn_set_comment(data.talk);
				}*/
				location.reload();
			}
		});
	}
}

</script>

<?php
include_once('./_tail.php');
?>

