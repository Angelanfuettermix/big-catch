<?php

/**
 * @copyright (c) 2015, Alkim Media
 * @author TP <tp@alkim.de>
 */

// change smarty
$tmpSmarty = $smarty;
$smarty = $default_smarty;

// full view
require DIR_WS_BOXES . 'newsletter.php';
require DIR_WS_BOXES . 'socialmedia.php';

// tabbed
require DIR_WS_BOXES . 'best_sellers.php';
require DIR_WS_BOXES . 'whats_new.php';
require DIR_WS_BOXES . 'top.php';
require DIR_WS_BOXES . 'specials.php';
require DIR_WS_BOXES . 'last_viewed.php';

// reset smarty
$default_smarty = $smarty;
$smarty = $tmpSmarty;

?>
