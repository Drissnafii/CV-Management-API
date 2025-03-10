# 📦 API Job Application Platform

## 🚀 Introduction
Cette API pour une plateforme de candidature d'emploi est développée avec Laravel 11. Elle permet la gestion des candidatures, offres d'emploi et CVs avec un traitement asynchrone pour améliorer les performances.

## 🛠️ Technologies Utilisées
- **Framework** : Laravel 12 / PHP 8.3
- **Authentification** : JWT / Laravel Sanctum
- **Base de données** : MySQL
- **Queues** : Redis / Database
- **Stockage des CVs** : AWS S3 / DigitalOcean Spaces / Local Storage
- **Tests** : PHPUnit / Pest (Bonus)
- **Documentation API** : Swagger / OpenAPI / Postman

## 📐 Architecture
L'API suit une architecture RESTful avec :
- 📌 Versionnement des endpoints
- ✅ Structure de réponse cohérente
- 🔐 Authentification par token (JWT/Sanctum)
- 🧵 Traitement asynchrone (Queues Laravel)

## 🔗 Endpoints Principaux

### 🔑 1. Gestion des Utilisateurs
- 🔹 Inscription : `POST /api/v1/auth/register`
- 🔹 Connexion : `POST /api/v1/auth/login`
- 🔹 Profil : `GET /api/v1/auth/profile`
- 🔹 Mise à jour du profil : `PUT /api/v1/auth/profile`

### 💼 2. Gestion des Offres d'Emploi
- 📜 Lister : `GET /api/v1/jobs`
- 🔍 Voir une offre : `GET /api/v1/jobs/{id}`
- ➕ Créer : `POST /api/v1/jobs` (Recruteurs)
- ✏️ Modifier : `PUT /api/v1/jobs/{id}` (Recruteurs)
- ❌ Supprimer : `DELETE /api/v1/jobs/{id}` (Recruteurs)
- 🔎 Filtrer : `GET /api/v1/jobs?category=&location=&type=` (Bonus)

### 📄 3. Gestion des CVs
- 📜 Lister : `GET /api/v1/cvs`
- ⬆️ Upload : `POST /api/v1/cvs`
- ❌ Supprimer : `DELETE /api/v1/cvs/{id}`

### 📝 4. Gestion des Candidatures
- 📜 Lister : `GET /api/v1/applications`
- ➕ Postuler : `POST /api/v1/applications`
- 🔍 Voir une candidature : `GET /api/v1/applications/{id}`
- 📊 Postuler à plusieurs offres : `POST /api/v1/applications/batch`

## 🗄️ Modèles de Données

### 👤 1. Utilisateur (users)
| Champ | Type | Description |
|-------|------|-------------|
| id | int | Identifiant unique |
| name | string | Nom de l'utilisateur |
| email | string | Adresse e-mail |
| password | string | Mot de passe |
| phone | string | Numéro de téléphone |
| skills | json | Compétences de l'utilisateur |
| role | string | Rôle (candidat, recruteur) |
| email_verified_at | timestamp | Vérification e-mail |
| remember_token | string | Jeton de session |
| timestamps | timestamp | Dates de création et mise à jour |

### 💼 2. Offre d'Emploi (jobs)
| Champ | Type | Description |
|-------|------|-------------|
| id | int | Identifiant unique |
| title | string | Titre de l'offre |
| description | text | Description complète |
| company | string | Nom de l'entreprise |
| location | string | Localisation |
| category | string | Catégorie |
| contract_type | string | Type de contrat |
| salary | decimal | Salaire proposé |
| recruiter_id | int | ID du recruteur |
| status | string | État de l'offre |
| timestamps | timestamp | Dates de création et mise à jour |

### 📄 3. CV (cvs)
| Champ | Type | Description |
|-------|------|-------------|
| id | int | Identifiant unique |
| user_id | int | Utilisateur propriétaire |
| file_path | string | Chemin du fichier sur S3/DO |
| file_name | string | Nom original du fichier |
| file_size | int | Taille du fichier en octets |
| mime_type | string | Type de fichier (PDF/DOCX) |
| summary | text | Résumé généré (bonus) |
| is_default | boolean | CV par défaut |
| timestamps | timestamp | Dates de création et mise à jour |

### 📝 4. Candidature (applications)
| Champ | Type | Description |
|-------|------|-------------|
| id | int | Identifiant unique |
| user_id | int | Candidat |
| job_id | int | Offre d'emploi |
| cv_id | int | CV utilisé |
| status | string | État (pendante, acceptée, rejetée) |
| timestamps | timestamp | Dates de création et mise à jour |

## 🧵 Traitement Asynchrone avec Queues

### 📧 1. E-mails de Confirmation
- Queue pour l'envoi des e-mails après chaque candidature
- Classe: `App\Jobs\SendApplicationConfirmationJob`

### 📊 2. Export CSV des Candidatures
- Queue pour générer et envoyer des rapports CSV aux recruteurs
- Classe: `App\Jobs\GenerateApplicationsReportJob`
- Planification hebdomadaire via Task Scheduler

### 📄 3. Analyse automatique des CVs (Bonus)
- Queue pour l'extraction de texte et génération de résumé
- Classe: `App\Jobs\AnalyzeCVJob`

## 🛡️ Gestion des Rôles

### 🎭 Rôles
- 👤 candidat
- 🏢 recruteur

## 🧪 Plan de Tests
- ✅ Tests unitaires pour chaque endpoint
- ✅ Tests de fonctionnement des Queues
- ✅ Tests d'upload et stockage des CVs

## 📂 Organisation du Code
```
📂 app
 ├── 📁 Http
 │   ├── 📂 Controllers
 │   │   └── 📂 Api/V1
 │   ├── 📂 Requests
 │   ├── 📂 Resources
 ├── 📁 Models
 ├── 📁 Jobs
 ├── 📁 Services
 ├── 📂 routes
 │   ├── api.php
 ├── 📂 tests
 │   ├── Feature/Api/V1
```

## 📅 Planning de Développement (5 jours)

### 📆 Jour 1
- ✅ Initialisation du projet Laravel 11
- ✅ Configuration de l'authentification (JWT/Sanctum)
- ✅ Mise en place de la base de données et migrations

### 📆 Jour 2
- ✅ Développement des endpoints utilisateurs
- ✅ Développement des endpoints offres d'emploi
- ✅ Configuration du stockage S3/DO pour les CVs

### 📆 Jour 3
- ✅ Développement des endpoints de gestion des CVs
- ✅ Développement des endpoints de candidature
- ✅ Configuration des Queues Laravel

### 📆 Jour 4
- ✅ Implémentation des Jobs (emails, rapports CSV)
- ✅ Développement de l'endpoint de candidature multiple
- ✅ Écriture des tests unitaires

### 📆 Jour 5
- ✅ Développement des fonctionnalités bonus (analyse CV, filtrage)
- ✅ Documentation API (Swagger/Postman)
- ✅ Déploiement et finalisation du projet

## 🚀 Mise en Production
1. Cloner le repo Git
2. Installer les dépendances avec `composer install`
3. Configurer `.env` avec les credentials pour MySQL, Redis et S3/DO
4. Lancer les migrations avec `php artisan migrate`
5. Démarrer les workers de Queue avec `php artisan queue:work`
6. Démarrer le serveur avec `php artisan serve`

## 📤 Livraisons
- Scrum Board avec les User Stories
- Lien du repository GitHub
- Lien du projet déployé (Digital Ocean, Hostinger, AWS)
- Documentation API (Swagger/Postman)
