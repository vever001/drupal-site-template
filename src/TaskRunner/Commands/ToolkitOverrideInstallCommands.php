<?php

declare(strict_types = 1);

namespace Drupal\project\TaskRunner\Commands;

use Consolidation\AnnotatedCommand\CommandData;
use Drupal\project\TaskRunner\Tasks\LoadTasks;
use EcEuropa\Toolkit\TaskRunner\Commands\InstallCommands as ToolkitInstallCommands;
use Robo\Result;

/**
 * Class ToolkitOverrideInstallCommands.
 */
class ToolkitOverrideInstallCommands extends ToolkitInstallCommands {

  use LoadTasks;

  /**
   * Extra tasks to run after toolkit:install-clean.
   *
   * @hook post-command toolkit:install-clean
   */
  public function postInstallClean(Result $result, CommandData $commandData) {
    if ($result->wasSuccessful()) {
      // Check any config override/artifact & security updates.
      return $this->taskProjectDrush()
        ->drush('pm:security')
        ->drush('project:test-config-status')
        ->verbose(TRUE)
        ->printOutput(TRUE)
        ->stopOnFail(TRUE)
        ->run();
    }
  }

  /**
   * Replaces toolkit:install-clone (no prod yet).
   *
   * @hook replace-command toolkit:install-clone
   */
  public function installClone() {
    $this->say('Command disabled: no production environment yet, use clean install.');
  }

}
