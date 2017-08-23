<?php
include_once(caminho_util."bibliotecaSQL.php");
include_once(caminho_vos ."vopessoa.php");
include_once(caminho_vos ."vopessoavinculo.php");
include_once(caminho_lib ."filtroManter.php");

class filtroManterPessoa extends filtroManter{
    
    var $nmFiltro = "filtroManterPessoa";
    static $ID_REQ_DT_REFERENCIA = "filtroManterPessoa_ID_REQ_DT_REFERENCIA";
    
    // ...............................................................
	// construtor
    var $cd;
    var $cdTurma;
    var $colecaoCd;
    var $doc;
    var $nome;
    var $cdvinculo;
    var $dtReferenciaContrato;    
	
	function getFiltroFormulario(){		
		$this->cd = @$_POST[vopessoa::$nmAtrCd];
		$this->cdTurma = @$_POST[voturma::$nmAtrCd];
		//$this->cdGestor = @$_POST[vopessoa::$nmAtrCdGestor];
		$this->doc = @$_POST[vopessoa::$nmAtrDocCPF];
		$this->nome = @$_POST[vopessoa::$nmAtrNome];
		
		if(isLupa() && @$_GET[vopessoavinculo::$nmAtrCd] != null){
			//eh pq veio de uma requisicao anterior via lupa
			$this->cdvinculo = @$_GET[vopessoavinculo::$nmAtrCd];
		}else{
			//caso contrario pega via post mesmo
			$this->cdvinculo = @$_POST[vopessoavinculo::$nmAtrCd];
		}
		
	}
	
	function getFiltroConsultaSQL($comAtributoOrdenacao = null){
        $voPessoa= new vopessoa();
        $voPessoaVinculo= new vopessoavinculo();
		$filtro = "";
		$conector  = "";
		$this->voPrincipal = $voPessoa;

		$isHistorico = $this->isHistorico();
        $nmTabela = $voPessoa->getNmTabelaEntidade($isHistorico);
        $nmTabelaPessoaVinculo = $voPessoaVinculo->getNmTabela();
        $nmTabelaPessoaTurma = vopessoaturma::getNmTabelaStatic($isHistorico);
        
		//seta os filtros obrigatorios        
		if($this->isSetaValorDefault()){
			//anoDefault foi definido como constante na index.php
            //echo "setou o ano defaul";
            ;                        
		}
            		
		if($this->colecaoCd != null){
			$filtro = $filtro . $conector
						. $nmTabela. "." .vopessoa::$nmAtrCd
						. " IN ("
						. getSQLStringFormatadaColecaoIN($this->colecaoCd, false)
						. ")";
			
			$conector  = "\n AND ";
		}
        
		if($this->cd != null){
			$filtro = $filtro . $conector
			. $nmTabela. "." .vopessoa::$nmAtrCd
			. " = "
					. $this->cd;
					
					$conector  = "\n AND ";
		}
		
		if($this->cdTurma != null){
			$filtro = $filtro . $conector
			. $nmTabelaPessoaTurma. "." .vopessoaturma::$nmAtrCdTurma
			. " = "
					. $this->cdTurma;
					
					$conector  = "\n AND ";
		}
		
		if($this->cdvinculo != null){
			$filtro = $filtro . $conector
					. $nmTabelaPessoaVinculo. "." .vopessoavinculo::$nmAtrCd
					. " = "
					. $this->cdvinculo;
						
					$conector  = "\n AND ";
		}
		
		if($this->nome != null){
			$filtro = $filtro . $conector
			. $nmTabela. "." .vopessoa::$nmAtrNome
			. " LIKE '%"
					. utf8_encode($this->nome)
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
				vopessoa::$nmAtrNome => "Nome",
				vopessoavinculo::$nmAtrCd=> "Vinculo",				
				vopessoa::$nmAtrDhUltAlteracao=> "Data.Alteração",
				vopessoa::$nmAtrCd => "Código"
		);
		return $varAtributos;
	}
}

?>