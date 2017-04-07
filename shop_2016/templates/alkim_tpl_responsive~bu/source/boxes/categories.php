<?php
  /* -----------------------------------------------------------------------------------------
   $Id: categories.php 4200 2013-01-10 19:47:11Z Tomcraft1980 $

   modified eCommerce Shopsoftware
   http://www.modified-shop.org

   Copyright (c) 2009 - 2013 [www.modified-shop.org]
   -----------------------------------------------------------------------------------------
   based on:
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(categories.php,v 1.23 2002/11/12); www.oscommerce.com
   (c) 2003 nextcommerce (categories.php,v 1.10 2003/08/17); www.nextcommerce.org
   (c) 2006 XT-Commerce

   Released under the GNU General Public License
   -----------------------------------------------------------------------------------------
   Third Party contributions:
   Enable_Disable_Categories 1.3          Autor: Mikel Williams | mikel@ladykatcostumes.com

   Released under the GNU General Public License
   ---------------------------------------------------------------------------------------*/

$cache_id = null;
$template = CURRENT_TEMPLATE . '/boxes/box_categories.html';

$box_smarty = new smarty;
$box_smarty->caching = 0;
$box_smarty->assign('language', $_SESSION['language']);
$box_smarty->assign('tpl_path', DIR_WS_BASE . 'templates/' . CURRENT_TEMPLATE . '/');

// set cache ID
if ($box_smarty->caching) {
  $box_smarty->cache_lifetime = CACHE_LIFETIME;
  $box_smarty->cache_modified_check = CACHE_CHECK;
  $cache_id = implode('-', array(
    $_SESSION['language'],
    $_SESSION['customers_status']['customers_status_id'],
    $cPath,
  ));
}

require_once DIR_FS_CATALOG . 'templates/' . CURRENT_TEMPLATE . '/source/inc/am_get_category.inc.php';
require_once DIR_FS_INC . 'xtc_has_category_subcategories.inc.php';
require_once DIR_FS_INC . 'xtc_count_products_in_category.inc.php';

$categories = array();
if (defined('RT_HEADER_TOPMENU_HOME') && 1 == (int) RT_HEADER_TOPMENU_HOME) {
  $categories[] = array(
    'id'      => 0,
    'name'    => 'Home',
    'link'    => xtc_href_link(FILENAME_DEFAULT, xtc_get_all_get_params(array('cPath', 'products_id', 'coID', 'x', 'y')), $request_type, false),
    //'link'    => xtc_href_link(FILENAME_DEFAULT),
    'parent'  => -1,
    'level'   => 0,
    'active'  => false,
    'childs'  => array(),
  );
}
$categories = array_merge($categories, am_get_category());

$box_smarty->assign('categories', $categories);
$box_categories = $box_smarty->fetch($template);

$smarty->assign('box_CATEGORIES', $box_categories);
?>