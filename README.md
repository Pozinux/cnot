# CnoT

CnoT est une alternative open source à des applications comme Evernote. CnoT ne prétend pas être à la hauteur des fonctionnalités et de la puissance d'Evernote mais a assez de fonctionnalités pour dépanner ceux qui ne souhaitent pas dépendre d'un service en ligne.

CnoT est basée sur php, mysql et javascript.

La démonstration publique se trouve au lien suivant. Utilisez <b>"password"</b> comme mot de passe :

[Lien vers la démonstration publique](https://cnot.fr)

### Voici un example de mon propre CnoT :

![](image.png)

Possibilités de mettre en forme lorsque l'on sélectionne du texte :

![](image2.png)

## Technologies utilisées

Liste des technologies utilisées pour le développement et l'utilisation de CnoT (en gros l'environnement pour lequel CnoT fonctionne) :

* Linux (pour moi c'est un petit VPS Kimsufi OVH) avec un compte super-utilisateur
* CentOS Linux release 8.2.2004
* mysql 8.0.21
* Apache 2.4.37
* PHP 7.2.24
* Google Chrome 87.0.4280.66 (je n'ai pas pu encore valider sur d'autres navigateurs) 

<b>Notes</b> : 

* CnoT n'est pas fait pour être responsive pour le moment. Il est adapté aux écrans d'ordinateurs.
* L'application ne fonctionnera pas sur un Wamp pour le moment car elle utilise des commandes Linux. 
* Je n'ai pas pu tester sur un hébergement mutualisé. 
* Il est nécessaire que ce soit un linux et que vous ayez un compte super-utilisateur pour modifier les permissions si besoin. 

## Installation

* Téléchargez les sources de CnoT de ce repository Github sur votre serveur compatible php-mysql. 
* Créez une base de données mysql.
* Créez un utilisateur mysql qui aura les droits d'utiliser cette base.
* Editez le fichier credentials.php pour définir vos identifiants et les informations de votre base de données.
* Connectez-vous à l'adresse web de l'application et entrez votre mot de passe. Au lancement, l'application devrait créer elle-même la structure nécessaire dans la base de données.

<b>Note</b> : Cette application écrit les notes dans des fichiers, assurez-vous de fournir les autorisations nécessaires pour qu'Apache puisse lire et écrire dans le dossier nommé "entries". 

## Futurs développements

Il manque encore des fonctionnalités et il y a probablement des améliorations du code à faire. Je suis preneur de toutes aides en développement PHP et Javascript (ce ne sont pas mes compétences premières). 

To do list :

* Ajout d'une pièce jointe à une note
* Pouvoir créer un lien dans une note ou vers internet
* Avoir l'auto-complétion des tags lorsque l'on veut en ajouter un
* Pouvoir exporter toutes les notes depuis l'interface (car sinon il suffit de récupérer le répertoire "entries")
* Pouvoir partager une note
* ...

<b>Autres axes de travail :</b>

* Travailler sur la portabilité vers Wamp
* Peut-être passer sous SQLite ?
* ...

Pour info j'ai forké depuis le github suivant mais ce projet n'est plus mis à jour depuis 6 ans et la démo publique n'est plus accessible.

https://github.com/arkanath/SleekArchive 
