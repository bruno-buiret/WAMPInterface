<?php

namespace App\Controller;

use App\Entity\Alias;
use App\Entity\VirtualHost;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Cache\Adapter\AdapterInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class CommonController
 *
 * @package App\Controller
 * @author Bruno Buiret <bruno.buiret@gmail.com>
 * @Route(name="common_")
 */
class CommonController extends Controller
{
    /**
     * Displays the main page with information about the current system.
     *
     * @param \Symfony\Component\Cache\Adapter\AdapterInterface $cache
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/", methods={"GET"}, name="dashboard")
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function dashboard(AdapterInterface $cache): Response
    {
        // Fetch WAMP Server configuration from cache
        $configuration = $cache->getItem('wampserver.configuration');

        if(!$configuration->isHit())
        {
            if(is_file($path = $this->getParameter('wampserver.paths.configuration')))
            {
                $configuration
                    ->set(parse_ini_file($path, true))
                    ->expiresAfter(300)
                ;
                $cache->save($configuration);
            }
        }

        $configuration = $configuration->get();

        //
        /** @var \App\Repository\VirtualHostRepository $repository */
        $repository = $this->getDoctrine()->getRepository(VirtualHost::class);
        $virtualHostsNumber = $repository->count([]);
        $repository = $this->getDoctrine()->getRepository(Alias::class);
        $aliasesNumber = $repository->count([]);

        return $this->render(
            'common/dashboard.html.twig',
            [
                'wampServer' => [
                    'version' => $configuration['main']['wampserverVersion'] ?? null,
                    'path'    => $configuration['main']['installDir'] ?? null,
                ],
                'apache'     => [
                    'version'            => $configuration['apache']['apacheVersion'] ?? null,
                    'virtualHostsNumber' => $virtualHostsNumber,
                    'aliasesNumber'      => $aliasesNumber,
                ],
                'php'        => [
                    'version' => $configuration['php']['phpVersion'] ?? null,
                ],
                'mysql'      => [
                    'version' => $configuration['mysql']['mysqlVersion'] ?? null,
                    'port'    => $configuration['mysqloptions']['mysqlPortUsed'] ?? null,
                ],
                'mariaDb'    => [
                    'version' => $configuration['mariadb']['mariadbVersion'] ?? null,
                    'port'    => $configuration['mariadboptions']['mariaPortUsed'] ?? null,
                ],
            ]
        );
    }
}