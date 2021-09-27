CREATE DATABASE IF NOT EXISTS empresa_sector;
USE empresa_sector;

CREATE TABLE sector(
id              int(255) auto_increment not null,
nombre          varchar(255) not null,
CONSTRAINT pk_sector PRIMARY KEY(id) 
)ENGINE=InnoDb;

INSERT INTO sector VALUES(null, 'AdminSystem');
INSERT INTO sector VALUES(null, 'Tecnologico');
INSERT INTO sector VALUES(null, 'Automocion');
INSERT INTO sector VALUES(null, 'Farmaceutico');
INSERT INTO sector VALUES(null, 'Textil');
INSERT INTO sector VALUES(null, 'Construccion');
INSERT INTO sector VALUES(null, 'Alimentacion');
INSERT INTO sector VALUES(null, 'Metalurgia');
INSERT INTO sector VALUES(null, 'Transporte');
INSERT INTO sector VALUES(null, 'Sanitario');
INSERT INTO sector VALUES(null, 'Calzado');
INSERT INTO sector VALUES(null, 'Restauracion');
INSERT INTO sector VALUES(null, 'Siderurgia');
INSERT INTO sector VALUES(null, 'Educacion');


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

INSERT INTO empresa VALUES(NULL, 'PCComponentes', '968635898', 'pccomponentes@pccomponentes.com', 2);
INSERT INTO empresa VALUES(NULL, 'MediaMarck', '968875412', 'MediaMarck@MediaMarck.com', 2);
INSERT INTO empresa VALUES(NULL, 'Seur', '986985743', 'seur@seur.com', 9);
INSERT INTO empresa VALUES(NULL, 'MRV', '986988796', 'mrv@mrv.com', 9);
INSERT INTO empresa VALUES(NULL, 'Mercedes', '987654123', 'mercedes@mercedes.com', 3);
INSERT INTO empresa VALUES(NULL, 'Audi', '654123789', 'audi@audi.com', 3);
INSERT INTO empresa VALUES(NULL, 'BMV', '654784253', 'bmw@bmw.com', 3);
INSERT INTO empresa VALUES(NULL, 'Renault', '654778410', 'renault@renault.com', 3);
INSERT INTO empresa VALUES(NULL, 'Opel', '654710369', 'Opel@Opel.com', 3);
INSERT INTO empresa VALUES(NULL, 'Seat', '6874521036', 'Seat@Seat.com', 3);
INSERT INTO empresa VALUES(NULL, 'Zara', '6874577777', 'Zara@Zara.com', 5);
INSERT INTO empresa VALUES(NULL, 'Primark', '6800077542', 'Primark@Primark.com', 5);

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
INSERT INTO usuario_sector VALUES(2,3);
