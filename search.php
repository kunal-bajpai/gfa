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
<head>
	<meta charset="UTF-8">
	<title>GFA | Search</title>
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

    <link rel="stylesheet" href="plugins/datepicker/datepicker3.css">
	<!-- Theme style -->
	<link rel="stylesheet" href="dist/css/AdminLTE.min.css">
	<!-- AdminLTE Skins. Choose a skin from the css/skins
	folder instead of downloading all of them to reduce the load. -->
	<link rel="stylesheet" href="dist/css/skins/_all-skins.min.css">

	<link rel="stylesheet" type="text/css" media="all" href="jsDatePick_ltr.min.css" />
	<script type="text/javascript" src="js/jsDatePick.min.1.3.js"></script>

    <!-- jQuery 2.1.4 -->
    <script src="plugins/jQuery/jQuery-2.1.4.min.js"></script>
    <!-- Bootstrap 3.3.4 -->
    <script src="bootstrap/js/bootstrap.min.js"></script>
    <!-- FastClick -->
    <script src="plugins/fastclick/fastclick.min.js"></script>
    <script src="plugins/datepicker/bootstrap-datepicker.js"></script>
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

    <script type="text/javascript">
		window.onload = function(){
			// new JsDatePick({useMode:2,target:"dob",dateFormat:"%d %M %Y"});
			// $('#dob').daterangepicker();
		};

		$(function () {
	        $("#dob").datepicker();
    	});
	</script>


	<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
		<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
		<![endif]-->
	</head>
	<body class="skin-blue sidebar-mini">
		<div class="wrapper">

    	<?php include("header.php"); ?>

    	<?php include("sidebar.php"); ?>

    	<div class="content-wrapper">
    	<section class="content">
			<div class="box box-primary">
				<div class="box-header with-border">
					<h3 class="box-title">Search Players</h3>
				</div><!-- /.box-header -->
				<div class="box-body">
					<form method="POST">
						<div class="form-group col-md-4">
							<label>First Name</label>
							<input type="text" class="form-control" placeholder="Enter ..." name="first_name">
						</div>

						<div class="form-group col-md-4">
							<label>Middle Name</label>
							<input type="text" class="form-control" placeholder="Enter ..." name="mid_name">
						</div>

						<div class="form-group col-md-4">
							<label>Last Name</label>
							<input type="text" class="form-control" placeholder="Enter ..." name="last_name">
						</div>

						<div class="form-group col-md-4">
							<label>GFA License No.</label>
							<input type="text" class="form-control" placeholder="Enter ..." name="gfa_lic_num">
						</div>

						<div class="form-group col-md-4">
							<label>Nationality</label>
							<input type="text" class="form-control" placeholder="Enter ..." name="nat">
						</div>

						<div class="form-group col-md-4">
							<label>Date of Birth</label>
							<input type="text" class="form-control" placeholder="Enter ..."  name="dob" id="dob" autocomplete="off">
						</div>

						<div class="form-group col-md-4">
							<label>Village*</label>
							<select name="village" class="form-control">
								<option value=0>Any</option>
								<?php if(is_array($villages))
								foreach($villages as $village): ?>
								<option value="<?php echo $village->id;?>"><?php echo $village->name;?> </option>
								<?php endforeach;?>
							</select><br/>
						</div>
						<div class="form-group col-md-4">
							<label>Category</label>
							<select id="category" name="category" class="form-control">
								<option value=-1>Any</option>
								<option value=0>Amateur</option>
								<option value=1>Non-amateur</option>
							</select><br/>
						</div>
						<div class="form-group col-md-4">
							<label class="invisible">Submit</label>
							<input type="submit" value="Search" class="btn btn-primary form-control"/>
						</div>
					</form>
				</div>
			</div>

			<?php if(is_array($players) && count($players)>0) { ?>

				<div class="box">
                <div class="box-header with-border">
                  <h3 class="box-title">Search Results</h3>
                </div><!-- /.box-header -->
                <div class="box-body">
                  <table id="example2" class="table table-bordered table-hover">
                    <thead>
                      <tr>
                        <th>First Name</th>
                        <th>Middle Name</th>
                        <th>Last Name</th>
                        <th>GFA Licence Number</th>
                        <th>DOB</th>
                        <th>View</th>
                      </tr>
                    </thead>
                    <tbody>
                    <?php foreach($players as $player):?>
                      <tr>
                        <td><?php echo $player->first_name;?></td>
                        <td><?php echo $player->mid_name;?></td>
                        <td><?php echo $player->last_name;?></td>
                        <td><?php echo $player->gfa_lic_num;?></td>
                        <td><?php echo strftime("%d %b %Y",$player->dob);?></td>
                        <td><a href="playerView.php?id=<?php echo $player->id;?>">View</a></td>
                      </tr>
                      <?php endforeach;?>
                    </tbody>
                    <tfoot>
                      <tr>
                        <th>First Name</th>
                        <th>Middle Name</th>
                        <th>Last Name</th>
                        <th>GFA Licence Number</th>
                        <th>DOB</th>
                        <th>View</th>
                      </tr>
                    </tfoot>
                  </table>
                </div><!-- /.box-body -->
              </div><!-- /.box -->
            <?php } else if (count($conds)>0) { ?>
              	<div class="box box-primary">
	                <div class="box-header">
	                  <h3 class="box-title">Search Results</h3>
	                </div><!-- /.box-header -->
	                <div class="box-body">
						<div class="callout callout-danger">
		                    <h4>No Players Found</h4>
		                    <p>We could not find any players with the current search parameters. Try removing some search parameters.</p>
	                  	</div>
	                </div>
                </div>
            <?php } ?>
		</section>
		</div>

    	<?php include("footer.php"); ?>

    	</div><!-- ./wrapper -->
	</body>
</html>
