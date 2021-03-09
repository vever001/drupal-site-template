<?php

declare(strict_types = 1);

namespace Drupal\project\TaskRunner\Commands;

use Consolidation\AnnotatedCommand\CommandData;
use EcEuropa\Toolkit\TaskRunner\Commands\TestsCommands as ToolkitTestsCommands;

/**
 * Class ToolkitOverrideTestCommands.
 */
class ToolkitOverrideTestsCommands extends ToolkitTestsCommands {

  /**
   * Extra tasks to run before toolkit:test-behat.
   *
   * @hook pre-command toolkit:test-behat
   */
  public function preTestBehat(CommandData $commandData) {
    // Insert PHPUnit task.
    // Only execute this if we're on Drone.
    // Since we don't have access to the pipeline... let's hack things I guess.
    $drone_repo = getenv('DRONE_REPO');
    if (!empty($drone_repo)) {
      $result = $this->taskPhpUnit()->run();
      if (!$result->wasSuccessful()) {
        return $result;
      }
    }
  }

}
