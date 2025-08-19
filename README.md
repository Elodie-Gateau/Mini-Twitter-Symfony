# Mini Twitter – Symfony

Projet pédagogique de type **mini-réseau social** développé avec Symfony.  
Il permet de publier des tweets, commenter, liker, retweeter et administrer les utilisateurs.

---

## ✨ Fonctionnalités

- **Utilisateurs**
  - Inscription, connexion, déconnexion
  - Profils avec photo, édition des infos
  - Bannissement (bloque la connexion via `UserChecker`)

- **Tweets**
  - Créer, éditer, supprimer ses tweets
  - Ajouter une image (upload)
  - Retweeter et voir le compteur de retweets
  - Signaler un tweet inapproprié

- **Commentaires**
  - Ajouter, éditer, supprimer un commentaire
  - Ajouter une image au commentaire
  - Signaler un commentaire
  - Pagination des commentaires

- **Likes**
  - Liker/déliker un tweet ou un commentaire
  - Mise à jour possible en AJAX

- **Administration**
  - Panel admin réservé au rôle `ROLE_ADMIN`
  - Gérer les utilisateurs (ban/unban, suppression)
  - Gérer les tweets & commentaires signalés
  - Suppression / désignalement

---

## 🛠️ Stack technique

- **Backend** : PHP 8.1+, Symfony (Framework MVC)
- **Frontend** : Twig, TailwindCSS (classes utilitaires), Font Awesome
- **Base de données** : MySQL/MariaDB avec Doctrine ORM
- **Gestion des fichiers** : Upload d’images (tweets, commentaires, avatars)

---

## 🚀 Installation & utilisation

1. **Cloner le projet**
```bash
git clone https://github.com/Elodie-Gateau/Mini-Twitter-Symfony.git
cd Mini-Twitter-Symfony
```

2. **Installer les dépendances**
```bash
composer install
npm install   # si assets frontend
```

3. **Configurer l’environnement**
```bash
cp .env .env.local
# Modifier DATABASE_URL avec vos identifiants
```

4. **Créer la base et appliquer le schéma**
- Méthode recommandée (Doctrine migrations) :
```bash
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate
```

- Méthode alternative (dump SQL inclus) :  
Un fichier **`sql/mini-twitter-base.sql`** est fourni.  
Vous pouvez l’importer directement via phpMyAdmin ou en ligne de commande :
```bash
mysql -u user -p mini-twitter < sql/mini-twitter-base.sql
```

5. **Lancer le serveur Symfony**
```bash
symfony serve -d
```

Accéder à l’application : [http://127.0.0.1:8000](http://127.0.0.1:8000)

---

## 🔐 Sécurité

- Rôles gérés : `ROLE_USER`, `ROLE_ADMIN`
- Bannissement : champ `isBanned` vérifié au login (empêche la connexion)
- CSRF actif sur toutes les actions sensibles (formulaires)

---

## 👩‍💻 Auteurs

Développé par **Marion Courtois**, **Pierre Leboeuf**, **Sébastien Fournier** et **Élodie Gateau** dans le cadre d’un projet pédagogique collaboratif.
