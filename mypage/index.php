<?php
include_once('./_common.php');
include_once('./_head.php');
?>

<style>
/* 마이페이지 전용 추가 스타일 */
.mypage-container {
    max-width: 900px;
    margin: 0 auto;
    padding: 20px;
}

.page-title {
    margin-bottom: 30px;
    padding-bottom: 15px;
    border-bottom: 2px solid rgba(255, 255, 255, 0.3);
}

.page-title strong {
    display: block;
    font-size: 24px;
    font-weight: bold;
    color: #f0f5f3;
    margin-bottom: 5px;
}

.page-title span {
    font-size: 14px;
    color: rgba(240, 245, 243, 0.7);
    font-style: italic;
}

.theme-form,
.theme-list {
    width: 100%;
    border-collapse: collapse;
    background: rgba(255, 255, 255, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: 8px;
    overflow: hidden;
    margin-bottom: 20px;
}

.theme-form th,
.theme-list th {
    background: rgba(255, 255, 255, 0.15);
    color: #f0f5f3;
    padding: 12px 15px;
    font-weight: bold;
    text-align: left;
    border-bottom: 1px solid rgba(255, 255, 255, 0.2);
}

.theme-form td,
.theme-list td {
    background: rgba(255, 255, 255, 0.05);
    color: #f0f5f3;
    padding: 12px 15px;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
}

.theme-list tr.check {
    background: rgba(255, 200, 100, 0.1);
}

.theme-list tr.check td {
    background: rgba(255, 200, 100, 0.15);
}

.no-data {
    text-align: center;
    color: rgba(240, 245, 243, 0.6);
    font-style: italic;
    padding: 30px !important;
}

.txt-center {
    text-align: center;
    margin: 20px 0;
}

.ui-btn {
    background: #ABB5C9;
    color: #fff;
    border: 1px solid #9AA4B8;
    padding: 10px 20px;
    border-radius: 6px;
    text-decoration: none;
    display: inline-block;
    transition: all 0.3s ease;
    font-weight: bold;
}

.ui-btn:hover {
    background: #9AA4B8;
    transform: translateY(-1px);
}

.ui-btn.etc {
    background: #B43C3C;
    border-color: #A33333;
}

.ui-btn.etc:hover {
    background: #A33333;
}

section {
    margin-bottom: 40px;
}

/* 페이징 스타일 */
.pagination {
    text-align: center;
    margin: 20px 0;
}

.pagination a {
    color: #ABB5C9;
    padding: 8px 12px;
    margin: 0 2px;
    text-decoration: none;
    border-radius: 4px;
    transition: all 0.3s ease;
}

.pagination a:hover {
    background: rgba(171, 181, 201, 0.2);
    color: #fff;
}

.pagination .current {
    background: #ABB5C9;
    color: #fff;
    padding: 8px 12px;
    margin: 0 2px;
    border-radius: 4px;
}

hr.padding {
    border: none;
    height: 1px;
    background: rgba(255, 255, 255, 0.2);
    margin: 30px 0;
}
</style>

<div class="mypage-container">
    <h2 class="page-title">
        <strong>계정정보</strong>
        <span>My Information</span>
    </h2>

    <section>
        <table class="theme-form">
           <colgroup>
              <col style="width: 110px;" />
              <col />
           </colgroup>
           <tbody>
              <tr>
                 <th>오너</th>
                 <td>
                    <?=$member['mb_nick']?> <? if($member['mb_birth']) { ?>( <?=@number_format($member['mb_birth'])?> 년생 )<? } ?>
                 </td>
              </tr>
              <tr>
                 <th>E-mail</th>
                 <td>
                    <?=$member['mb_email']?>
                 </td>
              </tr>
              <tr>
                 <th>가입일</th>
                 <td>
                    <?=$member['mb_open_date']?>
                 </td>
              </tr>
    <? if($member['mb_error_cnt'] > 0) { ?>
              <tr>
                 <th>경고내역</th>
                 <td>
                    <p>
                       [ <span style='color: #ff6b6b; font-weight: bold;'><?=number_format($member['mb_error_cnt'])?></span> ] 회
                    </p>
                    <?=$member['mb_error_content']?>
                 </td>
              </tr>
    <? } ?>
           </tbody>
        </table>

        <div class="txt-center">
           <a href="<?=G5_BBS_URL?>/member_confirm.php?url=register_form.php" class="ui-btn">정보수정</a>
        </div>
    </section>

    <h2 class="page-title">
        <strong>호출내역</strong>
        <span>My Calling</span>
    </h2>

    <section>
        <table class="theme-list">
           <colgroup>
              <col style="width: 110px;" />
              <col />
           </colgroup>
           <thead>
              <tr>
                 <th>호출</th>
                 <th>내용</th>
              </tr>
           </thead>
           <tbody>
    <?
        $sql = " update {$g5['member_table']}
                 set mb_board_call = '',
                    mb_board_link = ''
              where mb_id = '".$member['mb_id']."' ";
        sql_query($sql);

        if(!$page) $page= 1;

        // 알람 내역을 가져온다
        $row = sql_fetch("select count(*) as cnt from {$g5['call_table']} where re_mb_id = '{$member['mb_id']}'");
        $total_count = $row['cnt'];
        $page_rows = 10;

        $total_page  = ceil($total_count / $page_rows);  // 전체 페이지 계산
        $from_record = ($page - 1) * $page_rows; // 시작 열을 구함

        $sql = " select * from {$g5['call_table']} where re_mb_id = '{$member['mb_id']}' order by bc_datetime desc limit {$from_record}, $page_rows ";
        $result = sql_query($sql);

        for($i = 0; $row = sql_fetch_array($result); $i++) {
    ?>
              <tr <?=!$row['bc_check'] ? "class='check'":""?>>
                 <td><?=$row['mb_name']?></td>
                 <td>
                    <p style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                       <a href="<?=G5_BBS_URL?>/board.php?bo_table=<?=$row['bo_table']?>&amp;log=<?=$row['wr_num'] * -1?>" style="color: #95c070; text-decoration: none;">
                           <?=$row['memo']?>
                       </a>
                    </p>
                 </td>
              </tr>
    <? }
        if($i == 0) {
    ?>
              <tr>
                 <td colspan="2" class="no-data">
                    호출 내역이 존재하지 않습니다.
                 </td>
              </tr>
    <? } ?>
           </tbody>
        </table>
    </section>

    <?
    $write_pages = get_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, './index.php?page=');
    if($write_pages) {
        echo '<div class="pagination">' . $write_pages . '</div>';
    }
    ?>

    <hr class="padding" />

    <div class="txt-center">
        <a href="<?php echo G5_BBS_URL; ?>/member_confirm.php?url=member_leave.php" class="ui-btn etc">탈퇴</a>
    </div>
</div>

<?php
include_once('./_tail.php');
?>