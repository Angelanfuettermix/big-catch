<?php
include ('includes/application_top.php');
header('P3P: CP="CURa ADMa DEVa PSAo PSDo OUR BUS UNI PUR INT DEM STA PRE COM NAV OTC NOI DSP COR"');
include(DIR_FS_INC.'xtc_get_products_image.inc.php');
$products_id = $_SESSION["mixxxer"]->current_product;

if($_GET["action"] != 'upload'){
      header("Content-Type: application/x-json;");
      if(!isset($_GET["action"])){
          $_SESSION["c_mix"]->precheck();
      }
      
}else{

  	  
      
       $error = "";
    	$msg = "";
    	$fileElementName = 'mixxxer_upload_file';
    
    	if(!empty($_FILES[$fileElementName]['error']))
    	{
    		switch($_FILES[$fileElementName]['error'])
    		{
    
    			case '1':
    				$error = 'The uploaded file exceeds the upload_max_filesize directive in php.ini';
    				break;
    			case '2':
    				$error = 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form';
    				break;
    			case '3':
    				$error = 'The uploaded file was only partially uploaded';
    				break;
    			case '4':
    				$error = 'No file was uploaded.';
    				break;
    
    			case '6':
    				$error = 'Missing a temporary folder';
    				break;
    			case '7':
    				$error = 'Failed to write file to disk';
    				break;
    			case '8':
    				$error = 'File upload stopped by extension';
    				break;
    			case '999':
    			default:
    				$error = 'No error code avaiable';
    		}
    	}elseif(empty($_FILES[$fileElementName]['tmp_name']) || $_FILES[$fileElementName]['tmp_name'] == 'none')
    	{
    		$error = 'No file was uploaded..';
    	}else 
    	{
    			$msg .= " File Name: " . $_FILES[$fileElementName]['name'] . ", ";
    			$msg .= " File Size: " . @filesize($_FILES[$fileElementName]['tmp_name']);
    			//for security reason, we force to remove all uploaded file
    				
    			$new_name = 'mixxxer_uploads/'.time().'_'.$_FILES[$fileElementName]['name'];
    			copy($_FILES[$fileElementName]['tmp_name'], DIR_FS_CATALOG.$new_name);
    			$_SESSION["c_mix"]->add_file($new_name);
    			
    			
    	}		
    	echo "{";
    	echo				"error: '" . $error . "',\n";
    	echo				"msg: '" . $msg . "'\n";
    	echo "}";
    	die;
}



$json = new Services_JSON();

$item_id = $_GET["mi_id"];
$ret["max_val"] = $_SESSION["c_mix"]->calc_max_val();
switch($_GET["action"]) {
    
    case "save_name":
         $_SESSION["c_mix"]->name = $_GET["name"];
         die;
         break;
   
    case "save_comment":
         $_SESSION["c_mix"]->comment = $_GET["c"];
         die;
         break;
         
    case "save_max_cost":
         $_SESSION["c_mix"]->max_cost = (float)str_replace(',', '.', $_GET["max_cost"]);
         die;
         break;
         
    case "del_file":
          $_SESSION["c_mix"]->del_file($_GET["fid"]);
          break; 
    
    case "del_max_cost":
         $_SESSION["c_mix"]->max_cost = '';
         break;
         
    case "del_text":
         $_SESSION["c_mix"]->my_text = '';
         break;
         
    case "save_text":
         $_SESSION["c_mix"]->my_text = $_GET["text"];
         die;
         break;
         
    case "set_max_value":
        if((float)$_GET["max_value"] >= $_SESSION["c_mix"]->free_val_1){
            $_SESSION["c_mix"]->max_val = (float)$_GET["max_value"];
        }else{
            $ret["dialog"] = MIXXXER_MAX_VALUE_TOO_LOW;
            $ret["reset_slider"] = 1;
        }
        break;
        
  
    case "new_mix":
        $_SESSION["mixxxer"]->createMixxx(true);
        $ret["reset_guided"] = 1;
        break;
    case "save_c_text":
        $_SESSION["c_mix"]->addCText($_GET["item_id"], $_GET["text"]);
        break;
    
       
    case "save_mix":
       
       if(count($_SESSION["c_mix"]->items)==0){
          $ret["dialog"] = MIXXXER_NO_ITEMS;
       }else{
          $mix_id = $_SESSION["mixxxer"]->saveMixxx($_SESSION["c_mix"]);
          $ret["dialog"] = MIX_SAVED_ID.$mix_id;
       }
       break;
       
    case "fb":
       if(count($_SESSION["c_mix"]->items)==0){
          $ret["dialog"] = MIXXXER_NO_ITEMS;
       }else{
          $mix_id = $_SESSION["mixxxer"]->saveMixxx($_SESSION["c_mix"]);
          $ret["fb"] = utf8_encode(str_replace('{configuration}', '<center> </center>'.str_replace('<br/>', '<center> </center>', $_SESSION["c_mix"]->give_item_list_plain()).'<center> </center>', MIXXXER_FB_TEXT).'
                        |||||'.xtc_href_link('mixxxer.php?mix_id='.$mix_id).'|||||'.xtc_href_link(DIR_WS_INFO_IMAGES.xtc_get_products_image($products_id)).'|||||'.MIXXXER_FB_HEADING);
       }
       break;
       
    case "delete_mix":
        $mix_num = (int)$_GET["del_num"];
        $_SESSION["mixxxer"]->deleteMixxx($mix_num);
        $ret["reset_guided"] = 1;
        break;
        
    case "add_item":
        $t = $_SESSION["c_mix"]->add_item($item_id, (int)$_GET["qty"]);
        if($t["error"]){
          $ret["dialog"] = $t["error"];
        }
        $_SESSION["c_mix"]->precheck();
        break;
     case "set_qty":
        $t = $_SESSION["c_mix"]->add_item((int)$_GET["id"], (int)$_GET["qty"], true);
        if($t["error"]){
          $ret["dialog"] = $t["error"];
        }
       
        break;    
    case "dec_item":
        $_SESSION["c_mix"]->dec_item($item_id, (int)$_GET["qty"]);
        break;
        
     case "make1000":
        $_SESSION["c_mix"]->make1000();
        break;   
    case "remove_item":
        $_SESSION["c_mix"]->remove_item($item_id);
        break;
  
}
$i = 1;




foreach($_SESSION["mixxxer"]->mixxxes[$_SESSION["mixxxer"]->current_product] AS $k=>$v){
    $ret["mix_navi"] .= '<a href="mixxxer_ajax_helper.php?action=mix_num&mix_num='.$k.'" class="mix_navi_el feature_ajax_link '.(($_SESSION["mixxxer"]->current_mixxx_index==$k)?'active_mix':'').'">'.$i.'</a>';
    $i++;
}

$ret["mix_navi"] .= '<a href="'.xtc_href_link('mixxxer_ajax_helper.php', 'action=new_mix').'" class="mix_navi_el new_mix_link feature_ajax_link" id="newMixLink"><span>+ '.MIXXXER_NEW_MIX.'</span></a>';
$ret["item_list"] = $_SESSION["c_mix"]->give_item_list();

$ret["qty"] = $_SESSION["c_mix"]->count_items();
$ret["base_item"] = '<div><b>Meine Basis</b> ('.(1000-$ret["qty"]).'g)</div>'.$_SESSION["c_mix"]->baseItem;
$ret["mixList2"] = $_SESSION["c_mix"]->give_item_list_alternative();
$price = $_SESSION["c_mix"]->calc_price();
$ret["total_price"] = $xtPrice->xtcFormat($price , true);
$ret["volumePrice"] = $_SESSION["c_mix"]->volumePrice;
$ret["volumeBasePrice"] = $_SESSION["c_mix"]->volumeBasePrice;
$ret["volumeSize"] = $_SESSION["c_mix"]->volumeSize;
//$ret["total_weight"] = $_SESSION["c_mix"]->weight;
$ret["warning"] = '';
if((int)MIXXXER_NUM_UPLOADS > 0){
    $ret["mixxxer_upload_list"] = $_SESSION["c_mix"]->give_file_list();
}


$ret['mix_name'] = $_SESSION["c_mix"]->name;
$ret['max_cost'] = ($_SESSION["c_mix"]->max_cost!='')?number_format($_SESSION["c_mix"]->max_cost, 2, ',', '.'):'';
$ret['my_text'] = $_SESSION["c_mix"]->my_text;
  
foreach($_SESSION["c_mix"]->items AS $item){
   $act_items .= "|".$item["id"].';'.$item["qty"].';'.$item["max"].';'.$item["multiselect"];
}           
$ret["active_items"] = $act_items;
if($_SESSION["c_mix"]->max_val!=''){
    $ret["max_val"] = $_SESSION["c_mix"]->calc_max_val();
    if($ret["max_val"]>0){
        $ret["base_price"] = $xtPrice->xtcFormat($price*$_SESSION["c_mix"]->price_base/$ret["max_val"], true);
    }else{
        $ret["base_price"] = '-';
    }
    $ret["max_val_limit"] = $_SESSION["c_mix"]->max_val;
}
$ret["comp_groups"] = $_SESSION["c_mix"]->compGroups;
$ret["prices"] = array();

foreach($_SESSION["c_mix"]->items AS $item){           
    $ret["prices"][$item["id"]] = $xtPrice->xtcFormat($item["price"], true, 0,false,0,0,8);
    if($item["percent"] != 0){
        $ret["prices"][$item["id"]] = $item["percent"]."%";
    }
} 
$_SESSION["c_mix"]->recalcForVolume();
foreach($_SESSION["c_mix"]->c_texts AS $item_id => $text){           
    $ret["--input=[name=\"mi_c_text[".$item_id."]\"]"] = $text;
}    

$files = $_SESSION["c_mix"]->getCFiles();

foreach($files AS $file){           
    $disp = $file["name"];
  
    if(getimagesize($file["path"])!==false){
        $disp = '<img src="'.$file["path"].'" class="mixxxerUploadImg" />';
    }
    $ret["file".$file["id"]] = '<a href="'.$file["path"].'" target="_blank">'.$disp.'</a>';
} 


if(MIXXXER_PRICE_DISPLAY_2 == '1'){

    
        $q = "SELECT * FROM mixxxer_groups
                WHERE
                  language_id = ".(int)$_SESSION["languages_id"]."
                 
                  ";
        
        $rs = mysql_query($q);  
        $i = 0;
        
        while ($mg_r = mysql_fetch_array($rs)){
            
          
            $q = "SELECT mi.mi_id FROM mixxxer_items mi, mixxxer_items_to_mixxxer_groups mi2mg 
                WHERE
                  mi.language_id = ".(int)$_SESSION["languages_id"]."
                    AND
                  mi2mg.mg_id = ".$mg_r["mg_id"]."
                    AND
                  mi2mg.mi_id = mi.mi_id
                  GROUP BY mi.mi_id
               ";
             
              
              $mi_rs = xtc_db_query($q);
              while ($mi_r = mysql_fetch_array($mi_rs)){
                  $qty = 1;
                  $add = 0;
                  if(isset($_SESSION["c_mix"]->items[$mi_r["mi_id"]]["qty"])){
                       $qty = $_SESSION["c_mix"]->items[$mi_r["mi_id"]]["qty"]+1;
                       
                  }
                  $_SESSION["_mixxxerPercent"] = 0;
                  $price = mixxxerHelper::getMixxxerItemPrice($mi_r["mi_id"], $_SESSION["mixxxer"]->current_product, $qty);
                  if($mg_r["mg_multiselect"] == "0"){
                     foreach($_SESSION["c_mix"]->items AS $item){
                              if($item["mg_id"]==$mg_r["mg_id"]){
                                   $price = $price-$item["price"];
                                   
                              }
                      
                      }
                  }else{
                      //FOR GRADUATED PRICES OF MULTI SELECT GROUPS
                      if(isset($_SESSION["c_mix"]->items[$mi_r["mi_id"]]["qty"])){
                       $qty = $_SESSION["c_mix"]->items[$mi_r["mi_id"]]["qty"];
                       $comp_price = $qty * $_SESSION["c_mix"]->items[$mi_r["mi_id"]]["price"];
                       $new_price = ($qty+1)*$price;
                       $price = $new_price-$comp_price;
                       
                      }
                  }
                  if($_SESSION["_mixxxerPercent"] != 0){
                    $ret["prices"][$mi_r["mi_id"]] = $_SESSION["_mixxxerPercent"].'%';
                  }else{
                    $ret["prices"][$mi_r["mi_id"]] = $xtPrice->xtcFormat($price, true); 
                  }
                  
                 
                  $ret["prices"][$mi_r["mi_id"]] = $xtPrice->xtcFormat($price, true, 0,false,0,0,8); 
              }
        
            $i++;
        
        
            
        }



}                                                                                                        

$ret["delete_link"] = '<a href="'.xtc_href_link('mixxxer_ajax_helper.php', 'action=delete_mix&del_num='.$_SESSION["mixxxer"]->current_mixxx_index).'" class="feature_ajax_link mixxxer_button"><img src="alkimmedia_modules/mixxxer/images/mixxxer_delete_icon.png" alt="'.DELETE_MIX.'" /> <span class="mixxxer_icon_text">'./*DELETE*/''.'</span></a>';
//$ret["nutrition"] = $_SESSION["c_mix"]->display_nutrition();
$ret["total_price"] = $xtPrice->xtcFormat($_SESSION["c_mix"]->calc_price(), true);
$ret["total_price_2"] = $ret["total_price"];
$vars = array();
foreach($ret as $k => $v){
	$vars['items'][] = array('update_select'=>'#'.$k,'update_text'=>(is_array($v)?$v:my_utf8_encode($v)));
}
$output = $json->encode($vars);


echo $output;

 function my_utf8_encode($str){
    return iconv ("ISO-8859-15", "UTF-8", $str);

}

?>
