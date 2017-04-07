<?php

if($_GET["reset_mixxxer"]){
    unset($_SESSION["mixxxer"]);
    unset($_SESSION["c_mix"]);
    xtc_redirect(xtc_href_link('mixxxer.php'));
}
if($_POST["action"]=='add_current_mix'){
        $_SESSION["mixxxer"]->addMixxxToCart(0, (int)$_POST["qty"]);
        $_SESSION["mixxxer"]->deleteCurrentMixxx();
        xtc_redirect(xtc_href_link(FILENAME_SHOPPING_CART));
}

$real_pid = -1;


if($_GET['products_id']){
  if(strpos($_GET['products_id'], '{')!==false){
    $t = explode('{', $_GET['products_id']);
    $real_pid = $t[0];
  }else{
    $real_pid = $_GET['products_id'];
  }
}else{
    //$_GET['products_id'] = $_SESSION["mixxxer"]->master_mixxxer_pid;
    $real_pid = $_SESSION["mixxxer"]->master_mixxxer_pid;
}

if(in_array($real_pid, $_SESSION["mixxxer"]->mixxxer_products) && (strstr($PHP_SELF, '/'.FILENAME_PRODUCT_INFO ) || strstr($PHP_SELF, 'mixxxer.php'))){
     
        $_SESSION["mixxxer"]->current_product  = $real_pid;
        if(strpos($_GET['products_id'], '{')!==false){
            $t = explode('{', $_GET['products_id']);
            $t = explode('}', $t[1]);
            $pov_id = $t[1];
            $_SESSION["mixxxer"]->loadMixxxAsCurrent($_SESSION["mixxxer"]->getMixxxIdFromPovId($pov_id));
            $_SESSION['c_mix']->wk_pov_id= $pov_id;
        }else{
           if(count($_SESSION["mixxxer"]->mixxxes[$real_pid]) > 0){
               
                $cInd = $_SESSION["mixxxer"]->mixxxerCurrentIndex[$real_pid];
                if(is_object($_SESSION["mixxxer"]->mixxxes[$real_pid][$cInd])){
                       
                         
                     $_SESSION["mixxxer"]->setCurrentMixxx($cInd);
                }else{
                        $i = 0;

                        foreach ($_SESSION["mixxxer"]->mixxxes[$real_pid] as $key => $value) {
                            $i++;

                            if ( $i == 1 ) break;
                        } 
                        $_SESSION["mixxxer"]->setCurrentMixxx($key);
                }
           }else{
                $_SESSION["mixxxer"]->createMixxx(true);
           }
        }
        if(strstr($PHP_SELF, FILENAME_PRODUCT_INFO )) 
            xtc_redirect(xtc_href_link('mixxxer.php', 'products_id='.$real_pid));
}

if(isset($_GET["mix_num"])){
      $old_mp = $_SESSION['c_mix']->mixxxer_product;
      $_SESSION["mixxxer"]->setCurrentMixxx((int)$_GET["mix_num"]);
      
      if($_SESSION['c_mix']->mixxxer_product != $old_mp){
        define('RELOAD', 1);
      }
} 

if(isset($_GET["mix_id"])){
   $_SESSION["mixxxer"]->loadMixxxAsCurrent((int)$_GET["mix_id"]);
}
    

if(!is_object($_SESSION["c_mix"])){
    $_SESSION["mixxxer"]->createMixxx(true);
 } 
?>
