<?php

$cache    = CacheCheck();
$cache_id = null;
$template = CURRENT_TEMPLATE . '/boxes/box_my_product_box.html';

$box_smarty = new smarty;
$box_smarty->caching = $cache ? 1 : 0;
$box_smarty->assign('language', $_SESSION['language']);
$box_smarty->assign('tpl_path', DIR_WS_BASE . 'templates/' . CURRENT_TEMPLATE . '/');

// set cache ID
if ($box_smarty->caching) {
  $box_smarty->cache_lifetime = CACHE_LIFETIME;
  $box_smarty->cache_modified_check = CACHE_CHECK;
  $cache_id = $_SESSION['language'];
}

if (!$cache || !$box_smarty->is_cached($template, $cache_id)) {
  $box_my_product_box = $box_smarty->fetch($template);
} else {
  $box_my_product_box = $box_smarty->fetch($template, $cache_id);
}
$smarty->assign('box_MY_PRODUCT_BOX', $box_my_product_box);
?>