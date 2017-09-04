<?php
include_once (caminho_lib . "dbprocesso.obj.php");
class dbpagamento extends dbprocesso {
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
		$retorno .= $this->getVarComoNumero ( $vopessoaturma->numParcelaPaga );		
		$retorno .= $vopessoaturma->getSQLValuesInsertEntidade ();
		
		return $retorno;
	}

}
?>