<?php

declare(strict_types=1);

namespace Webbaard\Pub\Command;

use Prooph\EventStore\EventStore;
use Prooph\EventStore\Stream;
use Prooph\EventStore\StreamName;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class CreateEventStreamCommand extends Command
{
    private EventStore $eventStore;

    public function __construct(EventStore $eventStore)
    {
        $this->eventStore = $eventStore;
        parent::__construct();
    }

    protected function configure()
    {
        $this->setName('event-store:event-stream:create')
            ->setDescription('Create event_stream.')
            ->setHelp('This command creates the event_stream');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->eventStore->create(new Stream(new StreamName('event_stream'), new \ArrayIterator([])));

        $output->writeln('<info>Event stream was created successfully.</info>');
        return 1;
    }
}
