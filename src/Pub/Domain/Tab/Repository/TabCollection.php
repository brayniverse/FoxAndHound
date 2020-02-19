<?php

declare(strict_types=1);

namespace Webbaard\Pub\Domain\Tab\Repository;

use Webbaard\Pub\Domain\Tab\Tab;
use Webbaard\Pub\Domain\Tab\ValueObject\TabId;

interface TabCollection
{
    public function get(TabId $tabId): Tab;

    public function save(Tab $tab): void;
}