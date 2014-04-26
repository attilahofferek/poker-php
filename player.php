<?php

class Player
{
	const VERSION = "AllLean 0.2";

	public function betRequest($game_state)
	{
		$my_player = $game_state['players'][$game_state['in_action']];

		$required_bet = $game_state['minimum_raise'];
		$cards = array_merge($my_player['hole_cards'], $game_state['community_cards']);
		$counts = array();
		foreach( $cards as $card) {
			$counts[$card['rank']]++;
		}
		$max = max($counts)*2;
		$ret = $required_bet + (($max - 1)*10);
		return $ret;
	}

	public function showdown($game_state)
	{
	}
}
