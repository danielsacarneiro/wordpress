<?php
include_once("../../config_lib.php");
include_once(caminho_util."bibliotecaHTML.php");

try{
//inicia os parametros
inicioComValidacaoUsuario(true);

$vo = new voturma();
//var_dump($vo->varAtributos);

$funcao = @$_GET["funcao"];

$readonly = "";
$isInclusao = $funcao == constantes::$CD_FUNCAO_INCLUIR;

removeObjetoSessao ( voturma::$ID_REQ_COLECAO_ALUNOS );
$nmFuncao = "";
if($isInclusao){    
	$nmFuncao = "INCLUIR ";	
}else{
    $readonly = "readonly";
	$chave = @$_GET["chave"];
	$array = explode("*",$chave);
	
	$vo->cd = $array[0];
    $vo->cdHistorico = $array[1];
    $isHistorico = ("S" == $vo->cdHistorico);        
	
	$dbprocesso = $vo->dbprocesso;					
	$colecao = $dbprocesso->consultarPorChave($vo, $isHistorico);	
	$vo->getDadosBancoPorChave($colecao);
	putObjetoSessao($vo->getNmTabela(), $vo);
	//putObjetoSessao(voturma::$ID_REQ_COLECAO_ALUNOS, $vo->colecaoAlunos);

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
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_moeda.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_ajax.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_pessoa.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>mensagens_globais.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>jquery.js"></SCRIPT>

<SCRIPT language="JavaScript" type="text/javascript">


function listarAlunos(cd, funcao) {
	  $.ajax({
	    type: "GET",
	    url: "../pessoa/campoDadosPessoaAjax.php",	    
	    data: {
	      //cdPessoa: $('#seu_nome').val()
	      chavePessoa: cd,
	      funcao: funcao
	    },
	    success: function(data) {
	      $('#<?=voturma::$NM_DIV_COLECAO_ALUNOS?>').html(data);
	    }
	  });
}

function transferirDadosPessoa(cdPessoa) {		   
	//chamar funcao bibliotecafuncoespessoa
	carregarDadosAluno(cdPessoa, '<?=voturma::$NM_DIV_COLECAO_ALUNOS?>');

	//listarAlunos(cdPessoa, "<?=constantes::$CD_FUNCAO_INCLUIR?>");
}

function limparDadosPessoa(cdPessoa) {		   
	//chamar funcao bibliotecafuncoespessoa
	limparDadosAluno(cdPessoa, '<?=voturma::$NM_DIV_COLECAO_ALUNOS?>');
	//listarAlunos(cdPessoa, "<?=constantes::$CD_FUNCAO_EXCLUIR?>");	
}


// Verifica se o formulario esta valido para alteracao, exclusao ou detalhamento
function isFormularioValido() {
	if (!isCampoMoedaComSeparadorMilharValido(document.frm_principal.<?=voturma::$nmAtrValor?>, 2, false))
		return false;		
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


</SCRIPT>
</HEAD>
<?=setTituloPagina($vo->getTituloJSP())?>
<BODY class="paginadados" onload="">
	  
<FORM name="frm_principal" method="post" action="../confirmar.php?class=<?=get_class($vo)?>" onSubmit="return confirmar();">

<INPUT type="hidden" id="funcao" name="funcao" value="<?=$funcao?>">
<INPUT type="hidden" id="<?=vousuario::$nmAtrID?>" name="<?=vousuario::$nmAtrID?>" value="<?=id_user?>">
<INPUT type="hidden" id="<?=voturma::$nmAtrCd?>" name="<?=voturma::$nmAtrCd?>" value="<?=$vo->cd?>">
 
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
                <TH class="campoformulario" nowrap width=1%>Descrição:</TH>
                <TD class="campoformulario" colspan=3><INPUT type="text" id="<?=voturma::$nmAtrDescricao?>" name="<?=voturma::$nmAtrDescricao?>"  value="<?php echo($vo->descricao);?>"  class="camponaoobrigatorio" size="50" required></TD>
            </TR>
			<TR>
	            <TH class="campoformulario" nowrap width=1%>Valor Mensal:</TH>
	            <TD class="campoformulario" colspan="3"><INPUT type="text" id="<?=voturma::$nmAtrValor?>" name="<?=voturma::$nmAtrValor?>" required value="<?php echo(getMoeda($vo->valor));?>"
	            onkeyup="formatarCampoMoedaComSeparadorMilhar(this, 2, event);" class="camponaoobrigatorioalinhadodireita" size="15" ></TD>
	        </TR>					            
			<TR>
                <TH class="campoformulario" nowrap width=1%>Observação:</TH>
                <TD class="campoformulario" colspan=3>
                				<textarea rows="2" cols="60" id="<?=voturma::$nmAtrObservacao?>" name="<?=voturma::$nmAtrObservacao?>" class="camponaoobrigatorio" maxlength="300"><?php echo($vo->obs);?></textarea>
				</TD>
            </TR>
			<TR>
				<TH class="textoseparadorgrupocampos" halign="left" colspan="4">
				<DIV class="campoformulario">&nbsp;&nbsp;Incluir Alunos&nbsp;&nbsp;
				<?php 
				include_once(caminho_funcoes. "pessoa/dominioVinculoPessoa.php");
				echo getLinkPesquisa("../pessoa?" . vopessoavinculo::$nmAtrCd . "=" . dominioVinculoPessoa::$CD_VINCULO_ALUNO);
				echo "&nbsp;&nbsp; Limpar tudo" . getBorrachaJS("limparDadosPessoa(-1);");
				?>
				</DIV>
				</TH>
			</TR>
						
					
            <TD class="conteinerfiltro" colspan="4">            
            <TABLE cellpadding="0" cellspacing="0" id="<?=voturma::$NM_DIV_COLECAO_ALUNOS?>">
            <TBODY>
            					  <?php
					  $voCamposDadosPessoaAjax = $vo;
					  include_once(caminho_funcoes. "pessoa/campoDadosPessoaAjax.php");					  
					  ?>
            
            </TBODY>
            </TABLE>
            </TD>
            </TR>
			
                             
        <?php if(!$isInclusao){
            echo  incluirUsuarioDataHoraDetalhamento($vo) ;
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
