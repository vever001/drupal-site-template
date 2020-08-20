<?php

/**
 * @file
 * Loads the .env files.
 *
 * This file is included very early. See autoload.files in composer.json.
 */

use Dotenv\Dotenv;
use Dotenv\Exception\InvalidPathException;

// Load any .env file.
try {
  $files = ['.env', '.env.local'];
  $dotenv = Dotenv::createImmutable(__DIR__, $files, FALSE);
  $dotenv->safeLoad();
}
catch (InvalidPathException $e) {
  // Do nothing. Production environments rarely use .env files.
}
