<?php

declare(strict_types=1);

namespace Webbaard\Pub\Domain\Tab\CommandHandler;

use Webbaard\Pub\Domain\Tab\Command\OpenTab;
use Webbaard\Pub\Domain\Tab\Repository\TabCollection;
use Webbaard\Pub\Domain\Tab\Tab;

final class OpenTabHandler
{
    private TabCollection $tabCollection;

    public function __construct(TabCollection $tabCollection)
    {
        $this->tabCollection = $tabCollection;
    }

    public function __invoke(OpenTab $tab): void
    {
        $tab = Tab::forCustomer($tab->customerName());

        $this->tabCollection->save($tab);
    }
}
