<?php

$languages = xtc_get_languages();

$option = $_GET["option"];
$ov = $_GET["ov"];
                   


$values = array();
if($ov != ""){
    $ovq = "SELECT * FROM mixxxer_items WHERE mi_id = $ov";

    $ovrs = mysql_query($ovq);
    while ($ovr = mysql_fetch_array($ovrs)){
       
        $values[$ovr["language_id"]] = $ovr;
       
    }
    

}


$ret .= '<div class="am_new_ov_form"><form method="POST" class="new_ov_form"><input type="hidden" name="pov_id" value="'.$ov.'"/><input type="hidden" name="o_id" value="'.$option.'"/>';
$first_l = true;
foreach ($languages AS $l){
  $lid = $l["id"];
  
  $headings .= '<div class="am_tb_c"><h3>'.$l["name"].'</h3></div>';
  
  
  $names .= '<div class="am_tb_c"><input name="ov_name_'.$lid.'" type="text" value="'.$values[$lid]["mi_name"].'" class="medium_input"/></div>';
 
  foreach ($products_options_values_fields AS $f){
      $name = $f["name"];
      $type = $f["type"];
      $title = $f["title"];
      $single = $f["single"];
      $f = $name;
 
      if($single == 0 || $first_l){
      
          if($single == 1){
              $lidt = 0;
          }else{
              $lidt = $lid;
          }
      ${$f} .= '<div class="am_tb_c">';
      
      ${$f} .= '<h3>'.$title.'</h3>';
      
     
                if($type=="long_text"){
                    ${$f} .= '<textarea name="'.$f.'_'.$lidt.'" class="medium_textarea">'.$values[$lid][$f].'</textarea>';
                }elseif($type=="short_text"){
                    ${$f} .= '<input type="text" name="'.$f.'_'.$lidt.'" class="medium_input" value="'.$values[$lid][$f].'"/>';
                }elseif($type=="yesno"){
                    ${$f} .= xtc_draw_pull_down_menu($f.'_'.$lidt, array(array('id'=>1, 'text' => AM_YES),array('id'=>0, 'text' => AM_NO)) , $values[$lid][$f]);
                }elseif($type=="image"){
                    if ($values[$lid][$f] != ""){
                          ${$f} .= '<img src="'.DIR_WS_CATALOG.DIR_WS_IMAGES.'mixxxer_items/thumbnail_images/'.$values[$lid][$f].'"><br />
                                    <a href="'.DIR_WS_CATALOG.DIR_WS_IMAGES.'mixxxer_items/popup_images/'.$values[$lid][$f].'" class="thickbox">+ '.AM_ZOOM.'</a><br />
                                    
                                    <input type="checkbox" name="delete_ov_img[]" value="'.$f.'|'.$values[$lid][$f].'" /> '.AM_DELETE.'<br />';
                    }
                    ${$f} .= '<input type="file" name="'.$f.'_'.$lidt.'" class="medium_input"/>';
                }elseif($type=="tags"){
                    if(strpos($values[$lid][$f], ',')!==false){
                        $a = explode(',', $values[$lid][$f]);
                    }else{
                        $a = array($values[$lid][$f]);
                    }
                    
                  
                    ${$f} .= '<ul class="mytags" id="fn_'.$f.'_'.$lidt.'">
                                ';
                    foreach($a AS $tag){
                          ${$f} .= '<li>'.trim($tag).'</li>';
                    }
                                     
                                  ${$f} .= '</ul>';   
                }  
      
      ${$f} .= '</div>';
     }
  }
  
  $first_l = false;
}
$product_heading .= '<div class="am_tb_c"><h3>'.MI_PRODUCT.'</h3></div>';

if($values[$_SESSION["languages_id"]]['mi_product'] != "0" && isset($values[$_SESSION["languages_id"]]['mi_product'])){
    $q = "SELECT * FROM products_description WHERE products_id = ".$values[$_SESSION["languages_id"]]['mi_product']." AND language_id = ".$_SESSION["languages_id"];
    $rs = xtc_db_query($q);
    $r = mysql_fetch_object($rs);
    $add = '<div style="font-weight:normal;font-style:italic;">'.$r->products_name.'</div>';
   

}

$product .= '<div class="am_tb_c">
                  '.$add.'
                  <input name="mi_product" type="text" value="'.$values[$_SESSION["languages_id"]]["mi_product"].'" class="medium_input"/>
            </div>';
  

$ret .= '<h3>'.AM_NEW_OV_HEADING.'</h3>';
$ret .= '<div class="am_tb">
          
          <div class="am_tb_r">'.$headings.'</div>
          <div class="am_tb_r">'.$names.'</div>
          <div class="am_tb_r">'.$product_heading.'</div>
          <div class="am_tb_r">'.$product.'</div>
          
        ';
        
foreach ($products_options_values_fields AS $f){
      $name = $f["name"];
      $type = $f["type"];
      $f = $name; 
  
    $ret .= '<div class="am_tb_r">'.${$f}.'</div>';
  
}

$ret .= '</div>';

$ret.='            <a href="#" class="am_save_ov" option="'.$option.'">'. xtc_image('mixxxer_manager/icon_save.png', '', '', '', 'style="position:relative; top:3px;"').AM_SAVE.'</a>
          </div></form>';      
          


echo $ret;








?>
