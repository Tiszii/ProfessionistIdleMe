DROP DATABASE IF EXISTS my_professionistidle;

CREATE DATABASE my_professionistidle;

USE my_professionistidle;

CREATE TABLE save(
    id INT NOT NULL AUTO_INCREMENT,
    data_salvataggio DATETIME NOT NULL DEFAULT NOW(),
    PRIMARY KEY(Id)
);

CREATE TABLE utente(
    username VARCHAR(20) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(100),
    google_id VARCHAR(21),
    id_salvataggio INT,
    numero_invitati INT DEFAULT 0,
    invitato TINYINT(1) DEFAULT 0,
    PRIMARY KEY(username),
    FOREIGN KEY(id_salvataggio) REFERENCES save(id) ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE post(
    id INT NOT NULL AUTO_INCREMENT,
    data DATETIME NOT NULL DEFAULT NOW(),
    autore VARCHAR(20),
    titolo VARCHAR(100) NOT NULL,
    testo LONGTEXT NOT NULL,
    PRIMARY KEY(id),
    FOREIGN KEY(autore) REFERENCES utente(username) ON UPDATE CASCADE ON DELETE
    SET
        NULL
);

CREATE TABLE materiale(
    id_salvataggio INT NOT NULL,
    tipo VARCHAR(12) NOT NULL,
    quantita BIGINT NOT NULL,
    m_velocita INT NOT NULL DEFAULT '1',
    m_quantita INT NOT NULL DEFAULT '1',
    PRIMARY KEY(id_salvataggio, tipo),
    FOREIGN KEY(id_salvataggio) REFERENCES save(id) ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE amicizia(
    id_utente1 VARCHAR(20) NOT NULL,
    id_utente2 VARCHAR(20) NOT NULL,
    PRIMARY KEY(id_utente1, id_utente2),
    FOREIGN KEY(id_utente1) REFERENCES utente(username) ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY(id_utente2) REFERENCES utente(username) ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE commento(
    id INT AUTO_INCREMENT,
    testo LONGTEXT NOT NULL,
    autore VARCHAR(20),
    id_post INT NOT NULL,
    PRIMARY KEY(id),
    FOREIGN KEY(id_post) REFERENCES post(id) ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY(autore) REFERENCES utente(username) ON UPDATE CASCADE ON DELETE
    SET
        NULL
);

CREATE TABLE feedback(
    id INT AUTO_INCREMENT,
    autore VARCHAR(20) DEFAULT "ACCOUNT_ELIMINATO",
    id_post INT NOT NULL,
    tipo_feedBack ENUM('L', 'D') NOT NULL,
    PRIMARY KEY(id),
    FOREIGN KEY(autore) REFERENCES utente(username) ON UPDATE CASCADE ON DELETE
    SET
        NULL,
        FOREIGN KEY(id_post) REFERENCES post(id) ON UPDATE CASCADE ON DELETE CASCADE
);