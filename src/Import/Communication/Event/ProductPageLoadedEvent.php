<?php declare(strict_types=1);


namespace Raw\CustomerPrice\Import\Communication\Event;

use Raw\CustomerPrice\Custom\CustomPriceEntity;
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
     * @var EntityRepositoryInterface
     */
    private $customerPriceRepo;

    /**
     * @param $customerPriceRepo
     */
    public function __construct(EntityRepositoryInterface $customerPriceRepo)
    {
        $this->customerPriceRepo = $customerPriceRepo;
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

            $entities = $this->customerPriceRepo->search(
                (new Criteria())->addFilter(new EqualsFilter('key', $customerNumber . ':' . $productNumber)),
                $context->getContext()
            );

            if ($entities->getTotal() === 1) {
                $elements = $entities->getElements();
                /** @var CustomPriceEntity $customer */
                $customer = array_shift($elements);
                $price = (float)$customer->getValue()[0]['price'];
                $calculatedPrice = new CalculatedPrice(
                    $price,
                    $price,
                    new CalculatedTaxCollection(),
                    new TaxRuleCollection(),
                    $customer->getValue()[0]['qu']
                );

                $productPage->setCalculatedListingPrice($calculatedPrice);
                $productPage->setCalculatedPrice($calculatedPrice);
                $productPage->setPrice(
                    new Price($price, $price, false)
                );
            }

        }
        dump(func_get_args(), $productPageLoadedEvent);
    }

}