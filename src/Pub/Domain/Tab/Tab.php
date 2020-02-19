<?php

declare(strict_types=1);

namespace Webbaard\Pub\Domain\Tab;

use Prooph\EventSourcing\AggregateChanged;
use Prooph\EventSourcing\AggregateRoot;
use Webbaard\Pub\Domain\Tab\Event\ItemWasAdded;
use Webbaard\Pub\Domain\Tab\Event\TabWasOpened;
use Webbaard\Pub\Domain\Tab\Event\TabWasPaid;
use Webbaard\Pub\Domain\Tab\ValueObject\CustomerName;
use Webbaard\Pub\Domain\Tab\ValueObject\MenuItem;
use Webbaard\Pub\Domain\Tab\ValueObject\OpenedOn;
use Webbaard\Pub\Domain\Tab\ValueObject\PaidOn;
use Webbaard\Pub\Domain\Tab\ValueObject\TabId;

final class Tab extends AggregateRoot
{
    private TabId $tabId;

    private OpenedOn $openedOn;

    private CustomerName $customerName;

    private ?PaidOn $paidOn = null;

    private int $openAmount = 0;

    /** @var MenuItem[] */
    private array $menuItems = [];

    public static function forCustomer(CustomerName $customerName): self
    {
        $self = new self();

        $self->customerName = $customerName;
        $self->tabId = TabId::new();

        $self->recordThat(TabWasOpened::forCustomer($self->tabId, $customerName));

        return $self;
    }

    public function pay(): void
    {
        $this->recordThat(TabWasPaid::forTab($this->tabId));
    }

    public function add(ValueObject\MenuItem $menuItem)
    {
        $this->recordThat(ItemWasAdded::forTab($this->tabId, $menuItem));
    }

    protected function aggregateId(): string
    {
        return $this->tabId->toString();
    }

    public function hasBeenPaid(): bool
    {
        return $this->paidOn instanceof PaidOn;
    }

    public function amountOutstanding(): int
    {
        return array_sum(array_map(fn(MenuItem $item) => $item->price()->asMoney()->getAmount(), $this->menuItems));
    }

    public function whenTabWasOpened(TabWasOpened $event): void
    {
        $this->tabId = $event->tabId();
        $this->customerName = $event->customerName();
        $this->openedOn = $event->openedOn();
    }

    public function whenTabWasPaid(TabWasPaid $event): void
    {
        $this->paidOn = $event->paidOn();
    }

    public function whenItemWasAdded(ItemWasAdded $event): void
    {
        $this->menuItems[] = $event->menuItem();
    }

    /** @inheritDoc */
    protected function apply(AggregateChanged $event): void
    {
        switch (true) {
            case $event instanceof TabWasOpened:
                $this->whenTabWasOpened($event);
                break;

            case $event instanceof TabWasPaid:
                $this->whenTabWasPaid($event);
                break;

            case $event instanceof ItemWasAdded:
                $this->whenItemWasAdded($event);
                break;
        }
    }

    public function payload(): array
    {
        return [
            'id' => $this->tabId->toString(),
            'customerName' => $this->customerName->toString(),
            'opened_on' => $this->openedOn->toString(),
            'paid_on' => $this->hasBeenPaid() ? $this->paidOn->toString() : null,
            'amount_outstanding' => $this->amountOutstanding(),
        ];
    }
}
