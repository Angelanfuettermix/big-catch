<?php

require_once(DIR_FS_CATALOG.DIR_WS_CLASSES . 'xtcPrice.php');
require_once(DIR_FS_INC .'xtc_get_tax_rate.inc.php');
require_once(DIR_FS_INC .'xtc_get_tax_class_id.inc.php');

$max_entries = 10;
$page_nr = $_GET["page"];
$keyw = $_GET["key"];


$pf = $_GET["price_from"];
$pt = $_GET["price_to"];


$start = ($page_nr-1)*$max_entries;
$min = 3;
$thumbs = false;


$price = true;

if ($min <= strlen($keyw)){
    $xtPrice = new xtcPrice(DEFAULT_CURRENCY,$_SESSION['customers_status']['customers_status_id']); 
      
      
     
      
      
      
      
      
      
      
      
      echo '<sesu_start>';
      
      
      $s_key = str_replace("%", "\%", addslashes($keyw));
      $keys = explode(" ", $s_key);
      
      foreach ($keys AS $key){
          $sq .= "(pd.products_model LIKE '%".$key."%' OR pd.products_name LIKE '%".$key."%' OR pd.products_keywords LIKE '%".$key."%' OR pd.products_description LIKE '%".$key."%') AND ";
      
      }
      
      
      
      $q = "SELECT * FROM ".TABLE_PRODUCTS_DESCRIPTION." pd, ".TABLE_PRODUCTS." p  
            WHERE 
                ($sq pd.products_id > 0)
                AND pd.language_id = '".$_SESSION['languages_id']."'
                AND p.products_status = 1
            AND p.products_id = pd.products_id"; // ORDER BY $order LIMIT $start, ".($max_entries+1);
      //echo $q;
      $rs =xtc_db_query($q);
      
      
      echo '<div id="ss_menu"><span style="float:right" id="ss_close">X</span></div>';
     
      while ($r =mysql_fetch_object($rs)){
          $pr = $xtPrice->xtcGetPrice($r->products_id, $format = true, 1, $r->products_tax_class_id, $r->products_price, 1);
          $pr = $pr["plain"];
          $product = array('products_image' => $r->products_image,
                            'products_name' => $r->products_name,
                            'products_description' => $r->products_description,
                            'products_keywords' => $r->products_keywords,
                            'products_ordered' => $r->products_ordered,
                            'products_date_added' => $r->products_date_added,
                            'products_tax_class_id' => $r->products_tax_class_id,
                            'products_id' => $r->products_id,
                            'products_price' => $r->products_price,
                            'real_price' => $pr
                            );
          $all_products[$r->products_id] = $product;
          $price_array[$r->products_id] = $pr;
          
           
      }
    
    
    
      $num_all = count($all_products);
      
                    
   
      if (count($all_products)==0){
            echo '<div class="search_suggest_list_item">'.SESU_NO_ITEMS_FOUND.'</div>';
          
      }else{
         
      } 
      
     
    
      
                
      for ($i = $start; $i<= ($start + $max_entries); $i++){
          if ($all_products[$i]['products_name'] != ""){
              
               
              if ($price){
                $tax_rate = $xtPrice->TAX[$all_products[$i]['products_tax_class_id']];
                $pr = $xtPrice->xtcGetPrice($all_products[$i]['products_id'], $format = true, 1, $all_products[$i]['products_tax_class_id'], $all_products[$i]['products_price'], 1);
                $pr = '<div style="margin-top:5px;text-align:right;">'.$pr["formated"].'<br /></div>';
              }
              
              
              $name = $all_products[$i]['products_name'];
              
              $name_t = $name;
              
              foreach ($keys as $k){
                  $pos = stripos($name_t, $k);
                  if (preg_match('/'.$k.'/i', $name_t)){
                      $name_t = substr($name_t, 0, $pos).'<b>'.substr($name_t, $pos, strlen($k)).'</b>'.substr($name_t, (strlen($k)+$pos));
                  }
              
              }
              
                
              $name = str_ireplace($keyw, '<b>'.$keyw.'</b>', $name);
              echo '<div class="search_suggest_list_item" pid="'.$all_products[$i]['products_id'].'" pname="'.$all_products[$i]['products_name'].'" href="'.xtc_href_link(FILENAME_PRODUCT_INFO, xtc_product_link($all_products[$i]['products_id'], 'asdasd')).'">'.$thumb.$name_t.$pr.$thumb_end.'</div>';
         }
      } 
      $act_num = count($all_products);
      $num_pages = ceil($act_num/$max_entries);  
      $more = ($act_num>$i);
      if ($page_nr > 1 || $more){
        echo '<div class="search_suggest_navi">';
        if ($more){
            echo '<span class="next_page" href="'.($page_nr+1).'">&gt;&gt;</span>';
        } 
        if ($page_nr > 1){
            echo '<span class="prev_page" href="'.($page_nr-1).'">&lt;&lt;</span>';
        }
        
        
        echo '<center style="clear:both";>'.SESU_PAGE.' '.$page_nr.' '.SESU_OF.' '.$num_pages.'</center>';
        echo '</div>';
      
      }
      
      if($num_all>$max_entries){
          echo '<span class="jq_link search_suggest_view_all" href="'.xtc_href_link(FILENAME_ADVANCED_SEARCH_RESULT, 'keywords='.urlencode($keyw), 'NONSSL', false).'" style="color:#000000">Alle Einträge ansehen</span>';
      
      }
      echo '<sesu_end>';
}
      
?>