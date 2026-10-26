# Route registration

The strategy's `getRouteConfig()` returns routes keyed by route name. Two
classes register them with `Mezzio\Application`; use one of them.

Both call `Application::route($path, $middleware, $methods, $name)` and then
apply the route's `options`, so the `defaults` (the resource's primary keys
under `id`) reach the matched request as attributes.

## Delegator factory (recommended)

Registers the routes once, when the `Application` service is created:

```php
'dependencies' => [
    'delegators' => [
        Mezzio\Application::class => [
            Contenir\Workflow\Factory\WorkflowApplicationDelegatorFactory::class,
        ],
    ],
],
```

Without a `workflow_manager` config it returns the application unchanged.
With one, `workflow_manager.strategy` is required and must resolve to a
`ResourceStrategyInterface`.

## Middleware

`WorkflowMiddleware` registers the routes on the first request it processes
and then delegates to the next handler. Pipe it before the routing
middleware:

```php
$app->pipe(Contenir\Workflow\Middleware\WorkflowMiddleware::class);
$app->pipe(Mezzio\Router\Middleware\RouteMiddleware::class);
```

Use this when building the routes at container creation is too early, for
example when the database is not available to CLI tools.

## Additional routes

A workflow routes its resource at one address, under the route named by
`getRouteId()`. A workflow that implements `AdditionalRoutesInterface` routes
it at more, each under the name its route config gives, registered after its
own:

```php
final class WorkWorkflow extends AbstractPageWorkflow implements AdditionalRoutesInterface
{
    protected ?string $middleware = WorkHandler::class;

    public function getAdditionalRouteConfigs(): array
    {
        $own = $this->getRouteConfig();

        return null === $own ? [] : [
            [...$own, 'path' => "{$own['path']}/:category", 'name' => "{$own['name']}-category"],
        ];
    }
}
```

Prefer additional routes to optional parts such as `/work[/:category]`. Mezzio
routes each workflow route by request method, and the router cannot build an
address that leaves an optional part out once it does, so `/work` could not be
linked to by its name.
