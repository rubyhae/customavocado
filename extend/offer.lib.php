<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

$g5['offer_table'] = G5_TABLE_PREFIX.'offer'; // 주식 관련 테이블

// 주식 테이블이 없을 경우 생성
if(!sql_query(" DESC {$g5['offer_table']} ")) {
	sql_query(" CREATE TABLE IF NOT EXISTS `{$g5['offer_table']}` (
	  `of_id` int(11) NOT NULL AUTO_INCREMENT,
	  `it_id` int(11) NOT NULL default '0',
	  `it_name` varchar(255) NOT NULL default '',
	  `of_content` text NOT NULL default '',
	  `of_sdate` varchar(255) NOT NULL default '',
	  `of_edate` varchar(255) NOT NULL default '',
	  `of_change_time` varchar(255) NOT NULL default '',
	  `of_min_cost` int(11) NOT NULL default '0',
	  `of_max_cost` int(11) NOT NULL default '0',
	  `of_reset` varchar(255) NOT NULL default '',
	  `of_cost` int(11) NOT NULL default '0',
	  `of_use` int(11) NOT NULL default '0',
	  PRIMARY KEY (`of_id`)
	) ", false);
}

function set_reset_offer($data) {
	global $g5;

	$now = date('Y-m-d H:i:s');
	if($data['of_reset'] == '0000-00-00 00:00:00') {
		$cost = rand($data['of_min_cost'], $data['of_max_cost']);
		$data['of_reset'] = date('Y-m-d H').":00:00";
		$data['of_cost'] = $cost;
		sql_query("update {$g5['offer_table']} set of_cost = '{$cost}', of_reset = '{$now}' where of_id = '{$data['of_id']}'");
	}

	$next_date = date('Y-m-d H:i:s', strtotime($data['of_reset']." +{$data['of_change_time']} Hour"));
	if($next_date < $now) {
		// 갱신 시간이 되었다. 시세 변동
		$cost = rand($data['of_min_cost'], $data['of_max_cost']);
		$data['of_cost'] = $cost;

		// 날짜 비교
		$to_time = strtotime($next_date); 
		$from_time = strtotime($now);
		$hour = floor(abs($to_time - $from_time) / 60 / 60); /* 시간 */
		$check_hour = floor($hour/$data['of_change_time']); /* 갱신 텀 체크 */

		if($check_hour > 0) {
			$reset_date = date('Y-m-d H:i:s', strtotime($next_date." +{$hour} Hour"));
		} else {
			$reset_date = $next_date;
		}
		//echo $hour;

		$data['of_reset'] = $reset_date;
		$data['of_cost'] = $cost;
		sql_query("update {$g5['offer_table']} set of_cost = '{$cost}', of_reset = '{$reset_date}' where of_id = '{$data['of_id']}'");
	}

	return $data;
}

?>