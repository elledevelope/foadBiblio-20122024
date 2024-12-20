# Last FOAD in 2024 - bibliothèque

## Étapes :

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


-----------
 ## Première partie : Fonctionnalités initiales

### Image upload :

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