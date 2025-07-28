# 📄 Documentation Technique – Système Sécurisé de Gestion des Utilisateurs (Laravel IAM)

## 🔰 Introduction

Ce projet a pour objectif de développer une application web sécurisée de gestion des utilisateurs, basée sur le framework Laravel. Il met en œuvre un système de gestion des identités et des accès (IAM) conforme aux standards de sécurité actuels, notamment le RGPD, et assure la protection des données via HTTPS/TLS.

---

## ⚙️ Installation et Mise en Œuvre Locale

### Prérequis
- PHP version 8.1 ou supérieure
- Composer
- MySQL ou SQLite
- Serveur local : Laragon ou Laravel Valet
- OpenSSL (optionnel mais recommandé pour HTTPS local)

### Procédure d’installation

1. **Cloner le dépôt GitHub** :
   ```bash
   git clone https://github.com/ton_nom_utilisateur/nom_du_repo.git
   cd nom_du_repo
   ```
2.Installer les dépendances :
   ```bash
     composer install
   ```
3.Configurer l’environnement :
   ```bash
    cp .env.example .env
    php artisan key:generate
   ```
4.Configurer la base de données dans .env :
  ```bash
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=sitesoremed
    DB_USERNAME=root
    DB_PASSWORD=
  ```  
5.Exécuter les migrations et seeders :
  ```bash
    php artisan migrate --seed
   ```
6.Lancer le serveur Laravel :
   ```bash
    php artisan serve
   ```
7.Accéder à l’application :
   - En HTTP : http://127.0.0.1:8000
   - En HTTPS (si configuré) : https://sitesoremed.test

## 🚀 Fonctionnalités Clés
### Gestion des utilisateurs:
L’application permet aux utilisateurs de s'inscrire, de se connecter et de gérer leur profil de manière sécurisée. Une fonctionnalité de suppression de compte est disponible afin de respecter le droit à l’oubli prévu par le RGPD. 

   - Inscription / Connexion sécurisées (bcrypt)
   - Tableau de bord personnel
   - Suppression de compte (droit à l’oubli, RGPD)

### Gestion des rôles et permissions (RBAC) 
Les utilisateurs sont classifiés par rôles (ex. : administrateur, manager,editor,viewer,user), chaque rôle disposant de permissions définies. Le contrôle d'accès est appliqué via des middlewares Laravel. 

### Interface administrateur 
L’administrateur a accès à un tableau de bord qui lui permet de : 

   - Interface d'administration avec visualisation de tous les utilisateurs. 
   - Attribuer ou retirer des rôles et des groupes. 
   - Accéder à un journal de sécurité listant les activités sensibles (tentatives de connexion échouées, suppression de comptes, activités critiques, etc.). 
   - Visualiser des statistiques sur les connexions et les événements sécuritaires. 
    -Visualiser et répondre aux messages/contact reçues 
    -Ajouter ou retirer des services, actualités ,etc.  

### RGPD – Conformité
Le système inclut un formulaire de consentement explicite lors de l’inscription. Les utilisateurs peuvent demander la suppression de leurs données personnelles, et les informations sensibles sont chiffrées pour assurer leur confidentialité. 


   - Consentement explicite à l'inscription .  
   - Droit à l'oubli : suppression complète et sécurisée des données .  
   - Chiffrement des données sensibles avec Laravel Encryption.  

## 🔐 Mesures de Sécurité
### Authentification sécurisée
Les mots de passe sont hachés avec l'algorithme bcrypt. Les accès aux différentes parties de l’application sont contrôlés par des middlewares basés sur les rôles. 

### Prévention des attaques
L’application intègre des dispositifs contre : 

   - Les attaques par force brute : limitation du nombre de tentatives de connexion avec Laravel Throttle.    
   - Les injections SQL : usage exclusif des requêtes préparées ou ORM Eloquent.   
   - Les attaques XSS : validation stricte et échappement des entrées utilisateurs. 

### Journalisation
Enregistrement des événements critiques dans une table dédiée (security_logs)

### Chiffrement des données
Les données sensibles (ex. : mot de passe ) sont chiffrées à l'aide du module Laravel Encryption. Les accès à ces données sont contrôlés par des mutateurs Eloquent. 

### HTTPS/TLS
Un certificat TLS auto-signé est utilisé pour les tests en local. L’application force l’utilisation du protocole HTTPS, avec en-têtes de sécurité tels que Content-Security-Policy, X-Frame-Options, et cookies sécurisés. 

## 🧪 Accès de Démonstration
Pour les tests, deux comptes sont fournis : 

   - Administrateur : admin@test.com / password .  
   - Utilisateur standard : user@test.com / password .  

## 📂 Structure du projet 
Les fichiers principaux sont organisés comme suit :  

   - Contrôleurs : app/Http/Controllers/  .
   - Vues : resources/views/ .  
   - Migrations : database/migrations/ . 
   - Routes : routes/web.php .  
   - Modèle personnalisé des rôles : app/Models/Role.php  .  
   - Seeder permissions : database/seeders/RoleAndPermissionSeeder.php

## 📚 Ressources utiles: 
   - Configuration HTTPS local : docs/https-setup.md .  
   - Laravel Sécurité : https://laravel.com/docs/10.x/security.  
   - Guide RGPD : https://gdpr.eu/developers/

## 📜 Licence
Ce projet est distribué sous licence MIT. Voir le fichier LICENSE pour plus de détails https://opensource.org/license/MIT.

## 👤 Contact
Auteur : Lafssal Fatima-Zahra    
GitHub : https://github.com/fatimazahar190     
Email : fatimazahralafssal@gmail.com
