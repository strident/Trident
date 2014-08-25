<?php

/*
 * This file is part of Trident.
 *
 * (c) Elliot Wright <elliot@elliotwright.co>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Trident\Module\MigrationModule\Console\Command;

use Phinx\Console\Command\Migrate as BaseMigrateCommand;

/**
 * Migrate Migration(s)
 *
 * @author Elliot Wright <elliot@elliotwright.co>
 */
class MigrateCommand extends BaseMigrateCommand
{
    protected function configure()
    {
        parent::configure();

        $this->setName('migrations:migrate');
    }
}
