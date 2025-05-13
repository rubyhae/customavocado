<?
include_once("./_common.php");


if($character['ch_id'] && $character['ch_state'] == '승인') { 
	$sql_common = " from {$g5['offer_table']} ";
	$sql_search = " where of_id = '{$of_id}' ";

	// 1. 진열 기간 사용 시
	$sql_search .= " AND (of_sdate <= '".date('Y-m-d')."' OR of_sdate = '') "; 
	$sql_search .= " AND (of_edate >= '".date('Y-m-d')."' OR of_edate = '') "; 

	$offer_sql = " select * {$sql_common} {$sql_search} ";
	$offer = sql_fetch($offer_sql);

	if($offer['of_id']) {
		$item = get_item($offer['it_id']);
		if(!$item['it_id']) alert("정보가 존재하지 않습니다.");

		switch($type) {
			case "구매" :
				// 구매 가능할 시
				if($member['mb_point'] < $offer['of_cost']) {
					// 구매 실패
					alert("구매에 실패하셨습니다.");

				} else  {
					// 구매 성공!
					$action = '차감';
					$po_content = $item['it_name'].' 구매 ( '.$offer['of_cost'].' 소모 )';
					$po_point = $offer['of_cost'] * -1;

					insert_point($member['mb_id'], $po_point, $item['it_name'].' 구매 ( '.$offer['of_cost'].$config['cf_money_pice'].' 소모 )', 'stock', time(), '구매');

					// 구매 성공 시 아이템 인벤토리에 추가
					// 인벤에 집어넣기
					$inven_sql = " insert into {$g5['inventory_table']}
									set ch_id = '{$character['ch_id']}',
										it_id = '{$item['it_id']}',
										it_name = '{$item['it_name']}',
										ch_name = '{$character['ch_name']}'";
					sql_query($inven_sql);
				}
			break;
			case "판매" :
				// 인벤토리에 아이템이 있는지 확인한다.
				$in = sql_fetch("select in_id, count(*) as cnt from {$g5['inventory_table']} where ch_id = '{$character['ch_id']}' and it_id = '{$item['it_id']}'");

				if($in['cnt'] == 0) {
					alert("판매할 물품이 없습니다.");
				} else {
					$action = '판매';
					$cost = $offer['of_cost'];
					$content = $item['it_name'].' 판매 ( '.$offer['of_cost'].'개 획득 )';
					insert_point($member['mb_id'], $cost, $item['it_name'].' 판매 ( '.$cost.$config['cf_money_pice'].' 획득 )', 'stock', time(), '판매');
					delete_inventory($in['in_id']);
				}
			break;
		}

	} else {
		alert("구매에 실패하셨습니다.");
	}
}


goto_url('./index.php');

?>

