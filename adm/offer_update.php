<?php
$sub_menu = "500150";
include_once('./_common.php');

auth_check($auth[$sub_menu], 'w');
check_token();

$sql_common = "";

$item = null;
if(!$it_id && $it_name) {
	$item = sql_fetch("select * from {$g5['item_table']} where it_name = '{$it_name}' limit 0, 1");
	$it_id = $item['it_id'];
} else {
	$item = get_item($it_id);
}
if(!$item['it_id']) { alert("아이템 정보가 없습니다."); }

$cost = rand($of_min_cost, $of_max_cost);

$sql = " insert into {$g5['offer_table']}
			set it_id = '{$item['it_id']}',
				it_name = '{$item['it_name']}',
				of_content = '{$of_content}',
				
				of_sdate = '{$of_sdate}',
				of_edate = '{$of_edate}',

				of_change_time = '{$of_change_time}',

				of_min_cost = '{$of_min_cost}',
				of_max_cost = '{$of_max_cost}',

				of_reset = '".date('Y-m-d')." 00:00:00',
				of_cost = '{$cost}',

				of_use = '{$of_use}'";

sql_query($sql);


goto_url('./offer_list.php?'.$qstr);
?>
