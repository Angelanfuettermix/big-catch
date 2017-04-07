<?php




$ov_id = $_GET["ov_id"];
$po_id = $_GET["po_id"];

if ($_GET["new"]==1){
     $q = "INSERT INTO mixxxer_items_active (mia_products_id, mia_mi_id) VALUES($pid, $ov_id)";
}else{
     $q = "DELETE FROM mixxxer_items_active WHERE mia_products_id = $pid AND mia_mi_id = $ov_id";
}

mysql_query($q);




          



?>