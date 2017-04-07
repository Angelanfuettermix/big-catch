<?php




$ov_id = $_GET["ov_id"];
$q = "DELETE FROM mixxxer_items WHERE mi_id = $ov_id";
echo $q;
mysql_query($q);

$q = "DELETE FROM mixxxer_items_to_mixxxer_groups WHERE mi_id = $ov_id";
echo $q;
mysql_query($q);




          



?>