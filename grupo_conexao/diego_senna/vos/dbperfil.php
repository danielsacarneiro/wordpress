<?php
include_once(caminho_lib. "dbprocesso.obj.php");
include_once (caminho_util."bibliotecaFuncoesPrincipal.php");

// .................................................................................................................

  Class dbperfil extends dbprocesso{
    
  	function consultarPorChave($vo, $isHistorico) {
  		$nmTabela = $vo->getNmTabelaEntidade ( $isHistorico );
  		
  		$arrayColunasRetornadas = array($nmTabela . ".*");
  		  		
  		$retorno= $this->consultarPorChaveMontandoQuery ( $vo, $arrayColunasRetornadas, $queryJoin, $isHistorico, true);
  		
  		return $retorno;
  	}
  	
    function incluirSQL($voperfil){
        $arrayAtribRemover = array(
            voperfil::$nmAtrDhInclusao,
            voperfil::$nmAtrDhUltAlteracao
            );
        
        if($voperfil->cd == null || $voperfil->cd == ""){
        	$voperfil->cd = $this->getProximoSequencial(voperfil::$nmAtrCd, $voperfil);        
        }
        
        //$voperfil->cd = $this->getProximoSequencial(voperfil::$nmAtrCd, $voperfil);        
        
        return $this->incluirQuery($voperfil, $arrayAtribRemover);
    }    

    function getSQLValuesInsert($voperfil){
		$retorno = "";
        $retorno.= $this-> getVarComoNumero($voperfil->cd) . ",";
        $retorno.= $this-> getVarComoString(strtoupper($voperfil->descricao));
        
        $retorno.= $voperfil->getSQLValuesInsertEntidade();
		        
		return $retorno;                
    }
        
    function getSQLValuesUpdate($vo){        
        $retorno = "";
        $sqlConector = "";
                
        if($vo->descricao != null){
        	$retorno.= $sqlConector . voperfil::$nmAtrDescricao . " = " . $this->getVarComoString(strtoupper($vo->descricao));
            $sqlConector = ",";
        }
               
        $retorno = $retorno . $sqlConector . $vo->getSQLValuesUpdate();
		        
		return $retorno;                
    }
}
?>