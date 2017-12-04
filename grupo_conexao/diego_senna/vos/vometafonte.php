<?php
include_once (caminho_lib . "voentidade.php");
include_once (caminho_funcoes_sistema. "/materia_fonte/dominioTpFonte.php");
include_once (caminho_funcoes_sistema. "/materia_fonte/dominioTpParametroFonte.php");
class vometafonte extends voentidade {
	
	static $ID_REQ_DsTpParam = "ID_REQ_DsTpParam";

	static $nmAtrSq = "metaf_sq";
	static $nmAtrCdMeta = "meta_cd";	
	static $nmAtrCdPerfil = "perf_cd";
	static $nmAtrCdMateria = "mat_cd";
	
	static $nmAtrTpFonte = "metaf_tpfonte";
	static $nmAtrCdFonte = "fonte_cd";
	static $nmAtrTpParam = "metaf_tpparam";
	static $nmAtrNumParamInicio = "metaf_numparaminicio";
	static $nmAtrNumParamFim = "metaf_numparamfim";	
	
	static $nmAtrObs = "metaf_obs";
	static $nmAtrNumHoras = "metaf_horas";
	
	var $sq = "";
	var $cdMeta = "";
	var $cdPerfil = "";
	var $cdMateria = "";
	
	var $cdFonte= "";
	var $tpFonte= "";
	var $numParamInicio = "";
	var $numParamFim = "";
	var $tpParam = "";
	
	var $obs = "";
	var $numHoras = "";
	
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
		return "META X PERFIL";
	}
	public static function getNmTabela() {
		return "meta_fonte";
	}
	public function getNmClassProcesso() {
		return "dbmetafonte";
	}
	
	function getValoresWhereSQLChave($isHistorico) {
		$nmTabela = $this->getNmTabelaEntidade ( $isHistorico );
		$query = $this->getValoresWhereSQLChaveLogicaSemSQ ( $isHistorico );
		
		$query .= " AND " . $nmTabela . "." . self::$nmAtrSq . "=" . $this->sq;	
		
		if ($isHistorico)
			$query .= " AND " . $nmTabela . "." . self::$nmAtrSqHist . "=" . $this->sqHist;
			
			return $query;
	}
	function getValoresWhereSQLChaveLogicaSemSQ($isHistorico) {
		
		$nmTabela = $this->getNmTabelaEntidade ( $isHistorico );
		$query = $nmTabela . "." . self::$nmAtrCdMeta . "=" . $this->cdMeta;
		$query = $nmTabela . "." . self::$nmAtrCdPerfil . "=" . $this->cdPerfil;
		$query = $nmTabela . "." . self::$nmAtrCdMateria. "=" . $this->cdMateria;
		
				
		return $query;
	}
	
	function getAtributosFilho() {		
		$retorno = array (
				self::$nmAtrSq,
				self::$nmAtrCdMeta,
				self::$nmAtrCdPerfil,
				self::$nmAtrCdMateria,
				self::$nmAtrTpFonte,
				self::$nmAtrCdFonte,
				self::$nmAtrTpParam,
				self::$nmAtrNumParamInicio,
				self::$nmAtrNumParamFim,
				self::$nmAtrObs,
				self::$nmAtrNumHoras,
		);
		
		return $retorno;
	}
	function getAtributosChavePrimaria() {
		$retorno = array (
				self::$nmAtrSq,
				self::$nmAtrCdMeta,
				self::$nmAtrCdPerfil,
				self::$nmAtrCdMateria,
		);
		
		return $retorno;
	}	
	function getDadosRegistroBanco($registrobanco) {		
		// as colunas default de voentidade sao incluidas pelo metodo getDadosBanco do voentidade
		$this->sq = $registrobanco [self::$nmAtrSq];
		$this->cdMeta= $registrobanco [self::$nmAtrCdMeta];
		$this->cdPerfil = $registrobanco [self::$nmAtrCdPerfil];
		$this->cdMateria = $registrobanco [self::$nmAtrCdMateria];

		$this->tpFonte = $registrobanco [self::$nmAtrTpFonte];
		$this->cdFonte = $registrobanco [self::$nmAtrCdFonte];
		$this->tpParam = $registrobanco [self::$nmAtrTpParam];
		$this->numParamInicio = $registrobanco [self::$nmAtrNumParamInicio];
		$this->numParamFim = $registrobanco [self::$nmAtrNumParamFim];

		$this->obs = $registrobanco [self::$nmAtrObs];
		$this->numHoras = $registrobanco [self::$nmAtrNumHoras];
		
	}
	function getDadosFormulario() {
		$this->sq = @$_POST [self::$nmAtrSq];
		$this->cdMeta= @$_POST [self::$nmAtrCdMeta];
		$this->cdPerfil = @$_POST [self::$nmAtrCdPerfil];
		$this->cdMateria = @$_POST [self::$nmAtrCdMateria];
		
		$this->tpFonte = @$_POST[self::$nmAtrTpFonte];
		$this->cdFonte = @$_POST [self::$nmAtrCdFonte];
		$this->tpParam = @$_POST [self::$nmAtrTpParam];
		$this->numParamInicio = @$_POST [self::$nmAtrNumParamInicio];
		$this->numParamFim = @$_POST [self::$nmAtrNumParamFim];
		
		$this->obs = @$_POST [self::$nmAtrObs];
		$this->numHoras = @$_POST [self::$nmAtrNumHoras];		
	}
	function getValorChavePrimaria() {
		return $this->sq . CAMPO_SEPARADOR . $this->cdMeta . CAMPO_SEPARADOR . $this->cdPerfil . CAMPO_SEPARADOR . $this->cdMateria . CAMPO_SEPARADOR . $this->sqHist;
	}
	
	function getChavePrimariaVOExplode($array) {
		$this->sq = $array [0];
		$this->cdMeta = $array [1];
		$this->cdPerfil = $array [2];
		$this->cdMateria = $array [3];
		$this->sqHist = $array [4];
	}
	
	function toString() {
		$retorno .= $this->sq . ",";
		$retorno .= $this->cdMeta . ",";
		$retorno .= $this->cdPerfil . ",";		
		$retorno .= $this->cdMateria . ",";
		$retorno .= $this->sqHist . ",";
		return $retorno;
	}
	
	function getMensagemComplementarTelaSucesso() {
		if($this->sq != null && getFuncao() != constantes::$CD_FUNCAO_EXCLUIR){
			$filtro = new filtroManterMetaFonte(false);			
			$filtro->vometafonte = $this;			
			$colecao = $this->dbprocesso->consultarTelaConsulta($filtro);			
			$registro = $colecao[0];
			
			$voperfil = new voperfil();
			$voperfil->getDadosBanco($registro);
			
			$vomateria = new vomateria();
			$vomateria->getDadosBanco($registro);
			
			$retorno = "Perfil($voperfil->descricao) x Mat�ria($vomateria->descricao): META " . complementarCharAEsquerda($this->cdMeta, "0", TAMANHO_CODIGOS_SAFI) 
			. ", SEQUENCIAL " . complementarCharAEsquerda($this->sq, "0", TAMANHO_CODIGOS_SAFI);
		}
		//$retorno = $this->getMensagemComplementarTelaSucessoPadrao ( $dsMateria . ":" . $this->getTituloJSP (), $this->cdFonte, $this->descricao, $this->sqHist );
		return $retorno;
	}
	
}
?>