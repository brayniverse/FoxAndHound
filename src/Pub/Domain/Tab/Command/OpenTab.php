<?php

declare(strict_types=1);

namespace Webbaard\Pub\Domain\Tab\Command;

use Prooph\Common\Messaging\Command;
use Prooph\Common\Messaging\PayloadConstructable;
use Prooph\Common\Messaging\PayloadTrait;
use Webbaard\Pub\Domain\Tab\ValueObject\CustomerName;

final class OpenTab extends Command implements PayloadConstructable
{
    use PayloadTrait;

    const CUSTOMER_NAME = 'customerName';

    public function customerName(): CustomerName
    {
        return CustomerName::fromString($this->payload[self::CUSTOMER_NAME]);
    }
}
