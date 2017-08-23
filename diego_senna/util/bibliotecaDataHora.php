<?php
include_once ("mensagens.class.php");
function getDataHora($dataSQL) {
	return getDataHoraParam ( $dataSQL, true );
}
function getData($dataSQL) {
	return getDataHoraParam ( $dataSQL, false );
}
function getDataHoraParam($dataSQL, $temHora) {
	$retorno = null;
	if ($dataSQL != null) {
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
	if ($data != null)
		$retorno = date ( "Y/m/d", strtotime ( $data ) );
	
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
	
	// echo "Intervalo é de {$intervalo->y} anos, {$intervalo->m} meses e {$intervalo->d} dias";
	
	return $retorno * $fator;
}
function getQtdMesesEntreDatas($data1, $data2) {
	$data1 = getData($data1);
	$data2 = getData($data2);
	
	$arr = explode ( '/', $data1 );
	$arr2 = explode ( '/', $data2 );
		
	$dia1 = $arr [0];
	
	$mes1 = $arr [1];
	
	$ano1 = $arr [2];
	
	$dia2 = $arr2 [0];
	
	$mes2 = $arr2 [1];
	
	$ano2 = $arr2 [2];
	
	$a1 = ($ano2 - $ano1) * 12;
	
	$m1 = ($mes2 - $mes1) + 1;
	
	$m3 = ($m1 + $a1);
	
	$data1 . " $data2 $a1 $m1";
	
	return $m3;
}
function getAnoHoje() {
	return date ( 'Y' );
}

?>