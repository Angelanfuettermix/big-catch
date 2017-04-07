
<?php
function smarty_function_product_rating($params, &$smarty)
{
  global $globalProductRatings;
  if (!is_array($globalProductRatings)) {
    $globalProductRatings = array();
  }
  if (!isset($params['pid']) || '' == trim($params['pid'])) {
    global $product;
    if ($product instanceof product) {
      $params['pid'] = $product->pID;
    } else {
      return false;
    }
  }
  $pid = preg_replace('#(\{[0-9]+\})+$#', '', $params['pid']);
  if (!isset($globalProductRatings[$pid])) {
    $stmt = 'SELECT ' .
              'COUNT(`reviews_id`) as `count`, ' .
              'AVG(`reviews_rating`) AS `avg`, ' .
              'MAX(`reviews_rating`) AS `max`, ' .
              'MIN(`reviews_rating`) AS `min` ' .
            'FROM `reviews` WHERE `products_id` = ' . $pid . ' ' .
            'GROUP BY (`products_id`)';
    $query = xtc_db_query($stmt);
    $row = false;
    if (1 == xtc_db_num_rows($query)) {
      $row = xtc_db_fetch_array($query);
      $row['percent'] = $row['avg'] * 20;
    }
    $globalProductRatings[$pid] = $row;
  }
  if ($params['assign'] && '' != $params['assign']) {
    $smarty->assign($params['assign'], $globalProductRatings[$pid]);
  } else {
    return $globalProductRatings[$pid];
  }
}
?>
