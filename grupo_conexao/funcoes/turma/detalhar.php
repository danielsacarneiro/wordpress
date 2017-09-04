<?php
include_once("../../config_lib.php");
include_once(caminho_util."bibliotecaHTML.php");

try{
//inicia os parametros
inicio();

removeObjetoSessao ( voturma::$ID_REQ_COLECAO_ALUNOS );

$vo = new voturma();
$vo->getVOExplodeChave($chave);
$isHistorico = ($vo->sqHist != null && $vo->sqHist != "");

$readonly = "";
$nmFuncao = "";
$readonly = "readonly";
$dbprocesso = $vo->dbprocesso;					
$colecao = $dbprocesso->consultarPorChave($vo, $isHistorico);	
$vo->getDadosBancoPorChave($colecao);
putObjetoSessao($vo->getNmTabela(), $vo);

//var_dump($vo->colecaoAlunos);
    
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
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_moeda.js"></SCRIPT>
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
			<?php if($isHistorico){?>
			<TR>
                <TH class="campoformulario" nowrap width=1%>Sq.Hist:</TH>
                <TD class="campoformulario" colspan=3><INPUT type="text" value="<?php echo(complementarCharAEsquerda($vo->sqHist, "0", TAMANHO_CODIGOS));?>"  class="camporeadonlyalinhadodireita" size="5" readonly></TD>
                <INPUT type="hidden" id="<?=voentidade::$nmAtrSqHist?>" name="<?=voentidade::$nmAtrSqHist?>" value="<?=$vo->sqHist?>">
            </TR>               
            <?php }
            ?>            
			<TR>
                <TH class="campoformulario" nowrap width=1%>Código:</TH>
                <TD class="campoformulario" colspan=3><INPUT type="text" value="<?php echo(complementarCharAEsquerda($vo->cd, "0", TAMANHO_CODIGOS));?>"  class="camporeadonlyalinhadodireita" size="5" readonly></TD>
            </TR>            
			<TR>
                <TH class="campoformulario" nowrap width=1%>Descrição:</TH>
                <TD class="campoformulario" colspan=3><INPUT type="text" id="<?=voturma::$nmAtrDescricao?>" name="<?=voturma::$nmAtrDescricao?>"  value="<?php echo($vo->descricao);?>"  class="camporeadonly" size="50" readonly></TD>
            </TR>  
            <TR>
	            <TH class="campoformulario" nowrap width=1%>Investimento:</TH>
	            <TD class="campoformulario" colspan=3><INPUT type="text" id="<?=voturma::$nmAtrValor?>" name="<?=voturma::$nmAtrValor?>" value="<?php echo(getMoeda($vo->valor));?>"
	            class="camporeadonlyalinhadodireita" size="15" readonly></TD>
	        </TR>
			<TR>
	            <TH class="campoformulario" nowrap width="1%">Período:</TH>
	            <TD class="campoformulario" colspan=3>
	            	Dt.Início: <INPUT type="text" 
	            	       id="<?=voturma::$nmAtrDtInicio?>" 
	            	       name="<?=voturma::$nmAtrDtInicio?>" 
	            			value="<?php echo(getData($vo->dtInicio));?>"
	            			class="camporeadonly" 
	            			size="10" 
	            			maxlength="10" readonly>
				a Dt.Fim:
	            	<INPUT type="text" 
	            	       id="<?=voturma::$nmAtrDtFim?>" 
	            	       name="<?=voturma::$nmAtrDtFim?>" 
	            			value="<?php echo(getData($vo->dtFim));?>" 
	            			class="camporeadonly" 
	            			size="10" 
	            			maxlength="10" readonly>
	            Duração:
	             <INPUT type="text" name = "<?=voturma::$ID_REQ_DURACAO?>" value="<?php if($vo->dtFim != null) echo getQtdMesesEntreDatas($vo->dtInicio, $vo->dtFim);?>"  class="camporeadonlyalinhadodireita" size="3" readonly> mes(es)
	             </TD>
	        </TR>	        
			<TR>
                <TH class="campoformulario" nowrap width=1%>Observação:</TH>
                <TD class="campoformulario" colspan=3>
                				<textarea rows="2" cols="60" id="<?=voturma::$nmAtrObservacao?>" name="<?=voturma::$nmAtrObservacao?>" class="camporeadonly" maxlength="300" readonly><?php echo($vo->obs);?></textarea>
				</TD>
            </TR>       
            
            <TR>
				<TH class="textoseparadorgrupocampos" halign="left" colspan="4">
				<DIV class="campoformulario">&nbsp;&nbsp;Alunos na turma&nbsp;&nbsp;
				</DIV>
				</TH>
			</TR>
			<TR>	
            <TD class="conteinerfiltro" colspan="4">            
            <TABLE cellpadding="0" cellspacing="0" id="<?=voturma::$NM_DIV_COLECAO_ALUNOS?>">
            <TBODY>
            		<?php
            		$voCamposDadosPessoaAjax = $vo;
            		$colecaoAlunosAntesCadastrados = $vo->colecaoAlunos;
            		if($colecaoAlunosAntesCadastrados!= null){
            			$colecaoCdPessoasCadastradas = array_keys($colecaoAlunosAntesCadastrados);
            		}
            		$strCdPessoasCadastradas = getColecaoEntreSeparador($colecaoCdPessoasCadastradas, constantes::$CD_CAMPO_SEPARADOR_ARRAY);
            		//deixa gravado na pagina os alunos anteriormente cadastrados
            		//para usa-los na exclusao???
            		echo getInputHidden(voturma::$ID_REQ_COLECAO_ALUNOS_ANTERIOR, voturma::$ID_REQ_COLECAO_ALUNOS_ANTERIOR, $strCdPessoasCadastradas) . "\n";					
            		echo mostrarGridAlunos($colecaoAlunosAntesCadastrados, true);					  					  
					?>
            
            </TBODY>
            </TABLE>
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
