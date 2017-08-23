<?php
include_once(caminho_util."dominio.class.php");

Class dominioEstados extends dominio{
	
	static $CD_ESTADO_AC="AC";
	static $CD_ESTADO_AL="AL";
	static $CD_ESTADO_AP="AP";
	static $CD_ESTADO_AM="AM";
	static $CD_ESTADO_BA="BA";
	static $CD_ESTADO_CE="CE";
	static $CD_ESTADO_DF="DF";
	static $CD_ESTADO_ES="ES";
	static $CD_ESTADO_GO="GO";
	static $CD_ESTADO_MA="MA";
	static $CD_ESTADO_MT="MT";
	static $CD_ESTADO_MS="MS";
	static $CD_ESTADO_MG="MG";
	static $CD_ESTADO_PA="PA";
	static $CD_ESTADO_PB="PB";
	static $CD_ESTADO_PR="PR";
	static $CD_ESTADO_PE="PE";
	static $CD_ESTADO_PI="PI";
	static $CD_ESTADO_RJ="RJ";
	static $CD_ESTADO_RN="RN";
	static $CD_ESTADO_RS="RS";
	static $CD_ESTADO_RO="RO";
	static $CD_ESTADO_RR="RR";
	static $CD_ESTADO_SC="SC";
	static $CD_ESTADO_SP="SP";
	static $CD_ESTADO_SE="SE";
	static $CD_ESTADO_TO="TO";
	
	// ...............................................................
	// Construtor
	function __construct () {
		$this->colecao = self::getColecao();
		//ksort($this->colecao);
	}
	
	static function getColecao(){
		$retorno = array(
				self::$CD_ESTADO_AC	=> self::$CD_ESTADO_AC,
				self::$CD_ESTADO_AL	=> self::$CD_ESTADO_AL,
				self::$CD_ESTADO_AP	=> self::$CD_ESTADO_AP,
				self::$CD_ESTADO_AM	=> self::$CD_ESTADO_AM,
				self::$CD_ESTADO_BA	=> self::$CD_ESTADO_BA,
				self::$CD_ESTADO_CE	=> self::$CD_ESTADO_CE,
				self::$CD_ESTADO_DF	=> self::$CD_ESTADO_DF,
				self::$CD_ESTADO_ES	=> self::$CD_ESTADO_ES,
				self::$CD_ESTADO_GO	=> self::$CD_ESTADO_GO,
				self::$CD_ESTADO_MA	=> self::$CD_ESTADO_MA,
				self::$CD_ESTADO_MT	=> self::$CD_ESTADO_MT,
				self::$CD_ESTADO_MS	=> self::$CD_ESTADO_MS,
				self::$CD_ESTADO_MG	=> self::$CD_ESTADO_MG,
				self::$CD_ESTADO_PA	=> self::$CD_ESTADO_PA,
				self::$CD_ESTADO_PB	=> self::$CD_ESTADO_PB,
				self::$CD_ESTADO_PR	=> self::$CD_ESTADO_PR,
				self::$CD_ESTADO_PE	=> self::$CD_ESTADO_PE,
				self::$CD_ESTADO_PI	=> self::$CD_ESTADO_PI,
				self::$CD_ESTADO_RJ	=> self::$CD_ESTADO_RJ,
				self::$CD_ESTADO_RN	=> self::$CD_ESTADO_RN,
				self::$CD_ESTADO_RS	=> self::$CD_ESTADO_RS,
				self::$CD_ESTADO_RO	=> self::$CD_ESTADO_RO,
				self::$CD_ESTADO_RR	=> self::$CD_ESTADO_RR,
				self::$CD_ESTADO_SC	=> self::$CD_ESTADO_SC,
				self::$CD_ESTADO_SP	=> self::$CD_ESTADO_SP,
				self::$CD_ESTADO_SE	=> self::$CD_ESTADO_SE,
				self::$CD_ESTADO_TO	=> self::$CD_ESTADO_TO
		);
		
		//uksort($retorno, 'strnatcmp');
		
		return $retorno;
	}
	
}
?>