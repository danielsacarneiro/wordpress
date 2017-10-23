<?php
include_once("../../config_lib.php");
include_once(caminho_util."bibliotecaHTML.php");

try{
//inicia os parametros
inicio();

$vo = new vopessoaturma();
//var_dump($vo->varAtributos);
$vo->getVOExplodeChave($chave);
$isHistorico = ($vo->sqHist != null && $vo->sqHist != "");

$readonly = "";
$nmFuncao = "";
$readonly = "readonly";
$dbprocesso = $vo->dbprocesso;					
$colecao = $dbprocesso->consultarPorChave($vo, $isHistorico);	
$vo->getDadosBancoPorChave($colecao);

$voTurma = new voturma();
$voTurma->getDadosBanco($colecao);

putObjetoSessao($vo->getNmTabela(), $vo);
    
$dhInclusao = $vo->dhInclusao;
$dhUltAlteracao = $vo->dhUltAlteracao;
$cdUsuarioInclusao = $vo->cdUsuarioInclusao;
$cdUsuarioUltAlteracao = $vo->cdUsuarioUltAlteracao;


$nmFuncao = "DETALHAR ";
$titulo = $vo->getTituloJSP();
$complementoTit = "";
$isExclusao = false;
if($isHistorico)
    $complementoTit = " Histórico";

$funcao = @$_GET["funcao"];
if($funcao == constantes::$CD_FUNCAO_EXCLUIR){
	$nmFuncao = "EXCLUIR ";
    $isExclusao = true;
}

$titulo = $nmFuncao. $titulo. $complementoTit;
setCabecalho($titulo);  
?>
<!DOCTYPE html>
<HEAD>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_principal.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_cnpfcnpj.js"></SCRIPT>

<SCRIPT language="JavaScript" type="text/javascript">
// Verifica se o formulario esta valido para alteracao, exclusao ou detalhamento
function isFormularioValido() {
	if (!isRadioButtonConsultaSelecionado("document.frm_principal.rdb_consulta"))
		return false;		
	return true;
}

function cancelar() {
	//history.back();
	lupa = document.frm_principal.lupa.value;	
	location.href="index.php?consultar=S&lupa="+ lupa;	
}

function confirmar() {
	return confirm("Confirmar Alteracoes?");    
}

</SCRIPT>

</HEAD>
<?=setTituloPagina($vo->getTituloJSP())?>
<BODY class="paginadados" onload="">
	  
<FORM name="frm_principal" method="post" action="../confirmar.php?class=<?=get_class($vo)?>" onSubmit="return confirmar();">

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
	            <TH class="campoformulario" nowrap width=1%>Valor Mensal:</TH>
	            <TD class="campoformulario" colspan="3"><INPUT type="text" id="<?=voturma::$nmAtrValor?>" name="<?=voturma::$nmAtrValor?>" value="<?php echo(getMoeda($vo->valor));?>"
	            class="camporeadonlyalinhadodireita" size="15" readonly></TD>
	        </TR>					            
			<TR>
                <TH class="campoformulario" nowrap width=1%>Observação:</TH>
                <TD class="campoformulario" colspan=3>
                				<textarea rows="2" cols="60" id="<?=voturma::$nmAtrObservacao?>" name="<?=voturma::$nmAtrObservacao?>" class="camporeadonly" maxlength="300" readonly><?php echo($vo->obs);?></textarea>
				</TD>
            </TR>        
            <?php
            	echo mostrarGridFinanceiro($vo, true, $voTurma);					  
			?>            
                     
            
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
