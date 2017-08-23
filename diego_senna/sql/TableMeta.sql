CREATE SCHEMA `diego_senna` ;
ALTER DATABASE unct CHARACTER SET utf8 COLLATE utf8_general_ci;

DROP TABLE IF EXISTS materia;
CREATE TABLE materia (
	ma_cd INT NOT NULL AUTO_INCREMENT,
    ma_ds VARCHAR(150) NOT NULL, 
    ma_obs VARCHAR(300),	
    -- TEM ALTERACAO PORQUE PERMITE ALTERACAO POR CIMA
    dh_ultima_alt TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL,
    cd_usuario_ultalt INT,    
    CONSTRAINT pk PRIMARY KEY (ma_cd)
);

drop table IF EXISTS meta;
CREATE TABLE meta (
	mt_cd INT NOT NULL AUTO_INCREMENT,
	ma_cd INT NOT NULL,
	mt_tp CHAR(2) NOT NULL, -- lei seca, livro indicado, outros
    mt_obs VARCHAR(300) NOT NULL, -- orientacoes sobre como seguir na meta
    mt_ponto_inicio int,
    mt_ponto_fim int,
	
    -- TEM ALTERACAO PORQUE PERMITE ALTERACAO POR CIMA
    dh_ultima_alt TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL,
    cd_usuario_ultalt INT,    
    CONSTRAINT pk PRIMARY KEY (tu_cd)
);

drop table IF EXISTS cronograma ;
-- relaciona aluno x meta, acrescentando a data de execucao da meta
CREATE TABLE cronograma (
	cg_cd INT NOT NULL,
	pe_cd INT NOT NULL,
	mt_cd INT NOT NULL,
    cg_dt_execucao DATE,
    cg_in_resolvido CHAR(2),
    cg_hora_inicio time,
    cg_hora_fim time,
	
    -- TEM ALTERACAO PORQUE PERMITE ALTERACAO POR CIMA
    dh_ultima_alt TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL,
    cd_usuario_ultalt INT,    
    CONSTRAINT pk PRIMARY KEY (tu_cd)
);

drop table IF EXISTS pessoa_turma;
CREATE TABLE pessoa_turma (
	pe_cd INT NOT NULL,
    tu_cd INT NOT NULL,
    pt_valor DECIMAL(10,2) DEFAULT NULL,
    pt_obs VARCHAR(300) DEFAULT NULL,
    dh_ultima_alt TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL,
    cd_usuario_ultalt INT,    
    CONSTRAINT pk PRIMARY KEY (pe_cd, tu_cd)
);
-- ALTER TABLE pessoa_materia ADD CONSTRAINT fk_pessoa_materia FOREIGN KEY ( pe_cd ) REFERENCES pessoa (pe_cd) 
-- ON DELETE RESTRICT
-- ON UPDATE RESTRICT;
