<?php

declare(strict_types=1);

namespace Contenir\Workflow\Strategy;

use Contenir\Workflow\Workflow\AdditionalRoutesInterface;
use Contenir\Workflow\Workflow\WorkflowInterface;

/**
 * The routes a workflow registers for its resource: its own, when it has one, then any
 * additional routes it names, each keyed by its route name.
 *
 * @psalm-import-type RouteConfig from WorkflowInterface
 *
 * @internal
 */
final class WorkflowRoutes
{
    /**
     * @return array<string, RouteConfig>
     */
    public static function of(WorkflowInterface $workflow): array
    {
        $own        = $workflow->getRouteConfig();
        $routes     = null === $own ? [] : [$workflow->getRouteId() => $own];
        $additional = $workflow instanceof AdditionalRoutesInterface ? $workflow->getAdditionalRouteConfigs() : [];
        foreach ($additional as $route) {
            $routes[$route['name']] = $route;
        }

        return $routes;
    }
}
