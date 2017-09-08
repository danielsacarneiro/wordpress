<?php
/**
 * Define uma classe de exceção
*/
class excecaoObjetoSessaoInexistente extends excecaoGenerica
{
	// Redefine a exceção de forma que a mensagem não seja opcional
	public function __construct($ID, Exception $previous = null) {
		// código
				
		$message = "Objeto inexistente na sessão (ID='" . $ID. "').";
		$message .= "<br>Arquivo origem: " . $this->getFile();
		$message .= "<br>Linha origem: " . $this->getLine();
		
		// garante que tudo está corretamente inicializado
		parent::__construct($message, excecaoGenerica::$CD_EXCECAO_OBJETO_SESSAO_INEXISTENTE, $previous);
	}

}
?>