<?php
/**
 * Define uma classe de exce��o
 */
class excecaoMaisDeUmRegistroRetornado extends excecaoGenerica
{
    // Redefine a exce��o de forma que a mensagem n�o seja opcional
    public function __construct($query = null, $code = 0, Exception $previous = null) {
    	$message = "Existe mais de um registro.";
    	if($query!= null){
    		$message .= "<br> Query: $query";
    	}
        // c�digo
    
        // garante que tudo est� corretamente inicializado
        parent::__construct($message, $code, $previous);
    }

}
?>