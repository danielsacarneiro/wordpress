<?php
include_once(caminho_lib. "dbprocesso.obj.php");
include_once (caminho_util."bibliotecaFuncoesPrincipal.php");

// .................................................................................................................

  Class dbmateriafonte extends dbprocesso{
    
  	function consultarPorChave($vo, $isHistorico) {
  		$nmTabela = $vo->getNmTabelaEntidade ( $isHistorico ); 		
  		$nmTabelaMateria = vomateria::getNmTabelaStatic(false);
  		$arrayColunasRetornadas = array($nmTabela . ".*",
  				"$nmTabelaMateria.".vomateria::$nmAtrDescricao,
  		);  
  		
  		$queryJoin .= "\n INNER JOIN " . $nmTabelaMateria;
  		$queryJoin .= "\n ON ";
  		$queryJoin .= $nmTabelaMateria. "." . vomateria::$nmAtrCd . "=" . $nmTabela . "." . vomateriafonte::$nmAtrCdMateria;
  		  		  		
  		$retorno= $this->consultarPorChaveMontandoQuery ( $vo, $arrayColunasRetornadas, $queryJoin, $isHistorico, true);  		
  		return $retorno;
  	}
  	
  	function consultarTelaConsulta($vo, $filtro) {
  		$isHistorico = $filtro->isHistorico;
  		$nmTabela = $vo->getNmTabelaEntidade ( $isHistorico );
  		$nmTabelaMateria = vomateria::getNmTabelaStatic(false);
  		$arrayColunasRetornadas = array("*");
  		
  		$queryJoin .= "\n INNER JOIN " . $nmTabelaMateria;
  		$queryJoin .= "\n ON ";
  		$queryJoin .= $nmTabelaMateria. "." . vomateria::$nmAtrCd . "=" . $nmTabela . "." . vomateriafonte::$nmAtrCdMateria;
  		
  		return parent::consultarMontandoQueryTelaConsulta ( $vo, $filtro, $arrayColunasRetornadas, $queryJoin );
  	}
  	
    function incluirSQL($vomateriafonte){
        $arrayAtribRemover = array(
            vomateriafonte::$nmAtrDhInclusao,
            vomateriafonte::$nmAtrDhUltAlteracao
            );
        
        if($vomateriafonte->cdFonte == null || $vomateriafonte->cdFonte== ""){
        	$vomateriafonte->cdFonte= $this->getProximoSequencialChaveComposta(vomateriafonte::$nmAtrCdMateria, $vomateriafonte);        	        	
        }        
        
        return $this->incluirQuery($vomateriafonte, $arrayAtribRemover);
    }    

    function getSQLValuesInsert($vomateriafonte){
		$retorno = "";
        $retorno.= $this-> getVarComoNumero($vomateriafonte->cdMateria) . ",";
        $retorno.= $this-> getVarComoNumero($vomateriafonte->cdFonte) . ",";
        $retorno.= $this-> getVarComoString(strtoupper($vomateriafonte->descricao));
        
        $retorno.= $vomateriafonte->getSQLValuesInsertEntidade();
		        
		return $retorno;                
    }
        
    function getSQLValuesUpdate($vo){        
        $retorno = "";
        $sqlConector = "";
                
        if($vo->descricao != null){
        	$retorno.= $sqlConector . vomateriafonte::$nmAtrDescricao . " = " . $this->getVarComoString(strtoupper($vo->descricao));
            $sqlConector = ",";
        }
               
        $retorno = $retorno . $sqlConector . $vo->getSQLValuesUpdate();
		        
		return $retorno;                
    }
}
?>