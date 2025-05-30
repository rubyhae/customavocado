<?php
include_once('../common.php');

$re_item_order = isset($_POST['re_item_order']) ? trim($_POST['re_item_order']) : '';

if (!$re_item_order) {
    echo json_encode(['error' => 'No input']);
    exit;
}

// 레시피 테이블에서 해당 it_id 조합이 존재하는지 확인
$re = sql_fetch("SELECT it_id FROM {$g5['item_table']}_recepi  
                 WHERE re_item_order = '{$re_item_order}' AND re_use = '1'");

if ($re && $re['it_id']) {
    echo json_encode([
        'found' => true,
        'it_id' => $re['it_id']
    ]);
} else {
    echo json_encode(['found' => false]);
}
?>