## Pourquoi ajouter le middleware Sanctum dans `Kernel.php` ?

**En résumé : OUI, c'est généralement nécessaire et fortement recommandé, surtout pour l'authentification stateful frontend.**

**Explication :**

1.  **`Kernel.php` : Le chef d'orchestre des requêtes HTTP Laravel**
    *   Point d'entrée pour toutes les requêtes HTTP.
    *   Responsable du bootstrap de l'application et de la gestion des **middlewares**.

2.  **Middlewares : Des filtres pour les requêtes HTTP**
    *   Série de filtres que chaque requête traverse avant d'atteindre ton code.
    *   Rôles : Authentification, sécurité, logging, modification des requêtes/réponses, etc.

3.  **Groupe de middlewares `'api'` dans `Kernel.php` :**
    *   Appliqué à toutes les routes définies dans `routes/api.php`.
    *   Configure des filtres spécifiques pour tes APIs.

4.  **Middlewares dans le groupe `'api'` (et leur importance) :**

    *   **`\Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class` (Sanctum) :**
        *   **Nécessaire** pour l'authentification **stateful** de Sanctum avec les frontends (sessions et cookies).
        *   Indispensable si tu veux utiliser cette méthode d'authentification.

    *   **`\Illuminate\Routing\Middleware\ThrottleRequests::class.':api'` (Limitation de débit) :**
        *   **Fortement recommandé** pour la sécurité de ton API.
        *   Protège contre les abus (attaques DDoS, brute-force) en limitant le nombre de requêtes.

    *   **`\Illuminate\Routing\Middleware\SubstituteBindings::class` (Substitution des bindings) :**
        *   **Utile et recommandé** pour la clarté du code.
        *   Simplifie l'injection automatique de modèles dans les contrôleurs basés sur les paramètres de route.

**Conclusion :** Ajouter ces middlewares au `Kernel.php` est une **excellente pratique** pour activer l'authentification stateful de Sanctum, sécuriser ton API et améliorer la qualité de ton code. C'est une étape importante pour construire des APIs Laravel modernes et robustes.
