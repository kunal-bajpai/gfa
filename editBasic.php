<?php
	require_once("includes/init.php");
	$villages = Village::find_all();
	$teams = Team::find_all();
	if(sizeof($_POST)>0){
		$player = new Player();
		$player->get_values();
		$player->dob = strtotime($player->dob);
		$player->save();
		$files = File::get_files();
		if($files[0]!=NULL){
			$files[0]->name="Player".$player->id;
			$files[0]->save_file_in(UPLOAD_DIR."images/photos/");
		}
	}
	$player = Player::find_by_id($_GET['id']);
?>
<html>
	<head>
		<link rel="stylesheet" type="text/css" media="all" href="jsDatePick_ltr.min.css" />
		<script type="text/javascript" src="js/jsDatePick.min.1.3.js"></script>
		<script type="text/javascript">
			window.onload = function(){
				new JsDatePick({useMode:2,target:"dob",dateFormat:"%d %M %Y"});
			};
		</script>
	</head>
	<body>
		<form method="POST" enctype="multipart/form-data">
			<img src="images/photos/Player<?php echo $player->id;?>" /><br/>
			Change Profile Pic <input type="file" name="photo" accept="image/*"/><br/>
			First Name* <input type="text" name="first_name" value="<?php echo $player->first_name;?>" required/><br/>
			Middle Name* <input type="text" name="mid_name" value="<?php echo $player->mid_name;?>" required/><br/>
			Last Name* <input type="text" name="last_name" value="<?php echo $player->last_name;?>" required/><br/>
			GFA License no.* <input type="text" name="gfa_lic_num" value="<?php echo $player->gfa_lic_num;?>" required/><br/>
			Nationality* <input type="text" name="nat" value="<?php echo $player->nat;?>" required/><br/>
			Date of Birth <input type="text" name="dob" id="dob" value="<?php echo strftime("%d %b %Y",$player->dob);?>"  autocomplete="off"/><br/>
			Address* <textarea name="address" required><?php echo $player->address;?></textarea><br/>
			Village* <select name="village">
			<?php if(is_array($villages))
					foreach($villages as $village): ?>
						<option value="<?php echo $village->id;?>" <?php if($player->village==$village->id) echo "selected";?>>
							<?php echo $village->name;?>
						</option>
					<?php endforeach;?>
			</select><br/>
			Team* <select name="team">
			<?php if(is_array($teams))
					foreach($teams as $team): ?>
						<option value="<?php echo $team->id;?>" <?php if($player->team==$team->id) echo "selected";?>>
							<?php echo $team->name;?> 
						</option>
					<?php endforeach;?>
			</select><br/>
			Telephone (residence) <input type="text" name="ph_res" value="<?php echo $player->ph_res;?>" /><br/>
			Telephone (office) <input type="text" name="ph_off" value="<?php echo $player->ph_off;?>" /><br/>
			Mobile number <input type="text" name="mob" value="<?php echo $player->mob;?>" /><br/>
			Email <input type="email" name="email" value="<?php echo $player->email;?>" required/><br/>
			<input type="submit" value="Save"/>
		</form>
	</body>
</html>
