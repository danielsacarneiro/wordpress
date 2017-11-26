<?php
function setSistemaInterno($pastaSistema, $nmSistema=null){
	define('isSistemaInterno', true);
	define('GLOBALNmSistema', $nmSistema);
	define('pastaSistema', $pastaSistema);
	define('imgSistema', "../../imagens/logo.jpg");	
}

function getNmSistemaInterno(){
	if(defined('pastaSistema'))
		$retorno = pastaSistema;
	
	return $retorno;
}

function temSistemaInterno(){
	if(defined('isSistemaInterno'))
		return isSistemaInterno;
	else		
		return false;
}
