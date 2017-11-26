<?php
$nmSistema = 'e-Senn@';
$pastaSistema = 'diego_senna';
/** Absolute path to the directory. */
$caminhoabs = str_replace($pastaSistema, "", dirname(__FILE__));
//echo $caminhoabs;
//include_once '../../../config_geral.php';
include_once $caminhoabs.'/config_geral.php';
setSistemaInterno($pastaSistema, $nmSistema);
//include_once '../../../config_lib.php';
include_once $caminhoabs.'/config_lib.php';
