<?php
include_once ("configuracao_geral.php");
include_once ("util/bibliotecaFuncoesPrincipal.php");
include_once ("util/constantes.class.php");
include_once ("excecoes/excecaoClassNaoEncontrada.php");

// mysqli_report(MYSQLI_REPORT_ALL);
mysqli_report ( MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT );
error_reporting ( E_ALL ^ E_NOTICE );

date_default_timezone_set ( 'America/Recife' );
// date_default_timezone_set('America/Los_Angeles');
setlocale ( LC_ALL, 'portuguese' );
set_exception_handler ( "pegaExcecaoSemTratamento" );

// fun��o definida pelo usu�rio para pegar exce��es n�o tratadas
function pegaExcecaoSemTratamento($exception) {
	// echo 'Exce��o pega sem tratamento:</br>', $exception->getMessage(), '</br></br></br>';
	// throw new Exception($exception->getMessage());
	throw $exception;
}

// configuracao
if ($site_cliente == null) {
	$site_cliente = "http://www.grupoeducacionalconexao.com.br";
}
if ($pasta_aplicacao == null) {
	$pasta_aplicacao = "/grupo_conexao";
}

if ($pasta_raiz_wordpress == null) {
	$pasta_raiz_wordpress = "/desenv/wordpress";
}

define ( 'nome_hospedagem', "econti.consulting" );
define ( 'site_hospedagem', "http://econti-consulting.umbler.net" );
define ( 'site_cliente', $site_cliente );
define ( 'pasta_aplicacao', $pasta_aplicacao );
define ( 'pasta_raiz_wordpress', $pasta_raiz_wordpress );
define ( 'pasta_raiz_sistema', pasta_raiz_wordpress . pasta_aplicacao );

header ( 'Content-type: text/html; charset=ISO-8859-1' );

$base = getPastaRoot ();
define ( 'caminho', $base . "/" );
define ( 'caminho_lib', "$base/lib/" );
define ( 'caminho_util', "$base/util/" );
define ( 'caminho_vos', "$base/vos/" );
define ( 'caminho_filtros', "$base/filtros/" );
define ( 'caminho_excecoes', "$base/excecoes/" );
define ( 'caminho_funcoesHTML', "funcoes/" );
define ( 'caminho_funcoesHTML_geral', "../funcoes/" );
define ( 'caminho_funcoes', "$base/funcoes/" );
define ( 'site_wordpress', pasta_raiz_wordpress . "/wp-admin/" );

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
 * a funcao abaixo serve para incluir a classe usada na confirmacao
 * o session precisa identificar qual classe ele serializa
 * dai o include
 */
function getIncludeClasseNaoEncontrada($class_name) {
	try {
		getIncludeClasseNaoEncontradaAplicacao ( $class_name );
	}catch ( excecaoClassNaoEncontrada $ex ) {
		$nomeSistema = getIdSistemaSeExistir();
		if ($nomeSistema != null) {
			// tenta encontrar no caminho da aplicacao especifica
			$caminhoAlternativo = $ex->caminhoAlternativo;
			$caminhoAlternativo = getCaminhoIncludeAplicacao ( $caminhoAlternativo, $nomeSistema );
			// echo $caminhoAlternativo . "<br>";
			getIncludeClasseNaoEncontradaAplicacao ( $class_name, $caminhoAlternativo );
		}
	}
}
function getIncludeClasseNaoEncontradaAplicacao($class_name, $caminhoAlternativo = null) {
	$caminhoClasse = caminho_vos;
	$pos = stripos ( $class_name, "filtro" );
	if ($pos !== false && $pos == 0) {
		// eh classe filtro
		$caminhoClasse = caminho_filtros;
	} else {
		// poderia tambem usar o stripos (mas quis demonstrar como cria uma nova funcao parametrizavel)
		$needle = "Excecao";
		$pos = getMultiPos ( $class_name, array (
				$needle 
		), false );
		$pos = $pos [$needle];
		if ($pos !== false && $pos == 0) {
			// eh classe EXCECAO
			$caminhoClasse = caminho_excecoes;
		}
	}
	
	$isClasseFramework = isClasseFrameWork ( $class_name, "vo" ) || isClasseFrameWork ( $class_name, "filtro" ) || isClasseFrameWork ( $class_name, "excecao" ) || isClasseFrameWork ( $class_name, "db" );
	
	// ECHO $class_name;
	if ($isClasseFramework) {
		// echo "ACHOU";
		include_once $caminhoClasse . $class_name . '.php';
	} else if ($caminhoAlternativo == null) {
		// se caminhoalternativo == null eh porque ainda nao foi tentado
		throw new excecaoClassNaoEncontrada ( $class_name, $caminhoClasse );
	}
}
spl_autoload_register ( getIncludeClasseNaoEncontrada );

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
