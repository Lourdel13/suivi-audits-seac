-- Base de données du suivi d'audits (MySQL / MariaDB)
CREATE DATABASE IF NOT EXISTS seac_audits CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE seac_audits;

DROP TABLE IF EXISTS audits;
DROP TABLE IF EXISTS sites;

CREATE TABLE sites (
    id    INT AUTO_INCREMENT PRIMARY KEY,
    nom   VARCHAR(100) NOT NULL,
    ville VARCHAR(100) NOT NULL
);

CREATE TABLE audits (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    site_id     INT NOT NULL,
    date_prevue DATE NOT NULL,
    statut      ENUM('a_planifier', 'planifie', 'realise') NOT NULL DEFAULT 'a_planifier',
    type_audit  VARCHAR(100) NOT NULL DEFAULT '',
    commentaire TEXT,
    FOREIGN KEY (site_id) REFERENCES sites(id)
);
-- Le "retard" n'est pas stocké : il est calculé (date dépassée et statut différent de "réalisé").
