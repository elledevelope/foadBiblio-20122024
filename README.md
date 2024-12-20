# Last FOAD in 2024 - bibliothèque

<img src="/public/demo/screenshot.png" alt="screenshot" width="550px" height="400px"  target="_blank"> 

## Table of contents

- [Etapes](#etapes)
- [Image Upload](#image-upload)
- [Book Search](#book-search)
- [Category Filter](#category-filter)
- [Author](#author)

  
## Etapes

1. Copy repository `foadBiblio` from https://github.com/souhilaAtr/foadBiblio


2. Create database `library`:
`php bin/console doctrine:database:create`
`php bin/console doctrine:schema:update --force`


3. Populate database with fake data using Faker :

- install faker and fixtures :
``composer require --dev orm-fixtures fakerphp/faker``
``composer require --dev doctrine/doctrine-fixtures-bundle``

- create ``Category``, ``Livre`` and ``User`` fixtures in `src/DataFixtures`, load fake data to db :
``php bin/console doctrine:fixtures:load --append``


## Image Upload

1. Modify ``php.ini``: 

- in `Dynamic Extetions`  activate ``extension=gd`` and ``extension=openssl ``(by removing `;` in front)
and modify size:
``upload_max_filesize=128M``
``post_max_size=128M``

2. install Gumlet bundle :
 `composer require gumlet/php-image-resize`

3. add path to save upload images in `services.yaml` :
```
parameters:
    upload_directory: '%kernel.project_dir%/public/uploads'
```

4. add `cover` property in ``Livre.php` entity :
`php bin/console make:entity`

5. add `cover` field in ``src/Forms/LivreType.php`` 

6. add image upload logic in ``src/Controller/LivreController.php`` in `/new` Route and in `/edit` Route
   

## Book Search

1. Create ``SearchController.php``

2. Add ``public function searchBy()`` in ``LivreRepository.php``
 
3. Add Route ``/searchlivre`` in ``LivreController.php``

4. Create form for search and result display in `templates/search/index.html.twig`

5. Create ``search.js`` for search url query and result display on btn click 'Rechercher'
   

## Category Filter

1. Add ``public function findByCategory()`` in ``LivreRepository.php``

2. Add filter by category in LivreController.php in ``/livres`` Route

3. Create category filter form in `templates/home/index.html.twig`


## Author

*Elmira*
