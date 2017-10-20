<?php
include_once("../../config_sistema.php");
include_once(caminho_util."bibliotecaHTML.php");
include_once(caminho_util."constantes.class.php");
include_once(caminho_util. "select.php");
include_once(caminho_filtros . "filtroManterPessoa.php");

//inicia os parametros
try{
inicio();

$titulo = "CONSULTAR " . vopessoa::getTituloJSP();
setCabecalho($titulo);
$vo = new vopessoa();

$filtro  = new filtroManterPessoa(true);
$filtro->voPrincipal = $vo;
$filtro = filtroManter::verificaFiltroSessao($filtro);
	
$nome = $filtro->nome;
$doc = $filtro->doc;
$cdHistorico = $filtro->cdHistorico;
$cdOrdenacao = $filtro->cdOrdenacao;
$isHistorico = ("S" == $cdHistorico); 

$dbprocesso = new dbpessoa();
$colecao = $dbprocesso->consultarPessoaManter ( $filtro, true );

$paginacao = $filtro->paginacao;
if($filtro->temValorDefaultSetado){
	;
}

$qtdRegistrosPorPag = $filtro->qtdRegistrosPorPag;
$numTotalRegistros = $filtro->numTotalRegistros;
?>

<!DOCTYPE html>
<HTML>
<HEAD>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_principal.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_datahora.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_cnpfcnpj.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_radiobutton.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_checkbox.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>tooltip.js"></SCRIPT>

<SCRIPT language="JavaScript" type="text/javascript">

// Verifica se o formulario esta valido para alteracao, exclusao ou detalhamento
function isFormularioValido() {
	if (!isRadioButtonConsultaSelecionado("document.frm_principal.rdb_consulta"))
		return false;		
	return true;
}

//Transfere dados selecionados para a janela principal
function selecionar(isMultiplaSelecao) {
	if (!isMultiplaSelecao && !isRadioButtonConsultaSelecionado("document.frm_principal.rdb_consulta"))
		return;
		
	if (window.opener != null) {
		if(!<?=booleanToExtenso(isMultiSelecao())?>){
			array = retornarValorRadioButtonSelecionadoComoArray("document.frm_principal.rdb_consulta", "<?=CAMPO_SEPARADOR?>");
			array = [array[0]];
		}else{
			array = retornarValoresCheckBoxesSelecionadosComoArray("document.frm_principal.rdb_consulta");
		}

		cdPessoa = array;

		//alert(cdPessoa);
		
		window.opener.transferirDadosPessoa(cdPessoa);
		window.close();
	}
}

<?=getFuncoesJSGenericas("document.frm_principal.rdb_consulta", $isHistorico);?>

</SCRIPT>
</HEAD>
<?=setTituloPagina($vo->getTituloJSP())?>
<BODY class="paginadados" onload="">
	  
<FORM name="frm_principal" method="post" action="index.php?consultar=S">

<INPUT type="hidden" name="utilizarSessao" value="N">
<INPUT type="hidden" id="numTotalRegistros" value="<?=$numTotalRegistros?>">
<INPUT type="hidden" name="consultar" id="consultar" value="N">    

<TABLE id="table_conteiner" class="conteiner" cellpadding="0" cellspacing="0">
    <TBODY>
		<TR>
		<TD class="conteinerfiltro">
        <?=cabecalho?>
		</TD>
		</TR>
<TR>
    <TD class="conteinerfiltro">
    <DIV id="div_filtro" class="div_filtro">
    <TABLE id="table_filtro" class="filtro" cellpadding="0" cellspacing="0">
        <TBODY>
			<TR>
                <TH class="campoformulario" nowrap>Código:</TH>
                <TD class="campoformulario" width="1%"><INPUT type="text" id="<?=vopessoa::$nmAtrCd?>" name="<?=vopessoa::$nmAtrCd?>"  value="<?php if($filtro->cd != null) echo complementarCharAEsquerda($filtro->cd, "0", TAMANHO_CODIGOS);?>"  class="camponaoobrigatorio" size="7" ></TD>
                <TH class="campoformulario" nowrap width="1%">Nome:</TH>
                <TD class="campoformulario" ><INPUT type="text" id="<?=vopessoa::$nmAtrNome?>" name="<?=vopessoa::$nmAtrNome?>"  value="<?php echo($nome);?>"  class="camponaoobrigatorio" size="50" ></TD>
            </TR>            
            <TR>
                <TH class="campoformulario" width="1%" nowrap>CNPJ/CPF:</TH>
                <TD class="campoformulario" ><INPUT type="text" id="<?=vopessoa::$nmAtrDocCPF?>" name="<?=vopessoa::$nmAtrDocCPF?>" onkeyup="formatarCampoCNPFouCNPJ(this, event);" value="<?php echo($doc);?>" class="camponaoobrigatorio" size="20" maxlength="18"></TD>
                <TH class="campoformulario" nowrap>Vínculo:</TH>
                <TD class="campoformulario" colspan="1">
                     <?php
                    include_once("bibliotecaPessoa.php");
                    echo getComboPessoaVinculo(vopessoavinculo::$nmAtrCd, vopessoavinculo::$nmAtrCd, $filtro->cdvinculo, "camponaoobrigatorio", "");                                        
                    ?>
            </TR>
       <?php
       echo getComponenteConsultaFiltro($vo->temTabHistorico, $filtro);
        ?>
       </TBODY>
  </TABLE>
		</DIV>
  </TD>
</TR>
<TR>
       <TD class="conteinertabeladados">
        <DIV id="div_tabeladados" class="tabeladados">
         <TABLE id="table_tabeladados" class="tabeladados" cellpadding="0" cellspacing="0">						
             <TBODY>
                <TR>
                  <TH class="headertabeladados" width="1%"><?=getXGridConsulta("rdb_consulta", isMultiSelecao())?></TH>
                  <?php 
                  if($isHistorico){
                  	?>
                  	<TH class="headertabeladados" width="1%">Sq.Hist</TH>
                  <?php 
                  }
                  ?>                  
                    <TH class="headertabeladados" width="1%">Código</TH>
                    <TH class="headertabeladados">Nome</TH>
                    <TH class="headertabeladados">Doc.</TH>
                    <TH class="headertabeladados">Vínculo</TH>
                    <TH class="headertabeladados" width="1%">Email</TH>
                    <TH class="headertabeladados" width="10%">Telefone</TH>
                    <TH class="headertabeladados" width="1%">Doc.OK</TH>
                </TR>
                <?php								
                if (is_array($colecao))
                        $tamanho = sizeof($colecao);
                else 
                        $tamanho = 0;
                
				$colspan=5;
				if($isHistorico){
					$colspan++;
				}
                				
                $domVinculo = new dominioVinculoPessoa();
                for ($i=0;$i<$tamanho;$i++) {
                        $voAtual = new vopessoa();
                        $voAtual->getDadosBanco($colecao[$i]);
                                                                
                        $vinculo = $colecao[$i][vopessoavinculo::$nmAtrCd];
                        $vinculo = $domVinculo->getDescricao($vinculo);
                                               
                        $docFormatado = documentoPessoa::getNumeroDocFormatado($voAtual->docCPF);
                        
                        $classColuna = "tabeladados";
                        
                        if($voAtual->inDocumentacaoEmdia == constantes::$CD_NAO)
                        		$classColuna = "tabeladadosdestacadovermelho";                        
                        
                ?>
                <TR class="dados">
                    <TD class="tabeladados">
                    <?=
                    getHTMLGridConsulta("rdb_consulta", "rdb_consulta", $voAtual, isMultiSelecao());
                    ?>					
                    </TD>
                  <?php                  
                  if($isHistorico){                  	
                  	?>
                  	<TD class="tabeladados"><?php echo complementarCharAEsquerda($colecao[$i][$voAtual::$nmAtrSqHist], "0", TAMANHO_CODIGOS);?></TD>
                  <?php 
                  }
                  ?>                    
                    <TD class="tabeladados"><?php echo complementarCharAEsquerda($colecao[$i][vopessoa::$nmAtrCd], "0", TAMANHO_CODIGOS);?></TD>
                    <TD class="tabeladados"><?php echo $colecao[$i][vopessoa::$nmAtrNome];?></TD>
                    <TD class="tabeladados"><?php echo $docFormatado;?></TD>
                    <TD class="tabeladados"><?php echo $vinculo;?></TD>
                    <TD class="tabeladados"><?php echo $colecao[$i][vopessoa::$nmAtrEmail];?></TD>
                    <TD class="tabeladados"><?php echo $colecao[$i][vopessoa::$nmAtrTel]?></TD>
                    <TD class="<?=$classColuna?>"><?php echo dominioSimNao::getDescricaoStatic($voAtual->inDocumentacaoEmdia)?></TD>
                </TR>					
                <?php
				}				
                ?>
                <TR>
                    <TD class="tabeladadosalinhadocentro" colspan=12><?=$paginacao->criarLinkPaginacaoGET()?></TD>
                </TR>				
                <TR>
                    <TD class="totalizadortabeladadosalinhadodireita" colspan=12>Total de registro(s) na página: <?=$i?></TD>
                </TR>
                <TR>
                    <TD class="totalizadortabeladadosalinhadodireita" colspan=12>Total de registro(s): <?=$numTotalRegistros?></TD>
                </TR>				
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
