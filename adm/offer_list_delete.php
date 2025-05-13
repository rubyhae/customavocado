<?php
$sub_menu = '500150';
include_once('./_common.php');

check_demo();

if (!count($_POST['chk'])) {
	alert($_POST['act_button']." 하실 항목을 하나 이상 체크하세요.");
}


if ($_POST['act_button'] == "선택수정") {
	
	for ($i=0; $i<count($_POST['chk']); $i++)
	{
		$sql_common = "";

		// 실제 번호를 넘김
		$k = $_POST['chk'][$i];
		
		$sql = " update {$g5['offer_table']}
					set of_content = '{$_POST['of_content'][$k]}',
						of_sdate = '{$_POST['of_sdate'][$k]}',
						of_edate = '{$_POST['of_edate'][$k]}',
						of_change_time = '{$_POST['of_change_time'][$k]}',
						of_min_cost = '{$_POST['of_min_cost'][$k]}',
						of_max_cost = '{$_POST['of_max_cost'][$k]}',

						of_reset = '{$_POST['of_reset'][$k]}',
						of_cost = '{$_POST['of_cost'][$k]}',

						of_use = '{$_POST['of_use'][$k]}'
					";
		$sql .= " where of_id = '{$_POST['of_id'][$k]}' ";
		sql_query($sql);
	}
} else if ($_POST['act_button'] == "선택삭제") {

	$count = count($_POST['chk']);
	for ($i=0; $i<$count; $i++)
	{
		// 실제 번호를 넘김
		$k = $_POST['chk'][$i];

		// 소속 내역삭제
		$sql = " delete from {$g5['offer_table']} where of_id = '{$_POST['of_id'][$k]}' ";
		sql_query($sql);
	}
}

if ($msg)
	alert($msg);
goto_url('./offer_list.php?'.$qstr);
?>