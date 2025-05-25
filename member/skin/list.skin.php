<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가 
add_stylesheet('<link rel="stylesheet" href="'.G5_CSS_URL.'/member.css">', 0);

// 모든 타이틀 정보를 미리 가져와서 배열로 저장
$title_array = array();

// 여러 방법으로 타이틀 데이터 가져오기 시도
try {
    // 방법 1: sql_query 함수 사용 (올바른 테이블명과 컬럼명)
    if(function_exists('sql_query')) {
        $title_sql = "SELECT ti_id, ti_title FROM avo_character_title";
        $title_result = sql_query($title_sql);
        if($title_result) {
            while($title_row = sql_fetch_array($title_result)) {
                $title_array[$title_row['ti_id']] = $title_row['ti_title'];
            }
        }
    }

    // 방법 2: mysqli 직접 사용
    if(empty($title_array) && isset($g5['connect_db'])) {
        $title_sql = "SELECT ti_id, ti_title FROM avo_character_title";
        $title_result = mysqli_query($g5['connect_db'], $title_sql);
        if($title_result) {
            while($title_row = mysqli_fetch_array($title_result)) {
                $title_array[$title_row['ti_id']] = $title_row['ti_title'];
            }
        }
    }

    // 방법 3: PDO 시도 (만약 사용중이라면)
    if(empty($title_array) && class_exists('PDO')) {
        // PDO 연결 정보는 환경에 따라 다를 수 있음
    }
} catch(Exception $e) {
    // 에러 발생시 빈 배열 유지
}

// 모든 캐릭터를 하나의 배열로 합치기
$all_characters = array();
for ($i = 0; $i < count($list); $i++) {
    $ch_list = $list[$i];
    for ($k = 0; $k < count($ch_list); $k++) {
        $all_characters[] = $ch_list[$k];
    }
}
?>

<div class="memberWrap">
    <ul class="member-list">
        <? if(count($all_characters) > 0) { ?>
            <? foreach($all_characters as $ch) { ?>
                <li>
                    <div class="item theme-box">
                        <div class="ui-thumb">
                            <a href="./viewer.php?ch_id=<?=$ch['ch_id']?>">
                                <? if($ch['ch_thumb']) { ?>
                                    <img src="<?=$ch['ch_thumb']?>" alt="<?=$ch['ch_name']?>"/>
                                <? } ?>
                            </a>
                        </div>

                        <!-- 캐릭터 이름 위에 타이틀 표시 -->
                        <?
                        if(isset($ch['ch_title']) && !empty($ch['ch_title']) && isset($title_array[$ch['ch_title']])) {
                            echo '<div class="member-title">'.$title_array[$ch['ch_title']].'</div>';
                        }
                        ?>

                        <div class="ui-profile">
                            <a href="<?=G5_BBS_URL?>/memo_form.php?me_recv_mb_id=<?=$ch['mb_id']?>" class='send_memo'>
                                <strong><?=$ch['ch_name']?></strong>
                            </a>
                        </div>
                    </div>
                </li>
            <? } ?>
        <? } else { ?>
            <li class='no-data'>등록된 멤버가 없습니다.</li>
        <? } ?>
    </ul>
</div>

<script>
$('.send_memo').on('click', function() {
    var target = $(this).attr('href');
    window.open(target, 'memo', "width=500, height=300");
    return false;
});
</script>