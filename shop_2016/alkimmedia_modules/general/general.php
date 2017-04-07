<?php

$q = "SELECT * FROM alkim_module_configuration";
$rs = xtc_db_query($q);

while($r = xtc_db_fetch_array($rs)){
    if($r["amc_language"] == $_SESSION["languages_id"] || $r["amc_language"] == 0){
         define($r["amc_key"], $r["amc_value"]);
         if(strpos($r["amc_value"], '||')){
            ${$r["amc_key"]} = explode('||', $r["amc_value"]);
         }
    }
    
    if($r["amc_language"] != 0){
         define($r["amc_key"].'_'.$r["amc_language"], $r["amc_value"]);
    }
    
}

$files = glob(DIR_FS_CATALOG.'alkimmedia_modules/*/lang/'.$_SESSION['language'].'/*.php');
if(is_array($files)){
    foreach($files AS $file){
        include_once($file);
    }
}

$files = glob(DIR_FS_CATALOG.'alkimmedia_modules/*/app_top.inc.php');
if(is_array($files)){
    foreach($files AS $file){
        include_once($file);
    }
}

include_once('includes/am_fields.class.php');
include_once('includes/AmHandler.class.php');


if($_SESSION["customers_status"]["customers_status_id"] == 0){
    AmHandler::$noCache = true;
}

?>
