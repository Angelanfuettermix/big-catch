<?php


$languages = xtc_get_languages();
$pov_id = $_POST["pov_id"];

if ($new_pov_id == ""){
           
            $qt = "SELECT * FROM mixxxer_items ORDER BY mi_id DESC LIMIT 1";
            $rst = mysql_query($qt);
            $rt = mysql_fetch_object($rst);
            $new_pov_id = $rt->mi_id + 1;
            /*
            $pov2po['mg_id'] = $_POST["o_id"];
            $pov2po['products_options_values_id'] = $new_pov_id;
            xtc_db_perform(TABLE_PRODUCTS_OPTIONS_VALUES_TO_PRODUCTS_OPTIONS, $pov2po);
            echo $_POST["o_id"];
            echo $new_pov_id; */
}

$mi2mg_set = false; 
foreach ($languages AS $l){
  
  if(is_array($_POST["delete_ov_img"])){
  
  foreach($_POST["delete_ov_img"] AS $del_img){
  
    $t = explode('|', $del_img);
    
    $field = $t[0];
    $file = $t[1];
    
    if(file_exists(DIR_FS_DOCUMENT_ROOT.DIR_WS_IMAGES.'/options_values/original_images/'.$file)){
      unlink(DIR_FS_DOCUMENT_ROOT.DIR_WS_IMAGES.'/options_values/original_images/'.$file);
    }
    if(file_exists(DIR_FS_DOCUMENT_ROOT.DIR_WS_IMAGES.'/options_values/popup_images/'.$file)){
      unlink(DIR_FS_DOCUMENT_ROOT.DIR_WS_IMAGES.'/options_values/popup_images/'.$file);
    }
    if(file_exists(DIR_FS_DOCUMENT_ROOT.DIR_WS_IMAGES.'/options_values/info_images/'.$file)){
      unlink(DIR_FS_DOCUMENT_ROOT.DIR_WS_IMAGES.'/options_values/info_images/'.$file);
    }
    if(file_exists(DIR_FS_DOCUMENT_ROOT.DIR_WS_IMAGES.'/options_values/thumbnail_images/'.$file)){
      unlink(DIR_FS_DOCUMENT_ROOT.DIR_WS_IMAGES.'/options_values/thumbnail_images/'.$file);
    }
    
    $q = "UPDATE mixxxer_items SET $field = '' WHERE mi_id = '".$pov_id."'";
    xtc_db_query($q);
    
  
  }
 }
 
  $en = array();
  
  $lid = $l["id"];
    if(is_utf8($_POST['ov_name_'.$lid])){
              $en['mi_name'] = xtc_db_prepare_input(my_utf8_decode($_POST['ov_name_'.$lid]));
             
    }else{
              $en['mi_name'] = xtc_db_prepare_input($_POST['ov_name_'.$lid]);
              
    }
  
  $en['mi_product'] = xtc_db_prepare_input(my_utf8_decode($_POST['mi_product']));
 

  foreach ($products_options_values_fields AS $f){
      $name = $f["name"];
      $type = $f["type"];
      $single = $f["single"];
      
 
     
      $f = $name;
      
      
      if ($type=="image"){
          if($_FILES[$f.'_'.($single == 1?'0':$lid)]["name"] != ""){
              $filename = $_FILES[$f.'_'.($single == 1?'0':$lid)]["name"];
              $fn = $pov_id.$new_pov_id.'_'.($single == 1?'0':$lid).'_'.$filename;
              $com_path = DIR_FS_DOCUMENT_ROOT.DIR_WS_IMAGES.'/mixxxer_items/original_images/'.$fn;
              move_uploaded_file($_FILES[$f.'_'.($single == 1?'0':$lid)]['tmp_name'], $com_path);  
              $thumb = PhpThumbFactory::create($com_path);  
             
              $thumb->resize($pov_popup_width, $pov_popup_height)->save(str_replace("original_images", "popup_images", $com_path));
               $thumb->resize($pov_info_width, $pov_info_height)->save(str_replace("original_images", "info_images", $com_path));
              $thumb->resize($pov_thumbnail_width, $pov_thumbnail_height)->save(str_replace("original_images", "thumbnail_images", $com_path));
              
              $en[$f] = xtc_db_prepare_input(my_utf8_decode($fn));
          }
      }elseif($type=="tags"){
         
            $_t = implode(',', $_POST[$f.'_'.($single == 1?'0':$lid)]['tags']);
           
           if(is_utf8($_t)){
              $en[$f] = xtc_db_prepare_input(my_utf8_decode($_t));
          }else{
              $en[$f] = xtc_db_prepare_input($_t);
          }
          
      }else{
           if(is_utf8($_POST[$f.'_'.($single == 1?'0':$lid)])){
              $en[$f] = xtc_db_prepare_input(my_utf8_decode($_POST[$f.'_'.($single == 1?'0':$lid)]));
          }else{
              $en[$f] = xtc_db_prepare_input($_POST[$f.'_'.($single == 1?'0':$lid)]);
          }
          
      }
      
       
  }
  if($pov_id != ""){
    
      xtc_db_perform('mixxxer_items', $en, 'update', "mi_id = '" .$pov_id. "' and language_id = '" . $lid . "'");
  }else{
      
     
      $en['mi_id'] = $new_pov_id;
      $en['language_id'] = $lid;
      
      if(xtc_db_perform('mixxxer_items', $en)){
        
        if (!$mi2mg_set){
            
            $pov2po['mg_id'] = $_POST["o_id"];
            $pov2po['mi_id'] = $new_pov_id;
            xtc_db_perform('mixxxer_items_to_mixxxer_groups', $pov2po);
            $mi2mg_set = true;
          }


      
      };
      
      
      
  }
   

}


          



?>
