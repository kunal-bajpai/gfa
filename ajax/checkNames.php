<?php
	require_once("../includes/init.php");
	if(isset($_POST['fname']) && isset($_POST['mname']) && isset($_POST['lname'])) {
		$players = Player::find_all();
		$res = array();
		if(is_array($players))
			foreach($players as $player)
				if(levenshtein($player->first_name,$_POST['fname'])<=3 && levenshtein($player->mid_name,$_POST['mname'])<=3 && levenshtein($player->last_name,$_POST['lname'])<=3)
					$res[]=$player;
		echo json_encode($res);
	}
