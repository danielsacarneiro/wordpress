<?php
include_once(caminho_util."bibliotecaSQL.php");
include_once(caminho_lib ."filtroManter.php");

class filtroManterPagamento extends filtroManter{
    
    var $nmFiltro = "filtroManterPessoaTurma";
    
    // ...............................................................
	// construtor
    var $cdPessoa;
    var $cdTurma;
    	
	function getFiltroFormulario(){		
		$this->cdPessoa = @$_POST[vopessoaturma::$nmAtrCdPessoa];
		$this->cdTurma = @$_POST[vopessoaturma::$nmAtrCdTurma];
	}
	
	function getFiltroConsultaSQL($comAtributoOrdenacao = null){
		$filtro = "";
		$conector  = "";

		$isHistorico = $this->isHistorico();
        $nmTabela = vopagamento::getNmTabelaStatic($isHistorico);
        
		//seta os filtros obrigatorios        
		if($this->isSetaValorDefault()){
			//anoDefault foi definido como constante na index.php
            //echo "setou o ano defaul";
            ;                        
		}
            		
		if($this->cdPessoa != null){
			$filtro = $filtro . $conector
			. $nmTabela. "." .vopagamento::$nmAtrCdPessoa
						. " = "
						. $this->cdPessoa;
			
			$conector  = "\n AND ";
		}
        
		if($this->cdTurma != null){
			$filtro = $filtro . $conector
			. $nmTabela. "." .vopagamento::$nmAtrCdTurma
			. " = "
					. $this->cdTurma;
					
					$conector  = "\n AND ";
		}		

		//$this->formataCampoOrdenacao(new vopessoa());
		//finaliza o filtro		
		$filtro = parent::getFiltroSQL($filtro, $comAtributoOrdenacao);
		//echo "Filtro:$filtro<br>";

		return $filtro;
	}
	
/*	function getAtributosOrdenacao(){
		$varAtributos = array(
				vopagamento::pessoaturma::$nmAtrCdTurma => "Turma",
				vopessoa::$nmAtrNome => "Pessoa"
		);
		return $varAtributos;
	}*/
}

?>