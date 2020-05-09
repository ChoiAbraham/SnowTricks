# SnowTricks

Développez de A à Z le site communautaire SnowTricks
==========
*Project 6 OpenClassRooms*

Go to the website of the project
https://snowtricks.club

[![Codacy Badge](https://api.codacy.com/project/badge/Grade/ca80d580d8ee4e8b9ce3659c91894bc8)](https://www.codacy.com/manual/choi.abri/SnowTricks?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=ChoiAbraham/SnowTricks&amp;utm_campaign=Badge_Grade)

A Symfony Project to learn about the framework. It includes : Forms, handlers, DTOs, Validator Custom Constraints, Twig Extension and so on. 
It features Snowboard Tricks. Users who sign up can post trick articles and comments.

* Symfony: version 4.4
* CSS : Bootstrap 4

## Getting Started

These instructions will get you a copy of the project up and running on your local machine for development.

### Prerequisites

* **Php 7.3**
* **Mysql 5.7**
* Composer (https://getcomposer.org/).
* yarn (https://classic.yarnpkg.com/fr/)

## Tested with:
- PHPUnit [more infos](https://phpunit.de/)

### Installing

- clone or download the repository into your environment. https://github.com/ChoiAbraham/SnowTricks

Install the dependencies on Composer
```
$ composer install
```
Open and enter your parameters database and mailer in .env file (see instructions on files)

Set the Database

```
$ php bin/console doctrine:database:create
```
```
$ php bin/console doctrine:migrations:migrate
```
```
$ php bin/console doctrine:fixtures:load --group=snowtricks
```
Run Encore:
```
$ yarn watch
```

## Built With

* [Bootstrap](https://getbootstrap.com/)
* [Twig](https://twig.symfony.com/) - Dependency Management
* [Twig](https://twig.symfony.com/) - Dependency Management
