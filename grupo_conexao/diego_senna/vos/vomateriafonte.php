<?php
include_once (caminho_lib . "voentidade.php");
class vomateriafonte extends voentidade {

	static $nmAtrCdMateria = "mat_cd";
	static $nmAtrCdFonte = "fonte_cd";
	static $nmAtrDescricao = "fonte_ds";
	
	var $cdFonte = "";
	var $cdMateria = "";
	var $descricao = "";
	
	// ...............................................................
	// Funções ( Propriedades e métodos da classe )
	function __construct() {
		parent::__construct ();
		$this->temTabHistorico = false;
		
		$class = self::getNmClassProcesso ();
		$this->dbprocesso = new $class ();
		
		// retira os atributos padrao que nao possui
		/*$arrayAtribRemover = array (
				self::$nmAtrDhInclusao,
				self::$nmAtrCdUsuarioInclusao
		);
		$this->removeAtributos ( $arrayAtribRemover );*/
	}
	public static function getTituloJSP() {
		return "FONTE DE LEITURA";
	}
	public static function getNmTabela() {
		return "materia_fonte";
	}
	public function getNmClassProcesso() {
		return "dbmateriafonte";
	}
	
	function getValoresWhereSQLChave($isHistorico) {
		$nmTabela = $this->getNmTabelaEntidade ( $isHistorico );
		$query = $this->getValoresWhereSQLChaveLogicaSemSQ ( $isHistorico );
		$query .= " AND " . $nmTabela . "." . self::$nmAtrCdFonte . "=" . $this->cdFonte;
		
		if ($isHistorico)
			$query .= " AND " . $nmTabela . "." . self::$nmAtrSqHist . "=" . $this->sqHist;
			
			return $query;
	}
	function getValoresWhereSQLChaveLogicaSemSQ($isHistorico) {
		$nmTabela = $this->getNmTabelaEntidade ( $isHistorico );
		$query = $nmTabela . "." . self::$nmAtrCdMateria. "=" . $this->cdMateria;
		
		return $query;
	}
	
	function getAtributosFilho() {
		$retorno = array (
				self::$nmAtrCdMateria,
				self::$nmAtrCdFonte,
				self::$nmAtrDescricao,
		);
		
		return $retorno;
	}
	function getAtributosChavePrimaria() {
		$retorno = array (
				self::$nmAtrCdMateria,
				self::$nmAtrCdFonte
		);
		
		return $retorno;
	}	
	function getDadosRegistroBanco($registrobanco) {
		// as colunas default de voentidade sao incluidas pelo metodo getDadosBanco do voentidade
		$this->cdMateria = $registrobanco [self::$nmAtrCdMateria];
		$this->cdFonte = $registrobanco [self::$nmAtrCdFonte];
		$this->descricao = $registrobanco [self::$nmAtrDescricao];
	}
	function getDadosFormulario() {
		$this->cdMateria = @$_POST [self::$nmAtrCdMateria];
		$this->cdFonte = @$_POST [self::$nmAtrCdFonte];
		$this->descricao = strtoupper(@$_POST [self::$nmAtrDescricao]);
	}
	function getValorChavePrimaria() {
		return $this->cdMateria . CAMPO_SEPARADOR . $this->cdFonte . CAMPO_SEPARADOR . $this->sqHist;
	}
	
	function getChavePrimariaVOExplode($array) {
		$this->cdMateria = $array [0];
		$this->cdFonte = $array [1];
	}
	
	function toString() {
		$retorno .= $this->cdMateria . ",";
		$retorno .= $this->cdFonte . ",";
		$retorno .= $this->descricao . ",";
		return $retorno;
	}
	
	function getMensagemComplementarTelaSucesso() {
		$vomateria = new vomateria();
		$vomateria->cd = $this->cdMateria;		
		$vomateria->dbprocesso->consultarPorChaveVO($vomateria);		
		//var_dump($vomateria);		
		$dsMateria = $vomateria->descricao;
		$retorno = $this->getMensagemComplementarTelaSucessoPadrao ( $dsMateria . ":" . $this->getTituloJSP (), $this->cdFonte, $this->descricao, $this->sqHist );
		return $retorno;
	}
	
}
?>