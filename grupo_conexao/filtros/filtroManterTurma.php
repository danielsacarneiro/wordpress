<?php
include_once (caminho_util . "bibliotecaSQL.php");
include_once (caminho_lib . "filtroManter.php");
class filtroManterTurma extends filtroManter {
	public static $nmFiltro = "filtroManterTurma";

	static $NM_COL_VALOR_IDEAL = "NM_COL_VALOR_IDEAL"; 
	static $NM_COL_VALOR_REAL = "NM_COL_VALOR_REAL";
	static $NM_COL_VALOR_PAGO = "NM_COL_VALOR_PAGO";
	static $NM_COL_QTD_ALUNOS = "NM_COL_QTD_ALUNOS";
	var $nomePessoa;
	var $cdPessoa;
	var $dsTurma;
	var $cdTurma;
	
	// ...............................................................
	function getFiltroFormulario() {
		$this->cdTurma = @$_POST [vopessoaturma::$nmAtrCdTurma];
		$this->dsTurma = @$_POST [voturma::$nmAtrDescricao];
		$this->cdPessoa = @$_POST [vopessoaturma::$nmAtrCdPessoa];
		$this->nomePessoa = @$_POST [vopessoa::$nmAtrNome];
	}
	function getFiltroConsultaSQL() {
		$filtro = "";
		$conector = "";
		
		$isHistorico = $this->isHistorico ();
		$nmTabelaTurma = voturma::getNmTabelaStatic ( $isHistorico );
		$nmTabelaPessoa = vopessoa::getNmTabelaStatic ( $isHistorico );
		$nmTabelaPessoaTurma = vopessoaturma::getNmTabelaStatic ( $isHistorico );
		
		//var_dump($this);
		// seta os filtros obrigatorios
		if ($this->isSetaValorDefault ()) {
			// anoDefault foi definido como constante na index.php
			// echo "setou o ano defaul";
			;
		}
		
		if ($this->cdTurma != null) {
			$filtro = $filtro . $conector . $nmTabelaTurma . "." . voturma::$nmAtrCd . " = " . $this->cdTurma;
			
			$conector = "\n AND ";
		}
		
		if ($this->dsTurma != null) {
			$filtro = $filtro . $conector . $nmTabelaTurma . "." . voturma::$nmAtrDescricao . " LIKE '%" . $this->dsTurma . "%'";
			
			$conector = "\n AND ";
		}
		
		if ($this->cdPessoa != null) {
			$filtro = $filtro . $conector . $nmTabelaPessoa. "." . vopessoa::$nmAtrCd . " = " . $this->cdPessoa;
			
			$conector = "\n AND ";
		}
		
		if ($this->nomePessoa != null) {
			$filtro = $filtro . $conector . $nmTabelaPessoa. "." . vopessoa::$nmAtrNome . " LIKE '%" . $this->nomePessoa. "%'";
			
			$conector = "\n AND ";
		}
		
		if (!$this->isHistorico()) {
			$filtro = $filtro . $conector . $nmTabelaPessoaTurma. "." . vopessoaturma::$nmAtrInDesativado . "= 'N'";
			
			$conector = "\n AND ";
		}
		
		// finaliza o filtro
		$filtro = parent::getFiltroConsulta ( $filtro );
		
		// echo "Filtro:$filtro<br>";
		
		return $filtro;
	}
	function getAtributosOrdenacao() {
		$varAtributos = array (
				voturma::$nmAtrDescricao => "Descrição",
				voturma::$nmAtrValor => "Valor" 
		);
		return $varAtributos;
	}
}

?>