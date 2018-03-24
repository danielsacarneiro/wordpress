<?php
include_once(caminho_util."dominio.class.php");
include_once("dominioTpParametroFonte.php");

  Class dominioTpFonte extends dominio{

  	static $DS_APOSTILA = "Apostila";
  	static $DS_JULGADOS = "Julgados";
  	static $DS_LEI_SECA= "Lei seca";
  	static $DS_LIVRO = "Livro";
  	static $DS_QUESTOES = "Questões";
  	static $DS_VIDEO_AULAS = "Vídeo aula";

  	static $CD_APOSTILA = 1;
  	static $CD_JULGADOS = 2;
  	static $CD_LEI_SECA= 3;
  	static $CD_LIVRO = 4;
  	static $CD_QUESTOES = 5;
  	static $CD_VIDEO_AULAS = 6;
  	
// ...............................................................
// Construtor
    function __construct () {        
		$this->colecao = self::getColecao();
	}
	
	static function getColecao() {
		return array (
				self::$CD_APOSTILA=> self::$DS_APOSTILA,
				self::$CD_JULGADOS=> self::$DS_JULGADOS,
				self::$CD_LEI_SECA=> self::$DS_LEI_SECA,
				self::$CD_LIVRO=> self::$DS_LIVRO,
				self::$CD_QUESTOES=> self::$DS_QUESTOES,
				self::$CD_VIDEO_AULAS=> self::$DS_VIDEO_AULAS,
				
		);
	}
	
	static function getColecaoPorTpParametro() {
		return array (
				self::$CD_APOSTILA=> dominioTpParametroFonte::$CD_PAGINA,
				self::$CD_JULGADOS=> dominioTpParametroFonte::$CD_PAGINA,
				self::$CD_LIVRO=> dominioTpParametroFonte::$CD_PAGINA,
				self::$CD_LEI_SECA=> dominioTpParametroFonte::$CD_ARTIGO,
				//self::$CD_QUESTOES=> self::$DS_QUESTOES,
				self::$CD_VIDEO_AULAS=> dominioTpParametroFonte::$CD_AULA,
				
		);
	}

	static function getTpParametroFontePorTpFonte($tpFonte) {
		return static::getColecaoPorTpParametro()[$tpFonte];
	}
	
	//sao fontes que por si so ja se definem (ja se sabe que a lei seca do CPC eh o proprio CPC)
	/**
	 * relacao tpFonte x tpParametrofonte
	 * @return number[]
	 */
	static function getColecaoFonteAutonoma() {
		return array (
				self::$CD_LEI_SECA=> dominioTpParametroFonte::$CD_ARTIGO,
				self::$CD_APOSTILA => dominioTpParametroFonte::$CD_PAGINA,
				self::$CD_QUESTOES => dominioTpParametroFonte::$CD_NENHUM,
				//self::$CD_QUESTOES=> self::$DS_QUESTOES,
				self::$CD_VIDEO_AULAS=> dominioTpParametroFonte::$CD_AULA, //???
		);
	}
	
}
?>