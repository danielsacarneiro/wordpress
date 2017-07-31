<?php
include_once (caminho_lib . "dbprocesso.obj.php");
class dbpessoaturma extends dbprocesso {
	function consultarPorChave($vo, $isHistorico) {
		$nmTabela = $vo->getNmTabelaEntidade ( $isHistorico );
		
		$query = "SELECT * FROM " . $nmTabela;
		$query .= " WHERE ";
		$query .= $vo->getValoresWhereSQLChave ( $isHistorico );
		
		// echo $query;
		return $this->consultarEntidade ( $query, true );
	}
	function incluirSQL($vopessoaturma) {
		$arrayAtribRemover = array (
				vopessoaturma::$nmAtrValor,
				vopessoaturma::$nmAtrObservacao,
				vopessoaturma::$nmAtrDhInclusao,
				vopessoaturma::$nmAtrDhUltAlteracao 
		);
		
		return $this->incluirQuery ( $vopessoaturma, $arrayAtribRemover );
	}
	function getSQLValuesInsert($vopessoaturma) {
		$retorno = "";
		$retorno .= $this->getVarComoNumero ( $vopessoaturma->cdPessoa ) . ",";
		$retorno .= $this->getVarComoNumero ( $vopessoaturma->cdTurma );
		
		$retorno .= $vopessoaturma->getSQLValuesInsertEntidade ();
		
		return $retorno;
	}
	function getSQLValuesUpdate($vo) {
		$retorno = "";
		$sqlConector = "";
		
		if ($vo->valor != null) {
			$retorno .= $sqlConector . vopessoaturma::$nmAtrValor . " = " . $this->getVarComoDecimal ( $vo->valor );
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