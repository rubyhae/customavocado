<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가
add_stylesheet('<link rel="stylesheet" href="'.G5_CSS_URL.'/member.css">', 0);

?>

<style>
/* 캐릭터 프로필 추가 스타일 (head.php 스타일과 조화) */
#character_profile {
    /* head.php에서 기본 스타일 적용됨 */
}

/* 추가 세부 조정만 */
.pro_name, .pro_enname, .pro_side, .pro_basic, .pro_room {
    text-align: center;
    margin: 15px 0;
}

.pro_subtitle {
    color: #d4af37;
    font-weight: bold;
    margin: 20px 0 10px 0;
    padding-bottom: 5px;
    border-bottom: 1px solid rgba(212, 175, 55, 0.3);
}

.theme-box {
    background: rgba(255, 255, 255, 0.05);
    border-radius: 8px;
    padding: 15px;
    margin: 10px 0;
}
</style>

<div id="character_profile">

	<nav id="profile_menu" >
	<? if($article['ad_use_closet'] && $article['ad_use_body']) { ?>
		<a href="<?=G5_URL?>/member/closet.php?ch_id=<?=$ch['ch_id']?>" onclick="window.open(this.href, 'big_viewer', 'width=800 height=800 menubar=no status=no toolbar=no location=no scrollbars=yes resizable=yes'); return false;" class="ui-btn ico point camera circle big">
				옷장보기
		</a>
	<? } ?>
	<? if($article['ad_use_exp']) { ?>
		<a href="<?=G5_URL?>/member/exp.php?ch_id=<?=$ch['ch_id']?>" onclick="popup_window(this.href, 'exp', 'width=400, height=500'); return false;" class="ui-btn ico point exp circle big">
				경험치 내역 보기
		</a>
	<? } ?>
	</nav>

<!-- 한마디 생략
<div class="pro_say">
"<?=$ch['say']?>"
</div>-->

<hr class="padding" />
<hr class="padding" />

<!-- 캐릭터 비쥬얼 (이미지) 출력 영역 -->
	<div class="visual-area">
		<? if($article['ad_use_body'] && $ch['ch_body']) { ?>
			<div id="character_body">
				<img src="<?=$ch['ch_body']?>" alt="캐릭터 전신" />
			</div>
		<? } ?>
		<? if($article['ad_use_head'] && $ch['ch_head']) { ?>
			<div id="character_head">
				<img src="<?=$ch['ch_head']?>" alt="캐릭터 흉상" />
			</div>
		<? } ?>
	</div>
<!-- //캐릭터 비쥬얼 (이미지) 출력 영역 -->


<!-- (생략 후 밑에 따로 선언) 캐릭터 기본정보 출력 영역
	<table class="theme-form">
		<colgroup>
			<col style="width: 110px;">
			<col>
		</colgroup>
		<tbody>

		<? if($article['ad_use_name']) { ?>
			<tr>
				<th scope="row"><?=$article['ad_text_name']?></th>
				<td>
					<?php echo $ch['ch_name'] ?>
				</td>
			</tr>
		<? } ?>
		<? if($config['cf_side_title']) {
			// 소속 정보 출력
		?>
			<tr>
				<th><?=$config['cf_side_title']?></th>
				<td>
					<?=get_side_name($ch['ch_side'])?>
				</td>
			</tr>
		<? } ?>
		<? if($config['cf_class_title']) {
			// 종족 정보 출력
		?>
			<tr>
				<th><?=$config['cf_class_title']?></th>
				<td>
					<?=get_class_name($ch['ch_class'])?>
				</td>
			</tr>
		<? } ?>
		<? if($article['ad_use_rank']) {
			// 랭킹정보 출력
		?>
			<tr>
				<th scope="row"><?=$config['cf_rank_name']?></th>
				<td>
					<?php echo get_rank_name($ch['ch_rank']); ?>
				</td>
			</tr>
		<? } ?>
		<? if($article['ad_use_exp']) {
			// 경험치 정보 출력
		?>
			<tr>
				<th scope="row"><?=$config['cf_exp_name']?></th>
				<td>
					<?=$ch['ch_exp']?>
					<?=$config['cf_exp_pice']?>
				</td>
			</tr>
		<? } ?>


		<? for($i=0; $i < count($ch_ar); $i++) {
			// 추가 프로필 항목 출력
			$ar = $ch_ar[$i];
			$key = $ar['ar_code'];
		?>
			<tr>
				<th>
					<?=$ar['ar_name']?>
				</th>
				<?
					if($ar['ar_type'] == 'file' || $ar['ar_type'] == 'url') {
				?>
					<td>
						<img src="<?=$ch[$key]?>" />
					</td>
				<? } else { ?>
					<td>
					<?
						if($ar['ar_type'] == 'textarea')
							echo nl2br($ch[$key]);
						else
							echo $ch[$key];

						if($ar['ar_type'] != 'textarea' && $ar['ar_type'] != 'select')
							echo $ar['ar_text'];
					?>
					</td>
				<? } ?>
			</tr>
			<? } ?> -
		</tbody>
	</table>
		캐릭터 기본정보 출력 영역 -->

<hr class="padding" />
<hr class="padding" />
<hr class="padding" />

<!-- 상단 장식-->
<div class="txt-center"><img src="<?= G5_URL ?>/img/border1.png"></div>
<hr class="padding" />
<hr class="padding" />

<!-- 영문이름-->
<div class="pro_enname"><?=$ch['en_name']?></div>

<!-- 이름-->
<div class="pro_name">
<div class="universe">븿</div> <?=$ch['ch_name']?> <div class="universe">븿</div>
</div>

<hr class="padding" />

<!-- 소속-->
<div class="pro_side">
<?=get_side_name($ch['ch_side'])?></div>
</div>

<!-- 기본정보:나이, 키 -->
<div class="pro_basic">
<?=$ch['birth']?> | 나이 <?=$ch['age']?>세 | 신장 <?=$ch['cm']?>cm | 체중 <?=$ch['kg']?>kg | 성별 <?=$ch['gender']?>
</div>

<!-- 마이룸 -->
<hr class="padding" />
<div class="pro_room">
<a href="<?=G5_URL?>/room/index.php?ch_id=<?=$ch_id?>">마이룸</a>
</div>

<!-- 하단 장식-->
<hr class="padding" />
<hr class="padding" />
<div class="txt-center"><img src="<?= G5_URL ?>/img/border2.png"></div>

<? if($article['ad_use_status']) { // 스탯 설정 ?>
	<div class="pro_subtitle">
			스탯
		<span style="float:right;">
			<em class="txt-point" data-type="point_space"><?=get_space_status($ch['ch_id'])?></em> / <?=$ch['ch_point']?>
		</span>
	</div>
	<div class="theme-box">
		<div class="status-bar">
			<? for($i = 0; $i < count($status); $i++) {

				$status[$i]['has']	= $status[$i]['has'] ? $status[$i]['has'] : $status[$i]['min'];

				$status_percent = $status[$i]['max'] ? $status[$i]['has'] / $status[$i]['max'] * 100 : 0;
				$mine_percent = $status[$i]['max'] ? $status[$i]['now'] / $status[$i]['max'] * 100 : 0;

				$resent_use_point += $status[$i]['has'];

				$sub_text = "";
				if($status[$i]['drop']) $sub_text = "(".$status[$i]['now'].")";
			?>
				<dl>
					<dt><?=$status[$i]['name']?></dt>
					<dd>
						<p>
							<i><?=$status[$i]['has']?><?=$sub_text?></i>
							<span style="width: <?=$status_percent?>%;"></span>
							<sup style="width: <?=$mine_percent?>%;"></sup>
						</p>
					</dd>
				</dl>
			<? } ?>
		</div>
	</div>
<? } ?>

<!-- 외형 -->
	<div class="pro_app">
		<div class="pro_subtitle">외형</div>
		<div class="theme-box">
		<?=$ch['app']?></div>
	</div>

<!-- 성격 -->
	<div class="pro_cha">
		<div class="pro_subtitle">성격</div>
		<div class="theme-box">
		<?=$ch['cha']?></div>
	</div>

<!-- 기타 -->
	<div class="pro_etc">
		<div class="pro_subtitle">기타</div>
		<div class="theme-box">
		<?=$ch['etc']?></div>
	</div>


<? if($article['ad_use_title']) { // 타이틀 설정 ?>
	<hr class="padding" />
	<div class="pro_subtitle">
		TITLE
	</div>
	<div class="theme-box">
		<div class="title-list">
			<? for($i=0; $i < count($title); $i++) { ?>
				<img src="<?=$title[$i]['ti_img']?>" />
			<? }
				if($i == 0) {
					echo "<div class='no-data'>보유중인 타이틀이 없습니다.</div>";
				}
			?>
		</div>
	</div>
<? } ?>

<? if($article['ad_use_inven']) { // 인벤토리 출력 ?>
		<hr class="padding" />
		<div class="pro_subtitle">
			INVENTORY
		<? if($article['ad_use_money']) { // 소지금 사용시 현재 보유 중인 소지금 출력 ?>
			<span style="float:right;">
				<em class="txt-point"><?=$mb['mb_point']?></em><?=$config['cf_money_pice']?>
			</span>
		<? } ?>
		</div>
		<div class="theme-box">
			<? include(G5_PATH."/inventory/list.inc.php"); ?>
		</div>
	<? } ?>



<? if($ch['ch_state'] == '승인') { // 관계란 출력, 승인된 캐릭터만 출력됩니다. ?>
		<hr class="padding" />
		<div class="pro_subtitle">관계</div>
		<div class="relation-box">
			<ul class="relation-member-list">
				<?
					for($i=0; $i < count($relation); $i++) {
						$re_ch = get_character($relation[$i]['re_ch_id']);
						if($relation[$i]['rm_memo'] == '') { continue; }
				?>
					<li>
						<div class="ui-thumb">
							<a href="<?=G5_URL?>/member/viewer.php?ch_id=<?=$re_ch['ch_id']?>" target="_blank">
								<img src="<?=$re_ch['ch_thumb']?>" />
							</a>
						</div>
						<div class="info">
							<div class="rm-name">
								<?=$re_ch['ch_name']?>
							</div>
							<div class="rm-like-style">
								<p>
									<? for($j=0; $j < 5; $j++) {
										$class="";
										$style = "";
										if($j < $relation[$i]['rm_like']) {
											$class="txt-point";
										} else {
											$style="opacity: 0.2;";
										}

										echo "<i class='{$class}' style='{$style}'></i>";
									} ?>
								</p>
							</div>
						</div>
						<div class="memo  theme-box">
							<?=nl2br($relation[$i]['rm_memo'])?>
						</div>

						<ol>
							<?
								$relation[$i]['rm_link'] = nl2br($relation[$i]['rm_link']);
								$link_list = explode('<br />', $relation[$i]['rm_link']);
								for($j=0; $j < count($link_list); $j++) {
									$r_row = $link_list[$j];
									if(!$r_row) continue;
							?>
								<li>
									<a href="<?=$r_row?>" class="btn-log" target="_blank"></a>
								</li>
							<? } ?>
						</ol>
					</li>
				<? }?>
			</ul>
		</div>
	<? } ?>

	<div class="ui-btn point small full">
		오너 : <?=$mb['mb_name']?>
	</div>

	<hr class="padding" />
	<hr class="padding" />

</div>