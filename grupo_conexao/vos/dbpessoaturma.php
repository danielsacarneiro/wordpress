<?php
include_once (caminho_lib . "dbprocesso.obj.php");
class dbpessoaturma extends dbprocesso {
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
		$nmTabelaPessoa = vopessoa::getNmTabelaStatic ( $filtro->isHistorico () );
		$nmTabelaPessoaTurma = vopessoaturma::getNmTabelaStatic ( $filtro->isHistorico () );
		
		$atributosConsulta = $nmTabelaPessoaTurma . "." . vopessoaturma::$nmAtrCdTurma;
		$atributosConsulta .= "," . $nmTabelaPessoaTurma . "." . vopessoaturma::$nmAtrCdPessoa;
		$atributosConsulta .= "," . $nmTabelaPessoaTurma . "." . vopessoaturma::$nmAtrValor;
		$atributosConsulta .= "," . $nmTabelaTurma . "." . voturma::$nmAtrDescricao;
		$atributosConsulta .= "," . $nmTabelaPessoa . "." . vopessoa::$nmAtrNome;
		
		$querySelect = "SELECT " . $atributosConsulta;
		
		$queryFrom = "\n FROM " . $nmTabelaTurma;
		$queryFrom .= "\n INNER JOIN " . $nmTabelaPessoaTurma;
		$queryFrom .= "\n ON " . $nmTabelaTurma . "." . voturma::$nmAtrCd . "=" . $nmTabelaPessoaTurma . "." . vopessoaturma::$nmAtrCdTurma;
		$queryFrom .= "\n LEFT JOIN " . $nmTabelaPessoa;
		$queryFrom .= "\n ON " . $nmTabelaPessoa . "." . vopessoa::$nmAtrCd . "=" . $nmTabelaPessoaTurma . "." . vopessoaturma::$nmAtrCdPessoa;
		
		// $filtro->groupby = $atributosConsulta;
		
		// echo $querySelect."<br>";
		// echo $queryFrom;
		
		return $this->consultarFiltro ( $filtro, $querySelect, $queryFrom, true );
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
			$retorno .= $sqlConector . vopessoaturma::$nmAtrNumParcelas . " = " . $this->getVarComoNumero($vo->numParcelas);
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