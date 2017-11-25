<?php
include_once (caminho_lib . "voentidade.php");
class vomateria extends voentidade {
	// var $nmTable = "contrato_import";
	// para teste
	static $nmAtrCd = "mat_cd";
	static $nmAtrDescricao = "mat_ds";
	
	var $cd = "";
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
		return "MAT�RIA";
	}
	public static function getNmTabela() {
		return "materia";
	}
	public function getNmClassProcesso() {
		return "dbmateria";
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
		);
		
		return $retorno;
	}
	function getDadosRegistroBanco($registrobanco) {
		// as colunas default de voentidade sao incluidas pelo metodo getDadosBanco do voentidade
		$this->cd = $registrobanco [self::$nmAtrCd];
		$this->descricao = $registrobanco [self::$nmAtrDescricao];
	}
	function getDadosFormulario() {
		$this->cd = @$_POST [self::$nmAtrCd];
		$this->descricao = strtoupper(@$_POST [self::$nmAtrDescricao]);
	}
	function getValorChavePrimaria() {
		return $this->cd;
	}
	function getValorChaveHTML() {
		$retorno = $this->getValorChavePrimaria ();
		$retorno .= constantes::$CD_CAMPO_SEPARADOR . $this->descricao;
		return $retorno;
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