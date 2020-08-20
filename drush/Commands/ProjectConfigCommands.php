<?php

namespace Drush\Commands;

use Consolidation\AnnotatedCommand\CommandResult;
use Drush\Drush;

/**
 * Class ProjectConfigCommands.
 *
 * @package Drush\Commands
 */
class ProjectConfigCommands extends DrushCommands {

  /**
   * Checks if there are any configuration overrides.
   *
   * @command project:config-status
   */
  public function configStatus() {
    /** @var \Consolidation\SiteProcess\SiteProcess $process */
    $process = $this->processManager()->drush(Drush::aliasManager()->getSelf(), 'config-status');
    $process->mustRun();

    $output = $process->getOutput();
    if (!empty($output)) {
      $this->logger()->error(dt('Found differences between DB and sync directory.'));
      return CommandResult::dataWithExitCode($output, self::EXIT_FAILURE);
    }
    else {
      $this->logger()->success(dt('No differences between DB and sync directory.'));
    }
  }

}
