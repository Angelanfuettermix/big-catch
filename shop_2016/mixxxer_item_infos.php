<?php
header("Content-Type: text/html; charset=iso-8859-15;");
include ('includes/application_top.php');
$smarty = new Smarty;

$mi_id = $_GET["mi_id"];
$q = "SELECT * FROM mixxxer_items mi, mixxxer_items_active mia WHERE mi.mi_id = '".(int)$mi_id."' AND mia.mia_mi_id = mi.mi_id AND mia.mia_products_id = ".$_SESSION["mixxxer"]->current_product." AND mi.language_id = ".(int)$_SESSION["languages_id"];



$rs = xtc_db_query($q);
$r = mysql_fetch_object($rs);

if($r->mi_product != 0){
    $p = new product($r->mi_product, true);
    $module_smarty = new Smarty;
    $module_smarty->assign('tpl_path', 'templates/'.CURRENT_TEMPLATE.'/');
    $module_content = array ();
    
    $staffel_data = $p->getGraduated();
    
    if (sizeof($staffel_data) > 1) {
    	$module_smarty->assign('language', $_SESSION['language']);
    	$module_smarty->assign('module_content', $staffel_data);
    	// set cache ID
    
    	$module_smarty->caching = 0;
    	$graduated = $module_smarty->fetch(CURRENT_TEMPLATE.'/module/graduated_price.html');
      $smarty->assign('GRADUATED', $graduated);
    	
    }
}

$products_id = $_SESSION["mixxxer"]->current_product;
$q = "SELECT * FROM products WHERE products_id = $products_id";
$rs = mysql_query($q);
$mixxxer_product = mysql_fetch_object($rs);
$tax_rate = $xtPrice->TAX[$mixxxer_product->products_tax_class_id];
$tax_info = $main->getTaxInfo($tax_rate);
$smarty->assign('TAX_NOTE', $tax_info);
if(preg_match('/FIX:/', $r->mia_price)){
  $smarty->assign('PRICE_TYPE', 'FIX');    
}


$q = "SELECT * FROM mixxxer_items mi, mixxxer_items_active mia, mixxxer_items_to_mixxxer_groups m2m, mixxxer_groups mg 
        WHERE 
      mi.mi_id = '".(int)$mi_id."' 
      AND 
      mia.mia_mi_id = mi.mi_id 
      AND 
      mia.mia_products_id = ".$_SESSION["mixxxer"]->current_product." 
      AND 
      mi.language_id = ".(int)$_SESSION["languages_id"]."
      AND
      mg.language_id = ".(int)$_SESSION["languages_id"]."
      AND
      m2m.mi_id = mi.mi_id
      AND
      m2m.mg_id = mg.mg_id
      ";
$rs = mysql_query($q);
$vars = mysql_fetch_array($rs);
$smarty->assign('VARS', $vars);

$smarty->assign('ITEM_IN_MIX', $_SESSION["c_mix"]->items[$r->mi_id]);
$smarty->assign('NAME', $r->mi_name);
$smarty->assign('MIID', $r->mi_id);
$smarty->assign('MI_FREEVAL', $r->mi_free_val_1);
$smarty->assign('MI_FREEVAL_UNIT', $r->mi_free_val_1_unit);
$smarty->assign('DESC', nl2br($r->mi_description));

                  $qty = 1;
                  $add = 0;
                  if(isset($_SESSION["c_mix"]->items[$r->mi_id]["qty"])){
                       $qty = $_SESSION["c_mix"]->items[$r->mi_id]["qty"]+1;
                       
                  }
                  $price = mixxxerHelper::getMixxxerItemPrice($r->mi_id, $_SESSION["mixxxer"]->current_product, $qty);
                  

if($r->mi_free_val_1 > 0){

            $max_val = $r->mi_free_val_1;
                    
             if($r->mi_free_val_1_factor != 0){
                        $max_val = $max_val/(float)$r->mi_free_val_1_factor;
             }
  $smarty->assign('BASE_PRICE', $xtPrice->xtcFormat($price*$_SESSION["c_mix"]->price_base/$max_val, true));
  $smarty->assign('PRICE_BASE', $_SESSION["c_mix"]->price_base);
}

if($r->mi_image == ''){
    $r->mi_image = 'mixxxer_no_image.jpg';
}


$smarty->assign('IMG', DIR_WS_IMAGES.'/mixxxer_items/thumbnail_images/'.$r->mi_image);
$smarty->assign('PRICE', $xtPrice->xtcFormat($price, true));
$smarty->assign('LINK', 'mixxxer_ajax_helper.php?action=add_item&mi_id=' . $mi_id);

          
          
$smarty->assign('language', $_SESSION['language']);
echo $smarty->fetch(CURRENT_TEMPLATE.'/module/mixxxer/mixxxer_item_info.html');



?>
