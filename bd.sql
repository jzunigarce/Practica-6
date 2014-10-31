DROP DATABASE IF EXISTS  REGISTRO;
CREATE DATABASE REGISTRO;
USE REGISTRO;
CREATE TABLE usuario(
id int not null primary key auto_increment,
nombre varchar(50) not null,
ap_paterno varchar(30) not null,
ap_materno varchar(30),
email varchar(50) not null unique
); 
