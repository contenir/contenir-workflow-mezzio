<?php

declare(strict_types=1);

namespace Contenir\Workflow\Tests\TestAsset\Workflow;

use Contenir\Workflow\Workflow\AbstractPageWorkflow;
use Contenir\Workflow\Workflow\AdditionalRoutesInterface;
use Override;

/**
 * A listing routed at its slug, and again under it by category and by category and item, each
 * an additional route named after its own.
 */
final class ListingWorkflow extends AbstractPageWorkflow implements AdditionalRoutesInterface
{
    protected ?string $middleware = 'listing.handler';

    #[Override]
    public function getAdditionalRouteConfigs(): array
    {
        $own = $this->getRouteConfig();
        if (null === $own) {
            return [];
        }

        return [
            [...$own, 'path' => "{$own['path']}/:category", 'name' => "{$own['name']}-category"],
            [...$own, 'path' => "{$own['path']}/:category/:item", 'name' => "{$own['name']}-item"],
        ];
    }
}
