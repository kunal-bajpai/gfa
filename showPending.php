<?php
	require_once("includes/init.php");
	$contPending = Player::find_by_sql("SELECT *,MAX(date_of_reg) FROM players JOIN contracts ON contracts.player = players.id WHERE expiry - ".time()." < 604800 AND term = 0 GROUP BY players.id ORDER BY expiry ASC");
	$insPending = Player::find_by_sql("SELECT *,MAX(expiry) FROM players JOIN insurances ON insurances.player = players.id WHERE expiry - ".time()." < 604800 GROUP BY players.id ORDER BY expiry ASC");
	$visaPending = Player::find_by_sql("SELECT *,MAX(expiry) FROM players JOIN visas ON visas.player = players.id WHERE expiry - ".time()." < 604800 GROUP BY players.id ORDER BY expiry ASC");
?>
<html>
	<body>
		Contracts pending
		<div>
			<?php if(is_array($contPending))
					foreach($contPending as $player):?>
					<p><?php echo $player->first_name;?></p>
					<p><?php echo $player->mid_name;?></p>
					<p><?php echo $player->last_name;?></p>
					<p><?php echo $player->gfa_lic_num;?></p>
					<p><?php echo strftime("%d %b %Y",$player->expiry);?></p>
					<a href="playerView.php?id=<?php echo $player->id;?>">View</a>
				<?php endforeach;?>
		</div>
		Insurances pending
		<div>
			<?php if(is_array($insPending))
					foreach($insPending as $player):?>
					<p><?php echo $player->first_name;?></p>
					<p><?php echo $player->mid_name;?></p>
					<p><?php echo $player->last_name;?></p>
					<p><?php echo $player->gfa_lic_num;?></p>
					<p><?php echo strftime("%d %b %Y",$player->expiry);?></p>
					<a href="playerView.php?id=<?php echo $player->id;?>">View</a>
				<?php endforeach;?>
		</div>
		Visas pending
		<div>
			<?php if(is_array($visaPending))
					foreach($visaPending as $player):?>
					<p><?php echo $player->first_name;?></p>
					<p><?php echo $player->mid_name;?></p>
					<p><?php echo $player->last_name;?></p>
					<p><?php echo $player->gfa_lic_num;?></p>
					<p><?php echo strftime("%d %b %Y",$player->expiry);?></p>
					<a href="playerView.php?id=<?php echo $player->id;?>">View</a>
				<?php endforeach;?>
		</div>
	</body>
</html>
