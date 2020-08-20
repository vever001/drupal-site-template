<?php

declare(strict_types = 1);

namespace Drupal\project\TaskRunner\Commands;

use OpenEuropa\TaskRunner\Commands\AbstractCommands;
use OpenEuropa\TaskRunner\Tasks as TaskRunnerTasks;
use Symfony\Component\Console\Input\InputOption;

/**
 * Class ProjectBuildCommands.
 *
 * @package Drupal\project\TaskRunner\Commands
 */
class ProjectBuildCommands extends AbstractCommands {

  use TaskRunnerTasks\ProcessConfigFile\loadTasks;

  /**
   * DEV build mode.
   *
   * @var string
   */
  const BUILD_MODE_DEV = 'dev';

  /**
   * DIST build mode.
   *
   * @var string
   */
  const BUILD_MODE_DIST = 'dist';

  /**
   * {@inheritdoc}
   */
  public function getConfigurationFile() {
    return __DIR__ . '/../../../config/task-runner/commands/build.yml';
  }

  /**
   * Build settings overrides.
   *
   * @param array $options
   *   Additional options for the command.
   *
   * @return \Robo\Collection\CollectionBuilder
   *   The collection builder.
   *
   * @command project:build-settings-overrides
   */
  public function buildSettingsOverrides(array $options = [
    'root' => InputOption::VALUE_REQUIRED,
    'sites-subdir' => InputOption::VALUE_REQUIRED,
    'mode' => self::BUILD_MODE_DEV,
  ]) {
    $collection = [];
    $source = 'resources/settings';
    $destination = $options['root'] . '/sites/' . $options['sites-subdir'] . '/settings';

    // Remove settings folder.
    $collection[] = $this->taskFilesystemStack()
      ->remove($destination);

    // Add common and specific dev/prod config overrides.
    $settings_files = [
      'settings.common.php',
      'settings.' . $options['mode'] . '.php',
    ];

    // Add specific config overrides for CI/Drone environment.
    $drone_repo = getenv('DRONE_REPO');
    if (!empty($drone_repo)) {
      $settings_files[] = 'settings.ci.php';
    }

    // Process settings files.
    foreach ($settings_files as $settings_file) {
      $collection[] = $this->taskProcessConfigFile($source . '/' . $settings_file, $destination . '/' . $settings_file);
    }

    return $this->collectionBuilder()->addTaskList($collection);
  }

}
