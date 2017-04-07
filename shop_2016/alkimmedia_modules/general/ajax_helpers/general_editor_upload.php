<?php
if(!isset($_SESSION["customers_status"]["customers_status_id"]) || $_SESSION["customers_status"]["customers_status_id"] != 0){
    echo 'AUTH FAILED';
    var_dump($_SESSION, isset($_SESSION["customers_status"]["customers_status_id"]));
    die;
}

if(isset($_FILES['image'])){
	$img = $_FILES['image'];
	$pi = pathinfo($img["name"]);
	$i = 0;
	do{
	    $path = "alkimmedia_modules/general/uploads/" . $pi["filename"] .($i > 0?'_'.$i:'').'.'.$pi["extension"];
	    if(!file_exists(DIR_FS_CATALOG.$path)){
	        $isValid = true;
	    }
	    $i++;
	}while(!$isValid);
	move_uploaded_file($img['tmp_name'], DIR_FS_CATALOG.$path);
	$data = getimagesize(DIR_FS_CATALOG.$path);
	$link = str_replace(array('https:', 'http:'), '', HTTP_SERVER).DIR_WS_CATALOG.$path;
	$res = array("upload" => array(
	                        "links" => array("original" => $link),
	                        "image" => array("width" => $data[0],
	                                         "height" => $data[1]
	                                        )
	            ));
	echo json_encode($res);
	die;
}

