<?php
/*
   *  muesli-mixes.php
*/

//define('DEFAULT_PRODUCTS_TIP_ID','1');

require('includes/application_top.php');
require_once (DIR_WS_CLASSES.'mix.class.php');
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $_SESSION['language_charset']; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<script type="text/javascript" src="includes/general.js"></script>
<script type="text/javascript" src="includes/javascript/jquery.tools.min.js"></script>
<style>

/* tooltip styling. by default the element to be styled is .tooltip*/
.tooltip {
	background-color:#000;
	border:1px solid #fff;
	padding:10px 15px;
	width:200px;
	display:none;
	color:#fff;
	text-align:left;
	font-size:12px;

	/* outline radius for mozilla/firefox only */
	-moz-box-shadow:0 0 10px #000;
	-webkit-box-shadow:0 0 10px #000;
}

/* style the trigger elements */
#dyna img {
	border:0;
	cursor:pointer;
	margin:0 8px;
}
</style>

</head>
<body bgcolor="#FFFFFF" onload="SetFocus();">
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>

<table border="0" width="100%" cellspacing="2" cellpadding="2">
  <tr>
    <td class="columnLeft2" width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="1" cellpadding="1" class="columnLeft">
<?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
    </table>
		</td>
    <td class="boxCenter" width="100%" valign="top">

		<table border="0" width="100%" cellspacing="0" cellpadding="2">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
  <tr>
    <td width="80" rowspan="2"><?php echo xtc_image(DIR_WS_ICONS.'heading_configuration.gif'); ?></td>
    <td class="pageHeading"><?php echo 'Zusammenfassung aller M&uuml;sli Mischungen'; ?></td>
  </tr>
  <tr>
    <td class="main" valign="top">M&uuml;sli Konfigurator</td>
  </tr>
</table>

	</td>
      </tr>
      <tr>
        <td valign="top">

				<table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td valign="top">
						<table border="0" width="100%" cellspacing="2" cellpadding="0">

              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent" width="10%"><?php echo 'Mix ID'; ?></td>
                <td class="dataTableHeadingContent"><?php echo 'Mix Name'; ?></td>
                <td class="dataTableHeadingContent" width="5%" align="right"><?php echo 'Infos'; ?>&nbsp;</td>
              </tr>
<?php

$mixes_query_raw = "SELECT mix_id,mix_code,mix_user from mixes";
$mixes_split = new splitPageResults($_GET['page'], '25', $mixes_query_raw, $mixes_query_numrows);
$mixes_query = xtc_db_query($mixes_query_raw);

while($mixes = xtc_db_fetch_array($mixes_query)) {
$q = "SELECT mix_code FROM mixes WHERE mix_id = '".$mixes['mix_id']."'";
$rs = mysql_query($q);
$r = mysql_fetch_object($rs);
$mix = new mix(0);
$mix = load_mix($mixes['mix_id']);

echo '                <tr class="dataTableHeadingRow"><td class="dataTableContent">' . $mixes['mix_id'] . '</td>';
echo '                <td class="dataTableContent"><div id="dyna">' . $mix->name .'</td>';
echo '                <td class="dataTableContent">' . '<div id="dyna">' . '<img src="images/info.gif" title="'.$mix->name.' - ID: '.$mixes['mix_id'].'<br /><br />'.$mix->give_feature_list_plain().'"/></div>' . '</td>';
?>
              </tr>
<?php
}
?>
              <tr>
                <td colspan="2">

								<table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td class="smallText" valign="top"><?php echo $mixes_split->display_count($mixes_query_numrows, '25', $_GET['page'], TEXT_DISPLAY_NUMBER_OF_MIXES); ?></td>
                    <td class="smallText" align="right"><?php echo $mixes_split->display_links($mixes_query_numrows, '25', MAX_DISPLAY_PAGE_LINKS, $_GET['page']); ?></td>
                  </tr>
                </table></td>
              </tr>
            </table></td>
          </tr>
        </table></td>
      </tr>
    </table></td>
<!-- body_text_eof //-->
  </tr>
</table>
<!-- body_eof //-->

<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
<br />
<script>

// initialize tooltip
$("#dyna img[title]").tooltip({

   // place tooltip on the right edge
	position: "center right",

	// a little tweaking of the position
	offset: [-2, 10],

	// use the built-in fadeIn/fadeOut effect
	effect: "fade",

	// custom opacity setting
	opacity: 0.7

// add dynamic plugin with optional configuration for bottom edge
}).dynamic({ bottom: { direction: 'down', bounce: true } });
</script>
<style>

/* override the arrow image of the tooltip */
.tooltip.bottom {
	background:url(/tools/img/tooltip/black_arrow_bottom.png);
	padding-top:40px;
	height:55px;
}

.tooltip.bottom {
	background:url(/tools/img/tooltip/black_arrow_bottom.png);
}
</style>
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>