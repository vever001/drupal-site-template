<?php

declare(strict_types = 1);

namespace Drupal\project\TaskRunner\Tasks;

/**
 * Load custom Robo tasks.
 */
trait LoadTasks {

  /**
   * Task Drush.
   *
   * @return \Drupal\project\TaskRunner\Tasks\DrushTask
   *   The Drush task.
   */
  protected function taskProjectDrush() {
    /** @var \Drupal\project\TaskRunner\Tasks\DrushTask $task */
    $task = $this->task(DrushTask::class);
    $task->setInput($this->input());
    /** @var \Symfony\Component\Console\Output\OutputInterface $output */
    $output = $this->output();
    $task->setVerbosityThreshold($output->getVerbosity());

    return $task;
  }

}
