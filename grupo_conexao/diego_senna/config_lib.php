<?php
//configuracao
$site_cliente = "http://www.diego_Senna.com.br";
//$pasta_aplicacao = "/grupo_conexao/diego_senna";
//$pasta_raiz_wordpress = "/desenv/wordpress";

$GLOBAL_PASTA_POR_SISTEMA = "../";
$GLOBAL_PASTA_APLICACAO = "diego_senna";
$GLOBAL_TITULO_SITE_SISTEMA = "DIEGO SENNA COACHING";
define('GLOBAL_NOME_SISTEMA' ,"e@Metas");

$var_include_config_geral = "../config_lib.php";
$pasta_imagens_por_sistema = "imagens";
if(!$GLOBAL_IS_PASTA_MENU){
	$var_include_config_geral = "../../$var_include_config_geral";
	$pasta_imagens_por_sistema = "../../imagens";
}

define('GLOBAL_PASTA_IMAGEM_APLICACAO' , $pasta_imagens_por_sistema);
define('GLOBAL_IMAGEM_LOGO' , "logo.jpg");


include_once($var_include_config_geral);


//echo caminho_menu;

?>
