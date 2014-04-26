<?php

class Player {

	const VERSION = "AllLean 0.2";

	public function betRequest($game_state) {
		$my_player = $game_state['players'][$game_state['in_action']];

		$minimum_bet = $game_state['current_buy_in'] - $my_player['bet'] + $game_state['minimum_raise'];
		$cards = array_merge($my_player['hole_cards'], $game_state['community_cards']);

		$finalRank = 0;
		$card_count = count($cards);
		if( $card_count >= 5) {
			$rank = $this->getRanking($cards);
			$finalRank = $rank['rank'];
		} else {
			$counts = array();
			foreach ($cards as $card) {
				$counts[$card['rank']] ++;
			}
			$finalRank = max($counts)-1;
		}

		$ret = $minimum_bet + ($finalRank * 20);
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
