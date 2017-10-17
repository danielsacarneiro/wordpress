<?php
//include_once ("../../config_lib.php");
include_once("../../configuracao_geral.php");
$CD_TIPO_PAGINA = configuracao_geral::$CD_TIPO_PAGINA_FUNCAO_GERAL;
setTipoPagina($CD_TIPO_PAGINA);
getIncludeConfiguracao();

include_once (caminho_util . "bibliotecaHTML.php");

try {
	inicioComValidacaoUsuario ( true );
	
	$vo = new vopessoa ();
	$vo->getDadosFormularioEntidade ();
	
	putObjetoSessao ( "vo", $vo );
	
	//$vo->excluirFoto();
	
	header("Location: ../confirmar.php?class=".$vo->getNmClassProcesso(),TRUE,307);
} catch ( Exception $ex ) {
	putObjetoSessao ( "vo", $vo );
	tratarExcecaoHTML ( $ex );
}

?>