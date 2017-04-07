<head>
<?php


include('am_html_header.php');

if ($_POST["am_config_id"]!=""){
    $sql_arr = array('am_title'=>$_POST["am_title"],
                     'am_db_field'=>$_POST["am_db_field"],
                     'am_type'=>$_POST["am_type"]);
    xtc_db_perform('mm_config', $sql_arr, 'update', 'am_config_id='.$_POST["am_config_id"]);
    if($_POST["delete"]==1){
        $q = "DELETE FROM mm_config WHERE am_config_id=".$_POST["am_config_id"];
        xtc_db_query($q);
    }
}

if($_POST['am_new_o_config']==1){
     $sql_arr = array('am_title'=>$_POST["am_title"],
                     'am_db_field'=>$_POST["am_db_field"],
                     'am_type'=>$_POST["am_type"],
                     'am_class'=>'o');
    xtc_db_perform('mm_config', $sql_arr);

}

if($_POST['am_new_ov_config']==1){
     $sql_arr = array('am_title'=>$_POST["am_title"],
                     'am_db_field'=>$_POST["am_db_field"],
                     'am_type'=>$_POST["am_type"],
                     'am_class'=>'ov');
    xtc_db_perform('mm_config', $sql_arr);

}

?>
</head>
<body style="padding:20px;">

  <?php
  
  echo '<h2>'.AM_OPTIONS_GROUP.'</h2>';
  $opt_gr_q = "SELECT * FROM mm_config WHERE am_class = 'o'";
  $rs = mysql_query($opt_gr_q);
  $ret .= '<div style="padding:10px;">
              <div class="am_float"><b>'.AM_TITLE.'</b></div>
               <div class="am_float"><b>'.AM_DB_FIELD.'</b></div>
               <div class="am_float"><b>'.AM_TYPE.'</b></div>
               
               <div class="clearer">&nbsp;</div>';
  
  while ($r = mysql_fetch_object($rs)){
      $ret .= '<form class="ajax_form" action="mixxxer_manager.php?what=save_config" method="post"><input type="hidden" name="am_config_id" value="'.$r->am_config_id.'"/>
                  <div class="am_float"><input type="text" name="am_title" value="'.$r->am_title.'"/></div>
                  <div class="am_float"><input type="text" name="am_db_field" value="'.$r->am_db_field.'"/></div>
                  <div class="am_float">'.draw_am_type($r->am_type).'</div>
                  <div class="am_float" style="width:100px;"><input type="checkbox" name="delete" value="1"/> '.AM_DELETE.'</div><button type="submit" style="float:left;">'.AM_SUBMIT.'</button>
                  
                  <div class="clearer">&nbsp;</div>
              </form>';
                 
  
  }  
  $ret .= '</div>'; 
  
  echo $ret;
  $ret  = '<div style="background:#eee;padding:10px;">';
  $ret  .= '<h4>'.AM_NEW_ENTRY.'</h4>';
  
  $ret .= '<form action="mixxxer_manager.php?what=save_config" method="post"><input type="hidden" name="am_new_o_config" value="1"/>
                  <div class="am_float"><input type="text" name="am_title" value=""/></div>
                  <div class="am_float"><input type="text" name="am_db_field" value=""/></div>
                  <div class="am_float">'.draw_am_type('').'</div>
                  <div class="am_float"><button type="submit">'.AM_SUBMIT.'</button></div>
                  
                  <div class="clearer">&nbsp;</div>
              </form></div>';
  echo $ret;
  $ret = '';
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  echo '<h2 style="margin-top:20px;">'.AM_OPTIONS_VALUES.'</h2>';
  $opt_gr_q = "SELECT * FROM mm_config WHERE am_class = 'ov'";
  $rs = mysql_query($opt_gr_q);
  $ret .= '<div style="padding:10px;">
               <div class="am_float"><b>'.AM_TITLE.'</b></div>
               <div class="am_float"><b>'.AM_DB_FIELD.'</b></div>
               <div class="am_float"><b>'.AM_TYPE.'</b></div>
               
               <div class="clearer">&nbsp;</div>';
  
  while ($r = mysql_fetch_object($rs)){
      $ret .= '<form class="ajax_form" action="mixxxer_manager.php?what=save_config" method="post"><input type="hidden" name="am_config_id" value="'.$r->am_config_id.'"/>
                  <div class="am_float"><input type="text" name="am_title" value="'.$r->am_title.'"/></div>
                  <div class="am_float"><input type="text" name="am_db_field" value="'.$r->am_db_field.'"/></div>
                  <div class="am_float">'.draw_am_type($r->am_type).'</div>
                  <div class="am_float" style="width:100px;"><input type="checkbox" name="delete" value="1"/> '.AM_DELETE.'</div> <button type="submit" style="float:left;">'.AM_SUBMIT.'</button>
                  
                  <div class="clearer">&nbsp;</div>
              </form>';
                 
  
  }   
  $ret .= '</div>';
  echo $ret;
  $ret  = '<div style="background:#eee;padding:10px;">';
  $ret  .= '<h4>'.AM_NEW_ENTRY.'</h4>';
  
  
  $ret .= '<form action="mixxxer_manager.php?what=save_config" method="post"><input type="hidden" name="am_new_ov_config" value="1"/>
                  <div class="am_float"><input type="text" name="am_title" value=""/></div>
                  <div class="am_float"><input type="text" name="am_db_field" value=""/></div>
                  <div class="am_float">'.draw_am_type('').'</div>
                  <div class="am_float"><button type="submit">'.AM_SUBMIT.'</button></div>
                  
                  <div class="clearer">&nbsp;</div>
              </form>';
  $ret .= '</div>';
  echo $ret;
  
  
  
  
  ?>
</body>

<?php


function draw_am_type($val){
    $types = array('image'=>AM_IMAGE,
                   'long_text'=>AM_LONG_TEXT,
                   'short_text'=>AM_SHORT_TEXT,
                   'yesno'=>AM_YESNO);
    $ret .= '<select name="am_type">';
    foreach ($types AS $key=>$value){
        if($val==$key){
            $sel = ' selected="selected"';
        }else{
            $sel = "";
        }
        
        $ret .= '<option value="'.$key.'"'.$sel.'>'.$value.'</option>';
    }
    $ret .= '</select>';
    return $ret;
    
}
?>