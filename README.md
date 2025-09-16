
# Famille Paul-Edourd

Site web familial moderne, sécurisé et responsive pour la famille Paul-Edourd.

## Présentation

Ce projet permet à chaque membre de la famille de :
- Créer un compte et gérer son profil
- Partager des photos et souvenirs
- Visualiser et enrichir l’arbre généalogique
- Publier et lire des articles de blog (validation admin)
- Organiser et consulter les événements familiaux (anniversaires, réunions…)
- Discuter en toute confidentialité

## Fonctionnalités principales

- Authentification sécurisée (rôles, validation admin)
- Galerie photo privée
- Blog familial avec validation
- Arbre généalogique interactif et graphique
- Gestion des événements (anniversaires, événements personnalisés)
- Responsive design (Bootstrap 5)
- Sauvegarde et restauration de la base de données
- Sécurité renforcée (upload, SQL, sessions)

## Installation locale

1. Cloner le dépôt :
	```sh
	git clone https://github.com/Jonas509/FamillePaulEdourd.git
	```
2. Placer le dossier `MeFamily` dans votre serveur local (WAMP/XAMPP)
3. Importer la base de données (voir le dossier `sql/` ou le script fourni)
4. Configurer l’accès à la base dans `partials/db.php`
5. Installer les dépendances PHP (optionnel) :
	```sh
	cd MeFamily
	composer install
	```
6. Accéder au site via `http://localhost/MeFamily`

## Utilisation de Composer

Le projet utilise Composer pour la gestion des dépendances PHP. Voir `composer.json`.

## Déploiement & CI

Un workflow GitHub Actions valide le projet à chaque push sur `main`.

## Auteurs

- Jonas509 (propriétaire)
- Contributions bienvenues !

---
"La famille, c’est là où la vie commence et où l’amour ne finit jamais."
