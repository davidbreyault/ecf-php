# Portail de compétence
L'objectif du projet est de créer un outil de gestion de compétences, permettant de centraliser les informations des collaborateurs et de leurs compétences. Il est souhaité la conception et le développement d'une solution moderne et innovante afin de gérer au mieux le suivi des candidats et des collaborateurs d'une entreprise, et ainsi faciliter le travail des ressources humaines et de l'équipe commerciale. 

Pour se faire, j'ai imaginé une entreprise fictive (IS-Corp) avec la création de plusieurs personnages ayant chacun des droits différents. Dès lors que vous aurez importé le fichier 'import.sql' dans votre SGBD, vous aurez la possibilité de vous connecter sous chaque profil, pour découvrir l'application et les droits d'accès réservés selon tel ou tel rôle. 

## Installation du projet

#### Récupération du projet
`git clone https://github.com/davidbreyault/portail-de-competences.git`

#### Installation des dépendances
`composer install`

Copiez-collez le fichier `.env`, renommez la copie en `.env.local`

Conservez seulement la ligne 30

`DATABASE_URL="mysql://db_username:db_password@127.0.0.1:3306/db_name?serverVersion=5.7"`

Renseignez vos identifiants à phpMyAdmin, nommez la future base de données.

`DATABASE_URL="mysql://`__db_username__`:`__db_password__`@127.0.0.1:3306/`__db_name__`?serverVersion=5.7"`

#### Création de la base de données
`symfony console doctrine:database:create`

#### Migration de la base de données vers votre phpMyAdmin
`symfony console doctrine:migrations:migrate`

Insertion du jeu de données dans la nouvelle base grâce au fichier __import.sql__

#### Gestion des documents au sein de l'application
`composer require symfony/filesystem`

#### Installation de Symfony Encore

```
composer require symfony/webpack-encore-bundle
yarn install
```

#### Compilation des assets
`yarn encore dev`

#### Découvrez le projet :smiley:
`symfony serve`

## Personnas

Vous pouvez vous connecter en tant que chaque utilisateur ci-dessous en utilisant leurs identifiants. Bien entendu, vous pouvez également créer votre propre compte et vous attribuer tous les droits en modifiants les rôles dans la base de données. Pour ce faire, utilisez directement le formulaire d'inscription sur l'application (Symfony s'occupera de hasher votre mot de passe), puis complétez le reste de vos données directement dans votre SGBD > table 'User'. Renseignez `["ROLE_ADMIN"]` dans le champs `roles`. Dans le champs, `is_employed`, renseignez la valeur `1` pour indiquer que vous faites parti de l'effectif de l'entreprise.

Exemple : `('paul-auchon@is-corp.fr', '["ROLE_ADMIN"]', '$2y$13$F71iD067gdiK5cIArQc.qOOpe8bi1jZpH7as1jucHMK9HlsQNGkHa', 'Paul', 'AUCHON', 'M', '1985-08-23', '31 rue des Hirondelles', '37000', 'TOURS', '0600232111', '2021-08-11', 1, '2021-08-11 17:31:13', '2021-08-11 17:31:13'),`

> Administrateur :
> 
> email : `paul-auchon@is-corp.fr` 
> password :	 `helloworld`

> Commercial :
> 
> email : `marc-hassin@is-corp.fr` 
> password :	 `symfony`

> Collaboratrice :
> 
> email : `annie-mahle@is-corp.fr` 
> password :	 `ilovedogs`

> Candidate :
> 
> email : `laureloge@jaimel.fr` 
> password :	 `timesup`

Dernièrement, un utilisateur qui répond au nom de Pacôme Laizautre s'est inscrit sur l'application et a candidaté pour rejoindre l'équipe IS-Corp. Il a uploadé son CV sur la plateforme. Vous devriez pouvoir le consulter si vous êtes connecté en tant qu'administrateur ou commercial.