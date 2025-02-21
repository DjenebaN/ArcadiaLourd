/*cree une bdd*/
CREATE DATABASE IF NOT EXISTS escapegame;

/*utiliser la bdd creer*/
USE escapegame;

-- Création de la table user_type d'abord
CREATE TABLE user_type (
    id INT PRIMARY KEY AUTO_INCREMENT,
    type_utilisateur VARCHAR(50) NOT NULL
);

-- Création de la table utilisateurs avec type d'utilisateur
CREATE TABLE utilisateurs (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(50) NOT NULL,
    prenom VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    mot_de_passe VARCHAR(255) NOT NULL,
    telephone VARCHAR(15),
    type_utilisateur INT,
    date_inscription DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (type_utilisateur) REFERENCES user_type(id)
);

-- Création de la table themes
CREATE TABLE themes (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(100) NOT NULL,
    description TEXT
);

-- Création de la table salles
CREATE TABLE salles (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(100) NOT NULL,
    image LONGBLOB,
    theme_id INT,
    description TEXT,
    duree INT NOT NULL, -- durée en minutes
    nb_joueurs_min INT NOT NULL,
    nb_joueurs_max INT NOT NULL,
    prix INT NOT NULL, -- en euros
    FOREIGN KEY (theme_id) REFERENCES themes(id)
);

-- Création de la table horaires
CREATE TABLE horaires (
    id INT PRIMARY KEY AUTO_INCREMENT,
    salle_id INT,
    heure_debut DATETIME NOT NULL,
    heure_fin DATETIME NOT NULL,
    disponible BOOLEAN DEFAULT true,
    FOREIGN KEY (salle_id) REFERENCES salles(id) 
);

-- Création de la table reservations
CREATE TABLE reservations (
    id INT PRIMARY KEY AUTO_INCREMENT,
    utilisateur_id INT,
    salle_id INT,
    horaire_id INT,
    nb_participants INT NOT NULL,
    date_reservation DATETIME DEFAULT CURRENT_TIMESTAMP,
    prix_total INT NOT NULL, -- en euros
    FOREIGN KEY (utilisateur_id) REFERENCES utilisateurs(id),
    FOREIGN KEY (salle_id) REFERENCES salles(id) ,
    FOREIGN KEY (horaire_id) REFERENCES horaires(id)
);

-- Création de la table gamemaster_status
CREATE TABLE gamemaster_status (
    id INT PRIMARY KEY AUTO_INCREMENT,
    statut_desc VARCHAR(255) -- Status du gamemaster
);

-- Création de la table pour l'assignation des gamemasters
CREATE TABLE gamemaster_assignments (
    id INT PRIMARY KEY AUTO_INCREMENT,
    reservation_id INT,
    gamemaster_id INT,
    statutGM INT,
    FOREIGN KEY (reservation_id) REFERENCES reservations(id) ON DELETE CASCADE,
    FOREIGN KEY (gamemaster_id) REFERENCES utilisateurs(id),
    FOREIGN KEY (statutGM) REFERENCES gamemaster_status(id),
    CONSTRAINT unique_gamemaster_reservation UNIQUE (reservation_id, gamemaster_id)
);

/*Insertion de donnée de test*/

-- Insertion des types d'utilisateurs
INSERT INTO user_type (type_utilisateur) VALUES
    ('Utilisateur'),
    ('gamemaster'),
    ('admin');

-- Insertion des utilisateurs de test
INSERT INTO utilisateurs (nom, prenom, email, mot_de_passe, telephone, type_utilisateur) VALUES
    ('Doe', 'John', 'john.doe@escapegame.com', '12345', '0777777777', 1),
    ('Doe', 'Jane', 'jane.doe@escapegame.com', '12345', '0777777778', 2),
    ('Doe', 'Bob', 'bob.doe@escapegame.com', '12345', '0777777779', 1),
    ('Doe', 'Alice', 'alice.doe@escapegame.com', '12345', '0777777780', 3);

-- Insertion des thèmes
INSERT INTO themes (nom, description) VALUES
    ('Mystère', 'Découvrez les mystères de l''univers'),
    ('Aventure', 'Partez à l''aventure'),
    ('Horreur', 'Faites face à vos peurs');

-- Insertion des salles
INSERT INTO salles (nom, theme_id, description, duree, nb_joueurs_min, nb_joueurs_max, prix) VALUES
    ('La Crypte', 1, 'Enigme et mystère', 60, 2, 6, 20),
    ('La Jungle', 2, 'Survivrez-vous à la jungle', 60, 2, 6, 20),
    ('La Maison Hantee', 3, 'Faites face à vos peurs', 60, 2, 6, 20);

-- Insertion des horaires
INSERT INTO horaires (salle_id, heure_debut, heure_fin) VALUES
    (1, '2024-01-01 10:00:00', '2024-01-01 11:00:00'),
    (2, '2024-01-01 10:00:00', '2024-01-01 11:00:00'),
    (3, '2024-01-01 10:00:00', '2024-01-01 11:00:00');

-- Insertion des statuts de gamemaster
INSERT INTO gamemaster_status (statut_desc) VALUES
    ('Disponible'),
    ('En service'),
    ('Indisponible');

CREATE TABLE status_res (
    id INT PRIMARY KEY AUTO_INCREMENT,
    etat VARCHAR(50) NOT NULL
);

INSERT INTO status_res (id, etat) VALUES
    (1, 'En Attente'),
    (2, 'Validée'),
    (3, 'Annulée');

ALTER TABLE reservations
ADD COLUMN status_id INT DEFAULT 1,
ADD CONSTRAINT FK_statusRes FOREIGN KEY (status_id) REFERENCES status_res(id);
