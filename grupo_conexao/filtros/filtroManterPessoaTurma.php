<?php
include_once(caminho_util."bibliotecaSQL.php");
include_once(caminho_lib ."filtroManter.php");

class filtroManterPessoaTurma extends filtroManter{
    
    var $nmFiltro = "filtroManterPessoaTurma";
    static $NM_COL_VALOR_PAGO = "NM_COL_VALOR_PAGO";
    static $NM_COL_VALOR_TOTAL = "NM_COL_VALOR_TOTAL";
    
    // ...............................................................
	// construtor
    var $cdPessoa;
    var $cdTurma;
    var $doc;
    var $nome;
    var $dsTurma;
    var $dsMateria;
    var $cdvinculo;
    	
	function getFiltroFormulario(){		
		$this->cdPessoa = @$_POST[vopessoaturma::$nmAtrCdPessoa];
		$this->cdTurma = @$_POST[vopessoaturma::$nmAtrCdTurma];
		//$this->cdGestor = @$_POST[vopessoa::$nmAtrCdGestor];
		$this->doc = @$_POST[vopessoa::$nmAtrDocCPF];
		$this->nome = @$_POST[vopessoa::$nmAtrNome];
		$this->dsTurma = @$_POST[voturma::$nmAtrDescricao];
		$this->dsMateria= @$_POST[vomateria::$nmAtrDescricao];
		$this->cdvinculo = @$_POST[vopessoavinculo::$nmAtrCd];
	}
	
	function getFiltroConsultaSQL($comAtributoOrdenacao = null){
		$filtro = "";
		$conector  = "";

		$isHistorico = $this->isHistorico();
        $nmTabelaPessoa = vopessoa::getNmTabelaStatic($isHistorico);
        $nmTabelaPessoaTurma = vopessoaturma::getNmTabelaStatic($isHistorico);
        $nmTabelaTurma = voturma::getNmTabelaStatic($isHistorico);
        
		//seta os filtros obrigatorios        
		if($this->isSetaValorDefault()){
			//anoDefault foi definido como constante na index.php
            //echo "setou o ano defaul";
            ;                        
		}
            		
		if($this->cdPessoa != null){
			$filtro = $filtro . $conector
						. $nmTabelaPessoaTurma. "." .vopessoaturma::$nmAtrCdPessoa
						. " = "
						. $this->cdPessoa;
			
			$conector  = "\n AND ";
		}
        
		if($this->cdTurma != null){
			$filtro = $filtro . $conector
			. $nmTabelaPessoaTurma. "." .vopessoaturma::$nmAtrCdTurma
			. " = "
					. $this->cdTurma;
					
					$conector  = "\n AND ";
		}
		
		if($this->dsTurma != null){
			$filtro = $filtro . $conector
			. $nmTabelaTurma. "." .voturma::$nmAtrDescricao
			. " LIKE '%"
					//. utf8_encode($this->nome)
					. $this->dsTurma
					. "%'";
					
					$conector  = "\n AND ";
		}
		
		if($this->dsMateria != null){
			$filtro = $filtro . $conector
			. $nmTabela. "." .vomateria::$nmAtrDescricao
			. " LIKE '%"
					//. utf8_encode($this->nome)
			. $this->dsMateria
			. "%'";
			
			$conector  = "\n AND ";
		}
		
		if($this->cdvinculo != null){
			$filtro = $filtro . $conector
					. $nmTabelaPessoaTurma. "." .voPessoaTurma::$nmAtrCd
					. " = "
					. $this->cdvinculo;
						
					$conector  = "\n AND ";
		}
		
		if($this->nome != null){
			$filtro = $filtro . $conector
			. $nmTabelaPessoa. "." .vopessoa::$nmAtrNome
			. " LIKE '%"
					. $this->nome
					. "%'";
						
					$conector  = "\n AND ";
		}
		
		if($this->doc != null){			
			$filtro = $filtro . $conector
						. $nmTabela. "." .vopessoa::$nmAtrDocCPF
						. "='"
						. documentoPessoa::getNumeroDocSemMascara($this->doc)
						. "'";
			
			$conector  = "\n AND ";
		}	
				
		if($this->dtReferenciaContrato != null){
			$filtro = $filtro . $conector
					.  getSQLDataVigenteSimplesPorData(
							vocontrato::getNmTabela(),
							$this->dtReferenciaContrato,
							vocontrato::$nmAtrDtVigenciaInicialContrato,
							vocontrato::$nmAtrDtVigenciaFinalContrato)
			;
		
					$conector  = "\n AND ";
		}		

		$this->formataCampoOrdenacao(new vopessoa());
		//finaliza o filtro		
		$filtro = parent::getFiltroSQL($filtro, $comAtributoOrdenacao);
		//echo "Filtro:$filtro<br>";

		return $filtro;
	}
	
	function getAtributosOrdenacao(){
		$varAtributos = array(
				vopessoaturma::$nmAtrCdTurma => "Turma",
				vopessoa::$nmAtrNome => "Pessoa"
		);
		return $varAtributos;
	}
}

?>