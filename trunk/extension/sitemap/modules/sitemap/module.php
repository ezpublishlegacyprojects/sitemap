<?php
 
$Module = array( 'name' => 'Google Sitemap' ); 
$ViewList = array(); 
 
$ViewList['xml'] = array( 'script' => 'xml.php', 
                           'functions' => array( 'read' ), 
                           'params' => array('RootNode') );

$FunctionList = array(); 
$FunctionList['read'] = array(); 
 
?>
