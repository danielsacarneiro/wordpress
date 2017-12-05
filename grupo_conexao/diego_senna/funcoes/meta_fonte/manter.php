<?php
include_once("../../config_sistema.php");
include_once(caminho_util."bibliotecaHTML.php");

try{
//inicia os parametros
inicioComValidacaoUsuario(true);

$vo = new vometafonte();
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
	
	$vomateria = new vomateria();
	$vomateria->getDadosBanco($colecao);

	$voperfil = new voperfil();
	$voperfil->getDadosBanco($colecao);
	
	$vofonte = new vomateriafonte();
	$vofonte->getDadosBanco($colecao);
	
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
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>mensagens_globais.js"></SCRIPT>

<SCRIPT language="JavaScript" type="text/javascript">
// Verifica se o formulario esta valido para alteracao, exclusao ou detalhamento
function isFormularioValido() {	
	campoCdPerfil = document.frm_principal.<?=vometafonte::$nmAtrCdPerfil?>;
	if(!isCampoNumericoValido(campoCdPerfil, true, 1, null, null, true)){
		exibirMensagem("Selecione o perfil.");
		return false;
	}

	campoCdMateria = document.frm_principal.<?=vometafonte::$nmAtrCdMateria?>;
	if(!isCampoNumericoValido(campoCdMateria, true, 1, null, null, true)){
		exibirMensagem("Selecione a matéria.");
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

function transferirDadosMateria(cdMateria, dsMateria){
	document.frm_principal.<?=vometafonte::$nmAtrCdMateria?>.value = completarNumeroComZerosEsquerda(cdMateria, <?=TAMANHO_CODIGOS_SAFI?>);
	document.frm_principal.<?=vomateria::$nmAtrDescricao?>.value = dsMateria;
}

function transferirDadosPerfil(cdPerfil, dsPerfil){
	document.frm_principal.<?=vometafonte::$nmAtrCdPerfil?>.value = completarNumeroComZerosEsquerda(cdPerfil, <?=TAMANHO_CODIGOS_SAFI?>);
	document.frm_principal.<?=voperfil::$nmAtrDescricao?>.value = dsPerfil;
}
function transferirDadosMateriaFonte(cdMateria, cdFonte, ds){
	cdMateriaChave = document.frm_principal.<?=vometafonte::$nmAtrCdMateria?>.value;
	//alert(cdMateriaChave + " " + cdMateria);
	if(eval(cdMateriaChave) != eval(cdMateria)){
		exibirMensagem("A fonte escolhida deve ser da matéria selecionada.");
		return;
	}

	document.frm_principal.<?=vometafonte::$nmAtrCdFonte?>.value = completarNumeroComZerosEsquerda(cdFonte, <?=TAMANHO_CODIGOS_SAFI?>);
	document.frm_principal.<?=vomateriafonte::$nmAtrDescricao?>.value = ds;
}

<?php
$varColecaoGlobalTpParametroFonte = "_globalColecaoTpParametroFonte";
echo getColecaoComoVariavelJS(dominioTpParametroFonte::getColecao(), $varColecaoGlobalTpParametroFonte);

$varColecaoGlobalTpParametroFontePorTpFonte = "_globalColecaoTpParametroFontePorTpFonte";
echo getColecaoComoVariavelJS(dominioTpFonte::getColecaoPorTpParametro(), $varColecaoGlobalTpParametroFontePorTpFonte);

$varColecaoGlobaTpFonteAutonoma = "_globalColecaoTpFonteAutonoma";
echo getColecaoComoVariavelJS(dominioTpFonte::getColecaoFonteAutonoma(), $varColecaoGlobaTpFonteAutonoma);
?>

function validaFormTpFonte(){	
	colecaoTpFonteAutonoma =<?=$varColecaoGlobaTpFonteAutonoma?>;
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
	}
	
}

function iniciar(){

	validaFormTpFonte();
	
}

</SCRIPT>
</HEAD>
<?=setTituloPagina($vo->getTituloJSP())?>
<BODY class="paginadados" onload="iniciar();">
	  
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
	        $lupaPerfil = getLinkPesquisa(caminho_funcoesHTML."perfil");
	        $lupaMateria = getLinkPesquisa(caminho_funcoesHTML."materia");
	        $jsMateria = " onChange=limparCampo(document.frm_principal.".vometafonte::$nmAtrCdFonte."); ";
	        $validacaoLupaFonte = "!document.frm_principal.". vometafonte::$nmAtrCdFonte . ".disabled";
	        $lupaFonte = getLinkPesquisa(caminho_funcoesHTML.vomateriafonte::getNmTabela(), $validacaoLupaFonte);
	        
	        if(!$isInclusao){
	        	$classChave = constantes::$CD_CLASS_CAMPO_READONLY;
	        	$lupaPerfil = "";
	        	$lupaMateria = "";	        	
	        	
	        }else{
	        	$classChave= constantes::$CD_CLASS_CAMPO_OBRIGATORIO;
	        	$size = TAMANHO_CODIGOS_SAFI;
	        }	        
	        
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
	        <?php 
	        if(!$isInclusao){
	        ?>
			<TR>
                <TH class="campoformulario" nowrap width=1%>Sequencial:</TH>
                <TD class="campoformulario" colspan=3>
               	<?=getInputText(vometafonte::$nmAtrSq, vometafonte::$nmAtrSq, complementarCharAEsquerda($vo->sq, "0", TAMANHO_CODIGOS_SAFI), constantes::$CD_CLASS_CAMPO_READONLY)?>
            </TR>
	        <?php 
	        }
	        ?>
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
                	<?=$selectTpfonte->getHtmlCombo(vometafonte::$nmAtrTpFonte, vometafonte::$nmAtrTpFonte, $vo->tpFonte, true, constantes::$CD_CLASS_CAMPO_OBRIGATORIO, true, "onChange='validaFormTpFonte();'");?>					 
                </TD>
            </TR>
			<TR>
                <TH class="campoformulario" width="1%" nowrap>Fonte:</TH>
                <TD class="campoformulario" colspan=3>
                	Cd. <?=getInputText(vometafonte::$nmAtrCdFonte, vometafonte::$nmAtrCdFonte, complementarCharAEsquerda($vo->cdFonte, "0", TAMANHO_CODIGOS_SAFI), constantes::$CD_CLASS_CAMPO_OBRIGATORIO, TAMANHO_CODIGOS_SAFI) . " $lupaFonte"?>
					- Descrição: <?=getInputText(vomateriafonte::$nmAtrDescricao, vomateriafonte::$nmAtrDescricao, $vofonte->descricao, constantes::$CD_CLASS_CAMPO_READONLY)?> 
                </TD>
            </TR>
			<TR>
                <TH class="campoformulario" width="1%" nowrap>Intervalo:</TH>
                <TD class="campoformulario" colspan=3>
                	<?=getInputText(vometafonte::$nmAtrNumParamInicio, vometafonte::$nmAtrNumParamInicio, $vo->numParamInicio, constantes::$CD_CLASS_CAMPO_OBRIGATORIO, TAMANHO_CODIGOS_SAFI)?>
					a <?=getInputText(vometafonte::$nmAtrNumParamFim, vometafonte::$nmAtrNumParamFim, $vo->numParamFim, constantes::$CD_CLASS_CAMPO_OBRIGATORIO, TAMANHO_CODIGOS_SAFI)?>
					&nbsp;<?=getInputText(vometafonte::$ID_REQ_DsTpParam, vometafonte::$ID_REQ_DsTpParam, dominioTpParametroFonte::getDescricaoStatic($vo->tpParam), constantes::$CD_CLASS_CAMPO_READONLY)?>				 
                </TD>
            </TR>
			<TR>
                <TH class="campoformulario" width="1%" nowrap>Tempo:</TH>
                <TD class="campoformulario" colspan=3>
                	<?=getInputText(vometafonte::$nmAtrNumHoras, vometafonte::$nmAtrNumHoras, $vo->numHoras, constantes::$CD_CLASS_CAMPO_OBRIGATORIO, TAMANHO_CODIGOS_SAFI, null, "onKeyUp='validarCampoNumericoPositivo(this);'")?> (horas)
                </TD>
            </TR>
			<TR>
                <TH class="campoformulario" nowrap width=1%>Observação:</TH>
                <TD class="campoformulario" colspan=3>
                	<textarea rows="2" cols="60" id="<?=vometafonte::$nmAtrObs?>" name="<?=vometafonte::$nmAtrObs?>" class="camponaoobrigatorio" maxlength="300"><?php echo($vo->obs);?></textarea>
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
