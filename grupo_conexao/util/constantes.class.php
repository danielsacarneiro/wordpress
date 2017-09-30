<?php
//header ('Content-type: text/html; charset=ISO-8859-1');

  Class constantes {	
	
	static $nomeSistema = "e@CONEX�O";
	static $tituloSistema = "GRUPO EDUCACIONAL CONEX�O";

	static $DATA_INICIO = "01/01/1900";
	static $DATA_FIM = "01/01/2099";
	
	static $qts_dias_ALERTA_VERMELHO = 20;
	static $qts_dias_ALERTA_AMARELO = 45;
    
    static $CD_TIPO_CONTRATO  = "C";
    static $CD_TIPO_CONVENIO  = "V";
    static $CD_TIPO_PROFISCO  = "P";

    static $DS_TIPO_CONTRATO  = "C-SAFI";
    static $DS_TIPO_CONVENIO  = "CV-SAFI";
    static $DS_TIPO_PROFISCO  = "C-PROFISCO";
    
    static $CD_FUNCAO_EXCLUIR = "E";
    static $CD_FUNCAO_HISTORICO = "H";
    static $CD_FUNCAO_INCLUIR = "I";
    static $CD_FUNCAO_ALTERAR = "A";
    static $CD_FUNCAO_DETALHAR = "D";
    static $CD_FUNCAO_CANCELAR = "C";
    static $CD_FUNCAO_TODAS = "T";
    
    static $ID_REQ_LUPA = "lupa";
    static $ID_REQ_MULTISELECAO = "multiSelecao";
    static $ID_REQ_ID_SISTEMA = "ID_REQ_ID_SISTEMA";
    
    static $DS_SIM  = "Sim";
    static $DS_NAO  = "N�o";
    static $DS_OPCAO_TODOS = "Todos";
     
    static $CD_SIM  = "S";
    static $CD_NAO  = "N"; 
    static $CD_OPCAO_TODOS  = "op_todos";
    static $CD_OPCAO_VAZIO = "op_vazio";
    
    static $CD_ORDEM_CRESCENTE = "ASC";
    static $CD_ORDEM_DECRESCENTE = "DESC";
    
    static $CD_OPCAO_OR = "OR";
    static $CD_OPCAO_AND = "AND";
    static $DS_OPCAO_OR = "OU";
    static $DS_OPCAO_AND = "E";
    
    static $CD_CLASS_CAMPO_OBRIGATORIO = "campoobrigatorio";
    static $CD_CLASS_CAMPO_NAO_OBRIGATORIO = "camponaoobrigatorio";
    static $CD_CLASS_CAMPO_READONLY = "camporeadonly";
    static $CD_NOVA_LINHA = "\n";
    
    static $TAMANHO_CODIGOS = 5;
    static $TAMANHO_CODIGOS_SAFI = 3;
    static $TAMANHO_MOEDA  = 11;
    static $CD_CAMPO_SEPARADOR = "*";
    static $CD_CAMPO_SEPARADOR_ARRAY = ",";
    static $CD_CAMPO_SUBSTITUICAO = "[[*]]";
    static $CD_CAMPO_NULO = "null";
    static $ID_REQ_SESSAO_VO = "vo";
       
}
?>