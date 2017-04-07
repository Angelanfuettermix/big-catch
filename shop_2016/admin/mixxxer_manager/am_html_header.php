<link rel="stylesheet" type="text/css" href="mixxxer_manager/am.css">
<link rel="stylesheet" type="text/css" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css">  

<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.4.3/jquery.min.js"></script>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.18/jquery-ui.min.js"></script>
<script type="text/javascript" src="mixxxer_manager/tagit.js"></script>
<script type="text/javascript" src="mixxxer_manager/am.js"></script>
<script type="text/javascript" src="mixxxer_manager/search_suggest.js"></script>
<script type="text/javascript" src="mixxxer_manager/thickbox.js"></script>
<script type="text/javascript" src="mixxxer_manager/jquery.form.js"></script>
<?php

$q = "SELECT * FROM mm_config WHERE am_type = 'tags'";
$rs = mysql_query($q);
$groups = array();
while($r = mysql_fetch_object($rs)){
   $q2 = "SELECT DISTINCT $r->am_db_field FROM ".($r->am_class == 'ov'?'mixxxer_items':'mixxxer_groups');
   
   $rs2 = mysql_query($q2);
   while($r2 = mysql_fetch_object($rs2)){
      if(strpos($r2->{$r->am_db_field}, ',')!==false){
          $new = explode(',', $r2->{$r->am_db_field});
      }else{
          $new = array($r2->{$r->am_db_field});
      }
      foreach($new AS $tag){
      
            if(!in_array(trim($tag), $groups)){
                $groups[] = trim($tag);
            }
      
      }
   }
   
}

$js = "'".implode("', '", $groups)."'";



?>

<script type="text/javascript">
var comp_groups = new Array(<?php echo $js; ?>);
</script>