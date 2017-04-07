<?php

include('includes/application_top.php');
header('Content-Type:text/plain');
ini_set('max_execution_time', 1800);
$q = "SELECT * FROM mixxxes m ";

$rs = xtc_db_query($q);
$_SESSION["language_id"] = 2;
$i= 0;
while($r = xtc_db_fetch_array($rs)){
	$mix = unserialize($r["mix_code"]);
	if(is_object($mix)){
		$mix->refreshItems();
		$data = serialize($mix);
		$q = "UPDATE mixxxes SET mix_code='".xtc_db_input($data)."' WHERE mix_id = ".$r["mix_id"];
		xtc_db_query($q);
		$i++;
	}

}
echo 'OK - '.$i.' mixxxes processed';
