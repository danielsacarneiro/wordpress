ALTER DATABASE unct CHARACTER SET utf8 COLLATE utf8_general_ci;

DROP TABLE IF EXISTS materia;
CREATE TABLE materia (
	ma_cd INT NOT NULL AUTO_INCREMENT,
    ma_ds VARCHAR(150) NOT NULL, 
    ma_valor DECIMAL(10,2),
    ma_obs VARCHAR(300),
	
    -- TEM ALTERACAO PORQUE PERMITE ALTERACAO POR CIMA
    dh_ultima_alt TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL,
    cd_usuario_ultalt INT,    
    CONSTRAINT pk PRIMARY KEY (ma_cd)
);

drop table IF EXISTS turma;
CREATE TABLE turma (
	tu_cd INT NOT NULL AUTO_INCREMENT,
    tu_ds VARCHAR(150) NOT NULL, 
    tu_valor DECIMAL(10,2),
    tu_obs VARCHAR(300),
    tu_dtinicio DATE,
    tu_dtfim DATE,
	
    dh_inclusao TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
    dh_ultima_alt TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL,
    cd_usuario_incl INT,
    cd_usuario_ultalt INT,
    in_desativado CHAR(1) NOT NULL DEFAULT 'N',

    CONSTRAINT pk PRIMARY KEY (tu_cd)
);

drop table IF EXISTS turma_hist;
CREATE TABLE turma_hist (
	hist INT NOT NULL AUTO_INCREMENT,
	
    tu_cd INT NOT NULL,
    tu_ds VARCHAR(150) NOT NULL, 
    tu_valor DECIMAL(10,2),
    tu_obs VARCHAR(300),
    tu_dtinicio DATE,
    tu_dtfim DATE,
	
    dh_inclusao TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
    dh_ultima_alt TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL,
    cd_usuario_incl INT,
    cd_usuario_ultalt INT,
    
    in_desativado CHAR(1),
	dh_operacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
    cd_usuario_operacao INT,

    CONSTRAINT pk PRIMARY KEY (hist)
);

drop table IF EXISTS pessoa_turma;
CREATE TABLE pessoa_turma (
	pe_cd INT NOT NULL,
    tu_cd INT NOT NULL,
    pt_numparcelas INT NOT NULL,
    pt_valor DECIMAL(10,2) DEFAULT NULL,
    pt_obs VARCHAR(300) DEFAULT NULL,
    
    dh_ultima_alt TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL,
    cd_usuario_ultalt INT,    
    in_desativado CHAR(1) DEFAULT 'N',
    
    CONSTRAINT pk PRIMARY KEY (pe_cd, tu_cd)
);

drop table IF EXISTS pessoa_turma_hist;
CREATE TABLE pessoa_turma_hist (
	hist INT NOT NULL AUTO_INCREMENT,
	pe_cd INT NOT NULL,
    tu_cd INT NOT NULL,
    pt_numparcelas INT NOT NULL,
    pt_valor DECIMAL(10,2) DEFAULT NULL,
    pt_obs VARCHAR(300) DEFAULT NULL,
    
    dh_ultima_alt TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL,
    cd_usuario_ultalt INT,    
    in_desativado CHAR(1),
    
	dh_operacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
    cd_usuario_operacao INT,
    
    CONSTRAINT pk PRIMARY KEY (hist)
);

drop table IF EXISTS pagamento;
CREATE TABLE pagamento (
	pe_cd INT NOT NULL,
    tu_cd INT NOT NULL,
    pag_parcela INT NOT NULL,
    dh_ultima_alt TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL,
    cd_usuario_ultalt INT,    
    
    -- FOREIGN KEY (pe_cd, tu_cd) REFERENCES pessoa_turma(pe_cd, tu_cd) ON DELETE CASCADE,
    
    CONSTRAINT pk PRIMARY KEY (pe_cd, tu_cd, pag_parcela)
);

-- SET SQL_SAFE_UPDATES = 0;
-- ALTER TABLE pessoa_materia ADD CONSTRAINT fk_pessoa_materia FOREIGN KEY ( pe_cd ) REFERENCES pessoa (pe_cd) 
-- ON DELETE RESTRICT
-- ON UPDATE RESTRICT;
