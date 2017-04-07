<?php
header('Content-type: application/json');

include_once('includes/application_top.php');


$json_array = array();
$json_vars = array();

if($_POST['what']=='get_price' || $_GET['what']=='get_price'){
	$actual_products_id = (int) $_POST['products_id'];
	$product = new product($actual_products_id);
	$products_price = $xtPrice->xtcGetPrice($product->data['products_id'], $format = false, (int)$_POST['products_qty'], $product->data['products_tax_class_id'], $product->data['products_price']);
	if ($product->isProduct == true){
		if(xtc_not_null($_POST['id']) && isset($_POST['id'])){
			$ids = $_POST['id'];
			foreach($ids as $mod_id => $mod_data){
				$products_attrib_price = '';
				$products_attrib_price = $xtPrice->xtcGetOptionPrice($product->data['products_id'],$mod_id,$mod_data);
				$products_attrib_price = $products_attrib_price['price'];
				$products_price = $products_price + $products_attrib_price;
			}
		}


		$products_price = $xtPrice->xtcFormat($products_price * (int)$_POST['products_qty'], true);
	}
	$json_array[] = array('update_select'=>'#new_price','update_text'=>$products_price);
}

//make the json_vars
foreach($json_array as $json_raw_id => $json_raw){
	$json_vars['items'][] = array('update_select'=>utf8_encode($json_raw['update_select']),'update_text'=>utf8_encode($json_raw['update_text']));
}
echo json_encode($json_vars);
exit;

?>
