<?php

declare(strict_types=1);

namespace Contenir\Workflow\Tests\Unit\Factory;

use Contenir\Workflow\Factory\WorkflowPluginManagerFactory;
use Contenir\Workflow\Tests\TestAsset\Container\InMemoryContainer;
use Contenir\Workflow\Tests\TestAsset\Workflow\DailyPageWorkflow;
use Contenir\Workflow\Workflow\PageWorkflow;
use Contenir\Workflow\Workflow\WorkflowFactory;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[Group('unit')]
final class WorkflowPluginManagerFactoryTest extends TestCase
{
    #[Test]
    public function addsTheWorkflowsTheConfigRegisters(): void
    {
        $container = new InMemoryContainer([
            'config' => [
                'workflow_manager' => [
                    'workflows' => [
                        'factories' => [DailyPageWorkflow::class => WorkflowFactory::class],
                        'aliases'   => ['daily' => DailyPageWorkflow::class],
                    ],
                ],
            ],
        ]);

        $manager = (new WorkflowPluginManagerFactory())($container);

        static::assertSame(
            [DailyPageWorkflow::class, PageWorkflow::class],
            [$manager->get('daily')::class, $manager->get('page')::class],
        );
    }

    #[Test]
    public function buildsAPluginManagerThatProvidesThePageWorkflow(): void
    {
        $manager = (new WorkflowPluginManagerFactory())(new InMemoryContainer());

        static::assertInstanceOf(PageWorkflow::class, $manager->get('PageWorkflow'));
    }

    #[Test]
    public function ignoresWorkflowsThatAreNotASection(): void
    {
        $container = new InMemoryContainer(['config' => ['workflow_manager' => ['workflows' => 'daily']]]);

        static::assertFalse((new WorkflowPluginManagerFactory())($container)->has('daily'));
    }
}
