# Correction d'une Erreur MassAssignmentException lors de la Création d'une Compétence

**Problème :** Une erreur `Illuminate\Database\Eloquent\MassAssignmentException` s'est produite lors de la tentative de création d'une compétence avec la méthode `create()`.

**Cause de l'Erreur :** Laravel protège par défaut contre l'assignation massive des attributs qui ne sont pas explicitement autorisés dans le modèle. L'attribut `name` n'était pas spécifié comme étant "fillable".

**Solution :** Définir la propriété `$fillable` dans le modèle `App\Models\Skill` pour autoriser l'assignation massive de l'attribut `name`.

**Étapes de Correction :**

1.  Ouvrir le fichier `app/Models/Skill.php`.
2.  Ajouter ou modifier la propriété `$fillable` pour inclure l'attribut `'name'` :
    ```php
    protected $fillable = ['name'];
    ```
3.  Ré-exécuter le code de création de la compétence.

**Bonnes Pratiques :**

* Être précis dans la définition des attributs autorisés dans la propriété `$fillable`.
* Utiliser la propriété `$guarded` avec prudence.
* Envisager l'utilisation de l'opérateur `new` et de la méthode `save()` comme alternative à `create()` dans certains cas.
