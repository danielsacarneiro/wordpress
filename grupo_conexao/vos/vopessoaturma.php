<?php
include_once (caminho_lib . "voentidade.php");
class vopessoaturma extends voentidade {

	static $ID_REQ_VALOR_TOTAL = "ID_REQ_VALOR_TOTAL";
	static $ID_REQ_COLECAO_PARCELAS_PAGAS = "ID_REQ_COLECAO_PARCELAS_PAGAS";
	
	static $nmAtrCdPessoa = "pe_cd";
	static $nmAtrCdTurma = "tu_cd";
	static $nmAtrValor = "pt_valor";
	static $nmAtrNumParcelas = "pt_numparcelas";
	static $nmAtrObservacao = "pt_obs";
	var $cdPessoa = "";
	var $cdTurma = "";
	var $valor = "";
	var $numParcelas = "";
	var $colecaoParcelasPagas = "";
	var $colecaoParcelasPagasAnteriores = "";
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
		//padrao
		$this->numParcelas = 1;
		$this->valor = 0;
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
				self::$nmAtrNumParcelas,
				self::$nmAtrValor,
				self::$nmAtrObservacao 
		);
		
		return $retorno;
	}
	function getAtributosChavePrimaria() {
		$retorno = array (
				self::$nmAtrCdPessoa,
				self::$nmAtrCdTurma
		);
		
		return $retorno;
	}
	
	function getDadosRegistroBanco($registrobanco) {
		// as colunas default de voentidade sao incluidas pelo metodo getDadosBanco do voentidade
		$this->cdPessoa = $registrobanco [self::$nmAtrCdPessoa];
		$this->cdTurma = $registrobanco [self::$nmAtrCdTurma];
		$this->valor = $registrobanco [self::$nmAtrValor];
		$this->numParcelas = $registrobanco [self::$nmAtrNumParcelas];
		if($this->numParcelas == null){
			$this->numParcelas = 1;
		}
		
		$this->obs = $registrobanco [self::$nmAtrObservacao];
	}
	function getDadosChaveOperacaoMaisComplexa() {
		// as colunas default de voentidade sao incluidas pelo metodo getDadosBanco do voentidade
		$recordset = consultarPagamentoPessoa($this);
		$this->setColecaoParcelasPagasRegistroBanco($recordset);
	}	
	function setColecaoParcelasPagasRegistroBanco($colecao) {
		$retorno = null;
		if ($colecao != null) {
			
			$retorno = array ();
			foreach ( $colecao as $registrobanco ) {
				$vo = new vopagamento();
				$vo->getDadosBanco ( $registrobanco );							
				$retorno [$vo->numParcelaPaga] = $vo;
			}
		}
		//var_dump($retorno);
		$this->colecaoParcelasPagas = $retorno;
	}	
	function getDadosFormulario() {
		$this->cdPessoa = @$_POST [self::$nmAtrCdPessoa];
		$this->cdTurma = @$_POST [self::$nmAtrCdTurma];
		$this->valor = @$_POST [self::$nmAtrValor];
		if($this->valor == null){
			$this->valor = 0;
		}
		$this->numParcelas = $_POST[self::$nmAtrNumParcelas];
		$this->obs = @$_POST [self::$nmAtrObservacao];
		
		//quando vier da pagina de pagamento
		$this->setColecaoParcelasPagas();
		
		//para guardar pra verificacao futura
		$this->setColecaoParcelasPagasAnteriores();
	}
	function setColecaoParcelasPagasAnteriores() {
		$strPArcelasPAgas = @$_POST [vopessoaturma::$ID_REQ_COLECAO_PARCELAS_PAGAS];		
		$colecao = explode(constantes::$CD_CAMPO_SEPARADOR_ARRAY,$strPArcelasPAgas);
		//var_dump($colecaoPagamento);
		$this->colecaoParcelasPagasAnteriores = $colecao;
	}
	function setColecaoParcelasPagas() {
		$colecaoPagamento = @$_POST [vopagamento::$nmAtrNumParcelaPaga];
		//var_dump($colecaoPagamento);		
		$retorno = null;
		if (!isColecaoVazia($colecaoPagamento)) {
			$retorno = array ();
			for ( $i = 0; $i < count($colecaoPagamento);$i++) {
				$numParcela = $colecaoPagamento[$i];
				$vopagamento = new vopagamento();
				$vopagamento->cdPessoa = $this->cdPessoa;
				$vopagamento->cdTurma = $this->cdTurma;
				$vopagamento->numParcelaPaga = $numParcela;
				
				$retorno [$numParcela] = $vopagamento;
			}
		}
		//var_dump($retorno);
		$this->colecaoParcelasPagas = $retorno;
	}
	function isParcelaPaga($parcela) {
		$retorno = false;		
		if(!isColecaoVazia($this->colecaoParcelasPagas)){
			$retorno = in_array($parcela, array_keys($this->colecaoParcelasPagas));
		}
		return $retorno;
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