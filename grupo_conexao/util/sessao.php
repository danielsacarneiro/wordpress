<?php
/*
 Use the static method getInstance to get the object.
 */
class Session
{
	const SESSION_STARTED = TRUE;
	const SESSION_NOT_STARTED = FALSE;
	
	// The state of the session
	private $sessionState = self::SESSION_NOT_STARTED;
	
	// THE only instance of the class
	private static $instance;
	
	
	private function __construct() {}
	
	
	/**
	 *    Returns THE instance of 'Session'.
	 *    The session is automatically initialized if it wasn't.
	 *
	 *    @return    object
	 **/
	
	public static function getInstance()
	{
		if ( !isset(self::$instance))
		{
			self::$instance = new self;
		}
		
		self::$instance->startSession();
		
		return self::$instance;
	}
	
	
	/**
	 *    (Re)starts the session.
	 *
	 *    @return    bool    TRUE if the session has been initialized, else FALSE.
	 **/
	
	public function startSession()
	{
		if ( $this->sessionState == self::SESSION_NOT_STARTED )
		{
			$this->sessionState = session_start();
		}
		
		return $this->sessionState;
	}
	
	
	/**
	 *    Stores datas in the session.
	 *    Example: $instance->foo = 'bar';
	 *
	 *    @param    name    Name of the datas.
	 *    @param    value    Your datas.
	 *    @return    void
	 **/
	
	public function __set( $name , $value )
	{
		$_SESSION[$name] = $value;
	}
	
	
	/**
	 *    Gets datas from the session.
	 *    Example: echo $instance->foo;
	 *
	 *    @param    name    Name of the datas to get.
	 *    @return    mixed    Datas stored in session.
	 **/
	
	public function __get( $name )
	{
		if ( isset($_SESSION[$name]))
		{
			return $_SESSION[$name];
		}
	}
	
	
	public function __isset( $name )
	{
		return isset($_SESSION[$name]);
	}
	
	
	public function __unset( $name )
	{
		unset( $_SESSION[$name] );
	}
	
	
	/**
	 *    Destroys the current session.
	 *
	 *    @return    bool    TRUE is session has been deleted, else FALSE.
	 **/
	
	public function destroy()
	{
		if ( $this->sessionState == self::SESSION_STARTED )
		{
			$this->sessionState = !session_destroy();
			unset( $_SESSION );
			
			return !$this->sessionState;
		}
		
		return FALSE;
	}
}
function iniciarSessao() {
	$sessao = Session::getInstance();
}
 
function putObjetoSessao($ID, $voEntidade) {
	$sessao = Session::getInstance();
	$sessao->__set($ID,$voEntidade);	
}
function existeObjetoSessao($ID) {
	$sessao = Session::getInstance();	
	return $sessao->__isset($ID) && $sessao->__get($ID) != null;
}

function removeObjetoSessao($ID) {
	$sessao = Session::getInstance();
	$sessao->__unset($ID);
}

function getObjetoSessao($ID, $levantarExcecaoSeObjetoInexistente = false) {	
	$sessao = Session::getInstance();
	$objeto = null;
	
	if (existeObjetoSessao($ID)) {
		//echo "existe objeto $ID <br>";
		$objeto = $sessao->__get($ID);		
		//var_dump($objeto);
		
	} else if ($levantarExcecaoSeObjetoInexistente) {
		throw new excecaoObjetoSessaoInexistente ( $ID );
	}
	
	$isUsarSessao = @$_POST ["utilizarSessao"] != "N";
	if (! $isUsarSessao) {
		$objeto = null;
		//echo "removendo objeto $ID <br>";
		removeObjetoSessao ( $ID );
	}
	
	return $objeto;
}
/*function iniciarSessao() {
	//session_name ( md5 ( 'sal' . $_SERVER ['REMOTE_ADDR'] . 'sal' . $_SERVER ['HTTP_USER_AGENT'] . 'sal' ) );	
	//session_start ();
}
function putObjetoSessao($ID, $voEntidade, $iniciarSessao = true) {
	if ($iniciarSessao) {
		iniciarSessao ();
	}
	$_SESSION [$ID] = $voEntidade;
}
function existeObjetoSessao($ID, $iniciarSessao = true) {
	if($iniciarSessao){
		iniciarSessao ();
	}
	return isset ( $_SESSION [$ID] ) && $_SESSION [$ID] != null;
}

function getObjetoSessao($ID, $levantarExcecaoSeObjetoInexistente = false, $iniciarSessao = true) {
	if($iniciarSessao){
		iniciarSessao ();
	}
	$objeto = null;
	
	if (existeObjetoSessao($ID, false)) {
		$objeto = $_SESSION [$ID];
	} else if ($levantarExcecaoSeObjetoInexistente) {
		throw new excecaoObjetoSessaoInexistente ( $ID );
	}
	
	$isUsarSessao = @$_POST ["utilizarSessao"] != "N";
	if (! $isUsarSessao) {
		$objeto = null;
		removeObjetoSessao ( $ID );
	}
	
	return $objeto;
}
function removeObjetoSessao($ID) {
	iniciarSessao ();
	unset ( $_SESSION [$ID] );
}*/
?>