<?php
include_once("../../config_sistema.php");
include_once ("bibliotecaPessoa.php");
include_once (caminho_util . "bibliotecaHTML.php");
include_once (caminho_funcoes . "turma/bibliotecaTurma.php");

//$voCamposDadosPessoaAjax vem eventualmente da pagina que chamou
echo imprimeGridAlunosTurma ($voCamposDadosPessoaAjax);

?>