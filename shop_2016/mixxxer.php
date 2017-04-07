<?php

include ('includes/application_top.php');
require_once (DIR_FS_INC.'xtc_get_products_mo_images.inc.php');
if(MIXXXER_TESTMODE == 1 && MIXXXER_ADMIN != 1 || MIXXXER_ACTIVE == 0){
    xtc_redirect(xtc_href_link(FILENAME_DEFAULT));
}
$products_id = (int)$_SESSION["mixxxer"]->current_product;

$q = "SELECT * FROM products WHERE products_id = $products_id";

$rs = mysql_query($q);
$mixxxer_product = mysql_fetch_object($rs);
$mpo = new product($products_id, true);

// create smarty elements
$smarty = new Smarty;
// include boxes
require (DIR_FS_CATALOG.'templates/'.CURRENT_TEMPLATE.'/source/boxes.php');

if(!isset($_GET["products_id"])){
    
   
	$cPath = xtc_get_product_path($mpo->data["products_id"]);
	

if (xtc_not_null($cPath)) {
	$cPath_array = xtc_parse_category_path($cPath);
	$cPath = implode('_', $cPath_array);
	$current_category_id = $cPath_array[(sizeof($cPath_array) - 1)];
} else {
	$current_category_id = 0;
}


// add category names or the manufacturer name to the breadcrumb trail
if (isset ($cPath_array)) {
	for ($i = 0, $n = sizeof($cPath_array); $i < $n; $i ++) {
    $group_check = '';
		if (GROUP_CHECK == 'true') {
			$group_check = "and c.group_permission_".$_SESSION['customers_status']['customers_status_id']."=1 ";
		}
		$categories_query = xtDBquery("select cd.categories_name
				                           from ".TABLE_CATEGORIES_DESCRIPTION." cd,
				                                ".TABLE_CATEGORIES." c
				                           where cd.categories_id = '".$cPath_array[$i]."'
				                           and c.categories_id=cd.categories_id
				                                ".$group_check."
				                           and cd.language_id='".(int) $_SESSION['languages_id']."'");
		if (xtc_db_num_rows($categories_query,true) > 0) {
			$categories = xtc_db_fetch_array($categories_query,true);

			$breadcrumb->add($categories['categories_name'], xtc_href_link(FILENAME_DEFAULT, xtc_category_link($cPath_array[$i], $categories['categories_name'])));
		} else {
			break;
		}
	}
}
   
    
    $breadcrumb->add($mpo->data["products_name"], xtc_href_link('mixxxer.php', '', 'SSL'));
}
$_PHP_SELF = $PHP_SELF;
$_SCRIPT_NAME = $_SERVER['SCRIPT_NAME'];
$_SERVER['SCRIPT_NAME'] = $PHP_SELF = '/product_info.php';
require (DIR_WS_INCLUDES.'header.php');
$PHP_SELF = $_PHP_SELF;
$_SERVER['SCRIPT_NAME'] = $_SCRIPT_NAME;


$groups = array();
$features = array();
$tips = array();


$q = "SELECT DISTINCT mg.* FROM 
            mixxxer_groups mg,
            mixxxer_items_to_mixxxer_groups m2m,
            mixxxer_items_active mia
        WHERE
          m2m.mg_id = mg.mg_id
            AND
          m2m.mi_id = mia.mia_mi_id
            AND
          mia.mia_products_id = $products_id
            AND
          mg.language_id = ".(int)$_SESSION["languages_id"]."
          ORDER BY mg_sortorder ASC
          ";
//TODO: ACTIVE STATE 0/1 FOR BASE
$rs = mysql_query($q);  
$i = 0;

while ($mg_r = mysql_fetch_array($rs)){
    
    $groups[$i] = $mg_r;
    if($groups[$i]["mg_image"]!='')
        $groups[$i]["mg_image"] = DIR_WS_IMAGES.'mixxxer_groups/thumbnail_images/'.$groups[$i]["mg_image"];
    $q = "SELECT * FROM mixxxer_items mi, mixxxer_items_to_mixxxer_groups mi2mg, mixxxer_items_active mia 
        WHERE
          mi.language_id = ".(int)$_SESSION["languages_id"]."
            AND
          mia.mia_mi_id = mi.mi_id
            AND
          mia.mia_products_id = $products_id
            AND
          mi2mg.mg_id = ".$mg_r["mg_id"]."
            AND
          mi2mg.mi_id = mi.mi_id
          GROUP BY mi.mi_id
          ORDER BY mi.mi_subgroup, mia_sortorder ASC
          
          ";
     
      //TODO: ACTIVE STATE 0/1 FOR BASE
      $mi_rs = xtc_db_query($q);  
      
      $hasSubGroups = false;
       if(mysql_num_rows($mi_rs)==0){
            unset($groups[$i]);
      }else{  
              while ($mi_r = mysql_fetch_array($mi_rs)){
                    if($mi_r["mi_image"] == ''){
                        $mi_r["mi_image"] = 'mixxxer_no_image.jpg';
                        $mi_r["no_image"] = 1;
                    }
                    if(trim($mi_r["mi_inc_steps"])==''){
                        if($mg_r["mg_id"]==BASE_MG_ID){
                            $mi_r["mi_inc_steps"] = '5,10,20';
                        }else{
                            $mi_r["mi_inc_steps"] = '50,100,200';
                        }
                    }
                    $mi_r["steps"] = explode(',', $mi_r["mi_inc_steps"]);
                    $mi_r["mi_subgroup"] = preg_replace('/[0-9]{4}\|/', '', $mi_r["mi_subgroup"]);
                    
                    if(preg_match('/FIX:/', $mi_r["mia_price"])){
                        $mi_r["mia_price_type"] = 'FIX';
                    }
                    $_SESSION["_mixxxerPercent"] = 0;
                    $mi_r["mia_price"] = mixxxerHelper::getMixxxerItemPrice($mi_r["mi_id"], $products_id, 1);
                    
                    
                    if($mi_r["mi_image"]!=''){
                      $mi_r["mi_image"] = DIR_WS_IMAGES.'mixxxer_items/thumbnail_images/'.$mi_r["mi_image"];
                    }
                    if($mi_r["mi_product"] != 0){
                        $p = new product($mi_r["mi_product"], true);
                        
                        $module_smarty = new Smarty;
                        $module_smarty->assign('tpl_path', 'templates/'.CURRENT_TEMPLATE.'/');
                        $module_content = array ();
                        if($mi_r["no_image"]==1 && $p->data["products_image"]!=''){
                             $mi_r["mi_image"] = $p->productImage($p->data["products_image"], 'thumbnail');
                             $mi_r["no_image"]=0;
                        }
                        $staffel_data = $p->getGraduated();
                        
                        if (sizeof($staffel_data) > 1) {
                        	$module_smarty->assign('language', $_SESSION['language']);
                        	$module_smarty->assign('module_content', $staffel_data);
                        	// set cache ID
                        
                        	$module_smarty->caching = 0;
                        	$graduated = $module_smarty->fetch(CURRENT_TEMPLATE.'/module/graduated_price.html');
                          $mi_r["graduated"] = $graduated;
                        	
                        }
                    }  
              
                    //$r["pov_price"] = $xtPrice->xtcFormat($r["pov_price"], true);
                    $mi_r["link"] = xtc_href_link('mixxxer_item_infos.php', 'mi_id='.$mi_r["mi_id"]);
                    //$mi_r["mi_description"] = $mi_r["mi_description"];
                    $mi_r["mia_price"] = $xtPrice->xtcFormat($mi_r["mia_price"], true,0,false,0,0,2);
                    if($_SESSION["_mixxxerPercent"] != 0){
                        $mi_r["mia_price"] = $_SESSION["_mixxxerPercent"].'%';
                    }
                    $mi_r["mi_c_text"] = (int)$mi_r["mi_c_text"];
                    $groups[$i]['items'][] = $mi_r;
                     if($mi_r["mi_subgroup"]!=''){
                        $hasSubGroups = true;
                    
                    }
                  
              } 
              $groups[$i]['hasSubGroups'] = $hasSubGroups;
    }
    $i++;


    
}


               $cgt = array();
               $cgq = "SELECT * FROM mixxxer_comp_texts WHERE language_id = ".$_SESSION["languages_id"];
               $cgrs = mysql_query($cgq);
               while($cgr = mysql_fetch_array($cgrs)){
                  $cgt[] =$cgr;
               }
               $smarty->assign('cgt', $cgt);
               
               
            
            



$num_uploads = (int)MIXXXER_NUM_UPLOADS;
if($num_uploads > 0){
    $smarty->assign('mixxxer_uploads', 1);
    $smarty->assign('mixxxer_upload_num', (int)MIXXXER_NUM_UPLOADS);


}

$add_links = array();
$display_add_links = false;
foreach($_SESSION["mixxxer"]->mixxxes AS $mp_id_t => $mixxxes){
    
    if(count($mixxxes)>0){
            $mp_id_t_p = new product($mp_id_t);
            $link = xtc_href_link('mixxxer.php', 'products_id='.$mp_id_t);
            $title = $mp_id_t_p->data["products_name"];
            $add_links[] = array('title'=>$title, 'link'=>$link, 'active'=>($mp_id_t != $mpo->data["products_id"]?'1':'0'));
            if($mp_id_t != $mpo->data["products_id"]){
                $display_add_links = true;
            }
    }
}


$smarty->assign('add_links', $add_links);
$smarty->assign('display_add_links', $display_add_links);
$smarty->assign('groups', $groups);
$smarty->assign('items', $items);
//$smarty->assign('ADD_FEATURE_BUTTON', '<img src="/templates/' . CURRENT_TEMPLATE . '/img/hinzufuegen.png" />');
$smarty->assign('ADD_FEATURE_BUTTON', ADD_ITEM);
$smarty->assign('save_link', xtc_href_link('mixxxer_ajax_helper.php', 'action=save_mix'));
$smarty->assign('fb_link', xtc_href_link('mixxxer_ajax_helper.php', 'action=fb'));

$smarty->assign('INFO', 'Info');
$smarty->assign('FORM_START', '<form method="POST" action="" id="mixer_form">');
$smarty->assign('FORM_END', '</form>');
$tax_rate = $xtPrice->TAX[$mixxxer_product->products_tax_class_id];
$tax_info = $main->getTaxInfo($tax_rate);
$smarty->assign('TAX_NOTE', $tax_info);
$smarty->assign('SHIPPING', $main->getShippingLink());
$smarty->assign('PRICE_BASE', $mixxxer_product->products_price_base);

if($mixxxer_product->products_max_values != ''){
     $smarty->assign('MAX_VAL_HEADING', MIXXXER_MAX_VAL_HEADING);
     if(strpos($mixxxer_product->products_max_values, ',')===false){
        $smarty->assign('MAX_VAL_TYPE', 'single');
       if(strpos($mixxxer_product->products_max_values, ':')===false){
                $t= $mixxxer_product->products_max_values;
            }else{
                $_t = explode(':', $mixxxer_product->products_max_values);
                $t = $_t[0];
            }
        
        $smarty->assign('MAX_VAL', $t);
     }else{
        $smarty->assign('MAX_VAL_TYPE', 'multi');
        
        $t = array();
        $maxVals = explode(',', $mixxxer_product->products_max_values);
        foreach($maxVals AS $maxVal){
            if(strpos($maxVal, ':')===false){
                $t[] = $maxVal;
            }else{
                $_t = explode(':', $maxVal);
                $t[] = $_t[0];
            }
        }
        $smarty->assign('MAX_VAL', $t);
        $smarty->assign('C_MAX_VAL', $_SESSION["c_mix"]->max_val);
     }
}


$smarty->assign('ADD_CART', '<input type="hidden" name="action" value="add_current_mix" />'.xtc_image_submit('button_buy_now.gif', IMAGE_BUTTON_IN_CART));
//$smarty->assign('PRICE', $xtPrice->xtcFormat($_SESSION["c_mix"]->calc_price(), true));
//$smarty->assign('WEIGHT', $_SESSION["c_mix"]->weight);
$smarty->assign('MY_NAME', xtc_draw_input_field('my_name', $_SESSION["c_mix"]->name, 'maxlength="20"'));
if($mixxxer_product->products_mixxxer_template == ''){
    $mixxxer_product->products_mixxxer_template = 'mixxxer_config_area.html';
}
$smarty->assign('config_area_path',CURRENT_TEMPLATE.'/module/mixxxer/'.$mixxxer_product->products_mixxxer_template);
$smarty->assign('MY_COMMENT', xtc_draw_textarea_field('my_comment', 10, 10, 'soft', $_SESSION["c_mix"]->comment, 'class="my_comment"'));
$smarty->assign('MAX_COST', xtc_draw_input_field('max_cost', $_SESSION["c_mix"]->max_cost));
$smarty->assign('TEXT', xtc_draw_textarea_field('my_text','soft', 20, 3, $_SESSION["c_mix"]->my_text));
$smarty->assign('LOAD_FROM_ID', xtc_draw_input_field('load_from_id', 'ID-NUMMER').' <a href="'.xtc_href_link('mixxxer_ajax_helper.php', 'action=load_from_mix_id').'" class="load_from_id_link"><span><img src="alkimmedia_modules/mixxxer/images/mixxxer_load_icon.png" alt="'.LOAD_IT.'" style="position:relative; top:6px;"/></span></a>');
if ($_SESSION['customers_status']['customers_status_id'] == 0){
    $smarty->assign('ADMIN_LINK', '<br /><a style="margin-top:5px;" href="' . xtc_href_link_admin(FILENAME_EDIT_PRODUCTS, 'cPath=' . $cPath . '&extra=mixxxer&pID=' . $products_id) . '&amp;action=new_product' . '" target="_blank" class="mixxxer_small_button">' . MIXXXER_EDIT . '</a>
                                  <a style="margin-top:5px;" href="' . xtc_href_link('mixxxer.php', 'reset_mixxxer=1').'" class="mixxxer_small_button">Reset</a>');
 
}






//$price = $_SESSION["c_mix"]->calc_item_price();
//$feature_list = $_SESSION["c_mix"]->give_item_list();
$smarty->assign('feature_list', $feature_list);







$image = $mpo->productImage($mpo->data['products_image'], 'info');
$mpo->data["products_image"] = $image;

 // more images
  $mo_images = xtc_get_products_mo_images($mpo->data['products_id']);
  if ($mo_images != false) {
    $more_images_data = array();
    foreach ($mo_images as $img) {
      $mo_img = $product->productImage($img['image_name'], 'info');
      $more_images_data[] = array ('PRODUCTS_IMAGE' => $mo_img, 
                                   'PRODUCTS_POPUP_LINK' => 'javascript:popupWindow(\''.xtc_href_link(FILENAME_POPUP_IMAGE, 
                                   'pID='.$product->data['products_id'].'&imgID='.$img['image_nr']).'\')'
                                   );
    }
    $smarty->assign('more_images', $more_images_data);
  }
  
$smarty->assign('MPA', $mpo->data);
$smarty->assign('MIXER', 1);

$smarty->assign('language', $_SESSION['language']);




$main_content = $smarty->fetch(CURRENT_TEMPLATE.'/module/mixxxer/mixxxer.html');

$smarty->assign('main_content', $main_content);

$smarty->caching = 0;
if (!defined(RM))
	$smarty->load_filter('output', 'note');
$smarty->display(CURRENT_TEMPLATE.'/index.html');

include ('includes/application_bottom.php');
?>
