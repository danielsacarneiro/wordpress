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

DROP TABLE IF EXISTS perfil_aluno;
CREATE TABLE perfil_aluno (
    perf_cd INT NOT NULL,
    pe_cd INT NOT NULL, -- aluno eh uma pessoa
    
    perfaluno_tpmeta INT NOT NULL, -- dominio: semanal, quinzenal ou mensal
    perfaluno_diasmeta INT NOT NULL, -- numero X de dias por meta (ex.: semanal)
    perfaluno_horaspormaterianodia INT NOT NULL, -- numero X de horas por materia no dia
    perfaluno_dtinicio DATE,    
	
    dh_inclusao TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
    dh_ultima_alt TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL,
    cd_usuario_incl INT,
    cd_usuario_ultalt INT,
    in_desativado CHAR(1) NOT NULL DEFAULT 'N',

    CONSTRAINT pk PRIMARY KEY (perf_cd, pe_cd),
    CONSTRAINT fk_perfil_aluno_pessoa FOREIGN KEY ( pe_cd ) REFERENCES pessoa (pe_cd) ON DELETE RESTRICT ON UPDATE RESTRICT,
    CONSTRAINT fk_perfil_aluno_perfil FOREIGN KEY ( perf_cd ) REFERENCES perfil (perf_cd) ON DELETE RESTRICT ON UPDATE RESTRICT
);

DROP TABLE IF EXISTS perfil_materia;
CREATE TABLE perfil_materia (
    perf_cd INT NOT NULL,
    mat_cd INT NOT NULL,
    
    perfmat_carga INT NOT NULL, -- numero X de horas total da materia
	
    dh_inclusao TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
    dh_ultima_alt TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL,
    cd_usuario_incl INT,
    cd_usuario_ultalt INT,
    in_desativado CHAR(1) NOT NULL DEFAULT 'N',

    CONSTRAINT pk PRIMARY KEY (perf_cd, mat_cd),
	CONSTRAINT fk_perfil_materia_materia FOREIGN KEY ( mat_cd ) REFERENCES materia (mat_cd) ON DELETE RESTRICT ON UPDATE RESTRICT    
);
