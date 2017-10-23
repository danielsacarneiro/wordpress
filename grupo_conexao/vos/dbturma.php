<?php
include_once (caminho_lib . "dbprocesso.obj.php");
include_once (caminho_util . "bibliotecaFuncoesPrincipal.php");

// .................................................................................................................
class dbturma extends dbprocesso {
	function consultarPorChave($vo, $isHistorico) {
		$nmTabelaTurma = $vo->getNmTabelaEntidade ( $isHistorico );
		$nmTabelaPessoaTurma = vopessoaturma::getNmTabelaStatic ( $isHistorico);
		
		$arrayColunasRetornadas = array (
				$nmTabelaTurma . ".*" 
		);
		
		$groupby = $nmTabelaTurma . "." . voturma::$nmAtrCd;
		
		// o retorno abaixo nao vai no groupby
		$arrayColunasRetornadas [] = "SUM(CASE WHEN $nmTabelaPessoaTurma." . vopessoaturma::$nmAtrValor . " > 0 THEN " . $nmTabelaPessoaTurma . "." . vopessoaturma::$nmAtrValor . " ELSE $nmTabelaTurma." . voturma::$nmAtrValor . " END) AS " . filtroManterTurma::$NM_COL_VALOR_REAL;
		
		$queryFrom .= "\n LEFT JOIN " . $nmTabelaPessoaTurma;
		$queryFrom .= "\n ON " . $nmTabelaTurma . "." . voturma::$nmAtrCd . "=" . $nmTabelaPessoaTurma . "." . vopessoaturma::$nmAtrCdTurma;
		
		$queryWhere = " WHERE ";
		$queryWhere .= $vo->getValoresWhereSQLChave ( $isHistorico );
		$queryWhere .= filtroManter::getSQLGroupby ( $groupby );
		
		return $this->consultarMontandoQuery ( $vo, $arrayColunasRetornadas, $queryFrom, $queryWhere, $isHistorico, true );
		
		// return $this->consultarPorChaveMontandoQuery ( $vo, $arrayColunasRetornadas, $queryFrom, $isHistorico );
	}
	function consultarFiltroManterTurma($filtro) {
		$isHistorico = $filtro->isHistorico ();
		$nmTabelaTurma = voturma::getNmTabelaStatic ( $isHistorico );
		$nmTabelaPessoa = vopessoa::getNmTabelaStatic ( false );
		$nmTabelaPessoaTurma = vopessoaturma::getNmTabelaStatic ( false );
		$nmTabelaPagamento = vopagamento::getNmTabelaStatic ( false );
		$nmTabPagamentoTemp = "TAB_PAGAMENTO_TEMP";
		
		$conector = "";
		if ($isHistorico) {
			$atributosConsulta = $nmTabelaTurma . "." . voturma::$nmAtrSqHist;
			$conector = ",";
		}
		$atributosConsulta .= $conector . $nmTabelaTurma . "." . voturma::$nmAtrCd;
		$atributosConsulta .= "," . $nmTabelaTurma . "." . voturma::$nmAtrDescricao;
		$atributosConsulta .= "," . $nmTabelaTurma . "." . voturma::$nmAtrTipo;
		$atributosConsulta .= "," . $nmTabelaTurma . "." . voturma::$nmAtrValor;
		$atributosConsulta .= "," . $nmTabelaTurma . "." . voturma::$nmAtrDtInicio;
		$atributosConsulta .= "," . $nmTabelaTurma . "." . voturma::$nmAtrDtFim;
		
		$groupby = $atributosConsulta;
		
		// a coluna abaixo fica fora do group by
		//$atributosConsulta .= ",SUM(CASE WHEN $nmTabelaPessoaTurma." . vopessoaturma::$nmAtrValor . " > 0 THEN " . $nmTabelaPessoaTurma . "." . vopessoaturma::$nmAtrValor . " ELSE $nmTabelaTurma." . voturma::$nmAtrValor . " END) AS " . filtroManterTurma::$NM_COL_VALOR_REAL;
		$atributosConsulta .= ",SUM(" . $nmTabelaPessoaTurma . "." . vopessoaturma::$nmAtrValor . "*" . $nmTabelaPessoaTurma . "." . vopessoaturma::$nmAtrNumParcelas . ") AS " . filtroManterTurma::$NM_COL_VALOR_REAL;
		$atributosConsulta .= ",SUM(" . $nmTabelaTurma . "." . voturma::$nmAtrValor . ") AS " . filtroManterTurma::$NM_COL_VALOR_IDEAL;
		$atributosConsulta .= ",SUM(" . $nmTabelaPessoaTurma . "." . vopessoaturma::$nmAtrValor . "*$nmTabPagamentoTemp." . vopagamento::$nmAtrNumParcelaPaga . ") AS " . filtroManterTurma::$NM_COL_VALOR_PAGO;
		$atributosConsulta .= ",COUNT(" . $nmTabelaPessoaTurma . "." . vopessoaturma::$nmAtrCdPessoa . ") AS " . filtroManterTurma::$NM_COL_QTD_ALUNOS;
		
		// sum(valor * (case when verba='salario' then -1 else 1 end))
		
		$querySelect = "SELECT " . $atributosConsulta;
		
		$queryFrom = "\n FROM " . $nmTabelaTurma;
		$queryFrom .= "\n LEFT JOIN " . $nmTabelaPessoaTurma;
		$queryFrom .= "\n ON " . $nmTabelaTurma . "." . voturma::$nmAtrCd . "=" . $nmTabelaPessoaTurma . "." . vopessoaturma::$nmAtrCdTurma;
		$queryFrom .= "\n LEFT JOIN " . $nmTabelaPessoa;
		$queryFrom .= "\n ON " . $nmTabelaPessoa . "." . vopessoa::$nmAtrCd . "=" . $nmTabelaPessoaTurma . "." . vopessoaturma::$nmAtrCdPessoa;
		
		$queryTemp = "SELECT " . vopagamento::$nmAtrCdTurma . "," . vopagamento::$nmAtrCdPessoa . ", COUNT(*) AS " . vopagamento::$nmAtrNumParcelaPaga . " FROM " . $nmTabelaPagamento;
		$queryTemp .= "\n GROUP BY " . vopagamento::$nmAtrCdTurma . "," . vopagamento::$nmAtrCdPessoa;
		
		$queryFrom .= "\n LEFT JOIN ($queryTemp) $nmTabPagamentoTemp";
		$queryFrom .= "\n ON " . $nmTabPagamentoTemp . "." . vopagamento::$nmAtrCdTurma . "=" . $nmTabelaPessoaTurma . "." . vopessoaturma::$nmAtrCdTurma;
		$queryFrom .= "\n AND " . $nmTabPagamentoTemp . "." . vopagamento::$nmAtrCdPessoa . "=" . $nmTabelaPessoaTurma . "." . vopessoaturma::$nmAtrCdPessoa;
		
		$filtro->groupby = $groupby;
		
		// echo $querySelect."<br>";
		// echo $queryFrom;
		
		return $this->consultarFiltro ( $filtro, $querySelect, $queryFrom, true );
	}
	function consultarPessoasTurma($voturma) {
		$nmTabela = vopessoa::getNmTabela ();
		$nmTabelaPessoaTurma = vopessoaturma::getNmTabela ();
		
		$atributosConsulta = $nmTabela . "." . vopessoa::$nmAtrCd;
		$atributosConsulta .= "," . $nmTabela . "." . vopessoa::$nmAtrNome;
		$atributosConsulta .= "," . $nmTabela . "." . vopessoa::$nmAtrDocCPF;
		// $atributosConsulta .= "," . $nmTabelaContrato . "." . vocontrato::$nmAtrCdAutorizacaoContrato;
		
		$querySelect = "SELECT " . $atributosConsulta;
		
		$queryFrom = "\n FROM " . $nmTabela;
		$queryFrom .= "\n INNER JOIN " . $nmTabelaPessoaTurma;
		$queryFrom .= "\n ON " . $nmTabela . "." . vopessoa::$nmAtrCd . "=" . $nmTabelaPessoaTurma . "." . vopessoaturma::$nmAtrCdPessoa;
		
		$filtro = new filtroManterPessoaTurma ( false );
		$filtro->cdTurma = $voturma->cd;
		$filtro->setaFiltroConsultaSemLimiteRegistro ();
		$filtro->cdAtrOrdenacao = vopessoa::$nmAtrNome;
		$filtro->cdOrdenacao = constantes::$CD_ORDEM_CRESCENTE;
		
		// echo $voturma->cd;
		// echo $querySelect."<br>";
		// echo $queryFrom;
		
		return $this->consultarFiltro ( $filtro, $querySelect, $queryFrom, false );
	}
	
	// o incluir eh implementado para nao usar da voentidade
	// por ser mais complexo
	function incluir($voturma) {
		// Start transaction
		$this->cDb->retiraAutoCommit ();
		try {
			$voturma = $this->incluirTurma ( $voturma );
			
			if (! isColecaoVazia ( $voturma->colecaoAlunos )) {
				$voturma->colecaoVOPessoaTurmaAIncluir= $voturma->colecaoAlunos;
				$this->incluirPessoaTurma ($voturma);
			} else {
				throw new excecaoGenerica ( "Não é permitido incluir turma sem alunos." );
			}
			// End transaction
			$this->cDb->commit ();
		} catch ( Exception $e ) {
			$this->tratarExcecao($e);
		}
		
		// var_dump($voturma->colecaoAlunos);
		
		return $voturma;
	}
	function incluirPessoaTurma($voturma) {
		$retorno = false;		
		$colecaoVOPessoaTurma = $voturma->colecaoVOPessoaTurmaAIncluir;
		$cdTurma = $voturma->cd;
		
		if (! isColecaoVazia ( $colecaoVOPessoaTurma )) {
			foreach ( $colecaoVOPessoaTurma as $vopessoaturma ) {
				// $vopeturma = new vopessoaturma ();
				$vopeturma = $vopessoaturma;
				if ($cdTurma != null) {
					$vopeturma->cdTurma = $cdTurma;
				}
				$dbpeturma = $vopeturma->dbprocesso;
				$dbpeturma->cDb = $this->cDb;
				
				if (getStringComoNumero($vopessoaturma->valor) <= 0) {
					//var_dump($vopessoaturma->valor);
					throw new excecaoGenerica ( "Há pelo menos 1 aluno com valor zero." );
				}
				$dbpeturma->incluir ( $vopeturma );
			}
			
			$retorno = true;
		}
		return $retorno;
		// echo "<br>incluiu pessoa vinculo:" . var_dump($vopeturma);
	}
	/*
	 * function excluirPessodsfsdfaTurma($voturma) {
	 * $voturma = new voturma ();
	 * $colecao = $voturma->colecaoAlunos;
	 * foreach ( $colecao as $vopessoaturma ) {
	 * // $vopagamento = new vopagamento();
	 * // so inclui o que ja nao tiver incluido antes
	 * $parcela = $vopagamento->numParcelaPaga;
	 * if (! in_array ( $parcela, $vopessoaturma->colecaoParcelasPagasAnteriores )) {
	 * $db = $vopagamento->dbprocesso;
	 * $db->cDb = $this->cDb;
	 * $db->incluir ( $vopagamento );
	 * }
	 * }
	 *
	 * // deve excluir todos os dados relacionados a pessoa x turma
	 * $nmTabelaPessoaTurma = vopessoaturma::getNmTabelaStatic ( false );
	 * $nmTabelaPagamento = vopagamento::getNmTabelaStatic ( false );
	 *
	 * $chave = $voturma->getValoresWhereSQLChaveSemNomeTabela ( false );
	 * // lemnbrar do comando CASCADE na tabela pagamento, que exclui o pagamento praquela pessoa
	 * $query = " DELETE FROM $nmTabelaPessoaTurma WHERE $nmTabelaPessoaTurma.$chave ";
	 *
	 * // echo $query . "<br>";
	 * return $this->atualizarEntidade ( $query );
	 * }
	 */
	function getColecaoVOPessoaTurmaAIncluirNaTurma($voturma) {
		//$voturma = new voturma ();		
		$colecao = $voturma->colecaoAlunos;
		$retorno = null;
		foreach ( $colecao as $vopessoaturma ) {
			$cdPessoa = $vopessoaturma->cdPessoa;
			if (! in_array ( $cdPessoa, array_keys ($voturma->colecaoAlunosAnteriores))) {
				$retorno [$cdPessoa] = $vopessoaturma;
			}
		}
		return $retorno;
	}
	function getColecaoVOPessoaTurmaARemoverDaTurma($voturma) {
		// $voturma = new voturma ();
		$colecao = $voturma->colecaoAlunosAnteriores;
		
		foreach ( $colecao as $vopessoaturma) {
			$cdPessoa = $vopessoaturma->cdPessoa;
			
			if (! in_array ( $cdPessoa, array_keys ( $voturma->colecaoAlunos ) )) {
				$retorno [$cdPessoa] = $vopessoaturma;
			}
		}
		return $retorno;
	}
	function excluirPessoaTurma($voturma) {		
		$colecaoVOPessoaTurma = $voturma->colecaoVOPessoaTurmaARemover;
		// var_dump($colecaoVOPessoaTurma);
		$retorno = false;
		if (! isColecaoVazia ( $colecaoVOPessoaTurma )) {
			foreach ( $colecaoVOPessoaTurma as $vopessoaturma ) {
				//$vopessoaturma = new vopessoaturma();
				//guarda o sqhistorico da turma
				$vopessoaturma->sqHistTurma = $voturma->sqHist;
				$db = $vopessoaturma->dbprocesso;
				$db->cDb = $this->cDb;
				$db->excluirAPArtirDaTurma ( $vopessoaturma );
			}
			
			$retorno = true;
		}
		
		return $retorno;
	}
	function incluirTurma($voturma) {
		$voturma->cd = $this->getProximoSequencial ( voturma::$nmAtrCd, $voturma );
		
		$arrayAtribRemover = array (
				voentidade::$nmAtrDhInclusao,
				voentidade::$nmAtrDhUltAlteracao 
		);
		
		$query = $this->incluirQuery ( $voturma, $arrayAtribRemover );
		$retorno = $this->cDb->atualizar ( $query );
		
		return $voturma;
	}
	function alterar($voturma) {
		// Start transaction
		$this->cDb->retiraAutoCommit ();
		try {
			$colecaoVOPessoaTurmaARemover = $this->getColecaoVOPessoaTurmaARemoverDaTurma ( $voturma );
			$colecaoVOPessoaTurmaAIncluir = $this->getColecaoVOPessoaTurmaAIncluirNaTurma ( $voturma );
								
			$voturma->colecaoVOPessoaTurmaARemover = $colecaoVOPessoaTurmaARemover;
			$voturma->colecaoVOPessoaTurmaAIncluir= $colecaoVOPessoaTurmaAIncluir;
 
			parent::alterar ( $voturma );
			$fezExclusao= $this->excluirPessoaTurma ( $voturma);
			$fezInclusao= $this->incluirPessoaTurma ( $voturma);
						
			// End transaction
			$this->cDb->commit ();
			
			if(!$fezExclusao && !$fezInclusao){
				$voturma->setMensagemComplementar("Nenhuma alteração nos alunos foi realizada.");
			}
			
		} catch ( Exception $e ) {			
			$this->tratarExcecao($e);
		}
		
		return $voturma;
	}
	function excluir($voturma) {
		// Start transaction
		$this->cDb->retiraAutoCommit ();
		try {
			$permiteExcluirPrincipal = $this->permiteExclusaoPrincipal ( $voturma );
						
			parent::excluir ( $voturma );
			$voturma->colecaoVOPessoaTurmaARemover = $voturma->colecaoAlunos;
			$this->excluirPessoaTurma ( $voturma);
			
			// End transaction
			$this->cDb->commit ();
		} catch ( Exception $e ) {
			$this->tratarExcecao($e);
		}
		
		return $voturma;
	}
	
	/*
	 * function incluirSQL($voturma){
	 * $arrayAtribRemover = array(
	 * voturma::$nmAtrDhInclusao,
	 * voturma::$nmAtrDhUltAlteracao
	 * );
	 *
	 * if($voturma->cd == null || $voturma->cd == ""){
	 * $voturma->cd = $this->getProximoSequencial(voturma::$nmAtrCd, $voturma);
	 * }
	 *
	 * //$voturma->cd = $this->getProximoSequencial(voturma::$nmAtrCd, $voturma);
	 *
	 * return $this->incluirQuery($voturma, $arrayAtribRemover);
	 * }
	 */
	function getSQLValuesInsert($voturma) {
		$retorno = "";
		$retorno .= $this->getVarComoNumero ( $voturma->cd ) . ",";
		$retorno .= $this->getVarComoString ( $voturma->descricao ) . ",";
		$retorno .= $this->getVarComoNumero($voturma->tipo) . ",";
		$retorno .= $this->getVarComoDecimal ( $voturma->valor ) . ",";
		$retorno .= $this->getVarComoData ( $voturma->dtInicio ) . ",";
		$retorno .= $this->getVarComoData ( $voturma->dtFim ) . ",";
		$retorno .= $this->getVarComoString ( $voturma->obs );
		
		$retorno .= $voturma->getSQLValuesInsertEntidade ();
		
		return $retorno;
	}
	function getSQLValuesUpdate($vo) {
		$retorno = "";
		$sqlConector = "";
		
		if ($vo->descricao != null) {
			$retorno .= $sqlConector . voturma::$nmAtrDescricao . " = " . $this->getVarComoString ( $vo->descricao );
			$sqlConector = ",";
		}
		
		if ($vo->valor != null) {
			$retorno .= $sqlConector . voturma::$nmAtrValor . " = " . $this->getVarComoDecimal ( $vo->valor );
			$sqlConector = ",";
		}
		
		if ($vo->dtInicio != null) {
			$retorno .= $sqlConector . voturma::$nmAtrDtInicio . " = " . $this->getVarComoData ( $vo->dtInicio );
			$sqlConector = ",";
		}
		
		if ($vo->dtFim != null) {
			$retorno .= $sqlConector . voturma::$nmAtrDtFim . " = " . $this->getVarComoData ( $vo->dtFim );
			$sqlConector = ",";
		}
		
		if ($vo->obs != null) {
			$retorno .= $sqlConector . voturma::$nmAtrObservacao . " = " . $this->getVarComoString ( $vo->obs );
			$sqlConector = ",";
		}
		
		$retorno = $retorno . $sqlConector . $vo->getSQLValuesUpdate ();
		
		return $retorno;
	}
}
?>