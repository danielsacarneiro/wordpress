<?php
include_once(caminho_util."bibliotecaSQL.php");
include_once(caminho_lib ."filtroManter.php");

class filtroManterMetaFonte extends filtroManter{
    
    public static $nmFiltro = "filtroManterMetaFonte";
    var $voperfil = "";
    var $vometafonte = "";
    var $vomateria = "";
    
    // ...............................................................
    function getFiltroFormulario(){
        $this->voperfil = new voperfil();        
        $this->voperfil->getDadosFormulario();
        $this->vomateria = new vomateria();
        $this->vomateria->getDadosFormulario();
        $this->vometafonte = new vometafonte();
        $this->vometafonte->getDadosFormulario();
	}
    	
	function getFiltroConsultaSQL($comAtributoOrdenacao = null){
		$filtro = "";
		$conector  = "";

		$isHistorico = $this->isHistorico();
		$nmTabelaMetaFonte = vometafonte::getNmTabelaStatic($isHistorico);
        $nmTabelaMateriaFonte = vomateriafonte::getNmTabelaStatic($isHistorico);
        $nmTabelaMateria = vomateria::getNmTabelaStatic($isHistorico);             
        $nmTabelaPerfil = voperfil::getNmTabelaStatic($isHistorico);
        
		//seta os filtros obrigatorios        
		if($this->isSetaValorDefault()){
			//anoDefault foi definido como constante na index.php
            //echo "setou o ano defaul";
            ;                        
		}
        
		if($this->vometafonte->sq != null){
			$filtro = $filtro . $conector
					. $nmTabelaMetaFonte. "." .vometafonte::$nmAtrSq
						. " = "
						. $this->vometafonte->sq
						;
			
			$conector  = "\n AND ";
        
		}
		
		if($this->vometafonte->cdMeta != null){
			$filtro = $filtro . $conector
			. $nmTabelaMetaFonte. "." .vometafonte::$nmAtrCdMeta
			. " = "
					. $this->vometafonte->cdMeta
					;
					
					$conector  = "\n AND ";
					
		}
		

		if($this->vometafonte->cdPerfil != null){
			$filtro = $filtro . $conector
			. $nmTabelaMetaFonte. "." .vometafonte::$nmAtrCdPerfil
			. " = "
					. $this->vometafonte->cdPerfil
					;
					
					$conector  = "\n AND ";
					
		}
		
		if($this->vometafonte->cdMateria != null){
			$filtro = $filtro . $conector
			. $nmTabelaMetaFonte. "." .vometafonte::$nmAtrCdMateria
			. " = "
					. $this->vometafonte->cdMateria
					;
					
					$conector  = "\n AND ";
					
		}
		
		
		if($this->voperfil->descricao != null){
			$filtro = $filtro . $conector
			. $nmTabelaPerfil. "." .voperfil::$nmAtrDescricao
			. " LIKE '%"
					. $this->voperfil->descricao
					. "%'";
					
					$conector  = "\n AND ";
					
		}

		if($this->vomateria->descricao!= null){
			$filtro = $filtro . $conector
			. $nmTabelaMateria. "." .vomateria::$nmAtrDescricao
			. " LIKE '%"
					. $this->vomateria->descricao
					. "%'";
					
					$conector  = "\n AND ";
					
		}
		
		$this->formataCampoOrdenacao(new vometafonte());
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
				vometafonte::$nmAtrCdMeta => "Meta",
				vometafonte::$nmAtrCdPerfil => "Perfil",
				vometafonte::$nmAtrCdMateria => "Matéria",
				vometafonte::$nmAtrSq => "Sequencial",
		);
		return $varAtributos;
	}
	
}

?>