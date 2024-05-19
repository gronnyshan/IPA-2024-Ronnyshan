DROP DATABASE IF EXISTS marketingmaster;
CREATE DATABASE marketingmaster;
USE marketingmaster;

CREATE TABLE mitarbeiter(

    benutzer_id INT AUTO_INCREMENT NOT NULL PRIMARY KEY,
    b_vorname VARCHAR(50) NOT NULL,
    b_nachname VARCHAR(50) NOT NULL,
    b_abteilung VARCHAR(15) NOT NULL,
    b_email VARCHAR(75) NOT NULL,
    b_passwort VARCHAR(30) NOT NULL

);

CREATE TABLE kunden (
    kunden_id INT AUTO_INCREMENT PRIMARY KEY,
    k_vorname VARCHAR(50) NOT NULL,
    k_nachname VARCHAR(50) NOT NULL,
    k_firmenname VARCHAR(50) NOT NULL,
    k_strasse VARCHAR(50) NOT NULL,
    k_plz INT NOT NULL,
    k_ort VARCHAR(50) NOT NULL,
    k_email VARCHAR(255) NOT NULL,
    k_telefon VARCHAR(255) NOT NULL,
    k_webseite VARCHAR(255)

);

CREATE TABLE dienstleistung(

    dienstleistung_id INT AUTO_INCREMENT NOT NULL PRIMARY KEY,
    d_name VARCHAR(50) NOT NULL,
    d_paket VARCHAR(50) NOT NULL,
    d_preis DECIMAL(15,2) NOT NULL

);

CREATE TABLE vertraege (
    vertrag_id INT AUTO_INCREMENT PRIMARY KEY,
    kunden_id INT NOT NULL,
    benutzer_id INT NOT NULL,
    vertragsbeginndatum DATE DEFAULT CURRENT_DATE,
    bemerkungen TEXT,
    FOREIGN KEY(kunden_id) REFERENCES kunden(kunden_id),
    FOREIGN KEY(benutzer_id) REFERENCES mitarbeiter(benutzer_id)
);

CREATE TABLE vd (

    vertrag_id INT NOT NULL,
    dienstleistung_id INT NOT NULL,
    PRIMARY KEY(vertrag_id, dienstleistung_id),
    FOREIGN KEY(vertrag_id) REFERENCES vertraege(vertrag_id),
    FOREIGN KEY(dienstleistung_id) REFERENCES dienstleistung(dienstleistung_id)

);

-- Mitarbeiter hinzufügen
INSERT INTO mitarbeiter (b_vorname, b_nachname, b_abteilung, b_email, b_passwort) VALUES 
('Ronnyshan', 'George', 'admin', 'test1@marketingmaster.ch', 'T1'),
('Ronnyshan', 'George', 'verkaeufer', 'test2@marketingmaster.ch', 'T2'),
('Ronnyshan', 'George', 'buchhaltung', 'test3@marketingmaster.ch', 'T3');

-- Dienstleistungen hinzufügen
INSERT INTO dienstleistung (d_name, d_paket, d_preis) VALUES 
('Webseite', 'Bronze 1-3 Seiten', 5770.00),
('Webseite', 'Silber 4-10 Seiten', 7690.00),
('Webseite', 'Silber 11-20 Seiten', 10440.00);

-- Benutzer erstellen für die Webseite
DROP USER 'marketing'@'localhost';
CREATE USER 'marketing'@'localhost' IDENTIFIED BY 'Marketingmaster@483020';
GRANT DELETE, INSERT, SELECT ON marketingmaster.* TO 'marketing'@'localhost';
GRANT SELECT ON marketingmaster.vertraege TO 'marketing'@'localhost';
