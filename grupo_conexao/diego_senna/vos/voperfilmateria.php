<?php
include_once (caminho_lib . "voentidade.php");
class voperfilmateria extends voentidade {

	static $nmAtrCdMateria = "mat_cd";
	static $nmAtrCdPerfil = "perf_cd";
	static $nmAtrCarga = "perfmat_carga";
	
	var $cdPerfil = "";
	var $cdMateria = "";
	var $carga = "";
	
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
		return "PERFIL X MAT�RIA (destrinchando o edital)";
	}
	public static function getNmTabela() {
		return "perfil_materia";
	}
	public function getNmClassProcesso() {
		return "dbperfilmateria";
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
		$query = $nmTabela . "." . self::$nmAtrCdMateria. "=" . $this->cdMateria;
		$query .= " AND " . $nmTabela . "." . self::$nmAtrCdPerfil . "=" . $this->cdPerfil; 
		
		return $query;
	}
	
	function getAtributosFilho() {
		$retorno = array (
				self::$nmAtrCdPerfil,
				self::$nmAtrCdMateria,
				self::$nmAtrCarga,
		);
		
		return $retorno;
	}
	function getAtributosChavePrimaria() {
		$retorno = array (
				self::$nmAtrCdPerfil,
				self::$nmAtrCdMateria,
		);
		
		return $retorno;
	}	
	function getDadosRegistroBanco($registrobanco) {
		// as colunas default de voentidade sao incluidas pelo metodo getDadosBanco do voentidade
		$this->cdPerfil = $registrobanco [self::$nmAtrCdPerfil];
		$this->cdMateria = $registrobanco [self::$nmAtrCdMateria];
		$this->carga = $registrobanco [self::$nmAtrCarga];
	}
	function getDadosFormulario() {
		$this->cdPerfil = @$_POST [self::$nmAtrCdPerfil];
		$this->cdMateria = @$_POST [self::$nmAtrCdMateria];
		$this->carga = @$_POST [self::$nmAtrCarga];
	}
	function getValorChavePrimaria() {
		return $this->cdPerfil . CAMPO_SEPARADOR . $this->cdMateria . CAMPO_SEPARADOR . $this->sqHist;
	}
	
	function getChavePrimariaVOExplode($array) {
		$this->cdPerfil = $array [0];
		$this->cdMateria = $array [1];
	}
	
	function toString() {
		$retorno .= $this->cdPerfil . ",";
		$retorno .= $this->cdMateria . ",";
		$retorno .= $this->carga . ",";
		return $retorno;
	}
	
	function getMensagemComplementarTelaSucesso() {
		if($this->carga != null){
			$filtro = new filtroManterPerfilMateria(false);
			$vomateria = new vomateria();
			$vomateria->cd = $this->cdMateria;
			$filtro->vomateria = $vomateria;
			$voperfil = new voperfil();
			$voperfil->cd = $this->cdPerfil;
			$filtro->voperfil = $voperfil;
			
			$colecao = $this->dbprocesso->consultarTelaConsulta($filtro);
			$registro = $colecao[0];
			$voperfil->getDadosBanco($registro);
			$vomateria->getDadosBanco($registro);
			
			$retorno = "Perfil($voperfil->descricao) x Mat�ria($vomateria->descricao): " . complementarCharAEsquerda($this->carga, "0", TAMANHO_CODIGOS_SAFI) . " HORAS";
		}
		//$retorno = $this->getMensagemComplementarTelaSucessoPadrao ( $dsMateria . ":" . $this->getTituloJSP (), $this->cdFonte, $this->descricao, $this->sqHist );
		return $retorno;
	}
	
}
?>