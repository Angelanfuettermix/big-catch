<?php
require('includes/application_top.php');
header('Content-type: text/html; charset=iso-8859-15');
require_once('mixxxer_manager/thumb_lib/ThumbLib.inc.php');
require_once('mixxxer_manager/am_config.php');
$pid = $_SESSION["pid"];
if (!$const){
    require_once('mixxxer_manager/am_const.php');
}
switch($_GET["what"]){
  case 'new_ov':
  require('mixxxer_manager/am_add_ov.php');
  break;

  case 'save_ov':
  require('mixxxer_manager/am_save_ov.php');
  break;

  case 'get_ov':
  require('mixxxer_manager/am_get_ov.php');
  break;

  case 'delete_ov':
  require('mixxxer_manager/am_delete_ov.php');
  break;

  case 'edit_po':
  require('mixxxer_manager/am_edit_po.php');
  break;
  
  case 'duplicate_po':
  require('mixxxer_manager/am_duplicate_po.php');
  break;

  case 'save_po':
  require('mixxxer_manager/am_save_po.php');
  break;

  case 'delete_po':
  require('mixxxer_manager/am_delete_po.php');
  break;

  case 'search_suggest':
  require('mixxxer_manager/search_suggest_helper.php');
  break;

  case 'save_profile':
  require('mixxxer_manager/am_save_profile.php');
  break;

  case 'load_profile':
  require('mixxxer_manager/am_load_profile.php');
  break;

  case 'delete_profile':
  require('mixxxer_manager/am_delete_profile.php');
  break;

  case "sel_ov_as_attr":
  require('mixxxer_manager/am_sel_ov_as_attr.php');
  break;

  case "get_attr_set":
  require('mixxxer_manager/am_get_attr_set.php');
  break;

  case "save_attr":
  require('mixxxer_manager/am_save_attr.php');
  break;
  
  case "ajax":
  require('mixxxer_manager/ajax_helper.php');
  break;




  case 'start':
  require('mixxxer_manager/am_start.php');
  break;

  case 'config':
  require('mixxxer_manager/am_config_manager.php');
  break;
  
  case 'comp_texts':
  require('mixxxer_manager/comp_texts.php');
  break;

  case 'save_config':
  require('mixxxer_manager/am_config_manager.php');
  break;


  default:
  require('mixxxer_manager/attribute_manager.php');

}


?>
