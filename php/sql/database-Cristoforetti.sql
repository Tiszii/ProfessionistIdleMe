CREATE DATABASE IF NOT EXISTS ProfessionistIdle DEFAULT CHARACTER SET = utf8;

USE ProfessionistIdle;

CREATE TABLE tutente (
    email VARCHAR(320) NOT NULL,
    username VARCHAR(30) DEFAULT NULL UNIQUE,
    password VARCHAR(20) DEFAULT NULL,
    id VARCHAR(30) DEFAULT NULL,
    PRIMARY KEY(email)
);