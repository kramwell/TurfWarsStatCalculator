<?php 
/*
#################################################################################################
-----------------------------------STAT CALC - v0.64  - 03/JAN/2012-----------support@turfwarsUK.com
  updated for regular-users to save          - v0.65  - 03/MAR/2012
  updated for two new LOOT items			 - v0.66  - 04/MAR/2012
  added format 1,000,000 |GET > REQUEST      - v0.67  - 29/OCT/2012
  mysql querys, clean function replaced      - v0.68  - 08/AUG/2014
  moved hosting providers, bug fixes         - v0.69  - 15/AUG/2021
  github release, cleaned up codebase	   	 - v0.70  - 17/JAN/2022
@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@

#I know there is a way to loop each section and slim the code down by 90%, will be nice to re-visit that at some point for neatness.

to add new LOOT, first update mysqli database with item, and add the $GET peramerters below.
'whatever category' you have, look for the correct section below, copy and paste the existing code snipppet.
fill in 5 changes with the srtname. and remember to do both attack and defence based on the category.
TIP: look for 'INPUT NEW LOOT HERE' and paste under correct section.
Update--- select the $totalSave and add new loot names. for saving results in db
*/

require_once('sql_details.php');	

//if user has selected to delete a saved result
if (isset($_POST['delete'])){
	
	$deletestat = check_val($_POST['delete']);

	$result = mysqli_query($conn,"SELECT showit FROM stat_calculator.saveresults WHERE showit = '$deletestat'");

	if (mysqli_num_rows($result) == '1'){
		mysqli_query($conn,"DELETE FROM stat_calculator.saveresults WHERE showit = '$deletestat'");
	}
	mysqli_free_result($result);	  
	exit();
}
	
$riotshield = only_num($_POST['riotshield']);
$kevlarvest = only_num($_POST['kevlarvest']);
$bodyarmor = only_num($_POST['bodyarmor']);
$kevlarlined = only_num($_POST['kevlarlined']);
$shank = only_num($_POST['shank']);
$brass = only_num($_POST['brass']);
$saturday = only_num($_POST['saturday']);
$german = only_num($_POST['german']);
$handgun = only_num($_POST['handgun']);
$chainsaw = only_num($_POST['chainsaw']);
$galesi = only_num($_POST['galesi']);
$molotov = only_num($_POST['molotov']);
$magnum = only_num($_POST['magnum']);
$grenade = only_num($_POST['grenade']);
$ak47 = only_num($_POST['ak47']);
$shotgun = only_num($_POST['shotgun']);
$glock = only_num($_POST['glock']);
$xm400 = only_num($_POST['xm400']);
$rpg = only_num($_POST['rpg']);
$tommygun = only_num($_POST['tommygun']);
$lapua = only_num($_POST['lapua']);
$ar15 = only_num($_POST['ar15']);
$garrote = only_num($_POST['garrote']);
$cleaver = only_num($_POST['cleaver']);
$steeltoed = only_num($_POST['steeltoed']);
$lupara = only_num($_POST['lupara']);
$machete = only_num($_POST['machete']);
$bazooka = only_num($_POST['bazooka']);
$brengun = only_num($_POST['brengun']);
$slugger = only_num($_POST['slugger']);
$mobCount = only_num($_POST['mobmembers']);
$beretta = only_num($_POST['beretta']);
$potatomasher = only_num($_POST['potatomasher']);

$ammo = toggle($_POST['ammo']); //on:off
$razor = toggle($_POST['razor']);

$savestats = check_name($_POST['savestats']);	

$mobCountTemp = $mobCount;
$totalMobCount = $mobCount;
$totalAttackM = "0";
$totalDefenceM = "0";
$totalAttackW = "0";
$totalDefenceW = "0";
$totalAttackA = "0";
$totalDefenceA = "0";

function only_num($str) {
	if (is_numeric($str)) {
		return $str;
	#added this for php7 compat
	}elseif ($str == ''){
		return 0;
	#end add
	}else{	
		exit();
	}
}
function toggle($str) {
	if ($str == 'on' OR $str == 'off') {
		return $str;
	}else{
		exit();
	}
}	

function check_val($str) {
	$str = preg_replace("/[^A-Za-z0-9 ]/", '', $str);
	$strLength1 = strlen($str);
	if ($strLength1 == '32'){
		return $str;
	}else{
		exit();
	}
}

function check_name($str) {
	$str = preg_replace("/[^A-Za-z0-9 ]/", '', $str);
	$strLength1 = strlen($str);
	if ($strLength1 < '32'){
		return $str;
	}else{
		exit();
	}
}

//-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-**************************=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-
//-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-         MELEE ATTACK       =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-
//-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-**************************=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-

//we need to loop through the melee attack first
$result = mysqli_query($conn,"SELECT srtname,attack FROM stat_calculator.weapons WHERE type = 'melee' ORDER BY attack DESC");
while($row = mysqli_fetch_array($result)){

	//if the name with the highest attack value matches 'machete'
	if ($row['srtname'] == 'machete'){
		//if the amount of mobmembers is more than the amount of machete's
		if ($mobCount > $machete){
			//totalAttackM becomes the attack value times by the amount of machete's
			$totalAttackM = $totalAttackM + ($row['attack'] * $machete);
			//take away value from mob count for next weapon
			$mobCount = $mobCount - $machete;
			//if the amount of mobmembers is LESS than the amount of machete's
		}else{
			//we only take the mobcount that is left and add to totalAttackM
			$totalAttackM = $totalAttackM + ($row['attack'] * $mobCount);
			//echo "mobvalue is now nothing so breaks loop";
			break;
		}
	}//end if - machete

	if ($row['srtname'] == 'slugger'){
		if ($mobCount > $slugger){
			$totalAttackM = $totalAttackM + ($row['attack'] * $slugger);
			$mobCount = $mobCount - $slugger; 
		}else{
			$totalAttackM = $totalAttackM + ($row['attack'] * $mobCount);
			break;
		}
	}//end if - slugger

	if ($row['srtname'] == 'chainsaw'){
		if ($mobCount > $chainsaw){
			$totalAttackM = $totalAttackM + ($row['attack'] * $chainsaw);
			$mobCount = $mobCount - $chainsaw;
		}else{
			$totalAttackM = $totalAttackM + ($row['attack'] * $mobCount);
			break;
		}
	}//end if - slugger

	if ($row['srtname'] == 'steeltoed'){
		if ($mobCount > $steeltoed){
			$totalAttackM = $totalAttackM + ($row['attack'] * $steeltoed);
			$mobCount = $mobCount - $steeltoed;
		}else{
			$totalAttackM = $totalAttackM + ($row['attack'] * $mobCount);
			break;
		}
	}//end if - slugger

	if ($row['srtname'] == 'german'){
		if ($mobCount > $german){
			$totalAttackM = $totalAttackM + ($row['attack'] * $german);
			$mobCount = $mobCount - $german;
		}else{
			$totalAttackM = $totalAttackM + ($row['attack'] * $mobCount);
			break;
		}
	}//end if - slugger

	if ($row['srtname'] == 'garrote'){
		if ($mobCount > $garrote){
			$totalAttackM = $totalAttackM + ($row['attack'] * $garrote);
			$mobCount = $mobCount - $garrote;
		}else{
			$totalAttackM = $totalAttackM + ($row['attack'] * $mobCount);
			break;
		}
	}//end if - slugger

	if ($row['srtname'] == 'cleaver'){
		if ($mobCount > $cleaver){
			$totalAttackM = $totalAttackM + ($row['attack'] * $cleaver);
			$mobCount = $mobCount - $cleaver;
		}else{
			$totalAttackM = $totalAttackM + ($row['attack'] * $mobCount);
			break;
		}
	}//end if - cleaver

	if ($row['srtname'] == 'shank'){
		if ($mobCount > $shank){
			$totalAttackM = $totalAttackM + ($row['attack'] * $shank);
			$mobCount = $mobCount - $shank;
		}else{
			$totalAttackM = $totalAttackM + ($row['attack'] * $mobCount);
			break;
		}
	}//end if - shank

	if ($row['srtname'] == 'brass'){
		if ($mobCount > $brass){
			$totalAttackM = $totalAttackM + ($row['attack'] * $brass);
			$mobCount = $mobCount - $brass;
		}else{
			$totalAttackM = $totalAttackM + ($row['attack'] * $mobCount);
			break;
		}
	}//end if - brass

	//echo $row['srtname']. "<br/>";

	//INPUT NEW LOOT HERE

}  
mysqli_free_result($result);	

//$totalAttackM now holds value to total Attack of Melee.;

//-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-**************************=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-
//-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-       MELEE DEFENCE      =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-
//-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-**************************=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-
$mobCount = $mobCountTemp;

//we need to loop through the melee defence first
$result = mysqli_query($conn,"SELECT srtname,defence FROM stat_calculator.weapons WHERE type = 'melee' ORDER BY defence DESC");
while($row = mysqli_fetch_array($result)){

	if ($row['srtname'] == 'chainsaw'){
		if ($mobCount > $chainsaw){
			$totalDefenceM = $totalDefenceM + ($row['defence'] * $chainsaw);
			$mobCount = $mobCount - $chainsaw;
		}else{
			$totalDefenceM = $totalDefenceM + ($row['defence'] * $mobCount);
			break;
		}
	}//end if - chainsaw

	if ($row['srtname'] == 'slugger'){
		if ($mobCount > $slugger){
			$totalDefenceM = $totalDefenceM + ($row['defence'] * $slugger);
			$mobCount = $mobCount - $slugger;
		}else{
			$totalDefenceM = $totalDefenceM + ($row['defence'] * $mobCount);
			break;
		}
	}//end if - slugger

	if ($row['srtname'] == 'steeltoed'){
		if ($mobCount > $steeltoed){
			$totalDefenceM = $totalDefenceM + ($row['defence'] * $steeltoed);
			$mobCount = $mobCount - $steeltoed;
		}else{
			$totalDefenceM = $totalDefenceM + ($row['defence'] * $mobCount);
			break;
		}
	}//end if - steeltoed

	if ($row['srtname'] == 'machete'){
		if ($mobCount > $machete){
			$totalDefenceM = $totalDefenceM + ($row['defence'] * $machete);
			$mobCount = $mobCount - $machete;
		}else{
			$totalDefenceM = $totalDefenceM + ($row['defence'] * $mobCount);
			break;
		}
	}//end if - machete

	if ($row['srtname'] == 'brass'){
		if ($mobCount > $brass){
			$totalDefenceM = $totalDefenceM + ($row['defence'] * $brass);
			$mobCount = $mobCount - $brass;
		}else{
			$totalDefenceM = $totalDefenceM + ($row['defence'] * $mobCount);
			break;
		}
	}//end if - brass

	if ($row['srtname'] == 'german'){
		if ($mobCount > $german){
			$totalDefenceM = $totalDefenceM + ($row['defence'] * $german);
			$mobCount = $mobCount - $german;
		}else{
			$totalDefenceM = $totalDefenceM + ($row['defence'] * $mobCount);
			break;
		}
	}//end if - german

	if ($row['srtname'] == 'garrote'){
		if ($mobCount > $garrote){
			$totalDefenceM = $totalDefenceM + ($row['defence'] * $garrote);
			$mobCount = $mobCount - $garrote;
		}else{
			$totalDefenceM = $totalDefenceM + ($row['defence'] * $mobCount);
			break;
		}
	}//end if - garrote

	if ($row['srtname'] == 'cleaver'){
		if ($mobCount > $cleaver){
			$totalDefenceM = $totalDefenceM + ($row['defence'] * $cleaver);
			$mobCount = $mobCount - $cleaver;
		}else{
			$totalDefenceM = $totalDefenceM + ($row['defence'] * $mobCount);
			break;
		}
	}//end if - cleaver

	//echo $row['srtname']. "<br/>";

	//INPUT NEW LOOT HERE

}  
mysqli_free_result($result);	

//$totalDefenceM now holds value to total Defence of Melee.;



//-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-**************************=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-
//-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-        WEAPON ATTACK     =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-
//-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-**************************=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-
$mobCount = $mobCountTemp;

//we need to loop through the WEAPON attack first
$result = mysqli_query($conn,"SELECT srtname,attack FROM stat_calculator.weapons WHERE type = 'weapon' ORDER BY attack DESC");
while($row = mysqli_fetch_array($result)){

	if ($row['srtname'] == 'brengun'){
		if ($mobCount > $brengun){
			$totalAttackW = $totalAttackW + ($row['attack'] * $brengun);
			$mobCount = $mobCount - $brengun;
		}else{
			$totalAttackW = $totalAttackW + ($row['attack'] * $mobCount);
			break;
		}
	}//end if - brengun

	if ($row['srtname'] == 'bazooka'){
		if ($mobCount > $bazooka){
			$totalAttackW = $totalAttackW + ($row['attack'] * $bazooka);
			$mobCount = $mobCount - $bazooka;
		}else{
			$totalAttackW = $totalAttackW + ($row['attack'] * $mobCount);
			break;
		}
	}//end if - bazooka

	if ($row['srtname'] == 'ar15'){
		if ($mobCount > $ar15){
			$totalAttackW = $totalAttackW + ($row['attack'] * $ar15);
			$mobCount = $mobCount - $ar15;
		}else{
			$totalAttackW = $totalAttackW + ($row['attack'] * $mobCount);
			break;
		}
	}//end if - ar15

	if ($row['srtname'] == 'tommygun'){
		if ($mobCount > $tommygun){
			$totalAttackW = $totalAttackW + ($row['attack'] * $tommygun);
			$mobCount = $mobCount - $tommygun;
		}else{
			$totalAttackW = $totalAttackW + ($row['attack'] * $mobCount);
			break;
		}
	}//end if - tommygun

	if ($row['srtname'] == 'rpg'){
		if ($mobCount > $rpg){
			$totalAttackW = $totalAttackW + ($row['attack'] * $rpg);
			$mobCount = $mobCount - $rpg;
		}else{
			$totalAttackW = $totalAttackW + ($row['attack'] * $mobCount);
			break;
		}
	}//end if - rpg

	if ($row['srtname'] == 'lupara'){
		if ($mobCount > $lupara){
			$totalAttackW = $totalAttackW + ($row['attack'] * $lupara);
			$mobCount = $mobCount - $lupara;
		}else{
			$totalAttackW = $totalAttackW + ($row['attack'] * $mobCount);
			break;
		}
	}//end if - lupara

	if ($row['srtname'] == 'lapua'){
		if ($mobCount > $lapua){
			$totalAttackW = $totalAttackW + ($row['attack'] * $lapua);
			$mobCount = $mobCount - $lapua;
		}else{
			$totalAttackW = $totalAttackW + ($row['attack'] * $mobCount);
			break;
		}
	}//end if - lapua

	if ($row['srtname'] == 'xm400'){
		if ($mobCount > $xm400){
			$totalAttackW = $totalAttackW + ($row['attack'] * $xm400);
			$mobCount = $mobCount - $xm400;
		}else{
			$totalAttackW = $totalAttackW + ($row['attack'] * $mobCount);
			break;
		}
	}//end if - xm400

	if ($row['srtname'] == 'ak47'){
		if ($mobCount > $ak47){
			$totalAttackW = $totalAttackW + ($row['attack'] * $ak47);
			$mobCount = $mobCount - $ak47;
		}else{
			$totalAttackW = $totalAttackW + ($row['attack'] * $mobCount);
			break;
		}
	}//end if - ak47

	if ($row['srtname'] == 'glock'){
		if ($mobCount > $glock){
			$totalAttackW = $totalAttackW + ($row['attack'] * $glock);
			$mobCount = $mobCount - $glock;
		}else{
			$totalAttackW = $totalAttackW + ($row['attack'] * $mobCount);
			break;
		}
	}//end if - glock

	if ($row['srtname'] == 'shotgun'){
		if ($mobCount > $shotgun){
			$totalAttackW = $totalAttackW + ($row['attack'] * $shotgun);
			$mobCount = $mobCount - $shotgun;
		}else{
			$totalAttackW = $totalAttackW + ($row['attack'] * $mobCount);
			break;
		}
	}//end if - shotgun

	if ($row['srtname'] == 'grenade'){
		if ($mobCount > $grenade){
			$totalAttackW = $totalAttackW + ($row['attack'] * $grenade);
			$mobCount = $mobCount - $grenade;
		}else{
			$totalAttackW = $totalAttackW + ($row['attack'] * $mobCount);
			break;
		}
	}//end if - grenade

	if ($row['srtname'] == 'galesi'){
		if ($mobCount > $galesi){
			$totalAttackW = $totalAttackW + ($row['attack'] * $galesi);
			$mobCount = $mobCount - $galesi;
		}else{
			$totalAttackW = $totalAttackW + ($row['attack'] * $mobCount);
			break;
		}
	}//end if - galesi

	if ($row['srtname'] == 'molotov'){
		if ($mobCount > $molotov){
			$totalAttackW = $totalAttackW + ($row['attack'] * $molotov);
			$mobCount = $mobCount - $molotov;
		}else{
			$totalAttackW = $totalAttackW + ($row['attack'] * $mobCount);
			break;
		}
	}//end if - molotov

	if ($row['srtname'] == 'magnum'){
		if ($mobCount > $magnum){
			$totalAttackW = $totalAttackW + ($row['attack'] * $magnum);
			$mobCount = $mobCount - $magnum;
		}else{
			$totalAttackW = $totalAttackW + ($row['attack'] * $mobCount);
			break;
		}
	}//end if - magnum

	if ($row['srtname'] == 'handgun'){
		if ($mobCount > $handgun){
			$totalAttackW = $totalAttackW + ($row['attack'] * $handgun);
			$mobCount = $mobCount - $handgun;
		}else{
			$totalAttackW = $totalAttackW + ($row['attack'] * $mobCount);
			break;
		}
	}//end if - handgun

	if ($row['srtname'] == 'saturday'){
		if ($mobCount > $saturday){
			$totalAttackW = $totalAttackW + ($row['attack'] * $saturday);
			$mobCount = $mobCount - $saturday;
		}else{
			$totalAttackW = $totalAttackW + ($row['attack'] * $mobCount);
			break;
		}
	}//end if - saturday

	if ($row['srtname'] == 'beretta'){
		if ($mobCount > $beretta){
			$totalAttackW = $totalAttackW + ($row['attack'] * $beretta);
			$mobCount = $mobCount - $beretta;
		}else{
			$totalAttackW = $totalAttackW + ($row['attack'] * $mobCount);
			break;
		}
	}//end if - beretta

	if ($row['srtname'] == 'potatomasher'){
		if ($mobCount > $potatomasher){
			$totalAttackW = $totalAttackW + ($row['attack'] * $potatomasher);
			$mobCount = $mobCount - $potatomasher;
		}else{
			$totalAttackW = $totalAttackW + ($row['attack'] * $mobCount);
			break;
		}
	}//end if - potatomasher


	//echo $row['srtname']. "<br/>";

	//INPUT NEW LOOT HERE

}  
mysqli_free_result($result);	

//$totalAttackW now holds value to total Attack of Weapon.;


//-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-**************************=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-
//-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-        WEAPON DEFENCE     =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-
//-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-**************************=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-
$mobCount = $mobCountTemp;

//we need to loop through the WEAPON DEFENCE first
$result = mysqli_query($conn,"SELECT srtname,defence FROM stat_calculator.weapons WHERE type = 'weapon' ORDER BY defence DESC");
while($row = mysqli_fetch_array($result)){

	if ($row['srtname'] == 'brengun'){
		if ($mobCount > $brengun){
			$totalDefenceW = $totalDefenceW + ($row['defence'] * $brengun);
			$mobCount = $mobCount - $brengun;
		}else{
			$totalDefenceW = $totalDefenceW + ($row['defence'] * $mobCount);
			break;
		}
	}//end if - brengun

	if ($row['srtname'] == 'lupara'){
		if ($mobCount > $lupara){
			$totalDefenceW = $totalDefenceW + ($row['defence'] * $lupara);
			$mobCount = $mobCount - $lupara;
		}else{
			$totalDefenceW = $totalDefenceW + ($row['defence'] * $mobCount);
			break;
		}
	}//end if - lupara

	if ($row['srtname'] == 'lapua'){
		if ($mobCount > $lapua){
			$totalDefenceW = $totalDefenceW + ($row['defence'] * $lapua);
			$mobCount = $mobCount - $lapua;
		}else{
			$totalDefenceW = $totalDefenceW + ($row['defence'] * $mobCount);
			break;
		}
	}//end if - lapua

	if ($row['srtname'] == 'xm400'){
		if ($mobCount > $xm400){
			$totalDefenceW = $totalDefenceW + ($row['defence'] * $xm400);
			$mobCount = $mobCount - $xm400;
		}else{
			$totalDefenceW = $totalDefenceW + ($row['defence'] * $mobCount);
			break;
		}
	}//end if - xm400

	if ($row['srtname'] == 'ar15'){
		if ($mobCount > $ar15){
			$totalDefenceW = $totalDefenceW + ($row['defence'] * $ar15);
			$mobCount = $mobCount - $ar15;
		}else{
			$totalDefenceW = $totalDefenceW + ($row['defence'] * $mobCount);
			break;
		}
	}//end if - ar15

	if ($row['srtname'] == 'rpg'){
		if ($mobCount > $rpg){
			$totalDefenceW = $totalDefenceW + ($row['defence'] * $rpg);
			$mobCount = $mobCount - $rpg;
		}else{
			$totalDefenceW = $totalDefenceW + ($row['defence'] * $mobCount);
			break;
		}
	}//end if - rpg

	if ($row['srtname'] == 'shotgun'){
		if ($mobCount > $shotgun){
			$totalDefenceW = $totalDefenceW + ($row['defence'] * $shotgun);
			$mobCount = $mobCount - $shotgun;
		}else{
			$totalDefenceW = $totalDefenceW + ($row['defence'] * $mobCount);
			break;
		}
	}//end if - shotgun

	if ($row['srtname'] == 'tommygun'){
		if ($mobCount > $tommygun){
			$totalDefenceW = $totalDefenceW + ($row['defence'] * $tommygun);
			$mobCount = $mobCount - $tommygun;
		}else{
			$totalDefenceW = $totalDefenceW + ($row['defence'] * $mobCount);
			break;
		}
	}//end if - tommygun

	if ($row['srtname'] == 'ak47'){
		if ($mobCount > $ak47){
			$totalDefenceW = $totalDefenceW + ($row['defence'] * $ak47);
			$mobCount = $mobCount - $ak47;
		}else{
			$totalDefenceW = $totalDefenceW + ($row['defence'] * $mobCount);
			break;
		}
	}//end if - ak47

	if ($row['srtname'] == 'glock'){
		if ($mobCount > $glock){
			$totalDefenceW = $totalDefenceW + ($row['defence'] * $glock);
			$mobCount = $mobCount - $glock;
		}else{
			$totalDefenceW = $totalDefenceW + ($row['defence'] * $mobCount);
			break;
		}
	}//end if - glock

	if ($row['srtname'] == 'galesi'){
		if ($mobCount > $galesi){
			$totalDefenceW = $totalDefenceW + ($row['defence'] * $galesi);
			$mobCount = $mobCount - $galesi;
		}else{
			$totalDefenceW = $totalDefenceW + ($row['defence'] * $mobCount);
			break;
		}
	}//end if - galesi

	if ($row['srtname'] == 'grenade'){
		if ($mobCount > $grenade){
			$totalDefenceW = $totalDefenceW + ($row['defence'] * $grenade);
			$mobCount = $mobCount - $grenade;
		}else{
			$totalDefenceW = $totalDefenceW + ($row['defence'] * $mobCount);
			break;
		}
	}//end if - grenade

	if ($row['srtname'] == 'magnum'){
		if ($mobCount > $magnum){
			$totalDefenceW = $totalDefenceW + ($row['defence'] * $magnum);
			$mobCount = $mobCount - $magnum;
		}else{
			$totalDefenceW = $totalDefenceW + ($row['defence'] * $mobCount);
			break;
		}
	}//end if - magnum

	if ($row['srtname'] == 'molotov'){
		if ($mobCount > $molotov){
			$totalDefenceW = $totalDefenceW + ($row['defence'] * $molotov);
			$mobCount = $mobCount - $molotov;
		}else{
			$totalDefenceW = $totalDefenceW + ($row['defence'] * $mobCount);
			break;
		}
	}//end if - molotov

	if ($row['srtname'] == 'handgun'){
		if ($mobCount > $handgun){
			$totalDefenceW = $totalDefenceW + ($row['defence'] * $handgun);
			$mobCount = $mobCount - $handgun;
		}else{
			$totalDefenceW = $totalDefenceW + ($row['defence'] * $mobCount);
			break;
		}
	}//end if - handgun

	if ($row['srtname'] == 'saturday'){
		if ($mobCount > $saturday){
			$totalDefenceW = $totalDefenceW + ($row['defence'] * $saturday);
			$mobCount = $mobCount - $saturday;
		}else{
			$totalDefenceW = $totalDefenceW + ($row['defence'] * $mobCount);
			break;
		}
	}//end if - saturday

	if ($row['srtname'] == 'beretta'){
		if ($mobCount > $beretta){
			$totalDefenceW = $totalDefenceW + ($row['defence'] * $beretta);
			$mobCount = $mobCount - $beretta;
		}else{
			$totalDefenceW = $totalDefenceW + ($row['defence'] * $mobCount);
			break;
		}
	}//end if - beretta

	if ($row['srtname'] == 'potatomasher'){
		if ($mobCount > $potatomasher){
			$totalDefenceW = $totalDefenceW + ($row['defence'] * $potatomasher);
			$mobCount = $mobCount - $potatomasher;
		}else{
			$totalDefenceW = $totalDefenceW + ($row['defence'] * $mobCount);
			break;
		}
	}//end if - potatomasher

	//INPUT NEW LOOT HERE

}  
mysqli_free_result($result);	

//$totalDefenceW now holds value to total Defence of Weapon.;



//-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-**************************=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-
//-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-        ARMOR ATTACK      =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-
//-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-**************************=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-
$mobCount = $mobCountTemp;

//we need to loop through the ARMOR ATTACK first
$result = mysqli_query($conn,"SELECT srtname,attack FROM stat_calculator.weapons WHERE type = 'armor' ORDER BY attack DESC");
while($row = mysqli_fetch_array($result)){

	if ($row['srtname'] == 'kevlarlined'){
		if ($mobCount > $kevlarlined){
			$totalAttackA = $totalAttackA + ($row['attack'] * $kevlarlined);
			$mobCount = $mobCount - $kevlarlined;
		}else{
			$totalAttackA = $totalAttackA + ($row['attack'] * $mobCount);
		break;
		}
	}//end if - kevlarlined

	if ($row['srtname'] == 'riotshield'){
		if ($mobCount > $riotshield){
			$totalAttackA = $totalAttackA + ($row['attack'] * $riotshield);
			$mobCount = $mobCount - $riotshield;
		}else{
			$totalAttackA = $totalAttackA + ($row['attack'] * $mobCount);
			break;
		}
	}//end if - riotshield

	if ($row['srtname'] == 'bodyarmor'){
		if ($mobCount > $bodyarmor){
			$totalAttackA = $totalAttackA + ($row['attack'] * $bodyarmor);
			$mobCount = $mobCount - $bodyarmor;
		}else{
			$totalAttackA = $totalAttackA + ($row['attack'] * $mobCount);
			break;
		}
	}//end if - bodyarmor

	if ($row['srtname'] == 'kevlarvest'){
		if ($mobCount > $kevlarvest){
			$totalAttackA = $totalAttackA + ($row['attack'] * $kevlarvest);
			$mobCount = $mobCount - $kevlarvest;
		}else{
			$totalAttackA = $totalAttackA + ($row['attack'] * $mobCount);
			break;
		}
	}//end if - kevlarvest

	//echo $row['srtname']. "<br/>";

	//INPUT NEW LOOT HERE

}  
mysqli_free_result($result);	

//$totalAttackA now holds value to total Attack of Armor.;


//-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-**************************=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-
//-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-        ARMOR DEFENCE     =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-
//-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-**************************=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-
$mobCount = $mobCountTemp;

//we need to loop through the ARMOR DEFENCE  first
$result = mysqli_query($conn,"SELECT srtname,defence FROM stat_calculator.weapons WHERE type = 'armor' ORDER BY defence DESC");
while($row = mysqli_fetch_array($result)){

	if ($row['srtname'] == 'kevlarlined'){
		if ($mobCount > $kevlarlined){
			$totalDefenceA = $totalDefenceA + ($row['defence'] * $kevlarlined);
			$mobCount = $mobCount - $kevlarlined;
		}else{
			$totalDefenceA = $totalDefenceA + ($row['defence'] * $mobCount);
			break;
		}
	}//end if - kevlarlined

	if ($row['srtname'] == 'bodyarmor'){
		if ($mobCount > $bodyarmor){
			$totalDefenceA = $totalDefenceA + ($row['defence'] * $bodyarmor);
			$mobCount = $mobCount - $bodyarmor;
		}else{
			$totalDefenceA = $totalDefenceA + ($row['defence'] * $mobCount);
			break;
		}
	}//end if - bodyarmor

	if ($row['srtname'] == 'riotshield'){
		if ($mobCount > $riotshield){
			$totalDefenceA = $totalDefenceA + ($row['defence'] * $riotshield);
			$mobCount = $mobCount - $riotshield;
		}else{
			$totalDefenceA = $totalDefenceA + ($row['defence'] * $mobCount);
			break;
		}
	}//end if - riotshield

	if ($row['srtname'] == 'kevlarvest'){
		if ($mobCount > $kevlarvest){
			$totalDefenceA = $totalDefenceA + ($row['defence'] * $kevlarvest);
			$mobCount = $mobCount - $kevlarvest;
		}else{
			$totalDefenceA = $totalDefenceA + ($row['defence'] * $mobCount);
			break;
		}
	}//end if - kevlarvest

	//echo $row['srtname']. "<br/>";

	//INPUT NEW LOOT HERE

}
mysqli_free_result($result);	

//$totalDefenceA now holds value to total Defence of Armor.;


//-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-**************************=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-
//-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-        WORK IT OUT!      =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-
//-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-**************************=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-

//$totalAttackM
//$totalDefenceM
//$totalAttackW
//$totalDefenceW
//$totalAttackA
//$totalDefenceA
//$ammo
//$razor

if ($ammo == 'on'){
	//get 10 percent of final attack value and it to total.
	$totalAttack = $totalAttackM + $totalAttackW + $totalAttackA;
	$totalTempa = $totalAttack / '100' * '10';
	$totalTempa = round($totalTempa,0);
	$totalAttack = $totalAttack + $totalTempa;
}else{
	$totalAttack = $totalAttackM + $totalAttackW + $totalAttackA;
}

if ($razor == 'on'){
	//adds 16 to final defence value.
	$totalDefence = $totalDefenceM + $totalDefenceW + $totalDefenceA;
	$totalDefence = $totalDefence + '16';
}else{
	$totalDefence = $totalDefenceM + $totalDefenceW + $totalDefenceA;
}

//total attack and defence now reeds 1,000,000 not 1000000
$totalAttack = number_format($totalAttack);
$totalDefence = number_format($totalDefence);

echo "Total Attack: <strong>" .$totalAttack."</strong>"; 
echo "<br/>";
echo "Total Defence: <strong>" .$totalDefence."</strong>";

if(isset($_COOKIE['calc_id']))	{
	
	//here we save the scores in db
	if ($savestats == 'undefined' || $savestats == ''){}else{

		$calc_id = check_val($_COOKIE['calc_id']);

		//here is where we need to save the score.
		$totalSave = "riotshield:".$riotshield.",kevlarvest:".$kevlarvest.",bodyarmor:".$bodyarmor.",kevlarlined:".$kevlarlined.",shank:".$shank.",brass:".$brass.",saturday:".$saturday.",german:".$german.",handgun:".$handgun.",chainsaw:".$chainsaw.",galesi:".$galesi.",molotov:".$molotov.",magnum:".$magnum.",grenade:".$grenade.",ak47:".$ak47.",shotgun:".$shotgun.",glock:".$glock.",xm400:".$xm400.",rpg:".$rpg.",tommygun:".$tommygun.",lapua:".$lapua.",ar15:".$ar15.",garrote:".$garrote.",cleaver:".$cleaver.",steeltoed:".$steeltoed.",lupara:".$lupara.",machete:".$machete.",bazooka:".$bazooka.",brengun:".$brengun.",slugger:".$slugger.",beretta:".$beretta.",potatomasher:".$potatomasher;

		$rand_id = md5(uniqid(rand())); //sets to a random code

		mysqli_query($conn,"INSERT INTO stat_calculator.saveresults (id, weaponlist, attack, defence, name, mobmembers, datestamp, showit) VALUES ('".$calc_id."', '".$totalSave."', '".$totalAttack."', '".$totalDefence."', '".$savestats."', '".$totalMobCount."', '".time()."', '".$rand_id."')");
		//save $totalSave in db with member id.. id, weapons, total attack, total defence

	}

}

?>
