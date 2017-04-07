<?php


$languages = xtc_get_languages();



$po_id = $_POST["po_id"];
if($po_id == ""){
            $qt = "SELECT * FROM mixxxer_groups ORDER BY mg_id DESC LIMIT 1";
            $rst = mysql_query($qt);
            $rt = mysql_fetch_object($rst);
            $new_po_id = $rt->mg_id + 1;
}
foreach ($languages AS $l){
  
if(is_array($_POST["delete_po_img"])){  
  foreach($_POST["delete_po_img"] AS $del_img){
  
    $t = explode('|', $del_img);
    
    $field = $t[0];
    $file = $t[1];
    
    if(file_exists(DIR_FS_DOCUMENT_ROOT.DIR_WS_IMAGES.'/options/original_images/'.$file)){
      unlink(DIR_FS_DOCUMENT_ROOT.DIR_WS_IMAGES.'/options/original_images/'.$file);
    }
    if(file_exists(DIR_FS_DOCUMENT_ROOT.DIR_WS_IMAGES.'/options/popup_images/'.$file)){
      unlink(DIR_FS_DOCUMENT_ROOT.DIR_WS_IMAGES.'/options/popup_images/'.$file);
    }
    if(file_exists(DIR_FS_DOCUMENT_ROOT.DIR_WS_IMAGES.'/options/info_images/'.$file)){
      unlink(DIR_FS_DOCUMENT_ROOT.DIR_WS_IMAGES.'/options/info_images/'.$file);
    }
    if(file_exists(DIR_FS_DOCUMENT_ROOT.DIR_WS_IMAGES.'/options/thumbnail_images/'.$file)){
      unlink(DIR_FS_DOCUMENT_ROOT.DIR_WS_IMAGES.'/options/thumbnail_images/'.$file);
    }
    
    $q = "UPDATE mixxxer_groups SET $field = '' WHERE mg_id = '".$po_id."'";
    xtc_db_query($q);
    
  
  }
  }
  
  $en = array();
  $en["mg_disp"] = $_POST["mg_disp"];
  $lid = $l["id"];
  
   if(is_utf8($_POST['po_name_'.$lid])){
              $en['mg_name'] = xtc_db_prepare_input(my_utf8_decode($_POST['po_name_'.$lid]));
          }else{
              $en['mg_name'] = xtc_db_prepare_input($_POST['po_name_'.$lid]);
          }
  
  $en['mg_sortorder'] = xtc_db_prepare_input(my_utf8_decode($_POST['products_options_sortorder']));
  

  foreach ($products_options_fields AS $f){
      $name = $f["name"];
      $type = $f["type"];
      $single = $f["single"];
      $f = $name;
      if ($type == "image"){
          if($_FILES[$f.'_'.($single == 1?'0':$lid)]["name"] != ""){
              $filename = $_FILES[$f.'_'.($single == 1?'0':$lid)]["name"];
              
              $fn = $po_id.$new_po_id.'_'.($single == 1?'0':$lid).'_'.$filename;
              $com_path = DIR_FS_DOCUMENT_ROOT.DIR_WS_IMAGES.'/mixxxer_groups/original_images/'.$fn;
              move_uploaded_file($_FILES[$f.'_'.($single == 1?'0':$lid)]['tmp_name'], $com_path);  
              $thumb = PhpThumbFactory::create($com_path);  
              $thumb->resize($po_popup_width, $po_popup_height)->save(str_replace("original_images", "popup_images", $com_path));
              $thumb->resize($po_info_width, $po_info_height)->save(str_replace("original_images", "info_images", $com_path));
              
              $thumb->resize($po_thumbnail_width, $po_thumbnail_height)->save(str_replace("original_images", "thumbnail_images", $com_path));
              $en[$f] = xtc_db_prepare_input(my_utf8_decode($fn));
          }
      }elseif($type=="tags"){
         
          $_t = implode(',', $_POST[$f.'_'.($single == 1?'0':$lid)]['tags']);
           
           if(is_utf8( $_t)){
              $en[$f] = xtc_db_prepare_input(my_utf8_decode( $_t));
          }else{
              $en[$f] = xtc_db_prepare_input( $_t);
          }
          
      }else{
         if(is_utf8($_POST[$f.'_'.($single == 1?'0':$lid)])){
              $en[$f] = xtc_db_prepare_input(my_utf8_decode($_POST[$f.'_'.($single == 1?'0':$lid)]));
          }else{
              $en[$f] = xtc_db_prepare_input($_POST[$f.'_'.($single == 1?'0':$lid)]);
          }
      }
       
  }
  if($po_id != ""){
      var_dump($en);
      xtc_db_perform('mixxxer_groups', $en, 'update', "mg_id = '" .$po_id. "' and language_id = '" . $lid . "'");
  }else{
      if ($new_po_id == ""){
            $qt = "SELECT * FROM mixxxer_groups ORDER BY mg_id DESC LIMIT 1";
            $rst = mysql_query($qt);
            $rt = mysql_fetch_object($rst);
            $new_po_id = $rt->mg_id + 1;
            
      }
      
      $en['mg_id'] = $new_po_id;
      $en['language_id'] = $lid;
      xtc_db_perform('mixxxer_groups', $en);
      
      
      
  }
   

}
echo 'OK';

          



?>
