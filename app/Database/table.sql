-- SQLite compatible schema
-- Créer la base de données SQLite
-- Pas besoin de "CREATE DATABASE" pour SQLite

-- Table: departements
CREATE TABLE departements (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    nom TEXT NOT NULL,
    description TEXT
);

-- Table: types_conge
CREATE TABLE types_conge (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    libelle TEXT NOT NULL,
    jours_annuels INTEGER NOT NULL,
    deductible INTEGER DEFAULT 0
);

-- Table: employes
CREATE TABLE employes (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    nom TEXT,
    prenom TEXT,
    email TEXT UNIQUE,
    password TEXT,
    role TEXT,
    departement_id INTEGER,
    date_embauche TEXT,
    actif INTEGER DEFAULT 1,
    FOREIGN KEY (departement_id) REFERENCES departements(id)
);

-- Table: soldes
CREATE TABLE soldes (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    employe_id INTEGER NOT NULL,
    type_conge_id INTEGER NOT NULL,
    annee TEXT NOT NULL,
    jours_attribues INTEGER NOT NULL,
    jours_pris INTEGER NOT NULL,
    FOREIGN KEY (employe_id) REFERENCES employes(id),
    FOREIGN KEY (type_conge_id) REFERENCES types_conge(id)
);

-- Table: conges
CREATE TABLE conges (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    employe_id INTEGER NOT NULL,
    type_conge_id INTEGER NOT NULL,
    date_debut TEXT NOT NULL,
    date_fin TEXT NOT NULL,
    nb_jours INTEGER NOT NULL,
    motif TEXT,
    statut TEXT,
    commentaire_rh TEXT,
    created_at TEXT DEFAULT CURRENT_TIMESTAMP,
    traite_par INTEGER,
    FOREIGN KEY (employe_id) REFERENCES employes(id),
    FOREIGN KEY (type_conge_id) REFERENCES types_conge(id),
    FOREIGN KEY (traite_par) REFERENCES employes(id)
);