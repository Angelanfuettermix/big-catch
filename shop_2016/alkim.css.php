<?php
header('Content-Type:text/css');

$useFromCache = false;
$cacheFile = 'alkimmedia_modules/general/cache/cached.css.php';
$readFromCache = (file_exists($cacheFile) && filemtime($cacheFile) > (time()-7200));

if($readFromCache && !isset($_GET["noCache"]) && $_GET["noCache"] != 1){
        include($cacheFile);
        die;
}



include('includes/application_top.php');
ob_start();
?>

.amHightlightText{
    color:<?php echo AM_FONT_HIGHLIGHT;?> !important;
}
.amHightlightBgI1{
    background:<?php echo AM_BACKGROUND_HIGHLIGHT_I_1;?> !important;
}
.amHightlightBgI2{
    background:<?php echo AM_BACKGROUND_HIGHLIGHT_I_2;?> !important;
}
.amHightlightBgL1{
    background:<?php echo AM_BACKGROUND_HIGHLIGHT_L_1;?> !important;
}
.amHightlightBgL2{
    background:<?php echo AM_BACKGROUND_HIGHLIGHT_L_2;?> !important;
}
.amHightlightBgL3{
    background:<?php echo AM_BACKGROUND_HIGHLIGHT_L_3;?> !important;
}
.amHightlightBorderLight{
    border-color:<?php echo AM_BORDER_LIGHT;?> !important;
}
.amHightlightBorderDark{
    border-color:<?php echo AM_BORDER_DARK;?> !important;
}

.amHighlightGradientI1{
    <?php printGradient(AM_BACKGROUND_HIGHLIGHT_I_1, AM_BACKGROUND_HIGHLIGHT_I_2); ?>
}
.amHighlightGradientI2{
   
    <?php printGradient(AM_BACKGROUND_HIGHLIGHT_I_2, AM_BACKGROUND_HIGHLIGHT_I_1); ?>
}

.amHighlightGradientL1{
    <?php printGradient(AM_BACKGROUND_HIGHLIGHT_L_1, AM_BACKGROUND_HIGHLIGHT_L_2); ?>
}
.amHighlightGradientL2{
    <?php printGradient(AM_BACKGROUND_HIGHLIGHT_L_2, AM_BACKGROUND_HIGHLIGHT_L_1); ?>
}

<?php

$files = glob(DIR_FS_CATALOG.'alkimmedia_modules/*/css/autoinclude.*');
if(is_array($files)){
    foreach($files AS $file){
        include($file);
    }
}

$content = ob_get_contents();

ob_end_clean();
echo $content;
if(isset($cacheFile)){
    file_put_contents($cacheFile, $content);
}

$q = "DELETE FROM whos_online WHERE last_page_url = '".xtc_db_prepare_input($_SERVER['REQUEST_URI'])."'";
xtc_db_query($q);

function printGradient($c1, $c2){
?>
background: <?php echo $c1; ?> !important; /* Old browsers */
/* IE9 SVG, needs conditional override of 'filter' to 'none' */
background: -moz-linear-gradient(top,  <?php echo $c1; ?> 0%, <?php echo $c2; ?> 100%) !important; /* FF3.6+ */
background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,<?php echo $c1; ?>), color-stop(100%,<?php echo $c2; ?>)) !important; /* Chrome,Safari4+ */
background: -webkit-linear-gradient(top,  <?php echo $c1; ?> 0%,<?php echo $c2; ?> 100%) !important; /* Chrome10+,Safari5.1+ */
background: -o-linear-gradient(top,  <?php echo $c1; ?> 0%,<?php echo $c2; ?> 100%) !important; /* Opera 11.10+ */
background: -ms-linear-gradient(top,  <?php echo $c1; ?> 0%,<?php echo $c2; ?> 100%) !important; /* IE10+ */
background: linear-gradient(to bottom,  <?php echo $c1; ?> 0%,<?php echo $c2; ?> 100%) !important; /* W3C */
filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='<?php echo $c1; ?>', endColorstr='<?php echo $c2; ?>',GradientType=0 ) !important; /* IE6-8 */
<?php
}
?>
