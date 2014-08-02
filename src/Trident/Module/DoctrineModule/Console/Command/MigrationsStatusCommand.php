<?php

/*
 * This file is part of Trident.
 *
 * (c) Elliot Wright <elliot@elliotwright.co>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Trident\Module\DoctrineModule\Console\Command;

use Doctrine\DBAL\Connection;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Migration Status Command
 *
 * @author Elliot Wright <elliot@elliotwright.co>
 */
class MigrationsStatusCommand extends Command
{
    protected $conn;

    /**
     * Constructor
     *
     * @param Connection $conn
     */
    public function __construct(Connection $conn)
    {
        parent::__construct();

        $this->conn = $conn;
    }

    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        $this
            ->setName('migrations:status')
            ->setDescription('View current status of migrations.')
        ;
    }

    /**
     * {@inheritDoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Test status');
        var_dump($this->conn);
    }
}
