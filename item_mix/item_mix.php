<?php
if (!defined('_GNUBOARD_'))
    exit; // 개별 페이지 접근 불가
add_stylesheet('<link rel="stylesheet" href="./item_mix.css">', 0);


$it = array();
$customer_sql = "";
?>

<div class="comment-data" id="action_H">
    <?
    // 조합 커멘드 관련 입력
    ?>
    <dl>
        <dt>ITEM 1</dt>
        <dd>
            <select name="make_1" id="make_1" class="make-imtem">
                <option value="">재료 선택</option>
                <?
                $re_result = sql_query("select * from {$g5['inventory_table']} inven, {$g5['item_table']} it where inven.ch_id = '{$character['ch_id']}' and it.it_use_recepi = 1 and inven.it_id = it.it_id");
                for ($i = 0; $re_row = sql_fetch_array($re_result); $i++) {
                    ?>
                    <option value="<?= $re_row['in_id'] ?>">
                        <?= $re_row['it_name'] ?>
                    </option>
                <?
                } ?>
            </select>
        </dd>
    </dl>
    <dl>
        <dt>ITEM 2</dt>
        <dd>
            <select name="make_2" id="make_2" class="make-imtem">
                <option value="">재료 선택</option>
                <?
                $re_result = sql_query("select * from {$g5['inventory_table']} inven, {$g5['item_table']} it where inven.ch_id = '{$character['ch_id']}' and it.it_use_recepi = 1 and inven.it_id = it.it_id");
                for ($i = 0; $re_row = sql_fetch_array($re_result); $i++) {
                    ?>
                    <option value="<?= $re_row['in_id'] ?>">
                        <?= $re_row['it_name'] ?>
                    </option>
                <?
                } ?>
            </select>
        </dd>
    </dl>
    <dl>
        <dt>ITEM 3</dt>
        <dd>
            <select name="make_3" id="make_3" class="make-imtem">
                <option value="">재료 선택</option>
                <?
                $re_result = sql_query("select * from {$g5['inventory_table']} inven, {$g5['item_table']} it where inven.ch_id = '{$character['ch_id']}' and it.it_use_recepi = 1 and inven.it_id = it.it_id");
                for ($i = 0; $re_row = sql_fetch_array($re_result); $i++) {
                    ?>
                    <option value="<?= $re_row['in_id'] ?>">
                        <?= $re_row['it_name'] ?>
                    </option>
                <?
                } ?>
            </select>
        </dd>
    </dl>

    <!-- 조합 버튼 -->
    <div style="margin-top: 10px;">
        <button type="button" onclick="combineItems()">조합</button>
    </div>

    <!-- 출력 결과 -->
    <div id="result" style="margin-top: 10px; font-weight: bold;"></div>
</div>

<!-- 조합 함수 -->
<script>
    function combineItems() {
        include_once('./item_mix_process.php');
    }
</script>