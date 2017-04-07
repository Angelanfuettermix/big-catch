<?php
/* --------------------------------------------------------------
ALKIM MEDIA BASISMODUL
---------------------------------------------------------------------------------------*/

require('includes/application_top.php');



if ($_GET["mode"] == "ajax") {
    $ajax_helpers = glob(DIR_FS_CATALOG . 'alkimmedia_modules/*/ajax_helpers/*.php');

    $include = false;
    foreach ($ajax_helpers AS $ah) {

        if (basename($ah) == $_GET["helper"]) {

            include($ah);
            die;
        }
    }

}



$configs = glob(DIR_FS_CATALOG . 'alkimmedia_modules/*/configuration_functions.inc.php');
if (is_array($configs)) {

    foreach ($configs AS $config) {
        include_once($config);

    }
}

$saved = false;
if (is_array($_POST["cfg"])) {

    foreach ($_POST['cfg'] AS $k => $v) {
        if (is_array($v)) {
            foreach ($v AS $l => $v2) {
                if (defined($k . '_' . $l)) {
                    $val = xtc_db_input($v2);
                    $val = str_replace('\\\\\\', '\\', $val);
                    $q   = "UPDATE alkim_module_configuration SET amc_value = '" . $val . "' WHERE amc_key = '" . $k . "' AND amc_language = $l";
                    xtc_db_query($q);

                } else {

                    xtc_db_perform('alkim_module_configuration', array(
                        'amc_value' => $v2,
                        'amc_language' => $l,
                        'amc_key' => $k
                    ));
                }


            }
        } else {
            if (defined($k)) {
                xtc_db_perform('alkim_module_configuration', array(
                    'amc_value' => $v
                ), 'update', "amc_key = '" . $k . "'");
            } else {
                xtc_db_perform('alkim_module_configuration', array(
                    'amc_value' => $v,
                    'amc_key' => $k
                ));
            }
        }



    }
    $saved = true;
}
if (is_array($_POST['checboxCfg'])) {
    foreach ($_POST['checboxCfg'] AS $k => $v) {

        $val = implode('||', $v);

        if (defined($k)) {
            xtc_db_perform('alkim_module_configuration', array(
                'amc_value' => $val
            ), 'update', "amc_key = '" . $k . "'");
        } else {
            xtc_db_perform('alkim_module_configuration', array(
                'amc_value' => $val,
                'amc_key' => $k
            ));
        }




    }
    $saved = true;
}

if (is_array($_POST['cfgS'])) {
    foreach ($_POST['cfgS'] AS $k => $v) {



        if (defined($k)) {
            xtc_db_perform('configuration', array(
                'configuration_value' => $v
            ), 'update', "configuration_key = '" . $k . "'");
        } else {
            xtc_db_perform('configuration', array(
                'configuration_value' => $v,
                'configuration_key' => $k
            ));
        }




    }
    $saved = true;
}

if (is_array($_FILES["cfgFile"]["name"])) {

    foreach ($_FILES["cfgFile"]["name"] AS $key => $names) {
        $db  = array();
        $amI = 0;
        foreach ($names AS $num => $name) {
            if ($_POST["delFile"][$key][$num] == '1') {
                $db[] = '';
            } else {
                if ($name != '') {
                    $target_path        = $_POST[$key . '_DIRECTORY'] . basename($name);
                    $moveto_target_path = DIR_FS_CATALOG . $target_path;

                    if (move_uploaded_file($_FILES['cfgFile']['tmp_name'][$key][$num], $moveto_target_path)) {
                        $db[] = $target_path;
                    } else {
                        $db[] = '';
                    }
                } else {
                    if (count($_FILES["cfgFile"]["name"][$key]) == 1 && (constant($key) != '')) {
                        $db[] = constant($key);
                    } elseif (is_array(${$key}) && isset(${$key}[$amI])) {
                        $db[] = ${$key}[$amI];
                    } else {
                        $db[] = '';
                    }


                }
            }
            $amI++;
        }
        $v = implode('||', $db);
        if (defined($key)) {
            xtc_db_perform('alkim_module_configuration', array(
                'amc_value' => $v
            ), 'update', "amc_key = '" . $key . "'");
        } else {
            xtc_db_perform('alkim_module_configuration', array(
                'amc_value' => $v,
                'amc_key' => $key
            ));
        }




    }
    $saved = true;

}
if ($saved) {
    xtc_redirect('alkim_module.php?saved=1&tab=' . $_POST["tab"]);


}


if ($_GET["method"]) {

    $method = urldecode($_GET["method"]);
    $t      = explode('::', $method);
    $class  = $t[0];
    $method = $t[1];

    call_user_func_array(array(
        $class,
        $method
    ), array());
    xtc_redirect('alkim_module.php?tab=' . $_GET["tab"]);


}

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php
echo HTML_PARAMS;
?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php
echo $_SESSION['language_charset'];
?>">
<title><?php
echo TITLE;
?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<?php
if (USE_WYSIWYG == 'true' && $_GET['action'] == 'email') {
    $query = xtc_db_query("SELECT code FROM " . TABLE_LANGUAGES . " WHERE languages_id='" . $_SESSION['languages_id'] . "'");
    $data  = xtc_db_fetch_array($query);
    echo xtc_wysiwyg('gv_mail', $data['code']);
}
?>
 <link rel="stylesheet" type="text/css" href="../alkimmedia_modules/general/js/fancybox/fancybox.css"">
 <link rel="stylesheet" type="text/css" href="../alkimmedia_modules/general/css/jquery.minicolors.css"">
<script type="text/javascript" src="includes/general.js"></script>
<script type="text/javascript" src="//code.jquery.com/jquery-1.7.2.js"></script>
<script type="text/javascript" src="//code.jquery.com/ui/1.9.1/jquery-ui.js"></script>
<script type="text/javascript" src="../alkimmedia_modules/general/js/jquery.form.js"></script>
<script type="text/javascript" src="../alkimmedia_modules/general/js/jquery.minicolors.js"></script>
<script type="text/javascript" src="../alkimmedia_modules/general/js/fancybox/fancybox.js"></script>

<script>
var nEIconPath = '<?php
echo HTTP_SERVER . DIR_WS_CATALOG;
?>/alkimmedia_modules/general/js/nicEditorIcons.gif';
$(function() {
		$('#config_tabs').tabs({
                activate: function( event, ui ) {

                    if(typeof(localStorage) != 'undefined'){
                        localStorage.setItem("AM_lastTab", parseInt(ui.newTab.index()));
                    }
               },
                active: (typeof(localStorage)!='undefined'?localStorage.getItem("AM_lastTab"):1)
        });
		$('.subTabs').tabs({
                activate: function( event, ui ) {

                    if(typeof(localStorage) != 'undefined'){
                        localStorage.setItem("AM_lastSubTab", parseInt(ui.newTab.index()));
                    }
               },
                active: (typeof(localStorage)!='undefined'?localStorage.getItem("AM_lastSubTab"):1)
        });
		$('input.minicolors').minicolors({letterCase:'uppercase', theme:'bootstrap'});


		$('a[href="#tab<?php
echo $_GET["tab"];
?>"]').click();
	 $('.fancyBox').fancybox({
        'transitionIn'    :    'elastic',
        'type'    :    'iframe',
        'width'    :    800,
        'height'    :    500,


        'speedIn'        :    600,
        'speedOut'        :    200,
        'overlayShow'    :    false
    });
	});
	$(document).ready(function(){
	    nicEditors.allTextAreas({buttonList : ['bold','italic','underline','left','center','right','ol','ul','fontSize','fontFamily','fontFormat','superscript','subscript','indent','outdent','link','unlink','striketrhough','forecolor','bgcolor','image','upload','xhtml']});
	});
</script>
<script type="text/javascript" src="<?php
echo HTTP_SERVER . DIR_WS_CATALOG;
?>/alkimmedia_modules/general/js/nicEdit.js"></script>

<style>
    #config_tabs{
        font-size:10px;

    }

     #config_tabs td{
        font-size:10px;

    }

     #config_tabs textarea{
        width:270px;
        height:120px;

    }

     #config_tabs input[type=text]{
        width:270px;


    }
     #config_tabs tr{
        line-height:26px;
        vertical-align:top;

    }

    .nicEdit-selectTxt{
        line-height:16px;
    }

    #amWrapper{
        width:1100px;
        margin-left:25px;
        font-size:11px;
        font-family:Verdana;
        position:relative;

    }

    .nicEdit-main{
        background:#fff;
    }

    .td_1{
        width:200px;
    }

    .td_2{
        width:600px;
    }

    .td_3{
        width:260px;
    }

    .amTable tr:nth-child(odd) {background: #eee}
    .amTable{
        width:100%;
    }

    .subTabs{
        padding: 0 !important;
        border: none !important;
    }
    .subTabs .ui-tabs-panel{
        padding: 0 !important;
        border:1px solid #ddd !important;
    }
    .subTabs > ul{
        padding: 0 !important;
        border: none !important;
        background:none !important;
        position:relative;
        top:4px;
    }
    #amMetaNavi{
        float:right;
        margin-top:40px;

    }

    #amMetaNavi > *{
        font-size:12px;
    }

    .amHeading{
        font-weight:bold;
        font-size:1.2em;
        color:#fff;
        background:#741212;
        padding:0.6em;
        margin:0;
    }

    .config_row.type_heading > td{
        padding:0;
    }

</style>
<link rel="stylesheet" type="text/css" href="//code.jquery.com/ui/1.9.1/themes/base/jquery-ui.css">


</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
<div id="spiffycalendar" class="text"></div>
<!-- header //-->
<?php
define('NO_JQUERY', 1);
require(DIR_WS_INCLUDES . 'header.php');
?>
<!-- header_eof //-->
<!-- body //-->
<table border="0" width="100%" cellspacing="2" cellpadding="2">
  <tr>
    <td class="columnLeft2" width="<?php
echo BOX_WIDTH;
?>" valign="top">
     <table border="0" width="<?php
echo BOX_WIDTH;
?>" cellspacing="1" cellpadding="1" class="columnLeft">
        <!-- left_navigation //-->
        <?php
require(DIR_WS_INCLUDES . 'column_left.php');
?>
        <!-- left_navigation_eof //-->
    </table>
    </td>
<!-- body_text //-->
<td valign="top">
    <div id="amWrapper">
     <?php
echo '<div style="float:left;">
                                <a href="http://www.alkim.de" target="_blank">
                                  <img src="http://www.alkim.de/images/am_logo.png" style="height:60px;" />
                                </a>
                            </div>
                            <div id="amMetaNavi">
                                <a href="alkim_module.php?mode=ajax&helper=contact.php" class="fancyBox">' . BM_CONTACT . '</a> |
                                <a href="http://www.alkim.de/basismodul/faq.htm" class="fancyBox">' . BM_FAQ . '</a> |
                                <a href="http://www.alkim.de/basismodul/news.htm" class="fancyBox">' . BM_NEWS . '</a>
                            </div>
                          <h1 style="position:relative; top:15px; left:15px;">alkim media</h1>
                          <div style="clear:both; height:0px; line-height:0px;">&nbsp;</div>

                            ';
if ($_GET["action"] == 'container') {
    echo '<div style="clear:both; padding-bottom:20px;"><a href="' . xtc_href_link('alkim_module.php') . '" class="button">' . BM_BACK . '</a></div>';
    include(DIR_FS_CATALOG . 'alkimmedia_modules/' . $_GET["module"] . '/admin_container/' . $_GET["plugin"]);
    echo '<div style="clear:both; padding-top:20px;"><a href="' . xtc_href_link('alkim_module.php') . '" class="button">' . BM_BACK . '</a></div>';




} else {



    if ($_GET["saved"] == "1") {
        echo '<div style="font-family:Verdana;background:#eee; border:1px solid #ddd; color: #680F0E; font-weight:bold; padding:10px; font-size:13px; margin-bottom:10px;">' . AMC_SAVED . '</div>';
    }
?>

                <?php

    $configs = glob(DIR_FS_CATALOG . 'alkimmedia_modules/*/configuration.inc.php');
    if (is_array($configs)) {
        echo '<div id="config_tabs">';
        $amI = 0;
        echo '<ul>';
        $installed = array();

        foreach ($configs AS $config) {
            include($config);
            if (!isset($config_values[0]['type'])) {
            } else {
                $config_values = array(
                    $config_values
                );
            }

            echo '<li><a href="#tab' . $amI . '">' . $title . '</a></li>';
            $installed[$amI] = true;
            foreach ($config_values AS $subArr) {

                foreach ($subArr AS $cv) {

                    if (!defined($cv["key"]) && $cv["type"] != 'container_link' && $cv["type"] != 'HTML' && $cv["type"] != 'include' && $cv["type"] != 'heading' && $cv["type"] != 'full_html') {
                        if ($_GET["debug"] == 1) {
                            echo $cv["key"];
                        }
                        $installed[$amI] = false;
                    }
                    echo "\n\n";
                }
            }
            if ($_GET["debug"] == 1) {
                $installed[$amI] = true;
            }
            $amI++;
        }
        echo '</ul>';
        $lang = xtc_get_languages();
        $amI  = 0;

        foreach ($configs AS $config) {
            include($config);
            $a    = explode('alkimmedia_modules/', $config);
            $b    = explode('/', $a[1]);
            $code = $b[0];
            echo '<div id="tab' . $amI . '">';


            if ($installed[$amI]) {
                echo '<form method="POST" enctype="multipart/form-data"><input type="hidden" name="tab" value="' . $amI . '" />';
                $show_button = false;
                $hasTabs     = false;
                if (!isset($config_values[0]['type'])) {

                    $hasTabs = true;

                } else {

                    $config_values = array(
                        $config_values
                    );

                }

                if ($hasTabs) {
                    echo '<div class="subTabs">
                                                <ul>';
                    $subTabCount = 0;
                    foreach ($config_values AS $k => $arr) {
                        echo '<li><a href="#subTab_' . $code . '_' . $subTabCount . '">' . constant($k . '_' . 'HEADING') . '</a></li>';
                        $subTabCount++;
                    }
                    echo '</ul>';
                }
                $subTabCount = 0;
                foreach ($config_values AS $subTabKey => $subTabArr) {
                    echo '<div id="subTab_' . $code . '_' . $subTabCount . '">
                                                <table class="amTable" cellspacing="0" cellpadding="5">';


                    foreach ($subTabArr AS $cv) {
                        if (in_array($cv["type"], array(
                            'short_text',
                            'long_text',
                            'bool',
                            'select',
                            'checkbox',
                            'radio',
                            'color'
                        ))) {
                            $show_button = true;
                        }

                        if (!in_array($cv["type"], array(
                            'include',
                            'full_html',
                            'heading'
                        ))) {

                            echo '<tr class="config_row">
                                                    <td class="td_1">
                                                      <b>' . $cv["title"][$_SESSION["language"]] . '</b>
                                                    </td>
                                                    <td class="td_2">';

                            switch ($cv["type"]) {
                                case 'color':

                                    echo '<input name="cfg' . ($cv["shopConfig"] ? 'S' : '') . '[' . $cv["key"] . ']" class="minicolors" value="' . constant($cv["key"]) . '" type="text" />';


                                    break;
                                case 'short_text':

                                    if ($cv["lang"] == 1) {
                                        foreach ($lang AS $l) {

                                            echo '<div style="float:left; width:285px;">
                                                                    <div style="margin-bottom:2px;"><img src="' . HTTP_SERVER . DIR_WS_CATALOG . 'lang/' . $l["directory"] . '/admin/images/icon.gif" /></div>
                                                                    <input name="cfg[' . $cv["key"] . '][' . $l['id'] . ']" value="' . constant($cv["key"] . '_' . $l['id']) . '" type="text" />
                                                                </div>';
                                        }

                                    } else {
                                        echo '<input name="cfg' . ($cv["shopConfig"] ? 'S' : '') . '[' . $cv["key"] . ']" value="' . constant($cv["key"]) . '" type="text" />';
                                    }

                                    break;
                                case 'long_text':
                                    if ($cv["lang"] == 1) {
                                        foreach ($lang AS $l) {

                                            echo '<div style="float:left; width:285px;">
                                                                    <div style="margin-bottom:2px;"><img src="' . HTTP_SERVER . DIR_WS_CATALOG . 'lang/' . $l["directory"] . '/admin/images/icon.gif" /></div>
                                                                    <textarea name="cfg[' . $cv["key"] . '][' . $l['id'] . ']" ' . ($cv["wysiwyg"] ? 'class="wysiwyg"' : '') . '>' . constant($cv["key"] . '_' . $l['id']) . '</textarea>
                                                                </div>';
                                        }

                                    } else {
                                        echo '<textarea name="cfg' . ($cv["shopConfig"] ? 'S' : '') . '[' . $cv["key"] . ']">' . constant($cv["key"]) . '</textarea>';
                                    }

                                    break;
                                case 'bool':
                                    echo '<input name="cfg[' . $cv["key"] . ']" type="radio" value="1" ' . ((int) constant($cv["key"]) == 1 ? 'checked="checked"' : '') . ' id="' . $cv["key"] . '_1"/>
                                                          <label for="' . $cv["key"] . '_1">' . AC_YES . '</label>
                                                          <input name="cfg[' . $cv["key"] . ']" type="radio" value="0" ' . ((int) constant($cv["key"]) == 0 ? 'checked="checked"' : '') . ' id="' . $cv["key"] . '_0"/>
                                                          <label for="' . $cv["key"] . '_0">' . AC_NO . '</label>';
                                    break;
                                case 'radio':
                                    foreach ($cv["options"] AS $k => $name) {
                                        echo '<input name="cfg' . ($cv["shopConfig"] ? 'S' : '') . '[' . $cv["key"] . ']" type="radio" value="' . $k . '" ' . (constant($cv["key"]) == $k ? 'checked="checked"' : '') . ' id="' . $cv["key"] . '_' . $k . '"/>
                                                              <label for="' . $cv["key"] . '_' . $k . '">' . $name[$_SESSION["language"]] . '</label>
                                                             ';
                                    }
                                    break;
                                case 'checkbox':
                                    foreach ($cv["options"] AS $k => $name) {
                                        echo '<input name="checboxCfg[' . $cv["key"] . '][]" type="checkbox" value="' . $k . '" ' . ((constant($cv["key"]) == $k || is_array(${$cv["key"]}) && in_array($k, ${$cv["key"]})) ? 'checked="checked"' : '') . ' id="' . $cv["key"] . '_' . $k . '"/>
                                                              <label for="' . $cv["key"] . '_' . $k . '">' . $name[$_SESSION["language"]] . '</label>
                                                             ';
                                    }
                                    break;
                                case 'select':
                                    echo '<select name="cfg' . ($cv["shopConfig"] ? 'S' : '') . '[' . $cv["key"] . ']">';
                                    foreach ($cv["options"] AS $k => $name) {
                                        echo '<option value="' . $k . '" ' . (constant($cv["key"]) == $k ? 'selected="selected"' : '') . ' id="' . $cv["key"] . '_' . $k . '">' . $name[$_SESSION["language"]] . '</option>

                                                             ';
                                    }
                                    echo '</select>';
                                    break;
                                case 'multi_select':
                                    echo '<select name="checboxCfg[' . $cv["key"] . '][]" multiple="multiple">';
                                    foreach ($cv["options"] AS $k => $name) {
                                        echo '<option value="' . $k . '" ' . ((constant($cv["key"]) == $k || is_array(${$cv["key"]}) && in_array($k, ${$cv["key"]})) ? 'selected="selected"' : '') . ' id="' . $cv["key"] . '_' . $k . '">' . $name[$_SESSION["language"]] . '</option>

                                                             ';
                                    }
                                    echo '</select>';
                                    break;
                                case 'file':
                                    if (!isset($cv["num"])) {
                                        $cv["num"] = 1;
                                    }

                                    for ($i = 0; $i <= $cv["num"] - 1; $i++) {
                                        echo '<div><input name="cfgFile[' . $cv["key"] . '][' . $i . ']" type="file" /></div>';
                                        $del = ' <input type="checkbox" name="delFile[' . $cv["key"] . '][' . $i . ']" value="1" id="delFile_' . $cv["key"] . '_' . $i . '" /><label for="delFile_' . $cv["key"] . '_' . $i . '">' . AMC_DELETE . '</label>';
                                        echo '<div style="height:25px;line-height:20px;">';
                                        if ($cv["num"] == 1 && (constant($cv["key"]) != '')) {
                                            echo '<a href="' . DIR_WS_CATALOG . constant($cv["key"]) . '">' . basename(constant($cv["key"])) . '</a>' . $del;
                                        } elseif (is_array(${$cv["key"]}) && isset(${$cv["key"]}[$i]) && ${$cv["key"]}[$i] != '') {
                                            echo '<a href="' . DIR_WS_CATALOG . ${$cv["key"]}[$i] . '">' . basename(${$cv["key"]}[$i]) . '</a>' . $del;

                                        }
                                        echo '</div>';
                                    }

                                    echo '<input type="hidden" name=" ' . $cv["key"] . '_DIRECTORY" value="' . $cv["directory"] . '" />';


                                    break;
                                case 'container_link':

                                    echo '<a href="?action=container&module=' . $code . '&plugin=' . $cv["key"] . '">' . AMC_OPEN . '</a>';

                                    break;
                                case 'HTML':

                                    echo $cv["key"];

                                    break;


                            }




                            echo '   </td>
                                                    <td class="td_3">
                                                        <i>' . $cv["description"][$_SESSION["language"]] . '</i>
                                                    </td>
                                                  </tr>';

                        } else {
                            echo '<tr class="config_row type_'.$cv["type"].'">
                                                        <td colspan="3">
                                                      ';
                            switch ($cv["type"]) {
                                case 'include':
                                    include($cv["key"]);
                                    break;
                                case 'full_html':
                                    echo $cv["content"][$_SESSION["language"]];
                                    break;
                                case 'heading':
                                    echo '<h3 class="amHeading">' . $cv["title"][$_SESSION["language"]] . '</h3>';
                                    break;
                            }
                            echo '</td>
                                  </tr>';



                        }

                    }


                    echo '</table></div>'; //subTabPanel

                    $subTabCount++;
                }

                if ($hasTabs) {
                    echo '</div>';
                }


                if ($show_button) {
                    echo '<button type="submit" style="margin-top:10px; font-size:14px;">' . AC_SUBMIT . '</button>';
                }
                echo '</form>';
            } else { //if($installed[$amI])

                echo '<a href="?method=' . $installer_method . '&tab=' . $amI . '" style="display:block; width:200px; margin:auto; padding:10px; border:1px solid #ddd; background: #eee;">' . INSTALL_THIS . '</a>';


            }
            echo '<div style="clear:both;height:1px;">&nbsp;</div>

                      </div>';
            $amI++;
        } // foreach($configs AS $config)
        echo '</div>';
    } else {
        echo '<div style="font-family:Verdana;background:#eee; border:1px solid #ddd; color: #680F0E; font-weight:bold; padding:10px; font-size:13px; margin-bottom:10px;">' . AMC_NO_MODULES . '</div>';
    }
}

?>
      </div> <?php
/* <div id="amWrapper"> */
?>
</td>
<!-- body_text_eof //-->
  </tr>
</table>
<!-- body_eof //-->
<!-- footer //-->
<?php
require(DIR_WS_INCLUDES . 'footer.php');
?>
<!-- footer_eof //-->
</body>
</html>
<?php
require(DIR_WS_INCLUDES . 'application_bottom.php');
?>
