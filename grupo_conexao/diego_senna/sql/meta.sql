ALTER DATABASE unct CHARACTER SET utf8 COLLATE utf8_general_ci;

/*DROP TABLE IF EXISTS meta;
CREATE TABLE meta (	    
    met_cd INT NOT NULL,
    perf_cd INT NOT NULL,    
    
    met_obs TEXT,
	
    dh_inclusao TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
    dh_ultima_alt TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL,
    cd_usuario_incl INT,
    cd_usuario_ultalt INT,
    in_desativado CHAR(1) NOT NULL DEFAULT 'N',

    CONSTRAINT pk PRIMARY KEY (met_cd, perf_cd),
	CONSTRAINT fk_meta_perfil FOREIGN KEY ( perf_cd ) REFERENCES perfil (perf_cd) ON DELETE RESTRICT ON UPDATE RESTRICT
);*/

DROP TABLE IF EXISTS meta;
CREATE TABLE meta (	
    meta_cd INT NOT NULL,
    perf_cd INT NOT NULL,
    mat_cd INT NOT NULL,
        
    meta_obs TEXT,
	
    dh_inclusao TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
    dh_ultima_alt TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL,
    cd_usuario_incl INT,
    cd_usuario_ultalt INT,
    in_desativado CHAR(1) NOT NULL DEFAULT 'N',

    CONSTRAINT pk PRIMARY KEY (meta_cd, perf_cd, mat_cd),
    CONSTRAINT fk_meta_materia FOREIGN KEY ( mat_cd ) REFERENCES materia (mat_cd) ON DELETE RESTRICT ON UPDATE RESTRICT,
	CONSTRAINT fk_meta_perfil FOREIGN KEY ( perf_cd ) REFERENCES perfil (perf_cd) ON DELETE RESTRICT ON UPDATE RESTRICT
);

DROP TABLE IF EXISTS meta_fonte;
CREATE TABLE meta_fonte (
    meta_cd INT NOT NULL,
    perf_cd INT NOT NULL,
    mat_cd INT NOT NULL,
	metaf_sq INT NOT NULL,        
    
    metaf_tpfonte INT NOT NULL, 
    fonte_cd INT, -- A FONTE PODE SER NULA (Ex. se for lei seca, nao tem fonte, ja eh predeterminada)
	metaf_tpparam INT, -- diz o tipo do parametro, se eh artigo ou pagina ou aula            
    metaf_numparaminicio INT,
    metaf_numparamfim INT,
    metaf_obs TEXT,
    metaf_horas INT NOT NULL, -- numero X de horas
	
    dh_inclusao TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
    dh_ultima_alt TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL,
    cd_usuario_incl INT,
    cd_usuario_ultalt INT,
    in_desativado CHAR(1) NOT NULL DEFAULT 'N',

    CONSTRAINT pk PRIMARY KEY (metaf_sq, meta_cd, perf_cd, mat_cd),
    CONSTRAINT fk_meta_fonte_materia FOREIGN KEY ( mat_cd ) REFERENCES materia (mat_cd) ON DELETE RESTRICT ON UPDATE RESTRICT,
	-- CONSTRAINT fk_meta_materia_meta FOREIGN KEY ( meta_cd,perf_cd ) REFERENCES meta (met_cd, perf_cd) ON DELETE RESTRICT ON UPDATE RESTRICT,        
    -- CONSTRAINT fk_meta_materia_fontelei FOREIGN KEY ( mat_cd, meta_font_lei_cd ) REFERENCES materia_fonte (mat_cd, fonte_cd) ON DELETE RESTRICT ON UPDATE RESTRICT,
	CONSTRAINT fk_meta_fonte_fonte FOREIGN KEY ( mat_cd, fonte_cd ) REFERENCES materia_fonte (mat_cd, fonte_cd) ON DELETE RESTRICT ON UPDATE RESTRICT    
);