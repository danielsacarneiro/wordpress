<?php

include_once("util/bibliotecaFuncoesPrincipal.php");
include_once ("util/constantes.class.php");

//mysqli_report(MYSQLI_REPORT_ALL);
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
date_default_timezone_set('America/Recife');
setlocale(LC_ALL, 'portuguese');
set_exception_handler("pegaExcecaoSemTratamento");

//função definida pelo usuário para pegar exceções não tratadas
function pegaExcecaoSemTratamento($exception){
	//echo 'Exceção pega sem tratamento:</br>', $exception->getMessage(), '</br></br></br>';
	//throw new Exception($exception->getMessage());
	throw $exception;
}

//configuracao
define('site_cliente', "http://www.grupoeducacionalconexao.com.br");
define('nome_hospedagem', "econti.consulting");
define('site_hospedagem', "http://econti-consulting.umbler.net");
define('pasta_aplicacao', "/diego_senna");
define('pasta_raiz_wordpress', "/desenv/wordpress");
define('pasta_raiz_sistema', pasta_raiz_wordpress . pasta_aplicacao);

header ('Content-type: text/html; charset=ISO-8859-1');

$base = getPastaRoot();
define('caminho', $base."/");
define('caminho_lib', "$base/lib/");
define('caminho_util', "$base/util/");
define('caminho_vos', "$base/vos/");
define('caminho_filtros', "$base/filtros/");
define('caminho_excecoes', "$base/excecoes/");
define('caminho_funcoesHTML', "funcoes/");
define('caminho_funcoes', "$base/funcoes/");
define('site_wordpress', pasta_raiz_wordpress . "/wp-admin/");

/*set_error_handler(function($errno, $errstr, $errfile, $errline, array $errcontext) {
	// error was suppressed with the @-operator
	if (0 === error_reporting()) {
		return false;
	}
	
	if (2 === error_reporting()) {
		throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
	}	


	//throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
});*/

/*a funcao abaixo serve para incluir a classe usada na confirmacao
 * o session precisa identificar qual classe ele serializa
 * dai o include
 */
spl_autoload_register(function ($class_name) {
	$caminhoClasse = caminho_vos;	
	$pos = stripos($class_name, "filtro");	
	if($pos !== false && $pos == 0){
		//eh classe filtro
		$caminhoClasse = caminho_filtros;
	}else{
		//poderia tambem usar o stripos (mas quis demonstrar como cria uma nova funcao parametrizavel)
		$needle = "Excecao";
		$pos = getMultiPos($class_name, array($needle), false);
		$pos = $pos[$needle];
		if($pos !== false && $pos == 0){			
			//eh classe EXCECAO
			$caminhoClasse = caminho_excecoes;
		}		
	}
		
	$isClasseFramework = isClasseFrameWork($class_name, "vo") || isClasseFrameWork($class_name, "filtro") || isClasseFrameWork($class_name, "excecao") || isClasseFrameWork($class_name, "db");
	
	//ECHO $class_name;
	if($isClasseFramework){
		//echo "ACHOU";
		include_once $caminhoClasse.$class_name . '.php';
	}/*else{
		echo "NAO ACHOU";
	}*/
	
});
	
$isPastaRaiz  = isPastaRaiz();
$pastaRaiz = "";
$caminhoJS = "lib/js/";
$caminhoCSS = "lib/css/";
$caminhoIMG = "imagens/";

$caminhoMenu = "";

if(!$isPastaRaiz){
	$pastaRaiz = "../../";
    $caminhoJS = $pastaRaiz . $caminhoJS;
    $caminhoCSS = $pastaRaiz . $caminhoCSS;
    $caminhoIMG = $pastaRaiz . $caminhoIMG;    
    $caminhoMenu = $pastaRaiz;
}

define('caminho_menu', $caminhoMenu);
define('caminho_css', $caminhoCSS);
define('caminho_js', $caminhoJS);
define('caminho_imagens', $caminhoIMG);

//define uma variavem javascript para que as imagens em js sejam recuperadas corretamente
$varGlobalJS = 
"<script type='text/javascript'>\n"
	. "var _pastaImagensGlobal = '" . caminho_imagens ."';\n"
	. "</script>\n";

echo $varGlobalJS;

//variaveis HTML
define('TAMANHO_CODIGOS', constantes::$TAMANHO_CODIGOS);
define('TAMANHO_CODIGOS_SAFI', constantes::$TAMANHO_CODIGOS_SAFI);
define('CAMPO_SEPARADOR', constantes::$CD_CAMPO_SEPARADOR);
define('CAMPO_SUBSTITUICAO', constantes::$CD_CAMPO_SUBSTITUICAO);

?>
