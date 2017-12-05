<?php
include_once("../../config_sistema.php");
include_once(caminho_util."bibliotecaHTML.php");

try{
//inicia os parametros
inicioComValidacaoUsuario(true);

$vo = new voperfilaluno();
//var_dump($vo->varAtributos);

$funcao = @$_GET["funcao"];

$readonly = "";
$isInclusao = $funcao == constantes::$CD_FUNCAO_INCLUIR;

$nmFuncao = "";
if($isInclusao){    
	$nmFuncao = "INCLUIR ";	
}else{
    $readonly = "readonly";
	$vo->getVOExplodeChave();
		
	$dbprocesso = $vo->dbprocesso;					
	$colecao = $dbprocesso->consultarPorChave($vo, $isHistorico);	
	$vo->getDadosBanco($colecao);
	
	$voaluno = new vopessoa();
	$voaluno->getDadosBanco($colecao);

	$voperfil = new voperfil();
	$voperfil->getDadosBanco($colecao);
	
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
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_text.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_datahora.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_select.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>mensagens_globais.js"></SCRIPT>

<SCRIPT language="JavaScript" type="text/javascript">
// Verifica se o formulario esta valido para alteracao, exclusao ou detalhamento
function isFormularioValido() {	
	campoCdPerfil = document.frm_principal.<?=voperfilaluno::$nmAtrCdPerfil?>;
	if(!isCampoNumericoValido(campoCdPerfil, true, 1, null, null, true)){
		exibirMensagem("Selecione o perfil.");
		return false;
	}

	campoCdAluno = document.frm_principal.<?=voperfilaluno::$nmAtrCdAluno?>;
	if(!isCampoNumericoValido(campoCdAluno, true, 1, null, null, true)){
		exibirMensagem("Selecione o Aluno.");
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

function validaFormTpMeta(){	
	/*colecaoTpFonteAutonoma =<?=$varColecaoGlobaTpFonteAutonoma?>;
	colecaoTpParametroFonte=<?=$varColecaoGlobalTpParametroFonte?>;
	colecaoTpParametroFontePorTpFonte=<?=$varColecaoGlobalTpParametroFontePorTpFonte?>;

	cd = document.frm_principal.<?=vometafonte::$nmAtrTpFonte?>.value;
	cdTpParametro = colecaoTpParametroFontePorTpFonte[cd];
	if(cdTpParametro == null){
		habilitarElementoMais('<?=vometafonte::$nmAtrNumParamInicio?>', false, true);
		habilitarElementoMais('<?=vometafonte::$nmAtrNumParamFim?>', false, true);
		limparCampo(document.frm_principal.<?=vometafonte::$ID_REQ_DsTpParam?>);
		
	}else{
		dsTpParametroFonte = colecaoTpParametroFonte[cdTpParametro];
		document.frm_principal.<?=vometafonte::$ID_REQ_DsTpParam?>.value = dsTpParametroFonte;
		habilitarElementoMais('<?=vometafonte::$nmAtrNumParamInicio?>', true, true);
		habilitarElementoMais('<?=vometafonte::$nmAtrNumParamFim?>', true, true);		
	}

	cdTpFonteAutonoma = colecaoTpFonteAutonoma[cd];
	if(cdTpFonteAutonoma == null){
		habilitarElementoMais('<?=vometafonte::$nmAtrCdFonte?>', true, true);		
	}else{
		limparCampo(document.frm_principal.<?=vometafonte::$nmAtrCdFonte?>);
		habilitarElementoMais('<?=vometafonte::$nmAtrCdFonte?>', false, true);
	}*/
	
}

function iniciar(){

	validaFormTpFonte();
	
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
	        if(!$isInclusao){
	        	$class = constantes::$CD_CLASS_CAMPO_READONLY;
	        }else{
	        	$class = constantes::$CD_CLASS_CAMPO_OBRIGATORIO;
	        	$size = TAMANHO_CODIGOS_SAFI;
	        	$lupaPerfil = getLinkPesquisa(caminho_funcoesHTML."perfil");
	        	$lupaAluno = getLinkPesquisa(caminho_funcoesHTML.vopessoa::getNmTabela());
	        }
	        ?>
			<TR>
                <TH class="campoformulario" width="1%" nowrap>Perfil:</TH>
                <TD class="campoformulario" colspan=3>
                	Cd. <?=getInputText(voperfilaluno::$nmAtrCdPerfil, voperfilaluno::$nmAtrCdPerfil, complementarCharAEsquerda($vo->cdPerfil, "0", TAMANHO_CODIGOS_SAFI), $class, $size) . " $lupaPerfil"?>
					- Descrição: <?=getInputText(voperfil::$nmAtrDescricao, voperfil::$nmAtrDescricao, $voperfil->descricao, constantes::$CD_CLASS_CAMPO_READONLY)?> 
                </TD>
            </TR>        
			<TR>
                <TH class="campoformulario" width="1%" nowrap>Aluno:</TH>
                <TD class="campoformulario" colspan=3>
                	Cd. <?=getInputText(voperfilaluno::$nmAtrCdAluno, voperfilaluno::$nmAtrCdAluno, complementarCharAEsquerda($vo->cdAluno, "0", TAMANHO_CODIGOS_SAFI), $class, $size) . " $lupaAluno"?>
					- Descrição: <?=getInputText(vopessoa::$nmAtrNome, vopessoa::$nmAtrNome, $voaluno->nome, constantes::$CD_CLASS_CAMPO_READONLY)?> 
                </TD>
            </TR>
            <TR>
				<TH class="textoseparadorgrupocampos" halign="left" colspan="4">&nbsp;
				</TH>
			</TR> 
			<?php 
			$selectTpMeta= new select(dominioTpMetaAluno::getColecao());
			?>
			<TR>
                <TH class="campoformulario" width="1%" nowrap>Meta:</TH>
                <TD class="campoformulario" colspan=3>
                	<?=$selectTpMeta->getHtmlCombo(voperfilaluno::$nmAtrTpMeta, voperfilaluno::$nmAtrTpMeta, $vo->tpMeta, true, constantes::$CD_CLASS_CAMPO_OBRIGATORIO, true, "onChange='validaFormTpMeta();'");?>					 
                </TD>
            </TR>			
			<TR>
                <TH class="campoformulario" width="1%" nowrap>Qtd.Dias/Meta:</TH>
                <TD class="campoformulario" colspan=3>
                	<?=getInputText(voperfilaluno::$nmAtrNumDiasMeta, voperfilaluno::$nmAtrNumDiasMeta, $vo->numDiasMeta, constantes::$CD_CLASS_CAMPO_OBRIGATORIO, TAMANHO_CODIGOS_SAFI, null, "onKeyUp='validarCampoNumericoPositivo(this);'")?>
                	(dias a estudar na meta)
                </TD>
            </TR>			           
			<TR>
                <TH class="campoformulario" width="1%" nowrap>Qtd.Horas/Matéria:</TH>
                <TD class="campoformulario" colspan=3>
                	<?=getInputText(voperfilaluno::$nmAtrNumHorasMateriaDia, voperfilaluno::$nmAtrNumHorasMateriaDia, $vo->numHorasMateriaDia, constantes::$CD_CLASS_CAMPO_OBRIGATORIO, TAMANHO_CODIGOS_SAFI, null, "onKeyUp='validarCampoNumericoPositivo(this);'")?>
                	(horas por cada matéria no dia de estudo)
                </TD>
            </TR>			           
			<TR>
	            <TH class="campoformulario" nowrap width="1%">Dt.Início:</TH>
	            <TD class="campoformulario" colspan=3>
	            	<INPUT type="text" 
	            	       id="<?=voperfilaluno::$nmAtrDtInicio?>" 
	            	       name="<?=voperfilaluno::$nmAtrDtInicio?>" 
	            			value="<?php echo(getData($vo->dtInicio));?>"
	            			onkeyup="formatarCampoData(this, event, false);"
	            			class="camponaoobrigatorio" 
	            			size="10" 
	            			maxlength="10" required>
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
	tratarExcecaoHTML ( $ex, null, temSistemaInterno());
}
?>
