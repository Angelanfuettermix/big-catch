<?php
define('AGB_CONTENT_ID', 3);
define('REVOCATION_CONTENT_ID', 3);
if(isset($_GET["pdfCoID"]) && isset($_GET["format"]) && $_GET["format"] == 'pdf'){

require_once(DIR_FS_CATALOG.'alkimmedia_modules/alkim_tpl_responsive/classes/html2pdf/html2pdf.class.php');

$content_body = ''; //DokuMan - set undefined variable
$group_check = ''; //DokuMan - set undefined variable
if (GROUP_CHECK == 'true') {
	$group_check = "and group_ids LIKE '%c_".$_SESSION['customers_status']['customers_status_id']."_group%'";
}
$shop_content_query = xtc_db_query("SELECT
                     content_id,
                     content_title,
                     content_heading,
                     content_text,
                     content_file
                     FROM ".TABLE_CONTENT_MANAGER."
                     WHERE content_group='".(int) $_GET['pdfCoID']."' ".$group_check."
                     AND languages_id='".(int) $_SESSION['languages_id']."'");
$shop_content_data = xtc_db_fetch_array($shop_content_query);
if ($_GET['pdfCoID'] != 7) {    
    require (DIR_WS_CLASSES.'Smarty_2.6.27/Smarty.class.php');

	if ($shop_content_data['content_file'] != '') {

		ob_start();
		if (strpos($shop_content_data['content_file'], '.txt'))
			echo '<pre>';
		include (DIR_FS_CATALOG.'media/content/'.$shop_content_data['content_file']);
		if (strpos($shop_content_data['content_file'], '.txt'))
			echo '</pre>';
		$content = ob_get_contents();
		ob_end_clean();

	} else {
		$content = $shop_content_data['content_text'];
	}
        $html2pdf = new HTML2PDF('P', 'A4', 'fr');
        $html2pdf->setDefaultFont('Arial');
        $html2pdf->writeHTML($content);
        $html2pdf->Output($shop_content_data['content_heading'].'.pdf', 'D');
        die;

}
}

