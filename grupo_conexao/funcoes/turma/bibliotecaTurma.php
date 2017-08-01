<?php
function mostrarGridAlunos($colecaoAlunos, $isDetalhamento) {
	// var_dump($colecaoAlunos);
	
	if (is_array ( $colecaoAlunos )) {
		$tamanho = sizeof ( $colecaoAlunos );
	} else {
		$tamanho = 0;
	}
	
	$html = "";
	$html .= "<TR>\n";
	$html .= "<TH class='textoseparadorgrupocampos' halign='left' colspan='4'>\n";
	
	if ($tamanho > 0) {
		
		$numColunas = 4;
		
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
					
					//o campo nome eh um array porque sao varias pessoas a incluir
					$html .= "<INPUT TYPE='HIDDEN' NAME='".voturma::$ID_REQ_COLECAO_ALUNOS."[]' VALUE='" . $voAtual->cd. "'> \n";
					$html .= "</TR> \n";
			}
		}
		
		$html .= "</TBODY> \n";
		$html .= "</TABLE> \n";
		//		$html .= "</DIV> \n";
	}else{
		$msg = "&nbsp;Selecione alunos clicando na lupa acima.";
		if ($isDetalhamento)
			$msg = "&nbsp;Não há alunos para exibir.";
			
			$html .= $msg;
	}
	
	$html .= "</TH>\n";
	$html .= "</TR>\n";
	
	return $html;
}

?>