<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Controller\Frontend;

use Sylius\Component\Resource\Repository\RepositoryInterface;
use Sylius\Bundle\WebBundle\Controller\Frontend\HomepageController as BaseHomepageController;
use Symfony\Component\HttpFoundation\Response;

/**
 * @author Magdalena Banasiak <magdalena.banasiak@lakion.com>
 */
class HomepageController extends BaseHomepageController
{
    /**
     * Store front page.
     *
     * @return Response
     */
    public function mainAction()
    {
        /** @var RepositoryInterface $carouselItemRepository */
        $carouselItemRepository = $this->get('app.repository.carousel_item');
        /** @var RepositoryInterface $productGridItemRepository */
        $productGridItemRepository = $this->get('app.repository.product_grid_item');
        /** @var RepositoryInterface $categoryGridItemRepository */
        $categoryGridItemRepository = $this->get('app.repository.category_grid_item');

        $carouselItems = $carouselItemRepository->findBy(['enabled' => true],['position' => 'ASC']);
        $productGridItems = $productGridItemRepository->findBy(['enabled' => true],['position' => 'ASC']);
        $categoryGridItems = $categoryGridItemRepository->findBy(['enabled' => true],['position' => 'ASC']);

        return $this->render(
            'SyliusWebBundle:Frontend/Homepage:main.html.twig',
            [
                'carouselItems' => $carouselItems,
                'productGridItems' => $productGridItems,
                'categoryGridItems' => $categoryGridItems,
            ]
        );
    }
}
