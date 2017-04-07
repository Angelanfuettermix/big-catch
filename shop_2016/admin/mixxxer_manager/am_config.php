<?php
/*

   AVAILABLE TYPES:
   image
   long_text
   short_text
   yesno

*/


/*

   EXAMPLES
   $products_options_fields = array(
   array('name'=>'products_options_image', 'type'=>'image'),
   array('name'=>'products_options_description', 'type'=>'long_text')




   );

   $products_options_values_fields = array(
   array('name'=>'products_options_values_image', 'type'=>'image'),
   array('name'=>'products_options_values_description', 'type'=>'long_text')




   );

*/

$q = "SELECT * FROM mm_config WHERE am_class='o'";
$rs = xtc_db_query($q);
$products_options_fields = array();
while ($r = mysql_fetch_array($rs)){
	$products_options_fields[]=array('name'=>$r['am_db_field'], 'title' => $r["am_title"], 'type'=>$r["am_type"], 'single'=>$r["am_single_language"]);
}

$q = "SELECT * FROM mm_config WHERE am_class='ov'";
$rs = xtc_db_query($q);
$products_options_values_fields = array();
while ($r = mysql_fetch_array($rs)){
	$products_options_values_fields[]=array('name'=>$r['am_db_field'], 'title' => $r["am_title"], 'type'=>$r["am_type"], 'single'=>$r["am_single_language"]);
}




$po_thumbnail_width = 100;
$po_thumbnail_height = 100;
$po_info_width = 300;
$po_info_height = 300;
$po_popup_width = 500;
$po_popup_height = 500;


$pov_thumbnail_width = 100;
$pov_thumbnail_height = 100;
$pov_info_width = 300;
$pov_info_height = 300;
$pov_popup_width = 500;
$pov_popup_height = 500;

function is_utf8($str){
  $strlen = strlen($str);
  for($i=0; $i<$strlen; $i++){
    $ord = ord($str[$i]);
    if($ord < 0x80) continue; // 0bbbbbbb
    elseif(($ord&0xE0)===0xC0 && $ord>0xC1) $n = 1; // 110bbbbb (exkl C0-C1)
    elseif(($ord&0xF0)===0xE0) $n = 2; // 1110bbbb
    elseif(($ord&0xF8)===0xF0 && $ord<0xF5) $n = 3; // 11110bbb (exkl F5-FF)
    else return false; // ungültiges UTF-8-Zeichen
    for($c=0; $c<$n; $c++) // $n Folgebytes? // 10bbbbbb
      if(++$i===$strlen || (ord($str[$i])&0xC0)!==0x80)
        return false; // ungültiges UTF-8-Zeichen
  }
  return true; // kein ungültiges UTF-8-Zeichen gefunden
}

function my_utf8_decode($str){
    return iconv ("UTF-8", "ISO-8859-15", $str);

}

?>