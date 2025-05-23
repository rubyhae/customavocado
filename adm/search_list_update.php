<?php
$sub_menu = "800200";
include_once('./_common.php');

check_demo();

if (!count($_POST['chk'])) {
	alert($_POST['act_button']." 하실 항목을 하나 이상 체크하세요.");
}

if ($_POST['act_button'] == "선택수정") {

	auth_check($auth[$sub_menu], 'w');

	for ($i=0; $i<count($_POST['chk']); $i++) {

		// 실제 번호를 넘김
		$k = $_POST['chk'][$i];

		$sql = " update {$g5['search_table']}
					set se_it_id            = '{$_POST['se_it_id'][$k]}',
						se_it_name             = '{$_POST['se_it_name'][$k]}',
						se_text         = '{$_POST['se_text'][$k]}',
						se_use         = '{$_POST['se_use'][$k]}',
						se_stat        = '{$_POST['se_stat'][$k]}',
						se_num     = '{$_POST['se_num'][$k]}',
						se_region		= '{$_POST['se_region'][$k]}',
						se_reward		= '{$_POST['se_reward'][$k]}'
				  where se_id               = '{$_POST['se_id'][$k]}' ";
		sql_query($sql);
	}

} else if ($_POST['act_button'] == "선택삭제") {
	auth_check($auth[$sub_menu], 'd');
	check_token();

	for ($i=0; $i<count($_POST['chk']); $i++) {
		$k = $_POST['chk'][$i];
		$temp_it_id = trim($_POST['se_id'][$k]);
		if (!$temp_it_id) { return; }

		sql_query(" delete from {$g5['search_table']} where se_id = '{$temp_it_id}'");
	}
}

goto_url('./search_list.php?'.$qstr."&se_id=".$se_id);
?>
