<?php
/**
 * Define uma classe de exceзгo
 */
class excecaoOperacaoSucesso extends excecaoGenerica
{
	// Redefine a exceзгo de forma que a mensagem nгo seja opcional
	public function __construct($message = "Sucesso.", $code = 0, Exception $previous = null) {
		// cуdigo
		
		// garante que tudo estб corretamente inicializado
		parent::__construct($message, excecaoGenerica::$CD_EXCECAO_SUCESSO, $previous);
	}
	
}
?>