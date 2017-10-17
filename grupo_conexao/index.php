<?php
include_once("configuracao_geral.php");
setTipoPagina(configuracao_geral::$CD_TIPO_PAGINA_MENU_GERAL);
removeObjetoSessao(constantes::$ID_REQ_ID_SISTEMA);

include_once("config_lib.php");
include_once(caminho_util."bibliotecaHTML.php");
include_once(caminho_util."constantes.class.php");

//inicia os parametros
inicio();
setCabecalho(constantes::$tituloSistema);

?>

<!DOCTYPE html>
<HTML>
<HEAD>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>tooltip.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_principal.js"></SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript" src="<?=caminho_js?>biblioteca_funcoes_treemenu.js"></SCRIPT>

<SCRIPT language="javascript">
	f = new Tree('Menu de Funcionalidades finalizadas e entregue a SEFAZ-PE (Homologa��o)', true);
	
	f1 = new Tree('Servi�o 01 (Tabelas)');	
    f1.adicionarItem(new Link('Documentos', '<?=caminho_funcoesHTML?>documento', ''));
    f1.adicionarItem(new Link('Pessoas', '<?=caminho_funcoesHTML?>pessoa', ''));
    f1.adicionarItem(new Link('Mat�rias', '<?=caminho_funcoesHTML?>materia', ''));
	f.adicionarItem(f1);
	
	f2 = new Tree('Servi�o 02 (Relacionamentos)');
    f2.adicionarItem(new Link('Pessoa x Turma', '<?=caminho_funcoesHTML?>pessoa_turma', ''));
    f2.adicionarItem(new Link('Turmas', '<?=caminho_funcoesHTML?>turma', ''));
	f.adicionarItem(f2);
	
	<?php if(isUsuarioAdmin()){?>
	/*f5 = new Tree('Servi�o 05 (ADMINISTRADOR)');
    f5.adicionarItem(new Link('Usu�rios', '<?=caminho_funcoesHTML?>usuario_info', ''));
	f5.adicionarItem(new Link('______', '#', ''));
	f5.adicionarItem(new Link('______', '#', ''));
	f5.adicionarItem(new Link('______', '#', ''));	
	f5.adicionarItem(new Link("IMPORTAR PROFISCO", "<?=caminho_funcoesHTML?>contrato/importarConvenio.php?tipo=P", ""));		
	f5.adicionarItem(new Link("ATUALIZAR CONTRATADA", "<?=caminho_funcoesHTML?>contrato/atualizarContratada.php", ""));
	f.adicionarItem(f5);	*/
	<?php }?>
	

	/*
	exemplo
    // SERVI�O 4
	f3 = new Tree('Di�ria');
	f3.adicionarItem(new Link("Grupo de Di�rias","/sfi_fin_gfu/PRManterGrupoDiaria", "25040"));
	f3.adicionarItem(new Link("Faixa de Di�ria", "/sfi_fin_gfu/PRManterFaixaDiaria", "25041"));
	f3.adicionarItem(new Link("Tabela de Di�ria", "/sfi_fin_gfu/PRManterTabelaDiaria", "25042"));
	f.adicionarItem(f3);
	*/


</SCRIPT>
</HEAD>
<?=setTituloPagina(null)?>
<BODY CLASS="paginadados">
	<FORM name="frm_principal" method="post">
		<INPUT type="hidden" id="id_contexto_sessao" name="id_contexto_sessao" value=""> 
		<INPUT type="hidden" id="evento" name="evento" value=""> 
			<TABLE id="table_conteiner" class="conteiner" cellpadding="0" cellspacing="0">
    			<TBODY>
        			<?=cabecalho?>
        			<TR>
            			<TD class="conteinerconteudodados">
            			 <DIV id="div_conteudodados" class="conteudodados">
							<TABLE id="table_conteudodados" class="conteudodados" cellpadding="0" cellspacing="0">

                    				<TR>
                        				<TD valign="top" bgcolor="#A5B9D7">
											<SCRIPT>f.escrever(false, 0);</SCRIPT>
                        				</TD>
                    				</TR>
                    				
                     				<!--<TR>
                                    	<TH  class="titulopassoapasso" ><B>Outros Sistemas</B></TH>
                                	</TR>
                    				<TR>
                        				<TD valign="top" bgcolor="#A5B9D7">
											<SCRIPT>fOutros.escrever(false, 0);</SCRIPT>
                        				</TD>
                    				</TR>-->
                				
            				</TABLE>
            			</TD>
        			</TR>        			
    			</TBODY>
			</TABLE>
		</FORM>
</BODY>
</HTML>