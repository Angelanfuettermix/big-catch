<?php
/* -----------------------------------------------------------------------------------------
   $Id: admin.php 1262 2005-09-30 10:00:32Z mz $   

   XT-Commerce - community made shopping
   http://www.xt-commerce.com

   Copyright (c) 2003 XT-Commerce
   -----------------------------------------------------------------------------------------
   based on: 
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommercebased on original files from OSCommerce CVS 2.2 2002/08/28 02:14:35 www.oscommerce.com 
   (c) 2003	 nextcommerce (admin.php,v 1.12 2003/08/13); www.nextcommerce.org

   Released under the GNU General Public License 
   ---------------------------------------------------------------------------------------*/

require_once DIR_FS_INC . 'xtc_image_button.inc.php';

// reset var
$box_smarty = new smarty;
$box_smarty->caching = 0;
$box_smarty->assign('tpl_path','templates/'.CURRENT_TEMPLATE.'/');
$box_smarty->assign('language', $_SESSION['language']);

$box_smarty->assign('adminLink', xtc_href_link_admin(FILENAME_START, '', 'NONSSL'));
if (isset($product) && $product instanceof product && $product->isProduct()) {
  $box_smarty->assign('editProduct', xtc_href_link_admin(FILENAME_EDIT_PRODUCTS, 'cPath=' . $cPath . '&amp;pID=' . $product->data['products_id'] . '&amp;action=new_product'));
}

$smarty->assign('box_ADMIN', $box_smarty->fetch(CURRENT_TEMPLATE . '/boxes/box_admin.html'));

?>