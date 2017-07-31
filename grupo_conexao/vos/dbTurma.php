<?php
include_once (caminho_lib . "dbprocesso.obj.php");
include_once (caminho_util . "bibliotecaFuncoesPrincipal.php");

// .................................................................................................................
class dbturma extends dbprocesso {
	function consultarPorChave($vo, $isHistorico) {
		$nmTabela = $vo->getNmTabelaEntidade ( $isHistorico );
		
		$arrayColunasRetornadas = array (
				$nmTabela . ".*" 
		);
		
		$retorno = $this->consultarPorChaveMontandoQuery ( $vo, $arrayColunasRetornadas, $queryJoin, $isHistorico, true );
		
		return $retorno;
	}
	
	// o incluir eh implementado para nao usar da voentidade
	// por ser mais complexo
	function incluir($voturma) {
		// Start transaction
		$this->cDb->retiraAutoCommit ();
		try {
			$voturma = $this->incluirTurma ( $voturma );
			
			if ($voturma->colecaoAlunos != null) {
				$this->incluirPessoaTurma ( $voturma );
			}
			// End transaction
			$this->cDb->commit ();
		} catch ( Exception $e ) {
			$this->cDb->rollback ();
			throw new Exception ( $e->getMessage () );
		}
		
		return $voturma;
	}
	function incluirPessoaTurma($voturma) {
		foreach ( $voturma->colecaoAlunos as $cdAluno) {			
			$vopeturma = new vopessoaturma ();
			$vopeturma->cdTurma = $voturma->cd;
			$vopeturma->cdPessoa = $cdAluno;
			$dbpeturma = $vopeturma->dbprocesso;
			$dbpeturma->cDb = $this->cDb;
			$dbpeturma->incluir ( $vopeturma );
		}
		// echo "<br>incluiu pessoa vinculo:" . var_dump($vopeturma);
	}
	function excluirPessoaTurma($voturma) {
		$vo = new vopessoaturma();
		$nmTabela = $vo->getNmTabelaEntidade ( false );
		$query = "DELETE FROM " . $nmTabela;
		$query .= "\n WHERE " . vopessoaturma::$nmAtrCdTurma. " = " . $voturma->cd;
		
		// echo $query;
		return $this->atualizarEntidade ( $query );
	}
	function incluirTurma($voturma) {
		$voturma->cd = $this->getProximoSequencial ( voturma::$nmAtrCd, $voturma );
		
		$arrayAtribRemover = array (
				vopessoa::$nmAtrDhInclusao,
				vopessoa::$nmAtrDhUltAlteracao 
		);
		
		$query = $this->incluirQuery ( $voturma, $arrayAtribRemover );
		$retorno = $this->cDb->atualizar ( $query );
		
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
		
		if ($vo->obs != null) {
			$retorno .= $sqlConector . voturma::$nmAtrObservacao . " = " . $this->getVarComoString ( $vo->obs );
			$sqlConector = ",";
		}
		
		$retorno = $retorno . $sqlConector . $vo->getSQLValuesUpdate ();
		
		return $retorno;
	}
}
?>