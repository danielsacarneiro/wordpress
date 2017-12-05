<?php
include_once(caminho_util."dominio.class.php");

Class dominioTpMetaAluno extends dominio{

  	static $DS_SEMANAL = "Semanal";
  	static $DS_QUINZENAL = "Quinzenal";
  	static $DS_MENSAL = "Mensal";
  	
  	static $CD_SEMANAL = "07";
  	static $CD_QUINZENAL = 15;
  	static $CD_MENSAL = 30;
  	
// ...............................................................
// Construtor
    function __construct () {        
		$this->colecao = self::getColecao();
	}
	
	static function getColecao() {
		return array (
				self::$CD_SEMANAL=> self::$DS_SEMANAL,
				self::$CD_QUINZENAL=> self::$DS_QUINZENAL,
				self::$CD_MENSAL=> self::$DS_MENSAL,
				
		);
	}	
}
?>