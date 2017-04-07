<?php


$filter = array('mi_id', 'mg_id', 'item_id', 'mix_id');
foreach($filter AS $k){
    if(isset($_GET[$k])){
        $_GET[$k] = preg_replace('/\D/', '', $_GET[$k]);
    }
}
include_once(DIR_FS_CATALOG.'alkimmedia_modules/mixxxer/classes/mixxx.class.php');
include_once(DIR_FS_CATALOG.'alkimmedia_modules/mixxxer/classes/mixxxer.class.php');
include_once(DIR_FS_CATALOG.'alkimmedia_modules/mixxxer/classes/mixxxerHelper.class.php');
require_once (DIR_FS_CATALOG.'includes/classes/product.php');
include_once('classes/json.class.php');
if ($_SESSION['customers_status']['customers_status_id'] == 0){
   define('MIXXXER_ADMIN', 1);
}
if(defined('MIXXXER_ACTIVE')){
    $q = "SELECT COUNT(*) AS master_num FROM products WHERE products_master_mixxxer = 1";
    $rs = xtc_db_query($q);
    $r = mysql_fetch_object($rs);
}

if($r->master_num > 0){
    define('MIXXXER_MASTER_EXISTS', 1);

}

if(MIXXXER_ACTIVE == 1 && !(MIXXXER_ADMIN != 1 && MIXXXER_TESTMODE == 1)){
    define('MIXXXER_SHOW', 1);
}

if(MIXXXER_ACTIVE == 1){
    if(!is_object($_SESSION["mixxxer"]) || (int)$_SESSION["mixxxer"]->current_product == 0){
        $_SESSION["mixxxer"] = new mixxxer();
    }
}

if(MIXXXER_ACTIVE == 1){
    include_once('actions/mixxxer.action.php');
}

if($_POST["cfg"]["products_overwrite_name"]){
      $mymix = $_SESSION["mixxxer"]->loadMixxx($_POST["cfg"]["products_mix_id"]);
      foreach($_POST["products_name"] AS $k=>$v){
          $_POST["products_name"][$k] = $mymix->name;
      }
}
  

?>
