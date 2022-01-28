<?php

require_once('sql_details.php');	

if(!isset($_COOKIE['calc_id'])){
	//set cookie
	$set_calc_id = md5(uniqid(rand())); //sets set_calc_id to a random code
	setcookie('calc_id',$set_calc_id,time() + (86400 * 31),'/'); // 31 days
}else{
	setcookie('calc_id',$_COOKIE['calc_id'],time() + (86400 * 31),'/'); // 31 days	
}	


$result = mysqli_query($conn,"SELECT srtname FROM stat_calculator.weapons");

// Count how many items there are
$countSrt=mysqli_num_rows($result);
$countSrtEnd = "0";
$srtnameGet = "";
$srtFunc = "";
$srtValue = "";

while($row = mysqli_fetch_array($result)){
	$countSrtEnd ++;
	if ($countSrtEnd == $countSrt){
		//loop is at eg 25 of 25  
		$srtnameGet = $srtnameGet.$row['srtname']."=\"+".$row['srtname']."+\"&mobmembers=\"+mobmembers+\"&ammo=\"+toggle+\"&razor=\"+toggle1+\"&savestats=\"+savestats";	
		$srtFunc = 	$srtFunc.$row['srtname'].",mobmembers,toggle,toggle1,savestats";
		$srtValue = $srtValue.$row['srtname']."1.innerText,mobmembers1.value,toggle,toggle1";
	}else{
		$srtnameGet = $srtnameGet.$row['srtname']."=\"+".$row['srtname']."+\"&";
		$srtFunc = 	$srtFunc.$row['srtname'].",";
		$srtValue = $srtValue.$row['srtname']."1.innerText,";
	}
}//end loop

//echo $srtnameGet."<br/>";
//echo $srtFunc."<br/>";
//echo $srtValue;

mysqli_free_result($result);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
	<head>
		<title>TurfWars - Stat Calculator</title>
		<meta http-equiv="Content-Language" content="English" />
		<meta name="author" content="KramWell.com" />
		<meta name="Robots" content="index,follow" />
		<meta http-equiv="content-type" content="text/html; charset=windows-1252" />
		<link rel="shortcut icon" href="img/ico.ico" />

		<script type="text/javascript" src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

		<script type="text/javascript">
			function validated(string) 
			{
				for (var i=0, output='', valid='0123456789'; i<string.length; i++)
				if (valid.indexOf(string.charAt(i)) != -1)
				output += string.charAt(i)
				return output;
			} 

			function calcResult(<?php echo $srtFunc; ?>)
			{
			$.ajax({
				type: "POST",
				url: "stat_calculator.php",
				beforeSend: function () {
					$("#calcResult1").html("Loading Attack...<br/>Loading Defence...");
				},
				data: "<?php echo $srtnameGet; ?>,
				success: function(msg){
					$("#calcResult1").html(msg);
				}
			});
			} 

			<?php 
						
			$result = mysqli_query($conn,"SELECT weaponname,srtname,more FROM stat_calculator.weapons");

			echo "\nfunction show_prompt(str)\n{";

			while($row = mysqli_fetch_array($result)){
				echo "\nif (str == '".$row['srtname']."'){\nvar ".$row['srtname']."value=document.getElementById('".$row['srtname']."1').innerHTML;\nvar ".$row['srtname']."=prompt(\"How many ".$row['weaponname'].$row['more']." are there?\",".$row['srtname']."value);\nif (";
				echo $row['srtname']."!=null && ".$row['srtname']."!=\"\")\n{\n".$row['srtname']." = parseInt(".$row['srtname'].");\nif (isNaN(".$row['srtname']."))\n{\nalert('Please enter numbers only!');\n}\nelse{\ndocument.getElementById(\"".$row['srtname']."1\").innerHTML=";
				echo $row['srtname'].";\ndocument.getElementById('".$row['srtname']."').style.backgroundColor = '#D6D6C2';\ndocument.getElementById('".$row['srtname']."').style.borderColor = '#969688';\ncalcResult(".$srtValue.")\n}\n}\n}";
			}  
			 
			echo "\n}\n";
			mysqli_free_result($result);	
			?>

			toggle='off'
			toggle1='off'

			function ammo_toggle(){
				if (toggle=='off'){
					document.getElementById('ammo').style.backgroundImage="url('img/ammo1.png')";
					toggle='on';
					<?php echo "calcResult(".$srtValue.")" ?>
				}else{
					document.getElementById('ammo').style.backgroundImage="url('img/ammo.png')";
					toggle='off';
					<?php echo "calcResult(".$srtValue.")" ?>
				}
			}

			function razor_toggle(){
				if (toggle1=='off'){
					document.getElementById('razor').style.backgroundImage="url('img/razor_wire1.png')";
					toggle1='on';
					<?php echo "calcResult(".$srtValue.")" ?>
				}else {
					document.getElementById('razor').style.backgroundImage="url('img/razor_wire.png')";
					toggle1='off';
					<?php echo "calcResult(".$srtValue.")" ?>
				}
			}

			function deleteStats(strid){
				var s=confirm("Are you sure?");
				if (s==true){
					if (strid.length==0){ 
						alert("Please enter an id");
						return;
					}
					$.ajax({
						type: "POST",
						url: "stat_calculator.php",
						data: "delete="+strid,
						success: function(){
							window.location.href='./';
						}
					});
				}
			}
			function saveStats(){
				var savestats=prompt("please enter a name to save?",'');
				if (savestats!=null && savestats!=""){
					<?php echo "calcResult(".$srtValue.",savestats);" ?>
					var r=confirm("Stats have been saved! Click OK to refresh.");
					if (r==true){
						window.location.href='./';
					}
				}
			}

		</script>

		<style type="text/css">
			.borderit {
				border: 2px solid transparent;
			}
			.borderit:hover {
				border: 2px solid #969688;
			}
			.borderit:hover{
				color: black; /* irrelevant definition to overcome IE bug */
			}
			.inner {
				color:black;
				text-align:right;
				background-color:#D6D6C2;
			}
			.stats:hover { 
			background-color: #D6D6C2;
			}
			#contentCalc{ 
				text-align: left;
				width:864px;
				padding: 0 0 0 5px;
				float: left; 
			}  
			body{
				font: normal .80em 'trebuchet ms', arial, sans-serif;
				background: #F5F5EE;
				color: #555;
			}
			#site_content{ 
				width: 880px;
				margin: 20px auto 0 auto;
			}	 
		</style>
	</head>

	<body>
		<div id="main">
		
			<div id="site_content">
				<div id="content">
					   
				<h1>TurfWars - Stat Calculator</h1>
			  
				<div id="contentCalc">
					<table style='width:100%;background-image:url(img/tw.jpg);border: 2px solid #D6D6C2;'>
						<tr>
							<td><b>Name</b></td>	
							<td><b>Attack</b></td>
							<td><b>Defence</b></td>
							<td><b>Mob Members</b></td>
							<td><b>Saved</b></td>
							<td>&nbsp;</td>
						</tr>

						<?php	
							
							if(isset($_COOKIE['calc_id']))	{
								echo "<tr>";	
									
								$calc_id = check_val($_COOKIE['calc_id']);
									
								//here is where i have to loop the mysql to find the results2
								$result = mysqli_query($conn,"SELECT * FROM stat_calculator.saveresults WHERE id = '".$calc_id."' ");
								while($row = mysqli_fetch_array($result)){
									echo "<tr class='stats'>";
									echo "<td>".$row['name']."</td>";
									echo "<td>".$row['attack']."</td>";
									echo "<td>".$row['defence']."</td>";
									echo "<td>".$row['mobmembers']."</td>";
									echo "<td>".date('F d ~ h:i A T',$row['datestamp'])."</td>";
									echo "<td><a href='?edit=".$row['showit']."'><img alt='Edit' title='Edit' onclick='editStats()' longdesc='Edit' src='img/edit-edit.png' /></a>&nbsp;&nbsp;&nbsp;<img style='cursor:pointer;' alt='Delete' onclick='deleteStats(this.id)' id='".$row['showit']."' title='Delete' longdesc='Delete' src='edit-delete.gif' /></td>";
									echo "</tr>";
								}
								mysqli_free_result($result);	
								echo "</tr>";

							}//end if isset calc_id
						?>	
					</table>	
							
					<p>&nbsp;</p>
					<table style="width: 100%;border:1px solid #D6D6C2;background:#F3F3ED">
						<tr>
							<td style="width: 370px" >
								&nbsp;&nbsp;&nbsp;Mob Members:&nbsp;<input onchange='this.value=validated(this.value)' onkeypress='this.value=validated(this.value)' id="mobmembers1" type="text"/>			
								<input name="btnGo" onclick='<?php echo "calcResult(".$srtValue.")" ?>' type="button" value="  Go!  " />
							</td>
							<td>
								<span id="calcResult1">Total Attack: <strong>0</strong><br/>Total Defence: 
								<strong>0</strong></span>
							</td>

							<td align="right">		
								<input name="Button1" onclick="saveStats()" type="button" value=" Save " />
								&nbsp;&nbsp;
								<input name="Button2" onclick="window.location.href='./'" type="button" value=" Refresh " />
							</td>
						</tr>
					</table>

					<?php 
						//split after 6 rows
						$result = mysqli_query($conn,"SELECT * FROM stat_calculator.weapons");
						echo "<table style='width:100%;background-image:url(img/tw.jpg);border: 2px solid #D6D6C2;'><tr>";
						$calcNum = "0";
						while($row = mysqli_fetch_array($result)){
							//echo $row['srtname'];
							if ($row['srtname'] <> "bazooka"){
								$calcNum ++;
								if ($calcNum > "6"){
									echo "</tr><tr>";
									$calcNum = "1";
								} 
								echo "<td align='right' class='borderit' onclick=\"show_prompt(this.id);\" title='".$row['weaponname']."\nAtt: (".$row['attack'].") Def: (".$row['defence'].")' id='".$row['srtname']."' style=\"background-image:url('img/".$row['srtname'].".png'); width:120px;height:90px;background-repeat:no-repeat;\"><br/><br/><br/><b><span class='inner' id='".$row['srtname']."1'></span></b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>";
							}
							//here
						}
						echo "</tr></table>";
						mysqli_free_result($result);	
					?>
					
					<p>&nbsp;</p>
					<h3>Additional items</h3> 
					<table style="width:100%;background-image:url('img/tw.jpg');border: 2px solid #D6D6C2;">
						<tr>
							<td align='right' class='borderit' onclick="show_prompt(this.id);" title='Bazooka Att: (40) Def: (0)' id='bazooka' style="background-image:url('img/bazooka.png');width:120px;height:90px;background-repeat:no-repeat;">
								<br/><br/><br/><b><span class='inner' id='bazooka1'></span></b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							</td>
												
							<td class='borderit' onclick="razor_toggle()" id="razor" title='Razor Wire, adds 16 defence points to total. Click to toggle' style="background-image:url('img/razor_wire.png');background-repeat:no-repeat;width:120px;height:90px;">
								&nbsp;
							</td>

							<td class='borderit' onclick="ammo_toggle()" id="ammo" title='Ammo, adds 10% to total attack score. Click to toggle' style="background-image:url('img/ammo.png');background-repeat:no-repeat;width:120px;height:90px;">
								&nbsp;
							</td>

							<td style="width:120px;height:90px;">&nbsp;</td>
							<td style="width:120px;height:90px;">&nbsp;</td>
							<td style="width:120px;height:90px;">&nbsp;</td>
						</tr>
					</table>
					
					<p>Add Bazooka and/or Razor Wire to work out the added attack and defence when battling turfs,<br/>You can also toggle Ammo to add 10% to total attack score!</p>
					
					<p style="text-align:center;">KramWell.com</p>
					
				</div> 

			</div>

		</div>
	</body>
</html>

<?php
if (isset($_GET['edit'])){
	
	$editstat = check_val($_GET['edit']);	
	$calc_id = check_val($_COOKIE['calc_id']);

	$result = mysqli_query($conn,"SELECT * FROM stat_calculator.saveresults WHERE showit = '$editstat'");
	$statResult = mysqli_fetch_assoc($result);

	if (mysqli_num_rows($result) == '1'){
		if ($statResult['id'] == $calc_id){
			//here we need to grab the weaponlist, cut and display.
			$splitStats = explode(",", $statResult['weaponlist']);
			$resultStats = count($splitStats);

			echo "<script type='text/javascript'>";
			for ($i=0; $i<$resultStats; $i++){
				//this breaks down the value into kevlarvest:3434, now we need to split again and place into 
				//echo "document.getElementById('riotshield1').innerHTML='9090';";

				//here we insert the name
				list($first, $middle) = explode(':', $splitStats[$i]);

				if ($middle == '' OR $middle == '0'){}else{
					echo "document.getElementById('".$first."1').innerHTML='".$middle."';";
					echo "document.getElementById('".$first."').style.backgroundColor = '#D6D6C2';";
					echo "document.getElementById('".$first."').style.borderColor = '#969688';";
				}//end middle
			}//end for i++						
			echo "document.getElementById('mobmembers1').value = '".$statResult['mobmembers']."';";
			echo "calcResult(".$srtValue.");";
			echo "</script>";
		}//end if id=cookie
	}//end isset
	mysqli_free_result($result);	  
  
}//end isset edit

function check_val($str) {
	$str = preg_replace("/[^A-Za-z0-9 ]/", '', $str);
	$strLength1 = strlen($str);
	if ($strLength1 == '32'){
		return $str;
	}else{
		exit();
	}
}
//32
//letters and numbers
?>