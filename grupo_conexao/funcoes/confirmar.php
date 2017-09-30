<?php
include_once("../config_lib.php");
include_once(caminho_util."bibliotecaHTML.php");
//define a classe do vo que chamou
$class = @$_GET["class"];
//echo $class;

inicioComValidacaoUsuario(true);

setCabecalhoPorNivel(null,1);
$funcao = @$_POST["funcao"];
//echo $funcao;
$isInclusao = $funcao == constantes::$CD_FUNCAO_INCLUIR;
$isExclusao = $funcao == constantes::$CD_FUNCAO_EXCLUIR;
$isAlteracao = $funcao == constantes::$CD_FUNCAO_ALTERAR;

$msgErro = "";
$nmFuncao = "";

$classMensagem = "campomensagemverde";
$msg = "OPERACÃO $nmFuncao REALIZADA COM SUCESSO";

try{
    $vo = new $class();
    $dbprocesso = $vo->dbprocesso;
    $vo->getDadosFormularioEntidade ();    
    putObjetoSessao ( "vo", $vo );
    
    //var_dump($vo);
    
    if($isInclusao){
        $nmFuncao = "INCLUIR";        
        /*$metodo = 'incluir';
        $parametros = array($vo, false); 
        $resultado = call_user_func_array(array( $classe, $metodo), $parametros);*/                
        $vo = $dbprocesso->incluir($vo);
                
    }else if($isExclusao){
        $nmFuncao = "EXCLUIR";
        $resultado = $dbprocesso->excluir($vo);
        
    }else if($isAlteracao){
        $nmFuncao = "ALTERAR";
        //$resultado = $dbprocesso->alterarContratoPorCima($vo);
        $resultado = $dbprocesso->alterar($vo);
    }else {    	
    	//chama um metodo especifico passado
    	//caso nao seja nenhuma das funcoes basicas acima
    	if (method_exists($dbprocesso,$funcao)) {
    		$argumentos = array($vo);
    		call_user_func_array(array($dbprocesso,$funcao),$argumentos);
    	}    	 
    }
            
    if($vo->getMensagemComplementarTelaSucessoVOEntidade() != ""){
    	$msgComplementar = $vo->getMensagemComplementarTelaSucessoVOEntidade();
    	$msg .= "<br>" . $msgComplementar; 
    }    
    
    //echo $vo->getNmTabela();
    //var_dump($vo);
    putObjetoSessao($vo->getNmTabela(), $vo);
    
}catch(Exception $e) {  
	//mais uma alternativa de usar a excecao para msgs na tela
	//mas ainda nao esta sendo utilizado
	if(!isExcecaoSucesso($e)){	
		$msgErro = $e->getMessage();
	    $classMensagem = "campomensagemvermelho";
	    $msg = "OPERACAO $nmFuncao FALHOU.<br>$msgErro";
	    $msg.= "<BR> Código Exceção: ". $e->getCode();
	    $msg.= "<BR> Nome Exceção: ". get_class($e);
	    
	}else{
		$msg .= "<BR>". $e->getMessage();
	}
}


?>
<!DOCTYPE html>
<HEAD>
<?=setTituloPaginaPorNivel(null,1)?>

<SCRIPT language="JavaScript" type="text/javascript">

function cancela() {	
	//history.back().back();
    //window.location.history.go(-2);
    //history.go(-2);
	//location.href="index.php";	
}

</SCRIPT>

</HEAD>
<BODY class="paginadados" onload="">
	  
<FORM name="frm_principal" method="post" action="<?=$vo->getTelaRetornoConfirmar()?>/index.php?consultar=S"> 
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
                <TR>
                    <TH class="<?=$classMensagem?>"><?=$msg?></TH>			
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
								<TD class="botaofuncao"><button id="cancelar" onClick="javascript:cancela();" class="botaofuncaop" type="submit" accesskey="o">OK</button></TD>
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
