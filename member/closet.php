<?php
include_once('./_common.php');
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가 
$cl_count = sql_fetch("select count(*) as cnt from {$g5['closthes_table']} where ch_id = '{$ch_id}'");
$cl_count = $cl_count['cnt'];
$cl_result = sql_query("select * from {$g5['closthes_table']} where ch_id = '{$ch_id}' order by cl_id asc");
?>

<div class="closet-list">

<? if($cl_count > 0) { ?>

<? for($i=0; $row=sql_fetch_array($cl_result); $i++) { 
	$class = "";

	$class .= $row['cl_type'];
	if($row['cl_use'] == '1') { 
		$class .=' selected ';
	}
?>
<li class="cl_item <?=$class?>">
 
 <? if($row['cl_type'] =='default'){ ?>
        			<? if($is_admin){?>
                <p class="ui-btn-box">
                 <a href="../mypage/character/closet_update.php?w=u&amp;cl_id=<?=$row['cl_id']?>&amp;ch_id=<?=$ch_id?>" class="ui-btn btn-use">적용</a>
                </p>
            <? }?>
 
    <a href="<?=$row['cl_path']?>"  onclick="view_body('<?=$row['cl_path']?>'); return false;">
			 <p class="clname"> DEFAULT </p> 
			</a>	
 
		 <? }else{?>
      			<? if($is_admin){?>
			<p class="ui-btn-box">
				<a href="../mypage/character/closet_update.php?w=u&amp;cl_id=<?=$row['cl_id']?>&amp;ch_id=<?=$ch_id?>" class="ui-btn btn-use">적용</a>
				<? if($row['cl_type'] !='default'){ ?>
    <a href="../mypage/character/closet_update.php?w=d&amp;cl_id=<?=$row['cl_id']?>&amp;ch_id=<?=$ch_id?>" onclick="return confirm('삭제한 데이터는 복구할 수 없습니다. 정말 삭제하시겠습니까?');" class="ui-btn btn-del">삭제</a>
                <? } ?>
			</p>
            <? }?>
 
   <a href="<?=$row['cl_path']?>"  onclick="view_body('<?=$row['cl_path']?>'); return false;">
     <p class="clname"> <?=$row['cl_subject']?> </p>
			</a>
  
         <? } ?>
</li>
<? } ?>

<? } ?>
 </div>
        <? if($is_admin){?>
<p class="addcl ui-btn point" onclick="$('.add').toggleClass('on');"> 추가 </p>
        <div class="add">
            <div class="add_menu">
            <p>이름</p>
            <p>전신</p>
            </div>
        <form action="<?=G5_URL?>/mypage/character/closet_update.php" method="post" name="frm_closet" id="frm_closet" enctype="multipart/form-data" >
            <input type="hidden" name="ch_id" value="<?=$ch_id?>" />
        
            <fieldset>
                <input type="text" name="cl_subject" id="cl_sibject" value="" class="full" placeholder="전신 이름" style="width:80%;" />
                <input type="file" name="cl_path_file" id="cl_path_file" value="" class="full" style="width:80%;" />
                <input type="submit" value="추가" class="ui-btn point" style="float: right;  top: -30px;  width: 20%;  height: 60px; border-radius: 0;"/>
            </fieldset>
        </form>
        </div>
        <? }?>
 

<script>
function view_body(_url){
 $('#openMask').empty().html('<img src="'+_url+'">');
 
}
</script>

