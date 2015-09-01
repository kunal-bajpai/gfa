<?php
	require_once("includes/init.php");
	$villages = Village::find_all();
	$teams = Team::find_all();
	if(sizeof($_POST)>0){
		$conds = array();
		if($_POST['first_name']!='') $conds[] = "first_name = '{$_POST['first_name']}'";
		if($_POST['mid_name']!='') $conds[] = "mid_name = '{$_POST['mid_name']}'";
		if($_POST['last_name']!='') $conds[] = "last_name = '{$_POST['last_name']}'";
		if($_POST['gfa_lic_num']!='') $conds[] = "gfa_lic_num = '{$_POST['gfa_lic_num']}'";
		if($_POST['nat']!='') $conds[] = "nat = '{$_POST['nat']}'";
		if($_POST['dob']!='') $conds[] = "dob = '{$_POST['dob']}'";
		if($_POST['village']!=0) $conds[] = "village = {$_POST['village']}";
		if($_POST['category']!=-1) $conds[] = "category = {$_POST['category']}";
		if(count($conds)>0)
			$players = Player::find_by_sql("SELECT * FROM players WHERE ".implode(' AND ', $conds));
		else
			$players = Player::find_all();
	}
?>
<html>
	<body>
		<form method="POST">
			First Name <input type="text" name="first_name"/><br/>
			Middle Name <input type="text" name="mid_name"/><br/>
			Last Name <input type="text" name="last_name"/><br/>
			GFA License No. <input type="text" name="gfa_lic_num"/><br/>
			Nationality <input type="text" name="nat"/><br/>
			Date of Birth <input type="text" name="dob" id="dob" autocomplete="off"/><br/>
			Village* <select name="village">
				<option value=0>Any</option>
				<?php if(is_array($villages))
						foreach($villages as $village): ?>
							<option value="<?php echo $village->id;?>"><?php echo $village->name;?> </option>
						<?php endforeach;?>
			</select><br/>
			Category <select id="category" name="category">
					<option value=-1>Any</option>
					<option value=0>Amateur</option>
					<option value=1>Non-amateur</option>
			</select><br/>
			<input type="submit" value="Search"/>
		</form>
		<div>
			<?php if(is_array($players))
					foreach($players as $player):?>
					<p><?php echo $player->first_name;?></p>
					<p><?php echo $player->mid_name;?></p>
					<p><?php echo $player->last_name;?></p>
					<p><?php echo $player->gfa_lic_num;?></p>
					<p><?php echo strftime("%d %b %Y",$player->dob);?></p>
					<a href="playerView.php?id=<?php echo $player->id;?>">View</a>
				<?php endforeach;?>
		</div>
	</body>
</html>
