<?php

/**
 * @copyright (c) 2015, Alkim Media
 * @author TP <tp@alkim.de>
 */

class ResonsiveTemplateInstaller {

  const KEY_PREFIX = 'RT_';
  
  /**
   * returns Configuration XML
   * 
   * @return DOMNode
   */
  public static function getConfigXML() {
    libxml_use_internal_errors(true);
    $xml = simplexml_load_file(dirname(__FILE__) . '/configuration.xml');
    if (!$xml) {
      echo 'unable to load XML: ' . dirname(__FILE__) . '/configuration.xml<br />';
      echo '<pre>';
        foreach (libxml_get_errors() as $error) {
          echo "\t" . $error->message . "\n";
        }
      echo '</pre>';
      return false;
    }
    return $xml;
  }

  /**
   * removes cached Files
   */
  public static function clearCache() {
    $file = self::getCacheFile();
    if (file_exists($file)) {
      unlink($file);
    }
  }
  
  /**
   * returns the name and path from the cached css-File
   * 
   * @return string
   */
  public static function getCacheFile() {
    return dirname(__FILE__) . '/cache/cache.css';
  }
  
  /**
   * writes all default configuration constants into the database
   * remove old constants before and clear cache at the end
   */
  public static function install() {
    // Remove all config vars
    $stmt = '-- ' . __FILE__ . '::' . __LINE__ . ' 
            DELETE FROM alkim_module_configuration 
            WHERE amc_key LIKE "%' . self::KEY_PREFIX . '%"';
    xtc_db_query($stmt);
    
    // get config vars and default values from xml
    $xml = self::getConfigXML();
    $values = array();
    foreach ($xml->tab as $t) {
      foreach ($t->rule as $r) {
        $s = (string) isset($r['name']) ? $r['name'] : $r['selector'];
        foreach (self::getConfigDefaultValues($r->property, $s) as $v) {
          $values[] = '"' . $v['key'] . '", "' . $v['value'] . '", ' . $v['lang'];
        }
      }
      foreach (self::getConfigDefaultValues($t->content, (string) $t['title']) as $v) {
        $values[] = '"' . $v['key'] . '", "' . $v['value'] . '", ' . $v['lang'];
      }
    }
    $values[] = '"' . self::KEY_PREFIX . 'CUSTOM_CSS' . '", "", 0';
        
    // set default values to database
    $stmt = '-- ' . __FILE__ . '::' . __LINE__ . ' 
            INSERT INTO alkim_module_configuration 
            (amc_key, amc_value, amc_language) VALUES (' . implode('), (', $values) . ')';
    xtc_db_query($stmt);
      
    // check and set content flags
    $flagsToCheck = array(
      'contentmenu_1',
      'contentmenu_2',
      'contentmenu_3',
      'contentmenu_4',
    );
    $stmt = '-- ' . __FILE__ . '::' . __LINE__ . ' 
            SELECT file_flag_name 
            FROM ' . TABLE_CM_FILE_FLAGS . ' 
            WHERE file_flag_name IN ("' . implode('", "', $flagsToCheck) . '")';
    $query = xtc_db_query($stmt);
    $existingFlags = array();
    if (0 < xtc_db_num_rows($query)) {
      while ($row = xtc_db_fetch_array($query)) {
        $existingFlags[] = $row['file_flag_name'];
      }
    }
    $createFlags = array_diff($flagsToCheck, $existingFlags);
    if (count($createFlags)) {
      // get the next id
      $stmt = '-- ' . __FILE__ . '::' . __LINE__ . ' 
              SELECT MAX(file_flag) AS last_key 
              FROM ' . TABLE_CM_FILE_FLAGS;
      $query = xtc_db_query($stmt);
      $row = xtc_db_fetch_array($query);
      $newKey = (is_array($row) && isset($row['last_key']) ? (int) $row['last_key'] : 0) + 1;
      $values = array();
      foreach ($createFlags as $f) {
        $values[] = '(' . $newKey++ . ', "' . $f . '")';
      }
      $stmt = '-- ' . __FILE__ . '::' . __LINE__ . ' 
              INSERT INTO ' . TABLE_CM_FILE_FLAGS . ' (file_flag, file_flag_name) 
              VALUES ' . implode(', ', $values);
      xtc_db_query($stmt);
    }
    
    self::clearCache();
  }


  public static function export() {

    // get config vars and default values from xml
    $xml = self::getConfigXML();
    $test = '';
    $values = array();
    foreach ($xml->tab as $t) {
      foreach ($t->rule as $r) {
        $elementPath = $s = (string) isset($r['name']) ? $r['name'] : $r['selector'];
        if (!is_array($elementPath)) {
          $elementPath = explode('_', $elementPath);
        }

        foreach ($r->property as $p) {
            if((int) $p['lang'] == 0){
                  $names = explode('|', (string) $p['name']);
                  $defaults = explode('|', (string) $p['default']);
                  $newDefault = array();
                  $firstName = true;

                  foreach ($names as $n => $name) {

                    $path = array_merge($elementPath, array($name));

                    if (count($p->property)) {
                      foreach ($p->property as $_p) {
                        if((int) $_p['lang'] == 0){
                              $_names = explode('|', (string) $_p['name']);
                              $_defaults = explode('|', (string) $_p['default']);
                              $_newDefault = array();
                              $keys = array();
                              
                              foreach ($_names as $_n => $_name) {
                                $_path = array_merge($elementPath, array($name), array($_name));
                                
                                if (count($_p->property)) {
                                  //$values = array_merge($values, self::getConfigDefaultValues($p->property, $path, $n));
                                } else {
                                  $_key = self::KEY_PREFIX . self::makeKey($_path);
                                  
                                  if(defined($_key)){
                                    $_newDefault[] = constant($_key);
                                    $keys[] = $_key;
                                  }
                                }
                              }
                              
                              if($_newDefaultStr = implode('|', $_newDefault)){
                       
                                if($firstName){
                                    $_p['default'] = $_newDefaultStr;
                                    //$_p['keys'] = implode($keys);
                                }else{
                          
                                    $_p['default'] = (string)$_p['default'].'|'.$_newDefaultStr;
                                    //$_p['keys'] = (string)$_p['keys'].'|'.implode($keys);
                                }
                                
                              }
                            }
                        }
                    } else {
                      $key = self::KEY_PREFIX . self::makeKey($path);
                      
                      if(defined($key)){
                        $newDefault[] = constant($key);
                      }
                    }
                    
               
                  $firstName = false;
                  }
                  if($newDefaultStr = implode('|', $newDefault)){
                    $p['default'] = $newDefaultStr;
                  }
                  
                  
                }
            }
      }
    }

    return $xml->asXML();
    
    
  }
  
  
  /**
   * returns the default values for one DOM-Element from the Configuration XML
   * 
   * @param   DOMNode $properties
   * @param   string $elementPath
   * @return  array
   */
  public static function getConfigDefaultValues($properties, $elementPath = array(), $number = -1) {
    if (!is_array($elementPath)) {
      $elementPath = explode('_', $elementPath);
    }
    $lang = xtc_get_languages();
    $values = array();
    foreach ($properties as $p) {
      $names = explode('|', (string) $p['name']);
      $defaults = explode('|', (string) $p['default']);
      foreach ($names as $n => $name) {
        $path = array_merge($elementPath, array($name));
        if (count($p->property)) {
          $values = array_merge($values, self::getConfigDefaultValues($p->property, $path, $n));
        } else {
          $key = self::KEY_PREFIX . self::makeKey($path);
          $pos = 1 < count($defaults) && -1 < $number ? $number : $n;
          $a = array (
            'key'   => $key,
            'value' => isset($defaults[$pos]) && '' != $defaults[$pos] ? $defaults[$pos] : $defaults[0],
          ); 
          if (isset($p['lang']) && 1 == (int) $p['lang']) {
            foreach ($lang as $l) {
              $a['lang'] = $l['id'];
              $values[] = $a;
            }            
          } else {
            $a['lang'] = 0;
            $values[] = $a;
          }
        }
      }
    }
    return $values;
  }
  
  /**
   * 
   * 
   * @param   DOMNode $rules
   * @param   string $elementPath
   * @return  array
   */
  public static function getRuleConfig($rules, $elementPath = array()) {
    $r = array();
    foreach ($rules as $rule) {
      $rs = self::getPropertyConfig($rule->property, (string) isset($rule['name']) ? $rule['name'] : $rule['selector']);
      if (count($rs)) {
        $key = isset($rule['name']) ? 'SUBLINE_' . self::makeKey($rule['name']) : $rule['selector'] . ' {}';
        $r[] = array(
          'key'   => $key,
          'type'  => 'heading',
        );
        $r = array_merge($r, $rs);
      }
    }
    return $r;
  }
  
  /**
   * 
   * 
   * @param   DOMNode $properties
   * @param   string $elementPath
   * @return  array
   */
  public static function getPropertyConfig($properties, $elementPath = array()) {
    if (0 == count($properties)) {
      return array();
    }
    if (!is_array($elementPath)) {
      $elementPath = array($elementPath);
    }
    $r = array();
#    $r = array(
#      array(
#        'key' => self::KEY_PREFIX . self::makeKey(array_merge($elementPath, array('properties'))),
#        'type' => 'HTML',
#      ),
#    );
    foreach ($properties as $p) {
      $names = explode('|', (string) $p['name']);
      foreach ($names as $name) {
        $path = array_merge($elementPath, array($name));
        if (count($p->property)) {
          $r[] = array(
            'key' => self::KEY_PREFIX . self::makeKey($path),
            'type' => 'HTML',
            'simple'=>($p["simple"]==1)
          );
          $r = array_merge($r, self::getPropertyConfig($p->property, $path));
        } else {
          
          $c = array(
            'key' => self::KEY_PREFIX . self::makeKey($path),
            'type' => (string) $p['type'],
            'simple'=>($p["simple"]==1)
          );
          switch ((string) $p['type']) {
            case 'select':
              $c['options'] = array();
              foreach ($p->option as $o) {
                $str = (string) $o['name'];
                if ('' != $o->__toString()) {
                  $str = $o->__toString();
                } else {
                  $const = self::KEY_PREFIX . self::makeKey(array_merge($path, array('option', (string) $o['name'])));
                  if (defined($const)) {
                    $str = constant($const);
                  }
                }
                $c['options'][(string) $o['name']] = array($_SESSION['language'] => $str);
              }
              break;
            case 'file':
              $c['directory'] = str_replace(DIR_FS_CATALOG, '', dirname(__FILE__)) . '/uploads/';
              break;
          }
          $r[] = $c;
        }
      } // end of names
    } // end of properties
    return $r;
  }
  
  public static function getContentConfig($content, $elementPath) {
    if (0 == count($content)) {
      return array();
    }
    if (!is_array($elementPath)) {
      $elementPath = array($elementPath);
    }
    $r = array(
      array(
        'key' => self::KEY_PREFIX . self::makeKey(array_merge($elementPath, array('content'))),
        'type' => 'heading',
      ),
    );
    foreach ($content as $c) {
      $names = explode('|', (string) $c['name']);
      foreach ($names as $name) {
        $path = array_merge($elementPath, array($name));
        $a = array(
          'key' => self::KEY_PREFIX . self::makeKey($path),
          'type' => (string) $c['type'],
          'simple' => ($c['simple']==1)
        );
        switch ((string) $c['type']) {
          case 'select':
            $a['options'] = array();
            foreach ($c->option as $o) {
              $str = (string) $o['name'];
              if ('' != $o->__toString()) {
                $str = $o->__toString();
              } else {
                $str = self::detectLanguageValue(self::makeKey(array_merge($path, array('option', (string) $o['name']))));
                //$const = self::KEY_PREFIX . self::makeKey(array_merge($path, array('option', (string) $o['name'])));
                //if (defined($const)) {
                //  $str = constant($const);
                //}
              }
              $a['options'][(string) $o['name']] = array($_SESSION['language'] => $str);
            }
            break;
          case 'file':
            $a['directory'] = str_replace(DIR_FS_CATALOG, '', dirname(__FILE__)) . '/uploads/';
            break;
        }
        foreach (array(
          'wysiwyg',
          'lang',
        ) as $k) {
          if (isset($c[$k])) {
            $a[$k] = (string) $c[$k];
          }
        }
        $r[] = $a;
      }
    }
    
    /** DEBUG * *
    echo '<strong>' . __FILE__ . '::' . __LINE__ . '</strong><br />';
    echo '<pre>$content: ' . print_r($content, true) . '</pre>';
    echo '<pre>$c: ' . print_r($c, true) . '</pre>';
    echo '<pre>$r: ' . print_r($r, true) . '</pre>';
    die();
    /**     * */ 
        
    return $r;
  }
  
  /**
   * returns name for the configutation-key
   * 
   * @param   string|array $str
   * @return  string
   */
  public static function makeKey($str) {
    if (is_array($str)) {
      $str = implode('_', $str);
    }
    $str = preg_replace('#[-\.=\[\]:,]#', '', $str);
    $str = preg_replace('#[\s,]+#', '_', $str);
    $str = strtoupper($str);
    return $str;
  } 
  
  public static function getConstantValue($const, $type = 'text') {
    $v = constant($const);
    $r = '';
    switch ($type) {
      case 'file':
        if ('' != $v) {
          $r = 'url("/' . $v . '")';
        }
        break;
      default:
        $r = $v;
        break;
    }
    return $r;
  }
  
  public static function detectLanguageValue($c, $postfix = '') {
    $p = explode('_', $c);
    $v = '';
    while (count($p) && '' == trim($v)) {
      $c = self::KEY_PREFIX . implode('_', array_filter(array_merge($p, array($postfix))));
      if (defined($c)) {
        $v = constant($c);
      }
      $p = array_slice($p, 1);
    }
    return $v;
  }

  
}
?>
