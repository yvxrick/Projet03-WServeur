USE `projet03-wserveur_db`;

-- Drop tables in correct order (children first)
DROP TABLE IF EXISTS connexions;
DROP TABLE IF EXISTS annonces;
DROP TABLE IF EXISTS utilisateurs;
DROP TABLE IF EXISTS categories;


CREATE TABLE categories (
    NoCategorie INT(6) PRIMARY KEY,
    Description VARCHAR(20)
) ENGINE=InnoDB;


CREATE TABLE utilisateurs (
    NoUtilisateur INT(3) AUTO_INCREMENT PRIMARY KEY ,
    Courriel VARCHAR(50),
    MotDePasse VARCHAR(255),
    Creation DATETIME,
    NbConnexions INT(4),
    Statut INT,
    NoEmpl INT(4),
    Nom VARCHAR(25),
    Prenom VARCHAR(20),
    NoTelMaison VARCHAR(15),
    NoTelTravail VARCHAR(21),
    NoTelCellulaire VARCHAR(15),
    Modification DATETIME,
    AutresInfos VARCHAR(50)
) ENGINE=InnoDB;


CREATE TABLE connexions (
    NoConnexion INT(4) AUTO_INCREMENT PRIMARY KEY,
    NoUtilisateur INT(3),
    Connexion DATETIME,
    Deconnexion DATETIME,
    FOREIGN KEY (NoUtilisateur) REFERENCES utilisateurs(NoUtilisateur)
        ON DELETE CASCADE
        ON UPDATE CASCADE
) ENGINE=InnoDB;


CREATE TABLE annonces (
    NoAnnonce INT(4) AUTO_INCREMENT PRIMARY KEY,
    NoUtilisateur INT(3),
    Parution DATETIME,
    Categorie INT(6),
    DescriptionAbregee VARCHAR(50),
    DescriptionComplete VARCHAR(250),
    Prix DECIMAL(8,2),
    Photo VARCHAR(50),
    MiseAJour DATETIME,
    Etat INT,
    FOREIGN KEY (NoUtilisateur) REFERENCES utilisateurs(NoUtilisateur)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    FOREIGN KEY (Categorie) REFERENCES categories(NoCategorie)
        ON DELETE RESTRICT
        ON UPDATE CASCADE
) ENGINE=InnoDB;

INSERT INTO `categories` VALUES
(1, "Location"),
(2, "Recherche"),
(3, "À vendre"),
(4, "À donner"),
(5, "Service offert"),
(6, "Autre");

INSERT INTO utilisateurs (Courriel, MotDePasse, Creation)
VALUES
('user1@test.com', 'pass', NOW()),
('user2@test.com', 'pass', NOW()),
('user3@test.com', 'pass', NOW());

INSERT INTO annonces 
(NoAnnonce, NoUtilisateur, Parution, Categorie, DescriptionAbregee, DescriptionComplete, Prix, Photo, MiseAJour, Etat)
VALUES
(1, 1, '2026-04-22 14:37:12', 3, 'Voiture sport', 'Lamborghini en excellent état, très rapide', 50000.00, 'placeholder.jpg', '2026-04-23 09:12:45', 1),
(2, 2, '2026-04-21 08:15:33', 3, 'Vélo route', 'Vélo léger parfait pour longues distances', 300.00, 'placeholder.jpg', '2026-04-22 18:44:10', 1),
(3, 1, '2026-04-23 06:55:01', 5, 'Réparation PC', 'Service de réparation informatique rapide', 50.00, 'placeholder.jpg', '2026-04-23 07:20:59', 1),
(4, 3, '2026-04-20 19:22:48', 4, 'Canapé gratuit', 'Canapé 3 places à donner', 0.00, 'placeholder.jpg', '2026-04-21 10:05:14', 1),
(5, 2, '2026-04-23 11:03:27', 3, 'Télévision', 'TV 55 pouces en bon état', 450.00, 'placeholder.jpg', '2026-04-23 11:45:02', 1),
(6, 1, '2026-04-19 07:48:55', 1, 'Appartement', '4 1/2 à louer centre-ville', 1200.00, 'placeholder.jpg', '2026-04-20 08:12:36', 1),
(7, 3, '2026-04-22 23:59:59', 2, 'Recherche coloc', 'Cherche colocataire calme', 600.00, 'placeholder.jpg', '2026-04-23 00:10:22', 1),
(8, 2, '2026-04-18 12:30:10', 3, 'Chaise gaming', 'Chaise confortable ergonomique', 150.00, 'placeholder.jpg', '2026-04-19 14:55:44', 1),
(9, 1, '2026-04-23 02:14:07', 6, 'Divers objets', 'Boîte d’objets variés', 20.00, 'placeholder.jpg', '2026-04-23 03:01:18', 1),
(10, 3, '2026-04-21 16:09:41', 5, 'Cours maths', 'Cours particuliers niveau secondaire', 25.00, 'placeholder.jpg', '2026-04-22 09:33:27', 1);