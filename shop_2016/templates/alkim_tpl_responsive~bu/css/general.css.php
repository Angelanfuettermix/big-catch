<?php
/* -----------------------------------------------------------------------------------------
   $Id: general.js.php 1262 2005-09-30 10:00:32Z mz $

   XT-Commerce - community made shopping
   http://www.xt-commerce.com

   Copyright (c) 2003 XT-Commerce
   -----------------------------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------------------------*/

   // Put CSS-Definitions here, these CSS-files will be loaded at the TOP of every page
?>
<link rel="stylesheet" href="alkimmedia_modules/alkim_tpl_responsive/css/dynamic.css.php" type="text/css" />
<?php
$base_path = DIR_FS_CATALOG.'templates/'.CURRENT_TEMPLATE.'/css/';
$cache_path = $base_path.'cached.css';
require_once(DIR_FS_CATALOG.'templates/'.CURRENT_TEMPLATE.'/minify/src/Converter.php');
require_once(DIR_FS_CATALOG.'templates/'.CURRENT_TEMPLATE.'/minify/src/Minify.php');
require_once(DIR_FS_CATALOG.'templates/'.CURRENT_TEMPLATE.'/minify/src/Exception.php');
require_once(DIR_FS_CATALOG.'templates/'.CURRENT_TEMPLATE.'/minify/src/CSS.php');
use MatthiasMullie\Minify;

if(!file_exists($cache_path) || filemtime($cache_path)){
    
    $minifier = new Minify\CSS($base_path.'stylesheet.css');
    $minifier->add($base_path.'responsive.css');
    $minifier->add($base_path.'jquery-ui.am.css');
    $minifier->add($base_path.'font-awesome.css');
    $minifier->add($base_path.'frontpage-slider.css');
    $minifier->add($base_path.'jquery.bxslider.css');
    $minifier->add($base_path.'responsive-tabs.css');
    $minifier->add($base_path.'superfish.css');
    $minifier->add($base_path.'ng_responsive_tables.css');
    $minifier->add($base_path.'magnific_popup.css');

    $minifier->minify($cache_path);
}
echo '<link rel="stylesheet" href="templates/'.CURRENT_TEMPLATE.'/css/cached.css" type="text/css" />';
?>
<link rel="stylesheet" href="alkim.css.php<?php if (AmHandler::$noCache) echo '?noCache=1'; ?>" type="text/css" media="screen" />
<!--<link rel="stylesheet" href="amStyle.css.php" type="text/css" media="screen" />-->
<link rel="stylesheet" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/smoothness/jquery-ui.css" type="text/css" media="screen" />
<?php
/*
<link rel="stylesheet" href="alkimmedia_modules/alkim_tpl_responsive/css/dynamic.css.php" type="text/css" />
<link rel="stylesheet" href="templates/<?php echo CURRENT_TEMPLATE; ?>/stylesheet.css" type="text/css" />
<link rel="stylesheet" href="templates/<?php echo CURRENT_TEMPLATE; ?>/css/responsive.css" type="text/css" media="screen" />
<link rel="stylesheet" href="templates/<?php echo CURRENT_TEMPLATE; ?>/css/jquery-ui.am.css" type="text/css" media="screen" />
<link rel="stylesheet" href="templates/<?php echo CURRENT_TEMPLATE; ?>/css/thickbox.css" type="text/css" media="screen" />
<link rel="stylesheet" href="templates/<?php echo CURRENT_TEMPLATE; ?>/css/font-awesome.css" type="text/css" media="screen" />
<link rel="stylesheet" href="templates/<?php echo CURRENT_TEMPLATE; ?>/css/frontpage-slider.css" type="text/css" media="screen" />
<link rel="stylesheet" href="templates/<?php echo CURRENT_TEMPLATE; ?>/css/jquery.bxslider.css" type="text/css" media="screen" />
<link rel="stylesheet" href="templates/<?php echo CURRENT_TEMPLATE; ?>/css/responsive-tabs.css" type="text/css" media="screen" />
<link rel="stylesheet" href="templates/<?php echo CURRENT_TEMPLATE; ?>/css/superfish.css" type="text/css" media="screen" />
<link rel="stylesheet" href="templates/<?php echo CURRENT_TEMPLATE; ?>/css/ng_responsive_tables.css" type="text/css" media="screen" />
<link rel="stylesheet" href="templates/<?php echo CURRENT_TEMPLATE; ?>/css/magnific_popup.css" type="text/css" media="screen" />
<link rel="stylesheet" href="alkim.css.php<?php if (AmHandler::$noCache) echo '?noCache=1'; ?>" type="text/css" media="screen" />
*/
