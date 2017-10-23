<?php
include_once("../../config_lib.php");
include_once(caminho_util."bibliotecaHTML.php");

try{
//inicia os parametros
inicioComValidacaoUsuario(true);

$vo = new voturma();
//var_dump($vo->varAtributos);

$funcao = @$_GET["funcao"];

$readonly = "";
$isInclusao = $funcao == constantes::$CD_FUNCAO_INCLUIR;

removeObjetoSessao ( voturma::$ID_REQ_COLECAO_ALUNOS );
$nmFuncao = "";
if($isInclusao){    
	$nmFuncao = "INCLUIR ";	
}else{
    $readonly = "readonly";
    $vo->getVOExplodeChave($chave);
    $isHistorico = ($vo->sqHist != null && $vo->sqHist != "");
    
	$dbprocesso = $vo->dbprocesso;					
	$colecao = $dbprocesso->consultarPorChave($vo, $isHistorico);	
	$vo->getDadosBancoPorChave($colecao);
	putObjetoSessao($vo->getNmTabela(), $vo);
	//putObjetoSessao(voturma::$ID_REQ_COLECAO_ALUNOS, $vo->colecaoAlunos);

    $nmFuncao = "ALTERAR ";
}

$titulo = $vo->getTituloJSP();
$titulo = $nmFuncao . $titulo;
setCabecalho($titulo);

$nome  = $vo->descricao;

putObjetoSessao(voturma::$ID_REQ_COLECAO_ALUNOS_ANTERIOR, $vo->colecaoAlunos);
   
?>
<!DOCTYPE html>
<HEAD>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_principal.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_moeda.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_datahora.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_ajax.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_pessoa.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_text.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_select.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>jquery.js"></SCRIPT>

<SCRIPT language="JavaScript" type="text/javascript">

//consulta de ajax pra ajudar
/*$.ajax({ 
	type: 'POST', 
	url: url, 
	data: data, 
	beforeSend: function() { 
		// setting a timeout $(placeholder).addClass('loading'); i++; 
		}, 
	success: function(data) { 
		if (append) { 
			$(placeholder).append(data); 
		} else { 
			$(placeholder).html(data); 
			} 
		}, 
	error: function(xhr) { 
		// if error occured alert("Error occured.please try again"); 
		$(placeholder).append(xhr.statusText + xhr.responseText); 
		$(placeholder).removeClass('loading'); 
		}, 
	complete: function() { 
		i--; 
		if (i <= 0) {
			 $(placeholder).removeClass('loading'); 
			 } 
		 }, 
	dataType: 'html' });
}
//No beforeSend*/
	
function listarAlunos(cdPessoa, cdTurma, operacao) {
	
	//try{
		parcelas = getValueComoArray("<?=vopessoaturma::$nmAtrNumParcelas?>[]");
		valores = getValueComoArray("<?=vopessoaturma::$nmAtrValor?>[]");
		cdsPessoa = getValueComoArray("<?=vopessoaturma::$nmAtrCdPessoa?>[]");
		valorTurma = getValorCampoMoedaComoNumero(document.frm_principal.<?=voturma::$nmAtrValor?>);
		tipoTurma = document.frm_principal.<?=voturma::$nmAtrTipo?>.value;
	/*}catch(ex){
		parcelas = "";
		valores = "";
		cdsPessoa = "";
	}*/

	  $.ajax({
	    type: "POST",
	    url: "../pessoa/campoDadosPessoaAjax.php",	    
	    data: {
	      //cdPessoa: $('#seu_nome').val()
	      funcao: "<?=$funcao?>",
	      chavePessoa: cdPessoa,
	      cdTurma: cdTurma,
	      tipoTurma: tipoTurma,
	      valorTurma: valorTurma,
	      operacao: operacao,
	      <?=vopessoaturma::$nmAtrNumParcelas?>: parcelas,
	      <?=vopessoaturma::$nmAtrValor?>: valores,
	      <?=vopessoaturma::$nmAtrCdPessoa?>: cdsPessoa
	    },
		beforeSend: function() { 
			$('#<?=voturma::$NM_DIV_COLECAO_ALUNOS?>').html("<img  title='Limpar' src='<?=caminho_imagens?>loading/loading26.gif'>");			 
		}, 
	    success: function(data) {
	      $('#<?=voturma::$NM_DIV_COLECAO_ALUNOS?>').html(data);
	    }
	  });
}

function transferirDadosPessoa(cdPessoa) {		   

	cdTurma = document.frm_principal.<?=voturma::$nmAtrCd?>.value;
	listarAlunos(cdPessoa, cdTurma, "<?=constantes::$CD_FUNCAO_INCLUIR?>");
	
	//chamar funcao bibliotecafuncoespessoa
	//cdPessoa = cdPessoa + CD_CAMPO_SEPARADOR + cdTurma;
	//carregarDadosAluno(cdPessoa, '<?=voturma::$NM_DIV_COLECAO_ALUNOS?>');	
}

function limparDadosPessoa(cdPessoa) {
	cdTurma = document.frm_principal.<?=voturma::$nmAtrCd?>.value;
	listarAlunos(cdPessoa, cdTurma, "<?=constantes::$CD_FUNCAO_EXCLUIR?>");		
	
	//bibliotecapessoajs
	//limparDadosAluno(cdPessoa, '<?=voturma::$NM_DIV_COLECAO_ALUNOS?>');		
}

function validaSelecionarAlunos() {
	if (!isCampoSelectValido(document.frm_principal.<?=voturma::$nmAtrTipo?>))
		return false;

	if (!isCampoMoedaComSeparadorMilharValido(document.frm_principal.<?=voturma::$nmAtrValor?>, 2, false))
		return false;

	return true;
}

function isTipoTurmaPagMensal(){
	return document.frm_principal.<?=voturma::$nmAtrTipo?>.value != <?=dominioTipoTurma::$CD_TP_TURMA_PRAZO_DETERM_TOTAL?>;
}

function validaDistribuirValores() {
	if (!isCampoSelectValido(document.frm_principal.<?=voturma::$nmAtrTipo?>))
		return false;

	/*if (isTipoTurmaPagMensal()){
		exibirMensagem("Operação não permitida para tipo pagamento mensal.");
		return false;
	}*/

	if (!isCampoMoedaComSeparadorMilharValido(document.frm_principal.<?=voturma::$nmAtrValor?>, 2, false))
		return false;

	return true;
}

// Verifica se o formulario esta valido para alteracao, exclusao ou detalhamento
function isFormularioValido() {
	if (!isCampoSelectValido(document.frm_principal.<?=voturma::$nmAtrTipo?>))
		return false;

	if (!isCampoMoedaComSeparadorMilharValido(document.frm_principal.<?=voturma::$nmAtrValor?>, 2, false))
		return false;

	dataInicio = document.frm_principal.<?=voturma::$nmAtrDtInicio?>;
	dataFim = document.frm_principal.<?=voturma::$nmAtrDtFim?>;
	if(!isPeriodoValido(dataInicio, dataFim, true, true, true, false, true)) {
		return false;
	}

	if (!isValoresPreenchidos()){
		return false;	
	}
	
	return true;
}

function cancelar() {
	//history.back();
	lupa = document.frm_principal.lupa.value;
	location.href="index.php?consultar=S&lupa="+ lupa;	
}

function confirmar() {
	if(!isFormularioValido())
		return false;
	
	return confirm("Confirmar Alteracoes?");    
}

function getNumDuracao(){
	try{
		num = getQtMeses(document.frm_principal.<?=voturma::$nmAtrDtInicio?>.value, document.frm_principal.<?=voturma::$nmAtrDtFim?>.value);
		if(!isNaN(num) && num >= 0)
			document.frm_principal.<?=voturma::$ID_REQ_DURACAO?>.value = num;
		else
			document.frm_principal.<?=voturma::$ID_REQ_DURACAO?>.value = '';
	}catch(err){
		document.frm_principal.<?=voturma::$ID_REQ_DURACAO?>.value = '';
	};
}

function formataCamposPagamento(pCdPessoa){
	
	campoParcelas = document.getElementById("<?=vopessoaturma::$nmAtrNumParcelas?>"+pCdPessoa);
	campoValor = document.getElementById("<?=vopessoaturma::$nmAtrValor?>"+pCdPessoa);
	campoTotal = document.getElementById("<?=vopessoaturma::$ID_REQ_VALOR_TOTAL?>"+pCdPessoa);

	if(!isCampoNumericoPositivoValido(campoParcelas, true, 1)){
		return false;
	}
	
	numParcelas = campoParcelas.value;	
	valor = campoValor.value;
	
	if(!isCampoMoedaComSeparadorMilharValido(campoValor, 2, true, 0.01, null, true, true)){
		valor = getValorCampoMoedaComoNumero(document.frm_principal.<?=voturma::$nmAtrValor?>);
		valor = valor/numParcelas;
	}else{
		valor = getValorCampoMoedaComoNumero(campoValor);
	}	
	
	try{
		total = numParcelas * valor;
	}catch(err){
		total = 0;
	};

	if(isNaN(total)){
		total = 0;
	}
	if(isNaN(valor)){
		valor = 0;
	}

	setValorCampoMoedaComSeparadorMilhar(campoTotal, total, 2);
	setValorCampoMoedaComSeparadorMilhar(campoValor, valor, 2);
	getSomatorioValoresPessoas();	
}

function isValoresPreenchidos(){
	try{
		arrayValores = document.getElementsByName("<?=vopessoaturma::$nmAtrValor?>[]");
		arrayParcelas = document.getElementsByName("<?=vopessoaturma::$nmAtrNumParcelas?>[]");
	}catch(err){
		arrayValores = null;
		arrayParcelas = null;
	}
	if(arrayValores != null){
		for(i=0; i < arrayValores.length ;i++){
			campoValor  = arrayValores[i];
			campoParcela = arrayParcelas[i];

			if(!isCampoMoedaComSeparadorMilharValido(campoValor, 2, true, 0.01)){
				return false;
			}
			
			if(!isCampoNumericoPositivoValido(campoParcela, true, 1)){
				return false;
			}
		}		
	}

	return true;
}

function getSomatorioValoresPessoas(){
	try{
		campoTotalTurma = document.getElementById("<?=voturma::$ID_REQ_VALOR_TOTAL?>");
		array = document.getElementsByName("<?=vopessoaturma::$ID_REQ_VALOR_TOTAL?>");
		
		//alert(array[0]);
		valor = 0;
		for(i=0; i < array.length ;i++){
			try{
				soma = getValorCampoMoedaComoNumero(array[i]);
				if(isNaN(soma)){
					soma = 0;
				}
						
				valor = valor + soma;
			}catch(erro){
				;
			}
		}
		setValorCampoMoedaComSeparadorMilhar(campoTotalTurma, valor, 2);
	}catch(err){
	;
	}
}
function distribuirValoresPessoas(zerar, apenasSeVazio){
	isPagMensal = isTipoTurmaPagMensal();
	//alert(1);	
	if(apenasSeVazio == null){
		apenasSeVazio = false;
	}

	if(zerar == null){
		zerar = false;
	}
	
	campoValorTurma = document.frm_principal.<?=voturma::$nmAtrValor?>;
	if(!validaDistribuirValores()){
		return;
	}

	valorTurma = getValorCampoMoedaComoNumero(campoValorTurma);
	try{
		arrayValores = document.getElementsByName("<?=vopessoaturma::$nmAtrValor?>[]");
		arrayValoresTotal = document.getElementsByName("<?=vopessoaturma::$ID_REQ_VALOR_TOTAL?>");
		arrayParcelas = document.getElementsByName("<?=vopessoaturma::$nmAtrNumParcelas?>[]");
		if(arrayValores != null){
			
			for(i=0; i < arrayValores.length ;i++){ 
				valor = 0;
				campoValor = arrayValores[i];
				isCampoAtivo = !campoValor.readOnly;
				//alert(isCampoAtivo); 
				if(isCampoAtivo && (!apenasSeVazio || (campoValor.value == null || campoValor.value == "" || getValorCampoMoedaComoNumero(campoValor) == 0))){					
						if(zerar){
							valor = 0;
							valorTurma = 0;
						}else{
							//distribuir igualmente
							parcela = eval(arrayParcelas[i].value);

							//o pagamento mensal faz com que o valor da turma seja igual ao valor da pessoa
							if(isPagMensal){
								parcela = 1;
							}							
							valor = valorTurma/parcela;
						}
						
						setValorCampoMoedaComSeparadorMilhar(campoValor, valor, 2);
						setValorCampoMoedaComSeparadorMilhar(arrayValoresTotal[i], valorTurma, 2);
				}
			}
		}
	}catch(erro){
		;
	}	
	getSomatorioValoresPessoas();
}
</SCRIPT>
</HEAD>
<?=setTituloPagina($vo->getTituloJSP())?>
<BODY class="paginadados" onload="">
	  
<FORM name="frm_principal" method="post" action="confirmar.php?class=<?=get_class($vo)?>" onSubmit="return confirmar();">

<INPUT type="hidden" id="funcao" name="funcao" value="<?=$funcao?>">
<INPUT type="hidden" id="<?=vousuario::$nmAtrID?>" name="<?=vousuario::$nmAtrID?>" value="<?=id_user?>">
<INPUT type="hidden" id="<?=voturma::$nmAtrCd?>" name="<?=voturma::$nmAtrCd?>" value="<?=$vo->cd?>">
 
<TABLE id="table_conteiner" class="conteiner" cellpadding="0" cellspacing="0">
    <TBODY>
		<TR>
		<TD class="conteinerfiltro"><?=cabecalho?></TD>
		</TR>
        <TR>
            <TD class="conteinerfiltro">
            <DIV id="div_filtro" class="div_filtro">
            <TABLE id="table_filtro" class="filtro" cellpadding="0" cellspacing="0">
            <TBODY>
	        <?php if(!$isInclusao){?>
				<TR>
	                <TH class="campoformulario" nowrap width=1%>Código:</TH>
	                <TD class="campoformulario" colspan=3><INPUT type="text" value="<?php echo(complementarCharAEsquerda($vo->cd, "0", TAMANHO_CODIGOS));?>"  class="camporeadonlyalinhadodireita" size="5" readonly></TD>
	            </TR>                            
				<TR>
					<TH class="campoformulario" nowrap width=1%>Tipo:</TH>
	                <TD class="campoformulario" colspan=3><?php echo dominioTipoTurma::getDetalhamentoHtml($vo->tipo, voturma::$nmAtrTipo, voturma::$nmAtrTipo)?></TD>
	            </TR>                            
	        <?php }else{?>
			<TR>
                <TH class="campoformulario" nowrap width=1%>Tipo:</TH>
                <TD class="campoformulario" colspan=3>
                <?php
                echo getComboColecaoGenerico(dominioTipoTurma::getColecao(), voturma::$nmAtrTipo, voturma::$nmAtrTipo, $vo->tipo, constantes::$CD_CLASS_CAMPO_NAO_OBRIGATORIO, " onChange='limparDadosPessoa(-1);' required ");
                ?></TD>
            </TR>
	        <?php }?>
			<TR>
                <TH class="campoformulario" nowrap width=1%>Descrição:</TH>
                <TD class="campoformulario" colspan=3><INPUT type="text" id="<?=voturma::$nmAtrDescricao?>" name="<?=voturma::$nmAtrDescricao?>"  value="<?php echo($vo->descricao);?>"  class="camponaoobrigatorio" size="50" required></TD>
            </TR>
			<TR>
	            <TH class="campoformulario" nowrap width=1%>Investimento:</TH>
	            <TD class="campoformulario" colspan="3"><INPUT type="text" id="<?=voturma::$nmAtrValor?>" name="<?=voturma::$nmAtrValor?>" required value="<?php echo(getMoeda($vo->valor));?>"
	            onkeyup="formatarCampoMoedaComSeparadorMilhar(this, 2, event);" onChange="distribuirValoresPessoas(false, false);" class="camponaoobrigatorioalinhadodireita" size="15" ></TD>	            
	        </TR>
			<TR>
	            <TH class="campoformulario" nowrap width="1%">Período:</TH>
	            <TD class="campoformulario" colspan=3>
	            	Dt.Início: <INPUT type="text" 
	            	       id="<?=voturma::$nmAtrDtInicio?>" 
	            	       name="<?=voturma::$nmAtrDtInicio?>" 
	            			value="<?php echo(getData($vo->dtInicio));?>"
	            			onkeyup="formatarCampoData(this, event, false);" 
	            			onChange="getNumDuracao();" 
	            			class="camponaoobrigatorio" 
	            			size="10" 
	            			maxlength="10" required>
				a Dt.Fim:
	            	<INPUT type="text" 
	            	       id="<?=voturma::$nmAtrDtFim?>" 
	            	       name="<?=voturma::$nmAtrDtFim?>" 
	            			value="<?php echo(getData($vo->dtFim));?>"
	            			onkeyup="formatarCampoData(this, event, false);"
		            		onChange="getNumDuracao();" 
	            			class="camponaoobrigatorio" 
	            			size="10" 
	            			maxlength="10">
	            Duração:
	             <INPUT type="text" name = "<?=voturma::$ID_REQ_DURACAO?>" value="<?php if($vo->dtFim != null) echo getQtdMesesEntreDatas(getData($vo->dtInicio), getData($vo->dtFim));?>"  class="camporeadonlyalinhadodireita" size="3" readonly> mes(es) aprox.
	             </TD>
	            </TR>                            
	            			
				</TD>                
            </TR>	        
			<TR>
                <TH class="campoformulario" nowrap width=1%>Observação:</TH>
                <TD class="campoformulario" colspan=3>
                				<textarea rows="2" cols="60" id="<?=voturma::$nmAtrObservacao?>" name="<?=voturma::$nmAtrObservacao?>" class="camponaoobrigatorio" maxlength="300"><?php echo($vo->obs);?></textarea>
				</TD>
            </TR>
			<TR>
				<TH class="textoseparadorgrupocampos" halign="left" colspan="4">
				<DIV class="campoformulario">&nbsp;&nbsp;Incluir Alunos&nbsp;&nbsp;
				<?php 
				include_once(caminho_funcoes. "pessoa/dominioVinculoPessoa.php");
				echo getLinkPesquisa("../pessoa/index.php?".constantes::$ID_REQ_MULTISELECAO."=S&" . vopessoavinculo::$nmAtrCd . "=" . dominioVinculoPessoa::$CD_VINCULO_ALUNO, 'validaSelecionarAlunos()');
				echo "&nbsp;&nbsp; Limpar tudo" . getBorrachaJS("limparDadosPessoa(-1);");
				echo "&nbsp;&nbsp; Distribuir Valor Turma " . getBotao ( "", "Distribuir valor", $classe, false, " onClick='javascript:distribuirValoresPessoas(false, false);'" );
				echo "&nbsp;&nbsp; Zerar valores " . getBotao ( "", "zerar valores", $classe, false, " onClick='javascript:distribuirValoresPessoas(true);'" );
				?>
				</DIV>
				</TH>
			</TR>
						
			<TR>	
            <TD class="conteinerfiltro" colspan="4">            
            <TABLE cellpadding="0" cellspacing="0" id="<?=voturma::$NM_DIV_COLECAO_ALUNOS?>">
            <TBODY>
            		<?php
					$voCamposDadosPessoaAjax = $vo;
					include_once(caminho_funcoes. "pessoa/campoDadosPessoaAjax.php");					  
					?>
            
            </TBODY>
            </TABLE>
            </TD>
            </TR>
			
                             
        <?php if(!$isInclusao){
            echo  incluirUsuarioDataHoraDetalhamento($vo) ;
        }?>
            </TBODY>
            </TABLE>
            </DIV>
            </TD>
        </TR>
        <TR>
            <TD class="conteinerbarraacoes">
            <TABLE id="table_barraacoes" class="barraacoes" cellpadding="0" cellspacing="0">
                <TBODY>
                    <TR>
						<TD>
                    		<TABLE class="barraacoesaux" cellpadding="0" cellspacing="0">
	                    	<TR>
							<?=getBotoesRodape();?>
						    </TR>
		                    </TABLE>
	                    </TD>
                    </TR>  
                </TBODY>
            </TABLE>
            </TD>
        </TR>
    </TBODY>
</TABLE>
</FORM>

</BODY>
</HTML>
<?php 
} catch ( Exception $ex ) {
	putObjetoSessao ( "vo", $vo );
	tratarExcecaoHTML ( $ex );
}
?>