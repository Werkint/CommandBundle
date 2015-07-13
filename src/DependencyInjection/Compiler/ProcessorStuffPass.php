<?php
namespace Werkint\Bundle\CommandBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * ProcessorStuffPass.
 *
 * @author Bogdan Yurov <bogdan@yurov.me>
 */
class ProcessorStuffPass implements
    CompilerPassInterface
{
    const CLASS_SRV = 'werkint_command.stuff';
    const CLASS_TAG = 'werkint_command.stuff';

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
            $a = $attributes[0];
            $definition->addMethodCall(
                'addProvider', [
                    new Reference($id),
                    $a['class'],
                    isset($a['priority']) ? $a['priority'] : 0,
                    isset($a['tick']) ? max((int)$a['tick'], 1) : 1,
                ]
            );
        }
    }

}
