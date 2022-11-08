<?php

/**
 * @file
 * This is a basic settings file. It forms the root of any project.
 */

// Default Drupal 9 settings.
//
// These are already explained with detailed comments in Drupal's
// default.settings.php file.
//
// See https://api.drupal.org/api/drupal/sites!default!default.settings.php/9
$databases = [];
$settings['update_free_access'] = FALSE;
$settings['install_profile'] = 'minimal';
$settings['config_sync_directory'] = '../config/sync';
$settings['file_private_path'] = __DIR__ . '/files/private';
$settings["file_temp_path"] = '/tmp';
$settings['http_client_config']['timeout'] = 5;
$config['locale.settings']['translation']['path'] = 'sites/' . basename(__DIR__) . '/files/translations';


$settings['container_yamls'][] = __DIR__ . '/services.yml';
if (file_exists(__DIR__ . '/services.env.yml')) {
  $settings['container_yamls'][] = __DIR__ . '/services.env.yml';
}

/**
 * Include environment specific settings.
 */
if (file_exists(__DIR__ . '/settings.env.php')) {
  include __DIR__ . '/settings.env.php';
}
