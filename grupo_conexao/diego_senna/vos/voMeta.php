<?php
include_once (caminho_lib . "voentidade.php");
//include_once ("dbMeta.php");
class voMeta extends voentidade {
	// var $nmTable = "contrato_import";
	// para teste
	static $nmAtrCd = "ma_cd";
	static $nmAtrDescricao = "ma_ds";
	static $nmAtrValor= "ma_valor";
	static $nmAtrObservacao = "ma_obs";
	
	var $cd = "";
	var $descricao = "";
	var $valor = "";
	var $obs = "";
	
	// ...............................................................
	// Funções ( Propriedades e métodos da classe )
	function __construct() {
		parent::__construct ();
		$this->temTabHistorico = false;
		
		$class = self::getNmClassProcesso ();
		$this->dbprocesso = new $class ();
		
		// retira os atributos padrao que nao possui
		$arrayAtribRemover = array (
				self::$nmAtrDhInclusao,
				self::$nmAtrCdUsuarioInclusao
		);
		$this->removeAtributos ( $arrayAtribRemover );		
	}
	public static function getTituloJSP() {
		return "MAT�RIA";
	}
	public static function getNmTabela() {
		return "materia";
	}
	public function getNmClassProcesso() {
		return "dbMeta";
	}
	function getValoresWhereSQLChave($isHistorico) {
		$nmTabela = $this->getNmTabelaEntidade ( $isHistorico );
		$query = $nmTabela . "." . self::$nmAtrCd . "=" . $this->cd;
		// $query.= " AND ". $nmTabela . "." . self::$nmAtrCd . "=" . $this->cd;
		if ($isHistorico)
			$query .= " AND " . $nmTabela . "." . self::$nmAtrSqHist . "=" . $this->sqHist;
		
		return $query;
	}
	function getAtributosFilho() {
		$retorno = array (
				self::$nmAtrCd,
				self::$nmAtrDescricao,
				self::$nmAtrValor,
				self::$nmAtrObservacao
		);
		
		return $retorno;
	}
	function getDadosRegistroBanco($registrobanco) {
		// as colunas default de voentidade sao incluidas pelo metodo getDadosBanco do voentidade
		$this->cd = $registrobanco [self::$nmAtrCd];
		$this->descricao = $registrobanco [self::$nmAtrDescricao];
		$this->valor = $registrobanco [self::$nmAtrValor];
		$this->obs = $registrobanco [self::$nmAtrObservacao];
	}
	function getDadosFormulario() {
		$this->cd = @$_POST [self::$nmAtrCd];
		$this->descricao = @$_POST [self::$nmAtrDescricao];
		$this->valor = @$_POST[self::$nmAtrValor];
		$this->obs = @$_POST[self::$nmAtrObservacao];
	}
	function getValorChavePrimaria() {
		return $this->cd;
	}
	function toString() {
		$retorno .= $this->cd . ",";
		$retorno .= $this->descricao . ",";
		return $retorno;
	}
	
	function getMensagemComplementarTelaSucesso() {
		$retorno = $this->getMensagemComplementarTelaSucessoPadrao ( $this->getTituloJSP (), $this->cd, $this->descricao, $this->sqHist );
		return $retorno;
	}
	
}
?>