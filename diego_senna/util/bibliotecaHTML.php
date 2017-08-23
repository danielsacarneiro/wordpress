<?php
include_once (caminho_wordpress . "wp-config.php");
include_once ("mensagens.class.php");
include_once ("constantes.class.php");
include_once ("bibliotecaDataHora.php");
include_once ("dominioPermissaoUsuario.php");
include_once ("dominioSimNao.php");
include_once ("dominioQtdObjetosPagina.php");
include_once ("radiobutton.php");
include_once (caminho_vos . "vousuario.php");

// .................................................................................................................

// Class bibliotecaHTML {
function inicio() {
	inicioComValidacaoUsuario ( false );
}
function inicioComValidacaoUsuario($validarPermissaoAcesso) {
	
	// include_path = ".:/usr/share/pear:/home/SEU_LOGIN_DE_FTP/SEU_DIRETORIO";
	set_include_path ( dirname ( __FILE__ ) );
	
	$nomeUsuario = "Visitante";
	$idUsuario = "-1";
	// redireciona o user para o login se n tiver logado
	if (is_user_logged_in ()) {
		$current_user = wp_get_current_user ();
		$nomeUsuario = $current_user->display_name;
		$idUsuario = get_current_user_id ();
	} else {
		if ($validarPermissaoAcesso) {
			auth_redirect ();
		}
	}
	
	define ( 'id_user', $idUsuario );
	define ( 'name_user', $nomeUsuario );
	
	define ( 'anoDefault', date ( 'Y' ) );
	define ( 'dtHoje', getDataHoje () );
	define ( 'dtHojeSQL', date ( 'Y/m/d' ) );
}
function setTituloPagina($titulo) {
	return setTituloPaginaPorNivel ( $titulo, null );
}
function setCabecalho($titulo) {
	return setCabecalhoPorNivel ( $titulo, null );
}
function setTituloPaginaPorNivel($titulo, $qtdNiveisAcimaEmSeEncontraPagina) {
	$pastaCSS = caminho_css;
	$pastaCSS = subirNivelPasta ( $pastaCSS, $qtdNiveisAcimaEmSeEncontraPagina );
	
	$pastaJS = caminho_js;
	$pastaJS = subirNivelPasta ( $pastaJS, $qtdNiveisAcimaEmSeEncontraPagina );
	
	if ($titulo == null) {
		$titulo = constantes::$nomeSistema;
	} else {
		$titulo = constantes::$nomeSistema . " : $titulo";
	}
	
	$codificacaoHTML = "\n<meta http-equiv='Content-Type' content='text/html; charset=iso-8859-1'>";
	$html = $codificacaoHTML;
	$html .= "\n<TITLE>$titulo</TITLE>";
	$html .= "\n<LINK href='" . $pastaCSS . "sefaz_pe.css' rel='stylesheet' type='text/css'>\n";
	$html .= "\n<SCRIPT language='JavaScript' type='text/javascript' src='" . $pastaJS . "mensagens_globais.js'></SCRIPT>\n";
	
	return $html;
}
function getPastaImagens() {
	return getPastaImagensPorNivel ( null );
}
function getPastaImagensPorNivel($qtdNiveisAcimaEmSeEncontraPagina) {
	$pastaImagens = subirNivelPasta ( caminho_imagens, $qtdNiveisAcimaEmSeEncontraPagina );
	return $pastaImagens;
}
function setCabecalhoPorNivel($titulo, $qtdNiveisAcimaEmSeEncontraPagina) {
	// se precisar fazer o mesmo para pasta menu
	$pastaImagens = getPastaImagensPorNivel ( $qtdNiveisAcimaEmSeEncontraPagina );
	$pastaMenu = subirNivelPasta ( caminho_menu, $qtdNiveisAcimaEmSeEncontraPagina );
	
	// ECHO $pastaMenu;
	
	define ( 'pasta_imagens', $pastaImagens );
	if ($titulo != null) {
		$titulo = " - " . $titulo;
	}
	
	$diaExtenso = strftime ( '%A, %d de %B de %Y', strtotime ( 'today' ) );
	// $diaExtenso = date ( 'l jS \of F Y' );
	
	$cabecalho = "		<TABLE id='table_conteiner' class='conteiner' cellpadding='0' cellspacing='0'>
                        <TBODY>
                                <TR>
                                <TH class=headertabeladados colspan=2>
                                    <a href='" . site_cliente . "' ><img id=imgLogotipoSefaz src='" . $pastaImagens . "bg_topo_trapezio.gif' alt='SEFAZ-PE'></a> " . $diaExtenso . "
                                </TH>
                                </TR>                                
                                <TR>
                                <TH class=headertabeladados>&nbsp;" . constantes::$nomeSistema . "$titulo<br></TH>
                                <TH class=headertabeladadosalinhadodireita width='1%' nowrap>&nbsp" . utf8_decode ( name_user ) . ",
                                <a class='linkbranco' href='" . $pastaMenu . "index.php' >Menu</a>
                                <a href='" . $pastaMenu . "login.php?funcao=I' ><img  title='Entrar' src='" . $pastaImagens . "botao_home_laranja.gif' width='20' height='20'></a>
                                <a href='" . $pastaMenu . "login.php?funcao=O' ><img  title='Sair' src='" . $pastaImagens . "logout.gif' width='25' height='20'></a>";
	
	if (isUsuarioAdmin ()) {
		$cabecalho .= "<a href='" . site_wordpress . "' ><img  title='WORDPRESS' src='" . $pastaImagens . "w-logo-white.png' width='25' height='20'></a>";
	}
	
	$cabecalho .= "\n
                                </TH>                                                                                                
                                </TR>
                        </TBODY>
                    </TABLE>";
	// <a href='javascript:limparFormulario();' ><img title='Limpar' src='imagens/borracha.jpg' width='20' height='20'></a>
	// <a href='http:/wordpress' ><img title='Home' src='imagens/botao_home_laranja.png' width='20' height='20'></a>
	
	define ( 'cabecalho', $cabecalho );
}
function complementarCharAEsquerda($texto, $char, $qtdfinal) {
	$retorno = $texto;
	if ($texto != null && $texto != "") {
		$tam = strlen ( "$texto" );
		// echo $tam. "<br>";
		if ($tam < $qtdfinal) {
			for($i = 1; $i <= ($qtdfinal - $tam); $i ++) {
				$retorno = $char . $retorno;
				// echo $retorno."<br>";
			}
		}
	}
	
	return $retorno;
}
function getMoeda($valorSQL) {
	$retorno = "";
	if ($valorSQL != null)
		$retorno = number_format ( $valorSQL, 2, ',', '.' );
	return $retorno;
}
function getOrdemAtributos() {
	$varAtributos = array (
			constantes::$CD_ORDEM_CRESCENTE => "Crescente",
			constantes::$CD_ORDEM_DECRESCENTE => "Decrescente" 
	);
	return $varAtributos;
}
function incluirUsuarioDataHoraDetalhamento($voEntidade, $colspan = null) {
	$USUARIO_BATCH = "IMPORT.PLANILHA";
	$nmusuinclusao = $voEntidade->nmUsuarioInclusao;
	$nmusualteracao = $voEntidade->nmUsuarioUltAlteracao;
	
	if ($voEntidade->cdUsuarioInclusao == null)
		$nmusuinclusao = $USUARIO_BATCH;
	if ($voEntidade->cdUsuarioUltAlteracao == null)
		$nmusualteracao = $USUARIO_BATCH;
	
	$retorno = "<TR>
				<TD halign='left' colspan='4'>
				<DIV class='textoseparadorgrupocampos'>&nbsp;</DIV>
				</TD>
				</TR>";
	
	if ($voEntidade->dhInclusao != null) {
		$retorno .= "<TR>
		            <TH class='campoformulario' nowrap>Data Inclusão:</TH>
		            <TD class='campoformulario' width='1%'>
		            	<INPUT type='text' 
		            	       id='" . voentidade::$nmAtrDhInclusao . "' 
		            	       name='" . voentidade::$nmAtrDhInclusao . "' 
		            			value='" . getDataHoraSQLComoString ( $voEntidade->dhInclusao ) . "'
		            			class='camporeadonly' 
		            			size='20' 
		            			maxlength='10' readonly>
					</TD>
		            <TH class='campoformulario' width='1%' nowrap>Usuário Inclusão:</TH>
		            <TD class='campoformulario' colspan=$colspan>
		            	<INPUT type='text' 
		            	       id='" . voentidade::$nmAtrCdUsuarioInclusao . "' 
		            	       name='" . voentidade::$nmAtrCdUsuarioInclusao . "' 
		            			value='" . $nmusuinclusao . "'            			
		            			class='camporeadonly' 
		            			size='20' 
		            			readonly>
					</TD>            					
		        </TR>";
	}
	
	if ($voEntidade->dhUltAlteracao != null) {
		$retorno .= "<TR>
	            <TH class='campoformulario' nowrap>Data Ult.Alteração:</TH>
	            <TD class='campoformulario'>
	            	<INPUT type='text'	            	        
	            			value='" . getDataHoraSQLComoString ( $voEntidade->dhUltAlteracao ) . "'
	            			class='camporeadonly' 
	            			size='20' 
	            			maxlength='10' readonly>
	            	<INPUT type='hidden'
	            	       id='" . voentidade::$nmAtrDhUltAlteracao . "' 
	            	       name='" . voentidade::$nmAtrDhUltAlteracao . "' 
	            			value='" . $voEntidade->dhUltAlteracao . "'>
	            					
				</TD>
	            <TH class='campoformulario' nowrap>Usuário Ult.Alteração:</TH>
	            <TD class='campoformulario' colspan=$colspan>
	            	<INPUT type='text' 
	            	       id='" . voentidade::$nmAtrCdUsuarioUltAlteracao . "' 
	            	       name='" . voentidade::$nmAtrCdUsuarioUltAlteracao . "' 
	            			value='" . $nmusualteracao . "'            			
	            			class='camporeadonly' 
	            			size='20' 
	            			readonly>
				</TD>
			</TR>";
	}
	
	if ($voEntidade->sqHist != null) {
		$nmusuHistorico = $voEntidade->nmUsuarioOperacao;
		
		$retorno .= "\n<TR>\n" . "<TH class='textoseparadorgrupocampos' halign='left' colspan='4'>" . "<DIV class='campoformulario' id='div_tramitacao'>&nbsp;&nbsp;Dados do Histórico" . "</DIV>" . "</TH>" . "</TR>";
		
		$retorno .= "<TR>
		            <TH class='campoformulario' nowrap>Data:</TH>
		            <TD class='campoformulario'>
		            	<INPUT type='text'
		            	       id='" . voentidade::$nmAtrDhOperacao . "'
		            	       name='" . voentidade::$nmAtrDhOperacao . "'
		            			value='" . getDataHoraSQLComoString ( $voEntidade->dhOperacao ) . "'
		            			class='camporeadonly'
		            			size='20'
		            			maxlength='10' readonly>
					</TD>
		            <TH class='campoformulario' nowrap>Usuário:</TH>
		            <TD class='campoformulario' colspan=$colspan>
		            	<INPUT type='text'
		            	       id='" . voentidade::$nmAtrCdUsuarioOperacao . "'
		            	       name='" . voentidade::$nmAtrCdUsuarioOperacao . "'
		            			value='" . $nmusuHistorico . "'
		            			class='camporeadonly'
		            			size='20'
		            			readonly>
					</TD>
				</TR>";
	}
	
	// return utf8_decode($retorno);
	return $retorno;
}
function getDsEspecie($voContrato) {
	$retorno = null;
	$especiesContrato = new dominioEspeciesContrato ();
	$cdEspecie = $voContrato->cdEspecie;
	$especie = $voContrato->especie;
	if ($cdEspecie != dominioEspeciesContrato::$CD_ESPECIE_CONTRATO_MATER) {
		$sqEspecie = $voContrato->sqEspecie;
	}
	
	if ($especie != null || $cdEspecie != null) {
		
		if ($sqEspecie != null)
			$sqEspecie = $sqEspecie . "º";
		
		if ($cdEspecie != null) {
			$retorno = $sqEspecie . " " . $especiesContrato->getDescricao ( $cdEspecie );
		} else
			$retorno = $especie;
	}
	return $retorno;
}
function getBotao($idBotao, $descricao, $classe, $isSubmit, $complementoHTML) {
	$retorno = "";
	$tipo = "button";
	if ($isSubmit)
		$tipo = "submit";
	
	if ($classe == null)
		$classe = "botaofuncaop";
	
	$retorno = "<button id='$idBotao' class='$classe' type='$tipo' $complementoHTML>$descricao</button>";
	
	return $retorno;
}
function isLupa() {
	// vem do linkPesquisa ou do campo formulario
	$lupa = @$_GET [constantes::$ID_REQ_LUPA];
	if ($lupa == null || $lupa == "") {
		$lupa = @$_POST [constantes::$ID_REQ_LUPA];
	}
	
	return $lupa == "S";
}
function isMultiSelecao() {
	// vem do linkPesquisa ou do campo formulario
	$flag = @$_GET [constantes::$ID_REQ_MULTISELECAO];
	if ($flag == null || $flag == "") {
		$flag = @$_POST [constantes::$ID_REQ_MULTISELECAO];
	}
	
	return $flag == "S";
}
function getBotaoValidacaoAcesso($idBotao, $descricao, $classe, $isSubmit, $imprimirNaLupa, $imprimirNaManutencao, $todosTemAcesso, $complementoHTML) {
	return getBotaoGeral ( $idBotao, $descricao, $classe, $isSubmit, $imprimirNaLupa, $imprimirNaManutencao, $todosTemAcesso, $complementoHTML, "" );
}
function getBotaoPorFuncao($idBotao, $descricao, $classe, $isSubmit, $imprimirNaLupa, $imprimirNaManutencao, $complementoHTML, $cdFuncaoBotao) {
	return getBotaoGeral ( $idBotao, $descricao, $classe, $isSubmit, $imprimirNaLupa, $imprimirNaManutencao, false, $complementoHTML, $cdFuncaoBotao );
}
function getBotaoGeral($idBotao, $descricao, $classe, $isSubmit, $imprimirNaLupa, $imprimirNaManutencao, $todosTemAcesso, $complementoHTML, $cdFuncaoBotao) {
	$retorno = getBotao ( $idBotao, $descricao, $classe, $isSubmit, $complementoHTML );
	
	$isLupa = isLupa ();
	
	if ($isLupa) {
		// echo "EH LUPA";
		if (! $imprimirNaLupa)
			$retorno = "";
	} else {
		// echo "NAO EH LUPA";
		if ($imprimirNaManutencao) {
			if (! temPermissao ( $cdFuncaoBotao ) && ! $todosTemAcesso)
				$retorno = "";
		} else
			$retorno = "";
	}
	
	return $retorno;
}
function getBotaoConfirmar() {
	return getBotaoValidacaoAcesso ( "bttconfirmar", "Confirmar", "botaofuncaop", true, false, true, false, " accesskey='c'" );
}
function getBotaoCancelar() {
	return getBotaoValidacaoAcesso ( "bttcancelar", "Cancelar", "botaofuncaop", false, true, true, true, "onClick='javascript:cancelar();' accesskey='r'" );
}
function getBotaoAlterar() {
	return getBotaoPorFuncao ( "bttalterar", "Alterar", "botaofuncaop", false, false, true, "onClick='javascript:alterar();' accesskey='l'", constantes::$CD_FUNCAO_ALTERAR );
}
function getBotaoExcluir() {
	return getBotaoPorFuncao ( "bttexcluir", "Excluir", "botaofuncaop", false, false, true, "onClick='javascript:excluir();' accesskey='x'", constantes::$CD_FUNCAO_EXCLUIR );
}
function getBotaoIncluir() {
	// return getBotaoValidacaoAcesso ( "bttincluir", "Incluir", "botaofuncaop", false, false, true, false, "onClick='javascript:incluir();' accesskey='n'" );
	return getBotaoPorFuncao ( "bttincluir", "Incluir", "botaofuncaop", false, false, true, "onClick='javascript:incluir();' accesskey='n'", constantes::$CD_FUNCAO_INCLUIR );
}
function getBotaoDetalhar() {
	return getBotaoValidacaoAcesso ( "bttdetalhar", "Detalhar", "botaofuncaop", false, true, true, true, "onClick='javascript:detalhar(false);' accesskey='d'" );
}
function getBotaoSelecionar() {
	return getBotaoValidacaoAcesso ( "bttselecionar", "Selecionar", "botaofuncaop", false, true, false, true, "onClick='javascript:selecionar();' accesskey='s'" );
}
function getBotaoFechar() {
	return getBotaoValidacaoAcesso ( "bttfechar", "Fechar", "botaofuncaop", false, true, false, true, "onClick='javascript:window.close();' accesskey='f'" );
}
function getRodape() {
	// aqui guarda a informacao de ser o contexto de LUPA, quando ocultara alguns botoes
	$lupa = "N";
	if (isLupa ())
		$lupa = "S";
	
	$multiSelecao = "N";
	if (isMultiSelecao ())
		$multiSelecao = "S";
	
	$retorno = "<INPUT type='hidden' name='" . constantes::$ID_REQ_LUPA . "' id='" . constantes::$ID_REQ_LUPA . "' value='" . $lupa . "'>\n";
	$retorno .= "<INPUT type='hidden' name='" . constantes::$ID_REQ_MULTISELECAO . "' id='" . constantes::$ID_REQ_MULTISELECAO . "' value='" . $multiSelecao . "'>\n";
	return $retorno;
}
function getBotoesRodape() {
	return getBotoesRodapeComRestricao ( null );
}
function getBotoesApenasDetalhar() {
	$arrayBotoesARemover = array (
			constantes::$CD_FUNCAO_INCLUIR,
			constantes::$CD_FUNCAO_ALTERAR,
			constantes::$CD_FUNCAO_EXCLUIR 
	);
	return getBotoesRodapeComRestricao ( $arrayBotoesARemover, true );
}
function exibeBotao($arrayBotoesARemover, $nmFuncaoBotao, $usuarioLogadoTemPermissao, $restringeBotaoSemValidarPermissao) {
	return ! existeItemNoArray ( $nmFuncaoBotao, $arrayBotoesARemover ) || ($usuarioLogadoTemPermissao && ! $restringeBotaoSemValidarPermissao);
}
/*
 * $restringeBotaoSemValidarPermissao serve para retirar o botao em $arrayBotoesARemover independente de ter permissao o usuario
 *
 */
function getBotoesRodapeComRestricao($arrayBotoesARemover, $restringeBotaoSemValidarPermissao = false) {
	
	// o administrador pode ver todos os botoes
	$usuarioLogadoTemPermissao = dominioPermissaoUsuario::isAdministrador ( getColecaoPermissaoUsuarioLogado () );
	
	/*
	 * $temIncluir = ! existeItemNoArray ( constantes::$CD_FUNCAO_INCLUIR, $arrayBotoesARemover ) || $usuarioLogadoTemPermissao;
	 * $temAlterar = ! existeItemNoArray ( constantes::$CD_FUNCAO_ALTERAR, $arrayBotoesARemover ) || $usuarioLogadoTemPermissao;
	 * $temExcluir = ! existeItemNoArray ( constantes::$CD_FUNCAO_EXCLUIR, $arrayBotoesARemover ) || $usuarioLogadoTemPermissao;
	 */
	
	// falta fazer para os outros botoes
	$temIncluir = exibeBotao ( $arrayBotoesARemover, constantes::$CD_FUNCAO_INCLUIR, $usuarioLogadoTemPermissao, $restringeBotaoSemValidarPermissao );
	$temAlterar = exibeBotao ( $arrayBotoesARemover, constantes::$CD_FUNCAO_ALTERAR, $usuarioLogadoTemPermissao, $restringeBotaoSemValidarPermissao );
	$temExcluir = exibeBotao ( $arrayBotoesARemover, constantes::$CD_FUNCAO_EXCLUIR, $usuarioLogadoTemPermissao, $restringeBotaoSemValidarPermissao );
	
	$isManutencao = false;
	$isDetalhamento = false;
	$funcao = @$_GET ["funcao"];
	
	// considera que qq funcao chamado que nao sejam as funcoes baiscas (alterar, excluir, incluir...) caira nessa opcao
	// marreta: verifica pelo tamanho do nome da funcao
	// um exemplo eh o metodo encaminhar chamado no encaminhamento de demanda (voDemandaTramitacao)
	$isMetodoChamadoEspecifico = strlen ( $funcao ) > 2;
	
	if ($funcao == constantes::$CD_FUNCAO_DETALHAR) {
		$isDetalhamento = true;
	} else if ($funcao == constantes::$CD_FUNCAO_EXCLUIR || $funcao == constantes::$CD_FUNCAO_INCLUIR || $funcao == constantes::$CD_FUNCAO_ALTERAR || $isMetodoChamadoEspecifico) {
		
		$isManutencao = true;
	}
	
	$html = "";
	
	if (! $isManutencao && ! $isDetalhamento && getBotaoDetalhar () != "")
		$html .= "<TD class='botaofuncao'>" . getBotaoDetalhar () . "</TD>\n";
	
	if (! $isDetalhamento && getBotaoSelecionar () != "")
		$html .= "<TD class='botaofuncao'>" . getBotaoSelecionar () . "</TD>\n";
	
	if (! $isManutencao) {
		if (! $isDetalhamento) {
			if (getBotaoIncluir () != "" && $temIncluir)
				$html .= "<TD class='botaofuncao'>" . getBotaoIncluir () . "</TD>\n";
			if (getBotaoAlterar () != "" && $temAlterar)
				$html .= "<TD class='botaofuncao'>" . getBotaoAlterar () . "</TD>\n";
			if (getBotaoExcluir () != "" && $temExcluir)
				$html .= "<TD class='botaofuncao'>" . getBotaoExcluir () . "</TD>\n";
		}
	} else {
		if (getBotaoConfirmar () != "")
			$html .= "<TD class='botaofuncao'>" . getBotaoConfirmar () . "</TD>\n";
	}
	
	if (getBotaoCancelar () != "" && ($isDetalhamento || $isManutencao))
		$html .= "<TD class='botaofuncao'>" . getBotaoCancelar () . "</TD>\n";
	
	if (getBotaoFechar () != "")
		$html .= "<TD class='botaofuncao'>" . getBotaoFechar () . "</TD>\n";
	
	$html .= getRodape ();
	
	return $html;
}
function getLinkPesquisa($link) {
	return getImagemLink ( "javascript:abrirJanelaAuxiliar('" . $link . "',true, false, false);\" ", "lupa.png" );
}
function getImagemLink($href, $nmImagem) {
	
	// $pasta = pasta_imagens . "//";
	$pasta = getPastaImagens () . "//";
	
	$html = "<A id='lnkFramework' name='lnkFramework' " . "href=\"" . $href . "\"" . " class='linkNormal' >" . "<img src='" . $pasta . $nmImagem . "'  width='22' height='22' border='0'></A>";
	
	// echo $pasta;
	
	return $html;
}
function getBorrachaJS($js) {
	$html = "&nbsp;&nbsp;<a onClick=\"javascript:" . $js . "\" ><img  title='Limpar' src='" . caminho_imagens . "borracha.gif' width='15' height='15' A style='CURSOR: POINTER'></a>\n";
	return $html;
}
function getBorracha($nmCampos) {
	$tam = count ( $nmCampos );
	
	$js = "";
	for($i = 0; $i < $tam; $i ++) {
		$nmCampoAtual = $nmCampos [$i];
		$js .= "document.frm_principal." . $nmCampoAtual . ".value='';";
	}
	
	$html = "&nbsp;&nbsp;<a onClick=\"javascript:" . $js . "\" ><img  title='Limpar' src='" . caminho_imagens . "borracha.gif' width='15' height='15' A style='CURSOR: POINTER'></a>\n";
	
	return $html;
}
function getColecaoPermissaoUsuarioLogado() {
	$current_user = wp_get_current_user ();
	$permissao_user = $current_user->roles;
	
	return $permissao_user;
}
function temPermissao($cdFuncaoBotao) {
	return temPermissaoPorFuncao ( $cdFuncaoBotao, false );
}
function isUsuarioAdmin() {
	return dominioPermissaoUsuario::isAdministrador ( getColecaoPermissaoUsuarioLogado () );
}
function temPermissaoParamHistorico($isHistorico) {
	return temPermissaoPorFuncao ( constantes::$CD_FUNCAO_HISTORICO, $isHistorico );
}
function temPermissaoPorFuncao($cdFuncaoBotao, $isHistorico) {
	$current_user = wp_get_current_user ();
	$permissao_user = $current_user->roles;
	
	$retorno = true;
	if ($isHistorico) {
		$retorno = dominioPermissaoUsuario::temPermissaoPorFuncao ( constantes::$CD_FUNCAO_HISTORICO, $permissao_user );
	} else {
		// $retorno= dominioPermissaoUsuario::temPermissao($permissao_user);
		$retorno = dominioPermissaoUsuario::temPermissaoPorFuncao ( $cdFuncaoBotao, $permissao_user );
	}
	return $retorno;
}
function getComboColecaoGenerico($colecao, $idCampo, $nmCampo, $cdOpcaoSelecionada, $classCampo, $tagHtml) {
	$select = new select ( $colecao );
	$retorno = $select->getHtmlCombo ( $idCampo, $nmCampo, $cdOpcaoSelecionada, true, $classCampo, true, $tagHtml );
	
	return $retorno;
}
function getComponenteConsultaFiltro($temHistorico, $filtro) {
	// $comboOrdenacao = $filtro->getAtributosOrdenacao();
	$comboOrdenacao = $filtro->getComboOrdenacao ();
	
	return getComponenteConsultaPaginacao ( $comboOrdenacao, $filtro->cdAtrOrdenacao, $filtro->cdOrdenacao, $filtro->TemPaginacao, $filtro->qtdRegistrosPorPag, $temHistorico, $filtro->cdHistorico );
}
function getComponenteConsulta($comboOrdenacao, $cdAtrOrdenacao, $cdOrdenacao, $qtdRegistrosPorPag, $temHistorico, $cdHistorico) {
	return getComponenteConsultaPaginacao ( $comboOrdenacao, $cdAtrOrdenacao, $cdOrdenacao, true, $qtdRegistrosPorPag, $temHistorico, $cdHistorico );
}
function getComponenteConsultaPaginacao($comboOrdenacao, $cdAtrOrdenacao, $cdOrdenacao, $temPaginacao, $qtdRegistrosPorPag, $temHistorico, $cdHistorico) {
	$html = "";
	
	$objetosPorPagina = new dominioQtdObjetosPagina ();
	$comboQtdRegistros = new select ( $objetosPorPagina->colecao );
	$radioHistorico = new radiobutton ( dominioSimNao::getColecao () );
	
	if ($cdHistorico == null) {
		$cdHistorico = constantes::$CD_NAO;
	}
	
	$html = "<TR>
                    <TH class='campoformulario' width='1%'>Consulta:</TH>
                    <TD class='campoformulario' valign='bottom' colspan='3' width='90%' nowrap>\n";
	
	// var_dump($comboOrdenacao);
	if ($comboOrdenacao != null && $comboOrdenacao != "") {
		// echo $cdOrdenacao;
		$comboOrdem = new select ( getOrdemAtributos () );
		$html .= "Coluna:" . $comboOrdenacao->getHtmlOpcao ( "cdAtrOrdenacao", "cdAtrOrdenacao", $cdAtrOrdenacao, false ) . " Ordem: " . $comboOrdem->getHtmlOpcao ( "cdOrdenacao", "cdOrdenacao", $cdOrdenacao, false );
	}
	
	if ($temPaginacao) {
		$html .= " Num.Registros por página: " . $comboQtdRegistros->getHtmlOpcao ( "qtdRegistrosPorPag", "qtdRegistrosPorPag", $qtdRegistrosPorPag, false );
	}
	
	if ($temHistorico && ! isLupa ())
		$html .= " &nbsp;Histórico: " . $radioHistorico->getHtmlRadio ( "cdHistorico", "cdHistorico", $cdHistorico, false, false );
	
	$html .= "&nbsp;<button id='localizar' class='botaoconsulta' type='submit'>Consultar</button>\n";
	$html .= "&nbsp;&nbsp;&nbsp;<a href='javascript:limparFormularioGeral();' ><img  title='Limpar' src='" . caminho_imagens . "borracha.gif' width='20' height='20'></a></TD>\n
                    </TR>";
	// $html .= "<imput type='hidden' id='javascript:ID_REQ_DT_HOJE' name='javascript:ID_REQ_DT_HOJE'>\n";
	
	return $html;
}
function getHTMLRadioButtonConsulta($nmRadio, $idRadio, $voAtualOuChaveString) {
	return getHTMLGridConsulta ( $nmRadio, $idRadio, $voAtualOuChaveString, false );
}
function getHTMLCheckBoxConsulta($nmRadio, $idRadio, $voAtualOuChaveString) {
	return getHTMLGridConsulta ( $nmRadio, $idRadio, $voAtualOuChaveString, true );
}
function getHTMLGridConsulta($nmRadio, $idRadio, $voAtualOuChaveString, $isCheckBox) {
	$isSelecionado = false;
	
	$ID = "";
	if ($voAtualOuChaveString instanceof voentidade) {
		$ID = $voAtualOuChaveString->getNmTabela ();
		$voSessao = getObjetoSessao ( $ID );
		$isSelecionado = $voAtualOuChaveString->isIgualChavePrimaria ( $voSessao );
		$chave = $voAtualOuChaveString->getValorChaveHTML ();
	} else {
		// voAtual nao existe
		// a chave eh o proprio parametro
		$chave = $voAtualOuChaveString;
	}
	
	/*
	 * if($voAtual != null){
	 * $voSessao = getObjetoSessao($voAtual->getNmTabela());
	 * $isSelecionado = $voAtual->isIgualChavePrimaria($voSessao);
	 * $chave = $voAtual->getValorChaveHTML();
	 * }
	 */
	
	$checked = "";
	if ($isSelecionado)
		$checked = "checked";
	
	$tipoComponente = "radio";
	if ($isCheckBox) {
		$tipoComponente = "checkbox";
	}
	
	$retorno = "<INPUT type='$tipoComponente' id='" . $idRadio . "' name='" . $nmRadio . "' value='" . $chave . "' " . $checked . ">";
	// echo $chave;
	
	return $retorno;
}
function getXGridConsulta($nmRadio, $isCheckBox) {
	$retorno = "&nbsp;&nbsp;X";
	if ($isCheckBox) {
		$js = "try{marcarTodosCheckBoxes('$nmRadio');}catch(erro){;}";
		$retorno = "<a onClick=\"javascript:" . $js . "\" A style='CURSOR: POINTER'>$retorno</a>\n";
	}
	
	return $retorno;
}
function getRadioButton($idRadio, $nmRadio, $chave, $checked, $complementoHTML) {
	$retorno = "<INPUT type='radio' id='" . $idRadio . "' name='" . $nmRadio . "' value='" . $chave . "' " . $checked . ">";
	return $retorno;
}
function getSelectGestor() {
	$dbgestor = new dbgestor ();
	$registros = $dbgestor->consultarSelect ();
	
	$select = new select ( $registros );
}
function putObjetoSessao($ID, $voEntidade) {
	/*
	 * if(!isSet($_SESSION))
	 * session_start();
	 * echo session_id();
	 * if(session_id() == "" || !isSet($_SESSION)){
	 * echo "NAO TEM SESSAO";
	 * session_start();
	 * }
	 * ELSE{
	 * echo "TEM SESSAO";
	 * }
	 */
	session_start ();
	$_SESSION [$ID] = $voEntidade;
}
function existeObjetoSessao($ID) {
	session_start ();
	return isset ( $_SESSION [$ID] ) && $_SESSION [$ID] != null;
}
function getObjetoSessao($ID, $levantarExcecaoSeObjetoInexistente = false) {
	session_start ();
	
	$objeto = null;
	
	if ($_SESSION [$ID] != null) {
		$objeto = $_SESSION [$ID];
	} else if ($levantarExcecaoSeObjetoInexistente) {
		throw new excecaoObjetoSessaoInexistente ( $ID );
	}
	
	$isUsarSessao = @$_POST ["utilizarSessao"] != "N";
	if (! $isUsarSessao) {
		$objeto = null;
		removeObjetoSessao ( $ID );
	}
	
	return $objeto;
}
function removeObjetoSessao($ID) {
	session_start ();
	unset ( $_SESSION [$ID] );
}
function formatarCodigoContrato($cd, $ano, $tipo) {
	$dominioTipoContrato = new dominioTipoContrato ();
	$complemento = $dominioTipoContrato->getDescricao ( $tipo );
	return formatarCodigoAnoComplemento ( $cd, $ano, $complemento );
}
function formatarCodigoAnoComplemento($cd, $ano, $complemento) {
	return formatarCodigoAnoComplementoArgs ( $cd, $ano, null, $complemento );
}
function formatarCodigoAnoComplementoArgs($cd, $ano, $pTamanhoCodigo, $complemento) {
	$retorno = "";
	if ($complemento != null && $complemento != "") {
		$retorno .= $complemento . " ";
	}
	
	$tamanhoCodigo = TAMANHO_CODIGOS_SAFI;
	if ($pTamanhoCodigo != null) {
		$tamanhoCodigo = $pTamanhoCodigo;
	}
	
	$retorno .= complementarCharAEsquerda ( $cd, "0", $tamanhoCodigo ) . "/" . substr ( $ano, 2, 2 );
	
	return $retorno;
}
function formatarCodigoAno($cd, $ano) {
	return formatarCodigoAnoComplemento ( $cd, $ano, null );
}
function getDataHoraAtual() {
	return date ( 'd/m/Y H:i:s' );
}
function getDataHoje() {
	return date ( 'd/m/Y' );
}
function tratarExcecaoHTML($ex, $vo = null) {
	if ($vo != null) {
		putObjetoSessao ( $vo->getNmTabela (), $vo );
		// a debaixo eh para a tela de msg de erro
		putObjetoSessao ( constantes::$ID_REQ_SESSAO_VO, $vo );
	}
	
	$msg = $ex->getMessage ();
	$msg = str_replace ( "\n", "", $msg );
	
	header ( "Location: ../mensagemErro.php?texto=" . $msg, TRUE, 307 );
}
function getStrComPuloLinhaHTML($str) {
	return getStrComPuloLinhaGenerico ( $str, "<br>" );
}
function getStrComPuloLinha($str) {
	return getStrComPuloLinhaGenerico ( $str, "\n" );
}
function getStrComPuloLinhaGenerico($str, $pulo) {
	return "$str$pulo";
}
function getFuncoesJSGenericas($pNmCampoConsulta, $isHistoricoFiltro, $colecaoFuncoes = null, $colecaoFuncoesARemover = null) {
	
	// se colecoes a remover for nulo, tera todas as funcoes
	$valorDefault = $colecaoFuncoesARemover == null;
	
	$temIncluir = $valorDefault;
	$temAlterar = $valorDefault;
	$temDetalhar = $valorDefault;
	$temExcluir = $valorDefault;
	$temCancelar = $valorDefault;
	
	// se for igual a remover todas, todas serao false como valor default
	if ($colecaoFuncoesARemover != null && $colecaoFuncoesARemover != constantes::$CD_FUNCAO_TODAS) {
		$temIncluir = ! existeItemNoArray ( constantes::$CD_FUNCAO_INCLUIR, $colecaoFuncoesARemover );
		$temAlterar = ! existeItemNoArray ( constantes::$CD_FUNCAO_ALTERAR, $colecaoFuncoesARemover );
		$temDetalhar = ! existeItemNoArray ( constantes::$CD_FUNCAO_DETALHAR, $colecaoFuncoesARemover );
		$temExcluir = ! existeItemNoArray ( constantes::$CD_FUNCAO_EXCLUIR, $colecaoFuncoesARemover );
		$temCancelar = ! existeItemNoArray ( constantes::$CD_FUNCAO_CANCELAR, $colecaoFuncoesARemover );
	}
	
	if ($colecaoFuncoes != null) {
		$temIncluir = existeItemNoArray ( constantes::$CD_FUNCAO_INCLUIR, $colecaoFuncoes );
		$temAlterar = existeItemNoArray ( constantes::$CD_FUNCAO_ALTERAR, $colecaoFuncoes );
		$temDetalhar = existeItemNoArray ( constantes::$CD_FUNCAO_DETALHAR, $colecaoFuncoes );
		$temExcluir = existeItemNoArray ( constantes::$CD_FUNCAO_EXCLUIR, $colecaoFuncoes );
		$temCancelar = existeItemNoArray ( constantes::$CD_FUNCAO_CANCELAR, $colecaoFuncoes );
	}
	
	if ($temDetalhar) {
		$html .= " function detalhar(isExcluir) {\n";
		$html .= "  if(isExcluir == null || !isExcluir){ \n";
		$html .= "   funcao = '" . constantes::$CD_FUNCAO_DETALHAR . "';\n";
		$html .= "  }else{\n";
		$html .= "   funcao = '" . constantes::$CD_FUNCAO_EXCLUIR . "';\n";
		$html .= "  }\n";
		$html .= "  //o campo pode ser um radio ou checkbox, depende se for uma janela de lupa ou nao\n";
		$html .= "  if (!isRadioButtonConsultaSelecionado('$pNmCampoConsulta'))\n";
		$html .= "   return;\n";
		
		$html .= "  if(" . booleanToExtenso ( isMultiSelecao () ) . "){\n";
		$html .= "   if (!isApenasUmCheckBoxSelecionado('$pNmCampoConsulta'))\n";
		$html .= "	  return;\n";
		$html .= "  }\n";
		
		$html .= "	campoChave = $pNmCampoConsulta;\n";
		$html .= "  if(!" . booleanToExtenso ( isMultiSelecao () ) . "){\n";
		$html .= "   chave = " . $pNmCampoConsulta . ".value;\n";
		$html .= "  }else{\n";
		// sera um ckeckbox
		// se for array pega o primeiro e unico registro selecionado
		$html .= "   chave = retornarValoresCheckBoxesSelecionadosComoArray(campoChave)[0];\n";
		$html .= "  }\n";
		
		$html .= "  lupa = document.frm_principal." . constantes::$ID_REQ_LUPA . ".value;\n";
		$html .= "  multiSelecao = document.frm_principal." . constantes::$ID_REQ_MULTISELECAO . ".value;\n";
		$html .= "  location.href='detalhar.php?funcao=' + funcao + '&chave=' + chave + '&" . constantes::$ID_REQ_LUPA . "='+ lupa + '&" . constantes::$ID_REQ_MULTISELECAO . "='+ multiSelecao;\n";
		$html .= "}\n\n";
	}
	
	if ($temExcluir) {
		$html .= " function excluir() {\n";
		$html .= "  detalhar(true);\n";
		$html .= " }\n\n";
	}
	
	if ($temIncluir) {
		$html .= " function incluir() {\n";
		$html .= "  location.href='manter.php?funcao=" . constantes::$CD_FUNCAO_INCLUIR . "';\n";
		$html .= " }\n\n";
	}
	
	if ($temAlterar) {
		$html .= " function alterar() {\n";
		$html .= "  if (!isRadioButtonConsultaSelecionado('$pNmCampoConsulta'))\n";
		$html .= "   return;\n";
		
		if ($isHistoricoFiltro)
			$html .= "  exibirMensagem('Registro de historico nao permite alteracao.');return;\n";
		
		$html .= " chave = $pNmCampoConsulta.value;\n";
		$html .= " location.href='manter.php?funcao=" . constantes::$CD_FUNCAO_ALTERAR . "&chave=' + chave;\n";
		$html .= "}\n\n";
	}
	
	if ($temCancelar) {
		$html .= " function cancelar() {\n";
		$html .= "  lupa = document.frm_principal." . constantes::$ID_REQ_LUPA . ".value;\n";
		$html .= "  multiSelecao = document.frm_principal." . constantes::$ID_REQ_MULTISELECAO . ".value;\n";
		$html .= "  location.href='index.php?consultar=S&" . constantes::$ID_REQ_LUPA . "='+ lupa + '&" . constantes::$ID_REQ_MULTISELECAO . "='+ multiSelecao;\n";
		
		$html .= "}\n\n";
	}
	
	return $html;
}

?>