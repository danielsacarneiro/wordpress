<?php
include_once ("util/constantes.class.php");
include_once ("util/sessao.php");



function setTipoPagina($CD_TIPO_PAGINA) {
	define ( 'CD_TIPO_PAGINA', $CD_TIPO_PAGINA );
}
function getIdSistemaGETouPOST() {
	$retorno = @$_GET [constantes::$ID_REQ_ID_SISTEMA];
	if ($retorno == null || $retorno == "") {		
		$retorno = @$_POST [constantes::$ID_REQ_ID_SISTEMA];
		
		echo ("retorno = $retorno,");
	}
	
	if ($retorno == null || $retorno == "") {		
		$retorno = getObjetoSessao(constantes::$ID_REQ_ID_SISTEMA);
		echo ("retorno = $retorno,");
		//$retorno = "diego_senna";
		//$retorno = $_SESSION [constantes::$ID_REQ_ID_SISTEMA];
	}
	//echo "SISTEMA $retorno";
	
	return $retorno;
}
function getIncludeConfiguracao($CD_TIPO_PAGINA) {
		
	setTipoPagina($CD_TIPO_PAGINA);
	
	$nomesistema = getIdSistemaGETouPOST ();
	getIncludeConfigLibGeral ( $nomesistema );
}

function getIncludeConfigLibGeral($nomesistema) {
	
	if ($nomesistema != null) {
		
		//poe na sessao 
		putObjetoSessao(constantes::$ID_REQ_ID_SISTEMA, $nomesistema, false);
		//aqui nao usa as funcoes definidas na biblioteca sessao.php porque estavam dando pau
		//$_SESSION [constantes::$ID_REQ_ID_SISTEMA] = $nomesistema;
		
		//E AGORA VAI INCLUIR O CONFIG LIB GERAL, dentro da estrutura de arquivo da aplicacao $nomesistema		
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
			//inclui o config_lib do sistema quando for o caso
			//se a funcao eh geral, ela esta sendo chamada dentro de uma aplicacao interna
			//eh preciso chamar o include da aplicacao interna.
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

		//inclui o config_lib geral
		include_once $var_include_config_geral;
	}else{
		//inclui apenas o config_lib geral
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