<?php
include_once("../../config_sistema.php");
include_once(caminho_util."bibliotecaHTML.php");

try{
//inicia os parametros
inicio();

$vo = new vometafonte();
$vo->getVOExplodeChave();

$readonly = "";
$nmFuncao = "";
$readonly = "readonly";

$dbprocesso = $vo->dbprocesso;
$colecao = $dbprocesso->consultarPorChave($vo, $isHistorico);
$vo->getDadosBanco($colecao);

$vomateria = new vomateria();
$vomateria->getDadosBanco($colecao);

$voperfil = new voperfil();
$voperfil->getDadosBanco($colecao);

$vofonte = new vomateriafonte();
$vofonte->getDadosBanco($colecao);

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
	        <?php
        	$classChave = constantes::$CD_CLASS_CAMPO_READONLY;	        
	        ?>
			<TR>
                <TH class="campoformulario" width="1%" nowrap>Meta:</TH>
                <TD class="campoformulario" colspan=3>
                	<?=getInputText(vometafonte::$nmAtrCdMeta, vometafonte::$nmAtrCdMeta, complementarCharAEsquerda($vo->cdMeta, "0", TAMANHO_CODIGOS_SAFI), $classChave, $size, null, "onKeyUp='validarCampoNumericoPositivo(this);'")?>
                </TD>
            </TR>        
			<TR>
                <TH class="campoformulario" width="1%" nowrap>Perfil:</TH>
                <TD class="campoformulario" colspan=3>
                	Cd. <?=getInputText(vometafonte::$nmAtrCdPerfil, vometafonte::$nmAtrCdPerfil, complementarCharAEsquerda($vo->cdPerfil, "0", TAMANHO_CODIGOS_SAFI), $classChave, $size) . " $lupaPerfil"?>
					- Descrição: <?=getInputText(voperfil::$nmAtrDescricao, voperfil::$nmAtrDescricao, $voperfil->descricao, constantes::$CD_CLASS_CAMPO_READONLY)?> 
                </TD>
            </TR>        
			<TR>
                <TH class="campoformulario" width="1%" nowrap>Matéria:</TH>
                <TD class="campoformulario" colspan=3>
                	Cd. <?=getInputText(vometafonte::$nmAtrCdMateria, vometafonte::$nmAtrCdMateria, complementarCharAEsquerda($vo->cdMateria, "0", TAMANHO_CODIGOS_SAFI), $classChave, $size, null, $jsMateria) . " $lupaMateria"?>
					- Descrição: <?=getInputText(vomateria::$nmAtrDescricao, vomateria::$nmAtrDescricao, $vomateria->descricao, constantes::$CD_CLASS_CAMPO_READONLY)?> 
                </TD>
            </TR>
			<TR>
                <TH class="campoformulario" nowrap width=1%>Sequencial:</TH>
                <TD class="campoformulario" colspan=3>
               	<?=getInputText(vometafonte::$nmAtrSq, vometafonte::$nmAtrSq, complementarCharAEsquerda($vo->sq, "0", TAMANHO_CODIGOS_SAFI), constantes::$CD_CLASS_CAMPO_READONLY)?>
            </TR>
            <TR>
				<TH class="textoseparadorgrupocampos" halign="left" colspan="4">&nbsp;
				</TH>
			</TR>	        
			<?php 
			$selectTpfonte = new select(dominioTpFonte::getColecao());
			?>
			<TR>
                <TH class="campoformulario" width="1%" nowrap>Tp.Fonte:</TH>
                <TD class="campoformulario" colspan=3>
                	<?=$selectTpfonte->getHtmlCombo(vometafonte::$nmAtrTpFonte, vometafonte::$nmAtrTpFonte, $vo->tpFonte, true, constantes::$CD_CLASS_CAMPO_READONLY, true, "");?>					 
                </TD>
            </TR>
			<?php 
			if($vo->cdFonte != null){
			?>            
			<TR>
                <TH class="campoformulario" width="1%" nowrap>Fonte:</TH>
                <TD class="campoformulario" colspan=3>
                	Cd. <?=getInputText(vometafonte::$nmAtrCdFonte, vometafonte::$nmAtrCdFonte, complementarCharAEsquerda($vo->cdFonte, "0", TAMANHO_CODIGOS_SAFI), constantes::$CD_CLASS_CAMPO_READONLY, TAMANHO_CODIGOS_SAFI) . " $lupaFonte"?>
					- Descrição: <?=getInputText(vomateriafonte::$nmAtrDescricao, vomateriafonte::$nmAtrDescricao, $vofonte->descricao, constantes::$CD_CLASS_CAMPO_READONLY)?> 
                </TD>
            </TR>
			<?php 
				}
			?>            
			<TR>
                <TH class="campoformulario" width="1%" nowrap>Intervalo:</TH>
                <TD class="campoformulario" colspan=3>
                	<?=getInputText(vometafonte::$nmAtrNumParamInicio, vometafonte::$nmAtrNumParamInicio, $vo->numParamInicio, constantes::$CD_CLASS_CAMPO_READONLY, TAMANHO_CODIGOS_SAFI)?>
					a <?=getInputText(vometafonte::$nmAtrNumParamFim, vometafonte::$nmAtrNumParamFim, $vo->numParamFim, constantes::$CD_CLASS_CAMPO_READONLY, TAMANHO_CODIGOS_SAFI)?>
					&nbsp;<?=getInputText(vometafonte::$ID_REQ_DsTpParam, vometafonte::$ID_REQ_DsTpParam, dominioTpParametroFonte::getDescricaoStatic($vo->tpParam), constantes::$CD_CLASS_CAMPO_READONLY)?>				 
                </TD>
            </TR>
			<TR>
                <TH class="campoformulario" width="1%" nowrap>Tempo:</TH>
                <TD class="campoformulario" colspan=3>
                	<?=getInputText(vometafonte::$nmAtrNumHoras, vometafonte::$nmAtrNumHoras, $vo->numHoras, constantes::$CD_CLASS_CAMPO_READONLY, TAMANHO_CODIGOS_SAFI, null, "onKeyUp='validarCampoNumericoPositivo(this);'")?> (horas)
                </TD>
            </TR>
            
			<TR>
                <TH class="campoformulario" nowrap width=1%>Observação:</TH>
                <TD class="campoformulario" colspan=3>
                	<textarea rows="2" cols="60" id="<?=vometafonte::$nmAtrObs?>" name="<?=vometafonte::$nmAtrObs?>" class="<?=constantes::$CD_CLASS_CAMPO_READONLY?>" maxlength="300" readonly><?php echo($vo->obs);?></textarea>
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
