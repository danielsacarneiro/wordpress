<?php
include_once("../../config_sistema.php");
include_once(caminho_util."bibliotecaHTML.php");

//inicia os parametros
inicio();

$nmConsulta = "MONTAGEM";

$titulo = "CONSULTAR " . $nmConsulta;
setCabecalho($titulo); 
	
$filtro  = new filtroConsultarMontagem();
$filtro = filtroManter::verificaFiltroSessao($filtro);

$nome = $filtro->descricao;
$cdHistorico = $filtro->cdHistorico;
$cdOrdenacao = $filtro->cdOrdenacao;
$isHistorico = ("S" == $cdHistorico); 

$vo = new voperfilaluno();
$dbprocesso = $vo->dbprocesso;
$colecao = $dbprocesso->consultarTelaConsultaMontagem($filtro);

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
		
		cdmateriafonte = array[0];
		dsmateriafonte = array[1];

		window.opener.transferirDadosOrgaomateriafonte(cdmateriafonte, dsmateriafonte);
		window.close();
	}
}

// Verifica se o formulario esta valido para alteracao, exclusao ou detalhamento
function isFormularioValido() {
	if (!isRadioButtonConsultaSelecionado("document.frm_principal.rdb_consulta"))
		return false;		
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
</SCRIPT>
</HEAD>
<?=setTituloPagina($nmConsulta)?>
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
                <TH class="campoformulario" width="1%" nowrap>Perfil:</TH>
                <TD class="campoformulario" >
                	Cd. <INPUT type="text" id="<?=voperfilaluno::$nmAtrCdPerfil?>" name="<?=voperfilaluno::$nmAtrCdPerfil?>"  value="<?php echo(complementarCharAEsquerda($filtro->cdPerfil, "0", TAMANHO_CODIGOS_SAFI));?>"  class="camponaoobrigatorio" size="4" >
					- Descrição: 
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
                  <TH class="headertabeladados" rowspan=2 width="1%">&nbsp;&nbsp;X</TH>
                    <TH class="headertabeladados" rowspan=2 >Perfil</TH>
                    <TH class="headertabeladados" rowspan=2 >Aluno</TH>
                    <TH class="headertabeladados" rowspan=2 >Matéria</TH>
                    <TH class="headertabeladadosalinhadocentro" colspan=2 width="1%">Horas</TH>
                </TR>
                <TR>
                    <TH class="headertabeladados" width="1%">Definidas</TH>
                    <TH class="headertabeladados" width="1%">A.Definir</TH>
                </TR>
                
                <?php								
                if (is_array($colecao))
                        $tamanho = sizeof($colecao);
                else 
                        $tamanho = 0;			
               
                 $colspan=7;
                 if($isHistorico){
                 	$colspan++;
                 }                        
                            
                for ($i=0;$i<$tamanho;$i++) {
                	$registroAtual = $colecao[$i];
                        $voAtual = new voperfilaluno();
                        $voAtual->getDadosBanco($colecao[$i]);
                                                                        
                        $voMateriaAtual = new vomateria();
                        $voMateriaAtual->getDadosBanco($colecao[$i]);
                        
                        $voPerfilMateriaAtual = new voperfilmateria();
                        $voPerfilMateriaAtual->getDadosBanco($colecao[$i]);
                        
                        $dsAluno = complementarCharAEsquerda($voAtual->cdAluno, "0", TAMANHO_CODIGOS_SAFI)
                        . "-"
						. $registroAtual[vopessoa::$nmAtrNome];
                        
						$dsPerfil = complementarCharAEsquerda($voAtual->cdPerfil, "0", TAMANHO_CODIGOS_SAFI)
						. "-"
						. $registroAtual[voperfil::$nmAtrDescricao];
		
				?>
                <TR class="dados">
                    <TD class="tabeladados">
                    <?=getHTMLRadioButtonConsulta("rdb_consulta", "rdb_consulta", $voAtual);?>					
                    </TD>                    
                    <TD class="tabeladados"><?php echo $dsPerfil;?></TD>
                    <TD class="tabeladados"><?php echo $dsAluno;?></TD>
                    <TD class="tabeladados"><?php echo $voMateriaAtual->descricao;?></TD>
                    <TD class="tabeladados"><?php echo complementarCharAEsquerda($voPerfilMateriaAtual->carga, "0", 2);?></TD>
                </TR>					
                <?php
				}				
                ?>
                <TR>
                    <TD class="tabeladadosalinhadocentro" colspan=<?=$colspan?>><?=$paginacao->criarLinkPaginacaoGET()?></TD>
                </TR>				
                <TR>
                    <TD class="totalizadortabeladadosalinhadodireita" colspan=<?=$colspan?>>Total de registro(s) na página: <?=$i?></TD>
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
