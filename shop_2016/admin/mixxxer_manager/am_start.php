<?php

$languages = xtc_get_languages();
 
$qt = "SELECT * FROM mm_attr_profile GROUP BY mia_products_id";
$rst = mysql_query($qt);
while ($rt = mysql_fetch_object($rst)){
  
  $options_str .= '<option value="'.$rt->mia_products_id.'" class="am_profile_option">'.$rt->mia_products_id.'</option>';
}
     
    $q = "SELECT * FROM mixxxer_groups WHERE language_id = ".$_SESSION["languages_id"]." ORDER BY mg_sortorder";


$rs = mysql_query($q);

$ret.= '
        <span class="am_save_area"><span style="display:inline-block; width:230px;">'.AM_SAVE_CURR_ATTR.'</span><input style="width:120px" id="new_profile_name" type="text"/> <a href="#" class="am_save_profile" option="'.$option.'"> '.AM_SAVE.'</a></span><br />
        <span class="am_load_profile_area"><span style="display:inline-block; width:230px;">'.AM_LOAD_PROFILE.'</span><select style="width:120px" id="load_profile">'.$options_str.'</select> <a href="#" class="load_profile_button">'.AM_LOAD.'</a>&nbsp;<a href="#" class="am_delete_profile">'.AM_DELETE.'</a></span>
   ';
$ret .= '<div class="am_options_table" style="clear:both">';
while ($r = mysql_fetch_array($rs)){
  /*$nosq = "SELECT COUNT(*) FROM ".TABLE_PRODUCTS_ATTRIBUTES." WHERE options_id = ".$r['products_options_id']." AND products_id = $pid";
  $nosrs = mysql_query($nosq);
  $nosr = mysql_fetch_array($nosrs);
  $nos = $nosr[0];
  $add_fields = array();
  foreach($r as $name=>$value){
      if (strpos($name, "ov_am_")!==false){
        array_push($add_fields, array("name"=>$name, "value"=>$value)); 
      }
  }
      */
  $ret .= '<div class="am_options_row">
              <div class="am_options_cell am_options_name">
                '.$r["mg_name"].($r["mg_note"]!=''?' - <i>'.$r["mg_note"].'</i>':'').'
                ';//' (<span class="num_of_sel_ov" option="'.$r["products_options_name"].'">'.$nos.'</span>)
           $ret .= '   </div>
              <div class="am_options_cell am_options_actions">
                <img src="mixxxer_manager/plus.png" alt="+" id="am_po_id_'.$r["mg_id"].'" option="'.$r["mg_id"].'" class="am_options_expand"/>
                <img src="mixxxer_manager/minus.png" alt="-" class="am_options_collapse"/>
                <a href="#" option="'.$r['mg_id'].'" class="am_edit_po">'. xtc_image('mixxxer_manager/icon_edit.gif').'</a>
                <a href="#" option="'.$r['mg_id'].'" class="am_duplicate_po">'. xtc_image('mixxxer_manager/icon_copy.png').'</a>
                <a href="#" option="'.$r['mg_id'].'" class="am_delete_po">'. xtc_image('mixxxer_manager/icon_cross.gif').'</a>
              </div> 
            </div>
            <div class="am_options_row am_options_values_wr">
              <div class="am_options_cell">
                <div class="am_ov_table" id="am_wr_'.$r["mg_id"].'">
                  
                    
                    ';
                    
                    
          
              
  
   $ret .= '                   
                </div>
              </div>
              
            </div>
                    
            
            ';

}

$ret .= '<div class="am_options_row">
              <div class="am_options_cell am_options_name">
                <a href="#" option="" class="am_edit_po am_new_po">'. xtc_image('mixxxer_manager/icon_plus.png', '+', '', '', 'style="position:relative; top:3px;"').AM_NEW_PO.'</a>
                <a href="#" option="" class="am_cancel_new_po" style="display:none;">'.AM_CANCEL_NEW_PO.'</a>
              </div>
              <div class="am_options_cell am_options_actions">
               
                
              </div> 
            </div>
            <div class="am_options_row am_options_values_wr">
              <div class="am_options_cell">
                <div class="am_ov_table" id="am_wr_'.$r["mg_id"].'">
                  
                    
                    ';
                    
                    
          
              
  
   $ret .= '                   
                </div>
              </div>
              
            </div>
                    
            
            ';
$ret .= '</div>';
echo $ret;

echo '<a href="mixxxer_manager.php?what=comp_texts&TB_iframe=1&width=900" class="thickbox" style="padding-top:10px;diyplay:inline-block;clear:both;float:right; ">'.COMP_TEXTS_START.'</a>
      <a href="mixxxer_manager.php?what=config&TB_iframe=1&width=900" class="thickbox" style="padding-top:10px;diyplay:inline-block;clear:both;float:right; ">'.AM_CONFIG_START.'</a>';







?>
