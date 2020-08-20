<?php

/**
 * @file
 * Contains config overrides for all build modes.
 */

// General Drupal settings.
$settings['trusted_host_patterns'] = explode(',', getenv('DRUPAL_TRUSTED_HOST_PATTERNS'));

// Redis settings.
if (!empty(getenv('DRUPAL_REDIS_HOST'))) {
  $settings['redis.connection']['interface'] = 'PhpRedis';
  $settings['redis.connection']['host'] = getenv('DRUPAL_REDIS_HOST');
  $settings['redis.connection']['port'] = getenv('DRUPAL_REDIS_PORT') ?: '6379';
  $settings['cache']['default'] = 'cache.backend.redis';
  $settings['container_yamls'][] = 'modules/contrib/redis/example.services.yml';
}
