<?php
include_once(caminho_lib. "dbprocesso.obj.php");
include_once (caminho_util."bibliotecaFuncoesPrincipal.php");

// .................................................................................................................

  Class dbperfilmateria extends dbprocesso{
    
  	function consultarPorChave($vo, $isHistorico) {
  		$nmTabela = $vo->getNmTabelaEntidade ( $isHistorico ); 		
  		$nmTabelaMateria = vomateria::getNmTabelaStatic(false);
  		$nmTabelaPerfil = voperfil::getNmTabelaStatic(false);
  		$arrayColunasRetornadas = array($nmTabela . ".*",
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
  		$nmTabela = voperfilmateria::getNmTabelaStatic($isHistorico);
  		$nmTabelaPerfil = voperfil::getNmTabelaStatic($isHistorico);
  		$nmTabelaMateria = vomateria::getNmTabelaStatic($isHistorico);
  		$arrayColunasRetornadas = array("*");
  		
  		$queryJoin .= "\n INNER JOIN " . $nmTabelaMateria;
  		$queryJoin .= "\n ON ";
  		$queryJoin .= $nmTabelaMateria. "." . vomateria::$nmAtrCd . "=" . $nmTabela . "." . voperfilmateria::$nmAtrCdMateria;
  		
  		$queryJoin .= "\n INNER JOIN " . $nmTabelaPerfil;
  		$queryJoin .= "\n ON ";
  		$queryJoin .= $nmTabelaPerfil. "." . voperfil::$nmAtrCd . "=" . $nmTabela . "." . voperfilmateria::$nmAtrCdPerfil;
  		
  		return parent::consultarMontandoQueryTelaConsulta ( new voperfilmateria(), $filtro, $arrayColunasRetornadas, $queryJoin );
  	}
  	
    function incluirSQL($vo){
        $arrayAtribRemover = array(
            vomateriafonte::$nmAtrDhInclusao,
            vomateriafonte::$nmAtrDhUltAlteracao
            );
        
        /*if($vo->cdFonte == null || $vo->cdFonte== ""){
        	$vo->cdFonte= $this->getProximoSequencialChaveComposta(vomateriafonte::$nmAtrCdMateria, $vo);        	        	
        } */       
        
        return $this->incluirQuery($vo, $arrayAtribRemover);
    }    

    function getSQLValuesInsert($vo){
		$retorno = "";
        $retorno.= $this-> getVarComoNumero($vo->cdPerfil) . ",";
        $retorno.= $this-> getVarComoNumero($vo->cdMateria) . ",";
        $retorno.= $this-> getVarComoNumero($vo->carga);
        
        $retorno.= $vo->getSQLValuesInsertEntidade();
		        
		return $retorno;                
    }
        
    function getSQLValuesUpdate($vo){        
        $retorno = "";
        $sqlConector = "";
                
        if($vo->carga!= null){
        	$retorno.= $sqlConector . voperfilmateria::$nmAtrCarga . " = " . $this->getVarComoNumero($vo->carga);
            $sqlConector = ",";
        }
               
        $retorno = $retorno . $sqlConector . $vo->getSQLValuesUpdate();
		        
		return $retorno;                
    }
}
?>