<?php

// .................................................................................................................
// Classe db
// Faz a conexao com o banco: responsavel pelas tarefas realizadas atraves do banco de dados
class db {
	var $id_conexao;
	var $resultado;
	private $contadorTransacao; 
	
	// ...............................................................
	// Construtor
	function db() {
		$this->id_conexao = "";
		$this->resultado = "";
		
		$this->contadorTransacao= 0;
	}
	
	// ...............................................................
	// Fun��es ( Propriedades e m�todos da classe )
	
	/**
	 * *********************************************************************
	 */
	// param:$db, $login, $senha, $odbc, $driver, $servidor
	// return : $id_conexao
	// retorna a identidade da conexao com o banco de dados especificado
	Function abrirConexao($db, $login, $senha, $odbc, $driver, $servidor) {
		try {
			// $this->id_conexao = mysqli_connect ($servidor, $login, $senha );
			$this->id_conexao = new mysqli ( $servidor, $login, $senha );
			$this->id_conexao->set_charset ( 'utf8' );
			
			// levanta erro se for o caso de nao conseguir a conexao
			if (mysqli_connect_errno ())
				trigger_error ( mysqli_connect_error () );
			
			// if ($this->id_conexao){
			mysqli_select_db ( $this->id_conexao, $db );
		} catch ( Exception $e ) {
			$this->levantaExcecaoGenerica ( "ERRO AO ABRIR CONEXAO COM O BANCO", $e );
		}
		
		return $this->id_conexao;
		/*
		 * }
		 * else
		 * echo ("Erro ao conectar ao banco!");
		 */
	}
	
	/**
	 * *********************************************************************
	 */
	// destroi a conexao com o bando de dados
	Function fecharConexao() {
		try {
			mysqli_close ( $this->id_conexao );
		} catch ( Exception $e ) {
			$this->levantaExcecaoGenerica ( "ERRO AO FECHAR CONEXAO COM O BANCO", $e );
		}
		
		// $mysqli->close();
	}
	
	/**
	 * *********************************************************************
	 */
	// param:$query
	// return: $resultado, a consulta realizada
	// executa a query passada como parametro no banco, serve para as transacoes de consulta apenas
	Function consultar($query) {
		
		// echo $query;
		try {
			$this->limpaResultado ();
			$dados = mysqli_query ( $this->id_conexao, $query );
			$numrows = mysqli_num_rows ( $dados );
			if ($numrows > 0) {
				$nf = mysqli_num_fields ( $dados );
				for($x = 0; $row = mysqli_fetch_row ( $dados ); $x ++) {
					for($w = 0; $w < $nf; $w ++) {
						$finfo = mysqli_fetch_field_direct ( $dados, $w );
						// MARRETA
						$item = $row [$w];
						$item = utf8_decode ( $item );
						// $item = utf8_encode($item);
						$this->resultado [$x] [$finfo->name] = $item;
					}
				}
			} else
				$this->resultado = "";
		} catch ( Exception $e ) {
			// retira o pulo de linha se nao dah pau na impressao
			$query = str_replace ( constantes::$CD_NOVA_LINHA, "", $query );
			
			/*
			 * $MSG = "Query: " . $query . " | ERROR: " . $e->getMessage ();
			 * throw new Exception ( $MSG );
			 */
			$this->levantaExcecaoGenerica ( $query, $e );
		}
		
		return $this->resultado;
	}
	function levantaExcecaoGenerica($query, $ex = null) {
		//$msg = "<br>----ERROR------:<br>" . mysqli_error ( $this->id_conexao ) . "<br>";
		$msg = "$msg. Query: $query";
		
		if ($ex != null) {
			$msg .= "<br>". $ex->getMessage ();
			$code = $ex->getCode ();
		}
		
		$excecao = new excecaoGenerica ( $msg, $code ,$ex);
		if($code == excecaoGenerica::$CD_EXCECAO_CHAVE_DUPLICADA){
			$excecao = new excecaoChaveDuplicada($msg, $ex);
		}
		
		throw $excecao;
	}
	
	/**
	 * *********************************************************************
	 */
	// param:$query
	// return: $resultado, booleano que indica se a query foi realizada com sucesso ou nao
	// executa a query de atualizacao,insercao ou delecao passada como parametro no banco
	Function atualizarImportacao($query) {
		return $this->atualizarDecode ( $query, false );
	}
	Function atualizar($query) {
		return $this->atualizarDecode ( $query, true );
	}
	Function atualizarDecode($query, $iscodificar) {
		$this->limpaResultado ();
		// MARRETA
		// utf8_encode usado para incluir com acentos no banco
		if ($iscodificar)
			$query = utf8_encode ( $query );
		
		try {
			$dados = mysqli_query ( $this->id_conexao, $query );
		} catch ( Exception $ex ) {
			$this->levantaExcecaoGenerica ( $query, $ex );
		}
		
		// MARRETA: volta ao padrao normal
		if ($iscodificar)
			$query = utf8_decode ( $query );
		
		// Verifica se ocorreu algum erro durante a execu��o da query.
		if (strlen ( trim ( mysqli_error ( $this->id_conexao ) ) ) == 0)
			$resultado = 1; // operacao efetuada com sucesso
		else {
			$resultado = 0;
			$this->levantaExcecaoGenerica ( $query );
		}
		
		return $resultado;
	}
	
	/**
	 * *********************************************************************
	 */
	// limpa o campo resultado
	function limpaResultado() {
		$this->resultado = "";
	}
	
	/**
	 * *********************************************************************
	 */
	// Fun��o usada para retornar os campos da tabela pessoa
	// param:$query
	// return: $resultado, a consulta realizada
	// executa a query passada como parametro no banco, serve para as transacoes de consulta apenas
	Function campos($tabela) {
		$this->limpaResultado ();
		$res = mysqli_list_fields ( $this->id_conexao, $tabela );
		$numFields = mysqli_num_fields ( $res );
		for($i = 0; $i < $numFields; $i ++) {
			$finfo = mysqli_fetch_field_direct ( $res, $i );
			// $this->resultado[$x][$finfo->name] = $row[$w];
			$this->resultado [$i] = [ 
					$finfo->name 
			];
			// $this->resultado[$i] = mysqli_field_name($res,$i);
		}
		
		return $this->resultado;
	}
	
	// transacoes multiplas
	function exemploTransacoesmultriplas() {
		// Start transaction
		$mysqli->autocommit ( FALSE );
		$mysqli->query ( 'UPDATE `table` SET `col`=2' );
		$mysqli->query ( 'UPDATE `table1` SET `col1`=3;' );
		$mysqli->commit ();
		// End transaction
	}
	function isControleTransacaoOK() {
		return $this->contadorTransacao == 1;
		
	}
	function retiraAutoCommit() {
		// Start transaction
		$this->contadorTransacao = $this->contadorTransacao +1;
		
		if($this->isControleTransacaoOK()){		
			mysqli_autocommit ( $this->id_conexao, FALSE );			
		}
	}
	function commit() {
		// End transaction
		if($this->isControleTransacaoOK()){
			mysqli_commit ( $this->id_conexao );
		}else{
			$this->contadorTransacao = $this->contadorTransacao - 1;
		}
	}
	function rollback() {
		mysqli_rollback ( $this->id_conexao );
	}
}

/**
 * ******************************* Fim da classe DB ******************************
 */
?>