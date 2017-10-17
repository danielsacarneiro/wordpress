<?php
include_once ("util/constantes.class.php");
include_once ("util/sessao.php");
include_once ("excecoes/excecaoClassNaoEncontrada.php");
include_once ("util/bibliotecaFuncoesPrincipal.php");
include_once ("util/constantes.class.php");

// mysqli_report(MYSQLI_REPORT_ALL);
mysqli_report ( MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT );
error_reporting ( E_ALL ^ E_NOTICE );

date_default_timezone_set ( 'America/Recife' );
// date_default_timezone_set('America/Los_Angeles');
setlocale ( LC_ALL, 'portuguese' );
set_exception_handler ( "pegaExcecaoSemTratamento" );

// função definida pelo usuário para pegar exceções não tratadas
function pegaExcecaoSemTratamento($exception) {
	// echo 'Exceção pega sem tratamento:</br>', $exception->getMessage(), '</br></br></br>';
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
$caminho = $base . "/";

define ( 'caminho', $caminho );
define ( 'caminho_lib', "$base/lib/" );
define ( 'caminho_util', "$base/util/" );
define ( 'caminho_vos', "$base/vos/" );
define ( 'caminho_filtros', "$base/filtros/" );
define ( 'caminho_excecoes', "$base/excecoes/" );
define ( 'caminho_funcoesHTML', "funcoes/" );
define ( 'caminho_funcoesHTML_geral', "../funcoes/" );
define ( 'caminho_funcoes', "$base/funcoes/" );
define ( 'site_wordpress', pasta_raiz_wordpress . "/wp-admin/" );

spl_autoload_register ( getIncludeClasseNaoEncontrada );
function getIncludeClasseNaoEncontrada($class_name) {
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
		// echo "eh fwk";
		$classeAIncluir = $caminhoClasse . $class_name . '.php';
		$classeAIncluir = str_replace ( "//", "/", $classeAIncluir );
		// echo $classeAIncluir . "<br>";
		
		if (! file_exists ( $classeAIncluir )) {
			// echo "NAO encontrou arquivo a incluir<br>";
			if (defined ( 'GLOBAL_ID_SISTEMA' )) {
				$id_sistema = GLOBAL_ID_SISTEMA;
				$caminhoAlternativo = substr ( $caminhoClasse, 0, count ( $caminhoClasse ) - 2 );
				$caminhoAlternativo = getCaminhoIncludeAplicacao ( $caminhoAlternativo, $id_sistema ) . "/";
				$caminhoAlternativo .= $class_name . '.php';
				// echo $caminhoAlternativo . "<br>";
				
				include_once ($caminhoAlternativo);
			}
		} else {
			// echo "tem arquivo <br>";
			include_once ($classeAIncluir);
		}
	}
}

$id_sistema = getIdSistemaGETouPOST();
if ($id_sistema != null) {
	//echo "TEM ARQUIVO PROPRIEDADE";
	$caminho .= "$id_sistema/";
	define ( 'GLOBAL_ID_SISTEMA', $id_sistema );
} /*else {
echo "NAO TEM ARQUIVO PROPRIEDADE";
}
echo " GLOBAL SISTEMA: " . GLOBAL_ID_SISTEMA;*/

// session_start();
function setTipoPagina($CD_TIPO_PAGINA) {
	define ( 'CD_TIPO_PAGINA', $CD_TIPO_PAGINA );
}
function getIdSistemaGETouPOST() {
	
	$retorno = getIDSistemaArquivoPropriedade ();	

	if ($retorno == null || $retorno == "") {		
		$retorno = @$_GET [constantes::$ID_REQ_ID_SISTEMA];
		$valor = 1;
		if ($retorno == null || $retorno == "") {
			$retorno = @$_POST [constantes::$ID_REQ_ID_SISTEMA];
			$valor = 2;
			if ($retorno == null || $retorno == "") {
				$teste = constantes::$ID_REQ_ID_SISTEMA;
				$retorno = getObjetoSessao ( $teste );
				$valor = 3;
			}
		}
	}
	
	// echo "($valor) eh : $retorno e teste eh $teste";
	return $retorno;
}
function getIncludeConfiguracao($CD_TIPO_PAGINA) {
	setTipoPagina ( $CD_TIPO_PAGINA );
	getIncludeConfigLibGeral ();
}
function getIncludeConfigLibGeral() {
	
	$nomesistema = getIdSistemaGETouPOST ();
	
	if ($nomesistema != null) {
		
		// poe na sessao
		// echo "INCLUINDO na sessao o nome do sistema <br>";
		putObjetoSessao ( constantes::$ID_REQ_ID_SISTEMA, $nomesistema );
		// aqui nao usa as funcoes definidas na biblioteca sessao.php porque estavam dando pau
		// $_SESSION [constantes::$ID_REQ_ID_SISTEMA] = $nomesistema;
		
		// E AGORA VAI INCLUIR O CONFIG LIB GERAL, dentro da estrutura de arquivo da aplicacao $nomesistema
		if (! defined ( 'CD_TIPO_PAGINA' )) {
			// throw new Exception("DECLARAR O TIPO DA PAGINA");
			define ( 'CD_TIPO_PAGINA', configuracao_geral::$CD_TIPO_PAGINA_FUNCAO_APLICACAO );
		}
		
		$CD_TIPO_PAGINA = CD_TIPO_PAGINA;
		
		// o config da aplicacao interna sera sempre uma pasta para baixo se comparado ao config da aplicacao geral
		// por isso, para achar o config geral, deve subir uma pasta
		$var_include_config_geral = "../config_lib.php";
		$logo = "imagens/logo.jpg";
		
		// ja o menu eh no mesmo nivel
		$pastaMenu = "";
		
		if ($CD_TIPO_PAGINA == configuracao_geral::$CD_TIPO_PAGINA_FUNCAO_GERAL) {
			// inclui o config_lib do sistema quando for o caso
			// se a funcao eh geral, ela esta sendo chamada dentro de uma aplicacao interna
			// eh preciso chamar o include da aplicacao interna.
			include_once ("../../$nomesistema/config_lib.php");
			
			$var_include_config_geral = "../" . $var_include_config_geral;
			
			$subirPasta = "../../$nomesistema";
			$logo = $subirPasta . "/" . $logo;
			$pastaMenu = $subirPasta;
			
			// echo "$subirPasta";
		} elseif ($CD_TIPO_PAGINA == configuracao_geral::$CD_TIPO_PAGINA_FUNCAO_APLICACAO) {
			$subirPasta = "../../";
			$var_include_config_geral = $subirPasta . $var_include_config_geral;
			$logo = $subirPasta . $logo;
			$pastaMenu = $subirPasta;
		}
		
		define ( 'GLOBAL_IMAGEM_LOGO', $logo );
		define ( 'GLOBAL_PASTA_MENU', $pastaMenu );
		
		$retorno = $var_include_config_geral;
		
		// inclui o config_lib geral
		include_once $var_include_config_geral;
	} else {
		// inclui apenas o config_lib geral
		include_once ("../../config_lib.php");
	}
}
class configuracao_geral {
	static $CD_TIPO_PAGINA_MENU_GERAL = 1;
	static $CD_TIPO_PAGINA_MENU_APLICACAO = 2;
	static $CD_TIPO_PAGINA_FUNCAO_GERAL = 3;
	static $CD_TIPO_PAGINA_FUNCAO_APLICACAO = 4;
	static $PASTA_CONFIG_LIB_FUNCOES_APLICACAO = "../../";
}
?>