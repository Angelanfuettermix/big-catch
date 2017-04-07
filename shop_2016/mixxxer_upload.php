<?php
include('includes/application_top.php');
if($_FILES["file"]){
    $file_name = time().'_'.session_id().'_'.rand(10000, 99999).'__'.$_FILES["file"]["name"];
    $path = DIR_FS_CATALOG."mixxxer_uploads/".$file_name;
    copy($_FILES["file"]["tmp_name"], $path);
    $_SESSION["c_mix"]->addCFile($_GET["id"], $file_name);
}
?>
