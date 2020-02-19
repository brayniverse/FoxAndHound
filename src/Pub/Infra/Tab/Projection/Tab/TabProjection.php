<?php
declare(strict_types=1);

namespace Webbaard\Pub\Infra\Tab\Projection\Tab;

use Prooph\Bundle\EventStore\Projection\ReadModelProjection;
use Prooph\EventStore\Projection\ReadModelProjector;
use Webbaard\Pub\Domain\Tab\Event\ItemWasAdded;
use Webbaard\Pub\Domain\Tab\Event\TabWasOpened;
use Webbaard\Pub\Domain\Tab\Event\TabWasPaid;
use Webbaard\Pub\Domain\Tab\ValueObject\MenuItem;

final class TabProjection implements ReadModelProjection
{
    public function project(ReadModelProjector $projector): ReadModelProjector
    {
        $projector->fromStream('event_stream')
            ->init(function (): array {
                return [
                    'items' => [],
                ];
            })
            ->when([
                TabWasOpened::class => function ($state, TabWasOpened $event): void {
                    /** @var TabReadModel $readModel */
                    $readModel = $this->readModel();

                    $readModel->stack('insert', [
                        'id' => $event->tabId()->toString(),
                        'customerName' => $event->customerName()->toString(),
                    ]);
                },
                TabWasPaid::class => function ($state, TabWasPaid $event): void {
                    /** @var TabReadModel $readModel */
                    $readModel = $this->readModel();

                    $readModel->stack('remove', [
                        'id' => $event->tabId()->toString(),
                    ]);
                },
                ItemWasAdded::class => function ($state, ItemWasAdded $event) {
                    /** @var TabReadModel $readModel */
                    $readModel = $this->readModel();

                    $tabId = $event->tabId();

                    if (! array_key_exists($tabId->toString(), $state['items'])) {
                        $state['items'][$tabId->toString()] = [];
                    }

                    $state['items'][$tabId->toString()][] = $event->menuItem();

                    $openAmount = array_sum(
                        array_map(
                            fn(MenuItem $item) => $item->price()->toString(),
                            $state['items'][$tabId->toString()]
                        )
                    );

                    $readModel->stack('update', [
                        'id' => $event->tabId()->toString(),
                        'open_amount' => $openAmount,
                    ]);

                    return $state;
                }
            ]);

        return $projector;
    }
}
