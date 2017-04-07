<?php
require_once(DIR_FS_INC.'xtc_get_tax_class_id.inc.php');
class mixxxerHelper{
    
    static function chooseInfoType(){
      
      $ret =  xtc_draw_pull_down_menu('configuration_value', array(array('id'=>'mouseover', 'text'=>'Mouse Over'), array('id'=>'click', 'text'=>'Click')), MIXXXER_ITEM_INFO_DISPLAY);
      return $ret;
    
    }
    
    static function chooseYesNo(){
      
      $ret =  xtc_draw_pull_down_menu('configuration_value', array(array('id'=>'0', 'text'=>'Nein'), array('id'=>'1', 'text'=>'Ja')), MIXXXER_PRICE_DISPLAY_2);
      return $ret;
    
    }
    
    
    
    static function getMixxxerItemPrice($mi_id, $pid, $qty=1, $cart_qty=1){
          global $xtPrice;
          if(is_object($xtPrice)){
          $tid = xtc_get_tax_class_id($pid);
          $q = "SELECT * FROM mixxxer_items mi, mixxxer_items_active mia WHERE 
              mi.mi_id = ".(int)$mi_id." 
              AND mi.language_id = '".(int) $_SESSION['languages_id']."'
              AND mia.mia_mi_id = mi.mi_id
              AND mia.mia_products_id = ".(int)$pid."
              
              ";
         
             $rs = mysql_query($q);
             $r = mysql_fetch_object($rs);
          //var_dump($r->mia_price); 
             $percent = 0;
             if(preg_match('/FIX:/', $r->mia_price_special)){
              $r->mia_price = (float)str_replace('FIX:', '', $r->mia_price_special)/$cart_qty;
               
            }elseif(preg_match('/%/', $r->mia_price_special)){
                $percent = (float)str_replace('%', '', $r->mia_price_special)/$cart_qty;
                $_SESSION["_mixxxerPercent"] = $percent;
            }
             $price = $r->mia_price;
           if($r->mi_product!=0){
               $_SESSION["_mixxxerPercent"] = 0;
              $mip_q = "SELECT * FROM products WHERE products_id = ".$r->mi_product;
              $mip_rs = mysql_query($mip_q);
              $mip = mysql_fetch_object($mip_rs);
              
              
              $pp = $xtPrice->xtcGetPrice($mip->products_id, $format = false, $qty, $mip->tax_class_id, $mip->products_price);
            
              $products_tax = isset($xtPrice->TAX[$mip->tax_class_id]) ? $xtPrice->TAX[$mip->tax_class_id] : 0;
              $pp = $xtPrice->xtcRemoveTax($pp, $products_tax)*(1+$percent/100);
              
              
              
              $price += $pp;
            
            }
            
            $price = $xtPrice->xtcFormat($price, false, $tid, true,  0, 0, 8);
            
            return $price;
         
           }
    
    
    
    }
    
    public static function generatePdfFromHtml($html){
        // Include the main TCPDF library (search for installation path).
        require_once(DIR_FS_CATALOG.'alkimmedia_modules/mixxxer/classes/mpdf/mpdf.php');


        // create new PDF document
        $pdf = new mPDF('utf-8', array(54,101), 10, 'freesans', 4, 4, 4, 10, 0, 0, 'L');
        $pdf->SetHTMLFooter('<div style="font-size:7pt; text-align:center; width:100%;">
        <div>
            <span>Angelanfuettermix Deutschland UG (haftungsbeschränkt) - www.angelanfuettermix.de</span>
        </div>
        <div>
            <span>Rohler Strasse 10, 63633 Birstein</span>
        </div>
        <br />
</div>');
        $pdf->WriteHTML(utf8_encode($html), false, false, true, false, '');
        $pdf->Output();
    }

}

?>
