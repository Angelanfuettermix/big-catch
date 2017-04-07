<?php
/* -----------------------------------------------------------------------------------------
   $Id: specials.php 1292 2005-10-07 16:10:55Z mz $   

   XT-Commerce - community made shopping
   http://www.xt-commerce.com

   Copyright (c) 2003 XT-Commerce
   -----------------------------------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(specials.php,v 1.30 2003/02/10); www.oscommerce.com 
   (c) 2003	 nextcommerce (specials.php,v 1.10 2003/08/17); www.nextcommerce.org

   Released under the GNU General Public License 
   ---------------------------------------------------------------------------------------*/

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

$stmt = '-- ' . __FILE__ . '::' . __LINE__ . '
        SELECT 
          p.products_id, 
          pd.products_name, 
          p.products_price, 
          p.products_tax_class_id, 
          p.products_image, 
          s.expires_date, 
          p.products_vpe, 
          p.products_vpe_status, 
          p.products_vpe_value, 
          s.specials_new_products_price 
        FROM ' . TABLE_PRODUCTS . ' AS p, 
          ' . TABLE_PRODUCTS_DESCRIPTION . ' AS pd, 
          ' . TABLE_SPECIALS . ' AS s 
        WHERE p.products_status = 1 
          AND p.products_id = s.products_id 
          AND pd.products_id = s.products_id 
          AND pd.language_id = ' . $_SESSION['languages_id'] . ' 
          AND s.status = 1 
          ' . $fsk_lock . '
          ' . $group_check . '
        ORDER BY s.specials_date_added DESC 
        LIMIT ' . MAX_RANDOM_SELECT_SPECIALS;
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
    $box_specials = $box_smarty->fetch(CURRENT_TEMPLATE . '/boxes/box_specials.html');
    $box_specials_center = $box_smarty->fetch(CURRENT_TEMPLATE . '/boxes/box_specials_center.html');
  } else {
    $box_smarty->caching = 1;
    $box_smarty->cache_lifetime = CACHE_LIFETIME;
    $box_smarty->cache_modified_check = CACHE_CHECK;
    $cache_id = $_SESSION['language'] . implode('.', array_keys($box_content)) . $_SESSION['customers_status']['customers_status_name'];
    $box_specials = $box_smarty->fetch(CURRENT_TEMPLATE . '/boxes/box_specials.html', $cache_id);
    $box_specials_center = $box_smarty->fetch(CURRENT_TEMPLATE . '/boxes/box_specials_center.html', $cache_id);
  }
  $smarty->assign('box_SPECIALS', $box_specials);
  $smarty->assign('box_SPECIALS_CENTER', $box_specials_center);
}
?>