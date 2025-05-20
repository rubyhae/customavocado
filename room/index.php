<?php
include_once('./_common.php');

$g5['title'] = "마이룸";

// ✅ `ch_id` 값 받아오기
$ch_id = isset($_GET['ch_id']) ? htmlspecialchars($_GET['ch_id'], ENT_QUOTES, 'UTF-8') : null;

// ✅ `ch_id` 값이 없으면 경고 후 종료
if (!$ch_id) {
    alert("캐릭터 ID(ch_id)가 전달되지 않았습니다.");
    exit;
}

include_once('./_head.php');
add_stylesheet('<link rel="stylesheet" href="'.G5_URL.'/room/css/index.css">', 0);

// ✅ 다른 파일에서도 `ch_id`를 사용할 수 있도록 설정
define('CHARACTER_ID', $ch_id);

// 해당 페이지는 룸이 전체 페이지로 작성될 때 사용되는 파일입니다. (헤더 / 푸터 포함)
include_once('./room.inc.php');

include_once('./_tail.php');
?>
