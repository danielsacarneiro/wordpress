ALTER DATABASE unct CHARACTER SET utf8 COLLATE utf8_general_ci;

DROP TABLE IF EXISTS materia;
CREATE TABLE materia (
    mat_cd INT NOT NULL AUTO_INCREMENT,    
    mat_ds VARCHAR(300) NOT NULL,
	
    dh_inclusao TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
    dh_ultima_alt TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL,
    cd_usuario_incl INT,
    cd_usuario_ultalt INT,
    in_desativado CHAR(1) NOT NULL DEFAULT 'N',

    CONSTRAINT pk PRIMARY KEY (mat_cd)
);

DROP TABLE IF EXISTS materia_fonte;
CREATE TABLE materia_fonte (	
    mat_cd INT NOT NULL,
    fonte_cd INT NOT NULL,
    fonte_ds VARCHAR(300) NOT NULL,    
	
    dh_inclusao TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
    dh_ultima_alt TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL,
    cd_usuario_incl INT,
    cd_usuario_ultalt INT,
    in_desativado CHAR(1) NOT NULL DEFAULT 'N',

    CONSTRAINT pk PRIMARY KEY (mat_cd, fonte_cd),
    CONSTRAINT fk_materia_fonte_materia FOREIGN KEY ( mat_cd ) REFERENCES materia (mat_cd) ON DELETE RESTRICT ON UPDATE RESTRICT
);
