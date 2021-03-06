/*
 Descri��o:
 - Cont�m fun��es de valida��o e formata��o de campos data e hora

 Depend�ncias:
 - biblioteca_funcoes_principal.js
*/

function getAnoAtual() {
	
	var mydate=new Date();
	var year=mydate.getYear();
	if (year < 1000)
		year+=1900;

	return year;
}

function formatarCampoData(pCampo, pEvento, pInMesAno) {
	var vlCampo = pCampo.value;
	var tam = vlCampo.length;
	var anoAtual = getAnoAtual();
	var anoLimite = anoAtual + 100;
	
	if (isTeclaFuncional(pEvento)) {
		return;
	}
	
	if (pEvento != null && pEvento.keyCode == 111) {
		if (vlCampo.length != 3 && vlCampo.length != 6) {
			pCampo.value = vlCampo.substr(0, tam - 1);
		}
		return;
	}

	var filtro = /^([0-9\/])*$/;
	if (!filtro.test(vlCampo)) {
		pCampo.value = vlCampo.substr(0, tam - 1);
		selecionarCampo(pCampo);
		exibirMensagem(mensagemGlobal(120));
		focarCampo(pCampo);
		return;
	}

	if (vlCampo.length == 2 && vlCampo.charAt(2) != '/') {
		vlCampo = vlCampo + '/';
		pCampo.value = vlCampo;
	}
	if (vlCampo.length > 2 && vlCampo.charAt(2) != '/') {
		vlCampo = vlCampo.substring(0, 2) + '/' + vlCampo.substring(2);
		pCampo.value = vlCampo;
	}

	if (vlCampo.length == 5) {
		if (pInMesAno) {
			if (vlCampo.substr(3, 4) <= 12) {
				vlCampo = vlCampo + '/';
				pCampo.value = vlCampo;
			}
		} else {
			vlCampo = vlCampo + '/';
			pCampo.value = vlCampo;
		}
	}
	if (vlCampo.length > 5 && vlCampo.charAt(5) != '/') {
		vlCampo = vlCampo.substring(0, 5) + '/' + vlCampo.substring(5);
		pCampo.value = vlCampo;
	}

	if ((vlCampo.length > 6 && vlCampo.charAt(6) == '0')
		|| (vlCampo.length > 7 && parseInt(vlCampo.substring(6, 8)) < 19)) {
		selecionarCampo(pCampo);
		exibirMensagem(mensagemGlobal(120) + '\n' + mensagemGlobal(133));
		focarCampo(pCampo);
		return;
	} 

	if (pInMesAno && vlCampo.length >= 7) {
		if (vlCampo.substr(3, 4) > 12) {
			isCampoDataValido(pCampo, null);
		}
	}

	if (vlCampo.length == 10) {
		isCampoDataValido(pCampo, null);
	}
}

// Verifica se existe uma data v�lida no campo "pCampo" passado como parametro
function isCampoDataValido(pCampo, pInObrigatorio, pInMesAno, pSemMensagem, pSemFocarCampo) {
	var msg = "";
	var vlCampo = pCampo.value;
	var anoAtual = getAnoAtual();
	var anoLimite = anoAtual + 100;
	
	if (pInObrigatorio != null || (typeof pInObrigatorio) == "undefined") {
		if (pInObrigatorio) {
			msg = "\n" + mensagemGlobal(0);

		} else {
			msg = "\n" + mensagemGlobal(1);

			if (vlCampo == "")
				return true;
		}
	}

	if (pInMesAno && vlCampo.length == 7) {
		var filtro = /^[0-9]{2}\/[0-9]{4}$/;
		if (!filtro.test(vlCampo)) {
			if (!pSemFocarCampo) {
				selecionarCampo(pCampo);
			}
			if (!pSemMensagem) {
				exibirMensagem(mensagemGlobal(120) + msg);
			}
			if (!pSemFocarCampo) {
				focarCampo(pCampo);
			}
	
			return false;
		}
	
		dia = 01;
		mes = (vlCampo.substring(0, 2));
		ano = (vlCampo.substring(3, 7));

	} else {
		var filtro = /^[0-9]{2}\/[0-9]{2}\/[0-9]{4}$/;
		if (!filtro.test(vlCampo)) {
			if (!pSemFocarCampo) {
				selecionarCampo(pCampo);
			}
			if (!pSemMensagem) {
				exibirMensagem(mensagemGlobal(120) + msg);
			}
			if (!pSemFocarCampo) {				
				focarCampo(pCampo);
			}
	
			return false;
		}
	
		dia = (vlCampo.substring(0, 2));
		mes = (vlCampo.substring(3, 5));
		ano = (vlCampo.substring(6, 10));
	}
	
	//exibirMensagem('dia '+dia);
	//exibirMensagem('mes '+mes);
	//exibirMensagem('ano '+ano);

	situacao = "";
	// verifica o dia valido para cada mes 
	if ((dia < 1) || (dia < 1 || dia > 30) && (mes == 4 || mes == 6 || mes == 9 || mes == 11) || dia > 31) {
		situacao = "falsa";
	}

	// verifica se o mes e valido 
	if (mes < 1 || mes > 12) {
		situacao = "falsa";
	}

	// verifica se ano eh valido
	/*if (ano < 1900) {
		situacao = "falsa";
		msg = "\n" + mensagemGlobal(133);
	}
	if (ano > anoLimite) {
		situacao = "falsa";
		msg = "\n" + mensagemGlobal(143).replace(CD_CAMPO_SUBSTITUICAO, anoLimite);
	}*/

	// verifica se e ano bissexto 
	if (mes == 2 && (dia < 1 || dia > 29 || (dia > 28 && (parseInt(ano / 4) != ano / 4)))) {
		situacao = "falsa";
	}

	if (pCampo.value == "") {
		situacao = "falsa";
	}

	if (situacao == "falsa") {
		if (!pSemFocarCampo) {
			selecionarCampo(pCampo);
		}
		if (!pSemMensagem) {
			exibirMensagem(mensagemGlobal(120) + msg);
		}
		if (!pSemFocarCampo) {
			focarCampo(pCampo);
		}

		return false;
	}

	return true;
}


function calculaIdade(dataNasc){ 
	var dataAtual = new Date();
	var anoAtual = dataAtual.getFullYear();
	var anoNascParts = dataNasc.split('/');
	var diaNasc =anoNascParts[0];
	var mesNasc =anoNascParts[1];
	var anoNasc =anoNascParts[2];
	var idade = anoAtual - anoNasc;
	var mesAtual = dataAtual.getMonth() + 1; 
	//se mês atual for menor que o nascimento, nao fez aniversario ainda; (26/10/2009) 
	if(mesAtual < mesNasc){
		idade--; 
	}else {
		//se estiver no mes do nasc, verificar o dia
		if(mesAtual == mesNasc){ 
			if(dataAtual.getDate() < diaNasc ){ 
				//se a data atual for menor que o dia de nascimento ele ainda nao fez aniversario
				idade--; 
			}
		}
	} 
	return idade; 
}