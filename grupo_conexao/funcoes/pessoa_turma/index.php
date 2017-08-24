<?php
include_once("../../config_lib.php");
include_once(caminho_util."bibliotecaHTML.php");
include_once(caminho_filtros . "filtroManterPessoaTurma.php");

try{
//inicia os parametros
inicio();

$titulo = "CONSULTAR " . vopessoaturma::getTituloJSP();
setCabecalho($titulo);
	
$filtro  = new filtroManterPessoaTurma(true);
$filtro = filtroManter::verificaFiltroSessao($filtro);

$nome = $filtro->descricao;
$cdHistorico = $filtro->cdHistorico;
$cdOrdenacao = $filtro->cdOrdenacao;
$isHistorico = ("S" == $cdHistorico); 

$vo = new vopessoaturma();
$dbprocesso = $vo->dbprocesso;
$colecao = $dbprocesso->consultarFiltroManterPessoaTurma($filtro);

if($filtro->temValorDefaultSetado){
	;
}

$paginacao = $filtro->paginacao;
$qtdRegistrosPorPag = $filtro->qtdRegistrosPorPag;
$numTotalRegistros = $filtro->numTotalRegistros;

?>

<!DOCTYPE html>
<HTML>
<HEAD>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_principal.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_radiobutton.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>tooltip.js"></SCRIPT>

<SCRIPT language="JavaScript" type="text/javascript">

//Transfere dados selecionados para a janela principal
function selecionar() {
	if (!isRadioButtonConsultaSelecionado("document.frm_principal.rdb_consulta"))
		return;
		
	if (window.opener != null) {
		array = retornarValorRadioButtonSelecionadoComoArray("document.frm_principal.rdb_consulta", "*");
		
		cdTurma = array[0];
		dsTurma = array[1];

		window.opener.transferirDadosOrgaoTurma(cdTurma, dsTurma);
		window.close();
	}
}

// Verifica se o formulario esta valido para alteracao, exclusao ou detalhamento
function isFormularioValido() {
	if (!isRadioButtonConsultaSelecionado("document.frm_principal.rdb_consulta"))
		return false;		
	return true;
}

<?=getFuncoesJSGenericas("document.frm_principal.rdb_consulta", $isHistorico);?>
</SCRIPT>
</HEAD>
<?=setTituloPagina($vo->getTituloJSP())?>
<BODY class="paginadados" onload="">
	  
<FORM name="frm_principal" method="post" action="index.php?consultar=S">
    
<INPUT type="hidden" name="utilizarSessao" value="N">
<INPUT type="hidden" name="numTotalRegistros" id="numTotalRegistros" value="<?=$numTotalRegistros?>">
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
                <TH class="campoformulario" width="1%" nowrap>Cd.Turma:</TH>
                <TD class="campoformulario"  width="1%">
                	<INPUT type="text" id="<?=vopessoaturma::$nmAtrCdTurma?>" name="<?=vopessoaturma::$nmAtrCdTurma?>"  value="<?php echo(complementarCharAEsquerda($filtro->cdTurma, "0", 5));?>"  class="camponaoobrigatorio" size="6" ></TD>                
                <TH class="campoformulario" nowrap>Descri��o:</TH>
                <TD class="campoformulario">
                	<INPUT type="text" id="<?=voturma::$nmAtrDescricao?>" name="<?=voturma::$nmAtrDescricao?>"  value="<?php echo($filtro->dsTurma);?>"  class="camponaoobrigatorio" size="50" >
                </TD>
            </TR>
			<TR>
				<TH class="campoformulario" width="1%" nowrap>Cd.Pessoa:</TH>
                <TD class="campoformulario"  width="1%">
					<INPUT type="text" id="<?=vopessoaturma::$nmAtrCdPessoa?>" name="<?=vopessoaturma::$nmAtrCdPessoa?>"  value="<?php echo(complementarCharAEsquerda($filtro->cdPessoa, "0", 5));?>"  class="camponaoobrigatorio" size="6" ></TD>                
                <TH class="campoformulario" width="1%" nowrap>Nome:</TH>
                <TD class="campoformulario">
					<INPUT type="text" id="<?=vopessoa::$nmAtrNome?>" name="<?=vopessoa::$nmAtrNome?>"  value="<?php echo($filtro->nome);?>"  class="camponaoobrigatorio" size="50" ></TD>
            </TR>
        <?PHP echo getComponenteConsultaFiltro($vo->temTabHistorico, $filtro);?>
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
                  <TH class="headertabeladados" width="1%">&nbsp;&nbsp;X</TH>
                    <TH class="headertabeladados" width="1%">Cd.Pessoa</TH>
                    <TH class="headertabeladados" width="60%">Nome</TH>
                    <TH class="headertabeladados" width="1%">Cd.Turma</TH>
                    <TH class="headertabeladados" width="35%">Descri��o</TH>
                    <TH class="headertabeladados" width="1%">Valor</TH>
                </TR>
                <?php								
                if (is_array($colecao))
                        $tamanho = sizeof($colecao);
                else 
                        $tamanho = 0;			
               
                 $colspan=6;
                 if($isHistorico){
                 	$colspan++;
                 }                        
                            
                for ($i=0;$i<$tamanho;$i++) {
                        $voAtual = new vopessoaturma();
                        $voAtual->getDadosBanco($colecao[$i]);                                                            
                ?>
                <TR class="dados">
                    <TD class="tabeladados">
                    <?=getHTMLRadioButtonConsulta("rdb_consulta", "rdb_consulta", $voAtual);?>					
                    </TD>
                    <TD class="tabeladados"><?php echo $voAtual->getCodigoFormatado($voAtual->cdPessoa);?></TD>
                    <TD class="tabeladados"><?php echo $colecao[$i][vopessoa::$nmAtrNome];?></TD>
                    <TD class="tabeladados"><?php echo $voAtual->getCodigoFormatado($voAtual->cdTurma);?></TD>
                    <TD class="tabeladados"><?php echo $colecao[$i][voturma::$nmAtrDescricao];?></TD>
                    <TD class="tabeladadosalinhadodireita"><?php echo getMoeda($voAtual->valor);?></TD>                    
                </TR>					
                <?php
				}				
                ?>
                <TR>
                    <TD class="tabeladadosalinhadocentro" colspan=<?=$colspan?>><?=$paginacao->criarLinkPaginacaoGET()?></TD>
                </TR>				
                <TR>
                    <TD class="totalizadortabeladadosalinhadodireita" colspan=<?=$colspan?>>Total de registro(s) na p�gina: <?=$i?></TD>
                </TR>
                <TR>
                    <TD class="totalizadortabeladadosalinhadodireita" colspan=<?=$colspan?>>Total de registro(s): <?=$numTotalRegistros?></TD>
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
	                   		<?php 
	                   		
	                   		$arrayBotoesARemover = array (
	                   				constantes::$CD_FUNCAO_INCLUIR
	                   		);
	                   		echo getBotoesRodapeComRestricao ( $arrayBotoesARemover, true );	                   		
	                   			                   		
	                   		?>
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
