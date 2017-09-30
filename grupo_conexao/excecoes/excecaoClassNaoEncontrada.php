<?php
include_once ("excecaoGenerica.php");
/**
 * Define uma classe de exce��o
 */
class excecaoClassNaoEncontrada extends excecaoGenerica
{
	var $caminhoAlternativo;
	// Redefine a exce��o de forma que a mensagem n�o seja opcional
	public function __construct($class_name, $caminhoAlternativo = null, Exception $previous = null) {
		// c�digo
		
		$message = "Classe n�o encontrada: $class_name.";
		// garante que tudo est� corretamente inicializado
		parent::__construct($message, excecaoGenerica::$CD_EXCECAO_CLASS_NAO_ENCONTRADA, $previous);
		$this->caminhoAlternativo = $caminhoAlternativo;
	}
	
}
?>