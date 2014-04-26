<?php

class Player {

	const VERSION = "AllLean 0.2";

	public function betRequest($game_state) {

		$HOLE_CARD_AVG_MULTIPLIER = 4;
		$CARD_RANK_MULTIPLIER = 50;
		$FOLD_BELOW = 30;
		$ALLIN_ABOVE = 80;
		//$STACK_FOLD_BELOW = 200;

		$my_player = $game_state['players'][$game_state['in_action']];

		//if ($my_player['stack'] < $STACK_FOLD_BELOW) {
		//	return 0;
		//}

		$minimum_bet = $game_state['current_buy_in'] - $my_player['bet'] + $game_state['minimum_raise'];
		$cards = array_merge($my_player['hole_cards'], $game_state['community_cards']);
		$turn = $game_state['orbits'];

		$hole_cards_sum = 0;
		foreach($my_player['hole_cards'] as $card) {
			$from = array('J'=>11, 'Q'=>12, 'K'=>13, 'A'=>14);
			$hole_cards_sum += strtr($card['rank'], $from);
		}
		$hole_cards_avg = $hole_cards_sum/count($my_player['hole_cards']);

		$evalpoints = $hole_cards_avg * $HOLE_CARD_AVG_MULTIPLIER;
		$finalRank = 0;
		$card_count = count($cards);
		$player_count = 0;
		foreach($game_state['players'] as $player) {
			if($player['status'] == 'active') {
				$player_count++;
			}
		}

		if( $card_count >= 5) {
			$rank = $this->getRanking($cards);
			$finalRank = $rank['rank'];
		} else {
			$counts = array();
			foreach ($cards as $card) {
				$counts[$card['rank']] ++;
			}
			$finalRank = (max($counts)-1)*4;
		}
		if(max($counts) < 2 && ($hole_cards_avg < 7)) {
			return 0;
		}

		$evalpoints += ($finalRank * $CARD_RANK_MULTIPLIER);
		if($turn < 6) {
			$evalpoints-=30;
		}



		if($evalpoints < $FOLD_BELOW) {
			return 0;
		} else if ($evalpoints > $ALLIN_ABOVE) {
			return 100000;
		}

		$ret = $minimum_bet;

		return $ret;
	}

	private function getRanking($cards) {
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_POST, true);
		$send = json_encode($cards);
		curl_setopt($curl, CURLOPT_POSTFIELDS, "cards=".$send);
		curl_setopt($curl, CURLOPT_PORT, 2048);
		curl_setopt($curl, CURLOPT_URL, 'http://localhost/');
		$ret = curl_exec($curl);

		return json_decode($ret, 1);
	}

	public function showdown($game_state) {

	}

}
