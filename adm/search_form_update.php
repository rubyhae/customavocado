<?php
$sub_menu = "800200";
include_once('./_common.php');

if ($w == 'u') check_demo();
auth_check($auth[$sub_menu], 'w');
check_token();

$item_data_path = G5_DATA_PATH."/search";
$item_data_url = G5_DATA_URL."/search";

@mkdir($item_data_path, G5_DIR_PERMISSION);
@chmod($item_data_path, G5_DIR_PERMISSION);


$it_result = sql_fetch("select * from {$g5['item_table']} where it_name = '{$se_it_name}'");
$sql_common = "";

if($w == '') {
	$se_id = sql_insert_id();
} else {
	$se_id = trim($se_id);
}

$sql_common = " se_use			= '{$se_use}',
				se_it_name		= '{$se_it_name}',
				se_it_id		= '{$it_id}',
				se_region		= '{$se_region}',
				se_text		= '{$se_text}',
				se_stat 		= '{$se_stat}',
				se_num			= '{$se_num}',
				se_range			= '{$se_range}',
				se_reward		= '{$se_reward}'";


if($w == '') {
	$sql = " insert into {$g5['search_table']}
				set se_id = '{$se_id}', {$sql_common}";
	sql_query($sql);
} else {
	$it = sql_fetch("select se_id from {$g5['search_table']} where se_id = '{$se_id}'");

	if(!$it['se_id']) {
		alert("탐색 이벤트 정보가 존재하지 않습니다.");
	}
	$sql = " update {$g5['search_table']}
				set {$sql_common}
				where se_id = '{$it['se_id']}'";
	sql_query($sql);
}
goto_url('./search_list.php?'.$qstr, false);
?>
