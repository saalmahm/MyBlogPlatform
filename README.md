# myblogplatform 📝 | Plateforme de Blog avec Gestion des Utilisateurs et des Rôles

## Description du projet

Ce projet consiste à développer une plateforme de blog où les utilisateurs peuvent s'inscrire, écrire, commenter, et gérer leurs articles. Les administrateurs peuvent modérer et gérer les utilisateurs, tandis que les utilisateurs ont des rôles définis pour interagir avec la plateforme. La gestion des rôles (utilisateur, administrateur) est essentielle pour assurer la sécurité et la modération des contenus. 🔒

### Fonctionnalités principales

- **Authentification et gestion des utilisateurs** 🔑 :
  - Inscription des utilisateurs avec email, nom d'utilisateur, et mot de passe.
  - Connexion sécurisée avec session.
  - Déconnexion sécurisée.

- **Gestion des articles de blog** ✍️ :
  - Création, modification, et suppression d'articles avec titre, contenu, et image.
  - Accès à la lecture des articles par tous les utilisateurs, y compris les visiteurs.

- **Système de commentaires** 💬 :
  - Les utilisateurs peuvent commenter et aimer les articles.
  - Modération des commentaires par les administrateurs.

- **Gestion des rôles** 👑 :
  - Les administrateurs peuvent attribuer des rôles aux utilisateurs (utilisateur, administrateur).
  - Modération des articles et commentaires par les administrateurs.

- **Gestion des catégories** 📂 :
  - Les administrateurs peuvent créer, modifier, et supprimer des catégories d'articles.
  - Les utilisateurs peuvent attribuer des catégories à leurs articles.

### Technologies utilisées ⚙️

- **Backend** : PHP 
- **Base de données** : MySQL
- **Contrôle de version** : Git
- **Sécurité** : 
  - Hashage des mots de passe.
  - Prévention des injections SQL avec des requêtes préparées (MySQLi).
  - Sécurisation des sessions.
  - Protection contre les attaques XSS et CSRF.

## Diagrammes et architecture 📊

- Diagramme **ERD** pour la base de données.
- Diagramme **UML** pour les cas d'utilisation.
