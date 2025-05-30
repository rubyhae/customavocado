<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가 
$inven_list = array();
$p_count = 0;

// 개인 아이템 - 선물
$pe_inven_sql = "select * from {$g5['inventory_table']} inven, {$g5['item_table']} item where inven.ch_id = '$ch_id' and item.it_id = inven.it_id and inven.se_ch_id != '' order by inven.it_id asc";
$pe_inven_result = sql_query($pe_inven_sql);
for($i=0; $row=sql_fetch_array($pe_inven_result); $i++) {
    $inven_list[$p_count] = $row;
    $p_count++;
}

// 개인 아이템 - 비선물
$pe_inven_sql = "select *, count(*) as cnt from {$g5['inventory_table']} inven, {$g5['item_table']} item where inven.ch_id = '$ch_id' and item.it_id = inven.it_id and inven.se_ch_id = '' group by inven.it_id order by inven.it_id asc";
$pe_inven_result = sql_query($pe_inven_sql);
for($i; $row=sql_fetch_array($pe_inven_result); $i++) {
    $inven_list[$p_count] = $row;
    $p_count++;
}
$i = 0;

// 기존 스킨 파일 대신 직접 HTML 출력
?>

<style>
/* 최강 우선순위 CSS */
#inventory_area .inventory-list,
.tab-box .inventory-list,
.mypage-container .inventory-list,
.mypageInside .inventory-list,
body .inventory-list {
    display: flex !important;
    flex-wrap: wrap !important;
    gap: 5px !important;
    list-style: none !important;
    padding: 0 !important;
    margin: 0 !important;
    overflow: visible !important;
    position: relative !important;
}

#inventory_area .inventory-list li,
.tab-box .inventory-list li,
.mypage-container .inventory-list li,
.mypageInside .inventory-list li,
body .inventory-list li,
.inventory-list .box-line {
    width: 80px !important;
    height: 80px !important;
    min-width: 80px !important;
    min-height: 80px !important;
    max-width: 80px !important;
    max-height: 80px !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    border: 2px solid #3a4a5c !important;
    background: radial-gradient(circle, #2a3a4c 0%, #1a252f 100%) !important;
    border-radius: 50% !important;
    box-sizing: border-box !important;
    position: relative !important;
    overflow: hidden !important;
    float: none !important;
    padding: 0 !important;
    margin: 0 !important;
    flex: none !important;
}

.inventory-list a,
.inventory-list .inven-open-popup {
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    width: 100% !important;
    height: 100% !important;
    text-decoration: none !important;
    border: none !important;
    background: transparent !important;
}

.inventory-list img {
    max-width: 45px !important;
    max-height: 45px !important;
    width: auto !important;
    height: auto !important;
    object-fit: contain !important;
    filter: drop-shadow(0 0 3px rgba(0,0,0,0.5)) !important;
    margin: 0 !important;
    border: none !important;
}

.inventory-list .count,
.inventory-list i.count,
.box-line .count,
.box-line i.count {
    position: absolute !important;
    bottom: 10px !important;
    right: 10px !important;
    background: rgba(0, 0, 0, 0.8) !important;
    color: white !important;
    font-size: 10px !important;
    font-weight: bold !important;
    padding: 1px 3px !important;
    border-radius: 2px !important;
    z-index: 999 !important;
    font-style: normal !important;
    border: 1px solid rgba(255, 255, 255, 0.5) !important;
    min-width: 12px !important;
    text-align: center !important;
    line-height: 1 !important;
    display: block !important;
    text-shadow: 1px 1px 1px rgba(0,0,0,0.8) !important;
}

.inventory-list .present {
    position: absolute !important;
    top: 2px !important;
    left: 2px !important;
    width: 8px !important;
    height: 8px !important;
    background: #ff4444 !important;
    border-radius: 50% !important;
    z-index: 10 !important;
    font-style: normal !important;
    border: none !important;
}

.inventory-list .no-data {
    padding: 20px !important;
    text-align: center !important;
    color: #f0f5f3 !important;
    width: 100% !important;
    border: none !important;
    background: transparent !important;
    border-radius: 0 !important;
}
</style>

<ul class="inventory-list" style="display: flex !important; flex-wrap: wrap !important; gap: 5px !important; list-style: none !important; padding: 0 !important; margin: 0 !important;">
<?
for($i=0; $i < count($inven_list); $i++) { ?>
    <li class="box-line bak" style="width: 80px !important; height: 80px !important; min-width: 80px !important; min-height: 80px !important; max-width: 80px !important; max-height: 80px !important; display: flex !important; align-items: center !important; justify-content: center !important; border: 2px solid #3a4a5c !important; background: radial-gradient(circle, #2a3a4c 0%, #1a252f 100%) !important; border-radius: 50% !important; box-sizing: border-box !important; position: relative !important; overflow: hidden !important; float: none !important; padding: 0 !important; margin: 0 !important; flex: none !important;">
<? if($inven_list[$i]['in_id']){ ?>
       <a href="#<?=$inven_list[$i]['in_id']?>" class="inven-open-popup" data-idx="<?=$inven_list[$i]['in_id']?>" data-type="" style="display: flex !important; align-items: center !important; justify-content: center !important; width: 100% !important; height: 100% !important; text-decoration: none !important; border: none !important; background: transparent !important;">
          <img src="<?=$inven_list[$i]['it_img']?>" alt="아이템" style="max-width: 45px !important; max-height: 45px !important; width: auto !important; height: auto !important; object-fit: contain !important; filter: drop-shadow(0 0 3px rgba(0,0,0,0.5)) !important; margin: 0 !important; border: none !important;" />
       <? if($inven_list[$i]['cnt'] > 1) { ?>
          <i class="count" style="position: absolute !important; bottom: 10px !important; right: 10px !important; background: rgba(0, 0, 0, 0.8) !important; color: white !important; font-size: 10px !important; font-weight: bold !important; padding: 1px 3px !important; border-radius: 2px !important; z-index: 999 !important; font-style: normal !important; border: 1px solid rgba(255, 255, 255, 0.5) !important; min-width: 12px !important; text-align: center !important; line-height: 1 !important; display: block !important; text-shadow: 1px 1px 1px rgba(0,0,0,0.8) !important;"><?=$inven_list[$i]['cnt']?></i>
       <? } ?>
       <? if($inven_list[$i]['se_ch_id'] != '') { ?>
          <i class="present" style="position: absolute !important; top: 2px !important; left: 2px !important; width: 8px !important; height: 8px !important; background: #ff4444 !important; border-radius: 50% !important; z-index: 10 !important; font-style: normal !important; border: none !important;"></i>
       <? } ?>
       </a>
<? } ?>
    </li>
<? }

if($i == 0) {
?>
    <li class="no-data" style="padding: 20px !important; text-align: center !important; color: #f0f5f3 !important; width: 100% !important; border: none !important; background: transparent !important; border-radius: 0 !important;">
       보유중인 아이템이 없습니다.
    </li>
<? } ?>
</ul>

<script>
// 강제 적용 스크립트
document.addEventListener('DOMContentLoaded', function() {
    setTimeout(function() {
        const inventoryList = document.querySelector('#inventory_area .inventory-list');
        const inventoryItems = document.querySelectorAll('#inventory_area .inventory-list li');

        if (inventoryList) {
            inventoryList.style.setProperty('display', 'flex', 'important');
            inventoryList.style.setProperty('flex-wrap', 'wrap', 'important');
            inventoryList.style.setProperty('gap', '5px', 'important');
        }

        inventoryItems.forEach(item => {
            if (!item.classList.contains('no-data')) {
                item.style.setProperty('width', '70px', 'important');
                item.style.setProperty('height', '70px', 'important');
                item.style.setProperty('border-radius', '50%', 'important');
                item.style.setProperty('background', 'radial-gradient(circle, #2a3a4c 0%, #1a252f 100%)', 'important');
                item.style.setProperty('border', '2px solid #3a4a5c', 'important');
                item.style.setProperty('display', 'flex', 'important');
                item.style.setProperty('align-items', 'center', 'important');
                item.style.setProperty('justify-content', 'center', 'important');
                item.style.setProperty('float', 'none', 'important');
            }
        });
    }, 100);
});
</script>