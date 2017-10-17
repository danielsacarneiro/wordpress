<?php
include_once(caminho_lib. "dbprocesso.obj.php");
include_once (caminho_util."bibliotecaFuncoesPrincipal.php");

// .................................................................................................................

  Class dbMeta extends dbprocesso{
    
  	function consultarPorChave($vo, $isHistorico) {
  		$nmTabela = $vo->getNmTabelaEntidade ( $isHistorico );
  		
  		$arrayColunasRetornadas = array($nmTabela . ".*");
  		  		
  		$retorno= $this->consultarPorChaveMontandoQuery ( $vo, $arrayColunasRetornadas, $queryJoin, $isHistorico, true);
  		
  		return $retorno;
  	}
  	
    function incluirSQL($vomateria){
        $arrayAtribRemover = array(
            vomateria::$nmAtrDhInclusao,
            vomateria::$nmAtrDhUltAlteracao
            );
        
        if($vomateria->cd == null || $vomateria->cd == ""){
        	$vomateria->cd = $this->getProximoSequencial(vomateria::$nmAtrCd, $vomateria);        
        }
        
        //$vomateria->cd = $this->getProximoSequencial(vomateria::$nmAtrCd, $vomateria);        
        
        return $this->incluirQuery($vomateria, $arrayAtribRemover);
    }    

    function getSQLValuesInsert($vomateria){
		$retorno = "";
        $retorno.= $this-> getVarComoNumero($vomateria->cd) . ",";
        $retorno.= $this-> getVarComoString($vomateria->descricao) . ",";
        $retorno.= $this-> getVarComoDecimal($vomateria->valor) . ",";
        $retorno.= $this-> getVarComoString($vomateria->obs);
        
        $retorno.= $vomateria->getSQLValuesInsertEntidade();
		        
		return $retorno;                
    }
        
    function getSQLValuesUpdate($vo){        
        $retorno = "";
        $sqlConector = "";
                
        if($vo->descricao != null){
            $retorno.= $sqlConector . vomateria::$nmAtrDescricao . " = " . $this->getVarComoString($vo->descricao);
            $sqlConector = ",";
        }
        
        if($vo->valor != null){
        	$retorno.= $sqlConector . vomateria::$nmAtrValor. " = " . $this->getVarComoDecimal($vo->valor);
        	$sqlConector = ",";
        }
        
        if($vo->obs != null){
        	$retorno.= $sqlConector . vomateria::$nmAtrObservacao. " = " . $this->getVarComoString($vo->obs);
        	$sqlConector = ",";
        }
        
        $retorno = $retorno . $sqlConector . $vo->getSQLValuesUpdate();
		        
		return $retorno;                
    }
    
    /**
     *FUNCOES DE IMPORTACAO EXCLUSIVA
     */
    
	function importar(){        
        $query = "SELECT ct_gestor from contrato where ct_gestor is not null GROUP BY ct_gestor ";
        $retorno = $this->consultarEntidade($query, false);        
            if($retorno != null){
                $tamanho = count($retorno);
                //echo "<br> qtd registros: " . $tamanho;
                               
                for ($i=0; $i<=$tamanho; $i++) {
                    $vo = new vogestor();
                    
                    $vo->descricao = $retorno[$i]["ct_gestor"];
                    //$vo->cd = $i."";
                    
                    //echo $vo->toString() . "<br>";
                    echo $this->incluir($vo) . "<br>";
                    
                }

            } 
        /*$vo = new vogestor();
        $vo->descricao = "TESTE";
                    
        //echo $vo->toString() . "<br>";
        echo $this->incluir($vo) . "<br>";*/
	}	
}
?>