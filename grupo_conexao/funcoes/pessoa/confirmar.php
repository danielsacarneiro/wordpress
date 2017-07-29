<?php
include_once ("../../config_lib.php");
include_once (caminho_util . "bibliotecaHTML.php");

try {
	inicioComValidacaoUsuario ( true );
	
	$vo = new vopessoa ();
	$vo->getDadosFormularioEntidade ();
	
	putObjetoSessao ( "vo", $vo );
	
	header("Location: ../confirmar.php?class=".$vo->getNmClassProcesso(),TRUE,307);
} catch ( Exception $ex ) {
	putObjetoSessao ( "vo", $vo );
	tratarExcecaoHTML ( $ex );
}

?>