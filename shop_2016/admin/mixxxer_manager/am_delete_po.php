<?php




$po_id = $_GET["po_id"];
$q = "DELETE FROM mixxxer_groups WHERE mg_id = $po_id";

xtc_db_query($q);




$q = "SELECT * FROM mixxxer_items_to_mixxxer_groups WHERE mg_id = $po_id";
$rs = xtc_db_query($q);

while($r = mysql_fetch_object($rs)){
    
    $q = "DELETE FROM mixxxer_items WHERE mi_id = ".$r->mi_id;
   
    xtc_db_query($q);
    $q = "DELETE FROM mixxxer_items_to_mixxxer_groups WHERE mi_id = ".$r->mi_id;
    
    xtc_db_query($q);
    $q = "DELETE FROM mixxxer_items_active WHERE mia_mi_id = ".$r->mi_id;;
 
    xtc_db_query($q);


}



          



?>
