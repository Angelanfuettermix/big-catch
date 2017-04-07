<head>
<?php


include('am_html_header.php');

if ($_POST){
    foreach($_POST["cgt"] AS $cg_name=>$cgt_by_lang){
        foreach($cgt_by_lang AS $lid=>$cgt){
              $sql_arr = array('mi_comp_gr'=>$cg_name,
                                'mi_comp_gr_text'=>$cgt,
                                'type'=>$_GET["type"],
                                'language_id'=>$lid);
              $qt = "SELECT * FROM mixxxer_comp_texts WHERE mi_comp_gr LIKE '$cg_name' AND language_id = $lid AND type='".$_GET["type"]."'";
              $rst = mysql_query($qt);
              
              if(mysql_num_rows($rst)>0){
                   xtc_db_perform('mixxxer_comp_texts', $sql_arr, 'update', "mi_comp_gr LIKE '$cg_name' AND language_id = $lid AND type='".$_GET["type"]."'");
              }else{
                   xtc_db_perform('mixxxer_comp_texts', $sql_arr);
              }
              
        
        }
    
    }
    
   
    
   
}


?>
<style>
  input{
      width:300px;
      }
</style>
</head>

 
<body style="padding:20px;">

  <?php
  
   $languages = xtc_get_languages();
   foreach ($languages AS $l){
        $lid = $l["id"];
        $lang[$lid] = $l;
   }
   
   $q = "SELECT DISTINCT mi_comp_gr FROM mixxxer_items WHERE mi_comp_gr != ''";
   $rs = mysql_query($q);
   $cp = array();
   while($r = mysql_fetch_object($rs)){
      $cp[] = $r->mi_comp_gr;
   }
   
  
  $ret .= '<h2>NICHT kompatibel zu</h2><form action="mixxxer_manager.php?what=comp_texts&action=save&type=ref" method="post">';
  $ret .= '<table><tr><td></td>';
  foreach($lang AS $lid=>$l){
      $ret .= '<td>'.$l["name"].'</td>';
  }
  $ret .= '</tr>';
  
  foreach($cp AS $cg){
    $ret.= '<tr><td>'.$cg.'</td>';
    foreach($lang AS $lid=>$l){
      $ret .= '<td><input name="cgt['.$cg.']['.$lid.']" value="'.get_cgt($cg, $lid, 'ref').'" /></td>';
    }
    $ret .= '</tr>';
  
  }
  $ret .= '</table><button type="submit">'.AM_SAVE.'</button></form>';
  
  
  
  
  
  $languages = xtc_get_languages();
   foreach ($languages AS $l){
        $lid = $l["id"];
        $lang[$lid] = $l;
   }
   
   $q = "SELECT DISTINCT mi_comp_gr FROM mixxxer_items WHERE mi_comp_gr != ''";
   $rs = mysql_query($q);
   $cp = array();
   while($r = mysql_fetch_object($rs)){
      $cp[] = $r->mi_comp_gr;
   }
   
  
  $ret .= '<h2 style="margin-top:20px;">NUR kompatibel zu</h2><form action="mixxxer_manager.php?what=comp_texts&action=save&type=only" method="post">';
  $ret .= '<table><tr><td></td>';
  foreach($lang AS $lid=>$l){
      $ret .= '<td>'.$l["name"].'</td>';
  }
  $ret .= '</tr>';
  
  foreach($cp AS $cg){
    $ret.= '<tr><td>'.$cg.'</td>';
    foreach($lang AS $lid=>$l){
      $ret .= '<td><input name="cgt['.$cg.']['.$lid.']" value="'.get_cgt($cg, $lid, 'only').'" /></td>';
    }
    $ret .= '</tr>';
  
  }
  $ret .= '</table><button type="submit">'.AM_SAVE.'</button></form>';
  
  
  echo $ret;
  
  
  $ret = '';
  
  
  

function get_cgt($cg, $lid, $type){
   $q = "SELECT * FROM mixxxer_comp_texts WHERE mi_comp_gr LIKE '$cg' AND language_id = $lid AND type = '$type'";
   $rs = mysql_query($q);
   $r = mysql_fetch_object($rs);
   return $r->mi_comp_gr_text;
    
}
?>
