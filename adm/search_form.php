<?php
$sub_menu = "800200";
include_once('./_common.php');
include_once(G5_EDITOR_LIB);

auth_check($auth[$sub_menu], 'w');

$html_title = '탐색';
$required = "";
$readonly = "";
if ($w == '') {

	$html_title .= ' 생성';
	$required = 'required';
	$required_valid = 'alnum_';
	$sound_only = '<strong class="sound_only">필수</strong>';
	$item['se_use'] = '1';


} else if ($w == 'u') {

	$html_title .= ' 수정';
	$item = sql_fetch("select * from {$g5['search_table']} where se_id = '{$se_id}'");
	if (!$item['se_id'])
		alert('존재하지 않는 탐색 이벤트 입니다.');
	$readonly = 'readonly';
}

$g5['title'] = $html_title;
include_once ('./admin.head.php');

$pg_anchor = '<ul class="anchor">
	<li><a href="#anc_001">기본 설정</a></li>';

if($config['cf_4']) {
	$pg_anchor .= '<li><a href="#anc_002">탐색 설정</a></li>';
}
$pg_anchor .= '</ul>';


$frm_submit = '<div class="btn_confirm01 btn_confirm">
	<input type="submit" value="확인" class="btn_submit" accesskey="s">
	<a href="./item_list.php?'.$qstr.'">목록</a>'.PHP_EOL;
$frm_submit .= '</div>';

?>

<form name="fitemform" id="fitemform" action="./search_form_update.php" onsubmit="return fitemform_submit(this)" method="post" enctype="multipart/form-data">
<input type="hidden" name="w" value="<?php echo $w ?>">
<input type="hidden" name="se_id" value="<?php echo $se_id ?>">
<input type="hidden" name="sfl" value="<?php echo $sfl ?>">
<input type="hidden" name="stx" value="<?php echo $stx ?>">
<input type="hidden" name="sst" value="<?php echo $sst ?>">
<input type="hidden" name="sod" value="<?php echo $sod ?>">
<input type="hidden" name="page" value="<?php echo $page ?>">

<section id="anc_001">
	<h2 class="h2_frm">탐색 이벤트 기본 설정</h2>
	<?php echo $pg_anchor ?>

	<div class="tbl_frm01 tbl_wrap">
		<table>
			<caption>탐색 이벤트 기본 설정</caption>
			<colgroup>
				<col style="width: 130px;">
				<col style="width: 100px;">
				<col>
			</colgroup>
			<tbody>
				<tr>
					<th scope="row">사용여부</th>
					<td colspan="2">
						<input type="checkbox" name="se_use" id="se_use" value="1" <?=$item['se_use'] == 'Y'? "checked" : ""?>/>
					</td>
				</tr>
				<tr>
					<th scope="row">지역 분류</th>
					<td colspan="2">
						<select name="se_region" id="se_region" >
								<option value="0" <?=$item['se_region'] == "0" ? "selected" : ""?>>학교</option>
								<option value="1" <?=$item['se_region'] == "1" ? "selected" : ""?>>퀴디치 경기장</option>
								<option value="2" <?=$item['se_region'] == "2" ? "selected" : ""?>>금지된 숲</option>
								<option value="3" <?=$item['se_region'] == "3" ? "selected" : ""?>>기숙사</option>
						</select>
					</td>
				</tr>
				<tr>
					<th scope="row" rowspan="2">필요 스탯</th>
					<td>
						스탯 종류
					</td>
					<td colspan="2">
						<select name="se_stat" id="se_stat" >
								<option value=" " <?=$item['se_stat'] == " " ? "selected" : ""?>>X</option>
								<option value="STR" <?=$item['se_stat'] == "STR" ? "selected" : ""?>>용기</option>
								<option value="INT" <?=$item['se_stat'] == "INT" ? "selected" : ""?>>지혜</option>
								<option value="CRE" <?=$item['se_stat'] == "CHA" ? "selected" : ""?>>신용</option>
								<option value="DEX" <?=$item['se_stat'] == "SST" ? "selected" : ""?>>기지</option>
						</select>
					</td>
				</tr>
				<tr>
					<td>
						적용값
					</td>
					<td>
						<?php echo help("※ 적용값이 추가로 필요 할 시에만 기입해 주시길 바랍니다.") ?>
						<input type="text" name="se_num" value="<?php echo $item['se_num']; ?>" id="se_num" size="15">
					</td>
				</tr>
				<tr>
					<th scope="row">아이템 이름</th>
					<td colspan="2">
						<input type="hidden" name="it_id" id="it_id" value="<?=$item['se_it_id']?>" />
						<input type="text" name="se_it_name" value="<?=get_item_name($item['se_it_id'])?>" id="se_it_name" onkeyup="get_ajax_item(this, 'item_list', 'it_id');" />
						<div id="item_list" class="ajax-list-box"><div class="list"></div></div>
					</td>
				</tr>

				<tr>
					<th scope="row">설명</th>
					<td colspan="2">
						<input type="text" name="se_text" value="<?php echo get_text($item['se_text']) ?>" id="se_text" required class="required" size="80">
					</td>
				</tr>

								<tr>
									<th scope="row">확률</th>

									<td>
										<?php echo help("※ 100면체 주사위를 굴렸을 때 나오는 숫자 중 획득 가능 범위를 지정해 주시길 바랍니다. (0 ~ 100)<br />※ 다수의 구간이 겹칠 시,랜덤으로 획득 됩니다.") ?>
										<input type="text" name="se_reward" value="<?php echo $item['se_reward']; ?>" id="se_reward" size="5" maxlength="11">
										~
										<input type="text" name="se_range" value="<?php echo $item['se_range']; ?>" id="se_range" size="5" maxlength="11"> 구간 획득
									</td>
								</tr>
			</tbody>
		</table>
	</div>
</section>

<?php echo $frm_submit; ?>
</form>


<script>
function fitemform_submit(f)
{
	return true;
}

</script>

<?php
include_once ('./admin.tail.php');
?>
