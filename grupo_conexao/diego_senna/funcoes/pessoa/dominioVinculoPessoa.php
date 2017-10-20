<?php
include_once(caminho_util."dominio.class.php");

  Class dominioVinculoPessoa extends dominio{
  	static $CD_VINCULO_ALUNO = 1;
  	static $CD_VINCULO_PROFESSOR = 2;

  	static $DS_VINCULO_ALUNO = 'Aluno';
  	static $DS_VINCULO_PROFESSOR = 'Professor';
  	
// ...............................................................
// Construtor
    function __construct () {        
		$this->colecao = self::getColecao();
	}
	
	static function getColecao() {
		return array (
				self::$CD_VINCULO_ALUNO => self::$DS_VINCULO_ALUNO,
				self::$CD_VINCULO_PROFESSOR => self::$DS_VINCULO_PROFESSOR);
	}
	
	
}
?>