<?php

class mixxx {


    function __construct($mixxxer_product, $max_val = 0) {
        $mixxxer_product       = (int) $mixxxer_product;
        $this->items           = array();
        $this->num_by_group    = array();
        $this->free_val_1      = 0;
        $this->max_val         = $max_val;
        $this->possibleMaxVals = array();
        $this->MaxValPrices    = array();
        $this->compGroups      = array();
        require_once(DIR_FS_CATALOG . 'includes/classes/product.php');
        $this->mpo        = new product($mixxxer_product, true);
        $this->volumeBase = $this->mpo->data["products_volume_price"];
        $this->volumeUnit = $this->mpo->data["products_volume_unit"];
        $this->cFiles     = array();
        $this->masterBaseItem = null;
    	$this->last_refresh = 0;

        if ($this->max_val == 0) {

            $q  = "SELECT * FROM products WHERE products_id = $mixxxer_product";
            $rs = mysql_query($q);
            $mp = mysql_fetch_object($rs);
            if (strpos($mp->products_max_values, ',') === false) {


                $this->registerMaxVal($mp->products_max_values);


            } else {

                $t = explode(',', $mp->products_max_values);
                foreach ($t AS $a) {
                    $this->registerMaxVal($a);
                }

            }
            $this->max_val    = $this->possibleMaxVals[0]["val"];
            $this->price_base = $mp->products_price_base;

        }
        $this->name            = MIXXXER_STANDARD_NAME;
        $this->c_texts         = array();
        $this->files           = array();
        $this->mixxxer_product = $mixxxer_product;

        $q = "SELECT * FROM mixxxer_items_active mia, mixxxer_groups mg,  mixxxer_items_to_mixxxer_groups m2m
        WHERE
        mia.mia_products_id = $mixxxer_product
        AND
        m2m.mg_id = mg.mg_id
        AND
        m2m.mi_id = mia.mia_mi_id
        AND
        mg.mg_volume = '1'
        AND
        mg.language_id = " . $_SESSION["languages_id"] . "
        AND
        mia.mia_model != ''";

        $rs = mysql_query($q);
        while ($r = mysql_fetch_object($rs)) {

            $t = explode('/', $r->mia_model);
            if ($t[2]) {
                $this->addCText($r->mia_mi_id, $t[2]);
            }
        }



    }

    function registerMaxVal($code) {
        global $xtPrice;
        if (strpos($code, ':') === false) {
            $this->possibleMaxVals[] = array(
                'price' => 0,
                'val' => trim($code)
            );
        } else {
            $t = explode(':', $code);
            if (isset($xtPrice)) {
                $price        = $t[1];
                $products_tax = isset($xtPrice->TAX[$this->mpo->data["products_tax_class_id"]]) ? $xtPrice->TAX[$this->mpo->data["products_tax_class_id"]] : 0;
                $pp           = $xtPrice->xtcaddTax($price, $products_tax);
            } else {
                $pp = $t[1];
            }

            $this->possibleMaxVals[]   = array(
                'price' => trim($pp),
                'val' => trim($t[0])
            );
            $this->MaxValPrices[$t[0]] = $pp;
        }


    }

    function give_base_price($xtPrice) {

        if ($this->free_val_1 > 0 && $this->price_base != '') {
            $bp = $this->calc_price() * $this->price_base / $this->free_val_1;
            $bp = $xtPrice->xtcFormat($bp, true) . ' / ' . $this->price_base . MIXXXER_MAX_VAL_UNIT;

            return $bp;
        }

    }

    function add_item($mi_id, $qty = 1, $absolute = false) {

        if ($qty == 0) {
            $this->remove_item($mi_id);
            return;
        }

        $this->calc_by_group();
        $q  = " SELECT *
                FROM
                  mixxxer_items mi,
                  mixxxer_items_to_mixxxer_groups mi2mg,
                  mixxxer_groups mg,
                  mixxxer_items_active mia
                WHERE
                  mi.mi_id = " . (int) $mi_id . "

                  AND mi.language_id = '" . (int) $_SESSION['languages_id'] . "'
                  AND mi2mg.mi_id = mi.mi_id
                  AND mi2mg.mg_id = mg.mg_id
                  AND mia.mia_mi_id = mi.mi_id
                  AND mia.mia_products_id = " . $this->mixxxer_product . "
                  AND mg.language_id = '" . (int) $_SESSION['languages_id'] . "'
              ";
        $rs = mysql_query($q);
        $r  = mysql_fetch_object($rs);


        $this->process_multiselect($mi_id);

        $max_val = $r->mi_free_val_1;

        if ($r->mi_free_val_1_factor != 0) {
            $max_val = $max_val / (float) $r->mi_free_val_1_factor;
        }
        if ($this->free_val_1 + $max_val > $this->max_val && $this->max_val != '') {
            return array(
                'error' => MIXXXER_MAX_VALUE_REACHED
            );
        }

        if ($this->num_by_group[$r->mg_id] >= $r->mg_maximum && $r->mg_maximum > 0) {
            return array(
                'error' => sprintf(MIXXXER_MAXIMUM_BY_GROUP_REACHED, $r->mg_maximum)
            );
        }



        //already in mix
        if (isset($this->items[$mi_id])) {

            if ($this->items[$mi_id]["mg_volume"] != '1') {
                if($r->mg_id == BASE_MG_ID){
                    if($qty == 1){
                        $this->remove_base_items();
                    }
                    $cQty = $this->count_items(true);
                    if($cQty > 0){
                        if($mi_id == $this->masterBaseItem){
                            $target = $qty+$this->items[$mi_id]["qty"];
                            $target = min($target, 100);
                            if($target == 100){
                                $qty = 999999999;
                            }else{
                                $qty = ($cQty-$this->items[$mi_id]["qty"])/(100/($target) - 1);
                             }
                            $absolute = true;
                        }else{
                            $masterQty = $this->items[$this->masterBaseItem]["qty"];
                            if($masterQty-$qty >= 5){
                                $this->items[$this->masterBaseItem]["qty"] -= $qty;
                            }else{
                                return;
                            }
                        }




                    }
                }else{
                    $additiveQty = $this->count_items(false);
                    if($additiveQty+$qty > 950){
                        return;
                    }
                }
                return $this->inc_item($mi_id, ($absolute ? ($qty - (int) $this->items[$mi_id]["qty"]) : $qty));
            }

            return;
        //not yet in mix
        } else {
            if($r->mg_id == BASE_MG_ID){
                if($qty == 1){
                    $this->remove_base_items();
                }
                if(!$this->has_base_item()){
                    $this->masterBaseItem = $mi_id;
                }else{
                    $this->make1000(true);
                    $masterQty = $this->items[$this->masterBaseItem]["qty"];
                    if($masterQty-$qty >= 5){
                        $this->items[$this->masterBaseItem]["qty"] -= $qty;
                    }else{
                        return;
                    }

                }


                /*
                $cQty = $this->count_items(true);
                if($cQty > 0){
                    $qty = $cQty/(100/$qty - 1);
                }*/
            }else{
                $additiveQty = $this->count_items(false);
                if($additiveQty+$qty > 950){
                    return;
                }
            }
            if ($r->mi_image == '') {
                $r->mi_image = 'mixxxer_no_image.jpg';
                $no_image    = true;

            }
            $pr_img      = false;
            $r->mi_image = HTTP_SERVER . DIR_WS_CATALOG . 'images/mixxxer_items/thumbnail_images/' . $r->mi_image;
            if ($r->mi_product != 0) {
                $p = new product($r->mi_product, true);

                if ($no_image && $p->data["products_image"] != '') {
                    $r->mi_image = HTTP_SERVER . DIR_WS_CATALOG . $p->productImage($p->data["products_image"], 'thumbnail');
                    $pr_img      = true;
                }


            }

            if (!$pr_img && $no_image && MIXXXER_NOIMAGE_MODE == 0) {
                $r->mi_image = '';
            }



            if ($r->mi_image_2 == '') {
                $no_image_2 = true;
                $img_2      = $r->mi_image;
            } else {
                $img_2 = 'images/mixxxer_items/thumbnail_images/' . $r->mi_image_2;
            }


            $compGr = explode(',', $r->mi_comp_gr);
            if ($compGr[0] == '' && count($compGr) == 1) {
                $compGr = array();
            }
            $_SESSION["_mixxxerPercent"] = 0;
            $price                       = mixxxerHelper::getMixxxerItemPrice($r->mi_id, $this->mixxxer_product, 1);

            $this_item = array(
                'id' => $r->mi_id,
                'qty' => (int) $qty,
                'name' => $r->mi_name,
                'model' => $r->mia_model,
                'img' => $r->mi_image,
                'img_2' => $img_2,
                'price' => $price,
                'percent' => $_SESSION["_mixxxerPercent"],
                'weight' => $r->mi_weight,
                'comp_gr' => $compGr,
                'comp_gr_ref' => $r->mi_comp_gr_ref . $r->mg_comp_gr_ref,
                'comp_gr_only' => $r->mi_comp_gr_only . $r->mg_comp_gr_only,
                'mg_id' => $r->mg_id,
                'max' => (int) $r->mi_maximum,
                'mg_max' => (int) $r->mg_maximum,
                'group_name' => $r->mg_name,
                'group_id' => $r->mg_id,
                'max_val' => $max_val,
                'required' => $r->mg_required,
                'multiselect' => $r->mg_multiselect,
                'mi_sort' => $r->mia_sortorder,
                'mi_c_text' => $r->mi_c_text,
                'mg_sort' => $r->mg_sortorder,
                'mg_supergroup' => $r->mg_listgroup,
                'mg_volume' => $r->mg_volume,
                'general_sort' => str_pad($r->mg_sortorder, 6, '0', STR_PAD_LEFT) . '-' . str_pad($r->mia_sortorder, 6, '0', STR_PAD_LEFT)

            );



            $this->items[(string) $mi_id] = $this_item;
            $this->set_comp_gr();
        }
        if($this->count_items() > 1000){
            $this->make1000();
        }
        $this->make1000(true);
        $this->calc_max_val();
        $this->calc_by_group();
        $this->precheck();
    }

	function refreshItems(){
		if($this->last_refresh > (time()-14400)){
			return;
		}
		foreach($this->items AS $id=>$item){
			$this->remove_item($item["id"], 1);
			if($item["id"] && $item["qty"]){
				$this->add_item($item["id"], $item["qty"]);
			}
		}
		$this->last_refresh = time();
	}

    function add_item_once($item_id) {
        $item_id = (int) $item_id;
        if (!isset($this->items[$item_id])) {
            $this->add_item($item_id);
        }


    }

    function add_file($path) {

        $this->files[] = $path;


    }

    function addCFile($id, $file) {
        $this->cFiles[(int) $id] = $file;
        $this->add_item_once((int) $id);

    }

    function getCFiles() {
        $ret = array();
        foreach ($this->cFiles AS $id => $file) {
            $t = explode('__', $file);
            unset($t[0]);
            $name     = implode('__', $t);
            $ret[$id] = array(
                'id' => $id,
                'path' => "mixxxer_uploads/" . $file,
                'name' => $name
            );
        }
        return $ret;
    }

    function calc_by_group() {

        $this->num_by_group = array();
        foreach ($this->items AS $item) {
            $this->num_by_group[$item["group_id"]] += $item["qty"];
        }


    }

    function del_file($fid) {

        unset($this->files[(int) $fid]);


    }

    function give_file_list($mode = "") {
        if (count($this->files) > 0) {

            $ret .= '<ul class="mixxxer_file_list">';
            foreach ($this->files AS $fid => $file) {
                $filename = basename($file);
                $t        = explode('_', $filename);
                unset($t[0]);
                $filename = implode('_', $t);

                $ret .= '<li class="mixxxer_file"><span class="mixxxer_small_button"><a href="' . xtc_href_link($file) . '" target="_blank">' . $filename . '</a> ';
                if ($mode == 'outside') {

                } else {
                    $ret .= '<a class="mixxxer_del_file" ref="' . $fid . '">X</a>';
                }
                $ret .= '</span></li>';

            }
            $ret .= '</ul>';

        }
        return $ret;

    }

    function set_comp_gr() {
        $this->compGroups = array();

        foreach ($this->items AS $item) {
            if (is_array($item["comp_gr"])) {
                foreach ($item["comp_gr"] AS $cGr) {
                    if (!in_array($cGr, $this->compGroups) && $cGr != '') {
                        $this->compGroups[] = $cGr;
                    }
                }
            }
        }

        $this->process_comp_gr();
    }

    function process_comp_gr() {


        foreach ($this->items AS $item) {

            $c_gr = array();
            if (strpos($item["comp_gr_ref"], ',') !== false) {
                $c_gr = explode(',', $item["comp_gr_ref"]);
            } else {
                $c_gr[] = $item["comp_gr_ref"];
            }



            foreach ($c_gr AS $gr) {
                if (in_array($gr, $this->compGroups) && $gr != '') {

                    $this->remove_item($item["id"], 1);

                }
            }


            $c_gr = array();
            if (strpos($item["comp_gr_only"], ',') !== false) {
                $c_gr = explode(',', $item["comp_gr_only"]);
            } else {
                $c_gr[] = $item["comp_gr_only"];
            }


            if ($item["comp_gr_only"] != '') {
                $remove = true;
                foreach ($c_gr AS $gr) {
                    foreach ($this->compGroups AS $hgr) {
                        if ($hgr == $gr && $gr != '') {
                            $remove = false;
                        }
                    }
                }
                if ($remove) {


                    $this->remove_item($item["id"], 1);
                } else {

                }
            }

        }
    }

    function clean_items(){
        foreach($this->items AS $k=>$v){
            if(empty($v["id"])){
                unset($this->items[$k]);
            }
        }
    }

    function remove_base_items(){
        foreach($this->items AS $k=>$v){
            if($v["group_id"] == BASE_MG_ID){
                $this->remove_item($v["id"], 1);
            }
        }
        $this->clean_items();
    }

    function has_base_item(){
        foreach($this->items AS $k=>$v){
            if($v["group_id"] == BASE_MG_ID){
                return true;
            }
        }
        return false;
    }

    function remove_item($mi_id, $force = 0) {

        if (isset($this->items[$mi_id]["required"]) && $this->items[$mi_id]["required"] == 1 && $force == 0) {

            foreach ($this->items AS $item) {

                if ($item["group_id"] == $this->items[$mi_id]["group_id"] && $item["id"] != $mi_id) {

                    unset($this->items[$mi_id]);
                    $this->set_comp_gr();
                    break;
                }

            }


        } else {

            unset($this->items[$mi_id]);
            $this->set_comp_gr();
        }

        $this->clean_items();
        $this->calc_by_group();
        $this->calc_max_val();


    }

    function inc_item($mi_id, $qty = 1) {
        $this->calc_by_group();
        if ($this->num_by_group[$this->items[$mi_id]["group_id"]] + $qty > $this->items[$mi_id]["mg_max"] && $this->items[$mi_id]["mg_max"] != 0) {
            return array(
                'error' => sprintf(MIXXXER_MAXIMUM_BY_GROUP_REACHED, $this->items[$mi_id]["mg_max"])
            );
        }
        if ($this->items[$mi_id]['qty'] + $qty <= $this->items[$mi_id]['max'] || $this->items[$mi_id]['max'] == 0) {
            $this->items[$mi_id]['qty'] += $qty;
            $this->items[$mi_id]['price'] = mixxxerHelper::getMixxxerItemPrice($mi_id, $this->mixxxer_product, $this->items[$mi_id]['qty']);
            $this->calc_max_val();


        } else {
            return array(
                'error' => sprintf(MIXXXER_MAXIMUM_REACHED, $this->items[$mi_id]['max'])
            );
        }
        if($this->count_items() > 1000){
            $this->make1000();
        }
        $this->make1000(true);


    }

    function count_items($baseOnly = false) {
        $n = 0;
        foreach ($this->items AS $item) {
            if (
                    ($item["group_id"] != BASE_MG_ID && !$baseOnly)
                        ||
                    ($item["group_id"] == BASE_MG_ID && $baseOnly)
                ) {
                $n += $item["qty"];
            }
        }
        return $n;

    }

    function make1000($baseOnly = false) {

        $n = $this->count_items($baseOnly);
        if ($n > 0) {
            $t   = ($baseOnly?100:1000);
            $f   = $t / $n;
            $new = 0;
            foreach ($this->items AS $id => $item) {
                if(
                         ($item["group_id"] != BASE_MG_ID && !$baseOnly)
                            ||
                        ($item["group_id"] == BASE_MG_ID && $baseOnly)
                ){
                    $new += ($this->items[$id]["qty"] = round($this->items[$id]["qty"] * $f));
                }
            }

            $d = $new - $t;
            $this->items[$id]["qty"] -= $d;
        }
    }
    function dec_item($mi_id, $qty = 1) {

        if($this->items[$mi_id]["group_id"] == BASE_MG_ID && $mi_id != $this->masterBaseItem){
            $this->items[$this->masterBaseItem]["qty"] += $qty;
        }
        if ($this->items[$mi_id]['qty'] - $qty > 0) {
            $this->items[$mi_id]['qty'] -= $qty;
            $this->items[$mi_id]['price'] = mixxxerHelper::getMixxxerItemPrice($mi_id, $this->mixxxer_product, $this->items[$mi_id]['qty']);
        } else {
            $this->remove_item($mi_id);
        }
        $this->make1000(true);
        $this->calc_by_group();

    }

    function calc_item_price($qty = 1) {
        $total  = 0;
        $factor = 1;
        if ($qty == 1) {
            foreach ($this->items AS $k => $v) {
                $total += $v['qty'] * $v["price"];
                $factor *= (1 + $v["percent"] / 100);
            }
        } else {
            foreach ($this->items AS $k => $v) {
                $total += $v['qty'] * mixxxerHelper::getMixxxerItemPrice($v["id"], $this->mixxxer_product, $v["qty"], $qty);
                $factor *= (1 + $v["percent"] / 100);
            }
        }

        $this->factor = $factor;

        return $total;
    }

    function calc_max_val() {
        $total = 0;
        foreach ($this->items AS $k => $v) {
            $total += $v['qty'] * $v["max_val"];
        }
        $this->free_val_1 = $total;
        return $total;
    }

    function calc_weight() {
        $weight = 0;
        foreach ($this->items AS $k => $v) {

            $weight += $v["weight"] * $v["qty"];
        }
        return $weight;
    }


    function calc_price($qty = 1, $mix_id = false) {

        global $xtPrice;
        $this->recalcForVolume();
        if ($mix_id !== false) {
            $q        = "SELECT * FROM products WHERE products_price_from_mixxx = 0 AND products_mix_id = " . (int) $mix_id;
            $products = xtc_db_fetch_array(xtc_db_query($q));

            if ($products["products_id"]) {
                $products_price = $xtPrice->xtcGetPrice($products['products_id'], $format = false, $qty, $products['products_tax_class_id'], $products['products_price']);
                return $products_price;
            }


        }
        $price         = $this->calc_item_price($qty);
        $max_val_price = $this->MaxValPrices[$this->max_val];
        $price += $max_val_price;

        $bp         = new product($this->mixxxer_product, true);
        $base_price = $xtPrice->xtcGetPrice($bp->data["products_id"], $format = false, $qty, $bp->data["products_tax_class_id"], $bp->data["products_price"]);

        $volumeItems = $this->getVolumeItems();
        if (count($volumeItems) > 0) {
            $t = 1;
            foreach ($volumeItems AS $vi) {
                $t *= (float) $this->c_texts[$vi["id"]];
            }
            $base_price   = $t * $this->volumeBase;
            $products_tax = isset($xtPrice->TAX[$this->mpo->data["products_tax_class_id"]]) ? $xtPrice->TAX[$this->mpo->data["products_tax_class_id"]] : 0;

            $base_price            = $xtPrice->xtcRemoveTax($base_price, $products_tax);
            $basePrice             = $xtPrice->xtcRemoveTax($this->volumeBase, $products_tax);
            $base_price            = $xtPrice->xtcFormat($base_price, false, $this->mpo->data["products_tax_class_id"]);
            $this->volumePrice     = $xtPrice->xtcFormat($base_price, true);
            $this->volumeBasePrice = $xtPrice->xtcFormat($basePrice, true, $this->mpo->data["products_tax_class_id"]) . '/' . $this->volumeUnit;
            $this->volumeSize      = $t . $this->volumeUnit;
            // var_dump($this->mpo->data);
        }









        $price += $base_price;

        return $price * $this->factor;
    }

    function addCText($id, $text, $add = true) {
        $this->c_texts[(int) $id] = $text;
        if ($add) {
            $this->add_item_once((int) $id);
        }
    }

    function recalcForVolume() {
        foreach ($this->items AS $item) {
            if ($item["mg_volume"] == '1') {
                $t                          = explode('/', $item["model"]);
                $this->c_texts[$item["id"]] = (float) str_replace(',', '.', $this->c_texts[$item["id"]]);
                if ($t[0] && $t[0] != '') {
                    $min = (float) $t[0];
                    if ($this->c_texts[$item["id"]] < $min) {
                        $this->c_texts[$item["id"]] = $min;
                    }
                }
                if ($t[1] && $t[1] != '') {
                    $max = (float) $t[1];
                    if ($this->c_texts[$item["id"]] > $max) {
                        $this->c_texts[$item["id"]] = $max;
                    }
                }
            }
        }
    }

    function getVolumeItems() {
        $ret = array();
        foreach ($this->items AS $item) {
            if ($item["mg_volume"] == '1') {
                $ret[] = $item;
            }
        }
        return $ret;

    }

    function calc_item_weight() {
        $total = 0;
        foreach ($this->items AS $k => $v) {
            $total += $v['qty'] * $v["weight"];
        }

        return $total;
    }

    function process_multiselect($new_mi_id) {
        $q = "SELECT * FROM mixxxer_items_to_mixxxer_groups mi2mg, mixxxer_groups mg
                WHERE mi2mg.mi_id = " . (int) $new_mi_id . "
                  AND
                mi2mg.mg_id = mg.mg_id";

        $rs = xtc_db_query($q);
        $r  = mysql_fetch_object($rs);

        if ($r->mg_multiselect == 0 && $r) {

            $q = "SELECT * FROM mixxxer_items_to_mixxxer_groups mi2mg
                     WHERE
                mi2mg.mg_id = " . $r->mg_id;

            $rs = xtc_db_query($q);

            while ($r = mysql_fetch_object($rs)) {

                $this->remove_item($r->mi_id, 1);

            }
        }

    }



    function give_item_list() {

        $i = 0;

        $items = $this->resort();
        $files = $this->getCFiles();
        if (!is_array($items)) {
            $items = array();
        }

        $ret = '';
        $this->baseItem = '';
        $this->baseItem2 = '';    // -- changes -- h.koch for alkim-media -- 03.2016 --
        foreach ($items AS $k => $v) {
            if ($v["id"]) {
                    $html = '';
                    $addHtml = '';
                    if ($prev_group != $v["mg_supergroup"] && $v["mg_supergroup"] != '') {
                        $addHtml .= '<div class="fl_line"><b>' . $v["mg_supergroup"] . '</b></div>' . "\n";
                    }

                    if ($this->c_texts[$v["id"]] != '') {
                        $text = $this->c_texts[$v["id"]];
                    } else {
                        $text = false;
                    }

                    $html .= '<div class="fl_line">
                              <span class="fl_img_wr">
                                  ' . ($v['img_2'] != '' ? '<img src="' . $v['img_2'] . '" class="fl_img" />' : '') . '
                              </span>
                               ' . ((strlen($v['name']) > 20) ? substr($v['name'], 0, 17) . '...' : $v['name']) . (($v['qty'] > 1) ? ' (' . $v['qty'] . 'g)' : '');
                    if ($v["mg_volume"] != '1') {
                        /*$html .= '<div class="fl_nav">
                                <a  href="mixxxer_ajax_helper.php?action=add_item&mi_id=' . $v["id"] . '" class="feature_ajax_link feature_plus mixxxer_item_incr mixxxer_qty_button mixxxer_item_incr_' . $v["id"] . '"><img src="images/icons/plus_button_small.png" alt="+" /></a>
                                ' . ($v["required"] == 0 || $v["multiselect"] == 1 ? '<a href="mixxxer_ajax_helper.php?action=remove_item&mi_id=' . $v["id"] . '" class="feature_ajax_link feature_minus mixxxer_item_decr mixxxer_qty_button mixxxer_item_decr_' . $v["id"] . '"><img src="images/icons/minus_button_small.png" alt="-" /></a>' : '') . '


                              </div>';*/
                    }
                    $html .= ($text ? '<div style="position:relative; top:-6px;font-size:0.9em;"><i>' . $text . '</i></div>' . "\n" : '') . ($files[$v["id"]] ? '<div style="position:relative; top:-6px;font-size:0.9em;"><i><a href="' . $files[$v["id"]]["path"] . '" target="_blank">' . $files[$v["id"]]["name"] . '</a></i></div>' . "\n" : '') . '</div>' . "\n";
                    $prev_group = $v["mg_supergroup"];

                    $i++;
                if($v["group_id"] != BASE_MG_ID){
                    $ret .= $addHtml.$html;
                }else{
                    $this->baseItem .= '<div>&nbsp;&nbsp;&nbsp;'.$v['name'].' ('.$v['qty'].'%)</div>';
                    // --- bof -- changes -- h.koch for alkim-media -- 03.2016 --
                    //$this->baseItem2 .= '<div>&nbsp;&nbsp;&nbsp;'.$v['name'].' ('.$v['qty'].'%)</div>';
                    $this->baseItem2 .= '<div class="fi_list_line fl_line">';
                    $this->baseItem2 .= '  <div class="fi_list_col1">'.$v['name'].'</div>';
                    $this->baseItem2 .= '  <div class="fi_list_col2">'.$v['qty'].'%</div>';
                    $this->baseItem2 .= '</div>';

                    // --- eof -- changes -- h.koch for alkim-media -- 03.2016 --
                }
            } else {
                unset($items[$k]);
            }
        }

        /*for($j = $i; $j<5; $j++){
        $ret .= '<div class="fl_line">&nbsp;</div>';
        } */



        return $ret;

    }

    // --- bof -- changes -- h.koch for alkim-media -- 03.2016 --
    function give_item_list2() {

        $i = 0;

        //$items = $this->resort();
        //$items = $this->resort2();   // andere sortierung
        $items = $this->items;
        $files = $this->getCFiles();
        if (!is_array($items)) {
            $items = array();
        }

        $ret = '';
        $this->baseItem = '';
        $this->baseItem2 = '';
        foreach ($items AS $k => $v) {
            if ($v["id"]) {
                    $html = '';
                    $addHtml = '';
                    if ($prev_group != $v["mg_supergroup"] && $v["mg_supergroup"] != '') {
                        $addHtml .= '<div class="fl_line"><b>' . $v["mg_supergroup"] . '</b></div>' . "\n";
                    }

                    if ($this->c_texts[$v["id"]] != '') {
                        $text = $this->c_texts[$v["id"]];
                    } else {
                        $text = false;
                    }

                    /*
              <div class="fi_list_line">
                    <div class="fi_list_col1">aaa</div>
                    <div class="fi_list_col2">bbb</div>
              </div>
                    */

                    $img  = ($v['img_2'] != '' ? '<img src="' . $v['img_2'] . '" class="fl_img" />' : '');
                    $name = ((strlen($v['name']) > 20) ? substr($v['name'], 0, 17) . '...' : $v['name']);
                    $gram = (($v['qty'] > 1) ? ' (' . $v['qty'] . 'g)' : '');

                    $col1 = $img.'&nbsp;'.$name;
                    $col2 = $gram;

                    $html .= '<div class="fi_list_line fl_line">';
                    $html .= '  <div class="fi_list_col1">'.$col1 .'</div>';
                    $html .= '  <div class="fi_list_col2">'.$col2.'</div>';
                    $html .= '</div>';

/*
                    $html .= '<div class="fl_line">
                              <span class="fl_img_wr">
                                  ' . ($v['img_2'] != '' ? '<img src="' . $v['img_2'] . '" class="fl_img" />' : '') . '
                              </span>
                               ' . ((strlen($v['name']) > 20) ? substr($v['name'], 0, 17) . '...' : $v['name']) . (($v['qty'] > 1) ? ' (' . $v['qty'] . 'g)' : '');
*/
//                    $html .= ($text ? '<div style="position:relative; top:-6px;font-size:0.9em;"><i>' . $text . '</i></div>' . "\n" : '') . ($files[$v["id"]] ? '<div style="position:relative; top:-6px;font-size:0.9em;"><i><a href="' . $files[$v["id"]]["path"] . '" target="_blank">' . $files[$v["id"]]["name"] . '</a></i></div>' . "\n" : '') . '</div>' . "\n";


                    $prev_group = $v["mg_supergroup"];

                    $i++;
                if($v["group_id"] != BASE_MG_ID){
                    $ret .= $addHtml.$html;
                }else{
                    $this->baseItem .= '<div>&nbsp;&nbsp;&nbsp;'.$v['name'].' ('.$v['qty'].'%)</div>';
                    // --- bof -- changes -- h.koch for alkim-media -- 03.2016 --
                    //$this->baseItem2 .= '<div>&nbsp;&nbsp;&nbsp;'.$v['name'].' ('.$v['qty'].'%)</div>';
                    $img  = ($v['img_2'] != '' ? '<img src="' . $v['img_2'] . '" class="fl_img" />' : '');
                    $this->baseItem2 .= '<div class="fi_list_line fl_line">';
                    $this->baseItem2 .= '  <div class="fi_list_col1">'.$img.'&nbsp;'.$v['name'].'</div>';
                    $this->baseItem2 .= '  <div class="fi_list_col2">'.$v['qty'].'%</div>';
                    $this->baseItem2 .= '</div>';

                    // --- eof -- changes -- h.koch for alkim-media -- 03.2016 --
                }
            } else {
                unset($items[$k]);
            }
        }

        /*for($j = $i; $j<5; $j++){
        $ret .= '<div class="fl_line">&nbsp;</div>';
        } */

        if($this->baseItem2==''){
            $this->baseItem2 = '<div class="warning">'.TEXT_MIXXXER_CHOOSE_BASE.'</div>';
        }
        if($ret==''){
            $ret = '<div class="warning">'.TEXT_MIXXXER_CHOOSE_EXTRA.'</div>';
        }


        return $ret;

    }
    // --- eof -- changes -- h.koch for alkim-media -- 03.2016 --

    function give_item_list_alternative() {
        $i = 0;

        $items = $this->resort();

        if (!is_array($items)) {
            $items = array();
        }
        $files = $this->getCFiles();

        foreach ($items AS $k => $v) {
            if ($v["id"]) {



                for ($i = 1; $i <= $v["qty"]; $i++) {
                    $ret .= '<div class="mixList2ImgWr">' . ($v["required"] == 0 || $v["multiselect"] == 1 ? '<a href="mixxxer_ajax_helper.php?action=dec_item&mi_id=' . $v["id"] . '" class="feature_ajax_link">' : '') . '<span>&nbsp;</span><img src="' . $v['img_2'] . '" />' . ($v["required"] == 0 || $v["multiselect"] == 1 ? '</a>' : '') . '</div>';
                }


            } else {
                unset($items[$k]);
            }
        }
        $ret .= '<div style="clear:both; height:5px; line-height:5px;">&nbsp;</div>';


        return $ret;

    }

    function give_item_list_fancy() {
        $i = 0;
        if (!is_array($this->items)) {
            $this->items = array();
        }
        $by_group = array();
        $sort     = array();
        $files    = $this->getCFiles();
        foreach ($this->items AS $k => $v) {
            if ($v["id"]) {
                $by_group[$v["group_name"]][] = $v;
                $sort[$v["group_name"]]       = $v["mg_sort"];
            }
        }
        array_multisort($sort, $by_group);
        $ret .= '<table>';
        foreach ($by_group AS $group => $items) {
            $sorti = array();
            foreach ($items AS $item) {
                $sorti[] = $item["mi_sort"];
            }
            array_multisort($sorti, $items);
            $ret .= '<tr><td colspan="2"><h4>' . $group . '</h4></td></tr>';
            foreach ($items AS $k => $v) {
                if ($this->c_texts[$v["id"]] != '') {
                    $text = $this->c_texts[$v["id"]];
                } else {
                    $text = false;
                }

                $ret .= '<tr><td>
                                ' . ($v['img'] != '' ? '<img src="' . $v['img'] . '" />' : '') . '
                                </td><td>' . $v['name'] . (($v['qty'] > 1) ? ' (' . $v['qty'] . 'g)' : '') . '</span>
                                ' . ($text ? '<div>' . $text . '</div>' : '') . ($files[$v["id"]] ? '<div><a href="' . $files[$v["id"]]["path"] . '" target="_blank">' . $files[$v["id"]]["name"] . '</a></div>' . "\n" : '') . '
                                </td></tr>' . "\n";
            }
        }
        $ret .= '</table><div class="clearer">&nbsp;</div>';



        return $ret;

    }



    function give_item_list_plain() {

        $files = $this->getCFiles();
        $i     = 0;
        if (!is_array($this->items)) {
            $this->items = array();
        }
        $by_group = array();
        $sort     = array();
        foreach ($this->items AS $k => $v) {
            $by_group[$v["group_name"]][] = $v;
            $sort[$v["group_name"]]       = $v["mg_sort"];
        }
        array_multisort($sort, $by_group);
        $this->baseItem = '<div><b>'.TEXT_CART_BASE_HEADING.' ('.(1000-$this->count_items()).'g)</b></div>';
        foreach ($by_group AS $group => $items) {
            $sorti = array();
            foreach ($items AS $item) {
                $sorti[] = $item["mi_sort"];
            }
            array_multisort($sorti, $items);

            foreach ($items AS $k => $v) {
                $html = '';
                if ($v["id"]) {
                    if ($prev_group != $v["mg_supergroup"] && $v["mg_supergroup"] != '') {
                        $html .= '<div class="fl_line"><b>' . $v["mg_supergroup"] . '</b></div>' . "\n";
                    }

                    if ($this->c_texts[$v["id"]] != '') {
                        $text = $this->c_texts[$v["id"]];
                    } else {
                        $text = false;
                    }




                    $html .= $v['name'] . (($v['qty'] > 1) ? ' (' . $v['qty'] . 'g)' : '');
                    if ($v["model"] != '') {
                        $html .= '<i> - ' . $v["model"] . '</i>';
                    }
                    $html .= '<br/>' . "\n";
                    $html .= ($text ? '<i>' . $text . '</i><br />' . "\n" : '');
                    $html .= ($files[$v["id"]] ? '<i><a href="' . $files[$v["id"]]["path"] . '" target="_blank">' . $files[$v["id"]]["name"] . '</a></i><br />' . "\n" : '');

                    $prev_group = $v["mg_supergroup"];

                    if($v["group_id"] != BASE_MG_ID){
                        $ret .= $html;
                    }else{
                        $this->baseItem .= '<div>'.$v['name'].' ('.$v['qty'].'%)</div>';
                    }
                }
            }
        }





        if ($this->comment != '') {

            $ret .= "<br/>\n<b>" . MIXXX_COMMENT_2 . "</b><br/>\n" . $this->comment . "\n";
        }

        if (count($this->files) > 0) {

            $ret .= "<br />\n<b>" . MIXXXER_FILES . "</b><br />\n";
            $ret .= $this->give_file_list('outside');

        }

        return $this->baseItem.'<div><b>'.TEXT_CART_EXTRA_HEADING.'</b></div>'.$ret;

    }

    function precheck($force = false) {

        if (count($this->items) == 0 || $force) {

            $q = "SELECT * FROM mixxxer_groups
        WHERE
          language_id = " . (int) $_SESSION["languages_id"] . "
          ORDER BY mg_sortorder ASC
          ";

            $rs = mysql_query($q);
            $i  = 0;

            $items_by_group = array();

            foreach ($this->items AS $item) {
                $items_by_group[$item['group_id']][] = $item["id"];
            }

            while ($mg_r = mysql_fetch_array($rs)) {


                if (!isset($items_by_group[$mg_r["mg_id"]]) || count($items_by_group[$mg_r["mg_id"]]) < 1) {

                    $q = "SELECT * FROM mixxxer_items mi, mixxxer_items_to_mixxxer_groups mi2mg, mixxxer_items_active mia
                        WHERE
                          mi.language_id = " . (int) $_SESSION["languages_id"] . "
                            AND
                          mia.mia_mi_id = mi.mi_id
                            AND
                          mia.mia_products_id = $this->mixxxer_product
                            AND
                          mi2mg.mg_id = " . $mg_r["mg_id"] . "

                            AND
                          mi2mg.mi_id = mi.mi_id
                          GROUP BY mi.mi_id
                          ORDER BY mia_sortorder ASC

                          ";


                    $mi_rs      = xtc_db_query($q);
                    $prechecked = array();
                    $first      = 0;
                    while ($mi_r = mysql_fetch_array($mi_rs)) {
                        $ref = $mi_r["mi_comp_gr_ref"];
                        if (strpos($ref, ',') !== false) {
                            $ref = explode(',', $ref);
                        } else {
                            $ref = array(
                                $ref
                            );
                        }

                        $req = $mi_r["mi_comp_gr_only"];
                        if (strpos($req, ',') !== false) {
                            $req = explode(',', $req);
                        } else {
                            $req = array(
                                $req
                            );
                        }

                        $possible = false;

                        if (count($req) == 1 && $req[0] == "") {
                            $possible = true;
                        } else {

                            foreach ($req AS $req_cg) {
                                if (in_array($req_cg, $this->compGroups)) {
                                    $possible = true;
                                }
                            }
                        }

                        foreach ($ref AS $ref_cg) {
                            if (in_array($ref_cg, $this->compGroups)) {
                                $possible = false;
                            }
                        }


                        if ($possible) {

                            if ($first == 0) {
                                $first = $mi_r["mi_id"];

                            }
                            if ($mi_r["mia_checked"] == 1) {
                                $prechecked[] = $mi_r["mi_id"];
                            }
                        }

                    }


                    if (count($prechecked) > 0) {
                        foreach ($prechecked AS $item_id) {
                            $this->add_item($item_id);
                        }
                    } elseif ($mg_r["mg_required"] == "1" && MIXXXER_GUIDED != 1) {

                        $this->add_item($first);
                    }
                }


                $i++;



            }
        }

    }

    function resort() {

        $sorter = array();
        $copy   = $this->items;
        foreach ($this->items AS $k => $item) {
            $sorter[$k] = $item["general_sort"];
        }

        array_multisort($sorter, $copy);
        return $copy;

    }

    // --- bof -- changes -- h.koch for alkim-media -- 03.2016 --
    function resort2() {
        $copy   = $this->items;
        usort($copy, 'item_cmp');
        return $copy;
    }
    // --- eof -- changes -- h.koch for alkim-media -- 03.2016 --


    function decrementProducts($qty = 1) {
        foreach ($this->items AS $id => $item) {
            if ($item["mi_product"]) {
                $q = "UPDATE products SET products_quantity = products_quantity - " . ($qty * $item["qty"]) . " WHERE products_id = " . $item["mi_product"];
                mysql_query($q);
            }
        }
    }

    function getIngredients(){

        $baseQty = 1000;
        $addQty = $this->count_items();
        $baseQty -= $addQty;
        $ingredients = array();
        foreach($this->items AS $item){
            $ingredient = array('data'=>$item);
            if($item["group_id"] == BASE_MG_ID){
                $ingredient["is_base"] = true;
                $ingredient["qty"] = $baseQty/100*$item["qty"];
            }else{
                $ingredient["is_base"] = false;
                $ingredient["qty"] = $item["qty"];
            }
            $ingredients[] = $ingredient;
        }
        $this->ingredients = $ingredients;
        return $ingredients;
    }

    function getIngredientInformation(){
        $ingredients = $this->getIngredients();
        $additives = array();
        $components = array();

        foreach($ingredients AS $v){
            $id = $v["data"]["id"];
            $q = "SELECT * FROM mixxxer_items WHERE mi_id = ".(int)$id." AND language_id = ".$_SESSION["languages_id"];
            $rs = xtc_db_query($q);
            $r = xtc_db_fetch_array($rs);
            if($r["mi_additives"]){
                $t = explode(',', $r["mi_additives"]);
                foreach($t AS $additive){
                    $additive = trim($additive);
                    if(!empty($additive)){
                        $additives[] = trim($additive);
                    }
                }
            }
            if($r["mi_components"]){
                $t = explode(',', $r["mi_components"]);
                foreach($t AS $component){
                    $component = trim($component);
                    if(!empty($component)){
                        $data = explode(':', $component);
                        $name = trim($data[0]);
                        $qty = trim($data[1]);
                        //var_dump($name, $qty, $v["qty"]);
                        $components[$name] += $qty/100*$v["qty"]/10;
                    }
                }
            }
        }
        $additives = array_unique($additives);
        usort($ingredients, "cmp_by_qty");
        arsort($components);
        arsort($additives);
        return array('ingredients'=>$ingredients, 'additives'=>$additives, 'components'=>$components);
    }

    function getLabelHtml($mix_id = ''){
        $data = $this->getIngredientInformation();
        $smarty = new Smarty;
        $ingredientArr = array();
        foreach($data["ingredients"] AS $ing){
            $ingredientArr[] = $ing["data"]["name"];
        }
        $componentArr = array();
        foreach($data["components"] AS $name=>$qty){
            $componentArr[] = $name.' '.number_format($qty, 2, ',', '.').'%';
        }
        $smarty->assign('components', implode(', ', $componentArr));
        $smarty->assign('ingredients', implode(', ', $ingredientArr));
        $smarty->assign('additives', implode(', ', $data["additives"]));
        $smarty->assign('name', $this->name);
        $smarty->assign('mix_id', $mix_id);
        $html = $smarty->fetch(CURRENT_TEMPLATE.'/module/mixxxer/label.html');
        return $html;

    }
    /*
    function calc_nutrition(){
    $total = array();

    foreach($this->features AS $k=>$v){
    $total["kcal"] += $v['qty']*$v["weight"]/100*$v['kcal'];
    $total["carb"] += $v['qty']*$v["weight"]/100*$v['carb'];
    $total["prot"] += $v['qty']*$v["weight"]/100*$v['prot'];
    $total["fat"] += $v['qty']*$v["weight"]/100*$v['fat'];
    }


    $v = $this->base;
    $v["weight"] = $this->weight-$this->calc_feature_weight();
    $total["kcal"] += $v['qty']*$v["weight"]/100*$v['kcal'];
    $total["carb"] += $v['qty']*$v["weight"]/100*$v['carb'];
    $total["prot"] += $v['qty']*$v["weight"]/100*$v['prot'];
    $total["fat"] += $v['qty']*$v["weight"]/100*$v['fat'];

    if($this->weight != 0){
    $total["kcal"] = round($total["kcal"]/$this->weight*100, 1);
    $total["carb"] = round($total["carb"]/$this->weight*100, 1);
    $total["prot"] = round($total["prot"]/$this->weight*100, 1);
    $total["fat"] = round($total["fat"]/$this->weight*100, 1);
    $this->total = $total;
    }

    }

    function display_nutrition(){
    $this->calc_nutrition();

    $ret .= '<div class="nutrition_table">
    <div class="nut_capt">'.NUT_KCAL.'</div>
    <div class="nut_value">'.$this->total['kcal'].NUT_UNIT_KCAL.'</div>

    <div class="nut_capt">'.NUT_CARB.'</div>
    <div class="nut_value">'.$this->total['carb'].NUT_UNIT_CARB.'</div>

    <div class="nut_capt">'.NUT_PROT.'</div>
    <div class="nut_value">'.$this->total['prot'].NUT_UNIT_PROT.'</div>

    <div class="nut_capt">'.NUT_FAT.'</div>
    <div class="nut_value">'.$this->total['fat'].NUT_UNIT_FAT.'</div>
    </div>
    ';
    return $ret;

    }


    */





}


// --- bof -- changes -- h.koch for alkim-media -- 03.2016 --
// Vergleichsfunktion für Auflistung Mixxer
function item_cmp($item1, $item2) {
  $gid1 = (int)$item1['group_id'];
  $gid2 = (int)$item2['group_id'];

  $mid1 = (int)$item1['mi_id'];
  $mid2 = (int)$item2['mi_id'];

  $ret = 0;
  if( $gid1==$gid2 ) {
    $ret = 0;
  } else {
    $ret = ($gid1 < $gid2) ? -1 : 1;
  }

  // wenn gids gleich waren
  if( $ret==0 ) {
    $ret = ($mid1 < $mid2) ? -1 : 1;
  }
  return $ret;
}
// --- eof -- changes -- h.koch for alkim-media -- 03.2016 --




function get_feature_price($mi_id) { //NOT PORTED
    $q  = "SELECT * FROM mixxxer_items WHERE mi_id = $mi_id AND language_id = '" . (int) $_SESSION['languages_id'] . "'";
    $rs = mysql_query($q);
    $r  = mysql_fetch_object($rs);
    return $r->mi_price;
}


?>
