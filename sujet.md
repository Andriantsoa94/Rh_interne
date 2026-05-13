# Spécifications Techniques : Système RH Interne - Entreprise TechMada

Ce document regroupe l'ensemble des directives, fonctionnalités et structures pour le développement du système de gestion des congés en binôme.

## 1. Présentation du Projet
L'objectif est de créer un système RH interne où les employés soumettent des demandes de congé, les responsables RH les valident/refusent, et les administrateurs supervisent l'ensemble via un tableau de bord.

* **Framework :** CodeIgniter 4 (CI4)
* **Durée cible :** 4h en binôme
* **Concepts clés :** Sessions CI4, 3 rôles utilisateurs, Logique métier côté serveur, Solde calculé dynamiquement.

---

## 2. Fonctionnalités par Rôle

### 👤 Employé (`role: employe`)
*Rôle par défaut à l'inscription.*
* **Connexion / déconnexion** (Obligatoire)
* **Soumettre une demande de congé** (type, dates, motif) (Obligatoire)
* **Consulter ses propres demandes et leurs statuts** (Obligatoire)
* **Voir son solde de congés restant par type** (Obligatoire)
* Annuler une demande encore en attente
* Modifier son profil (nom, mot de passe)

### 👥 Responsable RH (`role: rh`)
*Valide les demandes de son équipe.*
* **Voir toutes les demandes en attente** (Obligatoire)
* **Approuver ou refuser une demande** (avec commentaire optionnel) (Obligatoire)
* **Mise à jour automatique du solde à l'approbation** (Obligatoire)
* Filtrer les demandes par département ou statut
* Voir le solde de chaque employé

### 🛡️ Administrateur (`role: admin`)
*Gestion complète du système.*
* **CRUD employés** (créer, éditer, désactiver) (Obligatoire)
* **CRUD départements et types de congé** (Obligatoire)
* **Tableau de bord : absences du mois en cours** (Obligatoire)
* Initialiser / ajuster le solde annuel d'un employé
* Voir l'historique complet de toutes les demandes

---

## 3. Workflow & Logique Métier

### Processus d'une demande
1.  **Employé soumet** → Statut: `en_attente`
2.  **Décision RH** :
    * `approuvée` → **Solde déduit**
    * `refusée` → **Solde intact**

> **Note cruciale :** Le solde est déduit **uniquement** à l'approbation. Si une demande est annulée ou refusée après approbation, le solde doit être recrédité.

### Schéma de Base de Données (5 Tables)
1.  **employes** : `id (PK)`, `nom`, `prenom`, `email (UNIQUE)`, `password`, `role`, `departement_id (FK)`, `date_embauche`, `actif (0/1)`.
2.  **departements** : `id (PK)`, `nom`, `description`.
3.  **types_conge** : `id (PK)`, `libelle`, `jours_annuels`, `deductible (0/1)`.
4.  **soldes** : `id (PK)`, `employe_id (FK)`, `type_conge_id (FK)`, `annee`, `jours_attribues`, `jours_pris`.
5.  **conges** : `id (PK)`, `employe_id (FK)`, `type_conge_id (FK)`, `date_debut`, `date_fin`, `nb_jours`, `motif`, `statut`, `commentaire_rh`, `created_at`, `traite_par (FK->employes)`.

### Calcul du Solde
* `nb_jours_restant = jours_attribues - jours_pris`
* **À l'approbation :** `UPDATE soldes SET jours_pris = jours_pris + $nb_jours WHERE ...`
* **Si refus après approbation (annulation) :** `UPDATE soldes SET jours_pris = jours_pris - $nb_jours`
* **Vérification :** Toujours vérifier que `jours_pris + nb_jours_demandés <= jours_attribues` avant d'approuver.

---

## 4. Directives Techniques (CI4)

### Authentification & Rôles
* Session CI4 native, `password_hash()` obligatoire.
* Filtre `AuthFilter` sur toutes les routes protégées.
* 3 groupes de routes : `/employe`, `/rh`, `/admin`.
* Vérification du rôle dans chaque contrôleur.
* CSRF activé sur tous les formulaires POST.

### Modèles & Données
* 1 Model CI4 par table avec règles de validation.
* Migrations dans l'ordre : `departements` → `types_conge` → `employes` → `soldes` → `conges`.
* Calcul du nombre de jours via `date_diff()` (SQL) ou Carbon (PHP).
* Utilisation du **Query Builder** uniquement (pas de SQL brut).
* Seeder : 1 admin, 2 employés, 3 types de congé, soldes initialisés.

### Routing & Structure
* Pattern PRG : POST → redirect après toute écriture.
* Flashdata CI4 pour les messages succès/erreur.
* Layout partagé `layout/app.php` + sidebar dynamique selon le rôle.
* Vues séparées : `employe/`, `rh/`, `admin/`.
* **Aucun JavaScript métier** — tout est géré côté serveur.

### Calcul des Jours
* Calculer les jours ouvrables uniquement (exclure week-ends).
* Bloquer si `date_debut >= date_fin`.
* Bloquer si solde insuffisant (message flash explicite).
* Bloquer les chevauchements de dates.

---

## 5. Organisation & Livrables

### Découpage du temps (4H)
1.  **20min - Setup & BDD :** Migrations + seeder + routes squelette.
2.  **40min - Authentification :** Login, session, filtre 3 rôles.
3.  **60min - Espace Employé :** Soumettre, lister, solde, annuler.
4.  **50min - Espace RH :** Approuver/refuser + MAJ solde.
5.  **30min - Back-office Admin :** CRUD employés + dashboard.
6.  **20min - Finition :** Template, flashdata, README.

### Livrables attendus
* Code source complet (structure CI4 standard).
* Migrations + Seeder fonctionnels (`php spark migrate && php spark db:seed`).
* Fonctionnalités obligatoires opérationnelles pour les 3 rôles.
* README : instructions + compte admin + compte employé de test.