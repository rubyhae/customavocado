<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

/*************************************************************************************
	SAMPLE CODE
--------------------------------------------------------------------------------------

◆ DB 테이블 명 추가
$g5['테이블고유명칭_table'] = G5_TABLE_PREFIX.'테이블고유명칭';

◆ 관리자에 아이템 기능 자동 추가
if(!strstr($config['cf_item_category'], '아이템기능명')) {
	$config['cf_item_category'] .= "||아이템기능명";
}

◆ 테이블 자동 생성 (Mysql 테이블 생성 코드는 반드시 관련 지식에 대해 이해하고 작업하세요!)
if(!sql_query(" DESC {$g5['테이블고유명칭_table']} ")) {
	sql_query(" CREATE TABLE IF NOT EXISTS `{$g5['테이블고유명칭_table']}` (
	  `자동idx필드명` int(11) NOT NULL AUTO_INCREMENT,
	  `필드명` 타입 NOT NULL default '기본값',
	  PRIMARY KEY (`자동idx필드명`)
	) ", false);
}

◆ 기본 기능 함수 선언
function 함수명(매개변수) {
	global $g5; // 글로벌 변수, $g5가 있어야 테이블 명 등 기본 값등을 불러올 수 있습니다.

	// 함수 기능 선언
}

*************************************************************************************/

$g5['room_table'] = G5_TABLE_PREFIX.'room';

if(!sql_query(" DESC {$g5['room_table']} ")) {
	sql_query(" CREATE TABLE IF NOT EXISTS `{$g5['room_table']}` (
		`ro_id` int(11) NOT NULL AUTO_INCREMENT,
		`ch_id` int(11) NOT NULL default '0',
		`ro_img` varchar(255) NOT NULL default '',
		`ro_top` int(11) NOT NULL default '0',
		`ro_left` int(11) NOT NULL default '0',
		`ro_order` int(11) NOT NULL default '0',
		PRIMARY KEY (`ro_id`)
	) ", false);
}

if(!strstr($config['cf_item_category'], '마이룸가구')) {
	$config['cf_item_category'] .= "||마이룸가구";
}

if(!strstr($config['cf_item_category'], '마이룸커스텀가구')) {
	$config['cf_item_category'] .= "||마이룸커스텀가구";
}

if(!strstr($config['cf_item_category'], '마이룸배경')) {
	$config['cf_item_category'] .= "||마이룸배경";
}

// 캐릭터 룸 배경값이 존재하지 않을 경우
if($is_member && !isset($character['ch_room_bak'])) { 
	sql_query(" ALTER TABLE `{$g5['character_table']}` ADD `ch_room_bak` varchar(255) NOT NULL DEFAULT '' AFTER `ch_class` ");
}

?>