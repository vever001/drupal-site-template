# Drupal project.

## Prerequisites

If using Docker (recommended):
 - [Docker Compose](https://docs.docker.com/compose/install/)
 - PHP 7.1 or greater (needed to run [GrumPHP](https://github.com/phpro/grumphp) Git hooks)

If using a local LAMP stack:
 - A local LAMP stack with PHP 7.1 or greater
 - [Composer](https://getcomposer.org/doc/00-intro.md#installation-linux-unix-osx).

## Building your local

##### Using Docker

- Run: `composer install`
- Start the containers: `docker-compose up -d`
- Get a shell: `docker-compose exec --user fpfis web bash`
- Build the site: `./vendor/bin/run toolkit:build-dev`

| Service    | Url                                        |
| ---------- | ------------------------------------------ |
| WEB        | http://web.docker.localhost:8080/          |
| -
| phpMyAdmin | http://pma.docker.localhost:8080/          |
| Mailhog    | http://mailhog.docker.localhost:8080/      |
| XHGui      | http://xhgui.docker.localhost:8080/        |
| Portainer  | http://portainer.docker.localhost:8080/    |

- To profile requests using XHGui, uncomment the line in `web/.user.ini`.

##### Using local LAMP stack

- Run: `composer install`
- Build the site: `./vendor/bin/run toolkit:build-dev`
- Create a `.env.local` file with your db credentials and other overrides of `.env` if needed:
    ```
    DRUPAL_DATABASE_USERNAME=root
    DRUPAL_DATABASE_PASSWORD=password
    DRUPAL_DATABASE_PREFIX=
    DRUPAL_DATABASE_HOST=localhost
    DRUPAL_DATABASE_PORT=3306
    ```
- Create a vhost in apache/nginx pointing to the `web` folder

## Installation
##### Clone installation

Set ASDA credentials (provided by fellow colleagues or devops) in `.env.local`:
```
ASDA_USER=...
ASDA_PASSWORD=...
```

Download and install the dump:
```
./vendor/bin/run toolkit:download-dump
./vendor/bin/run install-clone
```

##### Clean installation

You can install the sites from the existing config, but you won't get the content.
```
./vendor/bin/drush toolkit:install-clean
```

## Running the tests

To run the coding standards and other static checks:

```bash
./vendor/bin/grumphp run
```

To run Behat tests:

```bash
./vendor/bin/behat
```

## Continuous integration and deployment

To check the status of the continuous integration of your project, go to [Drone](https://drone.fpfis.eu/).

A pipeline - created and maintained by DevOps - is applied by default.
It manages the code review of the code, it runs all tests on the repository and
builds the site artifact for the deployment.

You can control which commands will be ran during deployment by creating
and pushing a `.opts.yml` file.

If none is found the following one will be ran:

```yml
upgrade_commands:
  - './vendor/bin/drush state:set system.maintenance_mode 1 --input-format=integer -y'
  - './vendor/bin/drush updatedb -y'
  - './vendor/bin/drush cache:rebuild'
  - './vendor/bin/drush state:set system.maintenance_mode 0 --input-format=integer -y'
  - './vendor/bin/drush cache:rebuild'
```

The following conventions apply:

- Every push on the site's deployment branch (usually `master`) will trigger
  a deployment on the acceptance environment
- Every new tag on the site's deployment branch (usually `master`) will
  trigger a deployment on production
