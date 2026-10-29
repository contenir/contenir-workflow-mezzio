<?php

declare(strict_types=1);

namespace Contenir\Workflow\Factory;

use Contenir\Workflow\Container\WorkflowConfig;
use Contenir\Workflow\Workflow\WorkflowPluginManager;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;

/**
 * Builds the workflow plugin manager with the application container as its
 * parent locator, and the site's own workflows from "workflow_manager.workflows":
 * plugin manager config (factories, aliases, invokables) added to the package's
 * page workflow, so a site registers its workflows without replacing this factory.
 *
 * @api
 */
final class WorkflowPluginManagerFactory
{
    /**
     * @throws ContainerExceptionInterface
     *
     * @mago-expect analysis:less-specific-nested-argument-type The config is untyped; the plugin manager checks the services it builds.
     */
    public function __invoke(ContainerInterface $container): WorkflowPluginManager
    {
        return new WorkflowPluginManager(
            $container,
            WorkflowConfig::fromContainerOrEmpty($container)->section('workflows'),
        );
    }
}
