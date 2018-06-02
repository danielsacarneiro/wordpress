<?php
include_once(caminho_lib. "dbprocesso.obj.php");
include_once (caminho_util."bibliotecaFuncoesPrincipal.php");

// .................................................................................................................

  Class dbassunto extends dbprocesso{
    
  	function consultarPorChave($vo, $isHistorico) {
  		$nmTabela = $vo->getNmTabelaEntidade ( $isHistorico ); 		
  		$nmTabelaMateria = vomateria::getNmTabelaStatic(false);
  		$nmTabelaPerfil = voperfil::getNmTabelaStatic(false);
  		$arrayColunasRetornadas = array(
  				$nmTabela . ".*",
  				"$nmTabelaMateria.".vomateria::$nmAtrDescricao,
  				"$nmTabelaPerfil.".voperfil::$nmAtrDescricao,
  		);  
  		
  		$queryJoin .= "\n INNER JOIN " . $nmTabelaMateria;
  		$queryJoin .= "\n ON ";
  		$queryJoin .= $nmTabelaMateria. "." . vomateria::$nmAtrCd . "=" . $nmTabela . "." . voperfilmateria::$nmAtrCdMateria;
  		  		  		
  		$queryJoin .= "\n INNER JOIN " . $nmTabelaPerfil;
  		$queryJoin .= "\n ON ";
  		$queryJoin .= $nmTabelaPerfil . "." . voperfil::$nmAtrCd . "=" . $nmTabela . "." . voperfilmateria::$nmAtrCdPerfil;
  		
  		$retorno= $this->consultarPorChaveMontandoQuery ( $vo, $arrayColunasRetornadas, $queryJoin, $isHistorico, true);  		
  		return $retorno;
  	}
  	
  	function consultarTelaConsulta($filtro) {
  		$isHistorico = $filtro->isHistorico;
  		$nmTabela = voassunto::getNmTabelaStatic($isHistorico);
  		$nmTabelaPerfil = voperfil::getNmTabelaStatic($isHistorico);
  		$nmTabelaMateria = vomateria::getNmTabelaStatic($isHistorico);
  		$arrayColunasRetornadas = array("*");
  		
  		$queryJoin .= "\n INNER JOIN " . $nmTabelaMateria;
  		$queryJoin .= "\n ON ";
  		$queryJoin .= $nmTabelaMateria. "." . vomateria::$nmAtrCd . "=" . $nmTabela . "." . voperfilmateria::$nmAtrCdMateria;
  		
  		$queryJoin .= "\n INNER JOIN " . $nmTabelaPerfil;
  		$queryJoin .= "\n ON ";
  		$queryJoin .= $nmTabelaPerfil. "." . voperfil::$nmAtrCd . "=" . $nmTabela . "." . voperfilmateria::$nmAtrCdPerfil;
  		
  		return parent::consultarMontandoQueryTelaConsulta ( new voassunto(), $filtro, $arrayColunasRetornadas, $queryJoin );
  	}
  	
    function incluirSQL($vo){
        $arrayAtribRemover = array(
            vomateriafonte::$nmAtrDhInclusao,
            vomateriafonte::$nmAtrDhUltAlteracao
            );
        
        if($vo->sq == null || $vo->sq == ""){
        	$vo->sq= $this->getProximoSequencialChaveComposta(voassunto::$nmAtrSqAssunto, $vo);        	        	
        }       
        
        return $this->incluirQuery($vo, $arrayAtribRemover);
    }    
    
    function getSQLValuesInsert($vo){
		$retorno = "";
        $retorno.= $this-> getVarComoNumero($vo->cdPerfil) . ",";
        $retorno.= $this-> getVarComoNumero($vo->cdMateria) . ",";
        $retorno.= $this-> getVarComoNumero($vo->sq) . ",";
        
        $retorno.= $this-> getVarComoString($vo->idAssunto) . ",";        
        $retorno.= $this-> getVarComoNumero($vo->carga) . ",";
        $retorno.= $this-> getVarComoString($vo->ds) . ",";
        $retorno.= $this-> getVarComoNumero($vo->inLeiSeca) . ",";
        $retorno.= $this-> getVarComoNumero($vo->inDoutrina) . ",";
        $retorno.= $this-> getVarComoNumero($vo->inQuestoes);
        
        $retorno.= $vo->getSQLValuesInsertEntidade();
		        
		return $retorno;                
    }
        
    function getSQLValuesUpdate($vo){        
        $retorno = "";
        $sqlConector = "";
                
        if($vo->idAssunto!= null){
        	$retorno.= $sqlConector . voassunto::$nmAtrIdAssunto . " = " . $this->getVarComoString($vo->idAssunto);
            $sqlConector = ",";
        }
               
        if($vo->carga!= null){
        	$retorno.= $sqlConector . voassunto::$nmAtrCarga . " = " . $this->getVarComoNumero($vo->carga);
        	$sqlConector = ",";
        }
        
        if($vo->ds!= null){
        	$retorno.= $sqlConector . voassunto::$nmAtrDsAssunto . " = " . $this->getVarComoString($vo->ds);
        	$sqlConector = ",";
        }
        
        if($vo->inLeiSeca!= null){
        	$retorno.= $sqlConector . voassunto::$nmAtrInLeiSeca . " = " . $this->getVarComoNumero($vo->inLeiSeca);
        	$sqlConector = ",";
        }
        
        if($vo->inDoutrina!= null){
        	$retorno.= $sqlConector . voassunto::$nmAtrInDoutrina . " = " . $this->getVarComoNumero($vo->inDoutrina);
        	$sqlConector = ",";
        }
        
        if($vo->inQuestoes!= null){
        	$retorno.= $sqlConector . voassunto::$nmAtrInQuestoes . " = " . $this->getVarComoNumero($vo->inQuestoes);
        	$sqlConector = ",";
        }
        
        $retorno = $retorno . $sqlConector . $vo->getSQLValuesUpdate();
		        
		return $retorno;                
    }
}
?>