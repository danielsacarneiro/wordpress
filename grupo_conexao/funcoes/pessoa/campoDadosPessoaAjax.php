<?php
include_once ("../../config_lib.php");
include_once ("bibliotecaPessoa.php");

$db = new dbpessoa ();
$chave = @$_GET ["chave"];
$funcao = @$_GET ["funcao"];
$isInclusao = $funcao == constantes::$CD_FUNCAO_INCLUIR;
$isLimpar = $chave == - 1;

// echo "chave = $chave and funcao = $funcao";

$recordSet = "";
// -1 eh o codigo para limpar o grid
if (! $isLimpar) {
	if ($chave != null) {
		
		$colecaoCdAlunos = getObjetoSessao ( voturma::$ID_REQ_COLECAO_ALUNOS );
		if ($colecaoCdAlunos == null) {
			$colecaoCdAlunos = array (
					$chave 
			);
			//echo "incluiu do zero";
		} else {
			if ($isInclusao) {
				if (! existeItemNoArray ( $chave, $colecaoCdAlunos )) {					
					$colecaoCdAlunos [] = $chave;
					//echo "incluiu";
				}
			} else {
				$key = array_search ( $chave, $colecaoCdAlunos);
				if ($key !== false)
					unset($colecaoCdAlunos[$key]);
					
				//echo "removeu";
			}
		}
		
		putObjetoSessao ( voturma::$ID_REQ_COLECAO_ALUNOS, $colecaoCdAlunos );
		
		if ($colecaoCdAlunos != null)
			$recordSet = consultarPessoas ( $colecaoCdAlunos );
	}
} else {
	//echo "removeu tudo";
	removeObjetoSessao ( voturma::$ID_REQ_COLECAO_ALUNOS );
}

//var_dump ( $colecaoCdAlunos );

if ($recordSet != "") {
	mostrarGridAlunos ( $recordSet, true );
} else {
	echo "&nbsp;Selecione alunos clicando na lupa acima.";
}

?>