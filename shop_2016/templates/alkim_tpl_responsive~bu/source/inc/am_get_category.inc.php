<?php

/**
 * @copyright (c) 2015, Alkim Media
 * @author TP <tp@alkim.de>
 */

function am_get_category($parent = 0, $path = array()) {
  global $cPath;
  $pPath = explode('_', $cPath);
  $where = array();
  $where[] = 'c.categories_status = 1';
  $where[] = 'c.parent_id = ' . $parent;
  $where[] = 'c.categories_id = cd.categories_id';
  $where[] = 'cd.language_id = ' . (int) $_SESSION['languages_id'];
  $where[] = 'trim(cd.categories_name) != ""';
  if (GROUP_CHECK == 'true') {
    $where[] = 'c.group_permission_' . $_SESSION['customers_status']['customers_status_id'] . ' = 1 ';
  }
  $stmt = 'SELECT c.categories_id, ' .
            'cd.categories_name, ' .
            'c.parent_id ' .
          'FROM ' . TABLE_CATEGORIES . ' AS c, ' .
            TABLE_CATEGORIES_DESCRIPTION . ' AS cd ' .
          'WHERE ' . implode(' AND ',  $where) . ' ' .
          'ORDER BY sort_order, cd.categories_name';
  $query = xtc_db_query($stmt);
  if (0 == xtc_db_num_rows($query)) {
    return array();
  }
  $categories = array();
  $sep = 'true' == SEARCH_ENGINE_FRIENDLY_URLS ? '&' : '&amp;';
  while ($c = xtc_db_fetch_array($query)) {
    $p = array_merge($path, array($c['categories_id']));
    $params = 'cPath=' . implode('_', $p) . $sep . xtc_get_all_get_params(array('cPath', 'products_id', 'coID', 'x', 'y'));
    $categories[] = array(
      'id'      => $c['categories_id'],
      'name'    => $c['categories_name'],
      'link'    => xtc_href_link(FILENAME_DEFAULT, $params, $request_type, false),
      'parent'  => $c['parent_id'],
      'level'   => count($path),
      'active'  => in_array($c['categories_id'], $pPath),
      'childs'  => am_get_category($c['categories_id'], $p),
    );
  }
  return $categories;
}
?>