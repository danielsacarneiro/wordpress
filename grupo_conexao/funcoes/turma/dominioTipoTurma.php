<?php
include_once(caminho_util."dominio.class.php");

  Class dominioTipoTurma extends dominio{
  	static $CD_TP_TURMA_PRAZO_INDETERMINADO = 1;
  	static $CD_TP_TURMA_PRAZO_DETERM_MENSAL = 2;
  	static $CD_TP_TURMA_PRAZO_DETERM_TOTAL = 3;
  	
  	static $DS_TP_TURMA_PRAZO_INDETERMINADO = 'Prazo indeterminado - Pag. Mensal';
  	static $DS_TP_TURMA_PRAZO_DETERM_MENSAL = 'Prazo determinado - Pag. Mensal';
  	static $DS_TP_TURMA_PRAZO_DETERM_TOTAL = 'Prazo determinado - Pag. Total';
  	
  	 
// ...............................................................
// Construtor
    function __construct () {        
		$this->colecao = self::getColecao();		
		//ksort($this->colecao);
	}
	
	static function getColecao(){
		$retorno = array(
				self::$CD_TP_TURMA_PRAZO_INDETERMINADO => self::$DS_TP_TURMA_PRAZO_INDETERMINADO,
				self::$CD_TP_TURMA_PRAZO_DETERM_MENSAL=> self::$DS_TP_TURMA_PRAZO_DETERM_MENSAL,
				self::$CD_TP_TURMA_PRAZO_DETERM_TOTAL=> self::$DS_TP_TURMA_PRAZO_DETERM_TOTAL,			
		);		
		return $retorno;
	}
	
	static function isPagamentoMensal($cd){
		return self::$CD_TP_TURMA_PRAZO_DETERM_TOTAL != $cd;		
	}
	
}
?>