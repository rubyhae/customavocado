<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

if($inven_function == "마이룸가구") {
	// 지정된 이미지를 가구로 추가합니다.
	// 상세 이미지를 사용합니다.
	$count = sql_fetch("select MAX(ro_id) as cnt from {$g5['room_table']} where ch_id = '{$character['ch_id']}'");
	$count = $count['cnt']++;
	sql_query("insert into {$g5['room_table']} set ro_img='{$in['it_1']}', ro_order='{$count}', ch_id = '{$character['ch_id']}'");
	delete_inventory($in['in_id'], $in['it_use_ever']);

	echo location_url($return_url);
}

if($inven_function == "마이룸커스텀가구") {
	// 자유로운 이미지를 추가합니다.
	// 정해진 사이즈를 지정합니다. (적용값 사용)
	include_once(G5_PATH.'/room/room.add.inc.php');
}

if($inven_function == "마이룸배경") {
	// 지정된 이미지를 마이룸 배경으로 추가합니다.
	// 상세 이미지를 사용합니다.
	sql_query("update {$g5['character_table']} set ch_room_bak = '{$in['it_1']}' where ch_id = '{$character['ch_id']}'");
	delete_inventory($in['in_id'], $in['it_use_ever']);

	echo location_url($return_url);

}

?>