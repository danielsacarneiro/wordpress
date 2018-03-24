<?php
function getProximaMateria(&$arrayPesoMaterias, $pesoAnterior=null) {
	//$arrayPesoMaterias = array(3 => array("adm", "const"), 2=>array(civil), 1=> array("criminologia"));
	
	$arrayChavesPeso = array_keys($arrayPesoMaterias);
	$arrayChavesPesoTemp = $arrayChavesPeso;
	if ($pesoAnterior != null) {
		for ($i=0; $i< sizeof($arrayChavesPeso); $i++){
			$pesoTemp = $arrayChavesPeso[$i];
			if($pesoTemp >= $pesoAnterior){
				unset($arrayChavesPesoTemp[$i]);				
			}			
		}		
		$peso = max ( $arrayChavesPesoTemp);
		
		if ($peso == null) {
			//novo giro
			$peso = max ( $arrayChavesPeso);
			//$arrayPesoMaterias[$pesoAnterior] = null;
		}
		
	}else{	
		$peso = max ( $arrayChavesPeso);
	}
	
	$arrayMaterias = $arrayPesoMaterias[$peso];
	
	if($arrayMaterias == null){		
		unset($arrayChavesPesoTemp[$i]);
		//nao tem mais para esse peso
		return -1;
	}
	
	//caso nao tenha mais materia do peso
	if(isColecaoVazia($arrayMaterias)){
		return getProximaMateria($arrayPesoMaterias, $peso);
	}
	//o array eh ordenado do maior peso ao menor
	//o shift retira o primeiro elemento;
	//e assim as materias vao acabando
	$materia = array_shift($arrayMaterias);	
	$arrayPesoMaterias[$peso]=$arrayMaterias;
	
	return $materia;
}
function getMateria($peso, $colecaoMateriasPeso, &$arrayPosicaoMateriaPeso, &$filaPeso) {
	$posicao = $arrayPosicaoMateriaPeso [$peso];
	$retorno = getMateriaPeso ( $peso, $colecaoMateriasPeso, $posicao );
	$arrayPosicaoMateriaPeso [$peso] = $posicao;
	
	$tamanhoArrayMaterias = sizeof ( $arrayMaterias );
	$NAOTemMaisMateriaAconsultar = (($posicaoEscolhida % $tamanhoArrayMaterias) + 1) == $tamanhoArrayMaterias;
	
	if (! $NAOTemMaisMateriaAconsultar) {
		array_push ( $filaPeso, $peso );
	}
	
	return $retorno;
}
function getMateriaPeso($peso, $colecaoMateriasPeso, &$posicao) {
	$retorno = $colecaoMateriasPeso [$posicao];
	$posicao ++;
	
	return $retorno;
}