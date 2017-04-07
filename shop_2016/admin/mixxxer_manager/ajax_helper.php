<?php


if($_GET["action"]=='search_product'){
    $pq = $_GET["term"];
    $q = "SELECT * FROM products_description pd, products p WHERE 
                (pd.products_name LIKE '$pq%' OR p.products_model LIKE '$pq%')
                  AND p.products_id = pd.products_id AND pd.language_id = ".$_SESSION["languages_id"];
    $rs = mysql_query($q);
    echo '[';
    $print = array();
    while($r = mysql_fetch_object($rs)){
        
        $nq = "SELECT * FROM products_description WHERE products_id = ".$r->products_id;
        $nrs = mysql_query($nq);
        $names = array();
        $descs = array();
        while($nr = mysql_fetch_object($nrs)){
             $names[$nr->language_id] = $nr->products_name;
             $descs[$nr->language_id] = strip_tags($nr->products_description);
        }
        
       /* $name_str = implode(', ', $names);
        $desc_str = implode(', ', $descs);
        */
        $ret = array(
            'id'=>$r->products_id,
            "label"=>utf8_encode($r->products_name),
            "value"=>$r->products_id,
            "t"=>$names, //'{'.$name_str.'}', 
            "d"=>$descs //'{'.$desc_str.'}'
            
        
        
        );
        
        
        $print[] =  json_encode($ret);
        
    } 
    echo implode(', ', $print);
    echo ']';                
  



}


?>
