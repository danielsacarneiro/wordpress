<?php
function putObjetoSessao($ID, $voEntidade) {
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
	 session_start ();
	 $_SESSION [$ID] = $voEntidade;
}
function existeObjetoSessao($ID) {
	session_start ();
	return isset ( $_SESSION [$ID] ) && $_SESSION [$ID] != null;
}
function getObjetoSessao($ID, $levantarExcecaoSeObjetoInexistente = false) {
	session_start ();
	
	$objeto = null;
	
	if ($_SESSION [$ID] != null) {
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
	session_start ();
	unset ( $_SESSION [$ID] );
}
?>