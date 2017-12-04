<?php
include_once(caminho_util."dominio.class.php");

  Class dominioTpParametroFonte extends dominio{

  	static $DS_ARTIGO = "Artigo";
  	static $DS_PAGINA = "Página";
  	static $DS_AULA= "Aula";

  	static $CD_ARTIGO = 1;
  	static $CD_PAGINA = 2;
  	static $CD_AULA= 3;
  	
  	
// ...............................................................
// Construtor
    function __construct () {        
		$this->colecao = self::getColecao();
	}
	
	static function getColecao() {
		return array (
				self::$CD_ARTIGO=> self::$DS_ARTIGO,
				self::$CD_PAGINA=> self::$DS_PAGINA,
				self::$CD_AULA=> self::$DS_AULA,
				
		);
	}
	
	
}
?>