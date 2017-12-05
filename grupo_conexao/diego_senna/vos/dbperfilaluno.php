<?php
include_once(caminho_lib. "dbprocesso.obj.php");
include_once (caminho_util."bibliotecaFuncoesPrincipal.php");

// .................................................................................................................

  Class dbperfilaluno extends dbprocesso{
    
  	function consultarPorChave($vo, $isHistorico) {
  		$nmTabela = $vo->getNmTabelaEntidade ( $isHistorico ); 		
  		$nmTabelaAluno= vopessoa::getNmTabelaStatic(false);
  		$nmTabelaPerfil = voperfil::getNmTabelaStatic(false);
  		$arrayColunasRetornadas = array($nmTabela . ".*",
  				"$nmTabelaAluno.".vopessoa::$nmAtrNome,
  				"$nmTabelaPerfil.".voperfil::$nmAtrDescricao,
  		);  
  		
  		$queryJoin .= "\n INNER JOIN " . $nmTabelaAluno;
  		$queryJoin .= "\n ON ";
  		$queryJoin .= $nmTabelaAluno. "." . vopessoa::$nmAtrCd . "=" . $nmTabela . "." . voperfilaluno::$nmAtrCdAluno;
  		  		  		
  		$queryJoin .= "\n INNER JOIN " . $nmTabelaPerfil;
  		$queryJoin .= "\n ON ";
  		$queryJoin .= $nmTabelaPerfil . "." . voperfil::$nmAtrCd . "=" . $nmTabela . "." . voperfilaluno::$nmAtrCdPerfil;
  		
  		$retorno= $this->consultarPorChaveMontandoQuery ( $vo, $arrayColunasRetornadas, $queryJoin, $isHistorico, true);  		
  		return $retorno;
  	}
  	
  	function consultarTelaConsulta($filtro) {
  		$isHistorico = $filtro->isHistorico;
  		$nmTabela = voperfilaluno::getNmTabelaStatic($isHistorico);
  		$nmTabelaPerfil = voperfil::getNmTabelaStatic($isHistorico);
  		$nmTabelaPessoa = vopessoa::getNmTabelaStatic($isHistorico);
  		$arrayColunasRetornadas = array("*");
  		
  		$queryJoin .= "\n INNER JOIN " . $nmTabelaPessoa;
  		$queryJoin .= "\n ON ";
  		$queryJoin .= $nmTabelaPessoa. "." . vopessoa::$nmAtrCd . "=" . $nmTabela . "." . voperfilaluno::$nmAtrCdAluno;
  		
  		$queryJoin .= "\n INNER JOIN " . $nmTabelaPerfil;
  		$queryJoin .= "\n ON ";
  		$queryJoin .= $nmTabelaPerfil. "." . voperfil::$nmAtrCd . "=" . $nmTabela . "." . voperfilaluno::$nmAtrCdPerfil;
  		
  		return parent::consultarMontandoQueryTelaConsulta ( new voperfilaluno(), $filtro, $arrayColunasRetornadas, $queryJoin );
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
        $retorno.= $this-> getVarComoNumero($vo->cdAluno) . ",";
        $retorno.= $this-> getVarComoNumero($vo->tpMeta) . ",";
        $retorno.= $this-> getVarComoNumero($vo->numDiasMeta) . ",";
        $retorno.= $this-> getVarComoNumero($vo->numHorasMateriaDia) . ",";
        $retorno.= $this-> getVarComoData($vo->dtInicio);
        
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