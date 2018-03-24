<?php
include_once("../../config_sistema.php");
include_once(caminho_util."bibliotecaHTML.php");
include_once("biblioteca_htmlCalendario.php");

//inicia os parametros
inicio();

$nmConsulta = "CALENDÁRIO";

$titulo = "CONSULTAR " . $nmConsulta;
setCabecalho($titulo); 
	
$filtro  = new filtroConsultarMontagem(false, true);
$filtro = filtroManter::verificaFiltroSessao($filtro);

$nome = $filtro->descricao;
$cdHistorico = $filtro->cdHistorico;
$cdOrdenacao = $filtro->cdOrdenacao;
$isHistorico = ("S" == $cdHistorico); 

$vo = new voperfilaluno();
$dbprocesso = $vo->dbprocesso;
$colecao = $dbprocesso->consultarTelaConsultaCalendario($filtro);

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
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_text.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_radiobutton.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>tooltip.js"></SCRIPT>

<SCRIPT language="JavaScript" type="text/javascript">

//Transfere dados selecionados para a janela principal
function selecionar() {
	if (!isRadioButtonConsultaSelecionado("document.frm_principal.rdb_consulta"))
		return;
		
	if (window.opener != null) {
		array = retornarValorRadioButtonSelecionadoComoArray("document.frm_principal.rdb_consulta", "*");
		
		cdmateriafonte = array[0];
		dsmateriafonte = array[1];

		window.opener.transferirDadosOrgaomateriafonte(cdmateriafonte, dsmateriafonte);
		window.close();
	}
}

// Verifica se o formulario esta valido para alteracao, exclusao ou detalhamento
function isFormularioValido() {

	if(document.frm_principal.<?=voperfilaluno::$nmAtrCdPerfil?>.value == ""
		&& document.frm_principal.<?=voperfil::$nmAtrDescricao?>.value == ""){
		
		if(!isCampoNumericoPositivoValido(document.frm_principal.<?=voperfilaluno::$nmAtrCdPerfil?>, true)){
			return false;
		}
		
		if(!isCampoAlfaNumericoValido(document.frm_principal.<?=voperfil::$nmAtrDescricao?>, true)){
			return false;
		}

	}
	

	return true;
}

function limparFormulario() {	
	for(i=0;i<frm_principal.length;i++){
		frm_principal.elements[i].value='';
	}	
}

function detalhar(isExcluir) {    
    if(isExcluir == null || !isExcluir)
        funcao = "<?=constantes::$CD_FUNCAO_DETALHAR?>";
    else
        funcao = "<?=constantes::$CD_FUNCAO_EXCLUIR?>";
    
    if (!isRadioButtonConsultaSelecionado("document.frm_principal.rdb_consulta"))
            return;
    	
	chave = document.frm_principal.rdb_consulta.value;	
	lupa = document.frm_principal.lupa.value;
	location.href="detalhar.php?funcao=" + funcao + "&chave=" + chave + "&lupa="+ lupa;
}

function excluir() {
    detalhar(true);
}

function incluir() {
	location.href="manter.php?funcao=<?=constantes::$CD_FUNCAO_INCLUIR?>";
}

function alterar() {
    if (!isRadioButtonConsultaSelecionado("document.frm_principal.rdb_consulta"))
            return;
        
    <?php
    if($isHistorico){
    	echo "exibirMensagem('Registro de historico nao permite alteracao.');return";
    }?>
    
	chave = document.frm_principal.rdb_consulta.value;
	location.href="manter.php?funcao=<?=constantes::$CD_FUNCAO_ALTERAR?>&chave=" + chave;

}

function transferirDadosPessoa(array){	
	cd = array[0];
	ds = array[2];
	document.frm_principal.<?=voperfilaluno::$nmAtrCdAluno?>.value = completarNumeroComZerosEsquerda(cd, <?=TAMANHO_CODIGOS_SAFI?>);
	document.frm_principal.<?=vopessoa::$nmAtrNome?>.value = ds;
}

function transferirDadosPerfil(cdPerfil, dsPerfil){
	document.frm_principal.<?=voperfilaluno::$nmAtrCdPerfil?>.value = completarNumeroComZerosEsquerda(cdPerfil, <?=TAMANHO_CODIGOS_SAFI?>);
	document.frm_principal.<?=voperfil::$nmAtrDescricao?>.value = dsPerfil;
}

function confirmar() {
	if(!isFormularioValido())
		return false;
	
	return true;    
}
</SCRIPT>
</HEAD>
<?=setTituloPagina($nmConsulta)?>
<BODY class="paginadados" onload="">
	  
<FORM name="frm_principal" method="post" action="index.php?consultar=S" onSubmit="return confirmar();">
    
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
        <?php 
        	$lupaPerfil = getLinkPesquisa(caminho_funcoesHTML."perfil");
        ?>
			<TR>
                <TH class="campoformulario" width="1%" nowrap>Perfil:</TH>
                <TD class="campoformulario" >
                	Cd. <INPUT type="text" id="<?=voperfilaluno::$nmAtrCdPerfil?>" name="<?=voperfilaluno::$nmAtrCdPerfil?>"  value="<?php echo(complementarCharAEsquerda($filtro->cdPerfil, "0", TAMANHO_CODIGOS_SAFI));?>"  class="camponaoobrigatorio" size="4" >
					<?=$lupaPerfil?>- Descrição: 
                	<INPUT type="text" id="<?=voperfil::$nmAtrDescricao?>" name="<?=voperfil::$nmAtrDescricao?>"  value="<?php echo($filtro->dsPerfil);?>"  class="camponaoobrigatorio" size="50" >
                </TD>
            </TR>        
			<TR>
                <TH class="campoformulario" width="1%" nowrap>Aluno:</TH>
                <TD class="campoformulario" >
                	Cd. <INPUT type="text" id="<?=voperfilaluno::$nmAtrCdAluno?>" name="<?=voperfilaluno::$nmAtrCdAluno?>"  value="<?php echo(complementarCharAEsquerda($filtro->cdAluno, "0", TAMANHO_CODIGOS_SAFI));?>"  class="camponaoobrigatorio" size="4" >
					- Nome: 
                	<INPUT type="text" id="<?=vopessoa::$nmAtrNome?>" name="<?=vopessoa::$nmAtrNome?>"  value="<?php echo($filtro->nomeAluno);?>"  class="camponaoobrigatorio" size="50" >
                </TD>
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
                    <TH class="headertabeladados" width="1%">x</TH>
                    <TH class="headertabeladados"width="90%" >Matéria</TH>
                    <TH class="headertabeladadosalinhadocentro" width="1%">Num.Caixinhas</TH>
                    <TH class="headertabeladadosalinhadocentro" width="1%">Num.Caixinhas.Ideal</TH>
                </TR>                
                <?php								
                if (!isColecaoVazia($colecao))
                        $tamanho = sizeof($colecao);
                else 
                        $tamanho = 0;			
               
                 $colspan=4;
                 if($isHistorico){
                 	$colspan++;
                 } 
                 
                 $totalCaixinhas = 0;
                 if(!isColecaoVazia($colecao)){
                 	
                 	$totalCaixinhasIdeal = 0;
                 	
                	for ($i=0;$i<$tamanho;$i++) {
	                 	$registro = $colecao[$i];
	                 	$voAtual = new vomateria();
	                 	$voAtual->getDadosBanco($registro);
	                 	
	                 	$numHorasDia = $registro[voperfilaluno::$nmAtrNumHorasDia];
	                 	$numHorasMateriaDia = $registro[voperfilaluno::$nmAtrNumHorasMateriaDia];
	                 	$numDias = $registro[voperfilaluno::$nmAtrNumDiasMeta];
	                 	
	                 	$carga = $registro[voperfilmateria::$nmAtrCarga];
	                 	$numCargaTotalBase = $registro[filtroConsultarMontagem::$nmColNumCargaTotal];
	                 	
	                 	$numCaixinhasTotal = $numHorasDia/$numHorasMateriaDia*$numDias;
	                 	$numCaixinhas = $numCaixinhasTotal*$carga/$numCargaTotalBase;
	                 	$numCaixinhasIdeal = round($numCaixinhas);	                 	
	                 	
	                 	$totalCaixinhasIdeal= $totalCaixinhasIdeal+ $numCaixinhasIdeal;                 
                 ?>
                <TR class="dados">
                    <TD class="tabeladados"><input type="checkbox"></TD>
                    <TD class="tabeladados"><?php echo $colecao[$i][vomateria::$nmAtrDescricao];?></TD>                    
					<TD class="tabeladados"><?php echo "$numCaixinhas";?></TD>
					<TD class="tabeladados"><?php echo "$numCaixinhasIdeal";?></TD>
                </TR>					
                <?php               		               		
					}					
				?>
                <TR>
                    <TD class="totalizadortabeladadosalinhadodireita" colspan=<?=$colspan-2?>>Total:</TD>
                    <TD class="totalizadortabeladadosalinhadodireita" nowrap><?="$numCaixinhasTotal"?></TD>
                    <TD class="totalizadortabeladadosalinhadodireita" nowrap><?="$totalCaixinhasIdeal"?></TD>
                </TR>
				<?php 
				
                }

                if($filtro->TemPaginacao){
                ?>
                <TR>
                    <TD class="tabeladadosalinhadocentro" colspan=<?=$colspan?>><?=$paginacao->criarLinkPaginacaoGET()?></TD>
                </TR>				
                <TR>
                    <TD class="totalizadortabeladadosalinhadodireita" colspan=<?=$colspan?>>Total de registro(s) na página: <?=$i?></TD>
                </TR>
				<?php			
                }
                
                $numTotalRegistros = $tamanho;
                ?>
                
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
	                   		<?//=getBotoesRodape();?>
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
