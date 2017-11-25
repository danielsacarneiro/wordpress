ALTER DATABASE unct CHARACTER SET utf8 COLLATE utf8_general_ci;

DROP TABLE IF EXISTS meta;
CREATE TABLE meta (	
	mat_sq INT NOT NULL AUTO_INCREMENT,   -- usado para uma futura identificacao unica
    
    met_cd INT NOT NULL,
    mat_cd INT NOT NULL,
    perf_cd INT NOT NULL,    
    
    met_font_lei_cd INT,
    met_artigoinicio INT,
    met_artigofim INT,

	met_font_livro_cd INT,
    met_paginainicio INT,
    met_paginafim INT,        
    
    met_obs TEXT,
    met_dtinicio DATE,
    met_dtfim DATE,
	
    dh_inclusao TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
    dh_ultima_alt TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL,
    cd_usuario_incl INT,
    cd_usuario_ultalt INT,
    in_desativado CHAR(1) NOT NULL DEFAULT 'N',

    CONSTRAINT pk PRIMARY KEY (mat_sq, met_cd, mat_cd, perf_cd),
    CONSTRAINT fk_meta_materia FOREIGN KEY ( mat_cd ) REFERENCES materia (mat_cd) ON DELETE RESTRICT ON UPDATE RESTRICT,
	CONSTRAINT fk_meta_perfil FOREIGN KEY ( perf_cd ) REFERENCES perfil (perf_cd) ON DELETE RESTRICT ON UPDATE RESTRICT,
    
    CONSTRAINT fk_meta_materia_fontelei FOREIGN KEY ( mat_cd, met_font_lei_cd ) REFERENCES materia_fonte (mat_cd, fonte_cd) ON DELETE RESTRICT ON UPDATE RESTRICT,
	CONSTRAINT fk_meta_materia_fontelivro FOREIGN KEY ( mat_cd, met_font_livro_cd ) REFERENCES materia_fonte (mat_cd, fonte_cd) ON DELETE RESTRICT ON UPDATE RESTRICT    
);
