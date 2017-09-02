<?php
/**
 * Define uma classe de exceção
*/
class excecaoObjetoSessaoInexistente extends excecaoGenerica
{
	// Redefine a exceção de forma que a mensagem não seja opcional
	public function __construct($message, $code = 0, Exception $previous = null) {
		// código
				
		$message = "Objeto inexistente na sessão." . $message . ".";
		$message .= "Arquivo origem: " . $this->getFile() . "<br>";
		$message .= "Linha origem: " . $this->getLine(). "<br>";
		
		// garante que tudo está corretamente inicializado
		parent::__construct($message, $code, $previous);
	}

}
?>