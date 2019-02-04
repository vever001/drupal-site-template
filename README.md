# OpenEuropa template for Drupal projects

This is based on [drupal-composer/drupal-project](https://github.com/drupal-composer/drupal-project).

[![Build Status](https://drone.fpfis.eu/api/badges/openeuropa/drupal-site-template/status.svg?branch=master)](https://drone.fpfis.eu/openeuropa/drupal-site-template)

This project template provides a starter kit for creating a website using the
OpenEuropa Drupal 8 platform. It will install the [OpenEuropa Profile](https://github.com/openeuropa/oe_profile)
which is a lightweight installation profile that includes the minimal number
of modules that are required to enable the [OpenEuropa Theme](https://github.com/openeuropa/oe_theme).
Using this theme will ensure that the project complies with the guidelines for
[European Component Library](https://github.com/ec-europa/europa-component-library).

In order to build the functionality of the website you are free to use any of the
[OpenEuropa components](https://github.com/openeuropa/openeuropa/blob/master/docs/openeuropa-components.md).

Starting a new project is a 4 step procedure:

## 0. Prerequisites

You need to have the following software installed on your local development environment:

* [Composer](https://getcomposer.org/doc/00-intro.md#installation-linux-unix-osx).
* [Docker Compose](https://docs.docker.com/compose/install/)

## 1. Create the project

The project can be created using the following command:

```
$ composer create-project openeuropa/drupal-site-template --stability=dev my-openeuropa-site
```

This will download the starterkit into the `my-openeuropa-site` folder and a
wizard will ask you for the project name and your organisation. It will use this
information to personalize your project's configuration files.

The installer will then download all dependencies for the project. This process
takes several minutes. At the end you will be asked whether to remove the
existing version history. It is recommended to confirm this question so that you
can start your project with a clean slate.

## 2. Configuration

First let's move into the project's folder:

```
$ cd my-openeuropa-site
```

The project ships with default configuration that is intended to run the
website on the Docker containers we provide. If you are content with using
these, you can skip directly to step 3.

If you want to run the website using your locally installed LAMP stack, you
will want to change the configuration to match your local system.

Customize the default configuration values by copying `runner.yml.dist` to
'runner.yml`:

```
$ cp runner.yml.dist runner.yml
```

Now edit `runner.yml` with your most beloved text editor. You will want to set
the database host to `localhost`, and provide the correct database name and
credentials. Also update the `base_url` and Selenium path to match your local
environment.

## 3. Installation

### 3.1. Using Docker

You can build a development site using [Docker](https://www.docker.com/get-docker) and
[Docker Compose](https://docs.docker.com/compose/) with the provided configuration.

Docker provides the necessary services and tools such as a web server and a database server to get the site running,
regardless of your local host configuration.

#### Requirements:

- [Docker](https://www.docker.com/get-docker)
- [Docker Compose](https://docs.docker.com/compose/)

#### Configuration

By default, Docker Compose reads two files, a `docker-compose.yml` and an optional `docker-compose.override.yml` file.
By convention, the `docker-compose.yml` contains your base configuration and it's provided by default.
The override file, as its name implies, can contain configuration overrides for existing services or entirely new
services.
If a service is defined in both files, Docker Compose merges the configurations.

Find more information on Docker Compose extension mechanism on [the official Docker Compose documentation](https://docs.docker.com/compose/extends/).

#### Usage

To start, run:

```bash
docker-compose up
```

It's advised to not daemonize `docker-compose` so you can turn it off (`CTRL+C`) quickly when you're done working.
However, if you'd like to daemonize it, you have to add the flag `-d`:

```bash
docker-compose up -d
```

Then:

```bash
docker-compose exec web composer install
docker-compose exec web ./vendor/bin/run drupal:site-install
```

Using default configuration, the development site files should be available in the `build` directory and the development site
should be available at: [http://127.0.0.1:8080/build](http://127.0.0.1:8080/build).

#### Running the tests

To run the grumphp checks:

```bash
docker-compose exec web ./vendor/bin/grumphp run
```

To run the phpunit tests:

```bash
docker-compose exec web ./vendor/bin/phpunit
```

To run the behat tests:

```bash
docker-compose exec web ./vendor/bin/behat
```

### 3.2. Using a local LAMP stack

Install the website using the task runner:

```
$ ./vendor/bin/run drupal:site-install
```

The site will be available through your local web server.

To verify whether everything works as expected, you can run the example Behat
test suite:

```
$ ./vendor/bin/behat
```

## 4. Commit and push

The final step is to create a new git repository and commit all the files. A
`.gitignore` file is provided to ensure you only commit your own project files.

```
$ git init
$ git add .
$ git commit -m "Initial commit."
```

Now you are ready to push your project to your chosen code hosting service.

## 5. Patches list

When building sites with [OpenEuropa Multilingual](https://github.com/openeuropa/oe_multilingual) the following core patch may be useful. 

- [Patch](https://www.drupal.org/files/issues/2018-10-01/2599228-SQL_error_on_content_creation-78_0.patch) for issue [#2599228](https://www.drupal.org/project/drupal/issues/2599228) -
Programmatically created translatable content type returns SQL error on content creation.
