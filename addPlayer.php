<?php
	require_once("includes/init.php");
	$villages = Village::find_all();
	$teams = Team::find_all();
	if(sizeof($_POST)>0){
		$player = new Player();
		$player->get_values();
		$player->dob = strtotime($player->dob);
		$player->save();
		if($player->category==1){
			$contract = new Contract();
			$contract->set($player->id, strtotime($_POST['contFrom']), strtotime($_POST['contTo']));
			$contract->save();
			$ins = new Insurance();
			$ins->set($player->id, strtotime($_POST['insTo']));
			$ins->save();
			if($_POST['visaTo']!=''){
				$visa = new Visa();
				$visa->set($player->id, strtotime($_POST['visaTo']));
				$visa->save();
			}
		}
		$files = File::get_files();
		if($files[0]!=NULL){
			$files[0]->name="Player".$player->id;
			$files[0]->save_file_in(UPLOAD_DIR."images/photos/");
		}
	}
?>
<html>
	<head>
		<link rel="stylesheet" type="text/css" media="all" href="jsDatePick_ltr.min.css" />
		<script type="text/javascript" src="js/jsDatePick.min.1.3.js"></script>
		<script type="text/javascript">
			window.onload = function(){
				new JsDatePick({useMode:2,target:"dob",dateFormat:"%d %M %Y"});
				new JsDatePick({useMode:2,target:"contractFrom",dateFormat:"%d %M %Y"});
				new JsDatePick({useMode:2,target:"contractTo",dateFormat:"%d %M %Y"});
				new JsDatePick({useMode:2,target:"visaTo",dateFormat:"%d %M %Y"});
				new JsDatePick({useMode:2,target:"insTo",dateFormat:"%d %M %Y"});
			};
		</script>
	</head>
	<body>
		<form method="POST" enctype="multipart/form-data">
			First Name* <input type="text" name="first_name" id="first_name" required/><br/>
			Middle Name* <input type="text" name="mid_name" id="mid_name" required/><br/>
			Last Name* <input type="text" name="last_name" id="last_name" required/><br/>
			<div id="similarNames"></div>
			Photo <input type="file" name="photo" accept="image/*"/><br/>
			GFA License no.* <input type="text" name="gfa_lic_num" required/><br/>
			Nationality* <input type="text" name="nat" required/><br/>
			Date of Birth <input type="text" name="dob" id="dob" autocomplete="off"/><br/>
			Address* <textarea name="address" required></textarea><br/>
			Village* <select name="village">
			<?php if(is_array($villages))
					foreach($villages as $village): ?>
						<option value="<?php echo $village->id;?>"><?php echo $village->name;?> </option>
					<?php endforeach;?>
			</select><br/>
			Team* <select name="team">
			<?php if(is_array($teams))
					foreach($teams as $team): ?>
						<option value="<?php echo $team->id;?>"><?php echo $team->name;?> </option>
					<?php endforeach;?>
			</select><br/>
			Telephone (residence) <input type="text" name="ph_res"/><br/>
			Telephone (office) <input type="text" name="ph_off"/><br/>
			Mobile number <input type="text" name="mob"/><br/>
			Email <input type="email" name="email" required/><br/>
			Category 
			<select id="category" name="category">
				<option value=0>Amateur</option>
				<option value=1>Non-amateur</option>
			</select><br/>
			<div id="nonAmat" style="display:none">
				Date contract registered <input type="text" id="contractFrom" name="contFrom"/><br/>
				Contract valid until <input type="text" id="contractTo" name="contTo"/><br/>
				Visa for stay in India valid upto <input type="text" id="visaTo" name="visaTo"/><br/>
				Insurance valid upto <input type="text" id="insTo" name="insTo"/><br/>
				<input type="checkbox" id="med"/><label for="med">Medical certificate submitted</label><br/>
			</div>
			<input type="submit" value="Register"/>
		</form>
	</body>
	<script>
		document.getElementById("category").onchange = function() {
			if(this.value==1){
				document.getElementById("nonAmat").style.display = "block";
				document.getElementById("contractFrom").required = true;
				document.getElementById("contractTo").required = true;
				document.getElementById("insTo").required = true;
				document.getElementById("med").required = true;
			}
			else{
				document.getElementById("nonAmat").style.display = "none";
				document.getElementById("contractFrom").required = false;
				document.getElementById("contractTo").required = false;
				document.getElementById("insTo").required = false;
				document.getElementById("med").required = false;
			}
		}
		function check(){
			fname = document.getElementById("first_name").value;
			mname = document.getElementById("mid_name").value;
			lname = document.getElementById("last_name").value;
			if(fname!='' && mname!='' && lname!=''){
				var xhrName = new XMLHttpRequest();
				xhrName.open("POST","ajax/checkNames.php",true);
				xhrName.setRequestHeader("content-type","application/x-www-form-urlencoded");
				xhrName.onreadystatechange = function(){
					if(this.readyState==4 && this.status==200){
						var nameDiv = document.getElementById("similarNames");
						while (nameDiv.firstChild)
 							nameDiv.removeChild(nameDiv.firstChild);
 						var resp = JSON.parse(this.responseText);
 						for(i=0;i<resp.length;i++){
 							var newNode = document.createElement("div");
 							var p = document.createElement("div");
 							p.innerHTML = resp[i].first_name;
 							newNode.appendChild(p);
 							p = document.createElement("div");
 							p.innerHTML = resp[i].mid_name;
 							newNode.appendChild(p);
 							p = document.createElement("div");
 							p.innerHTML = resp[i].last_name;
 							newNode.appendChild(p);
 							p = document.createElement("div");
 							p.innerHTML = resp[i].gfa_lic_num;
 							newNode.appendChild(p);
 							p = document.createElement("a");
 							p.innerHTML = "View";
 							p.href = "playerView.php?id="+resp[i].id;
 							p.target="__blank";
 							newNode.appendChild(p);
 							nameDiv.appendChild(newNode);
 						}
					}
				}
				var nameDiv = document.getElementById("similarNames");
				while (nameDiv.firstChild)
 					nameDiv.removeChild(nameDiv.firstChild);
 				var p = document.createElement("div");
 				p.innerHTML = "Checking for similar names...";
 				nameDiv.appendChild(p);
 				xhrName.send("fname="+fname+"&mname="+mname+"&lname="+lname);
			}
		}
		document.getElementById("first_name").onblur = check;
		document.getElementById("mid_name").onblur = check;
		document.getElementById("last_name").onblur = check;
	</script>
</html>
