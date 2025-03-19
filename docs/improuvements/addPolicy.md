# Bonnes Pratiques & Améliorations Possibles

## Classes de requête spécifiques aux actions
Comme discuté, créer des classes de requête dédiées pour chaque action de contrôleur qui traite des données utilisateur améliore la clarté, la maintenabilité et la testabilité de ton code. Cela permet de définir précisément les règles de validation et d'autorisation pour chaque opération.

## Utilisation des politiques d'autorisation
Pour la vérification de l'autorisation (comme s'assurer que l'utilisateur connecté est le propriétaire du CV), l'utilisation des politiques d'autorisation de Laravel est une excellente pratique. Cela centralise ta logique d'autorisation et la rend plus facile à gérer.
