<?php
function smarty_function_mailstyle($params, &$smarty){
  
  if (!is_array($params) || 0 == count($params)) {
    return false;
  }
  
  foreach ($params as $a => $c) {
    $c = 'RT_' . strtoupper($c);
    if (defined($c) && '' != trim(constant($c))) {
      $style[] = str_replace('_', '-', $a) . ':' . constant($c) . ';';
    }
  }
  if (!count($style)) {
    return false;
  }
  
  return implode('', $style);
}
?>