<?php


class mixxxer_installer{


    public static function install(){
    
                $sql = "
ALTER TABLE `sessions` CHANGE `value` `value` MEDIUMTEXT;
CREATE TABLE IF NOT EXISTS `mixxer_items_to_mixxxer_groups` (
  `mi2mg_id` int(11) NOT NULL auto_increment,
  `mg_id` int(11) NOT NULL,
  `mi_id` int(11) NOT NULL,
  PRIMARY KEY  (`mi2mg_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;



CREATE TABLE IF NOT EXISTS `mixxxer_comp_texts` (
  `mict_id` int(11) NOT NULL auto_increment,
  `mi_comp_gr` varchar(50) NOT NULL,
  `mi_comp_gr_text` text NOT NULL,
  `language_id` int(11) NOT NULL,
  `type` varchar(10) NOT NULL,
  PRIMARY KEY  (`mict_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;



CREATE TABLE IF NOT EXISTS `mixxxer_groups` (
  `mg_id` int(11) NOT NULL default '0',
  `language_id` int(11) NOT NULL default '1',
  `mg_name` varchar(32) character set latin1 collate latin1_german1_ci NOT NULL default '',
  `mg_sortorder` int(11) NOT NULL,
  `mg_multiselect` tinyint(4) NOT NULL,
  `mg_required` tinyint(4) NOT NULL,
  `mg_description` text NOT NULL,
  `mg_image` varchar(100) NOT NULL,
  `mg_comp_gr_ref` varchar(50) NOT NULL,
  `mg_comp_gr_only` varchar(50) NOT NULL,
  `mg_multiaccordion_open` tinyint(4) NOT NULL,
  `mg_maximum` int(11) NOT NULL,
  `mg_note` VARCHAR(200) NOT NULL,
  `mg_volume` TINYINT(1) NOT NULL,
  PRIMARY KEY  (`mg_id`,`language_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;



CREATE TABLE IF NOT EXISTS `mixxxer_items` (
  `mi_id` int(11) NOT NULL default '0',
  `language_id` int(11) NOT NULL default '1',
  `mi_name` varchar(64) character set latin1 collate latin1_german1_ci NOT NULL default '',
  `mi_sortorder` int(11) NOT NULL,
  `mi_description` text NOT NULL,
  `mi_image` varchar(200) NOT NULL,
  `mi_image_2` varchar(200) NOT NULL,
  `mi_maximum` int(11) NOT NULL,
  `mi_comp_gr` varchar(50) NOT NULL,
  `mi_comp_gr_ref` varchar(50) NOT NULL,
  `mi_comp_gr_only` varchar(50) NOT NULL,
  `mi_product` int(11) NOT NULL,
  `mi_free_val_1` varchar(50) NOT NULL,
  `mi_subgroup` varchar(100) NOT NULL,
  `mi_c_text` tinyint(1) NOT NULL,
  `mi_c_upload` tinyint(1) NOT NULL,
  `mi_free_val_1_unit` varchar(20) NOT NULL,
  `mi_free_val_1_factor` float NOT NULL,
  PRIMARY KEY  (`mi_id`,`language_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;




CREATE TABLE IF NOT EXISTS `mixxxer_items_active` (
  `mia_id` int(11) NOT NULL auto_increment,
  `mia_mi_id` int(11) NOT NULL,
  `mia_products_id` int(11) NOT NULL,
  `mia_price` decimal(15,4) NOT NULL,
  `mia_price_special` VARCHAR(50) NOT NULL,
  
  `mia_stock` float NOT NULL,
  `mia_checked` tinyint(4) NOT NULL,
  `mia_model` varchar(30) NOT NULL,
  `mia_sortorder` int(11) NOT NULL,
  `mia_weight` float NOT NULL,
  PRIMARY KEY  (`mia_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;



CREATE TABLE IF NOT EXISTS `mixxxer_items_to_mixxxer_groups` (
  `mi2mg_id` int(11) NOT NULL auto_increment,
  `mg_id` int(11) NOT NULL,
  `mi_id` int(11) NOT NULL,
  PRIMARY KEY  (`mi2mg_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;


CREATE TABLE IF NOT EXISTS `mixxxes` (
  `mix_id` int(11) NOT NULL auto_increment,
  `mix_key` varchar(10) NOT NULL,
  `mix_name` varchar(100) NOT NULL,
  `mix_code` text NOT NULL,
  `mix_user` int(11) NOT NULL,
  PRIMARY KEY  (`mix_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;



CREATE TABLE IF NOT EXISTS `mm_attr_profile` (
`mia_id` int( 11 ) NOT NULL AUTO_INCREMENT ,
`mia_mi_id` int( 11 ) NOT NULL ,
`mia_products_id` VARCHAR( 50 ) NOT NULL ,
`mia_price` float NOT NULL ,
`mia_stock` float NOT NULL ,
`mia_checked` tinyint( 4 ) NOT NULL ,
`mia_model` varchar( 30 ) NOT NULL ,
`mia_sortorder` int( 11 ) NOT NULL ,
`mia_weight` float NOT NULL ,
`mia_price_special` varchar( 50 ) NOT NULL ,
PRIMARY KEY ( `mia_id` )
) ENGINE = MYISAM;

CREATE TABLE IF NOT EXISTS `mm_config` (
  `am_config_id` int(11) NOT NULL auto_increment,
  `am_type` varchar(10) NOT NULL,
  `am_class` varchar(2) NOT NULL,
  `am_title` varchar(50) NOT NULL,
  `am_db_field` varchar(30) NOT NULL,
  `am_single_language` tinyint(1) NOT NULL,
  `am_admin` tinyint(1) NOT NULL,
  PRIMARY KEY  (`am_config_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;



INSERT INTO `mm_config` (`am_type`, `am_class`, `am_title`, `am_db_field`, `am_single_language`, `am_admin`) VALUES
('long_text', 'ov', 'Beschreibung', 'mi_description', 0, 0),
('image', 'ov', 'Bild', 'mi_image', 0, 0),
('image', 'ov', 'Bild für Zusammenfassung', 'mi_image_2', 0, 0),
('yesno', 'ov', 'Freitext-Option', 'mi_c_text', 1, 0),
('yesno', 'ov', 'Upload-Option', 'mi_c_upload', 1, 0),
('short_text', 'o', 'Kommentar', 'mg_note', 0, 0),
('yesno', 'o', 'Multiselect', 'mg_multiselect', 1, 0),
('short_text', 'ov', 'Maximum', 'mi_maximum', 1, 0),
('short_text', 'o', 'Maximum', 'mg_maximum', 1, 0),
('yesno', 'o', 'Auswahl erzwingen', 'mg_required', 1, 0),
('tags', 'ov', 'Kompatibilitätsgruppe', 'mi_comp_gr', 1, 0),
('tags', 'ov', 'Nicht kompatibel zu', 'mi_comp_gr_ref', 1, 0),
('long_text', 'o', 'Beschreibung', 'mg_description', 0, 0),
('image', 'o', 'Bild', 'mg_image', 0, 0),
('tags', 'ov', 'Nur kompatibel zu', 'mi_comp_gr_only', 1, 0),
('tags', 'o', 'Nicht kompatibel zu', 'mg_comp_gr_ref', 1, 0),
('tags', 'o', 'Nur kompatibel zu', 'mg_comp_gr_only', 1, 0),
('short_text', 'ov', 'Maximalwert-Summand', 'mi_free_val_1', 1, 0),
('short_text', 'ov', 'Maximalwert-Einheit', 'mi_free_val_1_unit', 1, 0),
('short_text', 'ov', 'Maximalwert-Einheitenfaktor', 'mi_free_val_1_factor', 1, 0),
('yesno', 'o', 'Im Multiakkordeon geöffnet', 'mg_multiaccordion_open', 1, 0),
('yesno', 'o', 'Flaeche/Volumen', 'mg_volume', 1, 0);




ALTER TABLE `admin_access` ADD `mixxxer_manager` TINYINT NOT NULL DEFAULT '1';
ALTER TABLE `products_options_values` ADD `mix_id` INT NOT NULL ;
ALTER TABLE `products` ADD `products_master_mixxxer` TINYINT NOT NULL, ADD `products_remixxx` TINYINT NOT NULL, ADD `products_price_from_mixxx` TINYINT NOT NULL, ADD `products_mix_id` INT NOT NULL, ADD products_max_values 	VARCHAR(100) NOT NULL,
ADD products_price_base VARCHAR(50) NOT NULL, ADD products_is_mixxxer TINYINT NOT NULL,
ADD products_overwrite_name TINYINT NOT NULL,
ADD products_mixxxer_view_toggle TINYINT NOT NULL,
ADD products_mixxxer_default_view TINYINT NOT NULL;

ALTER TABLE `products` ADD `products_volume_price` FLOAT NOT NULL;
ALTER TABLE `products` ADD `products_volume_unit` VARCHAR(20) NOT NULL;
ALTER TABLE `products` ADD `products_mixxxer_template` VARCHAR(50) NOT NULL;
ALTER TABLE `orders_products_attributes` CHANGE `products_options_values` `products_options_values` VARCHAR( 1500 );


INSERT INTO `alkim_module_configuration` (`amc_key`, `amc_value`, `amc_language`) VALUES
('MIXXXER_FB_APP_ID', '352532768138372', 0),
('MIXXXER_ACTIVE', '0', 0),
('MIXXXER_NOIMAGE_MODE', 1, 0),
('MIXXXER_TESTMODE', '1', 0),
('MIXXXER_FB_TEXT', 'Das hab ich mir auf www.tollecomputer.de zusammengestellt:\r\n{configuration}', 2),
('MIXXXER_NUM_UPLOADS', '2', 0),
('MIXXXER_ITEM_INFO_DISPLAY', 'click', 0),
('MIXXXER_GUIDED', '0', 0),
('MIXXXER_STYLE', 'multiaccordion', 0),
('MIXXXER_INCOMP_DISPLAY', 'hide', 0),
('MIXXXER_FB_TEXT', 'test', 1),
('MIXXXER_FB_HEADING', 'Mein neuer PC', 2),
('MIXXXER_FB_TEXT', 'test', 0),
('MIXXXER_FB_HEADING', 'My new PC', 1),
('MIXXXER_STANDARD_NAME', 'Mein PC', 2),
('MIXXXER_STANDARD_NAME', 'My PC', 1),
('MIXXXER_CONTINUE_GUIDED', '1', 0),
('MIXXXER_PRICE_DISPLAY_2', '1', 0),
('MIXXXER_GUIDED_CONTINUE', '0', 0),
('MIXXXER_LINK_TEXT', 'Mix it baby', 2),
('MIXXXER_IMAGE_CLICK', 'add', 0),
('MIXXXER_LINK_TEXT', 'Let''s Mixxx', 1),
('MIXXXER_MAX_VAL_UNIT', 'g', 2),
('MIXXXER_MAX_VAL_PERCENT', '1', 0),
('MIXXXER_MAX_VAL_HEADING', 'Verpackungsgröße', 2),
('MIXXXER_MAX_VAL_HEADING', '', 1),
('MIXXXER_MAX_VAL_HEADING_CHOOSE', 'Bitte wählen Sie Ihre Verpackungsgröße', 2),
('MIXXXER_MAX_VAL_HEADING_CHOOSE', '', 1),
('MIXXXER_MAX_VAL_BELOW', 'Ihre Packung ist noch nicht voll :-(', 2),
('MIXXXER_MAX_VAL_BELOW', '', 1),
('MIXXXER_MAX_VALUE_REACHED', 'Das Höchstgewicht ist erreicht', 2),
('MIXXXER_MAX_VALUE_REACHED', '', 1),
('MIXXXER_MAX_VALUE_TOO_LOW', 'Sie haben bereits mehr Elemente gewählt als diese Packungsgröße zulässt. Bitte entfernen Sie einige :-)', 2),
('MIXXXER_MAX_VALUE_TOO_LOW', '', 1),
('MIXXXER_MAX_VAL_UNIT', '', 1),
('MIXXXER_MAX_VAL_DISPLAY_HEADING', 'Ihr Füllstand', 2),
('MIXXXER_MAX_VALUE_TOO_LOW_NOCART', 1, 0),
('MIXXXER_MAX_VAL_DISPLAY_HEADING', '', 1);


INSERT INTO `mm_config` (

`am_type` ,
`am_class` ,
`am_title` ,
`am_db_field` ,
`am_single_language` ,
`am_admin`
)
VALUES (
'tags', 'o', 'Gruppe in der Zusammenstellung', 'mg_listgroup', '', ''
),

(
'tags', 'ov', 'Untergruppe', 'mi_subgroup', '', ''
);

ALTER TABLE `mixxxer_groups` ADD `mg_listgroup` VARCHAR( 100 ) NOT NULL ;



ALTER TABLE `mixxxes` ADD `mix_archive` BOOL NOT NULL ;


ALTER TABLE `mixxxer_groups` ADD `mg_disp` VARCHAR( 20 ) NOT NULL ;
ALTER TABLE `mixxxer_items_active` ADD INDEX ( `mia_mi_id` ) ;
ALTER TABLE `mixxxer_items_active` ADD INDEX ( `mia_products_id` ) ;
ALTER TABLE `mixxxer_items_to_mixxxer_groups` ADD INDEX ( `mg_id` ) ;
ALTER TABLE `mixxxer_items_to_mixxxer_groups` ADD INDEX ( `mi_id` ); 
";

            $qt = "SELECT * FROM products_options ORDER BY products_options_id DESC LIMIT 1";
            $rst = mysql_query($qt);
            $rt = mysql_fetch_object($rst);
            $new_po_id = (int)$rt->products_options_id + 1;                
            
            $languages = xtc_get_languages();
            foreach ($languages AS $l){
                $sql_array = array(
                                  'language_id'=>$l["id"],
                                  'products_options_id' => $new_po_id,
                                  'products_options_name'=>'Configuration'
                                  
                );
                
                xtc_db_perform('products_options', $sql_array);
            } 
            xtc_db_query("INSERT INTO `alkim_module_configuration` (`amc_key`, `amc_value`, `amc_language`) VALUES
('CONFIG_OPT_GROUP', '$new_po_id', 0)");     
                  
                  $import = $sql;
                  
                  $import = preg_replace ("%/\*(.*)\*/%Us", '', $import);
                  $import = preg_replace ("%^--(.*)\n%mU", '', $import);
                  $import = preg_replace ("%^$\n%mU", '', $import);
                  
                  mysql_real_escape_string($import); 
                  $import = explode (";", $import); 
                  
                  foreach ($import as $imp){
                    //var_dump($imp);
                    //echo "\n\n\n\n\n";
                    if (trim($imp) != '' && $imp != ' '){
                             xtc_db_query($imp); 
                    }
                  } 
                  
                
    
    
    
    
    }






}




 


?>
