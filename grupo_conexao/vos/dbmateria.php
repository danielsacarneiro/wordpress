<?php
include_once(caminho_lib. "dbprocesso.obj.php");
include_once (caminho_util."bibliotecaFuncoesPrincipal.php");

// .................................................................................................................

  Class dbmateria extends dbprocesso{
    
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
        $retorno.= $this-> getVarComoString(strtoupper($vomateria->descricao));
        
        $retorno.= $vomateria->getSQLValuesInsertEntidade();
		        
		return $retorno;                
    }
        
    function getSQLValuesUpdate($vo){        
        $retorno = "";
        $sqlConector = "";
                
        if($vo->descricao != null){
        	$retorno.= $sqlConector . vomateria::$nmAtrDescricao . " = " . $this->getVarComoString(strtoupper($vo->descricao));
            $sqlConector = ",";
        }
               
        $retorno = $retorno . $sqlConector . $vo->getSQLValuesUpdate();
		        
		return $retorno;                
    }
}
?>