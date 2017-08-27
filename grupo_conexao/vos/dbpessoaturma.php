<?php
include_once (caminho_lib . "dbprocesso.obj.php");
class dbpessoaturma extends dbprocesso {
	static $NM_FUNCAO_PAGAMENTO = "pagamento";
	function consultarPorChave($vo, $isHistorico) {
		$nmTabelaPessoaTurma = $vo->getNmTabelaEntidade ( $isHistorico );
		$nmTabelaTurma = voturma::getNmTabelaStatic ( $isHistorico );
		$nmTabelaPessoa = vopessoa::getNmTabelaStatic ( $isHistorico );
		$arrayColunasRetornadas = array (
				$nmTabelaPessoaTurma . ".*",
				$nmTabelaPessoa . "." . vopessoa::$nmAtrNome,
				$nmTabelaTurma . "." . voturma::$nmAtrDescricao,
				"COALESCE ($nmTabelaPessoaTurma." . vopessoaturma::$nmAtrValor . ",$nmTabelaTurma." . voturma::$nmAtrValor . ") AS " . vopessoaturma::$nmAtrValor 
		);
		
		$queryFrom .= "\n INNER JOIN " . $nmTabelaTurma;
		$queryFrom .= "\n ON " . $nmTabelaTurma . "." . voturma::$nmAtrCd . "=" . $nmTabelaPessoaTurma . "." . vopessoaturma::$nmAtrCdTurma;
		$queryFrom .= "\n INNER JOIN " . $nmTabelaPessoa;
		$queryFrom .= "\n ON " . $nmTabelaPessoa . "." . vopessoa::$nmAtrCd . "=" . $nmTabelaPessoaTurma . "." . vopessoaturma::$nmAtrCdPessoa;
		
		return $this->consultarPorChaveMontandoQuery ( $vo, $arrayColunasRetornadas, $queryFrom, $isHistorico );
	}
	function consultarFiltroManterPessoaTurma($filtro) {
		$nmTabelaTurma = voturma::getNmTabelaStatic ( $filtro->isHistorico () );
		$nmTabelaPessoa = vopessoa::getNmTabelaStatic ( false );
		$nmTabelaPessoaTurma = vopessoaturma::getNmTabelaStatic ( false );
		$nmTabelaPagamento = vopagamento::getNmTabelaStatic ( false );
		
		$atributosConsulta = $nmTabelaPessoaTurma . "." . vopessoaturma::$nmAtrCdTurma;
		$atributosConsulta .= "," . $nmTabelaPessoaTurma . "." . vopessoaturma::$nmAtrCdPessoa;
		
		$groupby = $atributosConsulta;
		
		$atributosConsulta .= "," . $nmTabelaPessoaTurma . "." . vopessoaturma::$nmAtrValor;
		$atributosConsulta .= "," . $nmTabelaPessoaTurma . "." . vopessoaturma::$nmAtrNumParcelas;
		$atributosConsulta .= "," . $nmTabelaTurma . "." . voturma::$nmAtrDescricao;
		$atributosConsulta .= "," . $nmTabelaTurma . "." . voturma::$nmAtrInDesativado;
		$atributosConsulta .= "," . $nmTabelaPessoa . "." . vopessoa::$nmAtrNome;
		$atributosConsulta .= "," . $nmTabelaPessoaTurma . "." . vopessoaturma::$nmAtrValor . "*COUNT($nmTabelaPagamento." . vopagamento::$nmAtrNumParcelaPaga . ") AS " . filtroManterPessoaTurma::$NM_COL_VALOR_PAGO;
		$atributosConsulta .= "," . $nmTabelaPessoaTurma . "." . vopessoaturma::$nmAtrValor . "*" . $nmTabelaPessoaTurma . "." . vopessoaturma::$nmAtrNumParcelas . " AS " . filtroManterPessoaTurma::$NM_COL_VALOR_TOTAL;
		
		$querySelect = "SELECT " . $atributosConsulta;
		
		$queryFrom = "\n FROM " . $nmTabelaTurma;
		$queryFrom .= "\n INNER JOIN " . $nmTabelaPessoaTurma;
		$queryFrom .= "\n ON " . $nmTabelaTurma . "." . voturma::$nmAtrCd . "=" . $nmTabelaPessoaTurma . "." . vopessoaturma::$nmAtrCdTurma;
		$queryFrom .= "\n LEFT JOIN " . $nmTabelaPessoa;
		$queryFrom .= "\n ON " . $nmTabelaPessoa . "." . vopessoa::$nmAtrCd . "=" . $nmTabelaPessoaTurma . "." . vopessoaturma::$nmAtrCdPessoa;
		
		$queryFrom .= "\n LEFT JOIN $nmTabelaPagamento";
		$queryFrom .= "\n ON " . $nmTabelaPagamento . "." . vopagamento::$nmAtrCdTurma . "=" . $nmTabelaPessoaTurma . "." . vopessoaturma::$nmAtrCdTurma;
		$queryFrom .= "\n AND " . $nmTabelaPagamento . "." . vopagamento::$nmAtrCdPessoa . "=" . $nmTabelaPessoaTurma . "." . vopessoaturma::$nmAtrCdPessoa;
		
		$filtro->groupby = $groupby;
		
		// echo $querySelect."<br>";
		// echo $queryFrom;
		
		return $this->consultarFiltro ( $filtro, $querySelect, $queryFrom, true );
	}
	function consultarFiltroManterPagamento($filtro) {
		$nmTabela = vopagamento::getNmTabelaStatic ( $filtro->isHistorico () );
		
		$atributosConsulta .= "* ";
		
		$querySelect = "SELECT " . $atributosConsulta;
		
		$queryFrom = "\n FROM " . $nmTabela;
		
		return $this->consultarFiltro ( $filtro, $querySelect, $queryFrom, false );
	}
	function excluir($vopessoaturma) {
		throw new excecaoGenerica ( "Opera��o permitida apenas na tela de manuten��o da turma." );
	}
	function incluirPagamento($vopessoaturma) {
		//$vopessoaturma = new vopessoaturma();
		$colecaoPagamento = $vopessoaturma->colecaoParcelasPagas;
		foreach ( $colecaoPagamento as $vopagamento ) {
			//$vopagamento = new vopagamento();
			//so inclui o que ja nao tiver incluido antes
			$parcela = $vopagamento->numParcelaPaga;
			if(!in_array($parcela, $vopessoaturma->colecaoParcelasPagasAnteriores)){
				$db = $vopagamento->dbprocesso;
				$db->cDb = $this->cDb;
				$db->incluir ( $vopagamento );
			}
		}
		// echo "<br>incluiu pessoa vinculo:" . var_dump($vopeturma);
	}
	function excluirPagamento($vopessoaturma) {
		// $vopessoaturma = new vopessoaturma();
		$colecaoPagamento = $vopessoaturma->colecaoParcelasPagas;
		$sqlNotIn = null;
		if (! isColecaoVazia ( $colecaoPagamento )) {
			$arrayParcelas = array_keys ( $colecaoPagamento );
			$sqlNotIn = getSQLStringFormatadaColecaoIN ( $arrayParcelas, false );
		}
		
		$vo = new vopagamento ();
		$nmTabela = $vo->getNmTabelaEntidade ( false );
		$query = "DELETE FROM " . $nmTabela;
		$query .= "\n WHERE " . vopagamento::$nmAtrCdPessoa . " = " . $vopessoaturma->cdPessoa;
		$query .= "\n AND " . vopagamento::$nmAtrCdTurma . " = " . $vopessoaturma->cdTurma;
		if ($sqlNotIn) {
			$query .= "\n AND " . vopagamento::$nmAtrNumParcelaPaga . " NOT IN (" . $sqlNotIn . ")";
		}
		
		// echo $query;
		return $this->atualizarEntidade ( $query );
	}
	/*function excluirPagamento($vopessoaturma) {
		$vo = new vopagamento ();
		$nmTabela = $vo->getNmTabelaEntidade ( false );
		$query = "DELETE FROM " . $nmTabela;
		$query .= "\n WHERE " . vopagamento::$nmAtrCdPessoa . " = " . $vopessoaturma->cdPessoa;
		$query .= "\n AND " . vopagamento::$nmAtrCdTurma . " = " . $vopessoaturma->cdTurma;
		
		// echo $query;
		return $this->atualizarEntidade ( $query );
	}*/
	function pagamento($vopessoaturma) {
		// $vopessoaturma = new vopessoaturma();
		// Start transaction
		$this->cDb->retiraAutoCommit ();
		try {
			// inclui APENAS o pagamento das parcelas checadas
			if (! isColecaoVazia ( $vopessoaturma->colecaoParcelasPagas )) {
				$this->incluirPagamento ( $vopessoaturma );
			}			
			$this->excluirPagamento ( $vopessoaturma );
			// End transaction
			$this->cDb->commit ();
		} catch ( Exception $e ) {
			$this->cDb->rollback ();
			throw new Exception ( $e->getMessage () );
		}
		
		// var_dump($voturma->colecaoAlunos);
		
		return $voturma;
	}
	function incluirSQL($vopessoaturma) {
		$arrayAtribRemover = array (
				vopessoaturma::$nmAtrDhInclusao,
				vopessoaturma::$nmAtrDhUltAlteracao 
		);
		
		return $this->incluirQuery ( $vopessoaturma, $arrayAtribRemover );
	}
	function getSQLValuesInsert($vopessoaturma) {
		$retorno = "";
		$retorno .= $this->getVarComoNumero ( $vopessoaturma->cdPessoa ) . ",";
		$retorno .= $this->getVarComoNumero ( $vopessoaturma->cdTurma ) . ",";
		$retorno .= $this->getVarComoNumero ( $vopessoaturma->numParcelas ) . ",";
		$retorno .= $this->getVarComoDecimal ( $vopessoaturma->valor ) . ",";
		$retorno .= $this->getVarComoString ( $vopessoaturma->obs );
		
		$retorno .= $vopessoaturma->getSQLValuesInsertEntidade ();
		
		return $retorno;
	}
	// quando alterar da tela pessoa x turma
	// sera permitido alterar, porem de maneira isolada
	function getSQLValuesUpdate($vo) {
		$retorno = "";
		$sqlConector = "";
		
		if ($vo->valor != null) {
			$retorno .= $sqlConector . vopessoaturma::$nmAtrValor . " = " . $this->getVarComoDecimal ( $vo->valor );
			$sqlConector = ",";
		}
		
		if ($vo->numParcelas != null) {
			$retorno .= $sqlConector . vopessoaturma::$nmAtrNumParcelas . " = " . $this->getVarComoNumero ( $vo->numParcelas );
			$sqlConector = ",";
		}
		
		if ($vo->obs != null) {
			$retorno .= $sqlConector . vopessoaturma::$nmAtrObservacao . " = " . $this->getVarComoString ( $vo->obs );
			$sqlConector = ",";
		}
		
		$retorno = $retorno . $sqlConector . $vo->getSQLValuesUpdate ();
		
		return $retorno;
	}
}
?>