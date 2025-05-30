<?php
include_once('../common.php');
header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);
$characterId = $data['characterId'] ?? '';
$itemId = $data['itemId'] ?? '';

if (!$characterId || !$itemId) {
    echo json_encode(['success' => false, 'message' => '잘못된 요청']);
    exit;
}

$item = get_item($itemId);

if (!$item) {
    echo json_encode(['success' => false, 'message' => '아이템 없음']);
    exit;
}

// 인벤토리에 추가
insert_inventory($characterId, $item['it_id'], $item);

echo json_encode(['success' => true]);
?>