<?php

namespace Drupal\govcore_core\Command;

use Drupal\Console\Core\Command\Command;
use Drupal\Console\Core\Style\DrupalStyle;
use Drupal\govcore_core\UpdateManager;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Defines a console command to run optional configuration updates.
 */
class UpdateCommand extends Command {

  /**
   * The update manager service.
   *
   * @var \Drupal\govcore_core\UpdateManager
   */
  protected $updateManager;

  /**
   * UpdateCommand constructor.
   *
   * @param \Drupal\govcore_core\UpdateManager $update_manager
   *   The update manager service.
   */
  public function __construct(UpdateManager $update_manager) {
    parent::__construct('update:govcore');
    $this->updateManager = $update_manager;
  }

  /**
   * {@inheritdoc}
   */
  protected function execute(InputInterface $input, OutputInterface $output) {
    $io = new DrupalStyle($input, $output);
    $this->updateManager->executeAllInConsole($io);
  }

}
