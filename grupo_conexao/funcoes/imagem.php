<?php
include_once("../config_lib.php");

include_once(caminho_util."bibliotecaHTML.php");
inicio();

//$nmAtrBlobImagem = @$_GET["nmAtributoImagem"];
//$vo = getObjetoSessao("vo");



$vopessoa = new vopessoa();
$vopessoa->cd = 3;
$db = new dbpessoa(); 
$colecao = $db->consultarPorChave($vopessoa, false);
$vopessoa->getDadosBanco($colecao);

//Header( "Content-type: image/jpg");
echo $vopessoa->foto;

//echo "D:\Meus Documentos\Minhas imagens\porto.jpg";

?>