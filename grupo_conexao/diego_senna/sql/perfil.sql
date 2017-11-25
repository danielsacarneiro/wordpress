ALTER DATABASE unct CHARACTER SET utf8 COLLATE utf8_general_ci;

DROP TABLE IF EXISTS perfil;
CREATE TABLE perfil (
    perf_cd INT NOT NULL AUTO_INCREMENT,
    perf_ds VARCHAR(300) NOT NULL,
	
    dh_inclusao TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
    dh_ultima_alt TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL,
    cd_usuario_incl INT,
    cd_usuario_ultalt INT,
    in_desativado CHAR(1) NOT NULL DEFAULT 'N',

    CONSTRAINT pk PRIMARY KEY (perf_cd)
);

DROP TABLE IF EXISTS perfil_materia;
CREATE TABLE perfil_materia (
    perf_cd INT NOT NULL,
    mat_cd INT NOT NULL,
	
    dh_inclusao TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
    dh_ultima_alt TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL,
    cd_usuario_incl INT,
    cd_usuario_ultalt INT,
    in_desativado CHAR(1) NOT NULL DEFAULT 'N',

    CONSTRAINT pk PRIMARY KEY (perf_cd, mat_cd)
);
ALTER TABLE perfil_materia ADD CONSTRAINT fk_perfil_materia_materia FOREIGN KEY ( mat_cd ) REFERENCES materia (mat_cd) 
ON DELETE RESTRICT
ON UPDATE RESTRICT;