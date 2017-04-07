<?php
$q = "SELECT * FROM orders_products_attributes WHERE orders_products_id = ".(int)$_GET["id"];
$rs = xtc_db_query($q);
$r = xtc_db_fetch_array($rs);
$html = $r["orders_products_attributes_label_html"];
mixxxerHelper::generatePdfFromHtml($html);
die;
