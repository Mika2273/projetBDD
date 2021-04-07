# projetBDD

## Table of contents
* [General info](#general-info)

Use phpspreadsheet to read excel data and create a database

* [Technologies](#technologies)
PHP - (7.4 / 8.0)
SQLite - (3.34.0)
HTML - (5.0)
CSS - (3.0)
phpspreadsheet

* [Setup](#setup)

$composer require phpoffice/phpspreadsheet
$sudo apt install php7.4 && sudo apt install php7.4-sqlite3

## start project
# import data
php -S localhost:4000/import.php
# CRUD
php -S localhost:4000/display.php