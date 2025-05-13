<?php
$sub_menu = "500150";
include_once('./_common.php');

auth_check($auth[$sub_menu], 'r');

$token = get_token();

$sql_common = " from {$g5['offer_table']} ";

$sql_search = " where (1) ";

if (!$sst) {
	$sst  = "of_id";
	$sod = "desc";
}
$sql_order = " order by {$sst} {$sod} ";

$sql = " select count(*) as cnt
			{$sql_common}
			{$sql_search}
			{$sql_order} ";
$row = sql_fetch($sql);
$total_count = $row['cnt'];

$rows = $config['cf_page_rows'];
$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
if ($page < 1) $page = 1; // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함

$sql = " select *
			{$sql_common}
			{$sql_search}
			{$sql_order}
			limit {$from_record}, {$rows} ";
$result = sql_query($sql);

$listall = '<a href="'.$_SERVER['PHP_SELF'].'" class="ov_listall">전체목록</a>';

$g5['title'] = '주식 관리';
include_once ('./admin.head.php');

$colspan = 10;

$pg_anchor = '<ul class="anchor">
	<li><a href="#anc_001">주식물품 목록</a></li>
	<li><a href="#anc_002">주식물품 등록</a></li>
</ul>';

include_once(G5_PLUGIN_PATH.'/jquery-ui/datepicker.php');
?>
<div class="btn_add01 btn_add" style="position:relative; z-index:5;">
	<a href="<?=G5_URL?>/stock/" target="_blank">주식목록 바로가기</a>
</div>
<section id="anc_001" style="z-index:1;">
	<h2 class="h2_frm">주식물품 목록</h2>
	<?php echo $pg_anchor ?>

	<div class="local_ov01 local_ov">
		<?php echo $listall ?>
		전체 <?php echo number_format($total_count) ?> 건
	</div>

	<form name="fmosterlist" id="fmosterlist" method="post" action="./offer_list_delete.php" onsubmit="return fmosterlist_submit(this);"  enctype="multipart/form-data">
		<input type="hidden" name="sst" value="<?php echo $sst ?>">
		<input type="hidden" name="sod" value="<?php echo $sod ?>">
		<input type="hidden" name="sfl" value="<?php echo $sfl ?>">
		<input type="hidden" name="stx" value="<?php echo $stx ?>">
		<input type="hidden" name="page" value="<?php echo $page ?>">
		<input type="hidden" name="token" value="<?php echo $token ?>">

		<div class="tbl_head01 tbl_wrap">
			<table>
			<caption><?php echo $g5['title']; ?> 목록</caption>
			<colgroup>
				<col style="width: 50px;" />
				<col style="width: 50px;"/>
				<col  />
				<col style="width: 220px;"/>
				<col style="width: 100px;"/>
				<col style="width: 170px;"/>
				<col style="width: 160px;"/>
				<col style="width: 80px;"/>
				<col style="width: 80px;"/>
			</colgroup>
			<thead>
			<tr>
				<th scope="col">
					<label for="chkall" class="sound_only">주식물품 내역 전체</label>
					<input type="checkbox" name="chkall" value="1" id="chkall" onclick="check_all(this.form)">
				</th>
				<th scope="col" colspan="2">주식물품</th>
				<th scope="col">진열기간</th>
				<th scope="col">변동시간</th>
				<th scope="col">화폐 변동범위</th>
				<th scope="col">마지막 갱신시간</th>
				<th scope="col">현재시세</th>
				<th scope="col">사용</th>
			</tr>
			</thead>
			<tbody>
			<?php
			for ($i=0; $row=sql_fetch_array($result); $i++) {
				$bg = 'bg'.($i%2);
				$img = get_item_img($row['it_id']);

				if($row['of_use']) {
					// 시세 변동 체크 및 업데이트
					$row = set_reset_offer($row);
				}

			?>

			<tr class="<?php echo $bg; ?>">
				<td style="text-align: center">
					<input type="checkbox" name="chk[]" value="<?php echo $i ?>" id="chk_<?php echo $i ?>">
					<input type="hidden" name="of_id[<?php echo $i ?>]" value="<?php echo $row['of_id'] ?>" />
				</td>

				<td style="text-align: center">
					<?php if($img) { ?>
						<img src='<?=$img?>' style="max-height:50px;">
					<? } ?>
				</td>
				<td style="text-align:left;">
					<?=$row['it_name']?>
				</td>
				<td>
					<input type="text" name="of_sdate[<?php echo $i ?>]" value="<?php echo $row['of_sdate'] ?>" class="frm_input date" style="width:90px;">
					~
					<input type="text" name="of_edate[<?php echo $i ?>]" value="<?php echo $row['of_edate'] ?>" class="frm_input date" style="width:90px;">
				</td>
				<td>
					<select name="of_change_time[<?php echo $i ?>]" style="display: block; width:100%;">
						<option value="1" <?=$row['of_change_time'] == "1" ? "selected" : ""?>>1시간</option>
						<option value="3" <?=$row['of_change_time'] == "3" ? "selected" : ""?>>3시간</option>
						<option value="6" <?=$row['of_change_time'] == "6" ? "selected" : ""?>>6시간</option>
						<option value="12" <?=$row['of_change_time'] == "12" ? "selected" : ""?>>12시간</option>
					</select>
				</td>
				<td>
					<input type="text" name="of_min_cost[<?php echo $i ?>]" value="<?php echo $row['of_min_cost'] ?>" class="frm_input" style="width:60px;">
					~
					<input type="text" name="of_max_cost[<?php echo $i ?>]" value="<?php echo $row['of_max_cost'] ?>" class="frm_input" style="width:60px;">
				</td>
				<td>
					<input type="text" name="of_reset[<?php echo $i ?>]" value="<?php echo $row['of_reset'] ?>" class="frm_input" style="width: 98%;">
				</td>
				<td>
					<input type="text" name="of_cost[<?php echo $i ?>]" value="<?php echo $row['of_cost'] ?>" class="frm_input" style="width: 98%;">
				</td>
				<td>
					<input type="checkbox" name="of_use[<?php echo $i ?>]" value="1" <?php echo $row['of_use']?"checked":"" ?>>
				</td></tr><tr class="<?php echo $bg; ?>">
				<td colspan="<?=$colspan?>">
					<textarea name="of_content[<?=$i?>]" style="resize:none; height:80px;"><?=$row['of_content']?></textarea>
				</td>

			</tr>

			<?php
			}

			if ($i == 0)
				echo '<tr><td colspan="'.$colspan.'" class="empty_table">자료가 없습니다.</td></tr>';
			?>
			</tbody>
			</table>
		</div>

		<div class="btn_list01 btn_list">
			<input type="submit" name="act_button" value="선택수정" onclick="document.pressed=this.value">
			<input type="submit" name="act_button" value="선택삭제" onclick="document.pressed=this.value">
		</div>

	</form>

	<?php echo get_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, "{$_SERVER['PHP_SELF']}?$qstr&amp;page="); ?>
</section>

<section id="anc_002">
	<h2 class="h2_frm">주식물품정보 등록</h2>
	<?php echo $pg_anchor ?>

	<form name="fmosterform" method="post" id="fmosterform" action="./offer_update.php" autocomplete="off" enctype="multipart/form-data">
	<input type="hidden" name="sfl" value="<?php echo $sfl ?>">
	<input type="hidden" name="stx" value="<?php echo $stx ?>">
	<input type="hidden" name="sst" value="<?php echo $sst ?>">
	<input type="hidden" name="sod" value="<?php echo $sod ?>">
	<input type="hidden" name="page" value="<?php echo $page ?>">
	<input type="hidden" name="token" value="<?php echo $token ?>">

	<div class="tbl_frm01 tbl_wrap">
		<table>
		<colgroup>
			<col style="width: 120px;">
			<col>
		</colgroup>
		<tbody>
		<tr>
			<th scope="row">주식 아이템</th>
			<td>
				<input type="hidden" name="it_id" id="it_id" value="" />
				<input type="text" name="it_name" value="" id="it_name" onkeyup="get_ajax_item(this, 'item_list', 'it_id', '주식');" />
				<div id="item_list" class="ajax-list-box"><div class="list"></div></div>
			</td>
		</tr>
		<tr>
			<th>내용</th>
			<td>
				<textarea name="of_content" style="height:100px; resize:none;"></textarea>
			</th>
		</tr>
		<tr>
			<th>진열기간</th>
			<td>
				<?php echo help("※ 날짜를 지정하지 않을 시, 적용되지 않습니다.") ?>
				<input type="text" name="of_sdate" size="15" class="date" />
				~ 
				<input type="text" name="of_edate" size="15" class="date" />
			</td>
		</tr>

		<tr>
			<th>변동시간</th>
			<td>
				<input type="radio" name="of_change_time" id="of_change_time_1" value="1" />
				<label for="of_change_time_1">1시간</label>
				&nbsp;&nbsp;
				<input type="radio" name="of_change_time" id="of_change_time_2" value="3" />
				<label for="of_change_time_2">3시간</label>
				&nbsp;&nbsp;
				<input type="radio" name="of_change_time" id="of_change_time_3" value="6" />
				<label for="of_change_time_3">6시간</label>
				&nbsp;&nbsp;
				<input type="radio" name="of_change_time" id="of_change_time_4" value="12" />
				<label for="of_change_time_4">12시간</label>
				&nbsp;&nbsp;
			</td>
		</tr>

		<tr>
			<th>화폐변동범위</th>
			<td>
				<input type="text" name="of_min_cost" size="5" /> <?=$config['cf_money_pice']?>
				~ 
				<input type="text" name="of_max_cost" size="5" /> <?=$config['cf_money_pice']?>
			</td>
		</tr>
		
		<tr>
			<th scope="row">사용</th>
			<td>
				<input type="checkbox" name="of_use" value="1" />
			</td>
		</tr>
		
		</tbody>
		</table>
	</div>

	<div class="btn_confirm01 btn_confirm">
		<input type="submit" value="확인" class="btn_submit">
	</div>

	</form>

</section>

<script>
$(function(){
	$(".date").datepicker({ changeMonth: true, changeYear: true, dateFormat: "yy-mm-dd", showButtonPanel: true, yearRange: "c-99:c+99" });
});

function fmosterlist_submit(f)
{
	if (!is_checked("chk[]")) {
		alert(document.pressed+" 하실 항목을 하나 이상 선택하세요.");
		return false;
	}

	if(document.pressed == "선택삭제") {
		if(!confirm("선택한 자료를 정말 삭제하시겠습니까?")) {
			return false;
		}
	}

	return true;
}
</script>

<?php
include_once ('./admin.tail.php');
?>
