<?php
include_once ("../../config_lib.php");
include_once (caminho_util . "bibliotecaHTML.php");

//pagina que serve como caminho alternativo aquela que confirma geral
try {
	inicioComValidacaoUsuario ( true );
	
	$vo = new voturma ();
	//$vo->getDadosFormularioEntidade ();
	
		
	header("Location: ../confirmar.php?class=".get_class($vo),TRUE,307);
} catch ( Exception $ex ) {
	putObjetoSessao ( "vo", $vo );
	tratarExcecaoHTML ( $ex );
}

?>