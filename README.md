# CnoT

CnoT est une alternative open source à des applications comme Evernote. CnoT ne prétend pas être à la hauteur des fonctionnalités et de la puissance d'Evernote mais a assez de fonctionnalités pour dépanner ceux qui ne souhaitent pas dépendre d'un service en ligne.

CnoT est basée sur php, mysql et javascript.

La démonstration publique se trouve [ICI](https://cnot.fr). Utilisez "password" comme mot de passe.

Voici un example de mon propre CnoT :

![](image.png)

Technologies utilisées pour le développement et l'utilisation de CnoT :

* CentOS Linux release 8.2.2004 (Core)
* mysql  Ver 8.0.21 for Linux on x86_64 (Source distribution)
* Server version: Apache/2.4.37 (centos)
* PHP 7.2.24
* Google Chrome version 87.0.4280.66 (Build officiel) (64 bits)

Note : CnoT n'est pas fait pour être responsive. Il est adapté aux écrans d'ordinateurs.

Installation :

1. Téléchargez le dossier cnot contenant les fichiers sources sur votre serveur compatible php-mysql.
2. Editez le fichier credentials.php pour définir vos identifiants et les informations de votre base de données.
3. Au lancement, l'application créera elle-même la structure nécessaire dans la base de donnée.
4. Connectez-vous à l'adresse web de l'application.


Autorisations :

Cette application écrit les notes dans des fichiers, assurez-vous de fournir les autorisations nécessaires pour qu'Apache puisse lire et écrire dans le dossier entries.


Fork depuis :

https://github.com/arkanath/SleekArchive 
mais ce github n'est plus mis à jour depuis 6 ans et la démo publique n'est plus accessible.

