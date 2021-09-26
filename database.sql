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

CREATE TABLE usuario(
id              int(255) auto_increment not null,
nombre          varchar(100) not null,
apellidos       varchar(255),
email           varchar(255) not null,
password        varchar(255) not null,
role            varchar(20),
imagen          varchar(255),
CONSTRAINT pk_usuarios PRIMARY KEY(id),
CONSTRAINT uq_email UNIQUE(email)  
)ENGINE=InnoDb;

INSERT INTO usuario VALUES(NULL, 'Admin', 'Admin', 'admin@admin.com', '$2y$04$PGCkyQDK2oZIACrgfBVoy.9P1.XsEpalClTfN.iwBmyrOr606ucOm', 'ROLE_ADMIN', null);
INSERT INTO usuario VALUES(NULL, 'Cliente', 'Cliente', 'cliente@cliente.com', '$2y$04$b6lX7dZ7MyajexE2K2vNxeKlgj4rzDswJP/yvKTzCqW/JcGkuVb82', 'ROLE_USER', null);


CREATE TABLE usuario_sector(
id_usuario int(255) not null,
id_sector int(255) not null,
CONSTRAINT pk_usuario_sector PRIMARY KEY(id_usuario,id_sector),
CONSTRAINT fk_sector FOREIGN KEY(id_sector) REFERENCES sector(id) ON DELETE CASCADE,
CONSTRAINT fk_usuario FOREIGN KEY(id_usuario) REFERENCES usuario(id) ON DELETE CASCADE 
)ENGINE=InnoDB;

INSERT INTO usuario_sector VALUES(1,1);
INSERT INTO usuario_sector VALUES(2,2);