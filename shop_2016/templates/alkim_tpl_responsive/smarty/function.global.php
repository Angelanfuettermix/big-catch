<?php


function smarty_function_global($params, &$smarty) {
  global ${$params['get']};
  $value = ${$params['get']};
  if (isset($params['assign'])) {
    return $smarty->assign($params['assign'], $value);
  }
  return $value;
}

?>