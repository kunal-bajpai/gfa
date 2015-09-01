<?php
	require_once("includes/init.php");
	if(isset($_GET['id'])) {
		if(isset($_POST['term_current'])){
			$cont = Contract::find_by_sql("SELECT * FROM contracts WHERE player = {$_GET['id']} AND expiry>".time()." AND term = 0 ORDER BY date_of_reg DESC");
			if(isset($cont[0])){
				$cont[0]->term = 1;
				$cont[0]->save();
			}
		}
		if(isset($_POST['contFrom']) && isset($_POST['contTo'])){
			$newCont = new Contract();
			$newCont->set($_GET['id'], strtotime($_POST['contFrom']), strtotime($_POST['contTo']));
			$newCont->save();
		}
		$player = Player::find_by_id($_GET['id']);
		if($player->category==1) {
			$contracts = Contract::find_by_sql("SELECT * FROM contracts WHERE player={$player->id} ORDER BY date_of_reg ASC");
			$insurances = Insurance::find_by_sql("SELECT * FROM insurances WHERE player={$player->id} ORDER BY added_on ASC");
			$visas = Visa::find_by_sql("SELECT * FROM visas WHERE player={$player->id} ORDER BY added_on ASC");
		}
	}
?>
<html>
	<head>
		<link rel="stylesheet" type="text/css" media="all" href="jsDatePick_ltr.min.css" />
		<script type="text/javascript" src="js/jsDatePick.min.1.3.js"></script>
		<script type="text/javascript">
			window.onload = function(){
				new JsDatePick({useMode:2,target:"contFrom",dateFormat:"%d-%m-%Y"});
				new JsDatePick({useMode:2,target:"contTo",dateFormat:"%d-%m-%Y"});
			};
		</script>
	</head>
	<body>
		<img src="images/photos/Player<?php echo $player->id;?>" /><br/>
		First Name: <?php echo $player->first_name;?><br/>
		Middle Name: <?php echo $player->mid_name;?><br/>
		Last Name: <?php echo $player->last_name;?><br/>
		GFA License no.: <?php echo $player->gfa_lic_num;?><br/>
		Nationality: <?php echo $player->nat;?><br/>
		Date of Birth: <?php echo strftime("%d-%m-%Y",$player->dob);?><br/>
		Address: <?php echo $player->address;?><br/>
		Village: <?php echo Village::find_by_id($player->village)->name;?><br/>
		Team: <?php echo Team::find_by_id($player->team)->name;?><br/>
		Telephone (residence): <?php echo $player->ph_res;?><br/>
		Telephone (office): <?php echo $player->ph_off;?><br/>
		Mobile number: <?php echo $player->mob;?><br/>
		Email: <?php echo $player->email;?><br/>
		Category: <?php echo ($player->category==1)?"Non-amateur":"Amateur";?><br/>
		
		<?php if(is_array($contracts)):?>
		<div id="contHistory">
			<?php foreach($contracts as $contract):?>
				<p><?php echo strftime("%d-%m-%Y",$contract->date_of_reg);?></p>
				<p><?php echo strftime("%d-%m-%Y",$contract->expiry);?></p>
				<p><?php echo ($contract->term==1)?"Terminated":NULL;?></p><br/>
			<?php endforeach;?>
		</div>
		<form method="POST">
			<input type="hidden" name="term_current" value="1"/>
			<input type="submit" value="Terminate current contract" />
		</form>
		<form method="POST">
			New Contract<br/>
			Date contract registered <input type="text" name="contFrom" id="contFrom" /><br/>
			Contract valid until <input type="text" name="contTo" id="contTo" /><br/>
			<input type="submit" value="Add contract" />
		</form>
		<?php endif;?>
		
		<?php if(is_array($contracts)):?>
		<div id="insHistory">
			<?php foreach($insurances as $insurance):?>
				<p><?php echo strftime("%d-%m-%Y",$insurance->added_on);?></p>
				<p><?php echo strftime("%d-%m-%Y",$insurance->expiry);?></p><br/>
			<?php endforeach;?>
		</div>
		<?php endif;?>
		
		<?php if(is_array($visas)):?>
		<div id="visaHistory">
			<?php foreach($visas as $visa):?>
				<p><?php echo strftime("%d-%m-%Y",$visa->added_on);?></p>
				<p><?php echo strftime("%d-%m-%Y",$visa->expiry);?></p><br/>
			<?php endforeach;?>
		</div>
		<?php endif;?>
		
		<?php if(is_array($visas)):?>
		<div id="visaHistory">
			<?php foreach($visas as $visa):?>
				<p><?php echo strftime("%d-%m-%Y",$visa->added_on);?></p>
				<p><?php echo strftime("%d-%m-%Y",$visa->expiry);?></p><br/>
			<?php endforeach;?>
		</div>
		<?php endif;?>
	</body>
</html>
