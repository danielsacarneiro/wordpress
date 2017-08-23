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
	
    -- TEM ALTERACAO PORQUE PERMITE ALTERACAO POR CIMA
    dh_ultima_alt TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL,
    cd_usuario_ultalt INT,    
    CONSTRAINT pk PRIMARY KEY (tu_cd)
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
    CONSTRAINT pk PRIMARY KEY (pe_cd, tu_cd)
);
-- ALTER TABLE pessoa_materia ADD CONSTRAINT fk_pessoa_materia FOREIGN KEY ( pe_cd ) REFERENCES pessoa (pe_cd) 
-- ON DELETE RESTRICT
-- ON UPDATE RESTRICT;
