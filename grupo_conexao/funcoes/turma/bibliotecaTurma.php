<?php
function incluirChaveNoArray($chave, $array, $cdTurma = null) {
	if ($chave != null && ! existeItemNoArray ( $chave, $array )) {
		$vopessoaturma = new vopessoaturma ();
		$vopessoaturma->cdPessoa = vopessoa::getCdPessoaChaveExplode ( $chave );
		if ($cdTurma != null) {
			$vopessoaturma->cdTurma = $cdTurma;
		}
		$array [$vopessoaturma->cdPessoa] = $vopessoaturma;
		// var_dump($vopessoaturma);
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
	
	// var_dump($colecaoCdPessoas);
	
	for($i = 0; $i < count ( $colecaoCdPessoas ); $i ++) {
		$vopessoaturma = new vopessoaturma ();
		$cdPessoa = $colecaoCdPessoas [$i];
		$vopessoaturma = $retorno [$cdPessoa];
		$vopessoaturma->valor = getVarComoDecimal ( $colecaoValores [$i] );
		$vopessoaturma->numParcelas = $colecaoNumParcelas [$i];
		$retorno [$cdPessoa] = $vopessoaturma;
	}
	
	return $retorno;
}
function getColecaoVOPessoaTurmaFromRecordset($colecaoAlunosVOPessoaTurma, $cdTurma) {
	$recordSet = consultarPessoasTurma ( $colecaoAlunosVOPessoaTurma, $cdTurma );
	
	$retorno = "";
	foreach ( $recordSet as $registrobanco ) {
		$voPessoaTurma = new vopessoaturma ();
		$voPessoaTurma->getDadosBanco ( $registrobanco );
		
		$vopessoa = new vopessoa ();
		$vopessoa->getDadosBanco ( $registrobanco );
		$voturma = new voturma ();
		$voturma->getDadosBanco ( $registrobanco );
		
		$voPesTurmaTela = $colecaoAlunosVOPessoaTurma [$voPessoaTurma->cdPessoa];
		// echo $voPesTurmaTela->valor;
		
		$voPessoaTurma->valor = $voPesTurmaTela->valor;
		$voPessoaTurma->numParcelas = $voPesTurmaTela->numParcelas;
		// cria em execucao um OTD
		$voPessoaTurma->vopessoa = $vopessoa;
		$voPessoaTurma->voturma = $voturma;
		
		$retorno [$voPessoaTurma->cdPessoa] = $voPessoaTurma;
	}
	return $retorno;
}
function consultarPessoasTurma($colecaoPessoaTurma, $cdTurma) {
	if (! isColecaoVazia ( $colecaoPessoaTurma )) {
		// var_dump($colecaoPessoaTurma);
		
		$colecaoCdPessoa = getColecaoCdPessoa ( $colecaoPessoaTurma );
		$filtro = new filtroManterPessoaTurma ( false );
		$filtro->colecaoCdPessoa = $colecaoCdPessoa;
		// se tiver turma, traz as informacoes da turmaxpessoa
		if ($cdTurma != null && $cdTurma != "") {
			$filtro->inTemTurma = constantes::$CD_SIM;
			$filtro->cdTurma = $cdTurma;
		}
		
		$colecao = consultarPessoasManutencaoTurma ( $filtro );
	}
	
	return $colecao;
}
function consultarPessoasTurmaPorVOTurma($voturma) {
	// $voturma = new voturma();
	if ($voturma != null) {
		
		$filtro = new filtroManterPessoaTurma ( false );
		
		$cdHistorico = "N";
		if ($voturma->isHistorico ()) {
			$cdHistorico = "S";
			$filtro->sqHistTurma =  $voturma->sqHist;
		}
		
		$cdTurma = $voturma->cd;
		// echo "o codigo da turma é $cdTurma e o cdHistorico é $cdHistorico e o historico do voregistro é " . $voturma->sqHist;
		$filtro->inTemTurma = constantes::$CD_SIM;
		// $filtro->voPrincipal = new vopessoaturma();
		$filtro->cdTurma = $cdTurma;
		$filtro->cdHistorico = $cdHistorico;
		
		// FAZER UM NOVO FILTRO AQUI
		$colecao = consultarPessoasManutencaoTurma ( $filtro );
	}
	
	return $colecao;
}
function consultarPagamentoPessoa($vopessoaturma) {
	if ($vopessoaturma != null) {
		$filtro = new filtroManterPagamento ( false );
		$filtro->cdTurma = $vopessoaturma->cdTurma;
		$filtro->cdPessoa = $vopessoaturma->cdPessoa;
		$filtro->setaFiltroConsultaSemLimiteRegistro ();
		$db = new dbpessoaturma ();
		$colecao = $db->consultarFiltroManterPagamento ( $filtro );
	}
	return $colecao;
}
function consultarPessoasManutencaoTurma($filtro) {
	if ($filtro != null) {
		$filtro->setaFiltroConsultaSemLimiteRegistro ();
		$filtro->cdAtrOrdenacao = vopessoa::$nmAtrNome;
		$filtro->cdOrdenacao = constantes::$CD_ORDEM_CRESCENTE;
		$filtro->setMetodoFiltroConsulta ( filtroManterPessoaTurma::$NM_METODO_CONSULTA_PESSOA_TURMA );
		//$filtro->voPrincipal = new vopessoaturma ();
		
		$db = new dbpessoaturma ();
		$colecao = $db->consultarPessoaManutencaoTurma ( $filtro );
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
		$cdTurma = @$_POST ["cdTurma"];
		if (existeObjetoSessao ( voturma::$ID_REQ_COLECAO_ALUNOS )) {
			$colecaoAlunosVOPessoaTurma = getColecaoAlunosVOPessoaTurma ();
			// var_dump($colecaoAlunosVOPessoaTurma);
			// echo "pegou da sessao";
		}
	}
	// echo "turma = $cdTurma chave = $chave and funcao = $funcao and operacao = $operacao";
	$html = "";
	$isInclusao = $operacao == constantes::$CD_FUNCAO_INCLUIR;
	// $isDetalhamento = $funcao == constantes::$CD_FUNCAO_DETALHAR || $funcao == constantes::$CD_FUNCAO_EXCLUIR;
	
	// -1 eh o codigo para limpar o grid
	$isLimpar = $chave == - 1;
	// $temOperacaoComAColecao = $chave != null;
	if (! $isLimpar) {
		
		if ($chave != null) {
			
			if ($isInclusao) {
				// echo $chave;
				$pessoaAindaNaoPertenceATurma = isPessoaAindaNaoPertenceATurma ( $chave );
				// se a pessoa esta na turma, mas ela esta sendo incluida novamente, deve emitir um alerta
				$emitirAlerta = ! $pessoaAindaNaoPertenceATurma;
				if ($emitirAlerta) {
					// header("Location: ../confirmar.php?class=".$vo->getNmClassVO(),TRUE,307);
					// $html = "<SCRIPT language='JavaScript' type='text/javascript'>exibirMensagem('Aluno já existente. Reinicie a operação.');</SCRIPT>\n";
					$link = getLinkHTML ( "../../" . caminho_funcoesHTML . "pessoa_turma", "Pessoa X Turma" );
					$html = "INCLUSÃO DE ALUNO JÁ EXISTENTE NÃO PERMITIDA. <BR> PARA ALTERÁ-LO, SIGA PARA $link OU CANCELE E TENTE NOVAMENTE.";
				}
				
				// aqui vai a chave completa para permitir a inclusao de multiplas pessoas
				$colecaoAlunosVOPessoaTurma = incluirArrayNoArray ( $chave, $colecaoAlunosVOPessoaTurma );
				// var_dump($colecaoAlunosVOPessoaTurma);
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
		if ($isConsultarPessoa) {
			$colecaoAlunosVOPessoaTurma = getColecaoVOPessoaTurmaFromRecordset ( $colecaoAlunosVOPessoaTurma, null );
		}
		putObjetoSessao ( voturma::$ID_REQ_COLECAO_ALUNOS, $colecaoAlunosVOPessoaTurma );
	} else {
		removeObjetoSessao ( voturma::$ID_REQ_COLECAO_ALUNOS );
	}
	
	if (! $emitirAlerta) {
		$html = mostrarGridAlunos ( $colecaoAlunosVOPessoaTurma );
	}
	
	return $html;
}
function isPessoaAindaNaoPertenceATurma($cdPessoa) {
	$colecaoAlunosAnteriores = getObjetoSessao ( voturma::$ID_REQ_COLECAO_ALUNOS_ANTERIOR );
	$retorno = false;
	
	if ($colecaoAlunosAnteriores != null) {
		$arrayCdPessoasCadastradasAnterior = array_keys ( $colecaoAlunosAnteriores );
		
		if (! is_array ( $cdPessoa )) {
			$retorno = ! in_array ( $cdPessoa, $arrayCdPessoasCadastradasAnterior );
		} else {
			foreach ( $cdPessoa as $cd ) {
				// cd vem no formato de chaveprimaria de vopessoa
				$cd = vopessoa::getCdPessoaChaveExplode ( $cd );
				// echo "$cd<br>";
				$retorno = ! in_array ( $cd, $arrayCdPessoasCadastradasAnterior );
				if (! $retorno) {
					break;
				}
			}
		}
	} else {
		$retorno = true;
	}
	return $retorno;
}
function mostrarGridAlunos($colecaoAlunos) {
	$funcao = @$_GET ["funcao"];
	if ($funcao == null) {
		$funcao = @$_POST ["funcao"];
	}
	$operacao = @$_POST ["operacao"];
	
	$isAlteracao = $funcao == constantes::$CD_FUNCAO_ALTERAR;
	$isDetalhamento = $funcao == constantes::$CD_FUNCAO_DETALHAR || $funcao == constantes::$CD_FUNCAO_EXCLUIR;
	$isInclusaoDePessoaNova = $operacao == constantes::$CD_FUNCAO_INCLUIR;
	
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
		
		$html .= "<TH class='headertabeladados' width='1%' nowrap>Código</TH>   \n";
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
			if ($voturma->valor == null) {
				$voturma->valor = @$_POST ["valorTurma"];
			}
			
			$pessoaAindaNaoPertenceATurma = isPessoaAindaNaoPertenceATurma ( $voPessoaTurma->cdPessoa );
			
			if ($voAtual != null) {
				
				$html .= "<TR class='dados'> \n";
				
				$classValor = "camporeadonlyalinhadodireita";
				$readonly = " readonly";
				
				if ($pessoaAindaNaoPertenceATurma) {
					$classValor = "camponaoobrigatorioalinhadodireita";
					$javaScript = " onChange=\"formataCamposPagamento('$voAtual->cd');\"";
					$readonly = "required";
					//echo "aqui";
				}
				
				$doc = $voAtual->docCPF;
				if ($doc == null) {
					$doc = $voAtual->docRG;
				}
				
				$classColuna = "tabeladados";
				$valorAcompararPessoa = $voPessoaTurma->valor * $voPessoaTurma->numParcelas;
				// echo "voturma valor = $voturma->valor && vopessoaturma valor = $voPessoaTurma->valor";
				
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
				$html .= "<TD class='tabeladados' nowrap>" . getInputText ( vopessoaturma::$nmAtrNumParcelas . $voAtual->cd, vopessoaturma::$nmAtrNumParcelas . "[]", $voPessoaTurma->numParcelas, $classValor, 2, 2, "$readonly onkeyup='validarCampoNumericoPositivo(this, 2, event);' $javaScript" ) . " x</TD> \n";
				$html .= "<TD class='$classColuna' $mensagemAlerta>" . getInputText ( vopessoaturma::$nmAtrValor . $voAtual->cd, vopessoaturma::$nmAtrValor . "[]", getMoeda ( $voPessoaTurma->valor, true ), $classValor, constantes::$TAMANHO_MOEDA, constantes::$TAMANHO_MOEDA, "$readonly onkeyup='formatarCampoMoedaComSeparadorMilhar(this, 2, event);' $javaScript" ) . "</TD> \n";
				
				$total = $voPessoaTurma->valor * $voPessoaTurma->numParcelas;
				$html .= "<TD class='$classColuna' $mensagemAlerta>" . getInputText ( vopessoaturma::$ID_REQ_VALOR_TOTAL . $voAtual->cd, vopessoaturma::$ID_REQ_VALOR_TOTAL, getMoeda ( $total, true ), "camporeadonlyalinhadodireita", constantes::$TAMANHO_MOEDA, constantes::$TAMANHO_MOEDA, " readonly onkeyup='formatarCampoMoedaComSeparadorMilhar(this, 2, event);'" ) . "</TD> \n";
				
				if($isDetalhamento && $voPessoaTurma->dhUltAlteracao != null){
					$html .= "<TD class='tabeladados' nowrap>" . getDataHora ( $voPessoaTurma->dhUltAlteracao ) . "</TD> \n";
				}
					
				if (! $isDetalhamento) {
					$html .= "<TD class='tabeladados' nowrap>" . getBorrachaJS ( "limparDadosPessoa($voAtual->cd);" ) . "</TD> \n";
				}
								
				// o campo nome eh um array porque sao varias pessoas a incluir
				$html .= "<INPUT TYPE='HIDDEN' NAME='" . vopessoaturma::$nmAtrCdPessoa . "[]' VALUE='" . $voAtual->cd . "'> \n";
				$html .= getInputHidden ( vopessoaturma::$ID_REQ_COLECAO_DHALTERACAO . $voAtual->cd, vopessoaturma::$ID_REQ_COLECAO_DHALTERACAO . "[]", $voPessoaTurma->dhUltAlteracao ) . "\n";
				$html .= "</TR> \n";
			}
			// se precisar de um contador;
			$i ++;
			$valorReceita = $valorReceita + ($voPessoaTurma->valor * $voPessoaTurma->numParcelas);
		}
		
		$html .= "<TR>";
		$html .= "<TD class='totalizadortabeladadosalinhadodireita' colspan='" . ($numColunas - 1) . "'>Total: " . getInputText ( voturma::$ID_REQ_VALOR_TOTAL, voturma::$ID_REQ_VALOR_TOTAL, getMoeda ( $valorReceita, true ), "camporeadonlyalinhadodireita", constantes::$TAMANHO_MOEDA, constantes::$TAMANHO_MOEDA, "readonly " ) . "</TD> \n";
		$html .= "</TR>";
		
		$html .= "<TR>";
		$html .= "<TD class='totalizadortabeladadosalinhadodireita' colspan='" . ($numColunas - 1) . "'>Total de registro(s): $tamanho</TD>";
		// $html .= "</DIV> \n";
	} else {
		$msg = "&nbsp;Selecione alunos clicando na lupa acima.";
		if ($isDetalhamento)
			$msg = "&nbsp;Não há alunos para exibir.";
		
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
function mostrarGridFinanceiro($vopessoaturma, $isDetalhamento) {
	// $vopessoaturma = new vopessoaturma ();
	$html = "";
	
	$html .= " <TR>\n ";
	$html .= "<TD class='conteinerfiltro' colspan='4'>\n";
	$html .= "<TABLE cellpadding='0' cellspacing='0'>";
	$html .= "<TBODY>";
	
	$html .= "<TR>\n";
	$html .= "<TH class='textoseparadorgrupocampos' halign='left' colspan='4'>&nbsp;&nbsp;Pagamentos\n";
	
	$html .= "<TABLE id='table_tabeladados' class='tabeladados' cellpadding='0' cellspacing='0'> \n";
	$html .= " <TBODY>  \n";
	$html .= "        <TR>    \n";
	
	$numColunas = 3;
	
	$html .= "<TH class='headertabeladados' width='1%' nowrap>Parcela</TH>   \n";
	$html .= "<TH class='headertabeladados' width='1%'>Valor</TH> \n";
	$html .= "<TH class='headertabeladados' width='1%' nowrap> Pago";
	if (! $isDetalhamento) {
		$html .= getXGridConsulta ( vopagamento::$nmAtrNumParcelaPaga, true, true );
	} else {
		$disabled = "disabled";
	}
	$html .= "</TH> \n";
	$html .= "<TH class='headertabeladados' width='1%'>Dt.Registro</TH> \n";
	
	$html .= "</TR> \n";
	
	$valorParcela = $vopessoaturma->valor;
	$numParcela = $vopessoaturma->numParcelas;
	$cdPessoa = $vopessoaturma->cdPessoa;
	
	$classValor = "camporeadonlyalinhadodireita";
	$valorTotal = 0;
	// var_dump($vopessoaturma->colecaoParcelasPagas);
	for($i = 1; $i <= $numParcela; $i ++) {
		
		$checked = $vopessoaturma->isParcelaPaga ( $i );
		
		$vopagamento = $vopessoaturma->colecaoParcelasPagas [$i];
		
		$html .= "<TR class='dados'> \n";
		$html .= "<TD class='tabeladados' nowrap>" . getInputText ( "", "", $i, $classValor, 2, 2, " readonly " ) . " x</TD> \n";
		$html .= "<TD class='tabeladados' nowrap>" . getInputText ( "", "", getMoeda ( $valorParcela, true ), $classValor, constantes::$TAMANHO_MOEDA, constantes::$TAMANHO_MOEDA, "readonly " ) . "</TD> \n";
		$html .= "<TD class='tabeladados' nowrap>" . getCheckBoxBoolean ( vopagamento::$nmAtrNumParcelaPaga, vopagamento::$nmAtrNumParcelaPaga . "[]", $i, $checked, $disabled ) . "</TD> \n";
		$html .= "<TD class='tabeladados' nowrap>" . getDataHora ( $vopagamento->dhUltAlteracao ) . "</TD> \n";
		$html .= "</TR> \n";
		
		$valorTotal = $valorTotal + $valorParcela;
	}
	
	$html .= "<TR>";
	$html .= "<TD class='totalizadortabeladadosalinhadodireita' colspan='" . ($numColunas - 1) . "'>Total: " . getInputText ( "", "", getMoeda ( $valorTotal, true ), "camporeadonlyalinhadodireita", constantes::$TAMANHO_MOEDA, constantes::$TAMANHO_MOEDA, "readonly " ) . "</TD> \n";
	$colecaoPArcelasPagasAnteriores = null;
	if ($vopessoaturma->colecaoParcelasPagas != null) {
		$colecaoPArcelasPagasAnteriores = array_keys ( $vopessoaturma->colecaoParcelasPagas );
	}
	$strParcelasPagas = getColecaoEntreSeparador ( $colecaoPArcelasPagasAnteriores, constantes::$CD_CAMPO_SEPARADOR_ARRAY );
	$html .= getInputHidden ( vopessoaturma::$ID_REQ_COLECAO_PARCELAS_PAGAS, vopessoaturma::$ID_REQ_COLECAO_PARCELAS_PAGAS, $strParcelasPagas ) . "\n";
	$html .= "</TR>";
	// $html .= "</TR>";
	
	$html .= "</TBODY> \n";
	$html .= "</TABLE> \n";
	$html .= "</TH>\n";
	$html .= "</TR>\n";
	
	$html .= "</TBODY>\n";
	$html .= "</TABLE>\n";
	$html .= "</TD>\n";
	$html .= "</TR>\n";
	
	return $html;
}
function getStringValueColecaoAlunosAnteriores($colecaoAlunosAntesCadastrados) {
	if ($colecaoAlunosAntesCadastrados != null) {
		$colecaoCdPessoasCadastradas = array_keys ( $colecaoAlunosAntesCadastrados );
		$strCdPessoasCadastradas = getColecaoEntreSeparador ( $colecaoCdPessoasCadastradas, constantes::$CD_CAMPO_SEPARADOR_ARRAY );
		// deixa gravado na pagina os alunos anteriormente cadastrados
		$retorno = $strCdPessoasCadastradas;
	}
	return $retorno;
}

?>