<?php


/* -----------------------------------------------------------------------------------------
   $Id: search.php 1262 2005-09-30 10:00:32Z mz $   

   XT-Commerce - community made shopping
   http://www.xt-commerce.com

   Copyright (c) 2003 XT-Commerce
   -----------------------------------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce(search.php,v 1.22 2003/02/10); www.oscommerce.com 
   (c) 2003	 nextcommerce (search.php,v 1.9 2003/08/17); www.nextcommerce.org

   Released under the GNU General Public License 
   ---------------------------------------------------------------------------------------*/

require_once DIR_FS_INC . 'xtc_draw_input_field.inc.php';
require_once DIR_FS_INC . 'xtc_hide_session_id.inc.php';

$box_smarty = new smarty;
$box_smarty->caching = 0;
$box_smarty->config_load(DIR_FS_DOCUMENT_ROOT . 'alkimmedia_modules/alkim_tpl_responsive/lang/' . $_SESSION['language'] . '/lang_' . $_SESSION['language'] . '.conf', 'boxes');
$box_smarty->assign('FORM_ACTION', xtc_draw_form('quick_find', xtc_href_link(FILENAME_ADVANCED_SEARCH_RESULT, '', $request_type, false), 'get') . xtc_hide_session_id());
$box_smarty->assign('FORM_END', '</form>');
$box_smarty->assign('INPUT_SEARCH', xtc_draw_input_field('keywords', '', 'placeholder="' . IMAGE_BUTTON_SEARCH . '"', 'text', true));
$box_smarty->assign('BUTTON_SUBMIT', '<button type="submit" class="btn btn-success"><i class="fa fa-search"></i></button>');
$box_smarty->assign('LINK_ADVANCED', xtc_href_link(FILENAME_ADVANCED_SEARCH));
$smarty->assign('box_SEARCH', $box_smarty->fetch(CURRENT_TEMPLATE . '/boxes/box_search.html'));
?>
