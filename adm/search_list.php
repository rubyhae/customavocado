<?php
$sub_menu = "800200";
include_once('./_common.php');

auth_check($auth[$sub_menu], 'r');

$sql_common = " from {$g5['search_table']} ";
$sql_search = " where (1) ";

if ($stx) {
	$sql_search .= " and ( ";
	switch ($sfl) {
		default :
			$sql_search .= " ($sfl like '%$stx%') ";
			break;
	}
	$sql_search .= " ) ";
}


if (!$sst) {
	$sst  = "se_id";
	$sod = "asc";
}
$sql_order = " order by $sst $sod ";

$sql = " select count(*) as cnt {$sql_common} {$sql_search} {$sql_order} ";
$row = sql_fetch($sql);
$total_count = $row['cnt'];

$rows = $config['cf_page_rows'];
$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
if ($page < 1) { $page = 1; } // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함

$sql = " select * {$sql_common} {$sql_search} {$sql_order} limit {$from_record}, {$rows} ";
$result = sql_query($sql);

$listall = '<a href="'.$_SERVER['PHP_SELF'].'" class="ov_listall">전체목록</a>';

$g5['title'] = '탐색 관리';
include_once('./admin.head.php');

$colspan = 12;
?>

<div class="local_ov01 local_ov">
	<?php echo $listall ?>
	추가된 탐색 수 <?php echo number_format($total_count) ?>개
</div>

<form name="fsearch" id="fsearch" class="local_sch01 local_sch" method="get">


<label for="sfl" class="sound_only">검색대상</label>
<select name="sfl" id="sfl">
	<option value="se_it_name"<?php echo get_selected($_GET['sfl'], "se_it_name", true); ?>>아이템 이름</option>
	<option value="se_text"<?php echo get_selected($_GET['sfl'], "se_text"); ?>>탐색 설명</option>
</select>
<label for="stx" class="sound_only">검색어<strong class="sound_only"> 필수</strong></label>
<input type="text" name="stx" value="<?php echo $stx ?>" id="stx">
<input type="submit" value="검색" class="btn_submit">

</form>

<?php if ($is_admin == 'super') { ?>
<div class="btn_add01 btn_add">
	<a href="./search_form.php" id="bo_add">탐색 추가</a>
</div>
<?php } ?>

<form name="fitemlist" id="fitemlist" action="./search_list_update.php" onsubmit="return fitemlist_submit(this);" method="post">
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
			<col style="width:  40px;" />
			<col style="width: 100px;" />
			<col style="width: 100px;" />
			<col />
			<col style="width: 60px;"/>
			<col style="width: 60px;"/>
		</colgroup>
		<thead>
			<tr>
				<th scope="col" rowspan="2" class="bo-right">
					<label for="chkall" class="sound_only">탐색 전체</label>
					<input type="checkbox" name="chkall" value="1" id="chkall" onclick="check_all(this.form)">
				</th>
				<th scope="col" rowspan="2">지역</th>
				<th scope="col" class="bo-left bo-no-bottom bo-right">아이템 이름</th>
				<th scope="col" rowspan="2">사용</th>
				<th scope="col" rowspan="2">관리</th>
				<th scope="col" rowspan="2">확률</th>
				<th scope="col" rowspan="2">요구 스탯</th>
			</tr>
			<tr>
				<!--th scope="col" class="bo-right">적용값</th-->
				<th scope="col" class="bo-left">설명</th>
			</tr>
		</thead>
		<tbody>
			<?php
			for ($i=0; $item=sql_fetch_array($result); $i++) {
				$one_update = '<a href="./search_form.php?w=u&amp;se_id='.$item['se_id'].'&amp;'.$qstr.'">수정</a>';
				$bg = 'bg'.($i%2);
			?>

			<tr class="<?php echo $bg; ?>">
				<td rowspan="2">
					<label for="chk_<?php echo $i; ?>" class="sound_only"><?php echo get_text($item['se_it_name']) ?></label>
					<input type="checkbox" name="chk[]" value="<?php echo $i ?>" id="chk_<?php echo $i ?>">
					<input type="hidden" name="se_id[<?php echo $i ?>]" value="<?php echo $item['se_id'] ?>" />
					<input type="hidden" name="se_it_id[<?php echo $i ?>]" value="<?php echo $item['se_it_id'] ?>" />
				</td>
				<td rowspan="2">
					<select name="se_region[<?php echo $i ?>]" style="width: 80px;">
							<option value="0" <?=$item['se_region'] == "0" ? "selected" : ""?>>학교</option>
							<option value="1" <?=$item['se_region'] == "1" ? "selected" : ""?>>퀴디치 경기장</option>
							<option value="2" <?=$item['se_region'] == "2" ? "selected" : ""?>>금지된 숲</option>
							<option value="3" <?=$item['se_region'] == "3" ? "selected" : ""?>>기숙사</option>
					</select>
				</td>
				<td class="txt-left">
					<input type="text" name="se_it_name[<?php echo $i ?>]" value="<?php echo get_text($item['se_it_name']) ?>" id="se_it_name_<?php echo $i ?>" size="20">
				</td>
				<td rowspan="2">
					<input type="checkbox" name="se_use[<?php echo $i ?>]" value="1" id="se_use_<?php echo $i ?>" <?php echo $item['se_use']?"checked":"" ?>>
				</td>
				<td rowspan="2" class="td_mngsmall">
					<?php echo $one_update ?>
					<?php echo $one_copy ?>
				</td>
			</tr>
			<tr class="<?php echo $bg; ?>">
				<!-- td>
					<input type="text" name="it_value[<?php echo $i ?>]" value="<?php echo get_text($item['it_value']) ?>" id="it_value_<?php echo $i ?>" size="8">
				</td -->
				<td class="txt-left bo-no-left">
					<input type="text" name="se_text[<?php echo $i ?>]" value="<?php echo get_text($item['se_text']) ?>" id="se_text_<?php echo $i ?>" size="40">
				</td>
				<td>
					<input type="text" name="se_reward[<?php echo $i ?>]" value="<?php echo get_text($item['se_reward']) ?>" id="se_reward_<?php echo $i ?>" size="5">
						<input type="text" name="se_range[<?php echo $i ?>]" value="<?php echo get_text($item['se_range']) ?>" id="se_range_<?php echo $i ?>" size="5">
				</td>
				<td>
					<select name="se_stat[<?php echo $i ?>]" id="se_stat_<?php echo $i ?>" style="width: 80px;">
							<option value=" " <?=$item['se_stat'] == " " ? "selected" : ""?>>X</option>
							<option value="STR" <?=$item['se_stat'] == "STR" ? "selected" : ""?>>용기</option>
							<option value="INT" <?=$item['se_stat'] == "INT" ? "selected" : ""?>>지혜</option>
							<option value="CRE" <?=$item['se_stat'] == "CHA" ? "selected" : ""?>>신용</option>
							<option value="DEX" <?=$item['se_stat'] == "SST" ? "selected" : ""?>>기지</option>
					</select>
					<input type="text" name="se_num[<?php echo $i ?>]" value="<?php echo get_text($item['se_num']) ?>" id="se_num_<?php echo $i ?>" size="5">
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
	<?php if ($is_admin == 'super') { ?>
	<input type="submit" name="act_button" value="선택삭제" onclick="document.pressed=this.value">
	<?php } ?>
</div>

</form>

<?php echo get_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, $_SERVER['PHP_SELF'].'?'.$qstr.'&amp;se_id='.$se_id.'&amp;page='); ?>

<script>
function fitemlist_submit(f)
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
include_once('./admin.tail.php');
?>
