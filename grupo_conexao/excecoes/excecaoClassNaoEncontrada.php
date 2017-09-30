<?php
include_once ("excecaoGenerica.php");
/**
 * Define uma classe de exceзгo
 */
class excecaoClassNaoEncontrada extends excecaoGenerica
{
	var $caminhoAlternativo;
	// Redefine a exceзгo de forma que a mensagem nгo seja opcional
	public function __construct($class_name, $caminhoAlternativo = null, Exception $previous = null) {
		// cуdigo
		
		$message = "Classe nгo encontrada: $class_name.";
		// garante que tudo estб corretamente inicializado
		parent::__construct($message, excecaoGenerica::$CD_EXCECAO_CLASS_NAO_ENCONTRADA, $previous);
		$this->caminhoAlternativo = $caminhoAlternativo;
	}
	
}
?>