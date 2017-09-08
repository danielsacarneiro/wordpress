<?php
/**
 * Define uma classe de exce��o
*/
class excecaoObjetoSessaoInexistente extends excecaoGenerica
{
	// Redefine a exce��o de forma que a mensagem n�o seja opcional
	public function __construct($ID, Exception $previous = null) {
		// c�digo
				
		$message = "Objeto inexistente na sess�o (ID='" . $ID. "').";
		$message .= "<br>Arquivo origem: " . $this->getFile();
		$message .= "<br>Linha origem: " . $this->getLine();
		
		// garante que tudo est� corretamente inicializado
		parent::__construct($message, excecaoGenerica::$CD_EXCECAO_OBJETO_SESSAO_INEXISTENTE, $previous);
	}

}
?>