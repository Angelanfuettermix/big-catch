<?php
/* -----------------------------------------------------------------------------------------
   $Id: general.js.php 1262 2005-09-30 10:00:32Z mz $

   XT-Commerce - community made shopping
   http://www.xt-commerce.com

   Copyright (c) 2003 XT-Commerce
   -----------------------------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------------------------*/


   // this javascriptfile get includes at the BOTTOM of every template page in shop
   // you can add your template specific js scripts here

$base_path = DIR_FS_CATALOG.'templates/'.CURRENT_TEMPLATE.'/javascript/';
$cache_path = $base_path.'cache/cached.js';
require_once(DIR_FS_CATALOG.'templates/'.CURRENT_TEMPLATE.'/minify/src/Converter.php');
require_once(DIR_FS_CATALOG.'templates/'.CURRENT_TEMPLATE.'/minify/src/Minify.php');
require_once(DIR_FS_CATALOG.'templates/'.CURRENT_TEMPLATE.'/minify/src/Exception.php');
require_once(DIR_FS_CATALOG.'templates/'.CURRENT_TEMPLATE.'/minify/src/JS.php');
use MatthiasMullie\Minify;

if(!file_exists($cache_path) || filemtime($cache_path) < (time()+14400)){
    $minifier = new Minify\JS($base_path.'jquery.browser.js');
    $minifier->add($base_path.'jquery.bxslider.mod.js');
    $minifier->add($base_path.'jquery.responsiveTabs.js');
    $minifier->add($base_path.'superfish.js');
    $minifier->add($base_path.'ng_responsive_tables.js');
    $minifier->add($base_path.'magnific_popup.js');
    $minifier->add($base_path.'custom.js');
    $minifier->add($base_path.'jquery.unveil.js');
    $minifier->minify($cache_path);
}
?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js" type="text/javascript"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/jquery-ui.min.js" type="text/javascript"></script>
<script src="templates/<?php echo CURRENT_TEMPLATE; ?>/javascript/cache/cached.js" type="text/javascript"></script>

<?php if (strstr($_PHP_SELF, 'mixxxer.php' )) { ?>
<!-- Third party script for BrowserPlus runtime (Google Gears included in Gears runtime now) -->
<script type="text/javascript" src="http://bp.yahooapis.com/2.4.21/browserplus-min.js"></script>
<!-- Load plupload and all it's runtimes and finally the jQuery UI queue widget -->
<script type="text/javascript" src="<?php echo 'templates/'.CURRENT_TEMPLATE; ?>/javascript/plupload/plupload.full.js"></script>
<script type="text/javascript">
var guided_mixxxer = <?php echo MIXXXER_GUIDED; ?>;
var guided_mixxxer_cont = 1;
var mixxx_complete = '<?php echo MIXXXER_COMPLETE; ?>';
var mixxxIncompleteNoCart = parseInt(<?php echo MIXXXER_MAX_VALUE_TOO_LOW_NOCART; ?>);
var please_choose_item = '<?php echo MIXXXER_PLEASE_CHOOSE_ITEM; ?>';
var mixxxer_ajax_helper = '<?php echo xtc_href_link("mixxxer_ajax_helper.php", "from=js"); ?>';

$(document).ready(function(){

$('.mUploadWr').each(function(){
var id = $(this).attr('rel');
var outputLine = $('#file'+id);
var uploader = new plupload.Uploader({
runtimes: 'gears,html5,flash,silverlight,browserplus',
browse_button: 'pickfiles'+id,
container: 'mUploadWr'+id,
max_file_size: '10mb',
url: 'mixxxer_upload.php?id='+id,
flash_swf_url: '/templates/<?php echo CURRENT_TEMPLATE; ?>/javascript/plupload/plupload.flash.swf',
silverlight_xap_url: '/templates/<?php echo CURRENT_TEMPLATE; ?>/javascript/plupload/plupload.silverlight.xap',
filters: [{
title: "Image files",
extensions: "jpg,gif,png"
}, {
title: "Zip files",
extensions: "zip"
}],
resize: {
width: 2000,
height: 2000,
quality: 100
}
});
uploader.bind('Init', function (up, params) {
//$('#filelist').html("<div>Current runtime: " + params.runtime + "</div>");
});
$('#pickfiles').click(function (e) {
uploader.start();
e.preventDefault();
});
uploader.init();
uploader.bind('FilesAdded', function (up, files) {
$.each(files, function (i, file) {
outputLine.append('<div id="' + file.id + '">' + file.name + ' (' + plupload.formatSize(file.size) + ') <b></b>' + '</div>');
});
uploader.start();
up.refresh(); // Reposition Flash/Silverlight
});
uploader.bind('UploadProgress', function (up, file) {
outputLine.html(file.name + '('+file.percent + "%)");
});
uploader.bind('Error', function (up, err) {
outputLine.append("<div>Error: " + err.code + ", Message: " + err.message + (err.file ? ", File: " + err.file.name : "") + "</div>");
up.refresh(); // Reposition Flash/Silverlight
});
uploader.bind('FileUploaded', function (up, file, resp) {
mixxxerInit();
});

});



$('#mixer_form').submit(function(){
var go = false;
$('.fl_line').each(function(){
if($(this).html().trim() != '' && $(this).html().trim() != '&nbsp;'){
go = true;
}
});

if(!go){
var dialog = $('<div style="display:none" title=""><?php echo MIXXXER_NO_ITEMS; ?></div>').appendTo('body');
dialog.dialog();
return false;
}
});



});
</script>
<script src="<?php echo 'templates/'.CURRENT_TEMPLATE; ?>/javascript/jquery.tools.js" type="text/javascript"></script>
<script src="<?php echo 'templates/'.CURRENT_TEMPLATE; ?>/javascript/jquery.form.js" type="text/javascript"></script>
<script src="<?php echo 'templates/'.CURRENT_TEMPLATE; ?>/javascript/mixxxer.js" type="text/javascript"></script>


<?php } ?>

<?php
/*
<script src="templates/<?php echo CURRENT_TEMPLATE; ?>/javascript/jquery.browser.js" type="text/javascript"></script>
<script src="templates/<?php echo CURRENT_TEMPLATE; ?>/javascript/jquery.bxslider.mod.js" type="text/javascript"></script>
<script src="templates/<?php echo CURRENT_TEMPLATE; ?>/javascript/jquery.responsiveTabs.js" type="text/javascript"></script>
<script src="templates/<?php echo CURRENT_TEMPLATE; ?>/javascript/superfish.js" type="text/javascript"></script>
<script src="templates/<?php echo CURRENT_TEMPLATE; ?>/javascript/ng_responsive_tables.js" type="text/javascript"></script>
<script src="templates/<?php echo CURRENT_TEMPLATE; ?>/javascript/magnific_popup.js" type="text/javascript"></script>
<script src="templates/<?php echo CURRENT_TEMPLATE; ?>/javascript/custom.js" type="text/javascript"></script>
*/
?>
<?php include DIR_FS_CATALOG . 'alkimmedia_modules/general/js/jsHandler.php'; ?>

<?php // Frontpage ?>
<?php if (strpos($PHP_SELF, 'index')!==false && !isset($_GET['cPath']) && !isset($_GET['manufacturers_id'])) { ?>
  <?php // JSSOR slider
  /* BOC by Alkim Media (MK) - 2015-05-21_07:45
  <script src="templates/<?php echo CURRENT_TEMPLATE; ?>/javascript/jssor/jssor.core.js" type="text/javascript"></script>
  <script src="templates/<?php echo CURRENT_TEMPLATE; ?>/javascript/jssor/jssor.utils.js" type="text/javascript"></script>
  <script src="templates/<?php echo CURRENT_TEMPLATE; ?>/javascript/jssor/jssor.slider.js" type="text/javascript"></script>
  <script src="templates/<?php echo CURRENT_TEMPLATE; ?>/javascript/frontpage-slider.js" type="text/javascript"></script>
   EOC by Alkim Media (MK) */
?>
<script type="text/javascript">
    $(document).ready(function(){
       $('#frontpage-slider').bxSlider({
          mode: 'fade',
          captions: true,
          nextSelector: '#frontpage-slider-next',
          mode: 'horizontal',
          prevSelector: '#frontpage-slider-prev',
          nextText: '<i class="fa fa-chevron-right fa-2x"></i>',
          prevText: '<i class="fa fa-chevron-left fa-2x"></i>',
          auto:true
        });
    });

</script>
<?php } ?>
