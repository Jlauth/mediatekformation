# mediatekformation
# Atelier 1 BTS SIO option SLAM / 2ème année


## Contexte

Travaillant en tant que technicien développeur junior pour l’ESN InfoTech Services 86 et suite à l’obtention du marché pour différentes interventions au sein du réseau MediaTek86, ce travail portera sur le développement de l’application Web d’accès aux formations en ligne.


## Existant

Nous utiliserons en base de travail la partie front office du site qui a été confiée en amont à un autre développeur. Ce dernier nous a transmis un dossier documentaire nous permettant de visualiser sa démarche et construction applicative. De plus, un dépôt Git est mis à disposition ainsi que la base de données déjà configurée.


## Demande

Nettoyer le code puis ajout d’une fonctionnalité dans la page “Playlists” permettant d’afficher et trier le nombre de formations disponibles par playlist(s).
Créer un accès sécurisé permettant la gestion des formations, playlists et catégories disponibles au public.
Mettre en place des tests unitaires, d’intégration, fonctionnels et de compatibilité.
Créer une documentation technique et utilisateur.
Déployer le site en ligne, organiser la sauvegarde et la récupération de la base de données, puis mettre en place le déploiement continu de l’application Web.


## Langages utilisés

PHP et son framework Symfony ainsi que Twig.
MySQL pour le SGBDR.


## Technologies utilisées

L’IDE choisi est Netbeans de Apache. Les plugins SonarLint (analyse du code), Selenium  (tests de compatibilité Web) et phpDocumentor (création de la documentation technique) sont installés dans l’IDE.
WampServer sert de plateforme de développement web en local.

Composer (gestionnaire de dépendance entre application et librairies) est également configuré et utilisé en invite de commande Windows.

La solution de versioning Git est utilisée ainsi que la plateforme Web GitHub afin de sécuriser le code. On utilise également l’outil Projets de cette plateforme afin d’y répertorier les tâches à effectuer dans un Kanban, ainsi que lors du déploiement continu du projet une fois mis en ligne.

Le déploiement en ligne est effectué grâce à la solution gratuite de l’hébergeur PlanetHoster en utilisant la base de données MariaDB fournie par celui-ci afin d’y mettre en ligne notre BDD.
Le projet enregistré en local sera a été transféré sur cette plateforme par l’intermédiaire de FileZilla.

Une sauvegarde journalière est effectuée automatiquement à l’aide d’un script en .sh et configurée sur la plateforme d’administration de l’hébergeur.

Nous utilisons également une machine virtuelle Microsoft Azure afin de générer un certificat SSL via Xampp puis Certbot dans le but d’utiliser le protocole HTTPS ainsi que pour configurer Keycloak, le gestionnaire d’accès à la partie sécurisée du site Web.

