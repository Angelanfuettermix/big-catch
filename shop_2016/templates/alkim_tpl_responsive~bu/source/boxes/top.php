<?php
/***********************************************
 *
 *     @author   Alkim Media (MK) - info@alkim.de
 *     @version  1.0
 ***********************************************/

$template = CURRENT_TEMPLATE . '/boxes/box_top.html';

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
          WHERE p.products_status = 1 AND
            p.products_startpage = 1
            ' . $fsk_lock . '
            ' . $group_check . '
          ORDER BY p.products_startpage_sort';
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
		$box_whats_new = $box_smarty->fetch($template);
	} else {
		$box_smarty->caching = 1;
		$box_smarty->cache_lifetime = CACHE_LIFETIME;
		$box_smarty->cache_modified_check = CACHE_CHECK;
		$cache_id = $_SESSION['language'] . implode('.', array_keys($box_content)) . $_SESSION['customers_status']['customers_status_name'];
		$box_whats_new = $box_smarty->fetch($template, $cache_id);
	}
	$smarty->assign('box_TOP', $box_whats_new);
}
?>
