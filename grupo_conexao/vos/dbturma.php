<?php
include_once (caminho_lib . "dbprocesso.obj.php");
include_once (caminho_util . "bibliotecaFuncoesPrincipal.php");

// .................................................................................................................
class dbturma extends dbprocesso {
	function consultarPorChave($vo, $isHistorico) {
		$nmTabelaTurma = $vo->getNmTabelaEntidade ( $isHistorico );
		$nmTabelaPessoaTurma = vopessoaturma::getNmTabelaStatic ( false );
		
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
		$nmTabelaTurma = voturma::getNmTabelaStatic ($isHistorico);
		$nmTabelaPessoa = vopessoa::getNmTabelaStatic ( false);
		$nmTabelaPessoaTurma = vopessoaturma::getNmTabelaStatic ( false );
		$nmTabelaPagamento = vopagamento::getNmTabelaStatic ( false );
		$nmTabPagamentoTemp = "TAB_PAGAMENTO_TEMP";
		
		$conector = "";
		if($isHistorico){
			$atributosConsulta = $nmTabelaTurma . "." . voturma::$nmAtrSqHist;
			$conector = ",";
		}
		$atributosConsulta .= $conector . $nmTabelaTurma . "." . voturma::$nmAtrCd;
		$atributosConsulta .= "," . $nmTabelaTurma . "." . voturma::$nmAtrDescricao;
		$atributosConsulta .= "," . $nmTabelaTurma . "." . voturma::$nmAtrValor;
		$atributosConsulta .= "," . $nmTabelaTurma . "." . voturma::$nmAtrDtInicio;
		$atributosConsulta .= "," . $nmTabelaTurma . "." . voturma::$nmAtrDtFim;
		
		$groupby = $atributosConsulta;
		
		// a coluna abaixo fica fora do group by
		$atributosConsulta .= ",SUM(CASE WHEN $nmTabelaPessoaTurma." . vopessoaturma::$nmAtrValor . " > 0 THEN " . $nmTabelaPessoaTurma . "." . vopessoaturma::$nmAtrValor . " ELSE $nmTabelaTurma." . voturma::$nmAtrValor . " END) AS " . filtroManterTurma::$NM_COL_VALOR_REAL;
		$atributosConsulta .= ",SUM(" . $nmTabelaPessoaTurma . "." . vopessoaturma::$nmAtrValor . "*" . $nmTabelaPessoaTurma . "." . vopessoaturma::$nmAtrNumParcelas . ") AS " . filtroManterTurma::$NM_COL_VALOR_REAL;
		$atributosConsulta .= ",SUM(" . $nmTabelaTurma . "." . voturma::$nmAtrValor . ") AS " . filtroManterTurma::$NM_COL_VALOR_IDEAL;
		$atributosConsulta .= ",SUM(" . $nmTabelaPessoaTurma . "." . vopessoaturma::$nmAtrValor . "*$nmTabPagamentoTemp.".vopagamento::$nmAtrNumParcelaPaga.") AS " . filtroManterTurma::$NM_COL_VALOR_PAGO;
		$atributosConsulta .= ",COUNT(" . $nmTabelaPessoaTurma . "." . vopessoaturma::$nmAtrCdPessoa . ") AS " . filtroManterTurma::$NM_COL_QTD_ALUNOS;
		
		// sum(valor * (case when verba='salario' then -1 else 1 end))
		
		$querySelect = "SELECT " . $atributosConsulta;
		
		$queryFrom = "\n FROM " . $nmTabelaTurma;
		$queryFrom .= "\n LEFT JOIN " . $nmTabelaPessoaTurma;
		$queryFrom .= "\n ON " . $nmTabelaTurma . "." . voturma::$nmAtrCd . "=" . $nmTabelaPessoaTurma . "." . vopessoaturma::$nmAtrCdTurma;
		$queryFrom .= "\n LEFT JOIN " . $nmTabelaPessoa;
		$queryFrom .= "\n ON " . $nmTabelaPessoa . "." . vopessoa::$nmAtrCd . "=" . $nmTabelaPessoaTurma . "." . vopessoaturma::$nmAtrCdPessoa;
			
		$queryTemp = "SELECT ".vopagamento::$nmAtrCdTurma.",".vopagamento::$nmAtrCdPessoa.", COUNT(*) AS ".vopagamento::$nmAtrNumParcelaPaga." FROM " . $nmTabelaPagamento;
		$queryTemp .= "\n GROUP BY ".vopagamento::$nmAtrCdTurma.",".vopagamento::$nmAtrCdPessoa;
	
		$queryFrom .= "\n LEFT JOIN ($queryTemp) $nmTabPagamentoTemp";
		$queryFrom .= "\n ON " . $nmTabPagamentoTemp. "." . vopagamento::$nmAtrCdTurma . "=" . $nmTabelaPessoaTurma . "." . vopessoaturma::$nmAtrCdTurma;
		$queryFrom .= "\n AND " . $nmTabPagamentoTemp. "." . vopagamento::$nmAtrCdPessoa . "=" . $nmTabelaPessoaTurma . "." . vopessoaturma::$nmAtrCdPessoa;				
		
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
				$this->incluirPessoaTurma ( $voturma );
			} else {
				throw new excecaoGenerica ( "Não é permitido incluir turma sem alunos." );
			}
			// End transaction
			$this->cDb->commit ();
		} catch ( Exception $e ) {
			$this->cDb->rollback ();
			throw new Exception ( $e->getMessage () );
		}
		
		// var_dump($voturma->colecaoAlunos);
		
		return $voturma;
	}
	function incluirPessoaTurma($voturma) {
		foreach ( $voturma->colecaoAlunos as $vopessoaturma ) {
			// $vopeturma = new vopessoaturma ();
			$vopeturma = $vopessoaturma;
			$vopeturma->cdTurma = $voturma->cd;
			$dbpeturma = $vopeturma->dbprocesso;
			$dbpeturma->cDb = $this->cDb;
			
			if ($vopessoaturma->valor <= 0) {
				throw new excecaoGenerica ( "Valor do aluno " . $vopessoaturma->vopessoa->toString () . " não pode ser zero." );
			}
			$dbpeturma->incluir ( $vopeturma );
		}
		// echo "<br>incluiu pessoa vinculo:" . var_dump($vopeturma);
	}
	// se for alteracao, mantem os registros das pessoas anteriores
	function excluirPessoaTurma($voturma, $isAlteracao) {
		if ($isAlteracao) {
			$this->excluirPessoaTurmaAlteracao ( $voturma );
		} else {
			$this->excluirPessoaTurmaExclusao ( $voturma );
		}
	}
	function excluirPessoaTurmaExclusao($voturma) {
		// deve excluir todos os dados relacionados a pessoa x turma
		$nmTabelaPessoaTurma = vopessoaturma::getNmTabelaStatic ( false );
		$nmTabelaPagamento = vopagamento::getNmTabelaStatic ( false );
		
		$chave = $voturma->getValoresWhereSQLChaveSemNomeTabela( false );
		//lemnbrar do comando CASCADE na tabela pagamento, que exclui o pagamento praquela pessoa
		$query = " DELETE FROM $nmTabelaPessoaTurma WHERE $nmTabelaPessoaTurma.$chave ";		
		
		 //echo $query . "<br>";
		return $this->atualizarEntidade ( $query );
	}
	function excluirPessoaTurmaAlteracao($voturma) {
		// deleta apenas a referencia pessoa x turma
		// os dados de pagamento permanecem
		$vo = new vopessoaturma ();
		$nmTabela = $vo->getNmTabelaEntidade ( false );
		$query = "DELETE FROM " . $nmTabela;
		$query .= "\n WHERE " . vopessoaturma::$nmAtrCdTurma . " = " . $voturma->cd;
		
		// echo $query;
		return $this->atualizarEntidade ( $query );
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
			$this->excluirPessoaTurma ( $voturma , true);
			$this->incluirPessoaTurma ( $voturma );
			
			$voturma = parent::alterar ( $voturma );
			
			// End transaction
			$this->cDb->commit ();
		} catch ( Exception $e ) {
			$this->cDb->rollback ();
			throw new Exception ( $e->getMessage () );
		}
		
		return $voturma;
	}
	function excluir($voturma) {
		// Start transaction
		$this->cDb->retiraAutoCommit ();
		try {
			$permiteExcluirPrincipal = $this->permiteExclusaoPrincipal ( $voturma );
			// so exclui os relacionamentos se a exclusao for de registro historico
			// caso contrario , apenas desativa o voprincipal			
			if ($permiteExcluirPrincipal) {
				$this->excluirPessoaTurma ( $voturma, false );				
			}			
			parent::excluir ( $voturma );
			// End transaction
			$this->cDb->commit ();			
		} catch ( Exception $e ) {
			$this->cDb->rollback ();
			throw new Exception ( $e->getMessage () );
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