<?php
include_once("../../config_lib.php");
include_once(caminho_util."bibliotecaHTML.php");

try{
//inicia os parametros
inicioComValidacaoUsuario(true);

$vo = new vopessoaturma();
//var_dump($vo->varAtributos);

$funcao = @$_GET["funcao"];

$readonly = "";
$isInclusao = $funcao == constantes::$CD_FUNCAO_INCLUIR;

$nmFuncao = "";
if($isInclusao){    
	$nmFuncao = "INCLUIR ";	
}else{
    $readonly = "readonly";
    $vo->getVOExplodeChave($chave);
    $isHistorico = ($vo->sqHist != null && $vo->sqHist != "");
    
	$dbprocesso = $vo->dbprocesso;					
	$colecao = $dbprocesso->consultarPorChave($vo, $isHistorico);	
	$vo->getDadosBanco($colecao);
	
	$voTurma = new voturma();
	$voTurma->getDadosBanco($colecao);
	
	putObjetoSessao($vo->getNmTabela(), $vo);

    $nmFuncao = "ALTERAR ";
}

$titulo = $vo->getTituloJSP();
$titulo = $nmFuncao . $titulo;
setCabecalho($titulo);

$nome  = $vo->descricao;
   
?>
<!DOCTYPE html>
<HEAD>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_principal.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_moeda.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>mensagens_globais.js"></SCRIPT>

<SCRIPT language="JavaScript" type="text/javascript">
// Verifica se o formulario esta valido para alteracao, exclusao ou detalhamento
function isFormularioValido() {
	if (!isCampoMoedaComSeparadorMilharValido(document.frm_principal.<?=vopessoaturma::$nmAtrValor?>, 2, false))
		return false;		
	return true;
}

function cancela() {
	//history.back();
	location.href="index.php?consultar=S";	
}

function confirmar() {
	if(!isFormularioValido())
		return false;
	
	return confirm("Confirmar Alteracoes?");    
}

</SCRIPT>
</HEAD>
<?=setTituloPagina($vo->getTituloJSP())?>
<BODY class="paginadados" onload="">
	  
<FORM name="frm_principal" method="post" action="../confirmar.php?class=<?=get_class($vo)?>" onSubmit="return confirmar();">

<INPUT type="hidden" id="funcao" name="funcao" value="<?=$funcao?>">
<INPUT type="hidden" id="<?=vousuario::$nmAtrID?>" name="<?=vousuario::$nmAtrID?>" value="<?=id_user?>">
<INPUT type="hidden" id="<?=voturma::$nmAtrCd?>" name="<?=voturma::$nmAtrCd?>" value="<?=$vo->cdTurma?>">
 
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
            
			<TR>
                <TH class="campoformulario" nowrap width=1%>Pessoa:</TH>
                <TD class="campoformulario" colspan=3><INPUT type="text" value="<?php echo($vo->getCodigoDEscricaoFormatado($vo->cdPessoa, $colecao[vopessoa::$nmAtrNome]));?>"  class="camporeadonly" size="50" readonly></TD>
            </TR>
                <TH class="campoformulario" nowrap width=1%>Turma:</TH>
                <TD class="campoformulario" colspan=3><INPUT type="text" value="<?php echo($vo->getCodigoDEscricaoFormatado($vo->cdTurma, $colecao[voturma::$nmAtrDescricao]));?>"  class="camporeadonly" size="50" readonly></TD>
                
				<INPUT type="hidden" id="<?=vopessoaturma::$nmAtrCdTurma?>" name="<?=vopessoaturma::$nmAtrCdTurma?>"  value="<?php echo($vo->cdTurma);?>">
				<INPUT type="hidden" id="<?=vopessoaturma::$nmAtrCdPessoa?>" name="<?=vopessoaturma::$nmAtrCdPessoa?>"  value="<?php echo($vo->cdPessoa);?>">						
            </TR>            
             <TR>
				<TH class="campoformulario" nowrap width=1%>Tipo Turma:</TH>
	            <TD class="campoformulario" colspan=3><?php echo dominioTipoTurma::getDetalhamentoHtml($voTurma->tipo, voturma::$nmAtrTipo, voturma::$nmAtrTipo)?></TD>
	        </TR>
			<TR>
	            <TH class="campoformulario" nowrap width=1%>Valor a pagar:</TH>
	            <TD class="campoformulario" colspan="3">
	            <?php if(!dominioTipoTurma::isPagamentoMensal($voTurma->tipo)){?>
	            	<INPUT type="text" id="<?=vopessoaturma::$nmAtrNumParcelas?>" name="<?=vopessoaturma::$nmAtrNumParcelas?>" value="<?php echo($vo->numParcelas);?>" class="camponaoobrigatorioalinhadodireita" size="2" required> x
	            <?php }?>
	            <INPUT type="text" id="<?=vopessoaturma::$nmAtrValor?>" name="<?=vopessoaturma::$nmAtrValor?>" value="<?php echo(getMoeda($vo->valor));?>"
	            onkeyup="formatarCampoMoedaComSeparadorMilhar(this, 2, event);" class="camponaoobrigatorioalinhadodireita" size="15" required></TD>
	        </TR>
			<TR>
                <TH class="campoformulario" nowrap width=1%>Observação:</TH>
                <TD class="campoformulario" colspan=3>
                				<textarea rows="2" cols="60" id="<?=vopessoaturma::$nmAtrObservacao?>" name="<?=vopessoaturma::$nmAtrObservacao?>" class="camponaoobrigatorio" maxlength="300"><?php echo($vo->obs);?></textarea>
				</TD>
            </TR>                 

        <?php if(!$isInclusao){
            echo "<TR>" . incluirUsuarioDataHoraDetalhamento($vo) .  "</TR>";
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
								<?php
								if($funcao == "I" || $funcao == "A"){
								?>
                                    <TD class="botaofuncao"><?=getBotaoConfirmar()?></TD>
								<?php
								}?>
								<TD class="botaofuncao"><button id="cancelar" onClick="javascript:cancela();" class="botaofuncaop" type="button" accesskey="c">Cancelar</button></TD>                                
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
