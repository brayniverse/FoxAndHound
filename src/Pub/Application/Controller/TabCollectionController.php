<?php
declare(strict_types=1);

namespace Webbaard\Pub\Application\Controller;

use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Webbaard\Pub\Infra\Tab\Projection\Tab\TabFinder;

final class TabCollectionController
{
    private TabFinder $tabFinder;

    public function __construct(TabFinder $tabFinder)
    {
        $this->tabFinder = $tabFinder;
    }

    public function collectionAction(): Response
    {
        return new JsonResponse($this->tabFinder->findAll());
    }
}
