<?php

namespace Drush\Commands;

use Consolidation\AnnotatedCommand\CommandData;
use Consolidation\AnnotatedCommand\CommandResult;
use Consolidation\SiteAlias\SiteAliasManagerAwareTrait;
use Drush\SiteAlias\SiteAliasManagerAwareInterface;

/**
 * Sitewide Drush commands for this project.
 *
 * @package Drush\Commands
 */
class ProjectCommands extends DrushCommands implements SiteAliasManagerAwareInterface {

  use SiteAliasManagerAwareTrait;

  /**
   * Post installation, checks any configuration overrides and return exit code.
   *
   * @hook post-command site-install
   */
  public function postSiteInstall($result, CommandData $commandData) {
    if (!$commandData->input()->getOption('existing-config')) {
      return $result;
    }

    // After installing from existing config, check if there are any config
    // differences. If so, these "config artifacts" are most likely the result
    // of config being re-saved and should be committed.
    // Those artifacts can pop up for various reasons, at a later stage (often
    // after a full site install from existing config) while working on
    // something else, which is annoying.
    // This hook will prevent that if the site gets installed during CI.
    $selfRecord = $this->siteAliasManager()->getSelf();

    /** @var \Consolidation\SiteProcess\SiteProcess $process */
    $process = $this->processManager()->drush($selfRecord, 'config-status');
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
