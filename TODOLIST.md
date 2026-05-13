# Todo List - Système de Gestion RH (CodeIgniter 4)

Ce document détaille toutes les tâches nécessaires pour mener à bien le projet de système RH interne, en se basant sur les spécifications techniques.

---

## Phase 1 : Initialisation du Projet et Base de Données (20 min)

- [x] **Configuration de l'environnement**
    - [x] Configurer le fichier `.env` pour utiliser la base de données SQLite.
    - [x] S'assurer que le framework CodeIgniter 4 est correctement installé.

- [x] **Migrations de la Base de Données**
    - [x] Créer le fichier de migration pour la table `departements`.
    - [x] Créer le fichier de migration pour la table `types_conge`.
    - [x] Créer le fichier de migration pour la table `employes`.
    - [x] Créer le fichier de migration pour la table `soldes`.
    - [x] Créer le fichier de migration pour la table `conges`.
    - [x] Exécuter la commande `php spark migrate` pour créer les tables.

- [x] **Seeders (Données de test)**
    - [x] Créer un seeder principal `DatabaseSeeder`.
    - [x] Dans le seeder, ajouter :
        - [x] 1 utilisateur `admin`.
        - [x] 2 utilisateurs `employe`.
        - [x] 1 utilisateur `rh`.
        - [x] 2 `départements`.
        - [x] 3 `types_conge` (ex: Payé, Maladie, Spécial).
        - [x] Initialiser les soldes pour chaque employé pour chaque type de congé déductible.
    - [x] Exécuter `php spark db:seed` pour peupler la base de données.

- [x] **Routing Initial**
    - [x] Définir les groupes de routes `/employe`, `/rh`, `/admin`.
    - [x] Créer des contrôleurs squelettes (`EmployeController`, `RhController`, `AdminController`, `AuthController`).

---

## Phase 2 : Authentification et Gestion des Rôles (40 min)

- [ ] **Système de Connexion/Déconnexion**
    - [ ] Créer la vue du formulaire de connexion (`login.php`).
    - [ ] Implémenter la méthode `login()` dans `AuthController` qui vérifie l'email et le mot de passe (`password_verify`).
    - [ ] Stocker les informations utilisateur (`id`, `nom`, `role`) dans la session CI4.
    - [ ] Implémenter la méthode `logout()` pour détruire la session.
    - [ ] Rediriger l'utilisateur vers son tableau de bord respectif après connexion.

- [ ] **Filtres et Sécurité**
    - [ ] Créer un filtre `AuthFilter` qui vérifie si un utilisateur est connecté.
    - [ ] Appliquer le filtre aux groupes de routes `/employe`, `/rh`, `/admin`.
    - [ ] Dans chaque méthode de contrôleur, vérifier le rôle de l'utilisateur pour s'assurer qu'il a les droits d'accès.
    - [ ] Activer la protection CSRF sur tous les formulaires.

---

## Phase 3 : Espace Employé (60 min)

- [ ] **Tableau de bord Employé**
    - [ ] Afficher le solde de congés restant par type (`jours_attribues - jours_pris`).
    - [ ] Lister les demandes de congé de l'employé avec leur statut (`en_attente`, `approuvée`, `refusée`).

- [ ] **Soumission d'une Demande de Congé**
    - [ ] Créer le formulaire de demande (sélection du type de congé, date de début, date de fin, motif).
    - [ ] Dans le contrôleur :
        - [ ] Valider les données du formulaire (dates valides, solde suffisant, pas de chevauchement).
        - [ ] Calculer le nombre de jours ouvrables entre les dates.
        - [ ] Enregistrer la demande en base avec le statut `en_attente`.
        - [ ] Utiliser le pattern PRG (POST/Redirect/GET) avec un message flash de succès/erreur.

- [ ] **Annulation d'une Demande**
    - [ ] Ajouter un bouton "Annuler" pour les demandes avec le statut `en_attente`.
    - [ ] Implémenter la logique pour changer le statut à `annulee` ou supprimer la demande.

- [ ] **Profil Utilisateur**
    - [ ] Créer une page où l'employé peut modifier son nom et son mot de passe.

---

## Phase 4 : Espace RH (50 min)

- [ ] **Tableau de bord RH**
    - [ ] Afficher la liste de toutes les demandes de congé avec le statut `en_attente`.
    - [ ] Permettre de filtrer les demandes par statut ou département.

- [ ] **Traitement des Demandes**
    - [ ] Sur la vue d'une demande, afficher les détails complets (employé, dates, motif, solde restant de l'employé).
    - [ ] Ajouter des boutons "Approuver" et "Refuser".
    - [ ] Implémenter la logique `approve()`:
        - [ ] Changer le statut de la demande à `approuvée`.
        - [ ] **Mettre à jour la table `soldes` en déduisant les jours (`jours_pris`).**
        - [ ] Enregistrer qui a traité la demande (`traite_par`).
    - [ ] Implémenter la logique `refuse()`:
        - [ ] Changer le statut de la demande à `refusée`.
        - [ ] Ajouter un commentaire optionnel expliquant le refus.
        - [ ] Le solde de l'employé reste intact.

---

## Phase 5 : Back-Office Administrateur (30 min)

- [ ] **Gestion des Employés (CRUD)**
    - [ ] Lister tous les employés avec leurs informations (rôle, département).
    - [ ] Créer un formulaire pour ajouter un nouvel employé.
    - [ ] Créer un formulaire pour modifier un employé existant (changer son rôle, département, etc.).
    - [ ] Implémenter une fonctionnalité pour "désactiver" un employé (`actif = 0`).

- [ ] **Gestion des Entités**
    - [ ] CRUD complet pour les `départements`.
    - [ ] CRUD complet pour les `types_conge`.

- [ ] **Tableau de Bord Admin**
    - [ ] Afficher un résumé des absences du mois en cours.
    - [ ] Permettre d'ajuster manuellement le solde de congés d'un employé.

---

## Phase 6 : Finalisation et Finitions (20 min)

- [ ] **Interface et Expérience Utilisateur**
    - [ ] Créer un layout de base (`app.php`) avec une barre de navigation/sidebar.
    - [ ] La sidebar doit afficher des liens différents en fonction du rôle de l'utilisateur.
    - [ ] S'assurer que les messages flash (succès, erreur) sont affichés correctement.
    - [ ] Soigner la présentation des vues.

- [ ] **Documentation**
    - [ ] Mettre à jour le fichier `README.md`.
    - [ ] Inclure les instructions d'installation (`composer install`, `php spark migrate`, `php spark db:seed`).
    - [ ] Fournir les identifiants pour le compte `admin` et un compte `employe` de test.

- [ ] **Vérification Finale**
    - [ ] Tester le workflow complet d'une demande de congé.
    - [ ] Vérifier que les soldes sont correctement mis à jour.
    - [ ] S'assurer que les restrictions de rôle sont bien appliquées partout.
