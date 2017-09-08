<?php
/**
 * Define uma classe de exceзгo
 */
class excecaoChaveDuplicada extends excecaoGenerica
{
	// Redefine a exceзгo de forma que a mensagem nгo seja opcional
	public function __construct($message = "Chave Duplicada.", Exception $previous = null) {
		// cуdigo
		
		// garante que tudo estб corretamente inicializado
		parent::__construct($message, excecaoGenerica::$CD_EXCECAO_CHAVE_DUPLICADA, $previous);
	}
	
}
?>