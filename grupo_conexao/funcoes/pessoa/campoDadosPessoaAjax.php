<?php
include_once ("../../config_lib.php");
include_once ("bibliotecaPessoa.php");
include_once (caminho_util . "bibliotecaHTML.php");
include_once (caminho_funcoes . "turma/bibliotecaTurma.php");

$chave = @$_GET ["chavePessoa"];
$funcao = @$_GET ["funcao"];
// echo "chave = $chave and funcao = $funcao";

// pega do $voCamposDadosPessoaAjax que foi chamado no detalhamento da turma
// mas somente se a $chave nao tiver sido passada, quando tera alguma operacao sobre a colecao
if ($voCamposDadosPessoaAjax != null) {
	$colecaoCdAlunos = $voCamposDadosPessoaAjax->colecaoAlunos;
}

echo imprimeGridAlunosTurma ( $chave, $funcao, $colecaoCdAlunos );
function incluirChave($chave, $array) {
	// var_dump($chave);
	$arrayChave = explode ( constantes::$CD_CAMPO_SEPARADOR_ARRAY, $chave );
	$array = incluirArrayNoArray ( $arrayChave, $array );
	
	return $array;
}
function incluirChaveNoArray($chave, $array) {
	if ($chave != null && ! existeItemNoArray ( $chave, $array )) {
		$array [] = vopessoa::getCdPessoaChaveExplode ( $chave );
	}
	
	return $array;
}
function incluirArrayNoArray($arrayAIncluir, $array) {
	foreach ( $arrayAIncluir as $chave ) {
		$array = incluirChaveNoArray ( $chave, $array );
	}
	
	return $array;
}
function imprimeGridAlunosTurma($chave, $funcao, $colecaoCdAlunos) {
	$html = "";
	$isInclusao = $funcao == constantes::$CD_FUNCAO_INCLUIR;
	$isDetalhamento = $funcao == constantes::$CD_FUNCAO_DETALHAR || $funcao == constantes::$CD_FUNCAO_EXCLUIR;
	
	// -1 eh o codigo para limpar o grid
	$isLimpar = $chave == - 1;
	// $temOperacaoComAColecao = $chave != null;
	
	if (! $isLimpar) {
		// echo "entrou nao limpar<br>";
		// echo $chave . "eh a chave";
		
		if (existeObjetoSessao ( voturma::$ID_REQ_COLECAO_ALUNOS )) {
			$colecaoCdAlunos = getObjetoSessao ( voturma::$ID_REQ_COLECAO_ALUNOS );
			// echo "pegou da sessao";
		}
		
		if ($chave != null) {
			// echo "entrou chave";
			if ($isInclusao) {
				// echo $chave;
				$colecaoCdAlunos = incluirChave ( $chave, $colecaoCdAlunos );
			} else {
				$key = array_search ( $chave, $colecaoCdAlunos );
				if ($key !== false)
					unset ( $colecaoCdAlunos [$key] );
				// echo "removeu";
			}
		}
	} else {
		// echo "removeu tudo";
		removeObjetoSessao ( voturma::$ID_REQ_COLECAO_ALUNOS );
		$colecaoCdAlunos = null;
		// $recordSet = "";
	}
	
	$recordSet = "";
	if ($colecaoCdAlunos != null) {
		$recordSet = consultarPessoasTurma ( $colecaoCdAlunos );
		putObjetoSessao ( voturma::$ID_REQ_COLECAO_ALUNOS, $colecaoCdAlunos );
	}
	// var_dump($recordSet);
	// var_dump ( $colecaoCdAlunos );
	
	$html = mostrarGridAlunos ( $recordSet, $isDetalhamento );
	
	return $html;
}

?>