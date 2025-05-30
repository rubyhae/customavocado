<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가 

add_stylesheet('<link rel="stylesheet" href="'.G5_CSS_URL.'/style.profile.css">', 0);
add_stylesheet('<link rel="stylesheet" href="'.G5_CSS_URL.'/style.closet.css">', 0);

/** 스탯 이용 시 스탯 설정값 가져오기 **/
if($article['ad_use_status']) { 
	$status = array();
	$st_result = sql_query("select * from {$g5['status_config_table']} order by st_order asc");
	for($i = 0; $row = sql_fetch_array($st_result); $i++) {
		$status[$i] = $row;
	}
}
/** 추가 항목 설정값 가져오기 **/
$ch_ar = array();
$str_secret = ' where (1) ';

if($member['mb_id'] == $mb['mb_id']) {
	$str_secret .= " and ar_secret != 'H' ";
} else {
	$str_secret .= " and ar_secret = '' ";
}

$ar_result = sql_query("select * from {$g5['article_table']} {$str_secret} order by ar_order asc");
for($i = 0; $row = sql_fetch_array($ar_result); $i++) {
	$ch_ar[$i] = $row;
}


// --- 캐릭터 별 추가 항목 값 가져오기
$av_result = sql_query("select * from {$g5['value_table']} where ch_id = '{$ch['ch_id']}'");
for($i = 0; $row = sql_fetch_array($av_result); $i++) {
	$ch[$row['ar_code']] = $row['av_value'];
}

// ------- 캐릭터 의상 정보 가져오기
$temp_cl = sql_fetch("select * from {$g5['closthes_table']} where ch_id = '{$ch_id}' and cl_use = '1'");
if($temp_cl['cl_path']) { 
	$ch['ch_body'] = $temp_cl['cl_path'];
}

?>
<style>
.fix-layout{ max-width:none !important;}
#menu, #log_in_wrap{
	display:none;
	}
html {
	overflow-y: hidden;
	}
</style>
<link rel="stylesheet" href="/css/swiper.min.css">
<script src="<?php echo G5_JS_URL ?>/swiper.min.js"></script>
<script>

$(window).load(function(){
 	$('#loading').fadeOut(300);
    });


	function wrapWindowByMask(){
		//화면의 높이와 너비를 구한다.
		var maskHeight = $(document).height();  
		var maskWidth = $(window).width();  

		//마스크의 높이와 너비를 화면 것으로 만들어 전체 화면을 채운다.
		$('#mask').css({'width':maskWidth,'height':maskHeight});  

		//애니메이션 효과 - 일단 1초동안 까맣게 됐다가 80% 불투명도로 간다.  
		$('#mask').fadeIn(300);    
		$('.window').fadeIn(300);
	}

	$(document).ready(function(){
		//검은 막 띄우기
		$('#openMask').click(function(e){
			e.preventDefault();
			wrapWindowByMask();
		});

		//닫기 버튼을 눌렀을 때
		$('.window .close').click(function (e) {  
		    //링크 기본동작은 작동하지 않도록 한다.
		    e.preventDefault();  
		    $('#mask, .window').hide();  
		});       

		//검은 막을 눌렀을 때
		$('.window').click(function () {  
		$('#mask').fadeOut(300);    
		$('.window').fadeOut(300); 
		});      
	});
	

	
</script>

<div class="back" onclick="location.href='<?=G5_URL?>/member'"> <i class="fas fa-arrow-left"></i> BACK </div>


<!-- 캐릭터 팝업 출력 영역 -->
	<div class="window" id="body_popup">
                <img src="<?=$ch['ch_body']?>" />
	</div>  
   
<div id="mask"></div>




<!-- 캐릭터 비쥬얼 (이미지) 출력 영역 --> 
    
<div class="visual-area">
    <div id="character_body">
            <a href="#" id="openMask">
                <img src="<?=$ch['ch_body']?>" />
            </a>
    </div>
</div>


<!-- //캐릭터 비쥬얼 (이미지) 출력 영역 -->
<div id="character_closet">
 <? include_once(G5_PATH.'/member/closet.php'); ?>
</div>

<!-- 오른쪽 (토글)-->
<div id="character_profile">


<!--설정-->
<div class="main_tab_content" id="ch_info" >
 
<div class="info_name"> <span> <?=$ch['title']?> </span> <p><?=$ch['ch_name']?> <span> <?=$ch['en_name']?> </span> </p> </div> 

<p class="profile_detail">
            <!-- 성별 -->
					<? echo $ch['gender'] ?>&nbsp; 
                    <i class="fas fa-grip-lines-vertical"></i>&nbsp;
        	<!-- 나이 -->
					<?php echo $ch['age'] ?>세 &nbsp; 
                    <i class="fas fa-grip-lines-vertical"></i>&nbsp;
            <!-- 혈통 -->
                    <?php echo $ch['bld'] ?>&nbsp;
                    <i class="fas fa-grip-lines-vertical"></i>&nbsp;
            <!-- 체중 -->
                    <?php echo $ch['bld'] ?>&nbsp;
                    <i class="fas fa-grip-lines-vertical"></i>&nbsp;
            <!-- 신장 -->
					<?php echo $ch['height'] ?>cm &nbsp;
                   <i class="fas fa-grip-lines-vertical"></i>&nbsp;           
  			<!-- 체중 -->
					<?php echo $ch['weight'] ?>kg &nbsp;
                   <i class="fas fa-grip-lines-vertical"></i>&nbsp;
            <!-- 생일 -->
					<?php echo $ch['birth'] ?> &nbsp;

</p>
   
 
   <div class="ch_content" id="ch_app">
   <?php echo nl2br($ch['appr']); ?>
   </div>

 
<div class="ch_contentainer theme-box">   
   <div class="ch_content" id="ch_ch">
   <?php echo nl2br($ch['per']); ?>
   </div>
</div>   
   
 
<div class="ch_contentainer theme-box">     
   <div class="ch_content" id="ch_ect">
   <?php echo nl2br($ch['ect']); ?>
   </div>
</div>


</div>

</div>
