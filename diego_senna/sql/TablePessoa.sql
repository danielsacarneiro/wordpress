ALTER DATABASE unct CHARACTER SET utf8 COLLATE utf8_general_ci;

-- ALTER TABLE pessoa DROP FOREIGN KEY fk_pessoa_usuario;
drop table pessoa_vinculo;
drop table pessoa;
drop table pessoa_hist;
CREATE TABLE pessoa (
	pe_cd INT NOT NULL AUTO_INCREMENT,
    pe_nome VARCHAR(150),
	pe_responsavel VARCHAR(150),
    pe_doc_cpf VARCHAR(30),
	pe_doc_rg VARCHAR(30),
    pe_dtnascimento DATE,
    pe_tel VARCHAR(100),
    pe_tel_wapp VARCHAR(15),
    pe_email VARCHAR(100),
	pe_endereco VARCHAR(300),
	pe_bairro VARCHAR(50),
    pe_cidade VARCHAR(50),
    pe_uf CHAR(2),
    pe_obs TEXT,
    pe_in_todosdocs CHAR(2) NOT NULL,
    pe_foto VARCHAR(155),
    
    dh_inclusao TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
    dh_ultima_alt TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL,
    cd_usuario_incl INT,
    cd_usuario_ultalt INT,
    in_desativado CHAR(1) DEFAULT 'N' NOT NULL,
        
    CONSTRAINT pk PRIMARY KEY (pe_cd)
);    
-- ALTER TABLE pessoa ADD UNIQUE KEY chave_doc_pessoa (pe_doc_cpf); 
-- ALTER TABLE pessoa CHANGE COLUMN pe_tel pe_tel VARCHAR(100) NULL DEFAULT NULL ;
    
-- ALTER TABLE pessoa DROP FOREIGN KEY fk_pessoa_usuario;

CREATE TABLE pessoa_hist (
	hist INT NOT NULL AUTO_INCREMENT,
	pe_cd INT NOT NULL,
    pe_nome VARCHAR(150),
	pe_responsavel VARCHAR(150),
    pe_doc_cpf VARCHAR(30),
	pe_doc_rg VARCHAR(30),
    pe_dtnascimento DATE,
    pe_tel VARCHAR(100),
	pe_tel_wapp VARCHAR(15),
    pe_email VARCHAR(100),
	pe_endereco VARCHAR(300),
	pe_bairro VARCHAR(30),
    pe_cidade VARCHAR(50),
    pe_uf CHAR(2),    
    pe_obs TEXT,
    pe_in_todosdocs CHAR(2) DEFAULT 'S',
    pe_foto VARCHAR(155),
    
    dh_inclusao TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
    dh_ultima_alt TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL,
    cd_usuario_incl INT,
    cd_usuario_ultalt INT,
    in_desativado CHAR(1) NOT NULL,    
    
	dh_operacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
    cd_usuario_operacao INT,    
        
    CONSTRAINT pk PRIMARY KEY (hist)
);

CREATE TABLE pessoa_vinculo (
	vi_cd INT NOT NULL,
    pe_cd INT NOT NULL,
    dh_ultima_alt TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL,
    cd_usuario_ultalt INT,

        
    CONSTRAINT pk PRIMARY KEY (vi_cd, pe_cd)
);
ALTER TABLE pessoa_vinculo ADD CONSTRAINT fk_pessoa_vinculo FOREIGN KEY ( pe_cd ) REFERENCES pessoa (pe_cd) 
ON DELETE RESTRICT
ON UPDATE RESTRICT;
    
-- ALTER TABLE pessoa_vinculo DROP FOREIGN KEY fk_pessoa_vinculo; 

    
 show create table pessoa; 
    
/** INCLUSAO DAS CONTRATADAS */
DELIMITER $$
DROP PROCEDURE IF EXISTS importarContratada $$
CREATE PROCEDURE importarContratada()
BEGIN

  DECLARE done INTEGER DEFAULT 0;
  DECLARE nome VARCHAR(150);
  DECLARE doc VARCHAR(30);
  -- cdPessoa deve conter o cdPessoa da ultima pessoa incluida
  DECLARE cdPessoa INT DEFAULT 444;

  DECLARE cTabela CURSOR FOR 
	  select ct_contratada, ct_doc_contratada from contrato
		where ct_doc_contratada is not null		
        and pe_cd_contratada is null -- pega apenas as contratadas dos contratos que ainda nao tem relacao
        group by replace(replace(replace(ct_doc_contratada, ".", ""), "/", ""), "-","");
        -- retira os pontos, barras e tracos para evitar duplicacoes
	
  DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = 1;  
  -- SELECT MAX(pe_cd)+1 INTO cdPessoa FROM pessoa; 
  
  -- DELETE FROM pessoa_vinculo WHERE vi_cd = 2;
  
  OPEN cTabela;  
  REPEAT
  -- read_loop: LOOP
    #aqui você pega os valores do "select", para mais campos vocÇe pode fazer assim:
    #cTabela INTO c1, c2, c3, ..., cn
    FETCH cTabela INTO nome,doc;
		IF NOT done THEN
		
        set cdPessoa = cdPessoa +1;
		INSERT INTO pessoa  (pe_cd, pe_nome, pe_doc)  values (cdPessoa, nome, doc); 
        INSERT INTO pessoa_vinculo (vi_cd, pe_cd)  values (2, cdPessoa); 
        

		END IF;
  UNTIL done END REPEAT;
  CLOSE cTabela;
  
END $$
DELIMITER ;
call importarContratada();
/** INCLUSAO DAS CONTRATADAS */

UPDATE pessoa SET 
pe_nome = replace(replace(replace(pe_nome,'“','"'),'”','"'),'–','-')
WHERE pe_cd = 305;
