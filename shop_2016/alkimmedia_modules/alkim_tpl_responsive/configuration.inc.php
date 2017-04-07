<?php

/**
 * @copyright (c) 2015, Alkim Media
 * @author TP <tp@alkim.de>
 */

// generate fields from configuration xml
$xml = ResonsiveTemplateInstaller::getConfigXML();
ResonsiveTemplateInstaller::clearCache();
/** DEBUG * *
echo '<strong>' . __FILE__ . '::' . __LINE__ . '</strong><br />';
echo '<pre>$xml: ' . print_r($xml, true) . '</pre>';
die();
/** * */ 


// TITLE OF TAB IN ALKIM MODULE CONFIG (ADMIN)
$title = $xml['name'];

$config_values = array(
  'INFO' => array(
    array(
      'type'  => 'HTML',
      'key'   => ResonsiveTemplateInstaller::KEY_PREFIX . 'INTRO',
    ),
    array(
      'type'  => 'HTML',
      'key'   => ResonsiveTemplateInstaller::KEY_PREFIX . 'RESET',
    ),
    array(
      'type'  => 'HTML',
      'key'   => ResonsiveTemplateInstaller::KEY_PREFIX . 'CLEARCACHE',
    ),
  ),
);                       

// activate template
if (isset($_GET['activateTemplate'])) {
  $stmt = 'UPDATE ' . TABLE_CONFIGURATION . ' ' .
          'SET configuration_value = "alkim_tpl_responsive" ' .
          'WHERE configuration_key = "CURRENT_TEMPLATE"';
  $query = xtc_db_query($stmt);
}

// reset to default values
if (isset($_GET['resetTemplate'])) {
  ResonsiveTemplateInstaller::install();
}

// reset to default values
if (isset($_GET['clearCache'])) {
  ResonsiveTemplateInstaller::clearCache();
}

// check if template is set
$stmt = 'SELECT configuration_value  ' .
        'FROM ' . TABLE_CONFIGURATION . ' ' .
        'WHERE configuration_key = "CURRENT_TEMPLATE"';
$query = xtc_db_query($stmt);
if (0 < xtc_db_num_rows($query)) {
  $row = xtc_db_fetch_array($query);
  if ('alkim_tpl_responsive' != $row['configuration_value']) {
    $config_values['INFO'][] = array(
      'type'  => 'HTML',
      'key'   => ResonsiveTemplateInstaller::KEY_PREFIX . 'TEMPLATE_NOT_SET',
    );
  }
}

// check if cache folder is writeable
if (!is_writable(dirname(__FILE__) . '/cache/')) {
  $config_values['INFO'][] = array(
    'type'  => 'HTML',
    'key'   => ResonsiveTemplateInstaller::KEY_PREFIX . 'CACHE_NOT_WRITEABLE',
  );
}

// get elements from configuration xml as accordeon group
foreach ($xml->tab as $tab) {
  $values = array();
  $content = ResonsiveTemplateInstaller::getContentConfig($tab->content, (string) $tab['title']);
  
  if (count($content)) {
    $values = array_merge($values, $content);
  }
  $rules = ResonsiveTemplateInstaller::getRuleConfig($tab->rule);
  if (count($rules)) {
    $values = array_merge($values, $rules);
  }
foreach($values AS $k=>$v){
    if(RT_GLOBAL_MODE_SIMPLE == '1' && !in_array($tab['title'], array('info', 'global'))){
        
        if(!$v["simple"] && $v["type"] != 'heading' && $v["type"] != 'HTML'){
           unset($values[$k]);
        }
    }
}

$values = array_values($values);

foreach($values AS $k=>$v){
    if($v["type"] == 'HTML'){
        if(!isset($values[$k+1]) || $values[$k+1]["type"] == 'heading' || $values[$k+1]["type"] == 'HTML'){
            unset($values[$k]);
        }
    }
}

$values = array_values($values);

foreach($values AS $k=>$v){
    if($v["type"] == 'heading'){
        if(!isset($values[$k+1]) || $values[$k+1]["type"] == 'heading'){
            unset($values[$k]);
        }
    }
}

  
  if (count($values)) {
        $config_values[strtoupper($tab['title'])] = $values;
  }
}

$config_values['CUSTOM'] = array(
  array(
    'type'  => 'long_text',
    'key'   => ResonsiveTemplateInstaller::KEY_PREFIX . 'CUSTOM_CSS',
  ),
);                       

// set current language from translation file
foreach ($config_values as &$g) {
  foreach ($g as &$v) {
    $t = ResonsiveTemplateInstaller::detectLanguageValue($v['key'], 'TITLE');
    $v['title'] = array(
      //$_SESSION['language'] => defined($c) ? constant($c) : $v['key'],
      $_SESSION['language'] => '' != $t ? $t : $v['key'],
    );
    $d = ResonsiveTemplateInstaller::detectLanguageValue($v['key'], 'DESC');
    if ('' != $d) {
      $v['description'] = array(
        $_SESSION['language'] => $d,
      );
    }
    if ('HTML' == $v['type']) {
      $v['key'] = $v['description'][$_SESSION['language']];
      unset($v['description']);
    }
  }
}


if(RT_GLOBAL_MODE_SIMPLE == '1'){

/*

    $config_values["GLOBAL"][] = array(
                                            'type'=>'color',
                                            'key'=>'AM_FONT_HIGHLIGHT_LINK',
                                            'title'=>
                                                    array('german'=>'Farbe der Verweise', 'english'=>'')
                                      );
    
    $config_values["GLOBAL"][] = array(
                                            'type'=>'color',
                                            'key'=>'AM_BACKGROUND_HIGHLIGHT_MENU',
                                            'title'=>
                                                    array('german'=>'Hintergrundfarbe der horizontalen Navigation', 'english'=>'')
                                      );
    
    
    $config_values["GLOBAL"][] = array(
                                            'type'=>'color',
                                            'key'=>'AM_FONT_HIGHLIGHT_MENU',
                                            'title'=>
                                                    array('german'=>'Schriftfarbe in der horizontalen Navigation', 'english'=>'')
                                      );
    
    $config_values["GLOBAL"][] = array(
                                            'type'=>'color',
                                            'key'=>'AM_FONT_HIGHLIGHT_MENU_HOVER',
                                            'title'=>
                                                    array('german'=>'Hover-Schriftfarbe in der horizontalen Navigation', 'english'=>'')
                                      );
    
    $config_values["GLOBAL"][] = array(
                                            'type'=>'color',
                                            'key'=>'AM_BACKGROUND_HIGHLIGHT_MENU_HOVER',
                                            'title'=>
                                                    array('german'=>'Hover-Hintergrundfarbe der horizontalen Navigation', 'english'=>'')
                                      );
                                      
                                      
    
    $config_values["GLOBAL"][] = array(
                                            'type'=>'color',
                                            'key'=>'AM_BACKGROUND_HIGHLIGHT_BOXHEADER',
                                            'title'=>
                                                    array('german'=>'Hintergrundfarbe der Boxheader', 'english'=>'')
                                      );

    $config_values["GLOBAL"][] = array(
                                            'type'=>'color',
                                            'key'=>'AM_FONT_HIGHLIGHT_BOX_HEADER',
                                            'title'=>
                                                    array('german'=>'Schriftfarbe im Boxheader', 'english'=>'')
                                      );
                                      
    
    $config_values["GLOBAL"][] = array(
                                            'type'=>'color',
                                            'key'=>'AM_BACKGROUND_HIGHLIGHT_SHOPPINGCART_BOX',
                                            'title'=>
                                                    array('german'=>'Hintergrundfarbe der Warenkorbbox', 'english'=>'')
                                      );
    $config_values["GLOBAL"][] = array(
                                            'type'=>'color',
                                            'key'=>'AM_FONT_HIGHLIGHT_SHOPPINGCART',
                                            'title'=>
                                                    array('german'=>'Schriftarbe in der Warenkorbbox', 'english'=>'')
                                      );
    $config_values["GLOBAL"][] = array(
                                            'type'=>'color',
                                            'key'=>'AM_BACKGROUND_HIGHLIGHT_SHOPPINGCART_BUTTON',
                                            'title'=>
                                                    array('german'=>'Hintergrundfarbe des Warenkorbbuttons', 'english'=>'')
                                      );

    $config_values["GLOBAL"][] = array(
                                            'type'=>'color',
                                            'key'=>'AM_FONT_HIGHLIGHT_BOX_HEADER_HOVER',
                                            'title'=>
                                                    array('german'=>'Hover-Schriftfarbe  der Boxheader bei Verweisen', 'english'=>'')
                                      );
    $config_values["GLOBAL"][] = array(
                                            'type'=>'color',
                                            'key'=>'AM_BACKGROUND_HIGHLIGHT_FOOTER',
                                            'title'=>
                                                    array('german'=>'Hintergrundfarbe des Footers', 'english'=>'')
                                      );
    
    $config_values["GLOBAL"][] = array(
                                            'type'=>'color',
                                            'key'=>'AM_FONT_HIGHLIGHT_FOOTER',
                                            'title'=>
                                                    array('german'=>'Schriftarbe im Footer', 'english'=>'')
                                      );

    $config_values["GLOBAL"][] = array(
                                            'type'=>'color',
                                            'key'=>'AM_FONT_HIGHLIGHT_FOOTER_LINK',
                                            'title'=>
                                                    array('german'=>'Farbe der Trennlinien im Footer', 'english'=>'')
                                      );
    
    $config_values["GLOBAL"][] = array(
                                            'type'=>'color',
                                            'key'=>'AM_FONT_HIGHLIGHT_FOOTER_BOX_HEADER',
                                            'title'=>
                                                    array('german'=>'Schriftfarbe in den Boxheader des Footers', 'english'=>'')
                                      );
    
    $config_values["GLOBAL"][] = array(
                                            'type'=>'color',
                                            'key'=>'AM_BACKGROUND_HIGHLIGHT_FOOTER_BOX_HEADER',
                                            'title'=>
                                                    array('german'=>'Farbe der Boxheader des Footers', 'english'=>'')
                                      );

    
    $config_values["GLOBAL"][] = array(
                                            'type'=>'color',
                                            'key'=>'AM_FONT_HIGHLIGHT_TOP_BAR_DESKTOP',
                                            'title'=>
                                                    array('german'=>'Farbe der Trennlinien der Top Bar der mobilen Ansicht', 'english'=>'')
                                      );
    
    $config_values["GLOBAL"][] = array(
                                            'type'=>'color',
                                            'key'=>'AM_BACKGROUND_HIGHLIGHT_TOP_BAR_DESKTOP',
                                            'title'=>
                                                    array('german'=>'Hintergrundfarbe der Top Bar der mobilen Ansicht', 'english'=>'')
                                      );
    

*/
    
    

}


/** DEBUG * *
echo '<strong>' . __FILE__ . '::' . __LINE__ . '</strong><br />';
echo '<pre>$config_values: ' . print_r($config_values, true) . '</pre>';
die();
/** * */ 


// INSTALL METHOD FROM configuration_functions.inc.php
$installer_method = 'ResonsiveTemplateInstaller::install'; 

?>
