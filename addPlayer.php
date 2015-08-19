<?php
	require_once("includes/init.php");
	$villages = Village::find_all();
	if(sizeof($_POST)>0){
		$player = new Player();
		$player->get_values();
		$player->save();
	}
?>
<form method="POST">
	First Name* <input type="text" name="first_name" required/><br/>
	Middle Name* <input type="text" name="mid_name" required/><br/>
	Last Name* <input type="text" name="last_name" required/><br/>
	GFA License no.* <input type="text" name="gfa_lic_num" required/><br/>
	Nationality* <input type="text" name="nat" required/><br/>
	Address* <textarea name="address" required></textarea><br/>
	Village* <select name="village">
	<?php if(is_array($villages))
			foreach($villages as $village): ?>
				<option value="<?php echo $village->id;?>"><?php echo $village->name;?> </option>
			<?php endforeach;?>
	</select><br/>
	Telephone (residence) <input type="text" name="ph_res"/><br/>
	Telephone (office) <input type="text" name="ph_off"/><br/>
	Mobile number <input type="text" name="mob"/><br/>
	Email <input type="email" name="email" required/><br/>
	Category 
	<select name="category">
		<option value=0>Amateur</option>
		<option value=1>Non-amateur</option>
	</select><br/>
	<input type="submit" value="Register"/>
</form>
