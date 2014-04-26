<?php

class Player
{
	const VERSION = "AllLean 0.1";

	public function betRequest($game_state)
	{
		$my_player = array();
		foreach($game_state['players'] as $player) {
			if (array_key_exists("hole_cards", $player)) {
				$my_player = $player;
			}
		}

		return $game_state['current_buy_in'] - $my_player['bet'];
	}

	public function showdown($game_state)
	{
	}
}
