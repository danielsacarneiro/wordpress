<?php
include_once("../../config_lib.php");
include_once(caminho_util."bibliotecaHTML.php");
include_once(caminho_vos."dbpessoa.php");
include_once(caminho_vos . "vopessoavinculo.php");
include_once("dominioVinculoPessoa.php");

//inicia os parametros
inicioComValidacaoUsuario(true);

$vo = new vopessoa();
//var_dump($vo->varAtributos);

$funcao = @$_GET["funcao"];

$readonly = "";
$isInclusao = $funcao == constantes::$CD_FUNCAO_INCLUIR;

$nmFuncao = "";
if($isInclusao){    
	$nmFuncao = "INCLUIR ";	
}else{
    $readonly = "readonly";
    $vo->getVOExplodeChave($chave);
    $isHistorico = ($vo->sqHist != null && $vo->sqHist != "");
    
	$dbprocesso = new dbpessoa(null);					
	$colecao = $dbprocesso->consultarPorChave($vo, $isHistorico);	
	$vo->getDadosBanco($colecao);
	putObjetoSessao($vo->getNmTabela(), $vo);

    $nmFuncao = "ALTERAR ";
}

$titulo = "PESSOA";
$titulo = $nmFuncao . $titulo;
setCabecalho($titulo);

$nome  = $vo->nome;
$doc  = $vo->doc;
$email  = $vo->email;
    
$dhInclusao = $vo->dhInclusao;
$dhUltAlteracao = $vo->dhUltAlteracao;
$cdUsuarioInclusao = $vo->cdUsuarioInclusao;
$cdUsuarioUltAlteracao = $vo->cdUsuarioUltAlteracao;

?>
<!DOCTYPE html>

<HEAD>

<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_principal.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_text.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_datahora.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_radiobutton.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_cnpfcnpj.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_ajax.js"></script>

<SCRIPT language="JavaScript" type="text/javascript">

function transferirDadosOrgaoGestor(cdGestor, dsGestor) {
	document.getElementsByName("<?=vogestor::$nmAtrCd?>").item(0).value = completarNumeroComZerosEsquerda(cdGestor, <?=TAMANHO_CODIGOS?>);
	document.getElementsByName("<?=vogestor::$nmAtrDescricao?>").item(0).value = dsGestor;
}

function limpaCampoGestor() {		   
	document.getElementsByName("<?=vogestor::$nmAtrCd?>").item(0).value = "";
	document.getElementsByName("<?=vogestor::$nmAtrDescricao?>").item(0).value = "";
}

// Verifica se o formulario esta valido para alteracao, exclusao ou detalhamento
function isFormularioValido() {
	
	campoDocCPF = document.frm_principal.<?=vopessoa::$nmAtrDocCPF?>;
	if (!isCampoCNPFouCNPJValido(campoDocCPF, true, false)){
		return false;	
	}

	if (!validaFoto()){
		exibirMensagem("Selecione uma foto!");
		return false;	
	}
		
	return true;
}

function cancela() {
	//history.back();
	location.href="index.php?consultar=S";	
}

function confirmar() {
	if(!isFormularioValido())
		return false;
	
	return confirm("Confirmar Alteracoes?");    
}

function validaFoto(){
	campofoto = document.frm_principal.<?=vopessoa::$nmAtrFoto?>;
	foto = campofoto.value;

	isFotoNaoNula = foto != null && foto != '';	
	retorno  = isFotoNaoNula;	

	isFotoJpg = foto.indexOf( '.' + 'jpg' ) != -1 ;
	if(isFotoNaoNula && !isFotoJpg){
		exibirMensagem("A foto deve ser JPG.");
		retorno = false;
	}
	
	return retorno;
}

function validaMaiorIdade(){
	campoDataNascimento = document.frm_principal.<?=vopessoa::$nmAtrDtNascimento?>;
	campoResponsavel = document.frm_principal.<?=vopessoa::$nmAtrResponsavel?>;
	//isCampoDataValido(pCampo, pInObrigatorio, pInMesAno, pSemMensagem, pSemFocarCampo)
	
	idade = calculaIdade(campoDataNascimento.value);
	//alert (idade);
	if(idade < 18){
		return false;		
	}
	
	return true;
}

/*function validaVinculo(){
	vinculo = document.frm_principal.<?=vopessoavinculo::$nmAtrCd?>.value;
	if(vinculo == ''){
		
		if (!isCampoTextoValido(document.frm_principal.<?=vogestor::$nmAtrCd?>, true, 1, <?=TAMANHO_CODIGOS?>, true)){
			exibirMensagem("Selecione o Órgão Gestor!");
		    return false;
		}

	}
	
	return true;
}

function verificaVinculo(){
	vinculo = document.frm_principal.<?=vopessoavinculo::$nmAtrCd?>.value;
	campo = document.getElementById("<?=vogestor::getNmTabela()?>");
	if(vinculo == ''){
		campo.style.display = "";		
	}
	else{ 
		campo.style.display = "none";
		limpaCampoGestor();
	}	
}*/

function checkResponsabilidade() {
	campoResponsabilidade = document.frm_principal.checkBoxREsponsabilidade;
	campoResponsavel = document.frm_principal.<?=vopessoa::$nmAtrResponsavel?>;

	campoResponsavel.required = true;
	if(campoResponsabilidade.checked){
		if(!validaMaiorIdade()){
			exibirMensagem("A menoridade exige um responsável!");
			campoResponsabilidade.checked = false;
		}else{		
			campoResponsavel.required = false;
		}
	}else{
		campoResponsavel.required = true;
	}
}


function iniciar(){
	//verificaVinculo();	
}

function abrirJanelaAuxiliarGestor(){
	//abrirJanelaAuxiliar('".$link."',true, false, false);\" "		
}

</SCRIPT>
<?=setTituloPagina(null)?>
</HEAD>
<BODY class="paginadados" onload="iniciar();">
	  
<FORM name="frm_principal" method="post" action="confirmar.php" onSubmit="return confirmar();" enctype="multipart/form-data">

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
	        <?php if(!$isInclusao){?>
	        	<TR>
	        	<TH class="campoformulario" nowrap width=1%>Código:</TH>
	        	<TD class="campoformulario" colspan=3><INPUT type="text" value="<?php echo(complementarCharAEsquerda($vo->cd, "0", TAMANHO_CODIGOS));?>"  class="camporeadonlyalinhadodireita" size="5" readonly></TD>
	        	</TR>        	 
	        <?php }?>            
			<TR>
                <TH class="campoformulario" width="1%" nowrap>Vínculo:</TH>
                <TD class="campoformulario" colspan=3>
                     <?php
                    include_once("biblioteca_htmlPessoa.php");
                    echo getComboPessoaVinculo(vopessoavinculo::$nmAtrCd, vopessoavinculo::$nmAtrCd, $colecao[vopessoavinculo::$nmAtrCd], "camponaoobrigatorio", " required ");                    
                    ?>                
            </TR>
			<TR>
                <TH class="campoformulario" nowrap width=1%>Nome:</TH>
				<TD class="campoformulario" colspan=3>
                <INPUT type="text" id="<?=vopessoa::$nmAtrNome?>" name="<?=vopessoa::$nmAtrNome?>"  value="<?php echo($nome);?>"  class="camponaoobrigatorio" size="70" required></TD>
            </TR>
			<TR>
                <TH class="campoformulario" nowrap width=1%>RG/Órgão.Exp.:</TH>
                <TD class="campoformulario" width="1%"><INPUT type="text" id="<?=vopessoa::$nmAtrDocRG?>" name="<?=vopessoa::$nmAtrDocRG?>"  value="<?php echo($vo->docRG);?>"  class="camponaoobrigatorio" size="30" required></TD>
                <TH class="campoformulario" width="1%" nowrap>CPF:</TH>
                <TD class="campoformulario" ><INPUT type="text" id="<?=vopessoa::$nmAtrDocCPF?>" name="<?=vopessoa::$nmAtrDocCPF?>" onkeyup="formatarCampoCNPFouCNPJ(this, event);" value="<?php echo(documentoPessoa::getNumeroDocFormatado($doc));?>" class="camponaoobrigatorio" size="20" maxlength="18" required></TD>
            </TR>
			<TR>
                <TH class="campoformulario" nowrap width=1%>Email:</TH>
                <TD class="campoformulario" width="1%"><INPUT type="text" id="<?=vopessoa::$nmAtrEmail?>" name="<?=vopessoa::$nmAtrEmail?>"  value="<?php echo($email);?>"  class="camponaoobrigatorio" size="50" required></TD>
	            <TH class="campoformulario" nowrap width="1%">Data.Nascimento:</TH>
	            <TD class="campoformulario">
	            	<INPUT type="text" 
	            	       id="<?=vopessoa::$nmAtrDtNascimento?>" 
	            	       name="<?=vopessoa::$nmAtrDtNascimento?>" 
	            			value="<?php echo(getData($vo->dtNascimento));?>"
	            			onkeyup="formatarCampoData(this, event, false);" 
	            			onChange ="checkResponsabilidade();"
	            			class="camponaoobrigatorio" 
	            			size="10" 
	            			maxlength="10" required>
				</TD>                
            </TR>
			<TR>
                <TH class="campoformulario" nowrap width=1%>Telefone(s):</TH>
                <TD class="campoformulario" width="1%"><INPUT type="text" id="<?=vopessoa::$nmAtrTel?>" name="<?=vopessoa::$nmAtrTel?>"  value="<?php echo($vo->tel);?>"  class="camponaoobrigatorio" size="50" maxlength="100"></TD>
                <TH class="campoformulario" nowrap width=1%>Tel. Wapp:</TH>
                <TD class="campoformulario" ><INPUT type="text" id="<?=vopessoa::$nmAtrTelWapp?>" name="<?=vopessoa::$nmAtrTelWapp?>"  value="<?php echo($vo->telWapp);?>"  class="camponaoobrigatorio" size="14" maxlength="12"></TD>                
            </TR>    
            <?php 
            $comboEstados = new select(dominioEstados::getColecao());
            ?>
			<TR>
                <TH class="campoformulario" nowrap width=1%>Endereço:</TH>
                <TD class="campoformulario" width="1%" colspan=3>
                				Rua <textarea rows="1" cols="80" id="<?=vopessoa::$nmAtrEndereco?>" name="<?=vopessoa::$nmAtrEndereco?>" class="camponaoobrigatorio" maxlength="300"><?php echo($vo->endereco);?></textarea>
                				<br>
                				Bairro <INPUT type="text" id="<?=vopessoa::$nmAtrBairro?>" name="<?=vopessoa::$nmAtrBairro?>"  value="<?php echo($vo->bairro);?>"  class="camponaoobrigatorio" size="30" maxlength="50">
                				Cidade <INPUT type="text" id="<?=vopessoa::$nmAtrCidade?>" name="<?=vopessoa::$nmAtrCidade?>"  value="<?php echo($vo->cidade);?>"  class="camponaoobrigatorio" size="30" maxlength="50">
                				Estado: <?= $comboEstados->getHtmlCombo(vopessoa::$nmAtrUF, vopessoa::$nmAtrUF, $vo->uf, true, "camponaoobrigatorio", false, " ");?>
				</TD>
            </TR>     
			<TR>
                <TH class="campoformulario" nowrap width=1%>Observação:</TH>
                <TD class="campoformulario" width="1%" colspan=3>
                				<textarea rows="2" cols="60" id="<?=vopessoa::$nmAtrObservacao?>" name="<?=vopessoa::$nmAtrObservacao?>" class="camponaoobrigatorio" maxlength="300"><?php echo($vo->obs);?></textarea>
				</TD>
            </TR>     
            
			<TR>
                <TH class="campoformulario" nowrap width=1%>Foto:</TH>
                <TD class="campoformulario" width="1%" colspan=3>
					
					<?php 
					$nmCampoFoto = vopessoa::$nmAtrFoto;
					?>					
					<script src="http://code.jquery.com/jquery-1.11.2.min.js"></script>
					<input type="file" id="<?=$nmCampoFoto?>" name="<?=$nmCampoFoto?>">
					<script>
					$("#<?=$nmCampoFoto?>").change(function(){
					    if (this.files && this.files[0]) {
					        var reader = new FileReader();
					
					        reader.onload = function (e) {
					            $('#preview_foto').attr('src', e.target.result);
					        }
					        reader.readAsDataURL(this.files[0]);
					    }
					});
					</script>
															
				</TD>
            </TR>                 
			<TR>
                <TH class="campoformulario" nowrap width=1%>Preview:</TH>
                <TD class="campoformulario" width="1%" colspan=3>
                	<img id="preview_foto" src="<?php echo pasta_imagens?>foto_selecione.gif" height="150">
                	<?php                	
                	echo getBorrachaJS("document.frm_principal.$nmCampoFoto.value = '';document.frm_principal.preview_foto.src = ' " . pasta_imagens . "foto_selecione.gif';");
                	?>
				</TD>
            </TR>   
   			<TR>
                <TH class="campoformulario" nowrap width=1%>Responsável:</TH>
				<TD class="campoformulario" colspan=3>
                <INPUT type="text" id="<?=vopessoa::$nmAtrResponsavel?>" name="<?=vopessoa::$nmAtrResponsavel?>"  value="<?php echo($vo->responsavel);?>"  class="camponaoobrigatorio" size="70" required>
                <INPUT type="checkbox" name="checkBoxREsponsabilidade" value="" onClick="checkResponsabilidade();"> *O próprio.
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
								<?php
								if($funcao == "I" || $funcao == "A"){
								?>
                                    <TD class="botaofuncao"><?=getBotaoConfirmar()?></TD>
								<?php
								}?>
								<TD class="botaofuncao"><button id="cancelar" onClick="javascript:cancela();" class="botaofuncaop" type="button" accesskey="c">Cancelar</button></TD>                                
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
