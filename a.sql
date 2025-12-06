gestion de usuarios

usuario: persona o conjunto de personas que tiene acceso a un sistema  y puede realizar acciones segun su nivel de privilegio

privilegio: permiso o conjunto de permisos que se otorgan a un usuario para realizar ciertas acciones dentro de un sistema

rol: conjunto de privilegios que se asignan a un usuario o grupo de usuarios para definir su nivel de acceso y las acciones que pueden realizar dentro de un sistema    
---------------------

create user rr_hh identified by rr_hh;
grant connect, resource, unlimited tablespace to rr_hh;

connect es un rol que contiene los privilegios:create table, drop table, alter table, create procedure, create trigger,   , insert, delete, update

--------------------

COECTARSE A rr_hh
CREATE TABLE EMPLEADOS (CED_EMP VARCHAR(10) PRIMARY KEY,
NOM_EMP VARCHAR(10) NOT NULL,
APE_EMP VARCHAR(10) NOT NULL,
COR_EMP VARCHAR(10) NOT NULL
);

CREATE TABLE MULTAS
(
    NUN_MUL NUMBER PRIMARY KEY,
    FEC_MUL DATE NOT NULL,
    MOT_MUL VARCHAR(20) NOT NULL,
    VAL_MUL NUMBER NOT NULL,
    CED_EMP VARCHAR(10) REFERENCES EMPLEADOS(CED_EMP)
);

--------------------------


MATRIZ DE ASIGNACION DE privilegios


connect system

create user anabel identified by anabel123;
grant create session to anabel;
grant select, update(COR_EMP) on rr_hh.EMPLEADOS to anabel;
grant select, insert, update, delete on rr_hh.MULTAS to anabel;


create user mauricio identified by admin;
grant create session to mauricio;
grant select, insert, update, delete on rr_hh.EMPLEADOS to mauricio;
grant select, insert, update, delete on rr_hh.MULTAS to mauricio;


connect mauricio/admin;
insert into rr_hh.EMPLEADOS values ('801', 'henry', 'moya', 'hm@yahoo.es');

connect anabel/anabel123;
select * from rr_hh.EMPLEADOS;
update rr_hh.EMPLEADOS set COR_EMP ='hm@yahoo.com' where CED_EMP = '801';

---------------------------------------


los empleados quieren ver sus musltas en una terminal

vistas -> views
una vista s una sentencia sql que materializa en la bd

sintaxis
create or replace view nombre_vista 
as
consulta_sql;
--------

create view para_henry 
as
select * from rr_hh.MULTAS where CED_EMP = '801';

-------

connect system
grant create any view to rr_hh;

create user henry identified by henry123;
grant create session to henry;
grant para_henry to henry;

connect henry/henry123;
select * from para_henry;

-------

---------------------------


create user intruso identified by intruso123;
grant create session to intruso;

GESTIÓN DE USUARIOS------

USUARIO: 
Es una persona o conjunto de personas que tienen permisos de acceso a un sistema y pueden realizar acciones según su nivel de privilegio(perfil).

PRIVILEGIO:
Es un permiso para realizar una acción en un sistema.

ROL:
Es un conjunto de privilegios.
---------------------------------------------------------------------------------------------------------------------------------------------------------------
CONNECT SYSTEM
CREATE USER RR_HH IDENTIFIED BY RR_HH;
GRANT CONNECT, RESOURCE, UNLIMITED TABLESPACE TO RR_HH;   -> CONTENEDOR BD

---------------------------------------------------------------------------------------------------------------------------------------------------------------
GRANT -> Otorgar privilegio

CONNECT -> Es un rol que contiene los privilegios: CREATE TABLE, DROP TABLE, ALTER TABLE, CREATE PROCEDURE, CREATE TRIGGER, ……., INSERT, UPDATE, DELETE…
---------------------------------------------------------------------------------------------------------------------------------------------------------------
CONNECT RR_HH/RR_HH
---------------------------------------------
CREATE TABLE EMPLEADOS(
	CED_EMP VARCHAR(10) PRIMARY KEY,
	NOM_EMP VARCHAR(10) NOT NULL,
	APE_EMP VARCHAR(10) NOT NULL,
	COR_EMP VARCHAR(10) NOT NULL
);

ALTER TABLE EMPLEADOS MODIFY COR_EMP VARCHAR(20);

CREATE TABLE MULTAS(
	NUM_MUL NUMBER PRIMARY KEY,
	FEC_MUL DATE NOT NULL,
	MOT_MUL VARCHAR(20) NOT NULL,
	VAL_MUL NUMBER NOT NULL,
	CED_EMP_MUL NOT NULL REFERENCES EMPLEADOS(CED_EMP)
);
---------------------------------------------------------------------------------------------------------------------------------------------------------------
MATRIZ DE ASIGNACION DE USUARIOS

USUARIO: Anabel (secretaría)

TABLA/ACCIONES	SELECT	INSERT	UPDATE	DELETE
EMPLEADOS	SI	NO	SI (CORREO)	NO
MULTAS	SI	SI	SI	SI

USUARIO: Mauricio (admin)

TABLA/ACCIONES	SELECT 	INSERT	UPDATE	DELETE
EMPLEADOS	SI	SI	SI	SI
MULTAS	SI	SI	SI	SI

USUARIO: Henry, Hernan, Daniel

TABLA/ACCIONES	SELECT 	INSERT	UPDATE	DELETE
EMPLEADOS	NO	NO	NO	NO
MULTAS	SI (PROPIAS)	NO	NO	NO

---------------------------------------------------------------------------------------------------------------------------------------------------------------
CONNECT SYSTEM
CREATE USER ANABEL IDENTIFIED BY ANABEL123;
GRANT CREATE SESSION TO ANABEL;     -> (ESTABLECER SESION/LOGUEARSE)
GRANT SELECT, UPDATE(COR_EMP) ON RR_HH.EMPLEADOS TO ANABEL;
GRANT SELECT, INSERT, UPDATE, DELETE ON RR_HH.MULTAS TO ANABEL;

---------------------------------------------------------------------------------------------------------------------------------------------------------------
CREATE USER MAURICIO IDENTIFIED BY MAURICIO123;
GRANT CREATE SESSION TO MAURICIO;
GRANT SELECT, INSERT, UPDATE, DELETE ON RR_HH.EMPLEADOS TO MAURICIO;
GRANT SELECT, INSERT, UPDATE, DELETE ON RR_HH.MULTAS TO MAURICIO;

---------------------------------------------------------------------------------------------------------------------------------------------------------------
CREATE USER HENRY IDENTIFIED BY HENRY123;
GRANT CREATE SESSION TO HENRY;
---------------------------------------------------------------------------------------------------------------------------------------------------------------
CONNECT MAURICIO/MAURICIO123
INSERT INTO RR_HH.EMPLEADOS VALUES('1801', 'HENRY', 'MOYA', 'hm@gmail.com');
INSERT INTO RR_HH.EMPLEADOS VALUES('1802', 'HERNAN', 'AUZ', 'ha@gmail.com');
INSERT INTO RR_HH.EMPLEADOS VALUES('1803', 'DANIEL', 'DIAZ', 'dd@gmail.com');

CONNECT ANABEL/ANABEL123
SELECT * FROM RR_HH.EMPLEADOS;

UPDATE RR_HH.EMPLEADOS
SET COR_EMP = 'hm@yahoo.com'
WHERE CED_EMP = '1801';

    INSERT INTO RR_HH.MULTAS VALUES(1, SYSDATE, 'ATRASO', 20, '1801');
    INSERT INTO RR_HH.MULTAS VALUES(2, SYSDATE, 'ATRASO', 25, '1802');
    INSERT INTO RR_HH.MULTAS VALUES(3, SYSDATE, 'FALTA', 30, '1801');
    INSERT INTO RR_HH.MULTAS VALUES(4, SYSDATE, 'ATRASO', 40, '1803');

SELECT * FROM RR_HH.MULTAS;
---------------------------------------------------------------------------------------------------------------------------------------------------------------
LOS EMPLEADOS QUIEREN VER SUS MULTAS EN UNA TERMINAL
---------------------------------------------------------------------------------------------------------------------------------------------------------------
VISTAS - > VIEWS
Una vista es una sentencia SQL materializada en la BD.

SINTAXIS
CREATE [OR REPLACE] VIEW nombreVista
AS
	consultaSQL;
---------------------------------------------------------------------------------------------------------------------------------------------------------------
CONNECT RR_HH/RR_HH;

CREATE VIEW PARA_HENRY
AS
SELECT *
FROM MULTAS
WHERE CED_EMP_MUL='1801';

ERROR en lφnea 1:
ORA-01031: privilegios insuficientes ---------------->>>

---------------------------------------------------------------------------------------------------------------------------------------------------------------
CONNECT SYSTEM
GRANT CREATE ANY VIEW TO RR_HH;

CREATE USER HENRY IDENTIFIED BY HENRY123;
GRANT CREATE SESSION TO HENRY;
GRANT SELECT ON RR_HH.PARA_HENRY TO HENRY;

CONNECT HENRY/HENRY123
SELECT * FROM PARA_HENRY;
---------------------------------------------------------------------------------------------------------------------------------------------------------------
HACER LO MISMO PARA HERNAN Y DANIEL

CONNECT SYSTEM/Sebasandres1
CREATE VIEW PARA_HERNAN
AS
SELECT *
FROM MULTAS
WHERE CED_EMP_MUL='1802';

CREATE VIEW PARA_DANIEL
AS
SELECT *
FROM MULTAS
WHERE CED_EMP_MUL='1803';

CREATE USER HERNAN IDENTIFIED BY HERNAN123;
GRANT CREATE SESSION TO HERNAN;
GRANT SELECT ON RR_HH.PARA_HERNAN TO HERNAN;

CREATE USER DANIEL IDENTIFIED BY DANIEL123;
GRANT CREATE SESSION TO DANIEL;
GRANT SELECT ON RR_HH.PARA_DANIEL TO DANIEL;
---------------------------------------------------------------------------------------------------------------------------------------------------------------
---------------------------------------------------------------------------------------------------------------------------------------------------------------
CONNECT SYSTEM

CREATE USER INTRUSO IDENTIFIED BY INTRUSO123;
GRANT CREATE SESSION TO INTRUSO;
GRANT SELECT, INSERT, UPDATE, DELETE ON RR_HH.EMPLEADOS TO INTRUSO;
GRANT SELECT, INSERT, UPDATE, DELETE ON RR_HH.MULTAS TO INTRUSO;

UPDATE RR_HH.MULTAS
SET VAL_MUL=2
WHERE NUM_MUL=4;

TABLA PARA AUDITORIA----------------------------------------------------------

CONNECT RR_HH/RR_HH;

TABLA DE AUDITORIA
CREATE TABLE AUDITORIA(
	QUIEN VARCHAR(10),
	CUANDO DATE,
	ACCION VARCHAR(10),
	MOT_ANT VARCHAR(10),
	MOT_NUE VARCHAR(10),
	VAL_ANT NUMBER,
	VAL_NUE NUMBER,
	EMP_ANT VARCHAR(10),
	EMP_NUE VARCHAR(10)
);

TRIGGER PARA AUDITAR----------------------------------------------------------
CREATE OR REPLACE TRIGGER AUD_MULTAS_TRG
AFTER INSERT OR UPDATE OR DELETE ON MULTAS
FOR EACH ROW
BEGIN
	IF INSERTING THEN
		INSERT INTO AUDITORIA(QUIEN, CUANDO, ACCION, MOT_NUE, VAL_NUE, EMP_NUE)
					VALUES(USER, SYSDATE, 'INSERTÓ', :NEW.MOT_MUL, :NEW.VAL_MUL, :NEW.CED_EMP_MUL);
	END IF;
	
	IF UPDATING THEN
		INSERT INTO AUDITORIA(QUIEN, CUANDO, ACCION, MOT_ANT, MOT_NUE, VAL_ANT, VAL_NUE, EMP_ANT, EMP_NUE)
					VALUES(USER, SYSDATE, 'CAMBIÓ', :OLD.MOT_MUL, :NEW.MOT_MUL, :OLD.VAL_MUL, :NEW.VAL_MUL, :OLD.CED_EMP_MUL, :NEW.CED_EMP_MUL);
	END IF;
	
	IF DELETING THEN
		INSERT INTO AUDITORIA(QUIEN, CUANDO, ACCION, MOT_ANT, VAL_ANT, EMP_ANT)
					VALUES(USER, SYSDATE, 'BORRÓ', :OLD.MOT_MUL, :OLD.VAL_MUL,:OLD.CED_EMP_MUL);
	END IF;
END AUD_MULTAS_TRG;
.
/

CONNECT INTRUSO/INTRUSO123

UPDATE RR_HH.MULTAS
SET VAL_MUL=1, MOT_MUL = 'FALTITA'
WHERE NUM_MUL=1;

---------------------------------------------------------------------------------------

2/12/25

1.enunciado
2.analisis documental
3. sin aporo de documentos
4. ingenieria inversa

se desa crear un sistema con bd para la fisei que funcione con un red social, con las funcionalidades de facebook

usurios=(id_usu, nom_usu, ape_usu, fec_nac_usu, img_perf, correo_us)
		u01 ana mera 15/02/2005 .png am@uta.edu.ec 
		u02 luis perez 20/03/2004 .jpg lu@uta.edu.ec


solicitud_amistad=(id_sol, id_us_sol, id_usu_rec , fec_hot, sol, estado_sol, fec_resp_sol )
					1001, u01, u02, 14/12/2025 6:00, si, aceptada, 14/12/2025 6:30
					1002, u02, u01, 15/12/2025 8:00, si, pendiente,


publicaciones=(id_pub, tip_pub, id_usu_creo,fec_hor_pub, des_pub, id_pub_ori)
			    232 imagen u01 14/12/2025 7:00 vendo_laptop 
				555, imagen, u01, 15/12/2025 9:00, vendo_celular, 232
				888, imagen, u02, 16/12/2025 10:00, vendo_tablet, 555


reaccion_publicacion=(id_pub_rea, id_rea_sel, fec_hor_rea, usu_rea )
					4232, mg, 14/12/2025 8:01 u02

reacciones=(id_rea, nom_reac, )
			mg , me_gusta
			md , me_disgusta
			me , me_encata
			
comentarios_publicaciones=(id_com, texto_com, id_us, fec_hor_com,id_pub_com, id_com_res )
						9788, precio, u03, 14/12/2025 11:50:08	, 555
						9789, a, u09, 14/12/2025 12:00:00, , 9788

reaccion_comentario=(id_com_rea, id_rea_sel, fec_hor_rea, usu_rea )
					4232, mg, 14/12/2025 8:01 u02



chat=(id_msg, texto_msg, estado_msg, id_usu_env, fec_hor_env,id_usu_rec, fec_hor_rec, id_msg_res )
		789 , hola, leido, u04, 14/12/2025 10:00, u02, 14/12/2025 11:57:08, null


-----------------------------------------------------
CUANTOS AMIGOS TINE UN DETERMIADO USUARIO
CUANTOS AÑOS DE AMISTAD ENTRE U1 Y U2
CUAL FUE LA PUBLICACION CON MAS ME GUSTA EN EL 2024 DEL USUARIO U01
CUAL FUE LA PUBLICACION CON MAS COMENTARIOS FAVORABLES
LA PUBLICACION MAS COMPARTIDA
-------------------------------------------

create user fisei identified by fisei123;
grant connect, resource, unlimited tablespace to fisei;

connect fisei/fisei123;

create table usuarios(
	id_usu varchar(10) primary key,
	nom_usu varchar(30) not null,
	ape_usu varchar(30) not null,
	fec_nac_usu date not null,
	img_perf varchar(100),
	correo_usu varchar(50) not null
);

create table solicitud_amistad(
	id_sol number primary key,
	id_usu_sol varchar(10) references usuarios(id_usu),
	id_usu_rec varchar(10) references usuarios(id_usu),
	fec_hor date not null,
	sol varchar(5) not null,
	estado_sol varchar(15) not null,
	fec_resp_sol date
);

create table publicaciones(
	id_pub number primary key,
	tip_pub varchar(20) not null,
	id_usu_creo varchar(10) references usuarios(id_usu),
	fec_hor_pub date not null,
	des_pub varchar(200) not null,
	id_pub_ori number references publicaciones(id_pub)
);

create table reacciones(
	id_rea varchar(5) primary key,
	nom_reac varchar(20) not null
);

create table reaccion_publicacion(
	id_pub_rea number primary key,
	id_rea_sel varchar(5) references reacciones(id_rea),
	fec_hor_rea date not null,
	usu_rea varchar(10) references usuarios(id_usu)
);
create table comentarios_publicaciones(
	id_com number primary key,
	texto_com varchar(500) not null,
	id_usu varchar(10) references usuarios(id_usu),
	fec_hor_com date not null,
	id_pub_com number references publicaciones(id_pub),
	id_com_res number references comentarios_publicaciones(id_com)
);
create table reaccion_comentario(
	id_com_rea number primary key,
	id_rea_sel varchar(5) references reacciones(id_rea),
	fec_hor_rea date not null,
	usu_rea varchar(10) references usuarios(id_usu)
);
create table chat(
	id_msg number primary key,
	texto_msg varchar(500) not null,
	estado_msg varchar(10) not null,
	id_usu_env varchar(10) references usuarios(id_usu),
	fec_hor_env date not null,
	id_usu_rec varchar(10) references usuarios(id_usu),
	fec_hor_rec date,
	id_msg_res number references chat(id_msg)
);


-----inser de ejemplos
---------------------

INSERT INTO usuarios VALUES ('u01','Ana','Mera',TO_DATE('15/02/2005','DD/MM/YYYY'),'ana.png','am@uta.edu.ec');
INSERT INTO usuarios VALUES ('u02','Luis','Perez',TO_DATE('20/03/2004','DD/MM/YYYY'),'luis.jpg','lp@uta.edu.ec');
INSERT INTO usuarios VALUES ('u03','Maria','Lopez',TO_DATE('10/05/2005','DD/MM/YYYY'),'maria.png','ml@uta.edu.ec');
INSERT INTO usuarios VALUES ('u04','Carlos','Vera',TO_DATE('12/01/2003','DD/MM/YYYY'),'carlos.jpg','cv@uta.edu.ec');
INSERT INTO usuarios VALUES ('u05','Elena','Castro',TO_DATE('08/11/2004','DD/MM/YYYY'),'elena.jpg','ec@uta.edu.ec');

INSERT INTO solicitud_amistad VALUES (1001,'u01','u02',
    TO_DATE('14/12/2021 06:00','DD/MM/YYYY HH24:MI'),'si','aceptada',
    TO_DATE('14/12/2021 06:30','DD/MM/YYYY HH24:MI'));

INSERT INTO solicitud_amistad VALUES (1002,'u02','u01',
    TO_DATE('15/12/2025 08:00','DD/MM/YYYY HH24:MI'),'si','pendiente',NULL);

INSERT INTO solicitud_amistad VALUES (1003,'u01','u03',
    TO_DATE('10/01/2024 12:00','DD/MM/YYYY HH24:MI'),'si','aceptada',
    TO_DATE('10/01/2024 12:15','DD/MM/YYYY HH24:MI'));

INSERT INTO solicitud_amistad VALUES (1004,'u04','u01',
    TO_DATE('11/03/2023 17:50','DD/MM/YYYY HH24:MI'),'si','rechazada',
    TO_DATE('11/03/2023 18:10','DD/MM/YYYY HH24:MI'));

INSERT INTO solicitud_amistad VALUES (1005,'u05','u01',
    TO_DATE('20/07/2022 09:00','DD/MM/YYYY HH24:MI'),'si','aceptada',
    TO_DATE('20/07/2022 09:20','DD/MM/YYYY HH24:MI'));


INSERT INTO publicaciones VALUES (200,'imagen','u01',
    TO_DATE('10/12/2024 09:00','DD/MM/YYYY HH24:MI'),
    'Vendo laptop',NULL);

INSERT INTO publicaciones VALUES (201,'texto','u01',
    TO_DATE('15/12/2024 09:00','DD/MM/YYYY HH24:MI'),
    'Vendo celular',200);

INSERT INTO publicaciones VALUES (202,'imagen','u02',
    TO_DATE('16/12/2024 10:00','DD/MM/YYYY HH24:MI'),
    'Vendo tablet',201);

INSERT INTO publicaciones VALUES (203,'imagen','u03',
    TO_DATE('20/12/2024 14:20','DD/MM/YYYY HH24:MI'),
    'Oferta en audífonos',NULL);

INSERT INTO publicaciones VALUES (204,'texto','u01',
    TO_DATE('03/01/2025 08:30','DD/MM/YYYY HH24:MI'),
    'Feliz año nuevo FISEI',NULL);


INSERT INTO reacciones VALUES ('mg','me_gusta');
INSERT INTO reacciones VALUES ('md','me_disgusta');
INSERT INTO reacciones VALUES ('me','me_encanta');
INSERT INTO reacciones VALUES ('as','asombroso');
INSERT INTO reacciones VALUES ('tr','triste');

INSERT INTO reaccion_publicacion VALUES (3001,'mg',
    TO_DATE('10/12/2024 10:00','DD/MM/YYYY HH24:MI'),'u02');

INSERT INTO reaccion_publicacion VALUES (3002,'me',
    TO_DATE('10/12/2024 10:05','DD/MM/YYYY HH24:MI'),'u03');

INSERT INTO reaccion_publicacion VALUES (3003,'mg',
    TO_DATE('15/12/2024 09:30','DD/MM/YYYY HH24:MI'),'u05');

INSERT INTO reaccion_publicacion VALUES (3004,'md',
    TO_DATE('16/12/2024 11:00','DD/MM/YYYY HH24:MI'),'u01');

INSERT INTO reaccion_publicacion VALUES (3005,'mg',
    TO_DATE('20/12/2024 15:00','DD/MM/YYYY HH24:MI'),'u04');

INSERT INTO comentarios_publicaciones VALUES (4001,'¿Cuál es el precio?',
    'u03',TO_DATE('10/12/2024 11:50','DD/MM/YYYY HH24:MI'),200,NULL);

INSERT INTO comentarios_publicaciones VALUES (4002,'¿Incluye cargador?',
    'u05',TO_DATE('10/12/2024 12:10','DD/MM/YYYY HH24:MI'),200,NULL);

INSERT INTO comentarios_publicaciones VALUES (4003,'Sí, incluye.',
    'u01',TO_DATE('10/12/2024 12:20','DD/MM/YYYY HH24:MI'),200,4002);

INSERT INTO comentarios_publicaciones VALUES (4004,'¿Tiene garantía?',
    'u02',TO_DATE('16/12/2024 11:20','DD/MM/YYYY HH24:MI'),202,NULL);

INSERT INTO comentarios_publicaciones VALUES (4005,'Gracias por responder.',
    'u03',TO_DATE('10/12/2024 12:30','DD/MM/YYYY HH24:MI'),200,4003);


INSERT INTO reaccion_comentario VALUES (5001,'mg',
    TO_DATE('10/12/2024 12:00','DD/MM/YYYY HH24:MI'),'u02');

INSERT INTO reaccion_comentario VALUES (5002,'me',
    TO_DATE('10/12/2024 12:05','DD/MM/YYYY HH24:MI'),'u01');

INSERT INTO reaccion_comentario VALUES (5003,'mg',
    TO_DATE('10/12/2024 12:15','DD/MM/YYYY HH24:MI'),'u05');

INSERT INTO reaccion_comentario VALUES (5004,'as',
    TO_DATE('16/12/2024 11:35','DD/MM/YYYY HH24:MI'),'u03');

INSERT INTO reaccion_comentario VALUES (5005,'mg',
    TO_DATE('20/12/2024 15:10','DD/MM/YYYY HH24:MI'),'u04');


INSERT INTO chat VALUES (6001,'Hola, ¿cómo estás?','enviado',
    'u01',TO_DATE('14/12/2025 10:00','DD/MM/YYYY HH24:MI'),
    'u02',NULL,NULL);

INSERT INTO chat VALUES (6002,'Bien, ¿y tú?','leido',
    'u02',TO_DATE('14/12/2025 10:05','DD/MM/YYYY HH24:MI'),
    'u01',TO_DATE('14/12/2025 10:06','DD/MM/YYYY HH24:MI'),6001);

INSERT INTO chat VALUES (6003,'¿Ya revisaste la tarea?','enviado',
    'u03',TO_DATE('14/12/2025 11:00','DD/MM/YYYY HH24:MI'),
    'u01',NULL,NULL);

INSERT INTO chat VALUES (6004,'Todavía no.','leido',
    'u01',TO_DATE('14/12/2025 11:10','DD/MM/YYYY HH24:MI'),
    'u03',TO_DATE('14/12/2025 11:11','DD/MM/YYYY HH24:MI'),6003);

INSERT INTO chat VALUES (6005,'Gracias!','leido',
    'u03',TO_DATE('14/12/2025 11:15','DD/MM/YYYY HH24:MI'),
    'u01',TO_DATE('14/12/2025 11:16','DD/MM/YYYY HH24:MI'),6004);

