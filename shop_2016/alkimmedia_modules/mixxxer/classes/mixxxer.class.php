<?php


class mixxxer{

    function __construct(){
        $mixxxer_products = array();
        $this->master_mixxxer_pid = 0;
        /*$q = "SELECT DISTINCT mia.mia_products_id, p.products_master_mixxxer FROM mixxxer_items_active mia, products p WHERE p.products_id = mia.mia_products_id AND  p.products_is_mixxxer = 1";
        $rs = xtc_db_query($q);
        $this->mixxxes = array();
        while($r = mysql_fetch_object($rs)){
            
            $mixxxer_products[] = $r->mia_products_id;
            $this->mixxxes[$r->mia_products_id] = array();
            if($r->products_master_mixxxer==1){
                $this->master_mixxxer_pid = $r->mia_products_id;
            }
           
           
        }*/
        
        
        
        //if(mysql_num_rows($rs)==0 || $this->master_mixxxer_pid == 0){
          $q = "SELECT DISTINCT products_id, products_master_mixxxer FROM products WHERE products_is_mixxxer = 1";
          $rs = xtc_db_query($q);
          $this->mixxxes = array();
          while($r = mysql_fetch_object($rs)){
             
              $mixxxer_products[] = $r->products_id;
              $this->mixxxes[$r->products_id] = array();
              $this->mixxxerCurrentIndex[$r->products_id] = array();
              if($r->products_master_mixxxer==1){
                $this->master_mixxxer_pid = $r->products_id;
              }
          }
        
        //}
       
        $this->mixxxer_products = $mixxxer_products;
        $this->current_product = ($this->master_mixxxer_pid!=0?$this->master_mixxxer_pid:$this->mixxxer_products[0]);
        
        
    }
    
    function registerMixxx($mix, $is_current = true){
        if(is_array($this->mixxxes[$mix->mixxxer_product])){
            $this->mixxxes[$mix->mixxxer_product] = array_values($this->mixxxes[$mix->mixxxer_product]);
            $this->mixxxes[$mix->mixxxer_product][] = $mix;
            $this->current_product = $mix->mixxxer_product;
            
            if($is_current){
                $this->setCurrentMixxx(max(array_keys($this->mixxxes[$mix->mixxxer_product])));
            }
            return max(array_keys($this->mixxxes[$mix->mixxxer_product]));
        }
    } 
    
    function getMixxxes(){
        return $this->mixxxes[$this->current_product];
    }
    
    function setCurrentMixxx($key){
        
        $this->current_mixxx = $this->mixxxes[$this->current_product][$key];
        $this->mixxxerCurrentIndex[$this->current_product] = $key;
        $this->current_mixxx_index = $key;
        $_SESSION["c_mix"] = $this->current_mixxx;
        
        
    }
    
    function deleteMixxx($key){
        $key = (int)$key;
        
        unset($this->mixxxes[$this->current_product][$key]);
        
        $this->mixxxes[$this->current_product] = array_values($this->mixxxes[$this->current_product]);
        if($key == $this->current_mixxx_index){
           if(count($this->mixxxes[$this->current_product])>0){ 
               if($key <= max(array_keys($this->mixxxes[$this->current_product]))){
                  $this->setCurrentMixxx($key);
               }else{
                  $this->setCurrentMixxx(max(array_keys($this->mixxxes[$this->current_product])));
               }
           }else{
               $this->createMixxx(true);
           }
        }
        
  
    }
    
    function deleteCurrentMixxx(){
        $this->deleteMixxx($this->mixxxerCurrentIndex[$this->current_product]);
    }
    
    function createMixxx($current=false){
        $mix = new mixxx($this->current_product);
        $mix->precheck();
        
        $this->registerMixxx($mix, $current);
        
    }
    
    function loadMixxx($mix_id){
        $q = "SELECT mix_code FROM mixxxes WHERE mix_id = ".(int)$mix_id;
        $rs = xtc_db_query($q);
        $r = mysql_fetch_object($rs);
        
        if($r->mix_code!=""){
            return unserialize($r->mix_code);
        }else{
            return false;
        }
    }

    function loadMixxxAsCurrent($mix_id){
      $new_mix = $this->loadMixxx($mix_id);
      $this->registerMixxx($new_mix, true);
    }
    
    function saveMixxx($object, $mix_id=0){
        unset($object->wk_pov_id);
        $mix_id = (int)$mix_id;
        $data = serialize($object);
        if($mix_id != 0){
            $q = "UPDATE mixxxes SET mix_code='".xtc_db_input($data)."' WHERE mix_id = $mix_id";
            mysql_query($q);
            return $mix_id;
        }else{
            $q = "SELECT * FROM mixxxes WHERE mix_code = '".xtc_db_input($data)."'";
            $rs = xtc_db_query($q);
            if(mysql_num_rows($rs)>0){
              $r = mysql_fetch_object($rs);
              return $r->mix_id;
            }
            $user_id = (int)$_SESSION["customer_id"];
            $q = "INSERT INTO mixxxes (mix_code, mix_user) VALUES ('".xtc_db_input($data)."', $user_id)";
            mysql_query($q);
            return mysql_insert_id();
        }
    }
    
    function saveCurrentMixxx(){
         return $this->saveMixxx($this->current_mixxx);
    }

    function getMixxxIdFromPovId($pov_id){
        $q = "SELECT * FROM products_options_values WHERE products_options_values_id = $pov_id AND language_id = 2";
        $rs = mysql_query($q);
        $r = mysql_fetch_object($rs);
        return $r->mix_id;
    }

    



    function addMixxxToCart($mix_id=0, $qty = 1){
                  
                  if($mix_id == 0){
                     $mix = $_SESSION["c_mix"];
                     $wk_pov_id = $mix->wk_pov_id;
                     $mix_id = $this->saveMixxx($mix);
                  }else{
                     $mix = $this->loadMixxx($mix_id);
                  }
                  
                  
                  $name = $mix->name;
                  if (!is_object($_SESSION['cart'])) {
                    $_SESSION['cart'] = new shoppingCart();
                  }
                  
             
                  
                  if(isset($wk_pov_id) && $wk_pov_id != ''){
                   
                      $prd_id = $mix->mixxxer_product.'{'.CONFIG_OPT_GROUP.'}'.$wk_pov_id;
                      $qty = $_SESSION['cart'] -> get_quantity($prd_id);
                      $_SESSION['cart'] -> remove($prd_id);
                      //$mix_id = save_mix($mix, get_mix_id_from_pov_id($mix->wk_pov_id));
                      //$mix_id = save_mix($mix);
                  }
                  
                 
                
                
                 
                 
                  $q = "SELECT pov.products_options_values_id FROM products_attributes pa, products_options_values pov WHERE 
                          pa.products_id = ".$mix->mixxxer_product."
                            AND
                          pa.options_values_id = pov.products_options_values_id
                            AND 
                          pov.language_id = ".(int)$_SESSION["languages_id"]."
                            AND
                          pov.mix_id = $mix_id";
                          
                  $rs = xtc_db_query($q);
                  if(mysql_num_rows($rs)>0){
                      
                    $r = mysql_fetch_object($rs);
                    $ov_id = $r->products_options_values_id; 
                    
                  }else{ 
                  
                        $q = "SELECT * FROM products_options_values ORDER BY products_options_values_id DESC LIMIT 1";
                        $rs = mysql_query($q);
                        $r = mysql_fetch_object($rs);
            
                        $ov_id = $r->products_options_values_id+1;
            
            
                        $languages = self::getLanguages();
                        foreach($languages AS $l){
                			$sql_arr = array('products_options_values_id'=>$ov_id, 'language_id'=>$l["id"],	'products_options_values_name'=>$name,	'mix_id'=>$mix_id);
            				    xtc_db_perform('products_options_values', $sql_arr);
            	        }			   
            				   
                        $sql_arr = array('products_options_id'=>CONFIG_OPT_GROUP, 	'products_options_values_id'=>$ov_id);
                        xtc_db_perform('products_options_values_to_products_options', $sql_arr);
            
                        $sql_arr = array('products_id'=>$mix->mixxxer_product, 	'options_id'=>CONFIG_OPT_GROUP, 	'options_values_id'=>$ov_id, 	'options_values_price'=>0, 	'price_prefix'=>'+', 'attributes_stock'=>1000);
                        xtc_db_perform('products_attributes', $sql_arr);
                        $attr_id = mysql_insert_id();
                  
                  }
                  if($qty <1)
                    $qty = 1;
                  $_SESSION['cart']->add_cart($mix->mixxxer_product, $_SESSION['cart']->get_quantity(xtc_get_uprid($mix->mixxxer_product, array(CONFIG_OPT_GROUP=>$ov_id))) + (int)$qty, array(CONFIG_OPT_GROUP=>$ov_id));
                  
        }
        
        public static function getLanguages(){
        
            $languages_query = xtc_db_query("select languages_id, name, code, image, directory from ".TABLE_LANGUAGES." where status = '1' order by sort_order");
           
            while ($languages = xtc_db_fetch_array($languages_query)) {
              $languages_array[] = array ('id' => $languages['languages_id'],
                                          'name' => $languages['name'],
                                          'code' => $languages['code'],
                                          'image' => $languages['image'],
                                          'directory' => $languages['directory']
                                          );
            }
            return $languages_array;

        }

}

?>
