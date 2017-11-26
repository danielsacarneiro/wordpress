<?php
include_once (caminho_lib . "voentidade.php");
//include_once (caminho_funcoes . "demanda/dominioPrioridadeDemanda.php");
class vometa extends voentidade {
	static $nmAtrCd = "met_cd";
	static $nmAtrCdPerfil = "perf_cd";
	static $nmAtrTipo = "met_tp"; //Tem a opção de semanal, quinzenal e mensal
	static $nmAtrObs = "met_obs";
	static $nmAtrDtInicio = "met_dtinicio";
	static $nmAtrDtFim = "met_dtfim";
	
	var $cd = "";
	var $cdPerfil = "";
	var $tipo = "";
	var $obs = "";
	var $dtInicio = "";
	var $dtFim = "";
	
	// ...............................................................
	// Funcoes ( Propriedades e mÃ©todos da classe )
	function __construct() {
		parent::__construct ();
		$this->temTabHistorico = false;
		$class = self::getNmClassProcesso ();
		$this->dbprocesso = new $class ();		
	}
	public static function getTituloJSP() {
		return "META x PERFIL";
	}
	public static function getNmTabela() {
		return "meta";
	}
	public static function getNmClassProcesso() {
		return "dbmeta";
	}
	function getValoresWhereSQLChave($isHistorico) {
		$nmTabela = $this->getNmTabelaEntidade ( $isHistorico );
		$query = $this->getValoresWhereSQLChaveLogicaSemSQ ( $isHistorico );
		$query .= " AND " . $nmTabela . "." . self::$nmAtrCd . "=" . $this->cd;
		
		if ($isHistorico)
			$query .= " AND " . $nmTabela . "." . self::$nmAtrSqHist . "=" . $this->sqHist;
		
		return $query;
	}
	function getValoresWhereSQLChaveLogicaSemSQ($isHistorico) {
		$nmTabela = $this->getNmTabelaEntidade ( $isHistorico );
		$query = $nmTabela . "." . self::$nmAtrCdPerfil . "=" . $this->cdPerfil;
		
		return $query;
	}
	function getAtributosFilho() {
		
		static $nmAtrCd = "met_cd";
		static $nmAtrCdPerfil = "perf_cd";
		static $nmAtrTipo = "met_tp";
		static $nmAtrObs = "met_obs";
		static $nmAtrDtInicio = "met_dtinicio";
		static $nmAtrDtFim = "met_dtfim";
		
		$retorno = array (
				self::$nmAtrCd,
				self::$nmAtrCdPerfil,
				self::$nmAtrTipo,
				self::$nmAtrObs,
				self::$nmAtrDtInicio,
				self::$nmAtrDtFim, 
		);
		
		return $retorno;
	}
	function getAtributosChavePrimaria() {
		$retorno = array (
				self::$nmAtrCd,
				self::$nmAtrCdPerfil
		);
		
		return $retorno;
	}
	
	function getDadosRegistroBanco($registrobanco) {				
		// as colunas default de voentidade sao incluidas pelo metodo getDadosBanco do voentidade
		$this->cd = $registrobanco [self::$nmAtrCd];
		$this->cdPerfil = $registrobanco [self::$nmAtrCdPerfil];
		$this->tipo = $registrobanco [self::$nmAtrTipo];
		$this->obs = $registrobanco [self::$nmAtrObs];
		$this->dtInicio = $registrobanco [self::$nmAtrDtInicio];
		$this->dtFim = $registrobanco [self::$nmAtrDtFim];
		
	}
	function getDadosFormulario() {
		
		$this->cd = @$_POST [self::$nmAtrCd];
		$this->cdPerfil = @$_POST [self::$nmAtrCdPerfil];
		$this->tipo = @$_POST [self::$nmAtrTipo];
		$this->obs = @$_POST [self::$nmAtrObs];
		$this->dtInicio = @$_POST [self::$nmAtrDtInicio];
		$this->dtFim = @$_POST [self::$nmAtrDtFim];		
	}
	function toString() {
		$retorno .= $this->cd;
		$retorno .= "," . $this->cdPerfil;
		return $retorno;
	}
	function getValorChavePrimaria() {
		return $this->cd . CAMPO_SEPARADOR . $this->cdPerfil . CAMPO_SEPARADOR . $this->sqHist;
	}
	function getChavePrimariaVOExplode($array) {
		$this->cd = $array [0];
		$this->cdPerfil = $array [1];
	}
	function getMensagemComplementarTelaSucesso() {
		$retorno = "Meta (Número - Perfil): " . formatarCodigoAnoComplementoArgs ( $this->cd, $this->ano, TAMANHO_CODIGOS, null );
		if ($this->sqHist != null) {
			$retorno .= "<br>Núm. Histórico: " . complementarCharAEsquerda ( $this->sqHist, "0", TAMANHO_CODIGOS );
		}
		return $retorno;
	}
}
?>