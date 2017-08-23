<?php
include_once (caminho_lib . "voentidade.php");
include_once(caminho_funcoes."turma/bibliotecaTurma.php");

class voturma extends voentidade {
	static $NUM_MAXIMO_ALUNO = 15;
	
	static $NM_DIV_COLECAO_ALUNOS = "NM_DIV_COLECAO_ALUNOS";
	static $ID_REQ_COLECAO_ALUNOS = "ID_REQ_COLECAO_ALUNOS";
	static $ID_REQ_VALOR_TOTAL = "ID_REQ_VALOR_TOTAL_TURMA";
	static $ID_REQ_DURACAO = "ID_REQ_DURACAO";
	static $nmAtrCd = "tu_cd";
	static $nmAtrDescricao = "tu_ds";
	static $nmAtrValor = "tu_valor";
	static $nmAtrObservacao = "tu_obs";
	static $nmAtrDtInicio = "tu_dtinicio";
	static $nmAtrDtFim = "tu_dtfim";

	var $cd = "";
	var $descricao = "";
	var $valor = "";
	var $obs = "";
	var $dtInicio = "";
	var $dtFim = "";
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
				self::$nmAtrDtInicio,
				self::$nmAtrDtFim,
				self::$nmAtrObservacao 
		);
		
		return $retorno;
	}
	function getDadosRegistroBanco($registrobanco) {
		// as colunas default de voentidade sao incluidas pelo metodo getDadosBanco do voentidade
		$this->cd = $registrobanco [self::$nmAtrCd];
		$this->descricao = $registrobanco [self::$nmAtrDescricao];
		$this->valor = $registrobanco [self::$nmAtrValor];
		
		$this->dtInicio = $registrobanco [self::$nmAtrDtInicio];
		$this->dtFim = $registrobanco [self::$nmAtrDtFim];
		
		$this->obs = $registrobanco [self::$nmAtrObservacao];	
	}
	function getDadosChaveOperacaoMaisComplexa() {
		// as colunas default de voentidade sao incluidas pelo metodo getDadosBanco do voentidade
		$recordset = consultarPessoasTurmaPorVOTurma($this);
		$this->setColecaoAlunosRegistroBanco($recordset);
	}
	function getDadosFormulario() {
		$this->cd = @$_POST [self::$nmAtrCd];
		$this->descricao = strtoupper(@$_POST [self::$nmAtrDescricao]);
		$this->valor = @$_POST [self::$nmAtrValor];
		
		$this->dtInicio = @$_POST[self::$nmAtrDtInicio];
		$this->dtFim = @$_POST[self::$nmAtrDtFim];
		
		$this->obs = @$_POST [self::$nmAtrObservacao];
		
		$this->setColecaoAlunosFormulario();
		
		/*if (existeObjetoSessao ( self::$ID_REQ_COLECAO_ALUNOS )) {
			// echo "tem colecao alunos";
			$this->colecaoAlunos = getObjetoSessao ( self::$ID_REQ_COLECAO_ALUNOS );
		} else {
			throw new excecaoGenerica ( "� preciso ter alunos para criar uma turma." ); // echo "NAO tem colecao alunos";
		}*/
	}
	function setColecaoAlunosFormulario() {
		$colecaoValores = @$_POST [vopessoaturma::$nmAtrValor];
		$colecaoNumParcelas = @$_POST [vopessoaturma::$nmAtrNumParcelas];
		$colecaoCdPessoas = @$_POST [vopessoaturma::$nmAtrCdPessoa];
		
		//var_dump($colecaoValores);
		
		$retorno = null;
		if (!isColecaoVazia($colecaoCdPessoas)) {
			$retorno = array ();			
			for ( $i = 0; $i < count($colecaoCdPessoas);$i++) {				
				$cdPessoa = $colecaoCdPessoas[$i];
				$vopessoaturma = new vopessoaturma();
				$vopessoaturma->cdPessoa = $cdPessoa;
				
				$valor = $colecaoValores[$i];
				$numParcela = $colecaoNumParcelas[$i];
				//se nao colocar valor, vai ser o da turma
				if($valor == null || $valor == ""){
					$valor = $this->valor;
					$valor = $valor/$numParcela;
				}
				
				$vopessoaturma->valor = $valor;
				$vopessoaturma->numParcelas = $numParcela;
				
				//echo "cdPEssoa=$cdPessoa, valor=$colecaoValores[$i], numPArcelas=$colecaoNumParcelas[$i] <br>";
				
				$retorno [] = $vopessoaturma;
			}
		}
		//var_dump($retorno);
		$this->colecaoAlunos = $retorno;
	}
	function setColecaoAlunosRegistroBanco($colecao) {
		$retorno = null;
		if ($colecao != null) {
			$retorno = array ();
			foreach ( $colecao as $registrobanco ) {
				$vopessoaturma = new vopessoaturma();
				$vopessoaturma->getDadosBanco ( $registrobanco );				
			
				$vopessoa = new vopessoa ();
				$vopessoa->getDadosBanco ( $registrobanco );
				$voturma = new voturma ();
				$voturma->getDadosBanco ( $registrobanco );
				
				// cria em execucao um OTD
				$vopessoaturma->vopessoa = $vopessoa;
				$vopessoaturma->voturma = $voturma;				
				
				$retorno [$vopessoaturma->cdPessoa] = $vopessoaturma;
			}
		}
		//var_dump($retorno);
		$this->colecaoAlunos = $retorno;
	}
	
	function getValorChavePrimaria() {
		return $this->cd;
	}
	function getChavePrimariaVOExplode($array){
		$this->cd = $array [0];	
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