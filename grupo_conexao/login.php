<?php
include_once ("../wp-config.php");
include_once ("util/constantes.class.php");

$funcao = @$_GET["funcao"];
$nmSistema = @$_GET[constantes::$ID_REQ_NMSISTEMA];

/*$url = $_SERVER['SCRIPT_NAME'];
$url = "http://sf300451/". dirname($url) . "/";*/

$url = "index.php";
if($nmSistema!= null){
	//para o caso do sistema ser interno
	$url = "$nmSistema/funcoes/menu/index.php";	
}

//redireciona o user para o login se n tiver logado
if($funcao == "O"){
    wp_logout();
    //wp_safe_redirect($url); 
}else{
    if(!is_user_logged_in()){
        echo "usuario nao logado <br>";
        auth_redirect();
    }else{
        echo "usuario logado <br>";
        //wp_safe_redirect($url); 
    }
}

wp_safe_redirect($url); 
//print_r($_SERVER);

?>