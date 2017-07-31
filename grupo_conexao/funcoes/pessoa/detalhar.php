<?php
include_once("../../config_lib.php");
include_once(caminho_util."bibliotecaHTML.php");
include_once(caminho_vos."dbpessoa.php");

//inicia os parametros
try{
inicio();

$vo = new vopessoa();
//var_dump($vo->varAtributos);
$chave = @$_GET["chave"];
$array = explode("*",$chave);

$vo->getVOExplodeChave($chave);
$isHistorico = ($vo->sqHist != null && $vo->sqHist != "");

$readonly = "";
$nmFuncao = "";
$readonly = "readonly";
$dbprocesso = new dbpessoa();					
$colecao = $dbprocesso->consultarPorChave($vo, $isHistorico);	
$vo->getDadosBanco($colecao);

putObjetoSessao($vo->getNmTabela(), $vo);

$nome  = $vo->nome;
$doc  = $vo->doc;
$email  = $vo->email;
    
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

$colspanColunas = 5;

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
<INPUT type="hidden" id="<?=vopessoa::$nmAtrCd?>" name="<?=vopessoa::$nmAtrCd?>" value="<?=$vo->cd?>">
 
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
                <TD class="campoformulario" colspan=<?=$colspanColunas?>><INPUT type="text" value="<?php echo(complementarCharAEsquerda($vo->sqHist, "0", TAMANHO_CODIGOS));?>"  class="camporeadonlyalinhadodireita" size="5" readonly></TD>
                <INPUT type="hidden" id="<?=voentidade::$nmAtrSqHist?>" name="<?=voentidade::$nmAtrSqHist?>" value="<?=$vo->sqHist?>">
            </TR>               
            <?php }
            ?>
            <TR>
                <TH class="campoformulario" nowrap width=1%>Código:</TH>
                <TD class="campoformulario" colspan=<?=$colspanColunas-2?>><INPUT type="text" value="<?php echo(complementarCharAEsquerda($vo->cd, "0", TAMANHO_CODIGOS));?>"  class="camporeadonlyalinhadodireita" size="5" readonly></TD>
                 <TD class="campoformulario" width="1%"  rowspan=6>
                	<INPUT type="hidden" id="<?=vopessoa::$nmAtrFoto?>" name="<?=vopessoa::$nmAtrFoto?>"  value="<?php echo($vo->foto);?>">
                	<img src='<?=vopessoa::getNMPastaDestinoFotos(true). $vo->foto?>' height='150' class='aligncenter'>
				</TD>
            </TR>                                            
			<TR>
                <TH class="campoformulario" width="1%" nowrap>Vínculo:</TH>
                <TD class="campoformulario" colspan=<?=$colspanColunas-2?>>
                     <?php
                    include_once("bibliotecaPessoa.php");
                    echo getComboPessoaVinculo(vopessoavinculo::$nmAtrCd, vopessoavinculo::$nmAtrCd, $colecao[vopessoavinculo::$nmAtrCd], "camporeadonly", " disabled ");                    
                    ?>
            </TR>
			<TR>
                <TH class="campoformulario" nowrap width=1%>Nome:</TH>
				<TD class="campoformulario" colspan=<?=$colspanColunas-2?>>
                <INPUT type="text" id="<?=vopessoa::$nmAtrNome?>" name="<?=vopessoa::$nmAtrNome?>"  value="<?php echo($vo->nome);?>"  class="camporeadonly" size="70" readonly></TD>
            </TR>
			<TR>
                <TH class="campoformulario" nowrap width=1%>RG/Órgão.Exp.:</TH>
                <TD class="campoformulario" width="1%"><INPUT type="text" id="<?=vopessoa::$nmAtrDocRG?>" name="<?=vopessoa::$nmAtrDocRG?>"  value="<?php echo($vo->docRG);?>"  class="camporeadonly" size="30" readonly></TD>
                <TH class="campoformulario" width="1%" nowrap>CPF:</TH>
                <TD class="campoformulario" colspan=<?=$colspanColunas-4?>><INPUT type="text" id="<?=vopessoa::$nmAtrDocCPF?>" name="<?=vopessoa::$nmAtrDocCPF?>" value="<?php echo(documentoPessoa::getNumeroDocFormatado($vo->docCPF));?>" class="camporeadonly" size="20" maxlength="18" readonly></TD>
            </TR>
			<TR>
                <TH class="campoformulario" nowrap width=1%>Email:</TH>
                <TD class="campoformulario" width="1%"><INPUT type="text" id="<?=vopessoa::$nmAtrEmail?>" name="<?=vopessoa::$nmAtrEmail?>"  value="<?php echo($email);?>"  class="camporeadonly" size="50" readonly></TD>
	            <TH class="campoformulario" nowrap width="1%">Data.Nascimento:</TH>
	            <TD class="campoformulario" colspan=<?=$colspanColunas-4?>>
	            	<INPUT type="text" 
	            	       id="<?=vopessoa::$nmAtrDtNascimento?>" 
	            	       name="<?=vopessoa::$nmAtrDtNascimento?>" 
	            			value="<?php echo(getData($vo->dtNascimento));?>"
	            			onkeyup="formatarCampoData(this, event, false);" 
	            			onChange ="checkResponsabilidade();"
	            			class="camporeadonly" 
	            			size="10" 
	            			maxlength="10" readonly>
				</TD>
            </TR>
			<TR>
                <TH class="campoformulario" nowrap width=1%>Telefone(s):</TH>
                <TD class="campoformulario" width="1%"><INPUT type="text" id="<?=vopessoa::$nmAtrTel?>" name="<?=vopessoa::$nmAtrTel?>"  value="<?php echo($vo->tel);?>"  class="camporeadonly" size="50" maxlength="100" readonly></TD>
                <TH class="campoformulario" nowrap width=1%>Tel. Wapp:</TH>
                <TD class="campoformulario" colspan=<?=$colspanColunas-4?>><INPUT type="text" id="<?=vopessoa::$nmAtrTelWapp?>" name="<?=vopessoa::$nmAtrTelWapp?>"  value="<?php echo($vo->telWapp);?>"  class="camporeadonly" size="14" maxlength="12" readonly></TD>
            </TR>    
            <?php 
            $comboEstados = new select(dominioEstados::getColecao());
            ?>
			<TR>
                <TH class="campoformulario" nowrap width=1%>Endereço:</TH>
                <TD class="campoformulario" width="1%" colspan=<?=$colspanColunas?>>
                				Rua <textarea rows="1" cols="80" id="<?=vopessoa::$nmAtrEndereco?>" name="<?=vopessoa::$nmAtrEndereco?>" class="camporeadonly" maxlength="300"><?php echo($vo->endereco);?></textarea>
                				<br>
                				Bairro <INPUT type="text" id="<?=vopessoa::$nmAtrBairro?>" name="<?=vopessoa::$nmAtrBairro?>"  value="<?php echo($vo->bairro);?>"  class="camporeadonly" size="30" maxlength="50" readonly>
                				Cidade <INPUT type="text" id="<?=vopessoa::$nmAtrCidade?>" name="<?=vopessoa::$nmAtrCidade?>"  value="<?php echo($vo->cidade);?>"  class="camporeadonly" size="30" maxlength="50" readonly>
                				Estado: <?= $comboEstados->getHtmlCombo(vopessoa::$nmAtrUF, vopessoa::$nmAtrUF, $vo->uf, true, "camporeadonly", false, " disabled ");?>
				</TD>
            </TR>     
			<TR>
                <TH class="campoformulario" nowrap width=1%>Observação:</TH>
                <TD class="campoformulario" width="1%" colspan=<?=$colspanColunas?>>
                				<textarea rows="2" cols="60" id="<?=vopessoa::$nmAtrObservacao?>" name="<?=vopessoa::$nmAtrObservacao?>" class="camporeadonly" maxlength="300" readonly><?php echo($vo->obs);?></textarea>
				<?php 
	            include_once(caminho_util. "dominioSimNao.php");
	            $comboSimNao = new select(dominioSimNao::getColecao());	             
	            echo "&nbsp;Documentação OK?: ";
	            echo $comboSimNao->getHtmlCombo(vopessoa::$nmAtrInDocumentacaoEmDia,vopessoa::$nmAtrInDocumentacaoEmDia, $vo->inDocumentacaoEmdia, true, "campoobrigatorio", false,
	            		" onChange='formataFormDocumentacao();' disabled ");
	            ?>                				                				
				</TD>
            </TR>            
            <?php
            $nmREsponsavel = $vo->responsavel;
            if($nmREsponsavel == null){
            	$nmREsponsavel = "O PRÓPRIO";            	
            }
            ?>              
   			<TR>
                <TH class="campoformulario" nowrap width=1%>Responsável:</TH>
				<TD class="campoformulario" colspan=<?=$colspanColunas?>>
                <INPUT type="text" id="<?=vopessoa::$nmAtrResponsavel?>" name="<?=vopessoa::$nmAtrResponsavel?>"  value="<?php echo($nmREsponsavel);?>"  class="camporeadonly" size="70" readonly>
                </TD>
            </TR>
            
            
        <?php if(!$isInclusao){
        	echo "<TR>" . incluirUsuarioDataHoraDetalhamento($vo,$colspanColunas-2) .  "</TR>";
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
}catch(Exception $ex){
	putObjetoSessao("vo", $vo);
	tratarExcecaoHTML($ex);	
}
?>
