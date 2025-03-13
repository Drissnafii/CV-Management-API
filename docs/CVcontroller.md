# Implémentation du `CVController`

Ce contrôleur gère les opérations liées aux CVs via l'API.

**Méthodes :**

* **`index()`**: Récupère la liste des CVs de l'utilisateur authentifié.
* **`store(Request $request)`**: Permet à un utilisateur d'uploader un nouveau CV, en validant les données et en stockant le fichier sur S3.
* **`show($id)`**: Récupère les informations d'un CV spécifique de l'utilisateur authentifié.
* **`destroy($id)`**: Supprime un CV spécifique de l'utilisateur authentifié (supprime également le fichier de S3).
* **`download($id)`**: Génère une URL temporaire pour télécharger un CV spécifique de l'utilisateur authentifié depuis S3.

**Points Clés :**

* Utilise l'authentification pour s'assurer que les utilisateurs ne gèrent que leurs propres CVs.
* Effectue une validation des données (titre et fichier).
* Stocke les fichiers CV sur AWS S3 (ou un service compatible).
* Enregistre les informations du CV dans la base de données.
* Fournit une URL temporaire et sécurisée pour le téléchargement des CVs.
* Gère les erreurs (validation, CV non trouvé) en renvoyant des réponses JSON appropriées.

**Diagramme de Séquence (Upload de CV - Méthode `store`) :**

```mermaid
sequenceDiagram
    participant User
    participant Client (e.g., Browser, Mobile App)
    participant CVController
    participant Validator
    participant CV Model
    participant S3 Storage
    participant Database

    Client->>CVController: POST /api/cvs avec titre et fichier CV
    CVController->>Validator: Valider la requête (titre, cv_file)
    alt Validation échoue
        Validator-->>CVController: Erreurs de validation
        CVController-->>Client: Réponse JSON (status: error, errors) avec code 422
    else Validation réussit
        CVController->>CVController: Générer un nom de fichier unique
        CVController->>S3 Storage: Envoyer le fichier CV pour stockage ('cvs/{user_id}/...')
        S3 Storage-->>CVController: Chemin du fichier stocké (filePath)
        CVController->>Database: Créer un nouvel enregistrement CV (user_id, title, filePath, fileName, fileType, fileSize)
        Database-->>CVController: Objet CV créé
        CVController-->>Client: Réponse JSON (status: success, message, data: CV) avec code 201
    end
