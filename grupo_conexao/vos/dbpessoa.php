<?php
include_once (caminho_lib . "dbprocesso.obj.php");
include_once (caminho_util . "bibliotecaFuncoesPrincipal.php");

// .................................................................................................................
// Classe select
// cria um combo select html
class dbpessoa extends dbprocesso {
	function consultarPorChave($vo, $isHistorico) {
		$nmTabela = $vo->getNmTabelaEntidade ( $isHistorico );
		
		$arrayColunasRetornadas = array($nmTabela . ".*",
				vopessoavinculo::getNmTabela () . "." . vopessoavinculo::$nmAtrCd
		);		

		$queryJoin .= "\n INNER JOIN " . vopessoavinculo::getNmTabela ();
		$queryJoin .= "\n ON ";
		$queryJoin .= vopessoavinculo::getNmTabela () . "." . vopessoavinculo::$nmAtrCdPessoa . "=" . $nmTabela . "." . vopessoa::$nmAtrCd;			
		
		$retorno= $this->consultarPorChaveMontandoQuery ( $vo, $arrayColunasRetornadas, $queryJoin, $isHistorico, true);
		
		return $retorno;
	}
	
	function consultarFiltroManterPessoa($filtro) {
		$nmTabela = vopessoa::getNmTabela ();
		$nmTabelaPessoaVinculo = vopessoavinculo::getNmTabela ();
		
		$atributosConsulta = $nmTabela . "." . vopessoa::$nmAtrCd;
		$atributosConsulta .= "," . $nmTabela . "." . vopessoa::$nmAtrNome;
		$atributosConsulta .= "," . $nmTabela . "." . vopessoa::$nmAtrDocCPF;
		$atributosConsulta .= "," . $nmTabelaPessoaVinculo . "." . vopessoavinculo::$nmAtrCd;
		//$atributosConsulta .= "," . $nmTabelaContrato . "." . vocontrato::$nmAtrCdAutorizacaoContrato;
		
		$querySelect = "SELECT " . $atributosConsulta;
		
		$queryFrom = "\n FROM " . $nmTabela;
		$queryFrom .= "\n INNER JOIN " . $nmTabelaPessoaVinculo;
		$queryFrom .= "\n ON " . $nmTabela . "." . vopessoa::$nmAtrCd . "=" . $nmTabelaPessoaVinculo . "." . vopessoavinculo::$nmAtrCdPessoa;
		
		// echo $querySelect."<br>";
		// echo $queryFrom;
		
		return $this->consultarFiltro ( $filtro, $querySelect, $queryFrom, false );
	}
	function consultarFiltroManterPessoaTurma($filtro) {
		$isHistorico = $filtro->isHistorico();
		$nmTabela = vopessoa::getNmTabela ();
		$nmTabelaPessoaVinculo = vopessoavinculo::getNmTabela ();
		$nmTabelaPessoaTurma = vopessoaturma::getNmTabelaStatic($isHistorico);
		$nmTabelaTurma = voturma::getNmTabela ();
		
		$atributosConsulta = $nmTabela . "." . vopessoa::$nmAtrCd;
		$atributosConsulta .= "," . $nmTabela . "." . vopessoa::$nmAtrNome;
		$atributosConsulta .= "," . $nmTabela . "." . vopessoa::$nmAtrDocCPF;
		$atributosConsulta .= "," . $nmTabelaPessoaVinculo . "." . vopessoavinculo::$nmAtrCd;
		$temTurma = $filtro->cdTurma != null;
		
		if($temTurma){
			//echo "tem turma<br>";
			$atributosConsulta .= "," . $nmTabelaTurma . "." . voturma::$nmAtrValor;
			$atributosConsulta .= "," . $nmTabelaPessoaTurma. "." . vopessoaturma::$nmAtrCdTurma;
			$atributosConsulta .= "," . $nmTabelaPessoaTurma. "." . vopessoaturma::$nmAtrCdPessoa;
			$atributosConsulta .= "," . $nmTabelaPessoaTurma. "." . vopessoaturma::$nmAtrObservacao;
			$atributosConsulta .= "," . $nmTabelaPessoaTurma. "." . vopessoaturma::$nmAtrNumParcelas;
			$atributosConsulta .= "," . $nmTabelaPessoaTurma. "." . vopessoaturma::$nmAtrValor;
			//para validacao numa posterior exclusao
			$atributosConsulta .= "," . $nmTabelaPessoaTurma. "." . vopessoaturma::$nmAtrDhUltAlteracao;
			
			if($isHistorico){
				//echo "tem historico<br>";
				$atributosConsulta .= "," . $nmTabelaPessoaTurma. "." . vopessoaturma::$nmAtrSqHist;
			}
			
			//$atributosConsulta .= ",COALESCE(" . $nmTabelaPessoaTurma . "." . vopessoaturma::$nmAtrValor . "," . $nmTabelaTurma. "." . voturma::$nmAtrValor. ") AS " . vopessoaturma::$nmAtrValor;
		}else{
			//echo "NAO tem turma";
			//se a turma nao for passada, eh p consultar apenas as pessoas, ignorando os dados que tenham de turmas existentes
			//por isso o groupby, para nao trazer pessoas repetidas
			$groupby = $nmTabela . "." . vopessoa::$nmAtrCd;
			$filtro->groupby = $groupby;
		}
		
		$querySelect = "SELECT " . $atributosConsulta;
		
		$queryFrom = "\n FROM " . $nmTabela;
		$queryFrom .= "\n INNER JOIN " . $nmTabelaPessoaVinculo;
		$queryFrom .= "\n ON " . $nmTabela . "." . vopessoa::$nmAtrCd . "=" . $nmTabelaPessoaVinculo . "." . vopessoavinculo::$nmAtrCdPessoa;
		
		if($temTurma){		
			$queryFrom .= "\n LEFT JOIN " . $nmTabelaPessoaTurma;
			$queryFrom .= "\n ON " . $nmTabela . "." . vopessoa::$nmAtrCd . "=" . $nmTabelaPessoaTurma . "." . vopessoaturma::$nmAtrCdPessoa;
			$queryFrom .= "\n LEFT JOIN " . $nmTabelaTurma;
			$queryFrom .= "\n ON " . $nmTabelaTurma. "." . voturma::$nmAtrCd . "=" . $nmTabelaPessoaTurma . "." . vopessoaturma::$nmAtrCdTurma;
		}		
		// echo $querySelect."<br>";
		// echo $queryFrom;
		
		return $this->consultarFiltro ( $filtro, $querySelect, $queryFrom, false );
	}
	function consultarPessoaManter($filtro, $validarConsulta) {		
		$nmTabela = vopessoa::getNmTabelaStatic($filtro->isHistorico());
		$atributosConsulta = $nmTabela . "." . vopessoa::$nmAtrCd;
		$atributosConsulta .= "," . $nmTabela . "." . vopessoa::$nmAtrNome;
		$atributosConsulta .= "," . $nmTabela . "." . vopessoa::$nmAtrDocCPF;
		$atributosConsulta .= "," . $nmTabela . "." . vopessoa::$nmAtrEmail;
		$atributosConsulta .= "," . $nmTabela . "." . vopessoa::$nmAtrTel;
		$atributosConsulta .= "," . $nmTabela . "." . vopessoa::$nmAtrInDocumentacaoEmDia;
		$atributosConsulta .= "," . vopessoavinculo::getNmTabela () . "." . vopessoavinculo::$nmAtrCd;
		
		if($filtro->isHistorico()){
			$atributosConsulta .= "," . $nmTabela . "." . vopessoa::$nmAtrSqHist;
		}		
					
		
		$querySelect = "SELECT " . $atributosConsulta;		
		$queryFrom = "\n FROM " . $nmTabela;
		
		$queryFrom .= "\n INNER JOIN " . vopessoavinculo::getNmTabela ();
		$queryFrom .= "\n ON " . $nmTabela . "." . vopessoa::$nmAtrCd . "=" . vopessoavinculo::getNmTabela () . "." . vopessoavinculo::$nmAtrCdPessoa;
		
		// echo $querySelect."<br>";
		// echo $queryFrom;
		// $filtro = new filtroManterPessoa();
		$filtro->groupby = $atributosConsulta;
		
		return $this->consultarFiltro ( $filtro, $querySelect, $queryFrom, $validarConsulta );
	}
	function consultarPessoaPorContrato($filtro) {
		$atributosConsulta = vopessoa::getNmTabela () . "." . vopessoa::$nmAtrCd;
		$atributosConsulta .= "," . vopessoa::getNmTabela () . "." . vopessoa::$nmAtrNome;
		$atributosConsulta .= "," . vopessoa::getNmTabela () . "." . vopessoa::$nmAtrDocCPF;
		$atributosConsulta .= "," . vopessoa::getNmTabela () . "." . vopessoa::$nmAtrEmail;
		$atributosConsulta .= "," . vopessoa::getNmTabela () . "." . vopessoa::$nmAtrTel;
		$atributosConsulta .= "," . vopessoavinculo::getNmTabela () . "." . vopessoavinculo::$nmAtrCd;
		$atributosConsulta .= "," . vocontrato::getNmTabela () . "." . vocontrato::$nmAtrSqContrato;
		
		// $atributoVinculo = "(SELECT )"
		
		$querySelect = "SELECT DISTINCT " . $atributosConsulta;
		
		$queryFrom = "\n FROM " . vopessoa::getNmTabela ();
		$queryFrom .= "\n INNER JOIN " . vopessoavinculo::getNmTabela ();
		$queryFrom .= "\n ON " . vopessoa::getNmTabela () . "." . vopessoa::$nmAtrCd . "=" . vopessoavinculo::getNmTabela () . "." . vopessoavinculo::$nmAtrCdPessoa;
		$queryFrom .= "\n INNER JOIN " . vocontrato::getNmTabela ();
		$queryFrom .= "\n ON ";
		$queryFrom .= vopessoa::getNmTabela () . "." . vopessoa::$nmAtrCd . "=" . vocontrato::getNmTabela () . "." . vocontrato::$nmAtrCdPessoaContratada;
		
		return $this->consultarFiltro ( $filtro, $querySelect, $queryFrom, false );
	}
	
	// o incluir eh implementado para nao usar da voentidade
	// por ser mais complexo
	function incluir($vopessoa) {
		// Start transaction
		$this->cDb->retiraAutoCommit ();
		try {
			$vopessoa = $this->incluirPessoa ( $vopessoa );
			// echo "<br>incluiu pessoa:" . var_dump($vopessoa);
			$this->incluirPessoaVinculo ( $vopessoa );
			// End transaction
			$this->cDb->commit ();
		} catch ( Exception $e ) {
			$vopessoa->excluirFoto();
			$this->cDb->rollback ();
			throw new Exception ( $e->getMessage () );
		}
		
		return $vopessoa;
	}
	function incluirPessoaVinculo($vopessoa) {
		$vopvinculo = new vopessoavinculo ();
		$vopvinculo->cd = $vopessoa->cdVinculo;
		$vopvinculo->cdPessoa = $vopessoa->cd;
		$dbpvinculo = new dbpessoavinculo ();
		$dbpvinculo->cDb = $this->cDb;
		$dbpvinculo->incluir ( $vopvinculo );
		// echo "<br>incluiu pessoa vinculo:" . var_dump($vopvinculo);
	}
	function excluirPessoaVinculo($vopessoa) {
		$vo = new vopessoavinculo ();
		$nmTabela = $vo->getNmTabelaEntidade ( false );
		$query = "DELETE FROM " . $nmTabela;
		$query .= "\n WHERE " . vopessoavinculo::$nmAtrCdPessoa . " = " . $vopessoa->cd;
		
		// echo $query;
		return $this->atualizarEntidade ( $query );
	}
	//artificio para permitir incluir foto na alteracao
	//apenas sera feito isso uma vez
	//assim, todos os registros historicos que nao tinham passam a ter foto
	//tudo isso para garantir a exclusao da foto quando da exclusao de todos os historicos indepedente da ordem de exclusao
	function alterarFotosDeTodosHistoricos($vopessoa) {		
		$vo = new vopessoa ();
		$nmTabela = $vo->getNmTabelaEntidade ( true );
		$query = "UPDATE " . $nmTabela;
		$query .= " SET " . vopessoa::$nmAtrFoto . " = " . getVarComoString($vopessoa->foto);
		$query .= "\n WHERE " . vopessoa::$nmAtrCd . " = " . $vopessoa->cd;
		
		// echo $query;
		return $this->atualizarEntidade ( $query );
	}
	
	function incluirPessoa($vopessoa) {
		$vopessoa->cd = $this->getProximoSequencial ( vopessoa::$nmAtrCd, $vopessoa );
		
		$arrayAtribRemover = array (
				vopessoa::$nmAtrDhInclusao,
				vopessoa::$nmAtrDhUltAlteracao 
		);
		
		$query = $this->incluirQuery ( $vopessoa, $arrayAtribRemover );
		$retorno = $this->cDb->atualizar ( $query );
		
		return $vopessoa;
	}
	
	// o alterar eh implementado para nao usar da voentidade
	// por ser mais complexo
	function alterar($vopessoa) {
		// Start transaction
		$this->cDb->retiraAutoCommit ();
		try {
			$foto = $vopessoa->foto;
			$this->excluirPessoaVinculo ( $vopessoa );
			$this->incluirPessoaVinculo ( $vopessoa );
						
			parent::alterar ( $vopessoa );
			
			//se foto for nao nulo, quer dizer que antes era nulo e o sistema permitiu alterar para passar a ter uma foto
			//isso so pode acontecer no caso de na inclusao nao ter sido incluida foto!
			if( $foto != null){
				$vopessoa->foto = $foto;
				$this->alterarFotosDeTodosHistoricos( $vopessoa );
			}
			
			// End transaction
			$this->cDb->commit ();
		} catch ( Exception $e ) {
			$vopessoa->excluirFoto();
			$this->cDb->rollback ();
			throw new Exception ( $e->getMessage () );
		}
		
		return $vopessoa;
	}
		
	// o excluir eh implementado para nao usar da voentidade
	// por ser mais complexo
	function excluir($vopessoa) {
		// Start transaction
		$this->cDb->retiraAutoCommit ();
		try {
			$permiteExcluirPrincipal = $this->permiteExclusaoPrincipal($vopessoa);
			if($permiteExcluirPrincipal){
				//echo "excluiu";
				$this->excluirPessoaVinculo ( $vopessoa );
			}
			
			parent::excluir ( $vopessoa );			
			// End transaction
			$this->cDb->commit ();
			
			if($permiteExcluirPrincipal){				
				//se tudo ocorrer bem, e a exclusao for permitida, remove a foto
				$vopessoa->excluirFoto();
			}
			
		} catch ( Exception $e ) {
			$this->cDb->rollback ();
			throw new Exception ( $e->getMessage () );
		}
		
		return $vopessoa;
	}
	function getSQLValuesInsert($vopessoa) {
		$retorno = "";
		// $retorno.= $this-> getProximoSequencial(vopessoa::$nmAtrCd, $vopessoa) . ",";
		$retorno .= $this->getVarComoNumero ( $vopessoa->cd ) . ",";
		$retorno .= $this->getVarComoString ( strtoupper($vopessoa->nome) ) . ",";
		$retorno .= $this->getVarComoString ( strtoupper ($vopessoa->responsavel) ) . ",";
		$retorno .= $this->getVarComoString ( $vopessoa->docCPF ) . ",";
		$retorno .= $this->getVarComoString ( $vopessoa->docRG ) . ",";
		$retorno .= $this->getVarComoData ( $vopessoa->dtNascimento) . ",";
		$retorno .= $this->getVarComoString ( $vopessoa->tel ) . ",";
		$retorno .= $this->getVarComoString ( $vopessoa->telWapp ) . ",";
		$retorno .= $this->getVarComoString ( $vopessoa->email ) . ",";
		$retorno .= $this->getVarComoString ( $vopessoa->endereco ) . ",";
		$retorno .= $this->getVarComoString ( $vopessoa->bairro ) . ",";
		$retorno .= $this->getVarComoString ( $vopessoa->cidade ) . ",";
		$retorno .= $this->getVarComoString ( strtoupper($vopessoa->uf) ) . ",";
		$retorno .= $this->getVarComoString ( $vopessoa->foto) . ",";
		$retorno .= $this->getVarComoString ( $vopessoa->obs ) . ",";
		$retorno .= $this->getVarComoString ( $vopessoa->inDocumentacaoEmdia);
		
		$retorno .= $vopessoa->getSQLValuesInsertEntidade ();
		
		return $retorno;
	}
	function getSQLValuesUpdate($vo) {
		$retorno = "";
		$sqlConector = "";
				
		if ($vo->nome != null) {
			$retorno .= $sqlConector . vopessoa::$nmAtrNome . " = " . $this->getVarComoString ( $vo->nome );
			$sqlConector = ",";
		}
		
		if ($vo->responsavel != null) {
			$retorno .= $sqlConector . vopessoa::$nmAtrResponsavel . " = " . $this->getVarComoString ( $vo->responsavel );
			$sqlConector = ",";
		}
		
		if ($vo->docCPF != null) {
			$retorno .= $sqlConector . vopessoa::$nmAtrDocCPF . " = " . $this->getVarComoString ( $vo->docCPF );
			$sqlConector = ",";
		}
		
		if ($vo->docRG != null) {
			$retorno .= $sqlConector . vopessoa::$nmAtrDocRG . " = " . $this->getVarComoString ( $vo->docRG);
			$sqlConector = ",";
		}
		
		if ($vo->dtNascimento != null) {
			$retorno .= $sqlConector . vopessoa::$nmAtrDtNascimento . " = " . $this->getVarComoData ( $vo->dtNascimento);
			$sqlConector = ",";
		}		
		
		if ($vo->tel != null) {
			$retorno .= $sqlConector . vopessoa::$nmAtrTel . " = " . $this->getVarComoString ( $vo->tel );
			$sqlConector = ",";
		}
		
		if ($vo->telWapp != null) {
			$retorno .= $sqlConector . vopessoa::$nmAtrTelWapp . " = " . $this->getVarComoString ( $vo->telWapp );
			$sqlConector = ",";
		}
		
		if ($vo->email != null) {
			$retorno .= $sqlConector . vopessoa::$nmAtrEmail . " = " . $this->getVarComoString ( $vo->email );
			$sqlConector = ",";
		}
		
		if ($vo->endereco != null) {
			$retorno .= $sqlConector . vopessoa::$nmAtrEndereco . " = " . $this->getVarComoString ( $vo->endereco );
			$sqlConector = ",";
		}
		
		if ($vo->bairro != null) {
			$retorno .= $sqlConector . vopessoa::$nmAtrBairro . " = " . $this->getVarComoString ( $vo->bairro );
			$sqlConector = ",";
		}
		
		if ($vo->cidade != null) {
			$retorno .= $sqlConector . vopessoa::$nmAtrCidade . " = " . $this->getVarComoString ( $vo->cidade );
			$sqlConector = ",";
		}
		
		if ($vo->uf != null) {
			$retorno .= $sqlConector . vopessoa::$nmAtrUF . " = " . $this->getVarComoString ( $vo->uf );
			$sqlConector = ",";
		}
		
		if ($vo->foto != null) {
			$retorno .= $sqlConector . vopessoa::$nmAtrFoto . " = " . $this->getVarComoString ( $vo->foto );
			$sqlConector = ",";
		}
		
		if ($vo->obs != null) {
			$retorno .= $sqlConector . vopessoa::$nmAtrObservacao . " = " . $this->getVarComoString ( $vo->obs );
			$sqlConector = ",";
		}
		
		if ($vo->inDocumentacaoEmdia!= null) {
			$retorno .= $sqlConector . vopessoa::$nmAtrInDocumentacaoEmDia . " = " . $this->getVarComoString ( $vo->inDocumentacaoEmdia);
			$sqlConector = ",";
		}
		
		$retorno = $retorno . $sqlConector . $vo->getSQLValuesUpdate ();
		
		return $retorno;
	}
	
	/**
	 * FUNCOES DE IMPORTACAO EXCLUSIVA
	 */
	function importar($linha) {
		$vo = new vopessoa ();
		
		$atributosInsert = $vo->getTodosAtributos ();
		$arrayAtribRemover = array (
				vopessoa::$nmAtrCd,
				vopessoa::$nmAtrDhInclusao,
				vopessoa::$nmAtrDhUltAlteracao,
				vopessoa::$nmAtrCdUsuarioInclusao,
				vopessoa::$nmAtrCdUsuarioUltAlteracao 
		);
		// var_dump($arrayAtribRemover);
		$atributosInsert = removeColecaoAtributos ( $atributosInsert, $arrayAtribRemover );
		$atributosInsert = getColecaoEntreSeparador ( $atributosInsert, "," );
		
		$query = " INSERT INTO " . $vo->getNmTabela () . " \n";
		$query .= " (";
		$query .= $atributosInsert;
		$query .= ") ";
		
		$query .= " \nVALUES(";
		$query .= $this->getAtributosInsertImportacaoPlanilha ( $linha );
		$query .= ")";
		
		// echo $query;
		$retorno = $this->cDb->atualizarImportacao ( $query );
		return $retorno;
	}
	function getAtributosInsertImportacaoPlanilha($linha) {
		$nome = $linha ["B"];
		$tel = $linha ["D"];
		$email = $linha ["E"];
		$doc = null;
		$id = null;
		$endereco = null;
		
		// CUIDADO COM A ORDEM
		// DEVE ESTAR IGUAL A getAtributosFilho()
		$retorno = "";
		// $retorno.= $this-> getVarComoNumero($cd) . ",";
		$retorno .= $this->getVarComoNumero ( $id ) . ",";
		$retorno .= $this->getVarComoString ( $nome ) . ",";
		$retorno .= $this->getVarComoString ( $doc ) . ",";
		$retorno .= $this->getVarComoString ( $tel ) . ",";
		$retorno .= $this->getVarComoString ( $email ) . ",";
		$retorno .= $this->getVarComoString ( $endereco );
		
		return $retorno;
	}
}
?>