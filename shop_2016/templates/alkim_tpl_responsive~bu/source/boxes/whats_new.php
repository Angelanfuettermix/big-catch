<?php
  /* -----------------------------------------------------------------------------------------
   $Id: whats_new.php 4583 2013-04-05 15:25:22Z web28 $

   modified eCommerce Shopsoftware
   http://www.modified-shop.org

   Copyright (c) 2009 - 2013 [www.modified-shop.org]
   -----------------------------------------------------------------------------------------
   based on:
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(whats_new.php,v 1.31 2003/02/10); www.oscommerce.com
   (c) 2003  nextcommerce (whats_new.php,v 1.12 2003/08/21); www.nextcommerce.org
   (c) 2006 XT-Commerce

   Released under the GNU General Public License
   -----------------------------------------------------------------------------------------
   Third Party contributions:
   Enable_Disable_Categories 1.3 Autor: Mikel Williams | mikel@ladykatcostumes.com

   Released under the GNU General Public License
   ---------------------------------------------------------------------------------------*/

$template = CURRENT_TEMPLATE . '/boxes/box_whatsnew.html';

// fsk18 lock
$fsk_lock = '';
if ($_SESSION['customers_status']['customers_fsk18_display'] == '0') {
	$fsk_lock = 'AND p.products_fsk18 != 1 ';
}
// group check
$group_check = '';
if (GROUP_CHECK == 'true') {
	$group_check = 'AND p.group_permission_' . $_SESSION['customers_status']['customers_status_id'] . ' = 1 ';
}
// last daysd
$days = '';
if (0 != (int) MAX_DISPLAY_NEW_PRODUCTS_DAYS) {
  $days = 'AND p.products_date_added > "' . date('Y.m.d', mktime(1, 1, 1, date('m'), date('d') - MAX_DISPLAY_NEW_PRODUCTS_DAYS, date('Y'))) . '" ';
}

$stmt = '-- ' . __FILE__ . '::' . __LINE__ . '
        SELECT distinct p.products_id, 
          p.products_image,                                              
          p.products_tax_class_id,
          p.products_vpe,
          p.products_vpe_status,
          p.products_vpe_value,
          p.products_price,
          pd.products_name
        FROM ' . TABLE_PRODUCTS . ' AS p
          LEFT JOIN ' . TABLE_PRODUCTS_DESCRIPTION . ' AS pd 
            ON (p.products_id = pd.products_id AND pd.language_id = ' . (int) $_SESSION['languages_id'] . ' AND pd.products_name != "")
          LEFT JOIN ' . TABLE_PRODUCTS_TO_CATEGORIES . ' AS p2c
            ON p.products_id = p2c.products_id
          LEFT JOIN ' . TABLE_CATEGORIES . ' AS c
            ON (c.categories_id = p2c.categories_id AND c.categories_status = 1)
          WHERE p.products_status = 1
            ' . $fsk_lock . '
            ' . $group_check . '
            ' . $days . '
          ORDER BY p.products_date_added desc
          LIMIT ' . MAX_RANDOM_SELECT_NEW;
$query = xtc_db_query($stmt);
if (0 < xtc_db_num_rows($query)) {
  $box_content = array();
  while ($row = xtc_db_fetch_array($query)) {
    $box_content[$row['products_id']] = $product->buildDataArray($row);
  }
  
  $box_smarty = new smarty;
  $box_smarty->assign('language', $_SESSION['language']);
  $box_smarty->assign('box_content', $box_content);
  $box_smarty->assign('SPECIALS_LINK', xtc_href_link(FILENAME_SPECIALS));

  // set cache ID
  if (!CacheCheck()) {
    $box_smarty->caching = 0;
    $box_whats_new = $box_smarty->fetch($template);
  } else {
    $box_smarty->caching = 1;
    $box_smarty->cache_lifetime = CACHE_LIFETIME;
    $box_smarty->cache_modified_check = CACHE_CHECK;
    $cache_id = $_SESSION['language'] . implode('.', array_keys($box_content)) . $_SESSION['customers_status']['customers_status_name'];
    $box_whats_new = $box_smarty->fetch($template, $cache_id);
  }
  $smarty->assign('box_WHATSNEW', $box_whats_new);
}
?>