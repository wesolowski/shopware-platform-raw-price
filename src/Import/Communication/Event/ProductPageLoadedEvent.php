<?php declare(strict_types=1);


namespace Raw\CustomerPrice\Import\Communication\Event;

use Raw\CustomerPrice\Custom\CustomPriceEntity;
use Raw\CustomerPrice\Import\Communication\Service\Redis;
use Shopware\Core\Checkout\Cart\Price\Struct\CalculatedPrice;
use Shopware\Core\Checkout\Cart\Tax\Struct\CalculatedTaxCollection;
use Shopware\Core\Checkout\Cart\Tax\Struct\TaxRuleCollection;
use Shopware\Core\Checkout\Customer\CustomerEntity;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepositoryInterface;
use Shopware\Core\Framework\DataAbstractionLayer\Search\EntitySearchResult;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;
use Shopware\Core\Framework\Pricing\Price;
use Shopware\Core\System\SalesChannel\SalesChannelContext;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ProductPageLoadedEvent implements EventSubscriberInterface
{
    /**
     * @var Redis
     */
    private $redis;

    /**
     * @param Redis $redis
     */
    public function __construct(Redis $redis)
    {
        $this->redis = $redis;
    }

    public static function getSubscribedEvents()
    {
        return [
            \Shopware\Storefront\Page\Product\ProductPageLoadedEvent::NAME => 'checkPrice'
        ];
    }

    public function checkPrice(\Shopware\Storefront\Page\Product\ProductPageLoadedEvent $productPageLoadedEvent)
    {
        $productPage = $productPageLoadedEvent->getPage()->getProduct();
        /** @var SalesChannelContext $context */
        $context = $productPageLoadedEvent->getSalesChannelContext();
        if ($context instanceof SalesChannelContext && $context->getCustomer() instanceof CustomerEntity) {

            $customerNumber = $context->getCustomer()->getCustomerNumber();
            $productNumber = $productPage->getProductNumber();

            $priceInfo = json_decode($this->redis->get($customerNumber, $productNumber), true);

            if (!empty($priceInfo)) {
                $price = (float)$priceInfo['price'];
                $calculatedPrice = new CalculatedPrice(
                    $price,
                    $price,
                    new CalculatedTaxCollection(),
                    new TaxRuleCollection(),
                    $priceInfo['qu']
                );
                $productPage->setCalculatedListingPrice($calculatedPrice);
                $productPage->setCalculatedPrice($calculatedPrice);
                $productPage->setPrice(
                    new Price($price, $price, false)
                );
            }
        }
    }

}