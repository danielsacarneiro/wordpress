<?php
include_once (caminho_util . "DocumentoPessoa.php");
include_once (caminho_util . "dominioEstados.php");
include_once (caminho_lib . "voentidade.php");
class vopessoa extends voentidade {
	static $NM_DIV_FOTO = "NM_DIV_FOTO";
	static $NM_PASTA_DESTINO_FOTOS = "fotos/";
	static $nmAtrCd = "pe_cd";
	static $nmAtrNome = "pe_nome";
	static $nmAtrResponsavel = "pe_responsavel";
	static $nmAtrDocCPF = "pe_doc_cpf";
	static $nmAtrDocRG = "pe_doc_rg";
	static $nmAtrDtNascimento = "pe_dtnascimento";
	static $nmAtrTel = "pe_tel";
	static $nmAtrTelWapp = "pe_tel_wapp";
	static $nmAtrEmail = "pe_email";
	static $nmAtrEndereco = "pe_endereco";
	static $nmAtrBairro = "pe_bairro";
	static $nmAtrCidade = "pe_cidade";
	static $nmAtrUF = "pe_uf";
	static $nmAtrObservacao = "pe_obs";
	static $nmAtrFoto = "pe_foto";
	var $cd = "";
	var $nome = "";
	var $responsavel = "";
	var $email = "";
	var $tel = "";
	var $obs = "";
	var $telWapp = "";
	var $docCPF = "";
	var $docRG = "";
	var $dtNascimento = "";
	var $endereco = "";
	var $bairro = "";
	var $cidade = "";
	var $uf = "";
	var $foto = "";
	var $cdVinculo = "";
	var $dbprocesso = null;
	
	// ...............................................................
	// Funções ( Propriedades e métodos da classe )
	function __construct() {
		parent::__construct ();
		$this->temTabHistorico = true;
		
		$class = self::getNmClassProcesso ();
		$this->dbprocesso = new $class ();
	}
	public static function getTituloJSP() {
		return "PESSOA";
	}
	public static function getNmTabela() {
		return "pessoa";
	}
	public function getNmClassProcesso() {
		return "dbpessoa";
	}
	function getValoresWhereSQLChave($isHistorico) {
		$nmTabela = $this->getNmTabelaEntidade ( $isHistorico );
		$query = $nmTabela . "." . vopessoa::$nmAtrCd . "=" . $this->cd;
		// $query.= " AND ". $nmTabela . "." . vopessoa::$nmAtrCd . "=" . $this->cd;
		if ($isHistorico)
			$query .= " AND " . $nmTabela . "." . vopessoa::$nmAtrSqHist . "=" . $this->sqHist;
		
		return $query;
	}
	function getAtributosFilho() {
		$retorno = array (
				self::$nmAtrCd,
				self::$nmAtrNome,
				self::$nmAtrResponsavel,
				self::$nmAtrDocCPF,
				self::$nmAtrDocRG,
				self::$nmAtrDtNascimento,
				self::$nmAtrTel,
				self::$nmAtrTelWapp,
				self::$nmAtrEmail,
				self::$nmAtrEndereco,
				self::$nmAtrBairro,
				self::$nmAtrCidade,
				self::$nmAtrUF,
				self::$nmAtrFoto,
				self::$nmAtrObservacao 
		);
		
		return $retorno;
	}
	function getAtributosChavePrimaria() {
		$retorno = array (
				self::$nmAtrCd 
		);
		
		return $retorno;
	}
	function getDadosRegistroBanco($registrobanco) {
		
		// as colunas default de voentidade sao incluidas pelo metodo getDadosBanco do voentidade
		$this->cd = $registrobanco [vopessoa::$nmAtrCd];
		$this->nome = $registrobanco [vopessoa::$nmAtrNome];
		$this->responsavel = $registrobanco [vopessoa::$nmAtrResponsavel];
		$this->email = $registrobanco [vopessoa::$nmAtrEmail];
		$this->tel = $registrobanco [vopessoa::$nmAtrTel];
		$this->telWapp = $registrobanco [vopessoa::$nmAtrTelWapp];
		$this->obs = $registrobanco [vopessoa::$nmAtrObservacao];
		$this->docCPF = $registrobanco [vopessoa::$nmAtrDocCPF];
		$this->docRG = $registrobanco [vopessoa::$nmAtrDocRG];
		
		$this->dtNascimento = $registrobanco [vopessoa::$nmAtrDtNascimento];
		
		$this->endereco = $registrobanco [vopessoa::$nmAtrEndereco];
		$this->bairro = $registrobanco [vopessoa::$nmAtrBairro];
		$this->cidade = $registrobanco [vopessoa::$nmAtrCidade];
		$this->uf = $registrobanco [vopessoa::$nmAtrUF];
		
		$this->foto = $registrobanco [vopessoa::$nmAtrFoto];
		
		$this->cdVinculo = $registrobanco [vopessoavinculo::$nmAtrCd];
	}
	function getDadosFormulario() {
		$this->cd = @$_POST [vopessoa::$nmAtrCd];
		$this->nome = @$_POST [vopessoa::$nmAtrNome];
		$this->responsavel = @$_POST [vopessoa::$nmAtrResponsavel];
		$this->email = @$_POST [vopessoa::$nmAtrEmail];
		$this->tel = @$_POST [vopessoa::$nmAtrTel];
		$this->telWapp = @$_POST [vopessoa::$nmAtrTelWapp];
		$this->obs = @$_POST [vopessoa::$nmAtrObservacao];
		$this->docCPF = @$_POST [vopessoa::$nmAtrDocCPF];
		$this->docRG = @$_POST [vopessoa::$nmAtrDocRG];
		
		$this->dtNascimento = @$_POST [vopessoa::$nmAtrDtNascimento];
		
		$this->endereco = @$_POST [vopessoa::$nmAtrEndereco];
		$this->bairro = @$_POST [vopessoa::$nmAtrBairro];
		$this->cidade = @$_POST [vopessoa::$nmAtrCidade];
		$this->uf = @$_POST [vopessoa::$nmAtrUF];
		
		$this->setFoto( vopessoa::$nmAtrFoto );
		
		if ($this->docCPF != null) {
			$this->docCPF = documentoPessoa::getNumeroDocSemMascara ( $this->docCPF );
		}
		
		/*$this->dhUltAlteracao = @$_POST [vopessoa::$nmAtrDhUltAlteracao];
		$this->sqHist = @$_POST [vopessoa::$nmAtrSqHist];
		// usuario de ultima manutencao sempre sera o id_user
		$this->cdUsuarioUltAlteracao = id_user;*/
		
		// vinculo
		$this->cdVinculo = @$_POST [vopessoavinculo::$nmAtrCd];
	}
	function toString() {
		$retorno .= $this->cd . ",";
		$retorno .= $this->nome . ",";
		return $retorno;
	}
	function getValorChavePrimaria() {
		return $this->cd . constantes::$CD_CAMPO_SEPARADOR . $this->sqHist;
	}
	function getVOExplodeChave() {
		$chave = @$_GET ["chave"];
		$array = explode ( CAMPO_SEPARADOR, $chave );
		$this->cd = $array [0];
		$this->sqHist = $array [1];
	}
	function getMensagemComplementarTelaSucesso() {
		$retorno = $this->getMensagemComplementarTelaSucessoPadrao ( $this->getTituloJSP (), $this->cd, $this->nome );
		return $retorno;
	}
	function criaPastaFotos() {
		if (! file_exists ( self::$NM_PASTA_DESTINO_FOTOS )) {
			mkdir ( self::$NM_PASTA_DESTINO_FOTOS, 0700 );
		}
	}
	function getNmArquivoFotos($nmArquivoBase, $arquivo) {
		// exemplo de reconhecimento de extensao da imagem
		$extensao = strtolower ( substr ( $arquivo ['name'], - 4 ) );
		$nomeFinal = substr ( $nmArquivoBase, 0, 20 );
		$nomeFinal = str_replace ( " ", "_", $nomeFinal );
		
		$nomeFinal .= time () . '.jpg';
		$this->criaPastaFotos ();
		
		return $nomeFinal;
	}
	function excluirFoto() {
		$arquivo = caminho_funcoes . self::getNmTabela () . "/" . self::$NM_PASTA_DESTINO_FOTOS . $this->foto;
		if (file_exists ( $arquivo)) {
			unlink ( $arquivo);
		}
		// echo (caminho_funcoes.self::getNmTabela()."/".self::$NM_PASTA_DESTINO_FOTOS . $this->foto);
	}
	function setFoto($nmAtributoArquivo) {
		$imagem = @$_FILES [$nmAtributoArquivo];
		$retorno = null;
		
		// verifica se eh uma inclusao ou nao
		$funcao = @$_POST ["funcao"];
		$isInclusao = $funcao == constantes::$CD_FUNCAO_INCLUIR;
		
		if (isFileUploadValido($imagem)) {
			echo "tem imagem";
			var_dump($imagem);
			
			$nomeFinal = $this->getNmArquivoFotos ( $this->nome, $imagem );
			if (move_uploaded_file ( $imagem ['tmp_name'], self::$NM_PASTA_DESTINO_FOTOS . $nomeFinal )) {
				$retorno = $nomeFinal;
			}
		} else {
			echo "NAO tem imagem";
			if ($isInclusao) {
				echo "EH INCLUSAO";
				// na inclusao a foto eh obrigatoria
				throw new excecaoGenerica ( "Foto � obrigat�ria!" );
			} else {
				// eh detalhamento, basta pegar o valor da foto
				$retorno = @$_POST [vopessoa::$nmAtrFoto];
			}
		}
		
		$this->foto = $retorno;
	}
}
?>