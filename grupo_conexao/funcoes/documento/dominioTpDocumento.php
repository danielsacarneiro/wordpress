<?php
include_once(caminho_util."dominio.class.php");

  Class dominioTpDocumento extends dominio{
  	
  	static $ENDERECO_DRIVE = "\\\\sf044836\\_dag$";
  	//static $ENDERECO_DRIVE_HTML = "\\sf044836\\\\_dag$";
  	static $ENDERECO_DRIVE_HTML = "H:";
  	static $ENDERECO_PASTABASE = "ASSESSORIA JURÍDICA\ATJA";
  	static $ENDERECO_PASTABASE_UNCT = "UNCT";
  	
  	static $ENDERECO_PASTA_DOCUMENTOS = "Documentos";
  	  	  	
  	static $CD_TP_DOC_RG= 1;
  	static $CD_TP_DOC_CPF= 2;
  	static $CD_TP_DOC_MOTORISTA= 3;
  	static $CD_TP_DOC_COMPROV_RESIDENCIA= 4;
  	static $CD_TP_DOC_FOTO= 5;
  	 
  	static $DS_TP_DOC_RG= "RG";
  	static $DS_TP_DOC_CPF= "CPF";
  	static $DS_TP_DOC_MOTORISTA= "CNH";
  	static $DS_TP_DOC_COMPROV_RESIDENCIA= "Comprov.Residência";
  	static $DS_TP_DOC_FOTO= "Foto";
  	// ...............................................................
// Construtor
    function __construct () {        
		$this->colecao = self::getColecao();
	}
	
	static function getColecao(){
		return array(
				self::$CD_TP_DOC_RG=> self::$DS_TP_DOC_RG,
				self::$CD_TP_DOC_CPF=> self::$DS_TP_DOC_CPF,
				self::$CD_TP_DOC_MOTORISTA=> self::$DS_TP_DOC_MOTORISTA,
				self::$CD_TP_DOC_COMPROV_RESIDENCIA=> self::$DS_TP_DOC_COMPROV_RESIDENCIA,
				self::$CD_TP_DOC_FOTO=> self::$DS_TP_DOC_FOTO
				
		);
	}	
	
	static function getEnderecoPastaBase() {
		return self::$ENDERECO_DRIVE . "\\" . self::$ENDERECO_PASTABASE;
	}
	static function getEnderecoPastaBaseUNCT() {
		return self::$ENDERECO_DRIVE . "\\" . self::$ENDERECO_PASTABASE_UNCT;
	}
		
}
?>