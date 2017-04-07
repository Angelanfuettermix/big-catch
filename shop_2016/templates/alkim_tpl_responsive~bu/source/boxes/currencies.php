<?php
/* -----------------------------------------------------------------------------------------
   $Id: currencies.php 1262 2005-09-30 10:00:32Z mz $   

   XT-Commerce - community made shopping
   http://www.xt-commerce.com

   Copyright (c) 2003 XT-Commerce
   -----------------------------------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(currencies.php,v 1.16 2003/02/12); www.oscommerce.com 
   (c) 2003	 nextcommerce (currencies.php,v 1.11 2003/08/17); www.nextcommerce.org

   Released under the GNU General Public License 
   ---------------------------------------------------------------------------------------*/

require_once DIR_FS_INC . 'xtc_get_all_get_params.inc.php';

$currencies = array();
if (isset($xtPrice) && is_object($xtPrice)) {
  $sep = 'true' == SEARCH_ENGINE_FRIENDLY_URLS ? '&' : '&amp;';
  foreach ($xtPrice->currencies as $k => $v) {
    $currencies[] = array(
      'id' => $k,
      'title' => $v['title'],
      'code' => $v['code'],
      'link' => xtc_href_link(basename($PHP_SELF), 'currency=' . $k . $sep . xtc_get_all_get_params(array('language', 'currency', 'x', 'y')), $request_type),
    );
  }
}

// dont show box if there's only 1 currency
if (1 < count($currencies)) {
  $box_smarty = new smarty;
  $box_smarty->caching = 0;
  $box_smarty->config_load(DIR_FS_DOCUMENT_ROOT . 'alkimmedia_modules/alkim_tpl_responsive/lang/' . $_SESSION['language'] . '/lang_' . $_SESSION['language'] . '.conf', 'boxes');
  $box_smarty->assign('selected', $_SESSION['currency']);
  $box_smarty->assign('currencies', $currencies);
  $smarty->assign('box_CURRENCIES', $box_smarty->fetch(CURRENT_TEMPLATE . '/boxes/box_currencies.html'));
}
 ?>