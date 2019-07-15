<?php

/**
 * @file
 * Loads the .env file.
 *
 * This file is included very early. See autoload.files in composer.json.
 */

use Dotenv\Dotenv;
use Dotenv\Exception\InvalidPathException;

// Load any .env files.
try {
  $files = ['.env.project', '.env'];
  foreach ($files as $file) {
    $dotenv = Dotenv::create(__DIR__, $file);
    $dotenv->overload();
  }
}
catch (InvalidPathException $e) {
  // Do nothing. Production environments rarely use .env files.
}
