<?php


require_once(DIR_FS_CATALOG.DIR_WS_CLASSES . 'xtcPrice.php');
require_once(DIR_FS_INC .'xtc_get_tax_rate.inc.php');
require_once(DIR_FS_INC .'xtc_get_tax_class_id.inc.php');
$as_ov_id = $_GET["as_ov_id"];
if ($as_ov_id != ""){

    echo am_get_attr_set($as_ov_id, $pid);

}
function am_get_attr_set($ov_id, $pid){
    $xtPrice = new xtcPrice(DEFAULT_CURRENCY,$_SESSION['customers_status']['customers_status_id']);
      
      $q = "SELECT * FROM mixxxer_items_active mia, mixxxer_items mi WHERE mi.mi_id = mia_mi_id AND mi.language_id = ".(int)$_SESSION["languages_id"]." AND mia_products_id = $pid AND mia_mi_id = $ov_id";
    
      $rs = mysql_query($q);
      $r = mysql_fetch_object($rs);
      if (PRICE_IS_BRUTTO=='true'){
          $price = $xtPrice->xtcFormat(xtc_round($r->mia_price*((100+(xtc_get_tax_rate(xtc_get_tax_class_id($pid))))/100),PRICE_PRECISION),false);
      }else{
          $price = $r->mia_price; 
      
      }
      
       if($r->mia_price_special != ''){
        
        $price = $r->mia_price_special;
      
      }
    
      
       if($r->mi_product!=0){
              $mip_q = "SELECT * FROM products WHERE products_id = ".$r->mi_product;
              $mip_rs = mysql_query($mip_q);
              $mip = mysql_fetch_object($mip_rs);
              $r->mia_weight = $mip->products_weight;
              $r->mia_stock = $mip->products_quantity;
              $r->mia_model = $mip->products_model;
              
             
             if (PRICE_IS_BRUTTO=='true'){
                  $pp = $xtPrice->xtcFormat(xtc_round($mip->products_price*((100+(xtc_get_tax_rate(xtc_get_tax_class_id($r->mi_product))))/100),PRICE_PRECISION),false);
              }else{
                  $pp = $mip->products_price; 
              
              }
              $pp = $xtPrice->xtcFormat($pp, true);
              
              
             
            
      }
      
      
      //$price=number_format($price, 2);
      $ret .= '<div data-role="form" method="POST" id="am_attr_form_'.$ov_id.'" style="display:inline-block; margin:0px;position:relative;">
                <input type="hidden" name="ov_id" value="'.$ov_id.'">
                <input type="hidden" name="pid" value="'.$pid.'">
                <span class="am_attr_in_wr am_short" style="width:24px;">
               '.($r->mi_product!=0?'<img src="images/icons/zoom.png" title="'.MI_PRODUCT.'"/>':'').' 
               </span>
               <span class="am_attr_in_wr am_short" style="width:150px;">'.
                  xtc_draw_input_field('mia_price', $price, 'class="very_short_input"').($r->mi_product!=0?' <span style="font-size:10px;">+ '.$pp.'</span>':'').
              '</span>
              <span class="am_attr_in_wr am_very_short">'.
                  xtc_draw_input_field('mia_weight', $r->mia_weight, 'class="very_short_input"'.($r->mi_product!=0?' disabled="disabled" ':'')).
              '</span>
              <span class="am_attr_in_wr am_very_short">'.
                  xtc_draw_input_field('mia_stock', $r->mia_stock, 'class="very_short_input" '.($r->mi_product!=0?' disabled="disabled" ':'')).
              '</span>
              <span class="am_attr_in_wr am_short">'.
                  xtc_draw_input_field('mia_model', $r->mia_model, 'class="short_input"'.($r->mi_product!=0?' disabled="disabled" ':'')).
              '</span>
              <span class="am_attr_in_wr am_very_short">'.
                  xtc_draw_input_field('mia_sortorder', $r->mia_sortorder, 'class="very_short_input"').
              '</span>
              <span class="am_attr_in_wr am_very_short">
                  <input type="checkbox" name="mia_checked" value="1" '.(($r->mia_checked==1)?'checked="checked" ':'').'/>
              </span>
              <a href="#" class="am_save_attr" ov="'.$ov_id.'">'.xtc_image('mixxxer_manager/icon_save.png').xtc_image('mixxxer_manager/icon_check.png', '','','','id="check_attr_'.$ov_id.'" class="check_attr"').'</a>
              </div>';
              
      return $ret;
}        

//attributes_model 	 	sortorder
