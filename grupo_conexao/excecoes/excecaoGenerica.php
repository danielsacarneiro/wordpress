<?php
/**
 * Define uma classe de exceчуo
 */
class excecaoGenerica extends Exception
{
	
	static $CD_EXCECAO_SUCESSO = 1;
    // Redefine a exceчуo de forma que a mensagem nуo seja opcional
    public function __construct($message, $code = 0, Exception $previous = null) {
    	if($message == null || $message == ""){
    		$message = "Exceчуo Genщrica.";
    	}
    
        // garante que tudo estс corretamente inicializado
        parent::__construct(get_class($this). ":". $message, $code, $previous);
    }

    // personaliza a apresentaчуo do objeto como string
    public function __toString() {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }
}
?>