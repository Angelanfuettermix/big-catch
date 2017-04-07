<?php

//TITLE OF TAB IN ALKIM MODULE CONFIG (ADMIN)
$title = 'PDF Rechnung';

$config_values = array(
                       array(
                            'type'=>'hidden',         //BOOLEAN FIELD      
                            'key'=>'PDFBILL_INSTALLED',        //CONSTANT NAME FOR LATER USE
                            'title'=>
                                    array('german'=>'', 'english'=>'')
                      ),
                       array(
                            'type'=>'HTML',         //BOOLEAN FIELD      
                            'key'=>'<a href="'.xtc_href_link(FILENAME_PDFBILL_CONFIG, '', 'NONSSL').'">'.BOX_PDFBILL_CONFIG.'</a>',        //CONSTANT NAME FOR LATER USE
                            'title'=>
                                    array('german'=>'Konfiguration', 'english'=>'')
                      )
                      
                      
                    
                      
                      
                      
);

/*

OPTIONS

** 'shopConfig'=>true/false **

- only for single language fields
- only for field types short_text, long_text, radio and select

*/



//INSTALL METHOD FROM configuration_functions.inc.php
$installer_method = "pdfbill_installer::install"; 




?>
