CREATE DATABASE IF NOT EXISTS empresa_sector;
USE empresa_sector;

CREATE TABLE sector(
id              int(255) auto_increment not null,
nombre          varchar(255) not null,
CONSTRAINT pk_sector PRIMARY KEY(id) 
)ENGINE=InnoDb;

INSERT INTO sector VALUES(null, 'Tecnologico');
INSERT INTO sector VALUES(null, 'Transporte');
INSERT INTO sector VALUES(null, 'Inmobiliario');
INSERT INTO sector VALUES(null, 'Alimenticio');


CREATE TABLE empresa(
id            int(255) auto_increment not null,
nombre        varchar(255) not null,
telefono      varchar(255),
email         varchar(255) not null,
sector        int(255),
CONSTRAINT pk_empresa PRIMARY KEY(id),
CONSTRAINT uq_email UNIQUE(email),
CONSTRAINT fk_sector_empresa FOREIGN KEY(sector) REFERENCES sector(id) 
)ENGINE=InnoDb;

INSERT INTO empresa VALUES(NULL, 'PCComponentes', '968635898', 'pccomponentes@pccomponentes.com', 1);
INSERT INTO empresa VALUES(NULL, 'Seur', '986985743', 'seur@seur.com', 2);