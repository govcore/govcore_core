<?php

namespace Drupal\Tests\govcore_core\Functional\Update;

use Drupal\FunctionalTests\Update\UpdatePathTestBase;
use Drupal\govcore_core\UpdateManager;

/**
 * @group govcore_core
 */
class Update8006Test extends UpdatePathTestBase {

  /**
   * {@inheritdoc}
   */
  protected function setDatabaseDumpFiles() {
    $this->databaseDumpFiles = [
      $this->getDrupalRoot() . '/core/modules/system/tests/fixtures/update/drupal-8.8.0.bare.standard.php.gz',
      __DIR__ . '/../../../fixtures/Update8006Test.php.gz',
    ];
  }

  public function testUpdate() {
    // Forcibly remove GovCore Dev to prevent test failures. This does not
    // affect the test, because GovCore Dev should never have been included in
    // the fixture anyway.
    $this->config('core.extension')
      ->clear('module.govcore_dev')
      ->save();
    $this->container->get('keyvalue')
      ->get('system.schema')
      ->deleteMultiple(['govcore_dev']);

    $old_name = 'govcore.versions';
    $new_name = UpdateManager::CONFIG_NAME;

    // When doing a lot of updates, it's possible that govcore_core.versions
    // will exist before update 8007 runs, so we need to simulate that case.
    $this->config($new_name)->set('foo', '5.1.0')->save();

    // Ensure that update 8006 works if govcore.versions exists.
    $this->config($old_name)
      ->set('foo', '5.0.0')
      ->set('bar', '1.0.0')
      ->save();

    $this->runUpdates();

    // Assert that govcore.versions is gone, but its data is preserved.
    $this->assertTrue($this->config($old_name)->isNew());
    $new = $this->config($new_name);
    $this->assertSame('5.1.0', $new->get('foo'));
    $this->assertSame('1.0.0', $new->get('bar'));
  }

}
