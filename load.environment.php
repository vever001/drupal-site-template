<?php

/**
 * @file
 * Loads the .env file.
 *
 * This file is included very early. See autoload.files in composer.json.
 */

use Dotenv\Dotenv;
use Dotenv\Exception\InvalidPathException;

/**
 * Load any .env file. See /.env.example.
 */
$dotenv = Dotenv::create(__DIR__);

try {
  $dotenv->load();
}
catch (InvalidPathException $e) {
  // Do nothing. Production environments rarely use .env files.
}
