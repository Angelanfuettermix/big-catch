<?php
/* --------------------------------------------------------------
   $Id: configure.php 3072 2012-06-18 15:01:13Z hhacker $

   modified eCommerce Shopsoftware
   http://www.modified-shop.org

   Copyright (c) 2009 - 2013 [www.modified-shop.org]
   --------------------------------------------------------------
   based on:
   (c) 2000-2001 The Exchange Project  (earlier name of osCommerce)
   (c) 2002-2003 osCommerce (configure.php,v 1.13 2003/02/10); www.oscommerce.com
   (c) 2003 XT-Commerce (configure.php)
 fasfs
   Released under the GNU General Public License
   --------------------------------------------------------------*/
   
   
// --- bof -- changes -- h.koch for alkim-media -- 03.2016 --
define('KLEINUNTERNEHMER', true );
// --- eof -- changes -- h.koch for alkim-media -- 03.2016 --


// Define the webserver and path parameters
// * DIR_FS_* = Filesystem directories (local/physical)
// * DIR_WS_* = Webserver directories (virtual/URL)
  define('HTTP_SERVER', 'http://angelanfuettermix.de'); // eg, http://angelanfuettermix.alkim.de - should not be empty for productive servers
  define('HTTPS_SERVER', 'https://www.ssl-id.de/angelanfuettermix.de'); // eg, https://localhost - should not be empty for productive servers
  define('ENABLE_SSL', true); // secure webserver for checkout procedure?
  define('USE_SSL_PROXY', false); // using SSL proxy?
  define('DIR_WS_CATALOG', '/');
  define('DIR_FS_DOCUMENT_ROOT', '/mnt/weba/d3/87/53714287/htdocs/shop_2016/'); // absolute path required
  define('DIR_FS_CATALOG', '/mnt/weba/d3/87/53714287/htdocs/shop_2016/'); // absolute path required
  define('DIR_WS_ADMIN', 'admin/');
  define('DIR_WS_IMAGES', 'images/');
  define('DIR_WS_ORIGINAL_IMAGES', DIR_WS_IMAGES .'product_images/original_images/');
  define('DIR_WS_THUMBNAIL_IMAGES', DIR_WS_IMAGES .'product_images/thumbnail_images/');
  define('DIR_WS_INFO_IMAGES', DIR_WS_IMAGES .'product_images/info_images/');
  define('DIR_WS_POPUP_IMAGES', DIR_WS_IMAGES .'product_images/popup_images/');
  define('DIR_WS_ICONS', DIR_WS_IMAGES . 'icons/');
  define('DIR_WS_INCLUDES', DIR_FS_DOCUMENT_ROOT . 'includes/');
  define('DIR_WS_FUNCTIONS', DIR_WS_INCLUDES . 'functions/');
  define('DIR_WS_CLASSES', DIR_WS_INCLUDES . 'classes/');
  define('DIR_WS_MODULES', DIR_WS_INCLUDES . 'modules/');
  define('DIR_WS_LANGUAGES', DIR_FS_CATALOG . 'lang/');

  define('DIR_WS_DOWNLOAD_PUBLIC', DIR_WS_CATALOG . 'pub/');
  define('DIR_FS_DOWNLOAD', DIR_FS_CATALOG . 'download/');
  define('DIR_FS_DOWNLOAD_PUBLIC', DIR_FS_CATALOG . 'pub/');
  define('DIR_FS_INC', DIR_FS_CATALOG . 'inc/');

// define our database connection
  define('DB_SERVER', 'rdbms.strato.de'); // eg, localhost - should not be empty for productive servers
  define('DB_SERVER_USERNAME', 'U1357486');
  define('DB_SERVER_PASSWORD', 'L8Kc1buA8hje9SdH9SbC');
  define('DB_DATABASE', 'DB1357486');
  define('USE_PCONNECT', 'false'); // use persistent connections?
  define('STORE_SESSIONS', 'mysql'); // leave empty '' for default handler or set to 'mysql'
  define('DB_SERVER_CHARSET', 'latin1'); // set db charset utf8 or latin1
?>
