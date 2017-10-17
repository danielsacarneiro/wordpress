<?php
include_once ("config.obj.php");
/*
 * set_error_handler(function($errno, $errstr, $errfile, $errline, array $errcontext) {
 * // error was suppressed with the @-operator
 * if (0 === error_reporting()) {
 * return false;
 * }
 *
 * if (2 === error_reporting()) {
 * throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
 * }
 *
 *
 * //throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
 * });
 */

/*
 * spl_autoload_register(function ($class_name) {
 * $caminhoClasse = caminho_vos;
 * $pos = stripos($class_name, "filtro");
 * if($pos !== false && $pos == 0){
 * //eh classe filtro
 * $caminhoClasse = caminho_filtros;
 * }else{
 * //poderia tambem usar o stripos (mas quis demonstrar como cria uma nova funcao parametrizavel)
 * $needle = "Excecao";
 * $pos = getMultiPos($class_name, array($needle), false);
 * $pos = $pos[$needle];
 * if($pos !== false && $pos == 0){
 * //eh classe EXCECAO
 * $caminhoClasse = caminho_excecoes;
 * }
 * }
 *
 * $isClasseFramework = isClasseFrameWork($class_name, "vo") || isClasseFrameWork($class_name, "filtro") || isClasseFrameWork($class_name, "excecao") || isClasseFrameWork($class_name, "db");
 *
 * //ECHO $class_name;
 * if($isClasseFramework){
 * //echo "ACHOU";
 * include_once $caminhoClasse.$class_name . '.php';
 * }else{
 * echo "NAO ACHOU";
 * }
 *
 * });
 */

// CD_TIPO_PAGINA eh UMA CONSTANTE
$isPaginaFuncaoGeral = CD_TIPO_PAGINA == configuracao_geral::$CD_TIPO_PAGINA_FUNCAO_GERAL;
$isPaginaMenuAplicacao = CD_TIPO_PAGINA == configuracao_geral::$CD_TIPO_PAGINA_MENU_APLICACAO;
$isPaginaMenuGeral = isPastaRaiz ();
$isPaginaAplicacao = CD_TIPO_PAGINA == configuracao_geral::$CD_TIPO_PAGINA_MENU_APLICACAO || CD_TIPO_PAGINA == configuracao_geral::$CD_TIPO_PAGINA_FUNCAO_APLICACAO;
$isPastaMenu = $isPaginaMenuAplicacao || isPastaRaiz ();
$pastaRaiz = "";
$caminhoJS = "lib/js/";
$caminhoCSS = "lib/css/";
$caminhoIMG = "imagens/";
$caminhoMenu = "";

/*
 * if(!$isPastaMenu){
 * $pastaRaiz = "../../";
 * $caminhoJS = $pastaRaiz . $caminhoJS;
 * $caminhoCSS = $pastaRaiz . $caminhoCSS;
 * $caminhoIMG = $pastaRaiz . $caminhoIMG;
 * $caminhoMenu = $pastaRaiz;
 * }
 */

if ($isPaginaAplicacao || $isPaginaFuncaoGeral) {
	$GLOBAL_PASTA_POR_SISTEMA = "../";
}
if ($isPaginaFuncaoGeral) {
	$pastaRaiz = "../";
} elseif (! $isPastaMenu) {
	$pastaRaiz = "../../";
}

if ($pastaRaiz != "") {
	$caminhoJS = $pastaRaiz . $caminhoJS;
	$caminhoCSS = $pastaRaiz . $caminhoCSS;
	$caminhoIMG = $pastaRaiz . $caminhoIMG;
	$caminhoMenu = $pastaRaiz;
}

define ( 'caminho_menu', $caminhoMenu );
define ( 'caminho_css', $GLOBAL_PASTA_POR_SISTEMA . $caminhoCSS );
define ( 'caminho_js', $GLOBAL_PASTA_POR_SISTEMA . $caminhoJS );
define ( 'caminho_imagens', $GLOBAL_PASTA_POR_SISTEMA . $caminhoIMG );

// define uma variavem javascript para que as imagens em js sejam recuperadas corretamente
$varGlobalJS = "<script type='text/javascript'>\n" . "var _pastaImagensGlobal = '" . caminho_imagens . "';\n" . "</script>\n";

echo $varGlobalJS;

// variaveis HTML
define ( 'TAMANHO_CODIGOS', constantes::$TAMANHO_CODIGOS );
define ( 'TAMANHO_CODIGOS_SAFI', constantes::$TAMANHO_CODIGOS_SAFI );
define ( 'CAMPO_SEPARADOR', constantes::$CD_CAMPO_SEPARADOR );
define ( 'CAMPO_SUBSTITUICAO', constantes::$CD_CAMPO_SUBSTITUICAO );

// echo caminho_imagens;
// echo caminho_menu;

//a bibliotecafuncao esta aqui porque usa os caminhos definidos em config_lib
include_once ("util/bibliotecaHTML.php");

?>
