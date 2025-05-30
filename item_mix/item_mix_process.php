<?php
include_once('../common.php');

// 재료 정보 : make_1, make_2, make_3
$make_1 = get_inventory_item($make_1);
$make_2 = get_inventory_item($make_2);
$make_3 = get_inventory_item($make_3);

$re_item[0] = $make_1['it_id'];
$re_item[1] = $make_2['it_id'];
$re_item[2] = $make_3['it_id'];
sort($re_item);
$re_item_order = implode("||", $re_item);


// 레시피 검색
$re = sql_fetch("SELECT it_id FROM {$g5['item_table']}_recepi 
                 WHERE re_item_order = '{$re_item_order}' AND re_use = '1'");

if (!$re['it_id']) {
    // 레시피 조합 실패 (레시피 없음)
    $item_mix_log = $action . "||F||NON||NON||" . $re_item_order;
    echo $item_mix_log;
} else {
    // 레시피 있음 → 확률 계산 진행

    // 캐릭터 상태 테이블에서 sc_max 가져오기
    $status = sql_fetch("SELECT sc_max FROM {$g5['status_table']} 
                         WHERE ch_id = '{$character['ch_id']}' AND st_id = 2 
                         LIMIT 1");
    $sc_max = isset($status['sc_max']) ? (int) $status['sc_max'] : 0;

    // 성공 여부 판단
    $rand = rand(1, 100);

    if ($rand <= $sc_max) {
        // 성공
        $item = get_item($re['it_id']);
        insert_inventory($character['ch_id'], $item['it_id'], $item);
        $item_mix_log = $action . "||S||" . $re['it_id'] . "||" . $item['it_name'] . "||" . $in_id . "||" . $re_item_order . "||" . $rand;
        echo $item_mix_log;
    } else {
        // 확률 실패
        $item_mix_log = $action . "||F||FAIL||FAIL||" . $re_item_order . "||" . $rand;
        echo $item_mix_log;
    }

    /* 아이템 삭제
if (!$make_1['it_use_ever']) {
    delete_inventory($make_1['in_id']);
}
if (!$make_2['it_use_ever']) {
    delete_inventory($make_2['in_id']);
}
if (!$make_3['it_use_ever']) {
    delete_inventory($make_3['in_id']);
}
*/
}

$customer_sql .= " , wr_log = '{$item_mix_log}' ";