<?php
include_once(caminho_util."bibliotecaSQL.php");
include_once(caminho_lib ."filtroManter.php");

class filtroManterPerfil extends filtroManter{
    
    public static $nmFiltro = "filtroManterPerfil";
    
    // ...............................................................
	// construtor
	function __construct() {
        parent::__construct(true);
        
        $this->descricao = @$_POST[vomateria::$nmAtrDescricao];
        
	}
    	
	function getFiltroConsultaSQL(){
        $vomateria= new vomateria();
		$filtro = "";
		$conector  = "";

		$isHistorico = $this->isHistorico();
        $nmTabela = $vomateria->getNmTabelaEntidade($isHistorico);
        
		//seta os filtros obrigatorios        
		if($this->isSetaValorDefault()){
			//anoDefault foi definido como constante na index.php
            //echo "setou o ano defaul";
            ;                        
		}
        
		if($this->descricao != null){
			$filtro = $filtro . $conector
						. $nmTabela. "." .vomateria::$nmAtrDescricao
						. " LIKE '%"
						. utf8_encode($this->descricao)
						. "%'";
			
			$conector  = "\n AND ";
        
		}		

		//finaliza o filtro
		$filtro = parent::getFiltroConsulta($filtro);
		
		//echo "Filtro:$filtro<br>";

		return $filtro;
	}	
	
	function getAtributosOrdenacao(){
		$varAtributos = array(
				voperfil::$nmAtrCd=> "Código",
				voperfil::$nmAtrDescricao => "Descrição",
		);
		return $varAtributos;
	}
	
}

?>