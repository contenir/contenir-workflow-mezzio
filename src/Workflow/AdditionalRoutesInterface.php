<?php

declare(strict_types=1);

namespace Contenir\Workflow\Workflow;

/**
 * A workflow that routes its resource at more addresses than its own route, such as a listing
 * at its slug and again under it filtered by a category.
 *
 * Each additional route is registered beside the resource's own, under the name it gives, so it
 * can be linked to by name. Routes whose parts are all required can always be linked to, where a
 * route with optional parts cannot have them left out once Mezzio routes it by request method.
 *
 * @psalm-import-type RouteConfig from WorkflowInterface
 *
 * @api
 */
interface AdditionalRoutesInterface
{
    /**
     * The resource's routes besides its own, in the order they are registered.
     *
     * @return list<RouteConfig>
     */
    public function getAdditionalRouteConfigs(): array;
}
