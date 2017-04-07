<?php


require_once(DIR_FS_INC .'xtc_get_tax_rate.inc.php');
require_once(DIR_FS_INC .'xtc_get_tax_class_id.inc.php');
/*$languages = xtc_get_languages();


$q = "SHOW COLUMNS FROM ".TABLE_PRODUCTS_OPTIONS_VALUES;
         
$rs = mysql_query($q);
$fields = array();
while ($r = mysql_fetch_array($rs)){
  if (strpos($r[0], 'ov_am_')===0){
      array_push($fields, $r[0]); 
  }  
}
  */
$ov_id = $_POST["ov_id"];
$pid = $_POST["pid"];
  
  
  $en = array();
  
 if(!preg_match('/[A-Z%:]+/', $_POST['mia_price'])){
            $_POST['mia_price'] = (float)str_replace(',', '.', $_POST['mia_price']);
        
        if (PRICE_IS_BRUTTO=='true'){
            $price= ($_POST['mia_price']/((xtc_get_tax_rate(xtc_get_tax_class_id($pid)))+100)*100);
        }else{
            $price= $_POST['mia_price'];
        }
        $en['mia_price'] = xtc_db_prepare_input($price);
        $en['mia_price_special'] ="";
        //$price= xtc_round($price,PRICE_PRECISION);
  }else{
    $price = 0;
    $en['mia_price_special'] = xtc_db_prepare_input($_POST['mia_price']);
    $en['mia_price'] = 0;
  }
  
  
  
  
  $en['mia_price'] = xtc_db_prepare_input($price);
 
  $en['mia_weight'] = xtc_db_prepare_input($_POST['mia_weight']);
  $en['mia_stock'] = xtc_db_prepare_input($_POST['mia_stock']);
  $en['mia_sortorder'] = xtc_db_prepare_input($_POST['mia_sortorder']);
  $en['mia_model'] = xtc_db_prepare_input($_POST['mia_model']);
  $en['mia_checked'] = xtc_db_prepare_input($_POST['mia_checked']);


/*
              
  foreach ($fields AS $f){
      $en[$f] = xtc_db_prepare_input($_POST[$f.'_'.$lid]);
       
  }    */
  
  

  xtc_db_perform('mixxxer_items_active', $en, 'update', "mia_mi_id = '" .$ov_id. "' and mia_products_id = '" . $pid . "'");
 
   




          



?>
