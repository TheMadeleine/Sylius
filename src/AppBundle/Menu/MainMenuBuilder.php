<?php

namespace AppBundle\Menu;

use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;
use Sylius\Component\Taxonomy\Model\TaxonInterface;
use Sylius\Component\Taxonomy\Repository\TaxonRepositoryInterface;
use Webmozart\Assert\Assert;

/**
 * @author Magdalena Banasiak <magdalena.banasiak@lakion.com>
 */
class MainMenuBuilder
{
    /**
     * @var FactoryInterface
     */
    private $factory;

    /**
     * @var TaxonRepositoryInterface
     */
    private $taxonRepository;

    /**
     * @param FactoryInterface $factory
     * @param TaxonRepositoryInterface $taxonRepository
     */
    public function __construct(FactoryInterface $factory, TaxonRepositoryInterface $taxonRepository)
    {
        $this->factory = $factory;
        $this->taxonRepository = $taxonRepository;
    }

    /**
     * @return ItemInterface
     */
    public function createFullCategoriesMenu()
    {
        $menu = $this->factory->createItem('root');
        $menu->setChildrenAttribute('class', 'nav navbar-nav navbar-left');

        $taxons = $this->getTaxons();
        $this->addAllChildren($menu, $taxons);

        $menu->addChild('Contact', ['route' => 'sylius_contact']);

        return $menu;
    }

    /**
     * @param array $categories
     *
     * @return ItemInterface
     */
    public function createPartialCategoriesMenu($categories = [])
    {
        Assert::notEmpty($categories, 'The "categories" array cannot be empty.');

        $menu = $this->factory->createItem('root');
        $menu->setChildrenAttribute('class', 'nav navbar-nav navbar-left');

        $taxons = $this->getTaxons();
        $this->addGivenChildren($menu, $taxons, $categories);

        return $menu;
    }

    /**
     * @return array
     */
    private function getTaxons()
    {
        return $this->taxonRepository->findRootNodes();
    }

    /**
     * @param ItemInterface $menu
     * @param TaxonInterface[] $taxons
     */
    private function addAllChildren(ItemInterface $menu, array $taxons)
    {
        foreach ($taxons as $taxon) {
            $this->addTaxonAsChild($menu, $taxon);
        }
    }

    /**
     * @param ItemInterface $menu
     * @param TaxonInterface[] $taxons
     * @param array $categories
     */
    private function addGivenChildren(ItemInterface $menu, array $taxons, array $categories)
    {
        foreach ($taxons as $taxon) {
            $taxonCode = $taxon->getCode();
            if (in_array($taxonCode, $categories, true)) {
                $this->addTaxonAsChild($menu, $taxon);
            }
        }
    }

    /**
     * @param ItemInterface $menu
     * @param TaxonInterface $parentTaxon
     */
    private function addTaxonAsChild(ItemInterface $menu, TaxonInterface $parentTaxon)
    {
        $category = $menu->addChild($parentTaxon->getName(), ['route' => 'app_shop_product_index'])
            ->setAttribute('class', 'dropdown yamm-fw')
            ->setAttribute('style', 'display: block;')
            ->setLinkAttribute('class', 'dropdown-toggle')
            ->setLinkAttribute('data-toggle', 'dropdown')
            ->setChildrenAttribute('class', 'dropdown-menu')
            ->addChild('', [])
        ;

        $this->appendCategoryDropdown($category, $parentTaxon);
    }

    /**
     * @param ItemInterface $category
     * @param TaxonInterface $parentTaxon
     */
    private function appendCategoryDropdown(ItemInterface $category, TaxonInterface $parentTaxon)
    {
        $taxons = $parentTaxon->getChildren();

        foreach ($taxons as $taxon) {
            $category->addChild($taxon->getName(), [
                'route' => 'app_shop_product_index',
                'routeParameters' => ['criteria' => [$parentTaxon->getCode() => [$taxon->getId()]]],
            ]);
        }
    }
}
