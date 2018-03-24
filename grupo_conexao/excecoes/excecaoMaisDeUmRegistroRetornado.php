<?php
/**
 * Define uma classe de exceзгo
 */
class excecaoMaisDeUmRegistroRetornado extends excecaoGenerica
{
    // Redefine a exceзгo de forma que a mensagem nгo seja opcional
    public function __construct($query = null, $code = 0, Exception $previous = null) {
    	$message = "Existe mais de um registro.";
    	if($query!= null){
    		$message .= "<br> Query: $query";
    	}
        // cуdigo
    
        // garante que tudo estб corretamente inicializado
        parent::__construct($message, $code, $previous);
    }

}
?>