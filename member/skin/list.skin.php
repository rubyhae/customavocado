<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가 
add_stylesheet('<link rel="stylesheet" href="'.G5_CSS_URL.'/member.css">', 0);

// 모든 타이틀 정보를 미리 가져와서 배열로 저장
$title_array = array();
try {
    if(function_exists('sql_query')) {
        $title_sql = "SELECT ti_id, ti_title FROM avo_character_title";
        $title_result = sql_query($title_sql);
        if($title_result) {
            while($title_row = sql_fetch_array($title_result)) {
                $title_array[$title_row['ti_id']] = $title_row['ti_title'];
            }
        }
    }
} catch(Exception $e) {
    // 에러 발생시 빈 배열 유지
}

// 기숙사/소속 정보 가져오기 (정확한 컬럼명 사용)
$side_array = array();
$side_image_array = array();
try {
    if(function_exists('sql_query')) {
        $side_sql = "SELECT si_id, si_name, si_img FROM avo_character_side ORDER BY si_id";
        $side_result = sql_query($side_sql);
        if($side_result) {
            while($side_row = sql_fetch_array($side_result)) {
                $side_array[$side_row['si_id']] = $side_row['si_name'];
                $side_image_array[$side_row['si_id']] = $side_row['si_img'];
            }
        }
    }

    // mysqli 백업 방법
    if(empty($side_array) && isset($g5['connect_db'])) {
        $side_sql = "SELECT si_id, si_name, si_img FROM avo_character_side ORDER BY si_id";
        $side_result = mysqli_query($g5['connect_db'], $side_sql);
        if($side_result) {
            while($side_row = mysqli_fetch_array($side_result)) {
                $side_array[$side_row['si_id']] = $side_row['si_name'];
                $side_image_array[$side_row['si_id']] = $side_row['si_img'];
            }
        }
    }
} catch(Exception $e) {
    // 에러 발생시 기본 데이터 사용
    $side_array = array(
        '1' => '그리핀도르',
        '2' => '슬리데린',
        '3' => '허플푸프',
        '4' => '래번클로'
    );
}

// 모든 캐릭터를 하나의 배열로 합치기
$all_characters = array();
if(isset($list) && is_array($list)) {
    for ($i = 0; $i < count($list); $i++) {
        $ch_list = $list[$i];
        if(is_array($ch_list)) {
            for ($k = 0; $k < count($ch_list); $k++) {
                $all_characters[] = $ch_list[$k];
            }
        }
    }
}
?>

<!-- 기숙사 필터 버튼들 -->
<div class="dormitory-filter">
    <div class="dorm-btn all-btn active" data-dorm="all">
        <div class="dorm-logo">
            <img src="../../img/home.png" alt="전체">
        </div>
        <div class="star-1"></div>
        <div class="star-2"></div>
        <div class="star-3"></div>
        <div class="star-4"></div>
        <div class="star-5"></div>
        <div class="star-6"></div>
    </div>
    <? foreach($side_array as $side_id => $side_name) { ?>
        <div class="dorm-btn" data-dorm="<?=$side_id?>">
            <div class="dorm-logo">
                <?
                // 기숙사별 이미지 매핑 (올바른 상대 경로 사용)
                $side_images = array(
                    '1' => 'griffindor.png',
                    '2' => 'slytherin.png',
                    '3' => 'hufflepuff.png',
                    '4' => 'ravenclaw.png'
                );

                if(isset($side_images[$side_id])) {
                    $img_src = '../../img/' . $side_images[$side_id];
                    echo '<img src="' . $img_src . '" alt="' . $side_name . '">';
                } else {
                    echo '🏛️';
                }
                ?>
            </div>
            <div class="star-1"></div>
            <div class="star-2"></div>
            <div class="star-3"></div>
            <div class="star-4"></div>
            <div class="star-5"></div>
            <div class="star-6"></div>
        </div>
    <? } ?>
</div>

<!-- 멤버 카운트 -->
<div class="member-count">
    총 <span id="visible-count"><?=count($all_characters)?></span>명의 멤버
</div>

<div class="memberWrap">
    <ul class="member-list">
        <? if(count($all_characters) > 0) { ?>
            <? foreach($all_characters as $ch) { ?>
                <li data-dorm="<?=isset($ch['ch_side']) ? $ch['ch_side'] : '1'?>">
                    <div class="item theme-box">
                        <div class="ui-thumb">
                            <a href="./viewer.php?ch_id=<?=$ch['ch_id']?>">
                                <? if($ch['ch_thumb']) { ?>
                                    <img src="<?=$ch['ch_thumb']?>" alt="<?=$ch['ch_name']?>"/>
                                <? } else { ?>
                                    <img src="./img/no_profile.png" alt="<?=$ch['ch_name']?>" onerror="this.style.display='none';">
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

                        <!-- 기숙사 정보 표시 -->
                        <? if(isset($ch['ch_side']) && isset($side_array[$ch['ch_side']])) { ?>
                            <div class="member-house"><?=$side_array[$ch['ch_side']]?></div>
                        <? } ?>
                    </div>
                </li>
            <? } ?>
        <? } else { ?>
            <li class='no-data'>등록된 멤버가 없습니다.</li>
        <? } ?>
    </ul>
</div>

<script>
// jQuery 사용 확인
if (typeof jQuery !== 'undefined') {
    $('.send_memo').on('click', function() {
        var target = $(this).attr('href');
        window.open(target, 'memo', "width=500, height=300");
        return false;
    });
}

// 멤버 필터링 기능
document.addEventListener('DOMContentLoaded', function() {
    const dormButtons = document.querySelectorAll('.dorm-btn');
    const memberItems = document.querySelectorAll('.member-list li:not(.no-data)');
    const visibleCount = document.getElementById('visible-count');

    console.log('필터 버튼 개수:', dormButtons.length);
    console.log('멤버 개수:', memberItems.length);

    // 각 멤버의 기숙사 정보 로그
    memberItems.forEach(function(member, index) {
        console.log('멤버 ' + (index + 1) + ' 기숙사:', member.getAttribute('data-dorm'));
    });

    dormButtons.forEach(function(button) {
        button.addEventListener('click', function() {
            const selectedDorm = this.getAttribute('data-dorm');
            console.log('선택된 기숙사:', selectedDorm);

            dormButtons.forEach(function(btn) {
                btn.classList.remove('active');
            });
            this.classList.add('active');

            filterMembers(selectedDorm);
        });
    });

    function filterMembers(dormitory) {
        let visibleMembers = 0;

        memberItems.forEach(function(member) {
            const memberDorm = member.getAttribute('data-dorm');

            if (dormitory === 'all' || memberDorm === dormitory) {
                member.classList.remove('hidden');
                visibleMembers++;
            } else {
                member.classList.add('hidden');
            }
        });

        if(visibleCount) {
            visibleCount.textContent = visibleMembers;
        }

        console.log('표시된 멤버 수:', visibleMembers);
    }

    filterMembers('all');
});
</script>