<?php
// session_start ();
function iniciarSessao() {
	// if (! isSet ( $_SESSION )) {
	session_name ( md5 ( 'sal' . $_SERVER ['REMOTE_ADDR'] . 'sal' . $_SERVER ['HTTP_USER_AGENT'] . 'sal' ) );	
	session_start ();
	// }
}
function putObjetoSessao($ID, $voEntidade, $iniciarSessao = true) {
	/*
	 * if(!isSet($_SESSION))
	 * session_start();
	 * echo session_id();
	 * if(session_id() == "" || !isSet($_SESSION)){
	 * echo "NAO TEM SESSAO";
	 * session_start();
	 * }
	 * ELSE{
	 * echo "TEM SESSAO";
	 * }
	 */
	if ($iniciarSessao) {
		iniciarSessao ();
	}
	$_SESSION [$ID] = $voEntidade;
}
function existeObjetoSessao($ID, $iniciarSessao = true) {
	if($iniciarSessao){
		iniciarSessao ();
	}
	return isset ( $_SESSION [$ID] ) && $_SESSION [$ID] != null;
}

function getObjetoSessao($ID, $levantarExcecaoSeObjetoInexistente = false) {

	iniciarSessao ();	
	$objeto = null;
	
	if (existeObjetoSessao($ID, false)) {
		$objeto = $_SESSION [$ID];
	} else if ($levantarExcecaoSeObjetoInexistente) {
		throw new excecaoObjetoSessaoInexistente ( $ID );
	}
	
	$isUsarSessao = @$_POST ["utilizarSessao"] != "N";
	if (! $isUsarSessao) {
		$objeto = null;
		removeObjetoSessao ( $ID );
	}
	
	return $objeto;
}
function removeObjetoSessao($ID) {
	iniciarSessao ();
	unset ( $_SESSION [$ID] );
}
?>