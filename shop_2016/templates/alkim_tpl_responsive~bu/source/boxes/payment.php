<?php

/**
 * @author Alkim Media <tp@alkim.de>
 * @copyright (c) 2015, Alkim Media
 */

$template = CURRENT_TEMPLATE . '/boxes/box_payment.html';

$box_smarty = new smarty;
$box_smarty->assign('language', $_SESSION['language']);

// set cache ID
$cache = CacheCheck() ? true : false;
$box_smarty->caching = $cache ? 1 : 0;
if ($cache) {
  $box_smarty->cache_lifetime = CACHE_LIFETIME;
  $box_smarty->cache_modified_check = CACHE_CHECK;
  $cache_id = $_SESSION['language'] . $_SESSION['customers_status']['customers_status_id'];
}

if (!$box_smarty->is_cached($template, $cache_id) || !$cache) {
  $box_smarty->assign('tpl_path', 'templates/' . CURRENT_TEMPLATE . '/');
}

if (!$cache) {
  $box_payment = $box_smarty->fetch($template);
} else {
  $box_payment = $box_smarty->fetch($template, $cache_id);
}
$smarty->assign('box_PAYMENT', $box_payment);
?>