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

    private $message = [];

    private $i = 0;

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        for ($artNum = 1; $artNum <= 1000; $artNum++) {
            for ($customerNumber = 1; $customerNumber <= 1000; $customerNumber++) {
                $price = rand(1, 9900) / 100;
                $this->message[] = ['qu' => 1, 'price' => $price, 'artnum' => $artNum, 'customernumber' => $customerNumber];
                $this->sendMessage();
            }
        }

        if(!empty($this->message)) {
            $this->messageBus->dispatch(new QueuePriceImportMessage($this->message));
        }


        $output->writeln('customer price processes...');
    }

    private function sendMessage()
    {
        $this->i++;
        if ($this->i > 1000) {
            $this->messageBus->dispatch(new QueuePriceImportMessage($this->message));
            $this->i = 0;
            $this->message = [];
        }
    }
}