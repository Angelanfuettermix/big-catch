<?php


$title = 'Mixxxer';

$config_values = array(
                       array(
                            'type'=>'bool',
                            'key'=>'MIXXXER_ACTIVE',
                            'title'=>
                                    array('german'=>'Mixxxer verwenden', 'english'=>'Use Mixxxer'),
                            'description'=>
                                    array('german'=>'', 'english'=>'')
                      ),
                      array(
                            'type'=>'bool',
                            'key'=>'MIXXXER_TESTMODE',
                            'title'=>
                                    array('german'=>'Testmodus verwenden', 'english'=>'Use test mode'),
                            'description'=>
                                    array('german'=>'Im Testmodus können nur Administratoren den Mixxxer aufrufen', 'english'=>'In test mode, only administrators can access the Mixxxer')
                      ),

                      array(
                            'type'=>'bool',
                            'key'=>'MIXXXER_NOIMAGE_MODE',
                            'title'=>
                                    array('german'=>'Platzhalter bei fehlendem Bild', 'english'=>'Placeholder for missing image'),
                            'description'=>
                                    array('german'=>'Soll bei fehlenden Bildern der Mixxxer-Werte ein Platzhalter verwendet werden?', 'english'=>'Use placeholder when images are missing on Mixxxer values?')
                      ),

                      array(
                            'type'=>'short_text',
                            'key'=>'MIXXXER_FB_APP_ID',
                            'title'=>
                                    array('german'=>'Facebook AppID', 'english'=>'Facebook AppID'),
                            'description'=>
                                    array('german'=>'Wird zum veröffentlichen der Konfiguration durch den Kunden benötigt', 'english'=>'Required to publish the configuration of the customer')
                      ),

                      array(
                            'type'=>'short_text',
                            'key'=>'MIXXXER_FB_HEADING',
                            'title'=>
                                    array('german'=>'&Uuml;berschrift f&uuml;r Facebook-Post', 'english'=>'Title for Facebook-Post'),
                            'description'=>
                                    array('german'=>'', 'english'=>''),
                            'lang'=>1
                      ),
                      array(
                            'type'=>'long_text',
                            'key'=>'MIXXXER_FB_TEXT',
                            'title'=>
                                    array('german'=>'Text f&uuml;r Facebook-Post', 'english'=>'Text for Facebook-Post'),
                            'description'=>
                                    array('german'=>'{configuration} als Platzhalter für die Liste der Konfigurationselemente', 'english'=>'{configuration} as a placeholder for the list of configuration items'),
                            'lang'=>1
                      ),

                      array(
                            'type'=>'short_text',
                            'key'=>'MIXXXER_LINK_TEXT',
                            'title'=>
                                    array('german'=>'Link zum Mixxxer', 'english'=>'Link to the Mixxxer'),
                            'description'=>
                                    array('german'=>'', 'english'=>''),
                            'lang'=>1
                      ),

                      array(
                            'type'=>'short_text',
                            'key'=>'MIXXXER_MAX_VAL_HEADING',
                            'title'=>
                                    array('german'=>'Titel für Maximalwert', 'english'=>'Title for maxvalue'),
                            'description'=>
                                    array('german'=>'', 'english'=>''),
                            'lang'=>1
                      ),
                      array(
                            'type'=>'short_text',
                            'key'=>'MIXXXER_MAX_VAL_UNIT',
                            'title'=>
                                    array('german'=>'Einheit des Maximalwerts', 'english'=>'Unit for the maxvalue'),
                            'description'=>
                                    array('german'=>'', 'english'=>''),
                            'lang'=>1
                      ),
                       array(
                            'type'=>'bool',
                            'key'=>'MIXXXER_MAX_VAL_PERCENT',
                            'title'=>
                                    array('german'=>'Maximalwert in % anzeigen', 'english'=>'Show maxvalue in %'),
                            'description'=>
                                    array('german'=>'', 'english'=>''),
                            'lang'=>1
                      ),
                      array(
                            'type'=>'long_text',
                            'key'=>'MIXXXER_MAX_VAL_HEADING_CHOOSE',
                            'title'=>
                                    array('german'=>'Text für Auswahl aus mehreren Maximalwerten', 'english'=>'Text for selection of several maxvalues'),
                            'description'=>
                                    array('german'=>'', 'english'=>''),
                            'lang'=>1
                      ),

                      array(
                            'type'=>'short_text',
                            'key'=>'MIXXXER_MAX_VAL_BELOW',
                            'title'=>
                                    array('german'=>'Text für nicht erreichten Maximalwert', 'english'=>'Text for not reached maxvalue'),
                            'description'=>
                                    array('german'=>'', 'english'=>''),
                            'lang'=>1
                      ),
                       array(
                            'type'=>'short_text',
                            'key'=>'MIXXXER_MAX_VAL_DISPLAY_HEADING',
                            'title'=>
                                    array('german'=>'Überschrift für die Füllstandsanzeige', 'english'=>'Title for filing indicator'),
                            'description'=>
                                    array('german'=>'', 'english'=>''),
                            'lang'=>1
                      ),
                       array(
                            'type'=>'short_text',
                            'key'=>'MIXXXER_MAX_VALUE_REACHED',
                            'title'=>
                                    array('german'=>'Fehlermeldung bei Überschreiten des Maximalwertes', 'english'=>'Error message when the maxvalue is exceeded'),
                            'description'=>
                                    array('german'=>'', 'english'=>''),
                            'lang'=>1
                      ),

                       array(
                            'type'=>'short_text',
                            'key'=>'MIXXXER_MAX_VALUE_TOO_LOW',
                            'title'=>
                                    array('german'=>'Fehlermeldung bei Auswahl eines zu kleinen Maximalwertes', 'english'=>'Error message when selecting a too small maxvalue'),
                            'description'=>
                                    array('german'=>'', 'english'=>''),
                            'lang'=>1
                      ),
                       array(
                            'type'=>'bool',
                            'key'=>'MIXXXER_MAX_VALUE_TOO_LOW_NOCART',
                            'title'=>
                                    array('german'=>'Nur volle Packungen in den Warenkorb legen lassen', 'english'=>'Allow only to add full packs to cart'),
                            'description'=>
                                    array('german'=>'', 'english'=>''),
                            'lang'=>0
                      ),

                      array(
                            'type'=>'short_text',
                            'key'=>'MIXXXER_NUM_UPLOADS',
                            'title'=>
                                    array('german'=>'Anzahl der möglichen Kunden-Uploads', 'english'=>'Number of possible customer uploads'),
                            'description'=>
                                    array('german'=>'', 'english'=>'')
                      ),

                      array(
                            'type'=>'select',
                            'key'=>'MIXXXER_ITEM_INFO_DISPLAY',
                            'title'=>
                                    array('german'=>'Anzeige der Zusatzinformationen zu Konfiguraionselementen', 'english'=>'Displays additional information about configuration items'),
                            'description'=>
                                    array('german'=>'', 'english'=>''),
                            'options'=>array(
                                          'click'=>array('german'=>'Klicken', 'english'=>'Click'),
                                          'mouseover'=>array('german'=>'Mouse Over', 'english'=>'Mouse Over')
                                          )
                      ),

                      array(
                            'type'=>'bool',
                            'key'=>'MIXXXER_GUIDED',
                            'title'=>
                                    array('german'=>'Schrittweise Konfiguration', 'english'=>'Step by step configuration'),
                            'description'=>
                                    array('german'=>'Schaltet die nächste Optionsgruppe immer erst dann frei, wenn aus der vorherigen ein Element gewählt wurde', 'english'=>'Only enables the next options group, when an item from the previous has been selected')

                      ),




                      array(
                            'type'=>'select',
                            'key'=>'MIXXXER_STYLE',
                            'title'=>
                                    array('german'=>'Darstellung', 'english'=>'Appearance'),
                            'description'=>
                                    array('german'=>'', 'english'=>''),
                            'options'=>array(
                                          'tabs'=>array('german'=>'in Tabs', 'english'=>'in Tabs'),
                                          'accordion'=>array('german'=>'als Akkordeon', 'english'=>'as accordion'),
                                          'multiaccordion'=>array('german'=>'als Multi-Akkordeon', 'english'=>'as multi accordion')
                                          )
                      ),

                       array(
                            'type'=>'select',
                            'key'=>'MIXXXER_INCOMP_DISPLAY',
                            'title'=>
                                    array('german'=>'Darstellung inkompatibler Elemente', 'english'=>'Appearance of incompatible elements'),
                            'description'=>
                                    array('german'=>'', 'english'=>''),
                            'options'=>array(
                                          'hide'=>array('german'=>'verstecken', 'english'=>'hide'),
                                          'disable'=>array('german'=>'deaktivieren', 'english'=>'disable')
                                          )
                      ),

                      array(
                            'type'=>'short_text',
                            'key'=>'MIXXXER_STANDARD_NAME',
                            'title'=>
                                    array('german'=>'Name für neue Konfiguration', 'english'=>'Name for new configuration'),
                            'description'=>
                                    array('german'=>'', 'english'=>''),
                            'lang'=>1
                      ),


                      array(
                            'type'=>'select',
                            'key'=>'MIXXXER_PRICE_DISPLAY_2',
                            'title'=>
                                    array('german'=>'Preisdarstellung', 'english'=>'Price appearance'),
                            'description'=>
                                    array('german'=>'', 'english'=>''),
                            'options'=>array(
                                          '1'=>array('german'=>'reale Preise (Auf- bzw Abpreise)', 'english'=>'Real price (up- resp. downprices)'),
                                          '0'=>array('german'=>'Stückpreise', 'english'=>'Itemprices')
                                          )
                      ),

                       array(
                            'type'=>'select',
                            'key'=>'MIXXXER_IMAGE_CLICK',
                            'title'=>
                                    array('german'=>'Verhalten bei Klick auf Bild eines Konfigurationselementes', 'english'=>'Behavior when clicking on an image configuration element'),
                            'description'=>
                                    array('german'=>'', 'english'=>''),
                            'options'=>array(
                                          'zoom'=>array('german'=>'Bild vergr&ouml;ßern', 'english'=>'Zoom image'),
                                          'add'=>array('german'=>'Element zur Konfiguration hinzuf&uuml;gen', 'english'=>'Add element to the configuration')
                                          )
                      ),

                       array(
                            'type'=>'container_link',
                            'key'=>'mixes.php',
                            'title'=>
                                    array('german'=>'Mixxxes', 'english'=>'Mixxxes'),
                            'description'=>
                                    array('german'=>'', 'english'=>'')
                      ),






);

if(basename($_SERVER["SCRIPT_NAME"])=='alkim_module.php'){
	$add_link = '';

if($_SESSION["mixxxer"]->master_mixxxer_pid!=0){
	$add_link = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="'.xtc_href_link('categories.php', 'action=new_product&cPath=0&extra=mixxxer&pID='.$_SESSION["mixxxer"]->master_mixxxer_pid).'">'.GOTO_MASTER_MIXXXER.'</a>';
}
$config_values[] = array(
	                            'type'=>'HTML',
	                            'key'=>'<a href="'.xtc_href_link('categories.php', 'action=new_product&cPath=0&extra=mixxxer').'">'.CREATE_NEW_MIXXXER.'</a> '.$add_link,
	                            'title'=>
	                                    array('german'=>'Haupt-Mixxxer anlegen', 'english'=>'Create Main-Mixxxer'),
	                            'description'=>
	                                    array('german'=>'', 'english'=>'')
	                      );


}




$installer_method = "mixxxer_installer::install";

?>
