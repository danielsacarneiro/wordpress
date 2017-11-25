<?php
function setSistemaInterno($nmSistema){
	define('isSistemaInterno', true);
	define('nmSistema', $nmSistema);
	define('imgSistema', "../../imagens/logo.jpg");	
}

function getNmSistemaInterno(){
	if(defined('nmSistema'))
		$retorno = nmSistema;
	
	return $retorno;
}

function temSistemaInterno(){
	if(defined('isSistemaInterno'))
		return isSistemaInterno;
	else		
		return false;
}
