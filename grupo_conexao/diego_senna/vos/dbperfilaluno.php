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
  	
  	function consultarTelaConsultaMontagem($filtro) {
  		$isHistorico = $filtro->isHistorico;
  		$nmTabela = voperfilaluno::getNmTabelaStatic($isHistorico);
  		$nmTabelaPerfil = voperfil::getNmTabelaStatic($isHistorico);
  		$nmTabelaPerfilMateria = voperfilmateria::getNmTabelaStatic($isHistorico);
  		$nmTabelaMateria = vomateria::getNmTabelaStatic($isHistorico);
  		$nmTabelaPessoa = vopessoa::getNmTabelaStatic($isHistorico);
  		$nmTabelaMeta = vometafonte::getNmTabelaStatic($isHistorico);
  		
  		$atributosGroupBy = array("$nmTabelaMeta." . vometafonte::$nmAtrCdPerfil
  				,"$nmTabelaMeta.".vometafonte::$nmAtrCdMateria
  				,"$nmTabela.".voperfilaluno::$nmAtrCdAluno);
  		
  		$arrayColunasRetornadas = array(
  				//"SUM(". "$nmTabelaMeta.".vometafonte::$nmAtrNumHoras . ") AS " . $filtro::$nmColNumHorasDefinidas,
  				"$nmTabelaPerfilMateria.".voperfilmateria::$nmAtrCarga,
  				"$nmTabelaPerfil.".voperfil::$nmAtrDescricao,
  				"$nmTabelaMateria.".vomateria::$nmAtrDescricao,
  				"$nmTabelaPessoa.".vopessoa::$nmAtrNome,
  		);
  		
  		$arrayColunasRetornadas = array_merge($atributosGroupBy,$arrayColunasRetornadas);
  		
  		$queryJoin .= "\n INNER JOIN " . $nmTabelaPerfilMateria;
  		$queryJoin .= "\n ON ";
  		$queryJoin .= $nmTabelaPerfilMateria. "." . voperfilmateria::$nmAtrCdPerfil . "=" . $nmTabela . "." . voperfilaluno::$nmAtrCdPerfil;
  		
  		$queryJoin .= "\n INNER JOIN " . $nmTabelaMeta;
  		$queryJoin .= "\n ON ";
  		$queryJoin .= $nmTabelaMeta. "." . vometafonte::$nmAtrCdPerfil . "=" . $nmTabela . "." . voperfilaluno::$nmAtrCdPerfil;
  		$queryJoin .= "\n AND ";
  		$queryJoin .= $nmTabelaMeta. "." . vometafonte::$nmAtrCdMateria . "=" . $nmTabelaPerfilMateria. "." . voperfilmateria::$nmAtrCdMateria;
  		
  		$queryJoin .= "\n INNER JOIN " . $nmTabelaMateria;
  		$queryJoin .= "\n ON ";
  		$queryJoin .= $nmTabelaPerfilMateria. "." . voperfilmateria::$nmAtrCdMateria . "=" . $nmTabelaMateria. "." . vomateria::$nmAtrCd;
  		
  		$queryJoin .= "\n INNER JOIN " . $nmTabelaPessoa;
  		$queryJoin .= "\n ON ";
  		$queryJoin .= $nmTabelaPessoa. "." . vopessoa::$nmAtrCd . "=" . $nmTabela . "." . voperfilaluno::$nmAtrCdAluno;
  		
  		$queryJoin .= "\n INNER JOIN " . $nmTabelaPerfil;
  		$queryJoin .= "\n ON ";
  		$queryJoin .= $nmTabelaPerfil. "." . voperfil::$nmAtrCd . "=" . $nmTabela . "." . voperfilaluno::$nmAtrCdPerfil;
  		
  		$filtro->groupby = $atributosGroupBy;
  		
  		return parent::consultarMontandoQueryTelaConsulta ( new voperfilaluno(), $filtro, $arrayColunasRetornadas, $queryJoin );
  	}
  	
  	function consultarTelaConsultaCalendario($filtro) {
  		$isHistorico = $filtro->isHistorico;
  		$nmTabela = voperfilaluno::getNmTabelaStatic($isHistorico);
  		$nmTabelaPerfil = voperfil::getNmTabelaStatic($isHistorico);
  		$nmTabelaPerfilMateria = voperfilmateria::getNmTabelaStatic($isHistorico);
  		$nmTabelaMateria = vomateria::getNmTabelaStatic($isHistorico);
  		$nmTabelaPessoa = vopessoa::getNmTabelaStatic($isHistorico);
  		
  		/*$atributosGroupBy = array("$nmTabelaMeta." . vometafonte::$nmAtrCdPerfil
  				,"$nmTabelaMeta.".vometafonte::$nmAtrCdMateria
  				,"$nmTabela.".voperfilaluno::$nmAtrCdAluno);*/
  		
  		$arrayColunasRetornadas = array(
  				filtroConsultarMontagem::$nmColNumCargaTotal,
  				"$nmTabela.".voperfilaluno::$nmAtrNumDiasMeta,
  				"$nmTabela.".voperfilaluno::$nmAtrNumHorasMateriaDia,
  				"$nmTabela.".voperfilaluno::$nmAtrNumHorasDia,
  				"$nmTabelaPerfilMateria.".voperfilmateria::$nmAtrCarga,
  				"$nmTabelaPerfil.".voperfil::$nmAtrDescricao,
  				"$nmTabelaPerfil.".voperfil::$nmAtrCd,
  				"$nmTabelaMateria.".vomateria::$nmAtrDescricao,
  				"$nmTabelaPessoa.".vopessoa::$nmAtrNome,  				
  				"$nmTabelaPessoa.".vopessoa::$nmAtrCd,
  		);
  		
  		//$arrayColunasRetornadas = array_merge($atributosGroupBy,$arrayColunasRetornadas);
  		
  		$queryJoin .= "\n INNER JOIN " . $nmTabelaPerfilMateria;
  		$queryJoin .= "\n ON ";
  		$queryJoin .= $nmTabelaPerfilMateria. "." . voperfilmateria::$nmAtrCdPerfil . "=" . $nmTabela . "." . voperfilaluno::$nmAtrCdPerfil;
  		  		
  		$queryJoin .= "\n INNER JOIN " . $nmTabelaMateria;
  		$queryJoin .= "\n ON ";
  		$queryJoin .= $nmTabelaPerfilMateria. "." . voperfilmateria::$nmAtrCdMateria . "=" . $nmTabelaMateria. "." . vomateria::$nmAtrCd;
  		
  		$queryJoin .= "\n INNER JOIN " . $nmTabelaPessoa;
  		$queryJoin .= "\n ON ";
  		$queryJoin .= $nmTabelaPessoa. "." . vopessoa::$nmAtrCd . "=" . $nmTabela . "." . voperfilaluno::$nmAtrCdAluno;
  		
  		$queryJoin .= "\n INNER JOIN " . $nmTabelaPerfil;
  		$queryJoin .= "\n ON ";
  		$queryJoin .= $nmTabelaPerfil. "." . voperfil::$nmAtrCd . "=" . $nmTabela . "." . voperfilaluno::$nmAtrCdPerfil;
  		
  		$atributosGroup = voperfilmateria::$nmAtrCdPerfil;
  		$NM_TAB_PERFIL_PESOTOTAL = "NM_TAB_PERFIL_PESOTOTAL";  		
  		$queryJoin .= "\n INNER JOIN (";
  		$queryJoin .= " SELECT SUM(" . voperfilmateria::$nmAtrCarga. ") AS " . filtroConsultarMontagem::$nmColNumCargaTotal . "," . $atributosGroup . " FROM " . $nmTabelaPerfilMateria . " GROUP BY " . $atributosGroup;
  		$queryJoin .= ") $NM_TAB_PERFIL_PESOTOTAL";
  		$queryJoin .= "\n ON " . $nmTabelaPerfilMateria. "." . voperfilmateria::$nmAtrCdPerfil . " = $NM_TAB_PERFIL_PESOTOTAL." . voperfilmateria::$nmAtrCdPerfil;  		
  		
  		
  		//$filtro->groupby = $atributosGroupBy;
  		
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
        $retorno.= $this-> getVarComoNumero($vo->numHorasDia) . ",";
        $retorno.= $this-> getVarComoData($vo->dtInicio);
        
        $retorno.= $vo->getSQLValuesInsertEntidade();
		        
		return $retorno;                
    }
        
    function getSQLValuesUpdate($vo){        
        $retorno = "";
        $sqlConector = "";
                
        if($vo->numDiasMeta!= null){
        	$retorno.= $sqlConector . voperfilaluno::$nmAtrNumDiasMeta . " = " . $this->getVarComoNumero($vo->numDiasMeta);
            $sqlConector = ",";
        }
               
        if($vo->numHorasMateriaDia!= null){
        	$retorno.= $sqlConector . voperfilaluno::$nmAtrNumHorasMateriaDia . " = " . $this->getVarComoNumero($vo->numHorasMateriaDia);
        	$sqlConector = ",";
        }
        
        if($vo->numHorasDia!= null){
        	$retorno.= $sqlConector . voperfilaluno::$nmAtrNumHorasDia . " = " . $this->getVarComoNumero($vo->numHorasDia);
        	$sqlConector = ",";
        }
        
        $retorno = $retorno . $sqlConector . $vo->getSQLValuesUpdate();
		        
		return $retorno;                
    }
}
?>