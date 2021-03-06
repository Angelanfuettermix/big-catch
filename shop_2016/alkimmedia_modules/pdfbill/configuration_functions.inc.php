<?php

//PLEASE RENAME CLASS NAME
class pdfbill_installer{


    public static function install(){
        //SQL MAY BE PHPMYADMIN EXPORT
                $sql = "

ALTER TABLE `admin_access` ADD `pdfbill_config` INT( 1 ) DEFAULT '1' NOT NULL ;
ALTER TABLE `admin_access` ADD `pdfbill_display` INT( 1 ) DEFAULT '1' NOT NULL ;

CREATE TABLE IF NOT EXISTS `pdfbill_profile` (
  `profile_id` int(11) NOT NULL AUTO_INCREMENT,
  `profile_name` varchar(255) NOT NULL DEFAULT '',
  `profile_parameter` text NOT NULL,
  `profile_categories` text NOT NULL,
  `is_default` tinyint(1) NOT NULL,
  `is_invoice` tinyint(1) NOT NULL,
  PRIMARY KEY (`profile_id`)
);

        
ALTER TABLE `orders` 
  -- ADD `ibn_billnr` VARCHAR(64) NOT NULL , 
  -- ADD `ibn_billdate` DATE NOT NULL ,
  ADD `ibn_pdfnotifydate` DATE NOT NULL ;

INSERT INTO `configuration` ( `configuration_id` , `configuration_key` , `configuration_value` , `configuration_group_id` , `sort_order` , `last_modified` , `date_added` , `use_function` , `set_function` )
VALUES (
'', 'IBN_BILLNR', '1', '1', '99', NULL , '0000-00-00 00:00:00', NULL , ''
);            
INSERT INTO `configuration` ( `configuration_id` , `configuration_key` , `configuration_value` , `configuration_group_id` , `sort_order` , `last_modified` , `date_added` , `use_function` , `set_function` )
VALUES (
'', 'IBN_BILLNR_FORMAT', '{n}-{d}-{m}-{y}', '1', '99', NULL , '0000-00-00 00:00:00', NULL , ''
);            
INSERT INTO `configuration` ( `configuration_id` , `configuration_key` , `configuration_value` , `configuration_group_id` , `sort_order` , `last_modified` , `date_added` , `use_function` , `set_function` )
VALUES (
'', 'IBN_DEFAULT_PROFILE', 'default', '99999', '99', NULL , '0000-00-00 00:00:00', NULL , ''
);            



INSERT INTO `alkim_module_configuration` (`amc_key`, `amc_value`, `amc_language`) VALUES
('PDFBILL_INSTALLED', '1', 0);


";

                  $import = $sql;
                  
                  $import = preg_replace ("%/\*(.*)\*/%Us", '', $import);
                  $import = preg_replace ("%^--(.*)\n%mU", '', $import);
                  $import = preg_replace ("%^$\n%mU", '', $import);
                  
                  mysql_real_escape_string($import); 
                  $import = explode (";", $import); 
                  
                  foreach ($import as $imp){
                  
                    if (trim($imp) != '' && $imp != ' '){
                             xtc_db_query($imp); 
                    }
                  } 
                  xtc_db_query("
INSERT INTO `pdfbill_profile` (`profile_name`, `profile_parameter`, `profile_categories`) VALUES 
('default', 'bgimage_display=1,bgimage_image=hintergrund.png,headtext_display=1,headtext_text=Muster GBR Unterhaltungselektronik,headtext_font_color=#0000CC,headtext_font_type=arial,headtext_font_style={B;I},headtext_font_size=18,headtext_horizontal=15,headtext_vertical=0,headtext_width=,headtext_height=,addressblock_display=1,addressblock_text=Muster GBR#/K Postfach 4711#/K 12345 Flümme,addressblock_position=L,addressblock_font_color=,addressblock_font_type=arial,addressblock_font_style={B;U},addressblock_font_size=6,addressblock_position2=R,addressblock_font_color2=,addressblock_font_type2=arial,addressblock_font_style2={B;I},addressblock_font_size2=10,addressblock_horizontal=15,addressblock_vertical=15,addressblock_width=50,image_display=1,image_image=muster.jpg,image_horizontal=150,image_vertical=0,image_width=,image_height=,datafields_display=1,datafields_position=L,datafields_font_color=,datafields_font_type=arial,datafields_font_size=10,datafields_position2=R,datafields_font_color2=,datafields_font_type2=arial,datafields_font_style2={B},datafields_font_size2=10,datafields_text_1=Bestelldatum,datafields_value_1=*date_order*,datafields_text_2=Kundennummer,datafields_value_2=*customers_id*,datafields_text_3=Rechnungsnummer,datafields_value_3=*orders_id*,datafields_text_4=Rechnungsdatum,datafields_value_4=*date_invoice*,datafields_horizontal=110,datafields_vertical=80,datafields_width=40#/K30,billhead_display=1,billhead_text=Rechnung Nr: *orders_id*,billhead_position=L,billhead_font_color=,billhead_font_type=arial,billhead_font_style={B;I;U},billhead_font_size=12,billhead_horizontal=15,billhead_vertical=80,billhead_width=,billhead_height=,listhead_display=1,listhead_text=Rechnungspositionen,listhead_font_color=,listhead_font_type=arial,listhead_font_style={B},listhead_font_size=8,listhead_horizontal=15,listhead_vertical=100,listhead_width=,listhead_height=,poslist_font_color=,poslist_font_type=arial,poslist_font_size=6,poslist_head_1=Pos.,poslist_value_1=*pos_nr*,poslist_width_1=5,poslist_align_1=C,poslist_head_2=Art.Nr.,poslist_value_2=*p_model*,poslist_width_2=20,poslist_align_2=C,poslist_head_3=Artikel,poslist_value_3=*p_name*,poslist_width_3=105,poslist_align_3=L,poslist_head_4=Anz.,poslist_value_4=*p_qty*,poslist_width_4=5,poslist_align_4=C,poslist_head_5=Einz.Preis,poslist_value_5=*p_single_price*,poslist_width_5=15,poslist_align_5=R,poslist_head_6=Gesamt,poslist_value_6=*p_price*,poslist_width_6=15,poslist_align_6=R,poslist_head_7=,poslist_value_7=,poslist_width_7=,poslist_align_7=C,poslist_horizontal=15,poslist_vertical=,resumefields_display=1,resumefields_position=L,resumefields_font_color=,resumefields_font_type=arial,resumefields_font_size=8,resumefields_position2=R,resumefields_font_color2=,resumefields_font_type2=arial,resumefields_font_size2=8,resumefields_horizontal=60,resumefields_vertical=5,resumefields_width=80#/K40,subtext_display=1,subtext_text=Die Ware bleibt bis zur vollständigen Bezahlung Eigentum der Muster GBR ,subtext_font_color=,subtext_font_type=arial,subtext_font_size=8,subtext_horizontal=15,subtext_vertical=25,subtext_width=,subtext_height=,footer_display=1,footer_font_color=,footer_font_type=arial,footer_font_size=6,footer_display_1=1,footer_position_1=L,footer_text_1=Muster GbR Beispielstrasse 123 12345 Flümme,footer_display_2=1,footer_position_2=C,footer_text_2=Konto: 1234567 BLZ 222 333 44 Beispielbank,footer_display_3=1,footer_position_3=R,footer_text_3=HGR 32344424 AmtsG. Flümme StNr. 5545594,footer_position_4=L,footer_text_4=,terms_display=1,terms_formtext=Allgemeine Geschäftsbedingungen (AGB),terms_head_position=L,terms_head_font_style={B},terms_head_font_size=10,terms_font_color=,terms_font_type=arial,terms_font_size=6', '')
");


                  
                
    
    
    
    
    }






}




 


?>
