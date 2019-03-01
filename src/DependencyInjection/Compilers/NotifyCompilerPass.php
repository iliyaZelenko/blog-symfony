<?php

namespace App\DependencyInjection\Compilers;

use App\Utils\Contracts\Notify\NotifyChainInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Reference;

class NotifyCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        // приятно, что можно использовать интерфейс
        $class = NotifyChainInterface::class;

        // always first check if the primary service is defined
        if (!$container->has($class)) return;

        $definition = $container->findDefinition($class);
        // find all service IDs with the app.mail_transport tag
        $taggedServices = $container->findTaggedServiceIds('app.notifier');

        foreach ($taggedServices as $id => $tags) {
            // add the transport service to the TransportChain service
            $definition->addMethodCall('addNotifier', [
                new Reference($id)
            ]);
        }
    }
}
