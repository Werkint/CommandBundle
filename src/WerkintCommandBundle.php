<?php
namespace Werkint\Bundle\CommandBundle;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Werkint\Bundle\CommandBundle\DependencyInjection\Compiler\ProcessorCompilePass;
use Werkint\Bundle\CommandBundle\DependencyInjection\Compiler\ProcessorStuffPass;

/**
 * WerkintCommandBundle.
 */
class WerkintCommandBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new ProcessorCompilePass());
        $container->addCompilerPass(new ProcessorStuffPass());
    }
}
