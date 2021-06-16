# portfolio-php

Pour installer le projet :

- Cloner le projet sur votre repository
- Installer composer si vous ne l'avez pas
- Faites la commande composer install dans votre terminal
- Créer un fichier .env à la racine du projet pour les différentes variables d'environnement
- Créez une base de donnée MySQL et importez le fichier sql disponible à la racine du projet
APP_ENV= dev (or prod), DB_NAME= databasename, DB_HOST= databasehost, DB_USERNAME= databaseusername, DB_PASSWORD= databasepassword, MAIL_USERNAME= mailadresssmtp, MAIL_PASSWORD= mailpassword
- Démarrez le serveur avec php -S localhost:8000 ou lancez WAMP/XAMP/LAMP et gérez votre configuration apache pour la diriger vers le dossier public/ (là où se situe le fichier index.php qui lance le projet)

-----------------------------------------------------------------------------------------


To install the project:

- Clone the project on your repository
- Install composer if you don't have it
- Do the composer install command in your terminal
- Create an .env file at the root of the project for the different environment variables
- Create a MySQL database and import the sql file available at the root of the project APP_ENV=dev (or prod), DB_NAME=databasename, DB_HOST=databasehost, DB_USERNAME=databaseusername, DB_PASSWORD=databasepassword, MAIL_USERNAME=mailadresssmtp, MAIL_PASSWORD= mailpassword
- Start the server with php -S localhost:8000 or run WAMP/MAMP/LAMP and manage your apache configuration to direct it to the public/folder (where the index.php file is located which launches the project)
