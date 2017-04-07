<?php

/**
 * @copyright (c) 2015, Alkim Media
 * @author TP <tp@alkim.de>
 */

if (isset($cPath) && xtc_not_null($cPath)) {

  require_once DIR_FS_CATALOG . 'templates/' . CURRENT_TEMPLATE . '/source/inc/am_get_category.inc.php';

  $p = explode('_', $cPath);
  $cc = $p[count($p) - 1];
  $cats = am_get_category($cc, array_slice($p, 0, -1));
  
  if (count($cats)) {
    $box_smarty = new smarty;
    $box_smarty->assign('language', $_SESSION['language']);
    $box_smarty->assign('categories', $cats);
    $box_smarty->caching = 0;
    $smarty->assign('box_SUBCATEGORIES', $box_smarty->fetch(CURRENT_TEMPLATE . '/boxes/box_subcategories.html'));
  }
}
?>