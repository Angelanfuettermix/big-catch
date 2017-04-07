<?php

require('mixxxer_manager/am_get_attr_set.php');

$po_id = $_GET["po_id"];
   $q = "SELECT * FROM mixxxer_groups WHERE mg_id = $po_id AND language_id = ".$_SESSION["languages_id"];
   
  $group = mysql_fetch_object(mysql_query($q));             
  $q2 = "SELECT * FROM  mixxxer_items mi, 
                        mixxxer_items_to_mixxxer_groups mi2mg 
                      
                        
         WHERE
              mi.mi_id = mi2mg.mi_id
                AND
              mi2mg.mg_id = ".$po_id."
              AND language_id = ".$_SESSION["languages_id"]."
         ";
        
  $rs2 = xtc_db_query($q2);
  $ret .= '<div class="am_ov_row">
                  <div class="am_ov_cell">
                    <span class="am_ov_check"><input po="'.$po_id.'" class="am_sel_all_attr"  style="visibility:hidden" type="checkbox"/></span>
                    <span class="am_ov_name am_bold">'.AM_OPTIONS_VALUES_NAME.'</span>
                    <span class="am_very_short am_bold">'.AM_AC.'</span>
                    <span class="am_attr_settings">
                      <span class="am_attr_in_wr am_short" style="width:24px;">
               
               </span>
                      <span class="am_short am_bold" style="width:150px;">'.AM_PR.'</span>
                      <span class="am_very_short am_bold">'.AM_WE.'</span>
                      <span class="am_very_short am_bold">'.AM_ST.'</span>
                      <span class="am_short am_bold">'.($group->mg_volume == '1'?AM_MINMAXPRE:AM_MOD).'</span>
                      <span class="am_very_short am_bold">'.AM_ORD.'</span>
                      <span class="am_very_short am_bold">'.AM_CHECKED.'</span>
                          
                        
                        
                    </span>
                  
                  </div>
               </div>';  
  while ($r2 = mysql_fetch_array($rs2)){
     $q = "SELECT COUNT(*) FROM mixxxer_items_active WHERE mia_mi_id = ".$r2["mi_id"]." AND mia_products_id = $pid";
      $rs = mysql_query($q);
      $r = mysql_fetch_array($rs); 
      
      //TODO:
      if ($r[0]==0){
        $checked = "";
        $attr_set = "";
      }else{
        $checked = 'checked="checked"';
        $attr_set = am_get_attr_set($r2["mi_id"], $pid);
      }
      
      
      $ret .= '<div class="am_ov_row">
                  <div class="am_ov_cell">
                    
                    <span class="am_ov_check"><input ov="'.$r2["mi_id"].'" po="'.$po_id.'" class="am_sel_ov_as_attr" type="checkbox" '.$checked.'/></span>
                    <span class="am_ov_name" style="position:relative">'.$r2["mi_name"].'
                        <div style="position:absolute; top:14px; font-size:9px; left:0px;">'.($r2["mi_subgroup"] != ''?'Untergruppe: '.$r2["mi_subgroup"].'':'').'</div>
                        </span>
                    <span class="am_very_short">
                        <a href="attribute_manager.php?what=new_ov" option="'.$po_id.'" class="am_edit_ov" id="am_edit_ov_'.$r2["mi_id"].'">'. xtc_image('mixxxer_manager/icon_edit.gif').'</a><a href="#" ov="'.$r2["mi_id"].'" class="am_delete_ov">'. xtc_image('mixxxer_manager/icon_cross.gif').'</a>
                    </span>
                    <span class="am_attr_settings" id="am_attr_settings_'.$r2["mi_id"].'">'.$attr_set.'</span>
                  </div>
               </div>  
              ';
     
      
  
  
  }
    $ret .= '<div class="am_ov_row">
                  <div class="am_ov_cell">
                    <a href="#" class="am_add_ov" option="'.$po_id.'" id="am_add_ov_'.$r["mg_id"].'">'. xtc_image('mixxxer_manager/icon_plus.png', '+', '', '', 'style="position:relative; top:3px;"').AM_ADD_OV.'</a>
                    <a href="#" style="display:none;" class="am_cancel_add_ov" option="'.$po_id.'">'. xtc_image('mixxxer_manager/icon_minus.png', '-', '', '', 'style="position:relative; top:3px;"').AM_CANCEL_ADD_OV.'</a>
                  </div>
               </div>  
              ';
  echo $ret;            
?>   
