CREATE DATABASE IF NOT EXISTS ProfessionistIdle;

USE ProfessionistIdle;

CREATE TABLE IF NOT EXISTS Utente(
    Email VARCHAR(255),
    Username VARCHAR(20) NOT NULL UNIQUE,
    Id INT UNIQUE,
    Password VARCHAR(255),
    PRIMARY KEY(Email)
);