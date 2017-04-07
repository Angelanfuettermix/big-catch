<?php

if($_POST["moveArchive"]){
foreach($_POST["moveArchive"] AS $mixId){
    
    $q = "UPDATE mixxxes SET mix_archive = MOD(mix_archive+1, 2) WHERE mix_id = ".$mixId;
    mysql_query($q);

}


}


?>

<style>
.mixxxer_fancy_list_group{
    font-weight:bold;
    clear:left;
}

.mixxxer_fancy_list_item_line img{
      float:left;
      margin-right:10px;
      height:40px;
}

.mixxxer_fancy_list_item_line{
    clear:left;
}

.mixxxer_fancy_list_item_line span{
    position:relative;
    top:10px;
}
</style>
<script>
$(document).ready(function(){
    $('.tabs').tabs();
    <?php
    if($_GET["archive"]){
        echo "$('.tab_".$_GET["archive"]."').click();";
    }
    ?>

});
</script>

<table border="0" width="100%" cellspacing="0" cellpadding="0">
  <tr>
    <td width="80" rowspan="2"><?php echo xtc_image(DIR_WS_ICONS.'heading_configuration.gif'); ?></td>
    <td class="pageHeading"></td>
  </tr>
  <tr>
    <td class="main" valign="top"></td>
  </tr>
</table>
<div class="tabs">
    <ul>
        <li><a href="#nonArchive" class="tab_0"><?php echo MIXXXER_NON_ARCHIVE; ?></a></li>
        <li><a href="#archive" class="tab_1"><?php echo MIXXXER_ARCHIVE; ?></a></li>
     </ul>
  <div id="nonArchive"><?php giveMixxxesAdmin(0); ?></div>
  <div id="archive"><?php giveMixxxesAdmin(1); ?></div>
</div>
  
        

<?php




function giveMixxxesAdmin($archive = 0){
?>
<form method="POST" action="?action=container&module=mixxxer&plugin=mixes.php&archive=<?php echo $archive; ?>">
<table border="0" width="100%" cellspacing="2" cellpadding="0">

              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent" width="10%"><?php echo 'Mix ID'; ?></td>
                <td class="dataTableHeadingContent"><?php echo 'Mix Name'; ?></td>
                <td class="dataTableHeadingContent" width="5%" align="right"><?php echo constant('MOVE_ARCHIVE_'.$archive); ?>&nbsp;</td>
              </tr>
<?php
error_reporting(E_NONE);

$mixes_query_raw = "SELECT mix_id,mix_code,mix_user from mixxxes WHERE mix_archive = ".$archive;
$mixes_split = new splitPageResults($_GET['page'], '25', $mixes_query_raw, $mixes_query_numrows);
$mixes_query = xtc_db_query($mixes_query_raw);

while($mixes = xtc_db_fetch_array($mixes_query)) {

$q = "SELECT mix_code FROM mixxxes WHERE mix_id = '".$mixes['mix_id']."'";
$rs = xtc_db_query($q);
$r = mysql_fetch_object($rs);
$mix = new mixxxer(0);
$mix = $_SESSION["mixxxer"]->loadMixxx($mixes['mix_id']);
if(is_object($mix)){
echo '                <tr class="dataTableHeadingRow"><td class="dataTableContent">' . $mixes['mix_id'] . '</td>';
echo '                <td class="dataTableContent"><a href="#" onclick="$(this).parent().find(\'div.mixxx_wr\').slideToggle(300); return false;">' . $mix->name .'</a>
                       <div style="display:none;" class="mixxx_wr">'.$mix->give_item_list_fancy().'</div>
            </td>';

?>
<td class="dataTableContent">
    <input type="checkbox" name="moveArchive[]" value="<?php echo $mixes['mix_id']; ?>" /><button type="submit">&gt;&gt;</button>
</td>
              </tr>
<?php
}
}
?>
              <tr>
                <td colspan="2">

				<table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td class="smallText" valign="top"><?php echo $mixes_split->display_count($mixes_query_numrows, '25', $_GET['page'], TEXT_DISPLAY_NUMBER_OF_MIXES); ?></td>
                    <td class="smallText" align="right"><?php echo $mixes_split->display_links($mixes_query_numrows, '25', MAX_DISPLAY_PAGE_LINKS, $_GET['page'], 'action=container&module='.$_GET["module"].'&plugin='.$_GET["plugin"]); ?></td>
                  </tr>
                </table>
                </td>
              </tr>
            </table>
            </form>
 <?php
 
 }
 
 ?>         
            
            
            
