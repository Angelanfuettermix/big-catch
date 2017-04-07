<?php


$languages = xtc_get_languages();



$po_id = $_GET["option"];

$qt = "SELECT * FROM mixxxer_groups ORDER BY mg_id DESC LIMIT 1";
$rst = mysql_query($qt);
$rt = mysql_fetch_object($rst);
$new_po_id = $rt->mg_id + 1;


$q = "SELECT * FROM mixxxer_groups WHERE mg_id = $po_id";
$rs = xtc_db_query($q);

while($r = xtc_db_fetch_array($rs)){
    
    $r["mg_id"] = $new_po_id;
    $r["mg_name"] = $r["mg_name"].' - copy';
    xtc_db_perform('mixxxer_groups', $r);

}

$q = "SELECT DISTINCT mi_id FROM mixxxer_items_to_mixxxer_groups WHERE mg_id = $po_id";
$rs = xtc_db_query($q);

while($r = xtc_db_fetch_array($rs)){
            $qt = "SELECT * FROM mixxxer_items ORDER BY mi_id DESC LIMIT 1";
            $rst = mysql_query($qt);
            $rt = mysql_fetch_object($rst);
            $new_pov_id = $rt->mi_id + 1;
    
    $q = "SELECT * FROM mixxxer_items WHERE mi_id = ".$r["mi_id"];
    $rs_mi = xtc_db_query($q);

    while($r_mi = xtc_db_fetch_array($rs_mi)){
        $r_mi["mi_id"] = $new_pov_id;
        xtc_db_perform('mixxxer_items', $r_mi);
    }
    $m2m = array('mi_id'=>$new_pov_id, 'mg_id'=>$new_po_id);
    xtc_db_perform('mixxxer_items_to_mixxxer_groups', $m2m);
}




          



?>
