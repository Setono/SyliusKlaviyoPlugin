<?php

declare(strict_types=1);

namespace Setono\SyliusKlaviyoPlugin\Command;

use Setono\SyliusKlaviyoPlugin\Synchronizer\ListSynchronizerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Logger\ConsoleLogger;
use Symfony\Component\Console\Output\OutputInterface;

final class SynchronizeListsCommand extends Command
{
    protected static $defaultName = 'setono:sylius-klaviyo:synchronize:lists';

    protected static $defaultDescription = 'Synchronize your Klaviyo lists with the local database';

    public function __construct(private readonly ListSynchronizerInterface $listSynchronizer)
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->listSynchronizer->setLogger(new ConsoleLogger($output));
        $this->listSynchronizer->synchronize();

        return 0;
    }
}
