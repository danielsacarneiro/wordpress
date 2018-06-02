<?php
include_once (caminho_lib . "voentidade.php");
class voassunto extends voentidade {
	
	static $nmAtrCdMateria = "mat_cd";
	static $nmAtrCdPerfil = "perf_cd";
	static $nmAtrSqAssunto = "assunto_sq";
	
	static $nmAtrIdAssunto = "assunto_id";
	static $nmAtrCarga = "assunto_carga";
	
	static $nmAtrDsAssunto = "assunto_ds";
	static $nmAtrInLeiSeca = "assunto_in_leiseca";
	static $nmAtrInDoutrina = "assunto_in_doutrina";
	static $nmAtrInQuestoes = "assunto_in_questoes";
	
	var $cdPerfil = "";
	var $cdMateria = "";
	var $sq = "";
	
	var $idAssunto = "";
	var $carga = "";
	
	var $ds = "";
	var $inLeiSeca = "";
	var $inDoutrina = "";
	var $inQuestoes = "";	
	
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
		return "ASSUNTO (destrinchando o edital)";
	}
	public static function getNmTabela() {
		return "assunto";
	}
	public function getNmClassProcesso() {
		return "dbassunto";
	}
	
	function getValoresWhereSQLChave($isHistorico) {
		$nmTabela = $this->getNmTabelaEntidade ( $isHistorico );
		$query = $this->getValoresWhereSQLChaveLogicaSemSQ ( $isHistorico );
		$query .= " AND " . $nmTabela . "." . self::$nmAtrSqAssunto . "=" . $this->sq;
		//$query .= " AND " . $nmTabela . "." . self::$nmAtrCdFonte . "=" . $this->cdFonte;
		
		if ($isHistorico)
			$query .= " AND " . $nmTabela . "." . self::$nmAtrSqHist . "=" . $this->sqHist;
			
			return $query;
	}
	function getValoresWhereSQLChaveLogicaSemSQ($isHistorico) {
		$nmTabela = $this->getNmTabelaEntidade ( $isHistorico );
		$query = $nmTabela . "." . self::$nmAtrCdMateria. "=" . $this->cdMateria;
		$query .= " AND " . $nmTabela . "." . self::$nmAtrCdPerfil . "=" . $this->cdPerfil; 
		
		return $query;
	}
	
	function getAtributosFilho() {				
		$array1 = static::getAtributosChavePrimaria();
		$array2 = array (
				self::$nmAtrIdAssunto,
				self::$nmAtrCarga,
				self::$nmAtrDsAssunto,
				self::$nmAtrInLeiSeca,
				self::$nmAtrInDoutrina,
				self::$nmAtrInQuestoes,
		);
		
		$retorno = putElementoArray2NoArray1ComChaves($array1, $array2);
		
		//var_dump($retorno);
		return $retorno;
	}
	static function getAtributosChavePrimaria() {
		$retorno = array (
				self::$nmAtrCdPerfil,
				self::$nmAtrCdMateria,
				self::$nmAtrSqAssunto,
		);
		
		return $retorno;
	}	
	function getDadosRegistroBanco($registrobanco) {
		// as colunas default de voentidade sao incluidas pelo metodo getDadosBanco do voentidade
		$this->cdPerfil = $registrobanco [self::$nmAtrCdPerfil];
		$this->cdMateria = $registrobanco [self::$nmAtrCdMateria];
		$this->sq= $registrobanco [self::$nmAtrSqAssunto];
		
		$this->idAssunto = $registrobanco [self::$nmAtrIdAssunto];
		$this->carga = $registrobanco [self::$nmAtrCarga];
		
		$this->ds = $registrobanco [self::$nmAtrDsAssunto];
		$this->inLeiSeca = $registrobanco [self::$nmAtrInLeiSeca];
		$this->inDoutrina = $registrobanco [self::$nmAtrInDoutrina];
		$this->inQuestoes = $registrobanco [self::$nmAtrInQuestoes];
		
	}
	function getDadosFormulario() {
		$this->cdPerfil = @$_POST [self::$nmAtrCdPerfil];
		$this->cdMateria = @$_POST [self::$nmAtrCdMateria];
		$this->sq= @$_POST[self::$nmAtrSqAssunto];
		
		$this->idAssunto = @$_POST [self::$nmAtrIdAssunto];
		$this->carga = @$_POST [self::$nmAtrCarga];
		
		$this->ds = @$_POST [self::$nmAtrDsAssunto];
		$this->inLeiSeca = @$_POST [self::$nmAtrInLeiSeca];
		$this->inDoutrina = @$_POST [self::$nmAtrInDoutrina];
		$this->inQuestoes = @$_POST [self::$nmAtrInQuestoes];
	}
	function getValorChavePrimaria() {
		return $this->cdPerfil . CAMPO_SEPARADOR . $this->cdMateria . CAMPO_SEPARADOR . $this->sq . CAMPO_SEPARADOR . $this->sqHist;;
	}
	
	function getChavePrimariaVOExplode($array) {
		$this->cdPerfil = $array [0];
		$this->cdMateria = $array [1];
		$this->sq = $array [2];
	}
	
	function toString() {
		$retorno .= $this->cdPerfil . ",";
		$retorno .= $this->cdMateria . ",";
		$retorno .= $this->sq . ",";
		$retorno .= $this->idAssunto . ",";
		return $retorno;
	}
	
	function getMensagemComplementarTelaSucesso() {
		if($this->idAssunto != null){
			$filtro = new filtroManterPerfilMateria(false);
			$vomateria = new vomateria();
			$vomateria->cd = $this->cdMateria;
			$filtro->vomateria = $vomateria;
			$voperfil = new voperfil();
			$voperfil->cd = $this->cdPerfil;
			$filtro->voperfil = $voperfil;
			
			$voperfilmateria = new voperfilmateria();			
			$colecao = $voperfilmateria->dbprocesso->consultarTelaConsulta($filtro);
			$registro = $colecao[0];
			$voperfil->getDadosBanco($registro);
			$vomateria->getDadosBanco($registro);
			
			$retorno = "Perfil($voperfil->descricao) x Mat�ria($vomateria->descricao): " . $this->ds;
		}
		//$retorno = $this->getMensagemComplementarTelaSucessoPadrao ( $dsMateria . ":" . $this->getTituloJSP (), $this->cdFonte, $this->descricao, $this->sqHist );
		return $retorno;
	}
	
}
?>