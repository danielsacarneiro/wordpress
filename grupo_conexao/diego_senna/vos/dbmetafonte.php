<?php
include_once(caminho_lib. "dbprocesso.obj.php");
include_once (caminho_util."bibliotecaFuncoesPrincipal.php");

// .................................................................................................................

  Class dbmetafonte extends dbprocesso{
    
  	function consultarPorChave($vo, $isHistorico) {
  		$nmTabela = $vo->getNmTabelaEntidade ( $isHistorico ); 		
  		$nmTabelaMateria = vomateria::getNmTabelaStatic(false);
  		$nmTabelaPerfil = voperfil::getNmTabelaStatic(false);
  		$nmTabelaFonte = vomateriafonte::getNmTabelaStatic($isHistorico);
  		
  		$arrayColunasRetornadas = array($nmTabela . ".*",
  				"$nmTabelaMateria.".vomateria::$nmAtrDescricao,
  				"$nmTabelaPerfil.".voperfil::$nmAtrDescricao,
  				"$nmTabelaFonte.".vomateriafonte::$nmAtrDescricao,
  		);  
  		
  		$queryJoin .= "\n INNER JOIN " . $nmTabelaMateria;
  		$queryJoin .= "\n ON ";
  		$queryJoin .= $nmTabelaMateria. "." . vomateria::$nmAtrCd . "=" . $nmTabela . "." . voperfilmateria::$nmAtrCdMateria;
  		  		  		
  		$queryJoin .= "\n INNER JOIN " . $nmTabelaPerfil;
  		$queryJoin .= "\n ON ";
  		$queryJoin .= $nmTabelaPerfil . "." . voperfil::$nmAtrCd . "=" . $nmTabela . "." . voperfilmateria::$nmAtrCdPerfil;
  		
  		$queryJoin .= "\n LEFT JOIN " . $nmTabelaFonte;
  		$queryJoin .= "\n ON ";
  		$queryJoin .= $nmTabelaFonte. "." . vomateriafonte::$nmAtrCdFonte . "=" . $nmTabela. "." . vometafonte::$nmAtrCdFonte;
  		$queryJoin .= "\n AND " . $nmTabelaFonte. "." . vomateriafonte::$nmAtrCdMateria . "=" . $nmTabela. "." . vometafonte::$nmAtrCdMateria;
  		
  		
  		$retorno= $this->consultarPorChaveMontandoQuery ( $vo, $arrayColunasRetornadas, $queryJoin, $isHistorico, true);  		
  		return $retorno;
  	}
  	
  	function consultarTelaConsulta($filtro) {
  		$isHistorico = $filtro->isHistorico;
  		$nmTabelaMetaFonte = vometafonte::getNmTabelaStatic($isHistorico);
  		$nmTabelaPerfil = voperfil::getNmTabelaStatic($isHistorico);
  		$nmTabelaMateria = vomateria::getNmTabelaStatic($isHistorico);
  		$nmTabelaFonte = vomateriafonte::getNmTabelaStatic($isHistorico);
  		$arrayColunasRetornadas = array("*");
  		
  		$queryJoin .= "\n INNER JOIN " . $nmTabelaMateria;
  		$queryJoin .= "\n ON ";
  		$queryJoin .= $nmTabelaMateria. "." . vomateria::$nmAtrCd . "=" . $nmTabelaMetaFonte. "." . vometafonte::$nmAtrCdMateria;
  		
  		$queryJoin .= "\n INNER JOIN " . $nmTabelaPerfil;
  		$queryJoin .= "\n ON ";
  		$queryJoin .= $nmTabelaPerfil. "." . voperfil::$nmAtrCd . "=" . $nmTabelaMetaFonte. "." . vometafonte::$nmAtrCdPerfil;

  		/*$queryJoin .= "\n LEFT JOIN " . $nmTabelaFonte;
  		$queryJoin .= "\n ON ";
  		$queryJoin .= $nmTabelaFonte. "." . vomateriafonte::$nmAtrCdFonte . "=" . $nmTabelaMetaFonte. "." . vometafonte::$nmAtrCdFonte;
  		$queryJoin .= "\n AND " . $nmTabelaFonte. "." . vomateriafonte::$nmAtrCdMateria . "=" . $nmTabelaMetaFonte. "." . vometafonte::$nmAtrCdMateria;*/
  		
  		return parent::consultarMontandoQueryTelaConsulta ( new vometafonte(), $filtro, $arrayColunasRetornadas, $queryJoin );
  	}
  	
    function incluirSQL($vo){
        $arrayAtribRemover = array(
            vomateriafonte::$nmAtrDhInclusao,
            vomateriafonte::$nmAtrDhUltAlteracao
            );
        
        if($vo->sq == null || $vo->sq== ""){
        	$vo->sq= $this->getProximoSequencialChaveComposta(vometafonte::$nmAtrSq, $vo);        	        	
        }      
        
        return $this->incluirQuery($vo, $arrayAtribRemover);
    }    
    
    function getSQLValuesInsert($vo){    	    	
		$retorno = "";
        $retorno.= $this-> getVarComoNumero($vo->sq) . ",";
        $retorno.= $this-> getVarComoNumero($vo->cdMeta) . ",";
        $retorno.= $this-> getVarComoNumero($vo->cdPerfil) . ",";
        $retorno.= $this-> getVarComoNumero($vo->cdMateria) . ",";
        $retorno.= $this-> getVarComoNumero($vo->tpFonte). ",";
        $retorno.= $this-> getVarComoNumero($vo->cdFonte). ",";
        $retorno.= $this-> getVarComoNumero(dominioTpFonte::getTpParametroFontePorTpFonte($vo->tpFonte)). ",";
        $retorno.= $this-> getVarComoNumero($vo->numParamInicio). ",";
        $retorno.= $this-> getVarComoNumero($vo->numParamFim). ",";
        $retorno.= $this-> getVarComoString($vo->obs). ",";
        $retorno.= $this-> getVarComoNumero($vo->numHoras);
        
        $retorno.= $vo->getSQLValuesInsertEntidade();
		        
		return $retorno;                
    }
        
    function getSQLValuesUpdate($vo){        
        $retorno = "";
        $sqlConector = "";
              
        //$vo = new vometafonte();
        
        if($vo->numParamInicio!= null){
        	$retorno.= $sqlConector . vometafonte::$nmAtrNumParamInicio . " = " . $this->getVarComoNumero($vo->numParamInicio);
            $sqlConector = ",";
        }else{
        	$retorno.= $sqlConector . vometafonte::$nmAtrNumParamInicio . " = null";
        	$sqlConector = ",";        	
        }
               
        if($vo->numParamFim!= null){
        	$retorno.= $sqlConector . vometafonte::$nmAtrNumParamFim. " = " . $this->getVarComoNumero($vo->numParamFim);
        	$sqlConector = ",";
        }else{
        	$retorno.= $sqlConector . vometafonte::$nmAtrNumParamFim. " = null";
        	$sqlConector = ",";
        }
        
        if($vo->cdFonte!= null){
        	$retorno.= $sqlConector . vometafonte::$nmAtrCdFonte. " = " . $this->getVarComoNumero($vo->cdFonte);
        	$sqlConector = ",";
        }else{
        	$retorno.= $sqlConector . vometafonte::$nmAtrCdFonte. " = null";
        	$sqlConector = ",";
        }        
        
        if($vo->tpFonte!= null){
        	$retorno.= $sqlConector . vometafonte::$nmAtrTpFonte. " = " . $this->getVarComoNumero($vo->tpFonte);
        	$sqlConector = ",";
        }else{
        	$retorno.= $sqlConector . vometafonte::$nmAtrTpFonte. " = null";
        	$sqlConector = ",";
        }
        
        if($vo->obs!= null){
        	$retorno.= $sqlConector . vometafonte::$nmAtrObs. " = " . $this->getVarComoString($vo->obs);
        	$sqlConector = ",";
        }        	
        
        if($vo->numHoras != null){
        	$retorno.= $sqlConector . vometafonte::$nmAtrNumHoras. " = " . $this->getVarComoNumero($vo->numHoras);
        	$sqlConector = ",";
        }
        
        $retorno = $retorno . $sqlConector . $vo->getSQLValuesUpdate();
		        
		return $retorno;                
    }
}
?>