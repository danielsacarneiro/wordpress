ALTER DATABASE unct CHARACTER SET utf8 COLLATE utf8_general_ci;

drop table usuario_info;
CREATE TABLE usuario_info (
    ID INT NOT NULL, 

    dh_inclusao TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
    dh_ultima_alt TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL,
    cd_usuario_incl INT,
    cd_usuario_ultalt INT,    
    CONSTRAINT pk PRIMARY KEY (ID)
);

drop table usuario_setor;
CREATE TABLE usuario_setor (
    ID INT NOT NULL,
    usu_cd_setor INT NOT NULL,
    
    dh_inclusao TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
    cd_usuario_incl INT,
    CONSTRAINT pk PRIMARY KEY (ID,usu_cd_setor)
);


CREATE USER 'grupoconexao'@'localhost' IDENTIFIED BY 'grupoconexao123';
GRANT ALL PRIVILEGES ON * . * TO 'grupoconexao'@'localhost';
FLUSH PRIVILEGES;

-- exemplo de permissoes
/*
LEMBRAR DE SEMPRE USAR O FLUSH PRA AUALIZAR AS PERMISSOES

ALL PRIVILEGES- como vimos anteriormente, isso daria a um usuário do MySQL todo o acesso a uma determinada base de dados (ou se nenhuma base de dados for selecionada, todo o sistema)
CREATE- permite criar novas tabelas ou bases de dados
DROP- permite deletar tableas ou bases de dados
DELETE- permite deletar linhas das tabelas
INSERT- permite inserir linhas nas tabelas
SELECT- permite utilizar o comando Select para ler bases de dados
UPDATE- permite atualizar linhas das tabelas
GRANT OPTION- permite conceder ou revogar privilégios de outros usuários 

PAra dar permissao a um usuario especifico
GRANT [tipo de permissão] ON [nome da base de dados].[nome da tabela] TO ‘[nome do usuário]’@'localhost’;

PAra remover permissao
REVOKE [tipo de permissão] ON [nome da base de dados].[nome da tabela] FROM ‘[nome do usuário]’@‘localhost’;

PAra remover usuario
DROP USER ‘demo’@‘localhost’;
*/