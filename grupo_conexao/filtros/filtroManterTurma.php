<?php
include_once(caminho_util."bibliotecaSQL.php");
include_once(caminho_lib ."filtroManter.php");

class filtroManterTurma extends filtroManter{
    
    public static $nmFiltro = "filtroManterTurma";
    
    var $descricao;
    var $cdTurma;
    
    // ...............................................................
			
	function getFiltroFormulario(){
		$this->cdTurma = @$_POST[voturma::$nmAtrCd];
		$this->descricao = @$_POST[voturma::$nmAtrDescricao];
	}
	
	function getFiltroConsultaSQL(){
        $voturma= new voturma();
		$filtro = "";
		$conector  = "";

		$isHistorico = $this->isHistorico();
        $nmTabela = $voturma->getNmTabelaEntidade($isHistorico);
        
		//seta os filtros obrigatorios        
		if($this->isSetaValorDefault()){
			//anoDefault foi definido como constante na index.php
            //echo "setou o ano defaul";
            ;                        
		}
        
		if($this->cdTurma != null){
			$filtro = $filtro . $conector
						. $nmTabela. "." .voturma::$nmAtrCd
						. " = "
						. $this->cdTurma
						;
			
			$conector  = "\n AND ";
        
		}		

		if($this->descricao != null){
			$filtro = $filtro . $conector
			. $nmTabela. "." .voturma::$nmAtrDescricao
			. " LIKE '%"
					//. utf8_encode($this->descricao)
			. $this->descricao
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
				voturma::$nmAtrDescricao=> "Descrição",
				voturma::$nmAtrValor=> "Valor"
		);
		return $varAtributos;
	}
	
}

?>