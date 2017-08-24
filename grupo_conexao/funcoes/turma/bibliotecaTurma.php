<?php
function incluirChaveNoArray($chave, $array, $cdTurma = null) {
	if ($chave != null && ! existeItemNoArray ( $chave, $array )) {
		
		$vopessoaturma = new vopessoaturma ();
		$vopessoaturma->cdPessoa = vopessoa::getCdPessoaChaveExplode ( $chave );
		if ($cdTurma != null) {
			$vopessoaturma->cdTurma = $cdTurma;
		}
		// $array [] = vopessoa::getCdPessoaChaveExplode ( $chave );
		$array [$vopessoaturma->cdPessoa] = $vopessoaturma;
	}
	
	return $array;
}
function incluirArrayNoArray($arrayAIncluir, $array, $cdTurma = null) {
	foreach ( $arrayAIncluir as $chave ) {
		$array = incluirChaveNoArray ( $chave, $array, $cdTurma );
	}
	
	// var_dump ( $array );
	
	return $array;
}
function removeChave($array, $chave) {
	$retorno = array ();
	
	foreach ( $array as $vopessoaturma ) {
		$chaveAtual = $vopessoaturma->cdPessoa;
		// echo "chave atual = $chaveAtual , chave a remover = $chave <br>";
		if ($chaveAtual != $chave) {
			// echo "removeu $chave";
			$retorno [$chaveAtual] = $vopessoaturma;
		}
	}
	
	return $retorno;
}
function getCdTurma($colecaoPessoaTurma) {
	return $colecaoPessoaTurma [0]->cdTurma;
}
function getColecaoCdPessoa($colecaoPessoaTurma) {
	$retorno = "";
	foreach ( $colecaoPessoaTurma as $vopessoa ) {
		$retorno [] = $vopessoa->cdPessoa;
	}
	return $retorno;
}
function getColecaoAlunosVOPessoaTurma() {
	// cria um OTD para guardar os valores inseridos na pagina
	$retorno = getObjetoSessao ( voturma::$ID_REQ_COLECAO_ALUNOS );
	$colecaoValores = @$_POST [vopessoaturma::$nmAtrValor];
	$colecaoNumParcelas = @$_POST [vopessoaturma::$nmAtrNumParcelas];
	$colecaoCdPessoas = @$_POST [vopessoaturma::$nmAtrCdPessoa];
	
	//var_dump($colecaoCdPessoas);
	
	for($i = 0; $i < count ( $colecaoCdPessoas ); $i ++) {
		$vopessoaturma = new vopessoaturma ();
		$cdPessoa = $colecaoCdPessoas [$i];
		$vopessoaturma = $retorno [$cdPessoa];
		$vopessoaturma->valor = getVarComoDecimal($colecaoValores [$i]);
		$vopessoaturma->numParcelas = $colecaoNumParcelas [$i];
		$retorno [$cdPessoa] = $vopessoaturma;
	}
	
	return $retorno;
}
function getColecaoVOPessoaTurmaFromRecordset($colecaoAlunosVOPessoaTurma) {
	$recordSet = consultarPessoasTurma ( $colecaoAlunosVOPessoaTurma );
		
	$retorno = "";
	foreach ( $recordSet as $registrobanco ) {
		$voPessoaTurma = new vopessoaturma ();
		$voPessoaTurma->getDadosBanco ( $registrobanco );
		$vopessoa = new vopessoa ();
		$vopessoa->getDadosBanco ( $registrobanco );
		$voturma = new voturma ();
		$voturma->getDadosBanco ( $registrobanco );	
						
		$voPesTurmaTela = $colecaoAlunosVOPessoaTurma[$voPessoaTurma->cdPessoa];
		//echo $voPesTurmaTela->valor;
		
		$voPessoaTurma->valor = $voPesTurmaTela->valor;
		$voPessoaTurma->numParcelas = $voPesTurmaTela->numParcelas;
		// cria em execucao um OTD
		$voPessoaTurma->vopessoa = $vopessoa;
		$voPessoaTurma->voturma = $voturma;		
		
		$retorno [$voPessoaTurma->cdPessoa] = $voPessoaTurma;
	}
	return $retorno;
}
function consultarPessoasTurma($colecaoPessoaTurma) {
	if (! isColecaoVazia ( $colecaoPessoaTurma )) {
		// var_dump($colecaoPessoaTurma);		
		$colecaoCdPessoa = getColecaoCdPessoa ( $colecaoPessoaTurma );
		$cdTurma = getCdTurma ( $colecaoPessoaTurma );		
		$filtro = new filtroManterPessoa ( false );
		$filtro->colecaoCd = $colecaoCdPessoa;
		// se tiver turma, traz as informacoes da turmaxpessoa
		$filtro->cdTurma = $cdTurma;
		$colecao = consultarPessoasTurmaFiltroManterPessoa($filtro);
	}
	
	return $colecao;
}
function consultarPessoasTurmaPorVOTurma($voturma) {
	if ($voturma != null) {
		$cdTurma = $voturma->cd;
		$filtro = new filtroManterPessoa ( false );
		$filtro->cdTurma = $cdTurma;
		$colecao = consultarPessoasTurmaFiltroManterPessoa($filtro);
	}
	
	return $colecao;
}
function consultarPessoasTurmaFiltroManterPessoa($filtro) {
	if ($filtro!= null) {
		$filtro->setaFiltroConsultaSemLimiteRegistro ();
		$filtro->cdAtrOrdenacao = vopessoa::$nmAtrNome;
		$filtro->cdOrdenacao = constantes::$CD_ORDEM_CRESCENTE;
		
		$db = new dbpessoa ();
		$colecao = $db->consultarFiltroManterPessoaTurma ( $filtro );
	}
	
	return $colecao;
}
function imprimeGridAlunosTurma($voCamposDadosPessoaAjax) {
	$chave = @$_POST ["chavePessoa"];
	$funcao = @$_POST ["funcao"];
	$operacao = @$_POST ["operacao"];
	$isConsultarPessoa = false;
	
	// pega do $voCamposDadosPessoaAjax que foi chamado no detalhamento da turma
	// mas somente se a $chave nao tiver sido passada, quando tera alguma operacao sobre a colecao
	if ($voCamposDadosPessoaAjax != null) {
		$colecaoAlunosVOPessoaTurma = $voCamposDadosPessoaAjax->colecaoAlunos;
		$cdTurma = $voCamposDadosPessoaAjax->cd;
	} else {
		$isConsultarPessoa = true;
		// a chave da pessoa vem com o cdturma
		$cdTurma = @$_GET ["cdTurma"];
		if (existeObjetoSessao ( voturma::$ID_REQ_COLECAO_ALUNOS )) {
			$colecaoAlunosVOPessoaTurma = getColecaoAlunosVOPessoaTurma ();
			// var_dump($colecaoAlunosVOPessoaTurma);
			// echo "pegou da sessao";
		}
	}
	
	// echo "turma = $cdTurma chave = $chave and funcao = $funcao and operacao = $operacao";
	
	$html = "";
	$isInclusao = $operacao == constantes::$CD_FUNCAO_INCLUIR;
	$isDetalhamento = $funcao == constantes::$CD_FUNCAO_DETALHAR || $funcao == constantes::$CD_FUNCAO_EXCLUIR;
	
	// -1 eh o codigo para limpar o grid
	$isLimpar = $chave == - 1;
	// $temOperacaoComAColecao = $chave != null;
	
	if (! $isLimpar) {
		
		if ($chave != null) {

			if ($isInclusao) {
				// aqui vai a chave completa para permitir a inclusao de multiplas pessoas
				$colecaoAlunosVOPessoaTurma = incluirArrayNoArray ( $chave, $colecaoAlunosVOPessoaTurma );
			} else {
				// se for exclusao, a chave eh o cdpessoa
				$cdPessoaAexcluir = $chave;
				$colecaoAlunosVOPessoaTurma = removeChave ( $colecaoAlunosVOPessoaTurma, $cdPessoaAexcluir );
			}
		}
	} else {
		$colecaoAlunosVOPessoaTurma = null;
	}
	
	if (! isColecaoVazia ( $colecaoAlunosVOPessoaTurma )) {
		// var_dump($colecaoAlunosVOPessoaTurma);
		if($isConsultarPessoa){
			$colecaoAlunosVOPessoaTurma= getColecaoVOPessoaTurmaFromRecordset ( $colecaoAlunosVOPessoaTurma );
		}
		putObjetoSessao ( voturma::$ID_REQ_COLECAO_ALUNOS, $colecaoAlunosVOPessoaTurma);
	} else {
		removeObjetoSessao ( voturma::$ID_REQ_COLECAO_ALUNOS );
	}
	$html = mostrarGridAlunos ( $colecaoAlunosVOPessoaTurma, $isDetalhamento );
	
	return $html;
}
function getValorTotal($vopessoaturma) {
	;
}
function mostrarGridAlunos($colecaoAlunos, $isDetalhamento) {
	// var_dump($colecaoAlunos);
	if (is_array ( $colecaoAlunos )) {
		$tamanho = sizeof ( $colecaoAlunos );
		
		// var_dump($colecaoAlunos);
	} else {
		$tamanho = 0;
	}
	
	$html = "";
	$html .= "<SCRIPT language='JavaScript' type='text/javascript' src='" . caminho_js . "tooltip.js'></SCRIPT>\n";
	$html .= "<TR>\n";
	$html .= "<TH class='textoseparadorgrupocampos' halign='left' colspan='4'>\n";
	
	$html .= "<TABLE id='table_tabeladados' class='tabeladados' cellpadding='0' cellspacing='0'> \n";
	$html .= " <TBODY>  \n";
	$html .= "        <TR>    \n";
	
	if ($tamanho > 0) {
		
		$numColunas = 7;
		
		$html .= "<TH class='headertabeladados' width='1%' nowrap>C�digo</TH>   \n";
		$html .= "<TH class='headertabeladados' width='90%'>Nome</TH> \n";
		$html .= "<TH class='headertabeladados' width='1%'>Doc.</TH> \n";
		$html .= "<TH class='headertabeladados' width='1%'>Parcelas</TH> \n";
		$html .= "<TH class='headertabeladados' width='1%'>Valor</TH> \n";
		$html .= "<TH class='headertabeladados' width='1%'>Total</TH> \n";

		$html .= "</TR> \n";
		
		$i = 0;
		$valorReceita = 0;
		foreach ( $colecaoAlunos as $vopessoaturmaatual ) {
						
			$voPessoaTurma = $vopessoaturmaatual;
			$voAtual = $voPessoaTurma->vopessoa;
			$voturma = $voPessoaTurma->voturma;
			if($voturma->valor == null){
				$voturma->valor = @$_POST["valorTurma"];
			}
			
			if ($voAtual != null) {
				
				$html .= "<TR class='dados'> \n";
				
				$classValor = "camporeadonlyalinhadodireita";
				$readonly = " readonly";
				if (! $isDetalhamento) {
					/*
					 * $html .= "<TD class='tabeladados'> \n";
					 * $html .= getHTMLRadioButtonConsulta ( "rdb_alunos", "rdb_alunos", $i );
					 * $html .= "</TD> \n";
					 */
					
					//echo "NAO eh detalhamento";
					$classValor = "camponaoobrigatorioalinhadodireita";
					$javaScript = " onChange=\"formataCamposPagamento('$voAtual->cd');\"";
					$readonly = "required";
				}else{
					//echo "eh detalhamento";
				}
				
				$doc = $voAtual->docCPF;
				if ($doc == null) {
					$doc = $voAtual->docRG;
				}
				
				$classColuna = "tabeladados";
				$valorAcompararPessoa = $voPessoaTurma->valor * $voPessoaTurma->numParcelas;
				//echo "voturma valor = $voturma->valor && vopessoaturma valor = $voPessoaTurma->valor";
				
				$temValorDiferenciado = $voturma->valor != $valorAcompararPessoa;
				$mensagemAlerta = "";
				if ($temValorDiferenciado) {
					$classColuna = "tabeladadosdestacadoamarelo";
					$obs = $voPessoaTurma->obs;
					if ($obs != null) {
						$mensagemAlerta = "onMouseOver=\"toolTip('$obs')\" onMouseOut=\"toolTip()\"";
					}
				}
				
				$html .= "<TD class='tabeladados' nowrap>" . complementarCharAEsquerda ( $voAtual->cd, "0", TAMANHO_CODIGOS ) . "</TD> \n";
				$html .= "<TD class='tabeladados' >" . $voAtual->nome . "</TD> \n";
				$html .= "<TD class='tabeladados' nowrap>" . documentoPessoa::getNumeroDocFormatado ( $doc ) . "</TD> \n";
				$html .= "<TD class='tabeladados' nowrap>" . getInputText ( vopessoaturma::$nmAtrNumParcelas.$voAtual->cd, vopessoaturma::$nmAtrNumParcelas."[]", $voPessoaTurma->numParcelas, $classValor, 2, 2, "$readonly onkeyup='validarCampoNumericoPositivo(this, 2, event);' $javaScript" ) . " x</TD> \n";
				$html .= "<TD class='$classColuna' $mensagemAlerta>" . getInputText ( vopessoaturma::$nmAtrValor.$voAtual->cd, vopessoaturma::$nmAtrValor."[]", getMoeda ( $voPessoaTurma->valor, true ), $classValor, constantes::$TAMANHO_MOEDA, constantes::$TAMANHO_MOEDA, "$readonly onkeyup='formatarCampoMoedaComSeparadorMilhar(this, 2, event);' $javaScript" ) . "</TD> \n";
				
				$total = $voPessoaTurma->valor * $voPessoaTurma->numParcelas;
				$html .= "<TD class='$classColuna' $mensagemAlerta>" . getInputText ( vopessoaturma::$ID_REQ_VALOR_TOTAL.$voAtual->cd, vopessoaturma::$ID_REQ_VALOR_TOTAL, getMoeda ( $total, true ), "camporeadonlyalinhadodireita", constantes::$TAMANHO_MOEDA, constantes::$TAMANHO_MOEDA, " readonly onkeyup='formatarCampoMoedaComSeparadorMilhar(this, 2, event);'" ) . "</TD> \n";
				
				if (! $isDetalhamento) {
					$html .= "<TD class='tabeladados' nowrap>" . getBorrachaJS ( "limparDadosPessoa($voAtual->cd);" ) . "</TD> \n";
				}
				
				// o campo nome eh um array porque sao varias pessoas a incluir
				$html .= "<INPUT TYPE='HIDDEN' NAME='" . vopessoaturma::$nmAtrCdPessoa . "[]' VALUE='" . $voAtual->cd . "'> \n";
				$html .= "</TR> \n";
			}
			// se precisar de um contador;
			$i ++;
			$valorReceita = $valorReceita + ($voPessoaTurma->valor*$voPessoaTurma->numParcelas);
		}
		
			$html .= "<TR>";
			$html .= "<TD class='totalizadortabeladadosalinhadodireita' colspan='" . ($numColunas-1) ."'>Total: " 
					. getInputText ( voturma::$ID_REQ_VALOR_TOTAL, voturma::$ID_REQ_VALOR_TOTAL, getMoeda ( $valorReceita, true), "camporeadonlyalinhadodireita", constantes::$TAMANHO_MOEDA, constantes::$TAMANHO_MOEDA, "readonly " ) . "</TD> \n";		
			$html .= "</TR>";
		
		$html .= "<TR>";
		$html .= "<TD class='totalizadortabeladadosalinhadodireita' colspan='" . ($numColunas-1) ."'>Total de registro(s): $tamanho</TD>";
		// $html .= "</DIV> \n";
	} else {
		$msg = "&nbsp;Selecione alunos clicando na lupa acima.";
		if ($isDetalhamento)
			$msg = "&nbsp;N�o h� alunos para exibir.";
		
		$html .= "<TH class='tabeladados' width='1%' nowrap>$msg</TH>   \n";
		// $html .= $msg;
	}
	
	$html .= "</TR>";
	
	$html .= "</TBODY> \n";
	$html .= "</TABLE> \n";
	$html .= "</TH>\n";
	$html .= "</TR>\n";
	
	return $html;
}

?>