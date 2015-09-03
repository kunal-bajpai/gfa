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
	<meta charset="UTF-8">
	<title> Goa FA | Search</title>
	<!-- Tell the browser to be responsive to screen width -->
	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
	<!-- Bootstrap 3.3.4 -->
	<link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
	<!-- Font Awesome -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
	<!-- Ionicons -->
	<link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
	<!-- jvectormap -->
	<link rel="stylesheet" href="plugins/jvectormap/jquery-jvectormap-1.2.2.css">
	<!-- Theme style -->
	<link rel="stylesheet" href="dist/css/AdminLTE.min.css">
		<!-- AdminLTE Skins. Choose a skin from the css/skins
		folder instead of downloading all of them to reduce the load. -->
		<link rel="stylesheet" href="dist/css/skins/_all-skins.min.css">
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

		<!-- jQuery 2.1.4 -->
		<script src="plugins/jQuery/jQuery-2.1.4.min.js"></script>
		<!-- Bootstrap 3.3.4 -->
		<script src="bootstrap/js/bootstrap.min.js"></script>
		<!-- FastClick -->
		<script src="plugins/fastclick/fastclick.min.js"></script>
		<!-- AdminLTE App -->
		<script src="dist/js/app.min.js"></script>
		<!-- Sparkline -->
		<script src="plugins/sparkline/jquery.sparkline.min.js"></script>
		<!-- jvectormap -->
		<script src="plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script>
		<script src="plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script>
		<!-- SlimScroll 1.3.0 -->
		<script src="plugins/slimScroll/jquery.slimscroll.min.js"></script>
		<!-- ChartJS 1.0.1 -->
		<script src="plugins/chartjs/Chart.min.js"></script>
		<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
		<script src="dist/js/pages/dashboard2.js"></script>
		<!-- AdminLTE for demo purposes -->
		<script src="dist/js/demo.js"></script>

	</head>
	<body class="skin-blue sidebar-mini">
		<div class="wrapper">
			<?php include ("header.php"); ?>
			<?php include ("sidebar.php"); ?>
			<div class="content-wrapper">
				<section class="content">
					<div class="box box-primary" >
						<div class="box-body text-center">
							<img class="img-circle" src="images/photos/Player<?php echo $player->id;?>" /><br/>
							<a href="editBasic.php?id=<?php echo $player->id;?>">Edit Basic Info</a><br/>
						</div>
					</div>
					<div class="box box-primary" >
						<div class="box-header with-border">
							<h3 class="box-title">Player Details</h3>
						</div>
						<div class="box-body">
							<dl class="dl-horizontal">
								<dt>First Name</dt>
								<dd><?php echo $player->first_name;?></dd>
								<dt>Middle Name</dt>
								<dd><?php echo $player->mid_name;?></dd>
								<dt>Last Name</dt>
								<dd><?php echo $player->last_name;?></dd>
								<dt>GFA License no.</dt>
								<dd><?php echo $player->gfa_lic_num;?></dd>
								<dt>Nationality</dt>
								<dd><?php echo $player->nat;?></dd>
								<dt>Date of Birth</dt>
								<dd><?php echo strftime("%d %b %Y",$player->dob);?></dd>
								<dt>Address</dt>
								<dd><?php echo $player->address;?></dd>
								<dt>Village</dt>
								<dd><?php echo Village::find_by_id($player->village)->name;?></dd>
								<dt>Team</dt>
								<dd><?php echo Team::find_by_id($player->team)->name;?></dd>
								<dt>Status</dt>
								<dd><?php if(isset($transTo[0]))
									echo "Transfered to ".$transTo[0]->name;
									else
										if(isset($loanedTo[0])) 
											echo "On loan to ".$loanedTo[0]->name;?></dd>
								<dt>Telephone (residence)</dt>
								<dd><?php echo $player->ph_res;?></dd>
								<dt>Telephone (office)</dt>
								<dd><?php echo $player->ph_off;?></dd>
								<dt>Mobile number</dt>
								<dd><?php echo $player->mob;?></dd>
								<dt>Email</dt>
								<dd><?php echo $player->email;?></dd>
								<dt>Category</dt>
								<dd><?php echo ($player->category==1)?"Non-amateur":"Amateur";?></dd>
							</dl>

						</div>
					</div>

					<div class="box box-primary">
						<div class="box-header with-border">
							<h3 class="box-title">Contract History</h3>
						</div>
						<div class="box-body">
							<?php if($player->category==1):?>
								<div id="contHistory">
									<table id="example2" class="table table-bordered table-hover">
										<thead>
											<tr>
												<th>Team</th>
												<th>Start Date</th>
												<th>End Date</th>
												<th>Status</th>
											</tr>
										</thead>
										<tbody>

											<?php if(is_array($contracts))
											foreach($contracts as $contract):?>
											<tr>
												<td>TODO:TEAM</td>
												<td><?php echo strftime("%d %b %Y",$contract->date_of_reg);?></td>
												<td><?php echo strftime("%d %b %Y",$contract->expiry);?></td>
												<td><?php echo ($contract->term==1)?"Terminated":NULL;?></td>
											</tr>
											<?php endforeach;?>
										</tbody>
									</table>
								</div>
							<?php endif; ?>
						</div>
					</div>

					<div class="form-group">
						<form method="POST">
							<input type="hidden" name="term_current" class="form-control" value="1"/>
							<input class="btn btn-danger" type="submit" value="Terminate current contract" />
						</form>
					</div>

					<div class="box box-primary">
						<div class="box-header with-border">
							<h3 class="box-title">New Contract</h3>
						</div>
						<div class="box-body">
							<form method="POST">
								<div class="form-group col-md-6">
									<label>Start Date</label>
									<input type="text" class="form-control" name="contFrom" id="contFrom" />
								</div>
								<div class="form-group col-md-6">
									<label>End Date</label>
									<input type="text" class="form-control" name="contTo" id="contTo" />
								</div>
								<input type="submit" class="btn btn-primary" value="Add contract" />
							</form>
						</div>
					</div>

					<div class="box box-primary">
						<div class="box-header with-border">
							<h3 class="box-title">Insurance History</h3>
						</div>
						<div class="box-body">
							<div id="insHistory">
								<table id="example2" class="table table-bordered table-hover">
									<thead>
										<tr>
											<th>Added On</th>
											<th>Expiry Date</th>
										</tr>
									</thead>
									<tbody>
										<?php if(is_array($contracts)) foreach($insurances as $insurance):?>
										<tr>
											<td><?php echo strftime("%d %b %Y",$insurance->added_on);?></td>
											<td><?php echo strftime("%d %b %Y",$insurance->expiry);?></td>
										</tr>
									<?php endforeach;?>
									</tbody>
								</table>
							</div>
						</div>
					</div>

					<div class="box box-primary">
						<div class="box-header with-border">
							<h3 class="box-title">New Insurance</h3>
						</div>
						<div class="box-body">
							<form method="POST">
								<div class="form-group">
									<label>Expiry</label>
									<input type="text" class="form-control" name="insTo" id="insTo" />
								</div>
								<input type="submit" class="btn btn-primary" value="Add Insurance" />
							</form>
						</div>
					</div>

					<div class="box box-primary">
						<div class="box-header with-border">
							<h3 class="box-title">Visa History</h3>
						</div>
						<div class="box-body">
							<div id="visaHistory">
								<table id="example2" class="table table-bordered table-hover">
									<thead>
										<th>Added On</th>
										<th>Expiry Date</th>
									</thead>
									<tbody>
									<?php if(is_array($visas))
									foreach($visas as $visa):?>
									<tr>
									<td><?php echo strftime("%d %b %Y",$visa->added_on);?></td>
									<td><?php echo strftime("%d %b %Y",$visa->expiry);?></td>
									</tr>
									<?php endforeach;?>
									</tbody>
								</table>
							</div>
						</div>
					</div>

					<div class="box box-primary">
						<div class="box-header with-border">
							<h3 class="box-title">Add Visa</h3>
						</div>
						<div class="box-body">
							<form method="POST">
								<div class="form-group" >
									<label>Expiry</label>
									<input type="text" class="form-control" name="visaTo" id="visaTo" /><br/>
									<input type="submit" class="btn btn-primary" value="Add Visa" />
								</div>
							</form>
						</div>
					</div>

					<div class="box box-primary">
						<div class="box-header with-border">
							<h3 class="box-title">Loan History</h3>
						</div>
						<div class="box-body">
							<div id="loanHistory">
								<table id="example2" class="table table-bordered table-hover">
									<thead>
										<th>Team</th>
										<th>Start Date</th>
										<th>End Date</th>
									</thead>
									<tbody>
									<?php if(is_array($loans))
									foreach($loans as $loan):?>
									<tr>
										<td><?php echo Team::find_by_id($loan->team)->name;?></td>
										<td><?php echo strftime("%d %b %Y",$loan->start);?></td>
										<td><?php echo strftime("%d %b %Y",$loan->end);?></td>
									</tr>
									<?php endforeach;?>
									</tbody>
								</table>
							</div>
						</div>
					</div>

					<div class="box box-primary">
						<div class="box-header with-border">
							<h3 class="box-title">Add Loan</h3>
						</div>
						<div class="box-body">
							<form method="POST">
								<div class="form-group">
									<label>Team</label>
									<select class="form-control" name="team">
									<?php if(is_array($teams))
									foreach($teams as $team): ?>
									<option value="<?php echo $team->id;?>"><?php echo $team->name;?> </option>
								<?php endforeach;?>
									</select>
								</div>
								<div class="form-group col-md-6">
									<label>Start date</label>
									<input type="text" name="loanFrom" id="loanFrom" class="form-control" />
								</div>
								<div class="form-group col-md-6">
									<label>End date</label>
									<input type="text" name="loanTo" id="loanTo" class="form-control"/>
								</div>
						 
								<input type="submit" value="Add Loan" class="btn btn-primary" />
							</form>
						</div>
					</div>

					<div class="box box-primary">
						<div class="box-header with-border">
							<h3 class="box-title">Association Transfer History</h3>
						</div>
						<div class="box-body">
							<div id="transHistory">
								<table id="example2" class="table table-bordered table-hover">
									<thead>
										<th>Association</th>
										<th>Start Date</th>
										<th>End Date</th>
									</thead>
									<tbody>
									<?php if(is_array($transfers))
									foreach($transfers as $transfer):?>
									<tr>
										<td><?php echo Assoc::find_by_id($transfer->assoc)->name;?></td>
										<td><?php echo strftime("%d %b %Y",$transfer->start);?></td>
										<td><?php if($transfer->returned==0)
												echo "Not returned";
												else
													echo strftime("%d %b %Y",$transfer->returned);?></td>
									</tr>
									<?php endforeach;?>
									</tbody>
								</table>
							</div>
						</div>
					</div>

					<div class="form-group">
						<form method="POST">
							<input type="hidden" name="return_transfer" class="form-control" value="1"/>
							<input class="btn btn-warning" type="submit" value="Return from transfer" />
						</form>
					</div>

					<div class="box box-primary">
						<div class="box-header with-border">
							<h3 class="box-title">Add Transfer</h3>
						</div>
						<div class="box-body">
							<form method="POST">
								<div class="form-group">
									<label>Association</label>
									<select name="assoc" class="form-control">
									<?php if(is_array($assocs))
									foreach($assocs as $assoc): ?>
									<option value="<?php echo $assoc->id;?>"><?php echo $assoc->name;?> </option>
									<?php endforeach;?>
									</select><br/>
								</div>
								<div class="form-group">
									<label>Start Date</label>
									<input type="text" class="form-control" name="transFrom" id="transFrom" /><br/>
								</div>
								<input type="submit" value="Add Transfer" class="btn btn-primary"/>
							</form>
						</div>
					</div>
				</section>
			</div>
			<?php include("footer.php"); ?>
		</div>
	</body>
</html>
