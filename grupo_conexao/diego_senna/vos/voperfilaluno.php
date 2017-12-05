<?php
include_once (caminho_lib . "voentidade.php");
include_once (caminho_funcoes_sistema. "/perfil_aluno/dominioTpMetaAluno.php");
class voperfilaluno extends voentidade {

	static $nmAtrCdPerfil = "perf_cd";
	static $nmAtrCdAluno = "pe_cd";	
	
	static $nmAtrTpMeta = "perfaluno_tpmeta";
	static $nmAtrNumDiasMeta = "perfaluno_diasmeta";
	static $nmAtrNumHorasMateriaDia = "perfaluno_horaspormaterianodia";
	static $nmAtrDtInicio = "perfaluno_dtinicio";	
	
	var $cdPerfil = "";
	var $cdAluno = "";
	var $tpMeta = "";
	var $numDiasMeta = "";
	var $numHorasMateriaDia = "";
	var $dtInicio = "";
	
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
		return "PERFIL X ALUNO";
	}
	public static function getNmTabela() {
		return "perfil_aluno";
	}
	public function getNmClassProcesso() {
		return "dbperfilaluno";
	}
	
	function getValoresWhereSQLChave($isHistorico) {
		$nmTabela = $this->getNmTabelaEntidade ( $isHistorico );
		$query = $this->getValoresWhereSQLChaveLogicaSemSQ ( $isHistorico );
		/*$query .= " AND " . $nmTabela . "." . self::$nmAtrCdFonte . "=" . $this->cdFonte;
		$query .= " AND " . $nmTabela . "." . self::$nmAtrCdFonte . "=" . $this->cdFonte;*/
		
		if ($isHistorico)
			$query .= " AND " . $nmTabela . "." . self::$nmAtrSqHist . "=" . $this->sqHist;
			
			return $query;
	}
	function getValoresWhereSQLChaveLogicaSemSQ($isHistorico) {
		$nmTabela = $this->getNmTabelaEntidade ( $isHistorico );
		$query = $nmTabela . "." . self::$nmAtrCdPerfil . "=" . $this->cdPerfil;
		$query = $nmTabela . "." . self::$nmAtrCdAluno . "=" . $this->cdAluno;
		
		return $query;
	}
	
	function getAtributosFilho() {
		$retorno = array (
				self::$nmAtrCdPerfil,
				self::$nmAtrCdAluno,
				self::$nmAtrTpMeta,
				self::$nmAtrNumDiasMeta,
				self::$nmAtrNumHorasMateriaDia,
				self::$nmAtrDtInicio,
		);
		
		return $retorno;
	}
	function getAtributosChavePrimaria() {
		$retorno = array (
				self::$nmAtrCdPerfil,
				self::$nmAtrCdAluno,
		);
		
		return $retorno;
	}	
	function getDadosRegistroBanco($registrobanco) {
		// as colunas default de voentidade sao incluidas pelo metodo getDadosBanco do voentidade
		$this->cdPerfil = $registrobanco [self::$nmAtrCdPerfil];
		$this->cdAluno = $registrobanco [self::$nmAtrCdAluno];
		$this->tpMeta = $registrobanco [self::$nmAtrTpMeta];
		$this->numDiasMeta = $registrobanco [self::$nmAtrNumDiasMeta];
		$this->numHorasMateriaDia = $registrobanco [self::$nmAtrNumHorasMateriaDia];
		$this->dtInicio = $registrobanco [self::$nmAtrDtInicio];
	}
	function getDadosFormulario() {
		$this->cdPerfil = @$_POST  [self::$nmAtrCdPerfil];
		$this->cdAluno = @$_POST  [self::$nmAtrCdAluno];
		$this->tpMeta = @$_POST[self::$nmAtrTpMeta];
		$this->numDiasMeta = @$_POST  [self::$nmAtrNumDiasMeta];
		$this->numHorasMateriaDia = @$_POST  [self::$nmAtrNumHorasMateriaDia];
		$this->dtInicio = @$_POST  [self::$nmAtrDtInicio];
	}
	function getValorChavePrimaria() {
		return $this->cdPerfil . CAMPO_SEPARADOR . $this->cdAluno . CAMPO_SEPARADOR . $this->sqHist;
	}
	
	function getChavePrimariaVOExplode($array) {
		$this->cdPerfil = $array [0];
		$this->cdAluno = $array [1];
	}
	
	function toString() {
		$retorno .= $this->cdPerfil . ",";
		$retorno .= $this->cdAluno . ",";
		$retorno .= $this->sqHist . ",";
		return $retorno;
	}
	
	function getMensagemComplementarTelaSucesso() {
		if($this->numDiasMeta != null){
			$filtro = new filtroManterPerfilAluno(false);
			$filtro->cdPerfil = $this->cdPerfil;
			$filtro->cdAluno = $this->cdAluno;			
		
			$colecao = $this->dbprocesso->consultarTelaConsulta($filtro);
			$registro = $colecao[0];
			
			$voperfil = new voperfil();
			$voaluno = new vopessoa();
			
			$voperfil->getDadosBanco($registro);
			$voaluno->getDadosBanco($registro);
			
			$retorno = "Perfil($voperfil->descricao) x Aluno($voaluno->nome).";
		}
		//$retorno = $this->getMensagemComplementarTelaSucessoPadrao ( $dsMateria . ":" . $this->getTituloJSP (), $this->cdFonte, $this->descricao, $this->sqHist );
		return $retorno;
	}
	
}
?>