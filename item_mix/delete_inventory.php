<?php
include_once('../common.php');

header('Content-Type: application/json');
$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['in_ids']) || !is_array($data['in_ids'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid input']);
    exit;
}

$in_ids = array_map('intval', $data['in_ids']);

foreach ($in_ids as $in_id) {
    delete_inventory($in_id);
}

echo json_encode(['success' => true]);
