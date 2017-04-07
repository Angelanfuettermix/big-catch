<?php

/* -----------------------------------------------------------------------------------------
   $Id: last_viewed.php 1292 2005-10-07 16:10:55Z mz $   

   XT-Commerce - community made shopping
   http://www.xt-commerce.com

   Copyright (c) 2003 XT-Commerce
   -----------------------------------------------------------------------------------------

   Released under the GNU General Public License 
   ---------------------------------------------------------------------------------------*/

if (isset($_SESSION['tracking']['products_history']) && is_array($_SESSION['tracking']['products_history']) && count($_SESSION['tracking']['products_history'])) {
  
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
          SELECT p.products_id,
            pd.products_name,
            p.products_price,
            p.products_tax_class_id,
            p.products_image,
            p2c.categories_id,
            p.products_vpe,
            p.products_vpe_status,
            p.products_vpe_value,
            cd.categories_name 
          FROM ' . TABLE_PRODUCTS . ' AS p,
            ' . TABLE_PRODUCTS_DESCRIPTION . ' AS pd,
            ' . TABLE_PRODUCTS_TO_CATEGORIES . ' AS p2c,
            ' . TABLE_CATEGORIES_DESCRIPTION . ' AS cd
          WHERE p.products_status = 1
            AND p.products_id IN (' . implode(', ', $_SESSION['tracking']['products_history']) . ')
            AND pd.products_id = p.products_id
            AND p2c.products_id = p.products_id
            AND cd.categories_id = p2c.categories_id
            AND pd.language_id = ' . (int) $_SESSION['languages_id'] . '
            AND cd.language_id = ' . (int) $_SESSION['languages_id'] . '
            ' . $group_check . '
            ' . $fsk_lock . '
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

    // set cache ID
    if (!CacheCheck()) {
      $box_smarty->caching = 0;
      $box_last_viewed = $box_smarty->fetch(CURRENT_TEMPLATE . '/boxes/box_last_viewed.html');
      $box_last_viewed_center = $box_smarty->fetch(CURRENT_TEMPLATE . '/boxes/box_last_viewed_center.html');
    } else {
      $box_smarty->caching = 1;
      $box_smarty->cache_lifetime = CACHE_LIFETIME;
      $box_smarty->cache_modified_check = CACHE_CHECK;
      $cache_id = $_SESSION['language'] . implode('.', array_keys($box_content)) . $_SESSION['customers_status']['customers_status_name'];
      $box_last_viewed = $box_smarty->fetch(CURRENT_TEMPLATE . '/boxes/box_last_viewed.html', $cache_id);
      $box_last_viewed_center = $box_smarty->fetch(CURRENT_TEMPLATE . '/boxes/box_last_viewed_center.html', $cache_id);
    }
    $smarty->assign('box_LAST_VIEWED', $box_last_viewed);
    $smarty->assign('box_LAST_VIEWED_CENTER', $box_last_viewed_center);
  }
}
?>
