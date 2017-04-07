<?php

include ('includes/application_top.php');

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title><?php echo MIXXXER_YOUR_MIX; ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $_SESSION['language_charset']; ?>" /> 
<meta http-equiv="Content-Style-Type" content="text/css" />
<link rel="stylesheet" type="text/css" href="templates/<?php echo CURRENT_TEMPLATE;?>/stylesheet.css" />
</head>
<body class="popupproductinfo" onload="window.print()">
<?php
$products_id = (int)$_SESSION["mixxxer"]->current_product;
$mpo = new product($products_id, true);

echo '<h1>'.$mpo->data["products_name"].' - '.MIXXXER_YOUR_MIX.'</h1>
        <div>'.$mpo->data["products_description"].'</div>
       ';
$mpo = new product($products_id, true);
$mix = $_SESSION["c_mix"];
echo $mix->give_item_list_fancy();

?>
