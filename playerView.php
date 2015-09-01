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
		if(isset($_POST['return_transfer'])){
			$transfer = Transfer::find_by_sql("SELECT * FROM transfers WHERE player = {$_GET['id']} AND returned = 0 ORDER BY start DESC");
			if(isset($transfer[0])){
				$transfer[0]->returned = time();
				$transfer[0]->save();
			}
		}
		if(isset($_POST['contFrom']) && isset($_POST['contTo'])){
			$newCont = new Contract();
			$newCont->set($_GET['id'], strtotime($_POST['contFrom']), strtotime($_POST['contTo']));
			$newCont->save();
		}
		if(isset($_POST['loanFrom']) && isset($_POST['loanTo'])){
			$newLoan = new Loan();
			$newLoan->set($_GET['id'], $_POST['team'], strtotime($_POST['loanFrom']), strtotime($_POST['loanTo']));
			$newLoan->save();
		}
		if(isset($_POST['insTo'])){
			$newIns = new Insurance();
			$newIns->set($_GET['id'], strtotime($_POST['insTo']));
			$newIns->save();
		}
		if(isset($_POST['visaTo'])){
			$newVisa = new Visa();
			$newVisa->set($_GET['id'], strtotime($_POST['visaTo']));
			$newVisa->save();
		}
		if(isset($_POST['transFrom'])){
			$newTrans = new Transfer();
			$newTrans->set($_GET['id'], $_POST['assoc'], strtotime($_POST['transFrom']));
			$newTrans->save();
		}
		$teams = Team::find_all();
		$assocs = Assoc::find_all();
		$player = Player::find_by_id($_GET['id']);
		if($player->category==1) {
			$contracts = Contract::find_by_sql("SELECT * FROM contracts WHERE player={$player->id} ORDER BY date_of_reg ASC");
			$insurances = Insurance::find_by_sql("SELECT * FROM insurances WHERE player={$player->id} ORDER BY added_on ASC");
			$visas = Visa::find_by_sql("SELECT * FROM visas WHERE player={$player->id} ORDER BY added_on ASC");
			$loans = Loan::find_by_sql("SELECT * FROM loans WHERE player={$player->id} ORDER BY start ASC");
			$transfers = Transfer::find_by_sql("SELECT * FROM transfers WHERE player={$player->id} ORDER BY start ASC");
			$loanedTo = Team::find_by_sql("SELECT * FROM teams WHERE id = (SELECT team FROM loans WHERE player = {$_GET['id']} AND end > ".time().")");
			$transTo = Assoc::find_by_sql("SELECT * FROM assocs WHERE id = (SELECT assoc FROM transfers WHERE player = {$_GET['id']} AND returned = 0)");
		}
	}
?>
<html>
	<head>
		<link rel="stylesheet" type="text/css" media="all" href="jsDatePick_ltr.min.css" />
		<script type="text/javascript" src="js/jsDatePick.min.1.3.js"></script>
		<script type="text/javascript">
			window.onload = function(){
				new JsDatePick({useMode:2,target:"contFrom",dateFormat:"%d %M %Y"});
				new JsDatePick({useMode:2,target:"contTo",dateFormat:"%d %M %Y"});
				new JsDatePick({useMode:2,target:"loanFrom",dateFormat:"%d %M %Y"});
				new JsDatePick({useMode:2,target:"loanTo",dateFormat:"%d %M %Y"});
				new JsDatePick({useMode:2,target:"insTo",dateFormat:"%d %M %Y"});
				new JsDatePick({useMode:2,target:"visaTo",dateFormat:"%d %M %Y"});
				new JsDatePick({useMode:2,target:"transFrom",dateFormat:"%d %M %Y"});
			};
		</script>
	</head>
	<body>
		<img src="images/photos/Player<?php echo $player->id;?>" /><br/>
		<a href="editBasic.php?id=<?php echo $player->id;?>">Edit Basic Info</a><br/>
		First Name: <?php echo $player->first_name;?><br/>
		Middle Name: <?php echo $player->mid_name;?><br/>
		Last Name: <?php echo $player->last_name;?><br/>
		GFA License no.: <?php echo $player->gfa_lic_num;?><br/>
		Nationality: <?php echo $player->nat;?><br/>
		Date of Birth: <?php echo strftime("%d %b %Y",$player->dob);?><br/>
		Address: <?php echo $player->address;?><br/>
		Village: <?php echo Village::find_by_id($player->village)->name;?><br/>
		Team: <?php echo Team::find_by_id($player->team)->name;?><br/>
		Status: <?php if(isset($transTo[0]))
							echo "Transfered to ".$transTo[0]->name;
						else
							if(isset($loanedTo[0])) 
								echo "On loan to ".$loanedTo[0]->name;?><br/>
		Telephone (residence): <?php echo $player->ph_res;?><br/>
		Telephone (office): <?php echo $player->ph_off;?><br/>
		Mobile number: <?php echo $player->mob;?><br/>
		Email: <?php echo $player->email;?><br/>
		Category: <?php echo ($player->category==1)?"Non-amateur":"Amateur";?><br/>
		
		<?php if($player->category==1):?>
		<div id="contHistory">
			<?php if(is_array($contracts))
					foreach($contracts as $contract):?>
				<p><?php echo strftime("%d %b %Y",$contract->date_of_reg);?></p>
				<p><?php echo strftime("%d %b %Y",$contract->expiry);?></p>
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
		
		<div id="insHistory">
			<?php if(is_array($contracts))
					foreach($insurances as $insurance):?>
				<p><?php echo strftime("%d %b %Y",$insurance->added_on);?></p>
				<p><?php echo strftime("%d %b %Y",$insurance->expiry);?></p><br/>
			<?php endforeach;?>
		</div>
		<form method="POST">
			New Insurance<br/>
			Expiry <input type="text" name="insTo" id="insTo" /><br/>
			<input type="submit" value="Add Insurance" />
		</form>
		
		<div id="visaHistory">
			<?php if(is_array($visas))
					foreach($visas as $visa):?>
				<p><?php echo strftime("%d %b %Y",$visa->added_on);?></p>
				<p><?php echo strftime("%d %b %Y",$visa->expiry);?></p><br/>
			<?php endforeach;?>
		</div>
		<form method="POST">
			New Visa<br/>
			Expiry <input type="text" name="visaTo" id="visaTo" /><br/>
			<input type="submit" value="Add Visa" />
		</form>
		
		<div id="loanHistory">
			<?php if(is_array($loans))
					foreach($loans as $loan):?>
				<p><?php echo Team::find_by_id($loan->team)->name;?></p>
				<p><?php echo strftime("%d %b %Y",$loan->start);?></p>
				<p><?php echo strftime("%d %b %Y",$loan->end);?></p><br/>
			<?php endforeach;?>
		</div>
		<form method="POST">
			New Loan<br/>
			Team <select name="team">
			<?php if(is_array($teams))
					foreach($teams as $team): ?>
						<option value="<?php echo $team->id;?>"><?php echo $team->name;?> </option>
					<?php endforeach;?>
			</select><br/>
			Start date <input type="text" name="loanFrom" id="loanFrom" /><br/>
			End date <input type="text" name="loanTo" id="loanTo" /><br/>
			<input type="submit" value="Add Loan" />
		</form>
		
		<div id="transHistory">
			<?php if(is_array($transfers))
					foreach($transfers as $transfer):?>
				<p><?php echo Assoc::find_by_id($transfer->assoc)->name;?></p>
				<p><?php echo strftime("%d %b %Y",$transfer->start);?></p>
				<p><?php if($transfer->returned==0)
							echo "Not returned";
						else
							echo strftime("%d %b %Y",$transfer->returned);?></p>
			<?php endforeach;?>
		</div>
		<form method="POST">
			<input type="hidden" name="return_transfer" value="1"/>
			<input type="submit" value="Return from transfer" />
		</form>
		<form method="POST">
			New Transfer<br/>
			Association <select name="assoc">
			<?php if(is_array($assocs))
					foreach($assocs as $assoc): ?>
						<option value="<?php echo $assoc->id;?>"><?php echo $assoc->name;?> </option>
					<?php endforeach;?>
			</select><br/>
			Start date <input type="text" name="transFrom" id="transFrom" /><br/>
			<input type="submit" value="Add Transfer" />
		</form>
		<?php endif;?>
	</body>
</html>
