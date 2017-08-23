<?php
include_once (caminho_lib . "voentidade.php");
class vopessoaturma extends voentidade {

	static $NUM_MAXIMO_ALUNO = 15;
	
	static $nmAtrCdPessoa = "pe_cd";
	static $nmAtrCdTurma = "tu_cd";
	static $nmAtrValor = "pt_valor";
	static $nmAtrObservacao = "pt_obs";
	var $cdPessoa = "";
	var $cdTurma = "";
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
		return "PESSOA x TURMA";
	}
	public static function getNmTabela() {
		return "pessoa_turma";
	}
	public function getNmClassProcesso() {
		return "dbpessoaturma";
	}
	function getValoresWhereSQLChave($isHistorico) {
		$nmTabela = $this->getNmTabelaEntidade ( $isHistorico );
		$query = $nmTabela . "." . self::$nmAtrCdPessoa . "=" . $this->cdPessoa;
		$query .= " AND " . $nmTabela . "." . self::$nmAtrCdTurma . "=" . $this->cdTurma;
		if ($isHistorico)
			$query .= " AND " . $nmTabela . "." . self::$nmAtrSqHist . "=" . $this->sqHist;
		
		return $query;
	}
	function getAtributosFilho() {
		$retorno = array (
				self::$nmAtrCdPessoa,
				self::$nmAtrCdTurma,
				self::$nmAtrValor,
				self::$nmAtrObservacao 
		);
		
		return $retorno;
	}
	function getDadosRegistroBanco($registrobanco) {
		// as colunas default de voentidade sao incluidas pelo metodo getDadosBanco do voentidade
		$this->cdPessoa = $registrobanco [self::$nmAtrCdPessoa];
		$this->cdTurma = $registrobanco [self::$nmAtrCdTurma];
		$this->valor = $registrobanco [self::$nmAtrValor];
		$this->obs = $registrobanco [self::$nmAtrObservacao];
	}
	function getDadosFormulario() {
		$this->cdPessoa = @$_POST [self::$nmAtrCdPessoa];
		$this->cdTurma = @$_POST [self::$nmAtrCdTurma];
		$this->valor = @$_POST [self::$nmAtrValor];
		$this->obs = @$_POST [self::$nmAtrObservacao];
	}
	function getValorChavePrimaria() {
		return $this->cdPessoa . CAMPO_SEPARADOR . $this->cdTurma . CAMPO_SEPARADOR . $this->sqHist;
	}
	function getChavePrimariaVOExplode($array) {
		$this->cdPessoa = $array [0];
		$this->cdTurma = $array [1];
		$this->sqHist = $array [2];
	}
	function toString() {
		$retorno .= $this->cdPessoa . ",";
		$retorno .= $this->cdTurma . ",";
		return $retorno;
	}
}
?>