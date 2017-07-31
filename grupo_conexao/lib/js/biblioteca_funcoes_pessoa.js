/*
 Descricao:
 - Contem funcoes para tratamento de pessoas

 Dependencias:
 - biblioteca_funcoes_principal.js
 - biblioteca_funcoes_ajax.js
*/

function carregaDadosAluno(pCdPessoa, pNmCampoDiv){
		
	if(pCdPessoa != null && pCdPessoa != ""){
		//vai no ajax
		getDadosAlunoAjax(pCdPessoa, "I", pNmCampoDiv);
	}
}

function limparDadosAluno(pCdPessoa, pNmCampoDiv){	
	if(pCdPessoa != null && pCdPessoa != ""){
		//vai no ajax
		getDadosAlunoAjax(pCdPessoa, "E", pNmCampoDiv);
	}
}

function getNomePessoaContratada(pNmCampoPessoa){
	try{
		camposContratada = document.getElementsByName(pNmCampoPessoa);
		numContratadas = camposContratada.length;

		campoNomeContratada = null;
		if(numContratadas == 1){
			campoNomeContratada = camposContratada[0];
		}else{
			//pega o ultimo
			campoNomeContratada = camposContratada[numContratadas-1];
		}		
		
		nmContrata = campoNomeContratada.value;		
		nmContrata = truncarTexto(nmContrata, 20, "");
	}catch(ex){
		nmContrata = "";
	}
	
	return nmContrata;
}
