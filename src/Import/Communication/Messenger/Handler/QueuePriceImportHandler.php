<?php

namespace Raw\CustomerPrice\Import\Communication\Messenger\Handler;

use Raw\CustomerPrice\Import\Communication\Messenger\Message\QueuePriceImportMessage;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class QueuePriceImportHandler implements MessageHandlerInterface
{
    public function __invoke(QueuePriceImportMessage $importMessage)
    {
        $importData = $importMessage;
    }
}