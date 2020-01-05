# TigerORM

## Installation

Installer **composer** et lancer les commandes
- `composer require autoload`
- `composer install`
- `composer dumpautoload`

Mettre le dossier **TigerORM** dans le dossier **src** de votre projet.

## Usage

Utiliser le namespace `use TigerORM\TigerORM;` et mettre `require_once("vendor/autoload.php");` dans le premier fichier du code.

Créer un modèle, par exemple :

```
class Film {

    public $title;
    public $description;

    function __construct() {
    }

}
```

Créer un fichier JSON pour la configuration de l'ORM qui contiendra le nom des tables et leurs propriétés avec leurs types, par exemple :

```
{
    "Film": {
        "title": "String",
        "description": "Text"
    }
}
```

Initialiser l'ORM :

```
$orm = new TigerORM("dbname", "dbuser", "dbpass", "configFileName.json");
```

Sauvegarder un objet :
```
$film = new Film();
$film->title = "Star Wars";
$film->description = "Sci-Fi movie";

$orm = new TigerORM("dbname", "dbuser", "dbpass", "configFileName.json");
$orm->save($film);
```

Les méthodes pour lire des données commencent par "find" : findAll, findOne, findBy, findAllOrdered...
(voir exemples dans le dossier d'exemples)

## Exemples

`/public/init.php` dans le repository de l'ORM.
