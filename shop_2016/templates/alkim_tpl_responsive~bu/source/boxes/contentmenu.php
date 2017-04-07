<?php

/**
 * @author Alkim Media <tp@alkim.de>
 * @copyright (c) 2015, Alkim Media
 */

$template = CURRENT_TEMPLATE . '/boxes/box_contentmenu.html';

// get content falgs
$group_check = '';
if ('true' == GROUP_CHECK) {
  $group_check = 'AND group_ids LIKE "%c_' . $_SESSION['customers_status']['customers_status_id'] . '_group%" ';
}
$flags = array(
  'contentmenu_1',
  'contentmenu_2',
  'contentmenu_3',
  'contentmenu_4',
);
$stmt = '-- ' . __FILE__ . '::' . __LINE__ . ' 
        SELECT c.content_id, 
          c.content_title,
          c.content_group,
          f.file_flag_name
        FROM ' . TABLE_CONTENT_MANAGER . ' AS c,
          ' . TABLE_CM_FILE_FLAGS . ' AS f
        WHERE c.file_flag = f.file_flag 
          AND c.languages_id = ' . (int) $_SESSION['languages_id'] . '
          AND c.content_status = 1
          AND f.file_flag_name IN ("' . implode('", "', $flags) . '")
          ' . $group_check . '
        ORDER BY c.file_flag, c.sort_order, c.content_title';
$query = xtc_db_query($stmt);
$content = array();
if (0 < xtc_db_num_rows($query)) {
  while ($row = xtc_db_fetch_array($query)) {
    if (!isset($content[$row['file_flag_name']])) {
      $content[$row['file_flag_name']] = array();
    }
		$params = '';
		if (SEARCH_ENGINE_FRIENDLY_URLS == 'true') {
			$params = '&content=' . xtc_cleanName($row['content_title']);
    }
    $content[$row['file_flag_name']][$row['content_title']] = xtc_href_link(FILENAME_CONTENT, 'coID=' . $row['content_group'] . $params);
  }
}

foreach ($content as $name => $links) {
  $box_smarty = new smarty;
  $box_smarty->assign('language', $_SESSION['language']);
  $box_smarty->config_load(DIR_FS_DOCUMENT_ROOT . 'alkimmedia_modules/alkim_tpl_responsive/lang/' . $_SESSION['language'] . '/lang_' . $_SESSION['language'] . '.conf', 'boxes');
  
  // set cache ID
  $cache = CacheCheck() ? true : false;
  $box_smarty->caching = $cache ? 1 : 0;
  if ($cache) {
    $box_smarty->cache_lifetime = CACHE_LIFETIME;
    $box_smarty->cache_modified_check = CACHE_CHECK;
    $cache_id = $name . $_SESSION['language'] . $_SESSION['customers_status']['customers_status_id'];
  }
  
  if (!$box_smarty->is_cached($template, $cache_id) || !$cache) {
    $box_smarty->assign('tpl_path', 'templates/' . CURRENT_TEMPLATE . '/');
    $box_smarty->assign('title', constant('RT_FOOTER_HEADING_' . strtoupper($name)));
    $box_smarty->assign('links', $links);
  }
  
  if (!$cache) {
    $box_content = $box_smarty->fetch($template);
  } else {
    $box_content = $box_smarty->fetch($template, $cache_id);
  }
  $smarty->assign('box_' . strtoupper($name), $box_content);
  unset($name); // $name also used in the contact form
}
?>