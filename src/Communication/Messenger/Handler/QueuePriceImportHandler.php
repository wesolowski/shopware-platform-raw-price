<?php

namespace Raw\CustomerPrice\Import\Communication\Messenger\Handler;

use Raw\CustomerPrice\Custom\CustomPriceEntity;
use Raw\CustomerPrice\Import\Communication\Messenger\Message\QueuePriceImportMessage;
use Raw\CustomerPrice\Import\Communication\Service\Redis;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\EntitySearchResult;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class QueuePriceImportHandler implements MessageHandlerInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var Redis
     */
    private $redis;

    /**
     * QueuePriceImportHandler constructor.
     *
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container, Redis $redis)
    {
        $this->container = $container;
        $this->redis = $redis;
    }

    public function __invoke(QueuePriceImportMessage $importMessage)
    {
    }
}