<?php
    $files = glob(DIR_FS_CATALOG.'alkimmedia_modules/*/js/autoinclude.*.php');
    if(is_array($files)){
        foreach($files AS $file){
            include($file);
        }
    }
?>
<script src="alkim.js.php<?php if(AmHandler::$noCache) echo '?noCache=1'; ?>" type="text/javascript"></script>
