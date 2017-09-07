<?php
include_once (caminho_lib . "voentidade.php");
include_once (caminho_funcoes . "turma/bibliotecaTurma.php");
class voturma extends voentidade {
	static $NUM_MAXIMO_ALUNO = 15;
	static $NM_DIV_COLECAO_ALUNOS = "NM_DIV_COLECAO_ALUNOS";
	static $ID_REQ_COLECAO_ALUNOS = "ID_REQ_COLECAO_ALUNOS";
	static $ID_REQ_COLECAO_ALUNOS_ANTERIOR = "ID_REQ_COLECAO_ALUNOS_ANTERIOR";
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
	var $colecaoAlunosAnteriores = "";
	var $colecaoVOPessoaTurmaARemover = "";
	var $colecaoVOPessoaTurmaAIncluir = "";
	
	// ...............................................................
	// Funções ( Propriedades e métodos da classe )
	function __construct() {
		parent::__construct ();
		$this->temTabHistorico = true;
		
		$class = self::getNmClassProcesso ();
		$this->dbprocesso = new $class ();
		
		// retira os atributos padrao que nao possui
		$arrayAtribRemover = array (
				self::$nmAtrDhInclusao 
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
		if ($isHistorico){
			$query .= " AND " . $nmTabela . "." . self::$nmAtrSqHist . "=" . $this->sqHist;
		}
		
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
	function getAtributosChavePrimaria() {
		$retorno = array (
				self::$nmAtrCd 
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
		$this->getESetColecaoAlunosRegistroBanco();
	}
	function getESetColecaoAlunosRegistroBanco() {
		// as colunas default de voentidade sao incluidas pelo metodo getDadosBanco do voentidade
		//echo "historico do voturma � ". $this->sqHist;
		$recordset = consultarPessoasTurmaPorVOTurma ( $this );
		$this->setColecaoAlunosRegistroBanco ( $recordset );
	}
	function getDadosFormulario() {
		$this->cd = @$_POST [self::$nmAtrCd];
		$this->descricao = strtoupper ( @$_POST [self::$nmAtrDescricao] );
		$this->valor = @$_POST [self::$nmAtrValor];
		
		$this->dtInicio = @$_POST [self::$nmAtrDtInicio];
		$this->dtFim = @$_POST [self::$nmAtrDtFim];
		
		$this->obs = @$_POST [self::$nmAtrObservacao];		
		
		$cdFuncao = @$_POST ["funcao"];
		
		//se ele pegar do formulario, sobrescreve com as informacoes do formulario
		//na funcao EXCLUIR isso nao se deseja
		if($cdFuncao != constantes::$CD_FUNCAO_EXCLUIR){
			$this->setColecaoAlunosFormulario ();
		}else{
			//pega da base
			$this->getESetColecaoAlunosRegistroBanco();
			//var_dump($this->colecaoAlunos);
		}
		
		$this->colecaoAlunosAnteriores = getObjetoSessao(self::$ID_REQ_COLECAO_ALUNOS_ANTERIOR);
	}
	
	function setColecaoAlunosFormulario() {
		$colecaoValores = @$_POST [vopessoaturma::$nmAtrValor];
		$colecaoNumParcelas = @$_POST [vopessoaturma::$nmAtrNumParcelas];
		$colecaoCdPessoas = @$_POST [vopessoaturma::$nmAtrCdPessoa];
		$colecaoDhAlteracao = @$_POST [vopessoaturma::$ID_REQ_COLECAO_DHALTERACAO];
		
		$retorno = null;
		if (! isColecaoVazia ( $colecaoCdPessoas )) {
			$retorno = array ();
			for($i = 0; $i < count ( $colecaoCdPessoas ); $i ++) {
				$cdPessoa = $colecaoCdPessoas [$i];
				$vopessoaturma = new vopessoaturma ();
				$vopessoaturma->cdPessoa = $cdPessoa;
				
				$valor = $colecaoValores [$i];
				$numParcela = $colecaoNumParcelas [$i];
				$dhAlteracao = $colecaoDhAlteracao[$i];
				// se nao colocar valor, vai ser o da turma
				if ($valor == null || $valor == "") {
					$valor = $this->valor;
					$valor = $valor / $numParcela;
				}
				
				$vopessoaturma->valor = $valor;
				$vopessoaturma->numParcelas = $numParcela;
				$vopessoaturma->dhUltAlteracao = $dhAlteracao;
				
				$retorno [$cdPessoa] = $vopessoaturma;
			}
		}else{
			throw new excecaoGenerica("Selecione pelo menos 1 aluno.");
		}
		/*echo "retorno";
		var_dump($retorno);
		echo "<br><br>";*/
		$this->colecaoAlunos = $retorno;
	}
	function setColecaoAlunosRegistroBanco($colecao) {
		$retorno = null;
		if ($colecao != null) {
			$retorno = array ();
			foreach ( $colecao as $registrobanco ) {
				$vopessoaturma = new vopessoaturma ();
				$vopessoaturma->getDadosBanco ( $registrobanco );
				//echo "hist consultar banco:" . $vopessoaturma->sqHist;
								
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
		// var_dump($retorno);
		$this->colecaoAlunos = $retorno;
	}
	function getValorChavePrimaria() {
		return $this->cd . constantes::$CD_CAMPO_SEPARADOR . $this->sqHist;
	}
	function getChavePrimariaVOExplode($array) {
		$this->cd = $array [0];
		$this->sqHist = $array [1];
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