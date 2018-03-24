/*
 Descricao:
 - Contem funcoes para tratamento de pessoas
 Dependencias:
 - biblioteca_funcoes_principal.js
*/

function setNumeroCaixasMeta(pArrayIDCampos){
	
	pNmCampoNumHorasDia = pArrayIDCampos[0];
	pNmCampoNumHorasMateriaDia = pArrayIDCampos[1];
	pNmCampoNumDiasMeta = pArrayIDCampos[2];
	pNmCampoNumCaixas = pArrayIDCampos[3];


	pCampoNumHorasDia = document.getElementById(pNmCampoNumHorasDia);
	pCampoNumHorasMateriaDia = document.getElementById(pNmCampoNumHorasMateriaDia);
	pCampoNumDiasMeta = document.getElementById(pNmCampoNumDiasMeta);
	pCampoNumCaixas = document.getElementById(pNmCampoNumCaixas);

	horasDia = pCampoNumHorasDia.value;
	horasMateria = pCampoNumHorasMateriaDia.value;
	dias = pCampoNumDiasMeta.value;

	if(horasDia != "" && horasMateria != "" && dias != ""){
		retorno = horasDia/horasMateria*dias;
		//retorno = Math.trunc(retorno);
		//retorno = Math.round(retorno);

		pCampoNumCaixas.value = retorno; 
	}	 
	
}