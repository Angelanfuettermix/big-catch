<?php
define('BASE_MG_ID', 1);
$filter = array('mi_id', 'mg_id', 'item_id', 'mix_id');
foreach($filter AS $k){
    if(isset($_GET[$k])){
        $_GET[$k] = preg_replace('/\D/', '', $_GET[$k]);
    }
}
function cmp_by_qty($a, $b) {
  return $b["qty"] - $a["qty"];
}


?>
