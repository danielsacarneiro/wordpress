<?php
include_once (caminho_lib . "voentidade.php");
class vopagamento extends voentidade {
	
	static $nmAtrCdPessoa = "pe_cd";
	static $nmAtrCdTurma = "tu_cd";
	static $nmAtrNumParcelaPaga = "pag_parcela";

	var $cdPessoa = "";
	var $cdTurma = "";
	var $numParcelaPaga = "";
		
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
		return "PAGAMENTO";
	}
	public static function getNmTabela() {
		return "pagamento";
	}
	public function getNmClassProcesso() {
		return "dbpagamento";
	}
	function getValoresWhereSQLChave($isHistorico) {
		$nmTabela = $this->getNmTabelaEntidade ( $isHistorico );
		$query = $nmTabela . "." . self::$nmAtrCdPessoa . "=" . $this->cdPessoa;
		$query .= " AND " . $nmTabela . "." . self::$nmAtrCdTurma . "=" . $this->cdTurma;
		$query .= " AND " . $nmTabela . "." . self::$nmAtrNumParcelaPaga . "=" . $this->numParcelaPaga;
		if ($isHistorico)
			$query .= " AND " . $nmTabela . "." . self::$nmAtrSqHist . "=" . $this->sqHist;
		
		return $query;
	}
	function getAtributosFilho() {
		$retorno = array (
				self::$nmAtrCdPessoa,
				self::$nmAtrCdTurma,
				self::$nmAtrNumParcelaPaga
		);
		
		return $retorno;
	}
	function getDadosRegistroBanco($registrobanco) {
		// as colunas default de voentidade sao incluidas pelo metodo getDadosBanco do voentidade
		$this->cdPessoa = $registrobanco [self::$nmAtrCdPessoa];
		$this->cdTurma = $registrobanco [self::$nmAtrCdTurma];
		$this->numParcelaPaga = $registrobanco [self::$nmAtrNumParcelaPaga];
	}
	function getDadosFormulario() {
		$this->cdPessoa = @$_POST [self::$nmAtrCdPessoa];
		$this->cdTurma = @$_POST [self::$nmAtrCdTurma];
		$this->numParcelaPaga = $_POST[self::$nmAtrNumParcelaPaga];
	}
	function getValorChavePrimaria() {
		return $this->cdPessoa . CAMPO_SEPARADOR . $this->cdTurma . CAMPO_SEPARADOR . $this->numParcelaPaga . CAMPO_SEPARADOR . $this->sqHist;
	}
	function getChavePrimariaVOExplode($array) {
		$this->cdPessoa = $array [0];
		$this->cdTurma = $array [1];
		$this->numParcelaPaga = $array [2];
		$this->sqHist = $array [3];
	}
	function toString() {
		$retorno .= $this->cdPessoa . ",";
		$retorno .= $this->cdTurma . ",";
		$retorno .= $this->numParcelaPaga . ",";
		return $retorno;
	}
}
?>