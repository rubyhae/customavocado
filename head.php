<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

if(defined('G5_THEME_PATH')) {
	require_once(G5_THEME_PATH.'/head.php');
	return;
}


include_once(G5_PATH.'/head.sub.php');
include_once(G5_LIB_PATH.'/latest.lib.php');
include_once(G5_LIB_PATH.'/outlogin.lib.php');
include_once(G5_LIB_PATH.'/poll.lib.php');
include_once(G5_LIB_PATH.'/visit.lib.php');
include_once(G5_LIB_PATH.'/connect.lib.php');
include_once(G5_LIB_PATH.'/popular.lib.php');

/*********** Logo Data ************/
$logo = get_logo('pc');
$m_logo = get_logo('mo');

$logo_data = "";
if($logo)		$logo_data .= "<img src='".$logo."' ";
if($m_logo)		$logo_data .= "class='only-pc' /><img src='".$m_logo."' class='not-pc'";
if($logo_data)	$logo_data.= " />";
/*********************************/

?>

<!-- 헤더 영역 -->
<header id="header">
	<div class="fix-layout">
		<!-- 로고 영역 : PC 로고 / 모바일 로고 동시 출력 - 디자인 사용을 체크하지 않을 시, 제대로 출력되지 않을 수 있습니다. -->
		<!-- 관리자 기능을 사용하지 않고 로고를 넣고 싶을 시, < ? = $ log_data ? > 항목을 제거 하고 <img> 태그를 넣으세요. -->
		<h1 id="logo">
			<a href="<?=G5_URL?>/main.php">
				<?=$logo_data?>
			</a>
		</h1>
		<?include(G5_PATH."/menu.php");?>
</header>
<!-- // 헤더 영역 -->

<section id="body">
	<div class="fix-layout">