<?php
$smarty = new Smarty; 
$path = dirname(dirname(__FILE__));
$smarty->template_dir = $path.'/templates/';
$smarty->config_dir = $path.'/lib/smarty/configs/';
$smarty->compile_dir = $path.'/lib/smarty/templates_c/';
$smarty->cache_dir = $path.'/lib/smarty/cache/';

if ($_SERVER["HTTP_HOST"]=="127.0.0.1")
    $smarty->force_compile = true;

?>
