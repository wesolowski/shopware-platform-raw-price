<?php declare(strict_types=1);

namespace Raw\CustomerPrice\Import\Communication\Command;

use Raw\CustomerPrice\Import\Communication\Messenger\Message\QueuePriceImportMessage;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class PriceImport extends Command
{
    /**
     * @var MessageBusInterface
     */
    private $messageBus;

    /**
     * @param MessageBusInterface $messageBus
     */
    public function __construct(MessageBusInterface $messageBus)
    {
        $this->messageBus = $messageBus;
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setName('raw:customer-price:import');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $importData = [['qu' => 1, 'price' => 12.20]];

        $this->messageBus->dispatch(new QueuePriceImportMessage($importData));

        $output->writeln('customer price processes...');
    }
}