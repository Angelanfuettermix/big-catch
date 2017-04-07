<?php

/**
 * @copyright (c) 2015, Alkim Media
 * @author TP <tp@alkim.de>
 */
function set_eTagHeaders($file, $timestamp) {
	$gmt_mTime = gmdate('r', $timestamp);

	header('Cache-Control: public,max-age=604800');
	header('ETag: "' . md5($timestamp . $file) . '"');
	header('Last-Modified: ' . $gmt_mTime);

	if (isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) || isset($_SERVER['HTTP_IF_NONE_MATCH'])) {
		if ($_SERVER['HTTP_IF_MODIFIED_SINCE'] == $gmt_mtime || str_replace('"', '', stripslashes($_SERVER['HTTP_IF_NONE_MATCH'])) == md5($timestamp . $file)) {
			header('HTTP/1.1 304 Not Modified');
			exit();
		}
	}
}

set_eTagHeaders(__FILE__, filemtime(__FILE__));

header('Content-type: text/css');

require_once dirname(__FILE__) . '/../configuration_functions.inc.php';
$file = ResonsiveTemplateInstaller::getCacheFile();

function isPropertyValue($value) {
  return strlen($value);
}


// --- bof -- changes -- h.koch for alkim-media -- 03.2016 -- 
if (file_exists(dirname(__FILE__) . '/../../../includes/local/configure.php')) {
  require_once (dirname(__FILE__) . '/../../../includes/local/configure.php');
} else {
  require_once dirname(__FILE__) . '/../../../includes/configure.php';
}

if( defined('DEBUG_MAIL_SEND') ) {  // im lokalmodus kein cache
  $file='';
}  

// --- eof -- changes -- h.koch for alkim-media -- 03.2016 -- 


if (file_exists($file)) {
  echo file_get_contents($file);
} else {

  // --- bof -- changes -- h.koch for alkim-media -- 03.2016 -- 
  //require_once dirname(__FILE__) . '/../../../includes/configure.php';
  // --- eof -- changes -- h.koch for alkim-media -- 03.2016 -- 
  require_once dirname(__FILE__) . '/../../../inc/xtc_db_connect.inc.php';
  require_once dirname(__FILE__) . '/../../../inc/xtc_db_error.inc.php';
  require_once dirname(__FILE__) . '/../../../inc/xtc_db_query.inc.php';
  require_once dirname(__FILE__) . '/../../../inc/xtc_db_fetch_array.inc.php';

  xtc_db_connect();
  $stmt = 'SELECT * FROM alkim_module_configuration';
  $query = xtc_db_query($stmt);
  while ($row = xtc_db_fetch_array($query)) {
    define($row['amc_key'], $row['amc_value']);
  }

  $xml = ResonsiveTemplateInstaller::getConfigXML();
  ob_start();
  foreach ($xml->tab as $t) {
    foreach ($t->rule as $r) {
      echo str_replace(', ', ',', (string) html_entity_decode($r['selector'])) . '{';
      foreach ($r->property as $p) {
        foreach (explode('|', (string) $p['name']) as $name) {
          $path = array(
            (string) isset($r['name']) ? $r['name'] : $r['selector'],
            (string) $name,
          );
          $items = array();
          if (count($p->property)) {
            foreach ($p->property as $i) {
              $const = ResonsiveTemplateInstaller::KEY_PREFIX . ResonsiveTemplateInstaller::makeKey(array_merge($path, array((string) $i['name'])));
              $items[] = ResonsiveTemplateInstaller::getConstantValue($const, (string) $i['type']);
            }
          } else {
            $const = ResonsiveTemplateInstaller::KEY_PREFIX . ResonsiveTemplateInstaller::makeKey($path);
            $items[] = ResonsiveTemplateInstaller::getConstantValue($const, (string) $p['type']);
          }
          $items = array_filter($items, 'isPropertyValue');
          $v = trim(implode(' ', $items));
          if ('' != trim($v)) {
            echo (string) $name . ':' . $v . ';';
          }
        }
      }
      echo '}' . PHP_EOL;
    } // end of each rule
  } // end of each tab
  $c = 'RT_CUSTOM_CSS';
  if (defined($c) && '' != trim(constant($c))) {
    echo preg_replace('#[\r\n]#', '', constant($c));
  }
  $css = ob_get_contents();
  file_put_contents($file, $css);
}

?>
