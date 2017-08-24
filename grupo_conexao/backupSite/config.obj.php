<?php
// .................................................................................................................
// Classe classConfig

Class config {
        var $db;
        var $login;
        var $senha;
        var $odbc;
        var $driver;
        var $servidor;
				var $cDb;
          // ...............................................................
         // Construtor

Function config (){

                 $this->db        = "sistemasgep";

                 $this->login     = "grupoconexao";

                 $this->senha     = "grupoconexao123";

                 $this->odbc      = "";

                 $this->driver    = "";

                 $this->servidor  = "mysql796.umbler.com:41890";

      }
		
}

?>