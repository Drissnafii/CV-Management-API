## Configuration de Laravel Sanctum

Pour configurer Laravel Sanctum pour l'authentification API, suis ces étapes :

1.  **Installer le paquet Sanctum via Composer :**
    ```bash
    composer require laravel/sanctum
    ```
    *   Télécharge et installe le paquet Sanctum.
    *   Met à jour `composer.json` et `composer.lock`.

2.  **Publier les fichiers de configuration et de migration de Sanctum :**
    ```bash
    php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
    ```
    *   Copie `config/sanctum.php` dans ton répertoire `config`.
    *   Copie le fichier de migration pour la table `personal_access_tokens` dans ton répertoire `database/migrations`.

3.  **Exécuter les migrations pour créer la table `personal_access_tokens` :**
    ```bash
    php artisan migrate
    ```
    *   Crée la table `personal_access_tokens` dans ta base de données, nécessaire pour stocker les jetons API de Sanctum.

