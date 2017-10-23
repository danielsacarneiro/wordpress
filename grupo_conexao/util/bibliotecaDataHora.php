<?php
include_once ("mensagens.class.php");
include_once ("mensagens.class.php");
include_once ("dominioFeriados.php");
function getDataHora($dataSQL) {
	return getDataHoraParam ( $dataSQL, true );
}
function getData($dataSQL) {
	return getDataHoraParam ( $dataSQL, false );
}
function getDataHoraParam($dataSQL, $temHora) {
	$retorno = null;
	if ($dataSQL != null) {
		$dataSQL = str_replace ( "/", "-", $dataSQL );
		if ($dataSQL == "0000-00-00") {
			// $retorno = mensagens::$msgDataErro;
			$retorno = "";
		} else if ($dataSQL != null && $dataSQL != "0000-00-00") {
			$retorno = date ( "d/m/Y", strtotime ( $dataSQL ) );
			if ($temHora) {
				$retorno .= " " . date ( "H:i:s", strtotime ( $dataSQL ) );
			}
		}
	}
	return $retorno;
}
function getDataHoraSQLComoString($data) {
	// pega qualquer data e transforma no formato SQL
	$retorno = "";
	if ($data != null) {
		$retorno = getDataHora ( $data );
		/*
		 * $retorno = date("d/m/Y", strtotime($data)) . " ";
		 * $retorno .= date("H:i:s", strtotime($data));
		 */
	}
	
	return $retorno;
}
function getDataFormatoSQL($data) {
	// pega qualquer data e transforma no formato SQL
	$retorno = "";
	if ($data != null){
		//$retorno = date ( "Y/m/d", strtotime ( $data ) );
		$retorno = getVarComoDataSQL($data, false);
	}
	
	return $retorno;
}

// retorna negativo se a data inicio for maior que a data fim
function getQtdDiasEntreDatas($dataini, $datafim) {
	if ($dataini == null || $datafim == null)
		throw new Exception ( "uma das datas nula" );
	
	// usa o tipo DateTime
	$data1 = new DateTime ( getDataFormatoSQL ( $dataini ) );
	$data2 = new DateTime ( getDataFormatoSQL ( $datafim ) );
	
	$intervalo = $data1->diff ( $data2 );
	
	$ano = $intervalo->y;
	$mes = $intervalo->m;
	$dia = $intervalo->d;
	
	$fator = 1;
	if ($data1 > $data2) {
		$fator = - 1;
	}
	
	$retorno = 0;
	if ($ano != null)
		$retorno = abs ( $ano ) * 365;
	if ($mes != null)
		$retorno = $retorno + (abs ( $mes ) * 30);
	if ($dia != null)
		$retorno = $retorno + abs ( $dia );
	
	// echo "Intervalo Ã© de {$intervalo->y} anos, {$intervalo->m} meses e {$intervalo->d} dias";
	
	return $retorno * $fator;
}
/*
 * function getQtdMesesEntreDatas($data1, $data2) {
 * $data1 = getData($data1);
 * $data2 = getData($data2);
 *
 * $arr = explode ( '/', $data1 );
 * $arr2 = explode ( '/', $data2 );
 *
 * $dia1 = $arr [0];
 *
 * $mes1 = $arr [1];
 *
 * $ano1 = $arr [2];
 *
 * $dia2 = $arr2 [0];
 *
 * $mes2 = $arr2 [1];
 *
 * $ano2 = $arr2 [2];
 *
 * $a1 = ($ano2 - $ano1) * 12;
 *
 * $m1 = ($mes2 - $mes1) + 1;
 *
 * $m3 = ($m1 + $a1);
 *
 * $data1 . " $data2 $a1 $m1";
 *
 * return $m3;
 * }
 */
function getQtdMesesEntreDatas($data1, $data2) {
	$data1 = getDataFormatoSQL($data1);
	$data2 = getDataFormatoSQL($data2);
	//echoo($data1);
	//echoo($data2);
	
	//RETURN;
		
	$data_nascimento = $data1;
	$data_acompanhamento_calculo = $data2;
		
	$date = new DateTime ( $data_nascimento ); // Data de Nascimento
	$idade_acompanhamento = $date->diff ( new DateTime ( $data_acompanhamento_calculo ) ); // Data do Acompanhamento
	$idade_acompanhamento_mostra_anos = $idade_acompanhamento->format ( '%Y' ) * 12;
	$idade_acompanhamento_mostra_meses = $idade_acompanhamento->format ( '%m' );
	
	$total_meses = $idade_acompanhamento_mostra_anos + $idade_acompanhamento_mostra_meses;
	
	//echo $total_meses;
	return $total_meses;
}
function somarOuSubtrairDiasNaData($dataHTML, $count_days, $operacao = "+") {
	$dataHTML = getData ( $dataHTML );
	$dataHTML = str_replace ( "/", "-", $dataHTML );
	return gmdate ( 'd/m/Y', strtotime ( $operacao . $count_days . ' day', strtotime ( $dataHTML ) ) );
}
function somarOuSubtrairDiasUteisNaData($str_data, $int_qtd_dias_somar = 7, $operacao = "+") {
	$str_data = substr ( $str_data, 0, 10 );
	if (preg_match ( "@/@", $str_data ) == 1) {
		$str_data = implode ( "-", array_reverse ( explode ( "/", $str_data ) ) );
	}
	$array_data = explode ( "-", $str_data );
	$count_days = 0;
	$int_qtd_dias_uteis = 0;
	while ( $int_qtd_dias_uteis < $int_qtd_dias_somar ) {
		$count_days ++;
		$dtAcomparar = gmdate ( 'd/m/Y', strtotime ( $operacao . $count_days . ' day', strtotime ( $str_data ) ) );
		$dias_da_semana = gmdate ( 'w', strtotime ( $operacao . $count_days . ' day', mktime ( 0, 0, 0, $array_data [1], $array_data [2], $array_data [0] ) ) );
		// echo $dtAcomparar . "<br>";
		// if ( ( $dias_da_semana = gmdate('w', strtotime('+'.$count_days.' day', mktime(0, 0, 0, $array_data[1], $array_data[2], $array_data[0]))) ) != '0'
		if ($dias_da_semana != '0' && $dias_da_semana != '6' && ! isFeriado ( $dtAcomparar )) {
			
			$int_qtd_dias_uteis ++;
		}
	}
	return gmdate ( 'd/m/Y', strtotime ( $operacao . $count_days . ' day', strtotime ( $str_data ) ) );
}
function somarDiasUteisNaData($str_data, $int_qtd_dias_somar = 7) {
	return somarOuSubtrairDiasUteisNaData ( $str_data, $int_qtd_dias_somar, "+" );
}
function isFeriado($data) {
	if ($data == null || $data == "")
		throw new excecaoGenerica ( "Data inválida || verificacao feriado" );
	
	$data = str_replace ( "/", "-", $data );
	$acomparar = date ( 'd/m', strtotime ( $data ) );
	
	// echo $acomparar;
	
	return in_array ( $acomparar, dominioFeriados::getColecao () );
}
function getAnoHoje() {
	return date ( 'Y' );
}
