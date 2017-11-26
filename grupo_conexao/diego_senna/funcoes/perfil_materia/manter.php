<?php
include_once("../../config_sistema.php");
include_once(caminho_util."bibliotecaHTML.php");

try{
//inicia os parametros
inicioComValidacaoUsuario(true);

$vo = new voperfilmateria();
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
	campoCdPerfil = document.frm_principal.<?=voperfilmateria::$nmAtrCdPerfil?>;
	if(!isCampoNumericoValido(campoCdPerfil, true, 1, null, null, true)){
		exibirMensagem("Selecione o perfil.");
		return false;
	}

	campoCdMateria = document.frm_principal.<?=voperfilmateria::$nmAtrCdMateria?>;
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
	document.frm_principal.<?=voperfilmateria::$nmAtrCdMateria?>.value = completarNumeroComZerosEsquerda(cdMateria, <?=TAMANHO_CODIGOS_SAFI?>);
	document.frm_principal.<?=vomateria::$nmAtrDescricao?>.value = dsMateria;
}

function transferirDadosPerfil(cdPerfil, dsPerfil){
	document.frm_principal.<?=voperfilmateria::$nmAtrCdPerfil?>.value = completarNumeroComZerosEsquerda(cdPerfil, <?=TAMANHO_CODIGOS_SAFI?>);
	document.frm_principal.<?=voperfil::$nmAtrDescricao?>.value = dsPerfil;
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
	        	$class = constantes::$CD_CLASS_CAMPO_READONLY;
	        	$size = TAMANHO_CODIGOS_SAFI;
	        	$lupaPerfil = getLinkPesquisa(caminho_funcoesHTML."perfil");
	        	$lupaMateria = getLinkPesquisa(caminho_funcoesHTML."materia");
	        }
	        ?>
			<TR>
                <TH class="campoformulario" width="1%" nowrap>Perfil:</TH>
                <TD class="campoformulario" colspan=3>
                	Cd. <?=getInputText(voperfilmateria::$nmAtrCdPerfil, voperfilmateria::$nmAtrCdPerfil, complementarCharAEsquerda($vo->cdPerfil, "0", TAMANHO_CODIGOS_SAFI), $class, $size) . " $lupaPerfil"?>
					- Descrição: <?=getInputText(voperfil::$nmAtrDescricao, voperfil::$nmAtrDescricao, $voperfil->descricao, constantes::$CD_CLASS_CAMPO_READONLY)?> 
                </TD>
            </TR>        
			<TR>
                <TH class="campoformulario" width="1%" nowrap>Matéria:</TH>
                <TD class="campoformulario" colspan=3>
                	Cd. <?=getInputText(voperfilmateria::$nmAtrCdMateria, voperfilmateria::$nmAtrCdMateria, complementarCharAEsquerda($vo->cdMateria, "0", TAMANHO_CODIGOS_SAFI), $class, $size) . " $lupaMateria"?>
					- Descrição: <?=getInputText(vomateria::$nmAtrDescricao, vomateria::$nmAtrDescricao, $vomateria->descricao, constantes::$CD_CLASS_CAMPO_READONLY)?> 
                </TD>
            </TR>        
			<TR>
                <TH class="campoformulario" nowrap width=1%>Carga:</TH>
                <TD class="campoformulario" colspan=3>
                <INPUT type="text" id="<?=voperfilmateria::$nmAtrCarga?>" name="<?=voperfilmateria::$nmAtrCarga?>"  value="<?php echo(complementarCharAEsquerda($vo->carga, "0", TAMANHO_CODIGOS_SAFI));?>"  class="camponaoobrigatorio" 
                onKeyUp='validarCampoNumericoPositivo(this)' size="5" required> horas</TD>
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
