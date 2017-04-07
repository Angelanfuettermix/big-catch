<?php
$profile_name = $_GET["profile_name"];
$q = "DELETE FROM mm_attr_profile WHERE mia_products_id LIKE '$profile_name'";
xtc_db_query($q);

$q = "INSERT INTO mm_attr_profile 
          (	mia_mi_id, 	mia_products_id, 	mia_price, 	mia_stock, 	mia_checked, 	mia_model, 	mia_sortorder, 	mia_weight, 	mia_price_special)
      SELECT     
          mia_mi_id, 	mia_products_id, 	mia_price, 	mia_stock, 	mia_checked, 	mia_model, 	mia_sortorder, 	mia_weight, 	mia_price_special
      FROM mixxxer_items_active WHERE mia_products_id = $pid";
//echo $q;
xtc_db_query($q);

$q = "UPDATE mm_attr_profile SET mia_products_id = '$profile_name' WHERE mia_products_id = $pid";
xtc_db_query($q);

echo AM_SAVE_SUCCESS;










?>
