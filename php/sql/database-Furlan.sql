CREATE DATABASE professionists_Idle;

USE professionists_Idle;

CREATE TABLE utente(
    email VARCHAR(255),
    username VARCHAR(20) NOT NULL UNIQUE,
    password VARCHAR(255),
    google_id VARCHAR(21),
    PRIMARY KEY(email)
);