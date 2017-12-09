<?php
include_once(caminho_util."bibliotecaSQL.php");
include_once(caminho_lib ."filtroManter.php");

class filtroConsultarMontagem extends filtroManter{
    
    public static $nmFiltro = "filtroConsultarMontagem";
    public static $nmColNumHorasDefinidas = "nmColNumHorasDefinidas";
    //public static $nmColNumHorasADefinir = "nmColNumHorasADefinir";
    
    var $cdPerfil = "";
    var $cdAluno = "";
    var $dsPerfil = "";
    var $nomeAluno = "";    
    
    // ...............................................................
    function getFiltroFormulario(){
    	$this->cdPerfil = @$_POST[voperfilaluno::$nmAtrCdPerfil];
    	$this->cdAluno = @$_POST[voperfilaluno::$nmAtrCdAluno];
    	$this->nomeAluno = @$_POST[vopessoa::$nmAtrNome];
    	$this->dsPerfil = @$_POST[voperfil::$nmAtrDescricao];    	
	}
    	
	function getFiltroConsultaSQL(){
		$filtro = "";
		$conector  = "";

		$isHistorico = $this->isHistorico();
        $nmTabela = voperfilaluno::getNmTabelaStatic($isHistorico);
        $nmTabelaPerfil = voperfil::getNmTabelaStatic($isHistorico);
        $nmTabelaPessoa = vopessoa::getNmTabelaStatic($isHistorico);
        
		//seta os filtros obrigatorios        
		if($this->isSetaValorDefault()){
			//anoDefault foi definido como constante na index.php
            //echo "setou o ano defaul";
            ;                        
		}
        
		if($this->cdPerfil != null){
			$filtro = $filtro . $conector
						. $nmTabela. "." .voperfilaluno::$nmAtrCdPerfil
						. " = "
						. $this->cdPerfil
						;
			
			$conector  = "\n AND ";
        
		}		

		if($this->cdAluno != null){
			$filtro = $filtro . $conector
			. $nmTabela. "." .voperfilaluno::$nmAtrCdAluno
			. " = "
					. $this->cdAluno
					;
					
					$conector  = "\n AND ";
					
		}
		
		if($this->nomeAluno != null){
			$filtro = $filtro . $conector
			. $nmTabelaPessoa . "." .vopessoa::$nmAtrNome
			. " LIKE '%"
					. $this->nomeAluno
					. "%'";
					
					$conector  = "\n AND ";
					
		}

		if($this->dsPerfil != null){
			$filtro = $filtro . $conector
			. $nmTabelaPerfil. "." .voperfil::$nmAtrDescricao
			. " LIKE '%"
					. $this->dsPerfil
					. "%'";
					
					$conector  = "\n AND ";
					
		}
		
		//$this->formataCampoOrdenacao(new voDemanda());
		//finaliza o filtro
		$filtro = parent::getFiltroSQL($filtro, $comAtributoOrdenacao);
		
		//echo "Filtro:$filtro<br>";

		return $filtro;
	}	
	
	/*function getAtributoOrdenacaoDefault(){
	 $nmTabela = vometafonte::getNmTabelaStatic($this->isHistorico);
	 $retorno = $nmTabela. "." . vometafonte::$nmAtrCdPerfil. " " . constantes::$CD_ORDEM_CRESCENTE
	 . "," . $nmTabela. "." . vometafonte::$nmAtrCdMeta . " " . constantes::$CD_ORDEM_CRESCENTE
	 . "," . $nmTabela. "." . vometafonte::$nmAtrCdMateria . " " . constantes::$CD_ORDEM_CRESCENTE
	 . "," . $nmTabela. "." . vometafonte::$nmAtrSq . " " . constantes::$CD_ORDEM_CRESCENTE;
	 return $retorno;
	 }*/
	
	function getAtributosOrdenacao(){
		$varAtributos = array(
				voperfilaluno::$nmAtrCdPerfil=> "Perfil",
				voperfilaluno::$nmAtrCdAluno=> "Aluno",
				voperfilaluno::$nmAtrTpMeta=> "Meta",				
		);
		return $varAtributos;
	}
	
}

?>