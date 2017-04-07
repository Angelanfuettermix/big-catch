<?php

//TITLE OF TAB IN ALKIM MODULE CONFIG (ADMIN)
$title = 'Allgemein';

$config_values = array(
                    
                      array(
                            'type'=>'HTML',         //BOOLEAN FIELD      
                            'key'=>ALKIM_COLOR_SCHEME_INTRO,        //CONSTANT NAME FOR LATER USE
                            'title'=>
                                    array('german'=>'Info', 'english'=>'Info')
                      ),
                      
                       array(
                            'type'=>'color',         //BOOLEAN FIELD      
                            'key'=>'AM_FONT_HIGHLIGHT',        //CONSTANT NAME FOR LATER USE
                            'title'=>
                                    array('german'=>'Highlight Text', 'english'=>'Highlight text')
                      ),
                      
                      array(
                            'type'=>'color',         //BOOLEAN FIELD      
                            'key'=>'AM_BACKGROUND_HIGHLIGHT_I_1',        //CONSTANT NAME FOR LATER USE
                            'title'=>
                                    array('german'=>'Highlight Hintergrund intensiv 1', 'english'=>'Highlight background intensive 1')
                      ),
                      
                      array(
                            'type'=>'color',         //BOOLEAN FIELD      
                            'key'=>'AM_BACKGROUND_HIGHLIGHT_I_2',        //CONSTANT NAME FOR LATER USE
                            'title'=>
                                    array('german'=>'Highlight Hintergrund intensiv 2', 'english'=>'Highlight background intensive 2')
                      ),
                      
                      array(
                            'type'=>'color',         //BOOLEAN FIELD      
                            'key'=>'AM_BACKGROUND_HIGHLIGHT_L_1',        //CONSTANT NAME FOR LATER USE
                            'title'=>
                                    array('german'=>'Highlight Hintergrund hell 1', 'english'=>'Highlight background light 1')
                      ),
                      array(
                            'type'=>'color',         //BOOLEAN FIELD      
                            'key'=>'AM_BACKGROUND_HIGHLIGHT_L_2',        //CONSTANT NAME FOR LATER USE
                            'title'=>
                                    array('german'=>'Highlight Hintergrund hell 2', 'english'=>'Highlight background light 2')
                      ),
                      
                       array(
                            'type'=>'color',         //BOOLEAN FIELD      
                            'key'=>'AM_BACKGROUND_HIGHLIGHT_L_3',        //CONSTANT NAME FOR LATER USE
                            'title'=>
                                    array('german'=>'Highlight Hintergrund hell 3', 'english'=>'Highlight background light 3')
                      ),
                      
                      
                      array(
                            'type'=>'color',         //BOOLEAN FIELD      
                            'key'=>'AM_BORDER_LIGHT',        //CONSTANT NAME FOR LATER USE
                            'title'=>
                                    array('german'=>'Rahmen hell ', 'english'=>'Border light')
                      ),
                      
                       array(
                            'type'=>'color',         //BOOLEAN FIELD      
                            'key'=>'AM_BORDER_DARK',        //CONSTANT NAME FOR LATER USE
                            'title'=>
                                    array('german'=>'Rahmen dunkel ', 'english'=>'Border dark')
                      ),
                      
);                       
                      
$classes = array(   'amHightlightText',
                    'amHightlightBgI1',
                    'amHightlightBgI2',
                    'amHighlightGradientI1',
                    'amHighlightGradientI2',
                    'amHightlightBgL1',
                    'amHightlightBgL2',
                    'amHightlightBgL3',
                    'amHighlightGradientL1',
                    'amHighlightGradientL2',
                    'amHightlightBorderLight',
                    'amHightlightBorderDark');
                    
                  
$colHTML = '<link rel="stylesheet" href="'.DIR_WS_CATALOG.'alkim.css.php'.(AmHandler::$noCache?'?noCache=1':'').'" type="text/css" media="screen" />';

foreach($classes AS $class){
    $colHTML .= '<div class="'.$class.'" style="padding:4px; margin-top:5px; border-width:1px; border-style:solid;">.'.$class.'</div>';
}

$config_values[] = array(
                            'type'=>'HTML',         //BOOLEAN FIELD      
                            'key'=>$colHTML,
                            'title'=>
                                    array('german'=>'CSS-Klassen ', 'english'=>'CSS classes')
);                  
      
           
                      
                    
                      
                      
      

/*

OPTIONS

** 'shopConfig'=>true/false **

- only for single language fields
- only for field types short_text, long_text, radio and select

*/



//INSTALL METHOD FROM configuration_functions.inc.php
$installer_method = "general_installer::install"; 




?>
