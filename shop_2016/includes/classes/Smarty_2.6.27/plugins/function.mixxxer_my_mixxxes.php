<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */


/**
 * Smarty {popup} function plugin
 *
 * Type:     function<br>
 * Name:     popup<br>
 * Purpose:  make text pop up in windows via overlib
 * @link http://smarty.php.net/manual/en/language.function.popup.php {popup}
 *          (Smarty online manual)
 * @author   Monte Ohrt <monte at ohrt dot com>
 * @param array
 * @param Smarty
 * @return string
 */
function smarty_function_mixxxer_my_mixxxes($params, &$smarty)
{  
    $cid = (int)$_SESSION["customer_id"];
    
    if($_GET["del_mix"]){
        $q = "UPDATE mixxxes SET mix_user = 0 WHERE mix_user = $cid AND mix_id = ".(int)$_GET["del_mix"];
        mysql_query($q);
    }
    
    
     
    if($cid != 0){
      $q = "SELECT * FROM mixxxes WHERE mix_user = ".$cid;
      $rs = mysql_query($q);
      if(mysql_num_rows($rs)>0){
          
          while($r = mysql_fetch_object($rs)){
              $mix = unserialize($r->mix_code);
              echo '<li><a href="'.xtc_href_link('mixxxer.php', 'mix_id='.$r->mix_id).'">'.$mix->name.'</a> (<a href="'.xtc_href_link('account.php', 'del_mix='.$r->mix_id).'">'.MIXXXER_DELETE_MIXXXES.'</a>)</li>';
          }
      
      }else{
          echo '<li>'.MIXXXER_NO_MIXXXES.'</li>';
      }
    }
}

/* vim: set expandtab: */

?>
