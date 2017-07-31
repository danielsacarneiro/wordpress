<?php
include_once (caminho_lib . "voentidade.php");
class voturma extends voentidade {
	static $NM_DIV_COLECAO_ALUNOS = "NM_DIV_COLECAO_ALUNOS";
	static $ID_REQ_COLECAO_ALUNOS = "ID_REQ_COLECAO_ALUNOS";
	static $nmAtrCd = "tu_cd";
	static $nmAtrDescricao = "tu_ds";
	static $nmAtrValor = "tu_valor";
	static $nmAtrObservacao = "tu_obs";
	var $cd = "";
	var $descricao = "";
	var $valor = "";
	var $obs = "";
	var $colecaoAlunos = "";
	
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
		return "TURMA";
	}
	public static function getNmTabela() {
		return "turma";
	}
	public function getNmClassProcesso() {
		return "dbturma";
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
	function getDadosRegistroBancoPorChave($registrobanco) {
		// as colunas default de voentidade sao incluidas pelo metodo getDadosBanco do voentidade
		$this->getDadosRegistroBanco($registrobanco);
		$this->setColecaoAlunosRegistroBanco($this->dbprocesso->consultarPessoasTurma($this));
	}
	function getDadosFormulario() {
		$this->cd = @$_POST [self::$nmAtrCd];
		$this->descricao = @$_POST [self::$nmAtrDescricao];
		$this->valor = @$_POST [self::$nmAtrValor];
		$this->obs = @$_POST [self::$nmAtrObservacao];
		
		if (existeObjetoSessao ( self::$ID_REQ_COLECAO_ALUNOS )) {
			// echo "tem colecao alunos";
			$this->colecaoAlunos = getObjetoSessao ( self::$ID_REQ_COLECAO_ALUNOS );
		} else {
			throw new excecaoGenerica ( "� preciso ter alunos para criar uma turma." ); // echo "NAO tem colecao alunos";
		}
	}
	function setColecaoAlunosRegistroBanco($colecao) {
		$retorno = null;
		if ($colecao != null) {
			$retorno = array ();
			foreach ( $colecao as $registrobanco ) {
				$vopessoa = new vopessoa();
				$vopessoa->getDadosBanco ( $registrobanco );
				$retorno [] = $vopessoa->cd;
			}
		}
		//var_dump($retorno);
		$this->colecaoAlunos = $retorno;
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
		if ($this->colecaoAlunos != null) {
			$retorno .= "<br> Qtd. Alunos na turma: " . count ( $this->colecaoAlunos );
		}
		return $retorno;
	}
}
?>