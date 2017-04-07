<?php
header("Content-Type: text/html; charset=iso-8859-15");
include ('includes/application_top.php');


$this_mix = load_mix($_GET["mix_id"]);
echo '
      <h3>'.INGREDIENTS.'</h3>
      <div>'.$this_mix->give_feature_list_plain().'</div>';
            


?>