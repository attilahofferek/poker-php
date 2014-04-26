<?php

class Player {

	const VERSION = "AllLean 0.2";

	public function betRequest($game_state) {
		$my_player = $game_state['players'][$game_state['in_action']];

		$required_bet = $game_state['minimum_raise'];
		$cards = array_merge($my_player['hole_cards'], $game_state['community_cards']);

		$counts = array();

		$rank = $this->getRanking($cards);
		var_dump($rank);

		foreach ($cards as $card) {
			$counts[$card['rank']] ++;
		}
		$max = max($counts) * 2;
		$ret = $required_bet + (($max - 1) * 10);
		return $ret;
	}

	private function getRanking($cards) {
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_POST, true);
		$send = json_encode(array("cards" => $cards));
		curl_setopt($curl, CURLOPT_POSTFIELDS, $send);
		curl_setopt($curl, CURLOPT_PORT, 2048);
		curl_setopt($curl, CURLOPT_URL, 'http://localhost/');
		$ret = curl_exec($curl);

		return json_decode($ret, 1);
	}

	public function showdown($game_state) {

	}

}
