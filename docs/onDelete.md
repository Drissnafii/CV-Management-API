## 2. `->onDelete('cascade')` - Le Comportement de Suppression en Cascade

### **Clé Étrangère et Intégrité Référentielle**

Vous avez déjà défini une clé étrangère sur la colonne `user_id` qui référence la colonne `id` de la table `users` :

```php
$table->foreign('user_id')->references('id')->on('users');
```

Les clés étrangères servent à maintenir l'**intégrité référentielle** dans votre base de données. Elles garantissent que les relations entre les tables sont cohérentes.

### **`onDelete('cascade')` - Suppression en Cascade**

L'option `->onDelete('cascade')` spécifie ce qui doit se passer lorsque vous supprimez un enregistrement dans la table **parente** (ici, `users`). Avec `onDelete('cascade')`, vous dites à la base de données :

> "Si un utilisateur est supprimé de la table `users`, alors **supprime automatiquement** tous les CVs associés à cet utilisateur dans la table `cvs`."

### **Pourquoi Utiliser `onDelete('cascade')` dans ce Cas ?**

Dans le contexte de votre application, il est logique d'utiliser `onDelete('cascade')` pour la relation entre les utilisateurs et les CVs. Si un utilisateur quitte la plateforme et que son compte est supprimé, il est probable que vous souhaitiez également supprimer ses CVs associés.

### **Autres Options pour `onDelete`**

- **`restrict` (ou `no action`)** : Empêche la suppression d'un parent s'il existe des enfants associés.
- **`set null`** : Lorsque l'enregistrement parent est supprimé, la valeur de la clé étrangère dans les enregistrements enfants est mise à `NULL`.
- **`cascade`** : Supprime les enregistrements enfants lorsqu'on supprime le parent.

### **Choisir la Bonne Option `onDelete`**

- **`cascade`** : Utilisé pour les relations où les enfants doivent être supprimés avec le parent.
- **`restrict`** : Empêche la suppression d'un parent tant que des enfants existent.
- **`set null`** : Conserve les enregistrements enfants mais rompt le lien avec le parent.

Dans votre cas, `onDelete('cascade')` est probablement un choix judicieux.

## **Résumé**

- **`onDelete('cascade')` (`->onDelete('cascade')`)** : Supprime automatiquement les CVs lorsqu'un utilisateur est supprimé, maintenant l'intégrité des données.
