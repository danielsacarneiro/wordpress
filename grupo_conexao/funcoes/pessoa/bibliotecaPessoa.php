<?php
include_once("../../config_lib.php");
include_once("dominioVinculoPessoa.php");

function mostrarGridAlunos($colecaoAlunos, $isDetalhamento) {
	// var_dump($colecaoAlunos);
	
	if (is_array ( $colecaoAlunos )) {
		$tamanho = sizeof ( $colecaoAlunos );
	} else {
		$tamanho = 0;
	}
	
	$html = "";
	if ($tamanho > 0) {
		
		$numColunas = 4;
		
		$html .= "<TR>\n";
		$html .= "<TH class='textoseparadorgrupocampos' halign='left' colspan='4'>\n";
		//$html .= "<DIV class='campoformulario' id='div_tramitacao'>&nbsp;&nbsp;Histórico\n";
		
		$html .= "<TABLE id='table_tabeladados' class='tabeladados' cellpadding='0' cellspacing='0'> \n";
		$html .= " <TBODY>  \n";
		$html .= "        <TR>    \n";

		$html .= "<TH class='headertabeladados' width='1%' nowrap>Código</TH>   \n";
		$html .= "<TH class='headertabeladados' width='90%'>Nome</TH> \n";
		$html .= "<TH class='headertabeladados' width='1%'>Doc.</TH> \n";
		if(!$isDetalhamento){
			//$html .= "<TH class='headertabeladados' width='1%'>Excluir</TH> \n";
		}
		$html .= "</TR> \n";
		
		for($i = 0; $i < $tamanho; $i ++) {
			
			$voAtual = new vopessoa();
			$voAtual->getDadosBanco ( $colecaoAlunos [$i] );
			
			if ($voAtual != null) {
				
				$strColecaoAlunos = $voAtual->cd . constantes::$CD_CAMPO_SEPARADOR;
				
				$html .= "<TR class='dados'> \n";
				
				/*if (! $isDetalhamento) {
					$html .= "<TD class='tabeladados'> \n";
					$html .= getHTMLRadioButtonConsulta ( "rdb_alunos", "rdb_alunos", $i );
					$html .= "</TD> \n";
				}*/
				
				$doc = $voAtual->docCPF;
				if($doc == null)
					$doc = $voAtual->docRG;
					
					$html .= "<TD class='tabeladados' nowrap>" . complementarCharAEsquerda ( $voAtual->cd, "0", TAMANHO_CODIGOS ) . "</TD> \n";
					$html .= "<TD class='tabeladados' >" . $voAtual->nome . "</TD> \n";
					$html .= "<TD class='tabeladados' nowrap>" . documentoPessoa::getNumeroDocFormatado($doc) . "</TD> \n";
					if(!$isDetalhamento){
						$html .= "<TD class='tabeladados' nowrap>" .  getBorrachaJS("limparDadosPessoa($voAtual->cd);") . "</TD> \n";
					}
					$html .= "</TR> \n";					
					
			}
		}
		
		if($strColecaoAlunos != null){
			//$html .= "<INPUT TYPE='HIDDEN' NAME='".voturma::$ID_REQ_COLECAO_ALUNOS." VALUE='$strColecaoAlunos'> \n";
		}
		$html .= "</TBODY> \n";
		$html .= "</TABLE> \n";
//		$html .= "</DIV> \n";
		$html .= "</TH>\n";
		$html .= "</TR>\n";
		
	}
	
	return $html;
}

function getComboPessoaVinculo($idCampo, $nmCampo, $cdOpcaoSelecionada, $classCampo, $tagHtml){
	$dominioVinculo = new dominioVinculoPessoa();
	return getComboColecaoGenerico($dominioVinculo->colecao, $idCampo, $nmCampo, $cdOpcaoSelecionada, $classCampo, $tagHtml);
}

function consultarPessoas($colecaoCdPessoa){
	//$voContrato = new vocontrato();
	$filtro = new filtroManterPessoa(false);
	$filtro->colecaoCd = $colecaoCdPessoa;
	$filtro->setaFiltroConsultaSemLimiteRegistro();
	$filtro->cdAtrOrdenacao = vopessoa::$nmAtrNome;
	$filtro->cdOrdenacao = constantes::$CD_ORDEM_CRESCENTE;
	
	$db = new dbpessoa();
	$colecao = $db->consultarFiltroManterPessoa($filtro);
	
	return $colecao;
}

function getCampoContratada($pNmContratada, $pDocContratada, $pChaveContrato){

	$retorno = "Contratado: <INPUT type='text' class='camporeadonly' size=50 readonly value='NÃO ENCONTRADO - VERIFIQUE O CONTRATO'>\n";
	if($pDocContratada != ""){
		
		$doc = new documentoPessoa($pDocContratada);
		$docComMascara = $doc->formata();
		$sizeDoc = strlen($docComMascara);
		if($doc->valida()){
			$sizeDoc = 18;
		}
		
		$javaScript = "onLoad=''";
		$retorno = "Contratado: <INPUT type='text' class='camporeadonly' size=40 readonly value='".$pNmContratada."' ".$javaScript.">\n";
		$retorno .= "&nbsp;CNPJ/CNPF: <INPUT type='text' class='camporeadonlyalinhadodireita' size=".$sizeDoc. " readonly value='". $docComMascara."' ".$javaScript.">\n";
		$retorno .= "<INPUT type='hidden' id='" . vopessoa::$ID_NOME_DADOS_CONTRATADA . "' name='".vopessoa::$ID_NOME_DADOS_CONTRATADA."' value='".$pNmContratada."' >\n";
		$retorno .= "<INPUT type='hidden' id='" . vopessoa::$ID_DOC_DADOS_CONTRATADA. "' name='".vopessoa::$ID_DOC_DADOS_CONTRATADA."' value='".$pDocContratada."' >\n";
	}
	
	//$idContrato = vopessoa::$ID_CONTRATO. "[]";
	$idContrato = vopessoa::$ID_CONTRATO . $pChaveContrato;
	
	//vai em colchete porque podem ser retornados mais de um contrato
	$retorno .= "<INPUT type='hidden' id='" . $idContrato. "' name='".vopessoa::getID_REQ_ColecaoContrato()."' value='".$pChaveContrato."' onLoad='alert(this.name);'>\n";

	return $retorno;
}


?>