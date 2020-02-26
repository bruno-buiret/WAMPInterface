<?php

namespace App\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension as BaseExtension;

/**
 * Class Extension
 *
 * @package App\DependencyInjection
 * @author Bruno Buiret <bruno.buiret@gmail.com>
 */
class AppExtension extends BaseExtension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = $this->getConfiguration($configs, $container);
        $config = $this->processConfiguration($configuration, $configs);

        $container->setParameter('interface.theme', $config['interface']['theme']);
        $container->setParameter('wampserver.paths.configuration', $config['wampserver']['paths']['configuration']);
        $container->setParameter('wampserver.paths.virtual_hosts', $config['wampserver']['paths']['virtual_hosts']);
        $container->setParameter('wampserver.paths.aliases', $config['wampserver']['paths']['aliases']);
    }
}
