<?php
declare(strict_types=1);

namespace Webbaard\Pub\Application\Controller;

use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Webbaard\Pub\Infra\Menu\Projection\Menu\MenuItemFinder;

final class MenuCollectionController
{
    private MenuItemFinder $menuItemFinder;

    public function __construct(MenuItemFinder $menuFinder)
    {
        $this->menuItemFinder = $menuFinder;
    }

    public function collectionAction(): Response
    {
        return new JsonResponse([
            [
                'name' => 'Whiskey',
                'price' => '314'
            ],
            [
                'name' => 'Beer',
                'price' => '197'
            ],
            [
                'name' => 'Gin',
                'price' => '212'
            ],
            [
                'name' => 'Bourbon',
                'price' => '420'
            ],
            [
                'name' => 'Wine',
                'price' => '309'
            ],
            [
                'name' => 'Milk',
                'price' => '78'
            ]
        ]);
    }
}
