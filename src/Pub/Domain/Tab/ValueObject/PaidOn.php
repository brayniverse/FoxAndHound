<?php

declare(strict_types=1);

namespace Webbaard\Pub\Domain\Tab\ValueObject;

use DateTimeInterface;

final class PaidOn
{
    private DateTimeInterface $date;

    private function __construct(DateTimeInterface $date)
    {
        $this->date = $date;
    }

    public static function fromDateTime(DateTimeInterface $date): PaidOn
    {
        return new self($date);
    }

    public function toString(): string
    {
        return $this->date->format('Y-m-d H:i:s');
    }
}