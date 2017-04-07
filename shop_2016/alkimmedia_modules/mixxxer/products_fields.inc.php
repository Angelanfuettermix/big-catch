<?php


$title = 'Mixxxer';


$fields = array(
                      
                     
                      
);
$q = "SELECT * FROM mixxxer_items_active WHERE mia_products_id = ".(int)$_GET["pID"];
$rs = mysql_query($q);

$q2 = "SELECT * FROM products WHERE products_id = ".(int)$_GET["pID"];
$rs2 = mysql_query($q2);
$r2 = mysql_fetch_object($rs2);
$fields[] = array(
                            'type'=>'bool',
                            'key'=>'products_is_mixxxer',
                            'title'=>
                                    array('german'=>'Mixxxer-Basis')
                            
);

$fields[] = array(
                            'type'=>'bool',
                            'key'=>'products_master_mixxxer',
                            'title'=>
                                    array('german'=>'Haupt-Mixxxer')
                            
);

if($r2->products_is_mixxxer == 0){
    $fields[] = array(
                            'type'=>'short_text',
                            'key'=>'products_mix_id',
                            'title'=>
                                    array('german'=>'Mix-ID')
                            
                      );
  
     $fields[] = array(
                            'type'=>'select',
                            'key'=>'products_overwrite_name',
                            'title'=>
                                    array('german'=>'Welcher Name soll für das Produkt verwendet werden?'),
                            'options'=>array(
                                        '0'=>array('german'=>'Produktname'),
                                        '1'=>array('german'=>'Mixxx-Name'),
                                        
                            )
                            
                      );
                      
      $fields[] = array(
                            'type'=>'select',
                            'key'=>'products_price_from_mixxx',
                            'title'=>
                                    array('german'=>'Welcher Preis soll für das Produkt verwendet werden?'),
                            'options'=>array(
                                        '0'=>array('german'=>'Produkt'),
                                        '1'=>array('german'=>'Mixxx')
                                        
                            )
                            
                      );
      $fields[] = array(
                            'type'=>'bool',
                            'key'=>'products_remixxx',
                            'title'=>
                                    array('german'=>'Remixxxen erlauben?')
                            
                                        
                          
                            
                      );
}else{
    $fields[] = array(
                            'type'=>'short_text',
                            'key'=>'products_max_values',
                            'title'=>
                                    array('german'=>'Maximalwerte (z.B. Verpackungsgr&ouml;&szlig;e; Nettopreise mit <i>Menge1:Preis1,Menge2:Preis2,...</i>)')
                            
                      );
   $fields[] = array(
                            'type'=>'short_text',
                            'key'=>'products_price_base',
                            'title'=>
                                    array('german'=>'Grundpreis-Berechnungsgrundlage (z.B. 100)')
                            
                      );
   $fields[] = array(
                            'type'=>'short_text',
                            'key'=>'products_volume_price',
                            'title'=>
                                    array('german'=>'Fl&auml;chen und Volumen: Grundpreis (brutto)')
                            
                      );
   $fields[] = array(
                            'type'=>'short_text',
                            'key'=>'products_volume_unit',
                            'title'=>
                                    array('german'=>'Fl&auml;chen und Volumen: Einheit')
                            
                      );
  $fields[] = array(
                            'type'=>'select',
                            'key'=>'products_mixxxer_default_view',
                            'title'=>
                                    array('german'=>'Standardansicht f&uuml;r Konfigurations&uuml;bersicht?'),
                            'options'=>array(
                                        '0'=>array('german'=>'Liste'),
                                        '1'=>array('german'=>'Icons')
                                        
                            )
                            
                      );
  $fields[] = array(
                            'type'=>'bool',
                            'key'=>'products_mixxxer_view_toggle',
                            'title'=>
                                    array('german'=>'Darf die Ansicht vom Kunden gew&auml;hlt werden?')
                                        
                            );
                            
  $files = array ();
    if ($dir = opendir(DIR_FS_CATALOG.'templates/'.CURRENT_TEMPLATE.'/module/mixxxer')) {
      while (($file = readdir($dir)) !== false) {
       
        if (is_file(DIR_FS_CATALOG.'templates/'.CURRENT_TEMPLATE.'/module/mixxxer/'.$file) and (substr($file, -5) == ".html") and ($file != "mixxxer_item_info.html") and ($file != "mixxxer.html") and (substr($file, 0, 1) !=".")) {
          if($file == 'mixxxer_config_area.html'){
            $name = "Standard";
          }elseif($file == 'mixxxer_config_area_select.html'){
            $name = "Select Boxen";
          }else{
            $name = $file;
          }
          $files[$file] = array ('german' => $name);
        }
      }
      closedir($dir);
    }
    // set default value in dropdown!
    $fields[] = array(
                            'type'=>'select',
                            'key'=>'products_mixxxer_template',
                            'title'=>
                                    array('german'=>'Template des Konfigurationsbereiches'),
                            'options'=>$files
                                        
                            );                  
                            
                            
}

unset($_SESSION["mixxxer"]);
    unset($_SESSION["c_mix"]);

 

?>
