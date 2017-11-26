<?php
include_once(caminho_util."bibliotecaSQL.php");
include_once(caminho_lib ."filtroManter.php");

class filtroManterPerfilMateria extends filtroManter{
    
    public static $nmFiltro = "filtroManterPerfilMateria";
    var $vomateria = "";
    var $voperfil  = "";
    
    // ...............................................................
    function getFiltroFormulario(){
    	$this->vomateria = new vomateria();
    	$this->vomateria->getDadosFormulario();
    	$this->voperfil = new voperfil();
    	$this->voperfil->getDadosFormulario();
	}
    	
	function getFiltroConsultaSQL(){
		$filtro = "";
		$conector  = "";

		$isHistorico = $this->isHistorico();
        $nmTabela = voperfilmateria::getNmTabelaStatic($isHistorico);
        $nmTabelaPerfil = voperfil::getNmTabelaStatic($isHistorico);
        $nmTabelaMateria = vomateria::getNmTabelaStatic($isHistorico);
        
		//seta os filtros obrigatorios        
		if($this->isSetaValorDefault()){
			//anoDefault foi definido como constante na index.php
            //echo "setou o ano defaul";
            ;                        
		}
        
		if($this->vomateria->cd != null){
			$filtro = $filtro . $conector
						. $nmTabela. "." .voperfilmateria::$nmAtrCdMateria
						. " = "
						. $this->vomateria->cd
						;
			
			$conector  = "\n AND ";
        
		}		

		if($this->voperfil->cd != null){
			$filtro = $filtro . $conector
			. $nmTabela. "." .voperfilmateria::$nmAtrCdPerfil
			. " = "
					. $this->voperfil->cd
					;
					
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

		if($this->voperfil->descricao!= null){
			$filtro = $filtro . $conector
			. $nmTabelaPerfil. "." .voperfil::$nmAtrDescricao
			. " LIKE '%"
					. $this->voperfil->descricao
					. "%'";
					
					$conector  = "\n AND ";
					
		}
		
		//$this->formataCampoOrdenacao(new voDemanda());
		//finaliza o filtro
		$filtro = parent::getFiltroSQL($filtro, $comAtributoOrdenacao);
		
		//echo "Filtro:$filtro<br>";

		return $filtro;
	}	
}

?>