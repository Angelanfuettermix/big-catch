<?php

$languages = xtc_get_languages();

$po_id = $_GET["po_id"];

 

$values = array();
if($po_id != ""){
    $ovq = "SELECT * FROM mixxxer_groups WHERE mg_id = $po_id";
    $ovrs = mysql_query($ovq);
    while ($ovr = mysql_fetch_array($ovrs)){
       
        $values[$ovr["language_id"]] = $ovr;
        $_ovr = $ovr;
        
    }
}

$ret .= '<div class="am_new_po_form"></form><form method="POST" class="edit_po"><input type="hidden" name="po_id" value="'.$po_id.'"/>';
$li = 0;
$first_l = true;

  $disp = '<div class="am_tb_c">
                <h3>Darstellung</h3>
                '. xtc_draw_pull_down_menu('mg_disp', 
                                array(
                                        array('id'=>'default', 'text' => 'Standard - volle Breite'),
                                        array('id'=>'default_2col', 'text' => 'Standard - zweispaltig'),
                                        array('id'=>'radio', 'text' => 'Radio/Checkboxen'),
                                       // array('id'=>'select', 'text' => 'Select/Checkboxen')
                                      ) , 
                $_ovr["mg_disp"]).'
            
             </div>';
foreach ($languages AS $l){
  $lid = $l["id"];
  
  $headings .= '<div class="am_tb_c"><h3>'.$l["name"].'</div>';
  $names .= '<div class="am_tb_c"><input name="po_name_'.$lid.'" type="text" value="'.$values[$lid]["mg_name"].'" class="medium_input"/></div>';
 
  foreach ($products_options_fields AS $f){
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
      ${$f} .= '<div class="am_tb_c"><h3>'.$title.'</h3>';
      if($type == "long_text"){
          ${$f} .= '<textarea name="'.$f.'_'.$lidt.'" class="medium_textarea">'.$values[$lid][$f].'</textarea>';
      }elseif($type == "short_text"){
          ${$f} .= '<input type="text" name="'.$f.'_'.$lidt.'" class="medium_input" value="'.$values[$lid][$f].'"/>';
      }elseif($type == "yesno"){
          ${$f} .= xtc_draw_pull_down_menu($f.'_'.$lidt, array(array('id'=>'1', 'text' => AM_YES),array('id'=>'0', 'text' => AM_NO)) , (string)((int)$values[$lid][$f]));
      }elseif($type == "image"){
          if ($values[$lid][$f] != ""){
                ${$f} .= '<img src="'.DIR_WS_CATALOG.DIR_WS_IMAGES.'mixxxer_groups/thumbnail_images/'.$values[$lid][$f].'"><br />
                 <input type="checkbox" name="delete_po_img[]" value="'.$f.'|'.$values[$lid][$f].'" /> '.AM_DELETE.'<br />
                ';
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

$ret .= '<h3>'.AM_NEW_PO_HEADING.'</h3>';

$ret .= '<div class="am_tb">
          
          <div class="am_tb_r">'.$headings.'</div>
          <div class="am_tb_r">'.$names.'</div>
          <div class="am_tb_r">'.$disp.'</div>
          <div class="am_tb_r"><div class="am_tb_c"><h3>'.AM_SORTORDER.'</h3><input name="products_options_sortorder" type="text" value="'.$values[$lid]["mg_sortorder"].'" class="very_short_input"/></div></div>
        ';
        
foreach ($products_options_fields AS $f){
    $f = $f["name"];
    $ret .= '<div class="am_tb_r">'.${$f}.'</div>';
}

$ret .= '</div>
            <a href="#" class="am_save_po">'.xtc_image('mixxxer_manager/icon_save.png', 'Speichern', '', '', 'style="position:relative; top:3px;"').AM_SAVE.'</a>&nbsp;&nbsp;&nbsp;<a href="#" class="am_cancel_new_po">'.AM_CANCEL.'</a>
          </div></form>';      
          


echo $ret;








?>
