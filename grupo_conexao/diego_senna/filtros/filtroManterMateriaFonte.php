<?php
include_once(caminho_util."bibliotecaSQL.php");
include_once(caminho_lib ."filtroManter.php");

class filtroManterMateriaFonte extends filtroManter{
    
    public static $nmFiltro = "filtroManterMateriaFonte";
    var $dsFonte = "";
    var $cdMateria = "";
    var $dsMateria = "";
    
    // ...............................................................
    function getFiltroFormulario(){
        $this->dsFonte = @$_POST[vomateriafonte::$nmAtrDescricao];        
        $this->dsMateria = @$_POST[vomateria::$nmAtrDescricao];
        $this->cdMateria = @$_POST[vomateria::$nmAtrCd];
	}
    	
	function getFiltroConsultaSQL(){
		$filtro = "";
		$conector  = "";

		$isHistorico = $this->isHistorico();
        $nmTabela = vomateriafonte::getNmTabelaStatic($isHistorico);
        $nmTabelaMateria = vomateria::getNmTabelaStatic($isHistorico);
        
		//seta os filtros obrigatorios        
		if($this->isSetaValorDefault()){
			//anoDefault foi definido como constante na index.php
            //echo "setou o ano defaul";
            ;                        
		}
        
		if($this->cdMateria != null){
			$filtro = $filtro . $conector
						. $nmTabela. "." .vomateriafonte::$nmAtrCdMateria
						. " = "
						. $this->cdMateria
						;
			
			$conector  = "\n AND ";
        
		}		

		if($this->dsFonte != null){
			$filtro = $filtro . $conector
			. $nmTabela. "." .vomateriafonte::$nmAtrDescricao
			. " LIKE '%"
					. $this->dsFonte
					. "%'";
					
					$conector  = "\n AND ";
					
		}

		if($this->dsMateria != null){
			$filtro = $filtro . $conector
			. $nmTabelaMateria. "." .vomateria::$nmAtrDescricao
			. " LIKE '%"
					. $this->dsMateria
					. "%'";
					
					$conector  = "\n AND ";
					
		}
		
		//$this->formataCampoOrdenacao(new voDemanda());
		//finaliza o filtro
		$filtro = parent::getFiltroSQL($filtro, $comAtributoOrdenacao);
		
		//echo "Filtro:$filtro<br>";

		return $filtro;
	}
	
	function getAtributosOrdenacao(){
		$varAtributos = array(
				vomateria::$nmAtrCd=> "Código",
				vomateria::$nmAtrDescricao => "Descrição",
		);
		return $varAtributos;
	}
	
}

?>