<?php
$profile_name = $_GET["profile_name"];

$q = "DELETE FROM mm_attr_profile WHERE mia_products_id = '$profile_name'";

xtc_db_query($q);

echo AM_DELETE_SUCCESS;










?>
