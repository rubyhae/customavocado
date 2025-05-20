<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가 
$tab_width = 1000; ?>
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

<!-- 모바일 모드에서 메뉴를 열고 닫기 할 수 있는 버튼 -->
<a href="#" class="mob_open" id="gnb_control_box" onclick="return false;">
		<i class="material-icons mob_menu">arrow_right_alt</i>
		</a>
		<script>
		$(".mob_open").click(function(){
    $(".mini-menu").toggleClass("mini-menu3");
	});

  </script>
		<!-- 모바일 메뉴 열고 닫기 버튼 종료 -->
		<div id="gnb_wrapper">
			<?
			if($config['cf_menu_content']){
			$menu_co=explode(",",$config['cf_menu_content']);
			$menu_content = get_site_content($menu_co[1]);
			echo '<div id="gnb">'.$menu_content.'</div>';
			}else{
		/**************************************************************
		----------------------------메뉴 영역 시작----------------------------
		* 원하실 경우 하단의 <div id="gnb"> ....  </div> 부분을 수정/삭제 해주세요.
		**************************************************************/?> 

<div class="mini-menu mini-menu3">
<a href="<?=G5_URL?>"><li class="mini-item"><i class="material-icons mini-icon">home</i><div class="menu-txt">HOME</div></li></a>
<div class=menu-line></div>
<?
	$sql = " select *
	from {$g5['menu_table']}
	where me_use = '1'
	and length(me_code) = '2'
	order by me_order*1, me_id ";
	
	$result = sql_query($sql, false); 
	$count=sql_fetch($sql);
	$menu_datas = array();
	
	if($count['me_id']){ 
		$sql = " select *
		from {$g5['menu_table']}
		where me_use = '1'
		and length(me_code) = '2'
		order by me_order*1, me_id ";
		$result = sql_query($sql, false); 
		$menu_datas = array();

		for ($i=0; $row=sql_fetch_array($result); $i++) {
			$menu_datas[$i] = $row;

			$sql2 = " select *
			from {$g5['menu_table']}
			where me_use = '1'
			  and length(me_code) = '4'
			  and substring(me_code, 1, 2) = '{$row['me_code']}'
			order by me_order*1, me_id ";
	
			$result2 = sql_query($sql2);
			for ($k=0; $row2=sql_fetch_array($result2); $k++) {
				$menu_datas[$i]['sub'][$k] = $row2;
			}

		}

		$i = 0;
		foreach( $menu_datas as $row ){
			if( empty($row) ) continue; 
			if($row['me_name'] == '구분선') {?>
				<div class=menu-line></div>
			<?}else {
				if($member['mb_level']>=$row['me_level']){?>
				<a href="<?php echo $row['me_link']?>">
				<li class="mini-item">
				<i class="material-icons mini-icon"><?=$row['me_img']?></i>
				<div class="menu-txt"><?=$row['me_name']?></div>
				</li>
				</a>
			<?}}
			$i++;
		}
	} 
?> 
<div class=menu-line></div>
<?if ($is_member){?>
<?if($is_admin){?>
	<a href="<?=G5_ADMIN_URL?>" target="_blank"><li class="mini-item"><i class="material-icons mini-icon">settings</i><div class="menu-txt">ADMIN</div></li></a><?}if($is_member && !$is_admin){?>
		<a href="<?php echo G5_BBS_URL ?>/member_confirm.php?url=register_form.php" id="ol_after_info"><li class="mini-item"><i class="material-icons mini-icon">construction</i><div class="menu-txt">MYPAGE</div></li></a><?}?>
		<a href="<?php echo G5_BBS_URL ?>/logout.php" id="ol_after_logout"><li class="mini-item"><i class="material-icons mini-icon"><span class="material-icons-outlined">power_settings_new</i><div class="menu-txt">LOGOUT</div></li></a><?}else{?>
			<a href="<?=G5_BBS_URL?>/login.php"><li class="mini-item"><i class="material-icons mini-icon"><span class="material-icons-outlined">power_settings_new</i><div class="menu-txt">LOGIN</div></li></a>
			<?if($config['cf_1']){?>
				<a href="<?php echo G5_BBS_URL ?>/register.php"><li class="mini-item"><i class="material-icons mini-icon">how_to_reg</i><div class="menu-txt">JOIN</div></li></a><?}?>
				<?}?>
				<? if($config['cf_bgm']){?>
				<li class="mini-item"><a href="<?=G5_URL?>/bgm.php?action=play" target="bgm_frame" class="play playy music_btn" onclick="return fn_control_bgm('play')">
			</a><div class="menu-txt onoff play2"></div></li>
			<li class="mini-item"><a href="<?=G5_URL?>/bgm.php" target="bgm_frame" class="stop music_btn" onclick="return fn_control_bgm('stop')">
			</a></li>
			<script type="text/javascript">
var bgm_effect = null;

function fn_control_bgm(state) {
	if(state == 'play') { 
		$('.play').addClass('playy');
		$('.onoff').addClass('play2');
		$('.onoff').removeClass('stop2');
	} else { 
		$('.play').removeClass('playy');
		$('.onoff').addClass('stop2');
		$('.onoff').removeClass('play2');
	}

	if($('html').hasClass('single')) { 
		return false;
	} else {
		return true;
	}
}
bgm_effect = setInterval(set_equalizer, 300);
</script>
<? } ?>
</div>
			
			<? /**************************************************************
			----------------------------메뉴 영역 끝----------------------------
			**************************************************************/ }?> 
		</div>



<? /* 아래는 스타일시트 */ 

/** CSS 설정 가져오기 **/
$css_sql = sql_query("select * from {$g5['css_table']}");
$css = array();
for($i=0; $cs = sql_fetch_array($css_sql); $i++) {
	$css[$cs['cs_name']][0] = $cs['cs_value'];
	$css[$cs['cs_name']][1] = $cs['cs_etc_1'];
	$css[$cs['cs_name']][2] = $cs['cs_etc_2'];
	$css[$cs['cs_name']][3] = $cs['cs_etc_3'];
	$css[$cs['cs_name']][4] = $cs['cs_etc_4'];
	$css[$cs['cs_name']][5] = $cs['cs_etc_5'];
	$css[$cs['cs_name']][6] = $cs['cs_etc_6'];
	$css[$cs['cs_name']][7] = $cs['cs_etc_7'];
	$css[$cs['cs_name']][8] = $cs['cs_etc_8'];
	$css[$cs['cs_name']][9] = $cs['cs_etc_9'];
	$css[$cs['cs_name']][10] = $cs['cs_etc_10'];
}
?>
<style>

:root {
  --menu-icon:<?=$css['menu_text'][0]?>;
  --menu-text:<?=$css['menu_text'][0]?>;
  --menu-point:<?=$css['menu_background'][1]?>;
  --menu-point-trans:<?=$css['m_menu_background'][1]?>;
  --menu-shadow:<?=$css['menu_text'][1]?>52;
  --menu-background:linear-gradient(to bottom, <?=$css['menu_background'][1]?>, <?=$css['m_menu_background'][1]?>);
}

<? /* 모바일 반응형---- */ ?>
@media all and (min-width: <?=($tab_width + 1)?>px) { 
  #header			{
    padding:0 !important;
    position:fixed !important;
    z-index:2 !important;
  }
}

@media all and (max-width: <?=$tab_width?>px) {
  .mini-menu3{
		margin-left:-20% !important;
	}
}
@media all and (max-width: 1000px) {
#gnb_control_box {
    left: 10px !important;
	top: 10px;
	position: fixed;
  }
}
<? /* ----모바일 반응형 끝 */ ?>

.mob_menu {
  color:var(--menu-icon);
  background: var(--menu-point);
  border-radius: 100%;
}

.menu-line {
  position: relative;
  height:0px;
  border-top:1px dashed rgba(255,255,255,0.4);
  width:auto;
  margin:2px 0px;
  background-color:rgba(255,255,255,0.4);
}
.mini-menu {
  transition: None;
  width: 100px;
  height:fit-content;
  border-radius: 12px;
  padding: 8px 0px;
  background: transparent;
  position:fixed;
  left:2%;
  margin-left:0%;
  top: 50%;
  transform: translateY(-50%);
  text-align: left; 
  filter:drop-shadow(0px 1px 3px var(--menu-shadow));
  overflow: hidden;
  z-index: 15;
}

.mini-item {
  display:block;
  border-radius: 10px;
}
.mini-item:hover {
  filter:drop-shadow(0 0 2px white);
  background:var(--menu-point-trans);
}

.play2 {
}

.stop2 {

}
.onoff {

}


.mini-icon {
  color: var(--menu-icon);
  margin: 3px 5px;
}

.mini-icon2 {
  display: none; 
}

.menu-txt {
  position:absolute;
  color: var(--menu-text);
  display: inline-block;
  word-break:keep-all;
  transform: translateX(2px) translateY(6px);
  font-size: 11px;
  font-weight: normal;
  -webkit-font-smoothing: antialiased;
}


<?/* 뮤직 플레이어 */?>

.playy {
animation-name: playying;
animation-duration: 0.4s;
animation-direction: alternate;
animation-iteration-count: infinite;
}

.music_btn {
display: inline-block;
vertical-align:middle;
position: relative;
width: 18px;
height: 18px;
text-indent: -999px;
overflow: hidden;
background: var(--menu-icon);
transform: rotate(45deg);
margin: 5px 8px;
}

.music_btn:hover {
background-color: var(--menu-text);
}
.music_btn:before {
content: "";
color: var(--menu-point);
display: block;
position: relative;
text-indent: 0;
font-weight:normal;
text-align: center;
font-size: 9px;
margin:auto;
transform: rotate(-45deg);
}

.play:before { content: "ON"; }
.stop:before { content: "OFF"; }

@keyframes playying {
from {
  filter:drop-shadow(0px 0px 0px rgba(255,255,255,1));
}
 
to {
filter:drop-shadow(0px 0px 5px rgba(255,255,255,1));
}
  }
<?/* 뮤직 플레이어 끝 */?>
</style>