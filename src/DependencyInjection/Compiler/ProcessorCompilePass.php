<?php
namespace Werkint\Bundle\CommandBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * ProcessorCompilePass.
 *
 * @author Bogdan Yurov <bogdan@yurov.me>
 */
class ProcessorCompilePass implements
    CompilerPassInterface
{
    const CLASS_SRV = 'werkint_command.compile';
    const CLASS_TAG = 'werkint_command.compile';

    /**
     * {@inheritdoc}
     */
    public function process(
        ContainerBuilder $container
    ) {
        if (!$container->hasDefinition(static::CLASS_SRV)) {
            return;
        }
        $definition = $container->getDefinition(
            static::CLASS_SRV
        );

        $list = $container->findTaggedServiceIds(static::CLASS_TAG);
        foreach ($list as $id => $attributes) {
            $definition->addMethodCall(
                'addProvider', [
                    new Reference($id),
                    $attributes[0]['class'],
                    isset($attributes[0]['priority']) ? $attributes[0]['priority'] : 50,
                ]
            );
        }
    }

}
