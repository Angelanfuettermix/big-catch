<?php
header('Content-Type:text/css');

$useFromCache = false;
$cacheFile = 'alkimmedia_modules/general/cache/cached.js.php';
$readFromCache = (file_exists($cacheFile) && filemtime($cacheFile) > (time()-7200));

if($readFromCache && !isset($_GET["noCache"]) && $_GET["noCache"] != 1){
        include($cacheFile);
        die;
}

include('includes/application_top.php');
ob_start();

$files = glob(DIR_FS_CATALOG.'alkimmedia_modules/*/js/autoinclude.*.js');
if(is_array($files)){
    foreach($files AS $file){
        include($file);
    }
}

$content = ob_get_contents();
ob_end_clean();
echo $content;
if(isset($cacheFile)){
    file_put_contents($cacheFile, $content);
}
$q = "DELETE FROM whos_online WHERE last_page_url = '".xtc_db_prepare_input($_SERVER['REQUEST_URI'])."'";
xtc_db_query($q);


