# Must_Crud_App
## Description :
La réalisation d'une Crud REST API


## Stack technique requis 

- PHP 7.4 min.
- MySQL 5.8 min.
- Symfony 4.4
- Git
- JWT

## Informations de connexion

Utilisateur créé via les fixtures :
- admin@admin.com / admin

## Installation 

- Récupérer le projet en local ```git clone https://github.com/SELLAMI-Soufiane/Must_Crud_App.git```

- Accéder au dossier Backend et exécuter la commande suivante pour installer les dépendances ```composer install```

- Créer de la base de données ```php bin/console doctrine:database:create```

- Appliquer les migrations ```php bin/console doctrine:migrations:migrate```

- Insertion des données dans la base ```php bin/console doctrine:fixtures:load```

- Lancer l'application ```Symfony server:start```

## Authentification

- Afin de s'authentifier, il faut utiliser la route ```api/login_check``` avec la méthode POST et un objet JSON qui contient username(email) et password(mot de passe).
- Cette route générée par la suite un Token qui sera utiliser pour faire appeler aux APIs.

## Documentation

- Pour consulter la documentation des différentes routes de l'api, il faut utiliser le lien suivant : http://127.0.0.1:8000/doc
