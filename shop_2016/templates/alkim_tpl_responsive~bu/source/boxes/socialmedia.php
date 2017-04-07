<?php

/**
 * @copyright (c) 2015, Alkim Media
 * @author TP <tp@alkim.de>
 */

$box_smarty = new smarty;
$box_smarty->assign('language', $_SESSION['language']);
$box_smarty->assign('tpl_path', 'templates/' . CURRENT_TEMPLATE . '/');

// set cache ID
if (!CacheCheck()) {
	$cache = false;
	$box_smarty->caching = 0;
} else {
	$cache = true;
	$box_smarty->caching = 1;
	$box_smarty->cache_lifetime = CACHE_LIFETIME;
	$box_smarty->cache_modified_check = CACHE_CHECK;
	$cache_id = $_SESSION['language'];
}

$smarty->assign('box_SOCIALMEDIA', $box_smarty->fetch(CURRENT_TEMPLATE . '/boxes/box_socialmedia.html', $cache_id));
$smarty->assign('box_SOCIALMEDIA_CENTER', $box_smarty->fetch(CURRENT_TEMPLATE . '/boxes/box_socialmedia_center.html', $cache_id));
?>