create database rh_db;
use rh_db;

-- Table: departements
CREATE TABLE departements (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    description TEXT
);

-- Table: types_conge
CREATE TABLE types_conge (
    id INT AUTO_INCREMENT PRIMARY KEY,
    libelle VARCHAR(100) NOT NULL,
    jours_annuels INT NOT NULL,
    deductible BOOLEAN DEFAULT 0
);

create table employes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100),
    prenom VARCHAR(50)
    email VARCHAR(100) UNIQUE,
    password VARCHAR(100),
    role VARCHAR(),
    departement_id INT,
    date_embauche DATE,
    actif BOOLEAN DEFAULT 1,
    CONSTRAINT fk_employes_departements FOREIGN KEY (departement_id) REFERENCES departements(id)
);

-- Table: soldes
CREATE TABLE soldes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    employe_id INT NOT NULL,
    type_conge_id INT NOT NULL,
    annee YEAR NOT NULL,
    jours_attribues INT NOT NULL,
    jours_pris INT NOT NULL,
    FOREIGN KEY (employe_id) REFERENCES employes(id),
    FOREIGN KEY (type_conge_id) REFERENCES types_conge(id)
);

-- Table: conges
CREATE TABLE conges (
    id INT AUTO_INCREMENT PRIMARY KEY,
    employe_id INT NOT NULL,
    type_conge_id INT NOT NULL,
    date_debut DATE NOT NULL,
    date_fin DATE NOT NULL,
    nb_jours INT NOT NULL,
    motif TEXT,
    statut VARCHAR(50),
    commentaire_rh TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    traite_par INT,
    FOREIGN KEY (employe_id) REFERENCES employes(id),
    FOREIGN KEY (type_conge_id) REFERENCES types_conge(id),
    FOREIGN KEY (traite_par) REFERENCES employes(id)
);