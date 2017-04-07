<?php
/* -----------------------------------------------------------------------------------------
   $Id: languages.php 1262 2005-09-30 10:00:32Z mz $   

   XT-Commerce - community made shopping
   http://www.xt-commerce.com

   Copyright (c) 2003 XT-Commerce
   -----------------------------------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(languages.php,v 1.14 2003/02/12); www.oscommerce.com
   (c) 2003	 nextcommerce (languages.php,v 1.8 2003/08/17); www.nextcommerce.org 

   Released under the GNU General Public License 
   ---------------------------------------------------------------------------------------*/

require_once DIR_FS_INC . 'xtc_get_all_get_params.inc.php';

if (!isset($lng) || !is_object($lng)) {
  require_once DIR_WS_CLASSES . 'language.php';
  $lng = new language;
}

$languages = array();
$sep = 'true' == SEARCH_ENGINE_FRIENDLY_URLS ? '&' : '&amp;';
foreach ($lng->catalog_languages as $k => $v) {
  $languages[$v['id']] = array(
    'id' => $v['id'],
    'code' => $v['code'],
    'title' => $v['name'],
    'icon' => xtc_image('lang/' .  $v['directory'] . '/' . $v['image'], $v['name']),
    'link' => xtc_href_link(basename($PHP_SELF), 'language=' . $k . $sep . xtc_get_all_get_params(array('language', 'currency', 'x', 'y')), $request_type),
  );
}

// dont show box if there's only 1 language
if (1 < count($languages)) {
  $box_smarty = new smarty;
  $box_smarty->caching = 0;
  $box_smarty->config_load(DIR_FS_DOCUMENT_ROOT . 'alkimmedia_modules/alkim_tpl_responsive/lang/' . $_SESSION['language'] . '/lang_' . $_SESSION['language'] . '.conf', 'boxes');
  $box_smarty->assign('selected', $languages[$_SESSION['languages_id']]);
  $box_smarty->assign('languages', $languages);
  $smarty->assign('box_LANGUAGES', $box_smarty->fetch(CURRENT_TEMPLATE . '/boxes/box_languages.html'));
}
?>