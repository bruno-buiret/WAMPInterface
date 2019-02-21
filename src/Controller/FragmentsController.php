<?php

namespace App\Controller;

use App\Entity\Alias;
use App\Entity\VirtualHost;
use App\Menu\Renderer\TreeRenderer;
use DOMDocument;
use DOMNode;
use Knp\Menu\ItemInterface;
use Knp\Menu\MenuFactory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class FragmentsController
 *
 * @package App\Controller
 * @author Bruno Buiret <bruno.buiret@gmail.com>
 * @Route(name="fragments_")
 */
class FragmentsController extends AbstractController
{
    /**
     * Renders the sidebar menu.
     *
     * @param \Knp\Menu\MenuFactory $menuFactory
     * @param \App\Menu\Renderer\TreeRenderer $treeRenderer
     * @param \Symfony\Contracts\Translation\TranslatorInterface $translator
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("fragments/menu", methods={"GET"}, requirements={"host"="127.0.0.1"}, name="menu")
     */
    public function menu(MenuFactory $menuFactory, TreeRenderer $treeRenderer, TranslatorInterface $translator): Response
    {
        $rootNode = $menuFactory->createItem(
            'menu',
            [
                'childrenAttributes' => [
                    'class'       => 'sidebar-menu',
                    'data-widget' => 'tree',
                ],
            ]
        );

        $rootNode->addChild(
            'dashboard',
            [
                'label'  => 'Tableau de bord',
                'uri'    => $this->generateUrl('common_dashboard'),
                'extras' => [
                    'routes'    => ['common_dashboard'],
                    'left-icon' => 'fas fa-home',
                ],
            ]
        );

        $rootNode->addChild(
            'header_server',
            [
                'label'      => $translator->trans('menu.header.server', [], 'layout'),
                'attributes' => [
                    'class' => 'header',
                ],
                'extras'     => [
                    'is_header' => true,
                ],
            ]
        );

        $apacheNode = $rootNode->addChild(
            'apache_root',
            [
                'label'              => 'Apache',
                'attributes'         => [
                    'class' => 'treeview',
                ],
                'childrenAttributes' => [
                    'class' => ['treeview-menu'],
                ],
                'extras'             => [
                    'left-icon'  => 'fas fa-server',
                    'right-icon' => 'fas fa-angle-left',
                ],
            ]
        );
        $apacheNode->addChild(
            'apache_modules',
            [
                'label'  => 'Modules',
                'uri'    => $this->generateUrl('apache_modules'),
                'extras' => [
                    'routes'    => ['apache_modules'],
                    'left-icon' => 'fas fa-angle-right',
                ],
            ]
        );
        $apacheNode->addChild(
            'apache_virtual_hosts',
            [
                'label'  => 'Hôtes virtuels',
                'uri'    => $this->generateUrl('apache_virtual_hosts_list'),
                'extras' => [
                    'routes'    => [
                        'apache_virtual_hosts_list',
                        'apache_virtual_hosts_add',
                        'apache_virtual_hosts_edit',
                    ],
                    'left-icon' => 'fas fa-angle-right',
                ],
            ]
        );
        $apacheNode->addChild(
            'apache_aliases',
            [
                'label'  => 'Aliases',
                'uri'    => $this->generateUrl('apache_aliases_list'),
                'extras' => [
                    'routes'    => [
                        'apache_aliases_list',
                        'apache_aliases_add',
                        'apache_aliases_edit',
                    ],
                    'left-icon' => 'fas fa-angle-right',
                ],
            ]
        );

        $phpNode = $rootNode->addChild(
            'php_root',
            [
                'label'              => 'PHP',
                'attributes'         => [
                    'class' => 'treeview',
                ],
                'childrenAttributes' => [
                    'class' => ['treeview-menu'],
                ],
                'extras'             => [
                    'left-icon'  => 'fas fa-code',
                    'right-icon' => 'fas fa-angle-left',
                ],
            ]
        );
        $phpNode->addChild(
            'php_settings',
            [
                'label'  => 'Configuration',
                'uri'    => $this->generateUrl('php_settings'),
                'extras' => [
                    'routes'    => ['php_settings'],
                    'left-icon' => 'fas fa-angle-right',
                ],
            ]
        );
        $phpNode->addChild(
            'php_extensions',
            [
                'label'  => 'Extensions',
                'uri'    => $this->generateUrl('php_extensions'),
                'extras' => [
                    'routes'    => ['php_extensions'],
                    'left-icon' => 'fas fa-angle-right',
                ],
            ]
        );
        $phpNode->addChild(
            'php_documentation',
            [
                'label'  => 'Documentation',
                'uri'    => 'https://php.net/manual/',
                'extras' => [
                    'left-icon' => 'fas fa-angle-right',
                ],
            ]
        );

        $mysqlNode = $rootNode->addChild(
            'mysql_root',
            [
                'label'              => 'MySQL',
                'attributes'         => [
                    'class' => 'treeview',
                ],
                'childrenAttributes' => [
                    'class' => ['treeview-menu'],
                ],
                'extras'             => [
                    'left-icon'  => 'fas fa-database',
                    'right-icon' => 'fas fa-angle-left',
                ],
            ]
        );
        $mysqlNode->addChild(
            'mysql_documentation',
            [
                'label'  => 'Documentation',
                'uri'    => 'https://dev.mysql.com/doc/',
                'extras' => [
                    'left-icon' => 'fas fa-angle-right',
                ],
            ]
        );

        $mariaDbNode = $rootNode->addChild(
            'mariadb_root',
            [
                'label'              => 'MariaDB',
                'attributes'         => [
                    'class' => 'treeview',
                ],
                'childrenAttributes' => [
                    'class' => ['treeview-menu'],
                ],
                'extras'             => [
                    'left-icon'  => 'fas fa-database',
                    'right-icon' => 'fas fa-angle-left',
                ],
            ]
        );
        $mariaDbNode->addChild(
            'mariadb_documentation',
            [
                'label'  => 'Documentation',
                'uri'    => 'https://mariadb.com/kb/en/library/documentation/',
                'extras' => [
                    'left-icon' => 'fas fa-angle-right',
                ],
            ]
        );

        $rootNode->addChild(
            'header_virtual_hosts',
            [
                'label'      => $translator->trans('menu.header.virtual_hosts', [], 'layout'),
                'attributes' => [
                    'class' => 'header',
                    'id'    => 'header-virtual-hosts',
                ],
                'extras'     => [
                    'is_header' => true,
                ],
            ]
        );

        $this->buildVirtualHosts($rootNode);

        $rootNode->addChild(
            'header_aliases',
            [
                'label'      => $translator->trans('menu.header.aliases', [], 'layout'),
                'attributes' => [
                    'class' => 'header',
                    'id'    => 'header-aliases',
                ],
                'extras'     => [
                    'is_header' => true,
                ],
            ]
        );

        $this->buildAliases($rootNode);

        return new Response($treeRenderer->render($rootNode));
    }

    /**
     * Renders the virtual hosts list in the sidebar.
     *
     * @param \Knp\Menu\MenuFactory $menuFactory
     * @param \App\Menu\Renderer\TreeRenderer $treeRenderer
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/fragments/virtual-hosts", methods={"GET"}, name="virtual_hosts")
     */
    public function virtualHosts(MenuFactory $menuFactory, TreeRenderer $treeRenderer)
    {
        // Initialize vars
        $document = new DOMDocument('1.0', 'utf-8');
        $rootNode = $menuFactory->createItem('menu');

        // Build virtual hosts list
        $this->buildVirtualHosts($rootNode);
        $document->loadHTML(
            '<!DOCTYPE html><html lang="en-US">'.
            '<head><meta charset="utf-8"/><title></title></head>'.
            '<body>'.$treeRenderer->render($rootNode).'</body>'.
            '</html>',
            LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD
        );

        return new Response($this->getInnerHtml(
            $document,
            $document->getElementsByTagName('ul')->item(0)
        ));
    }

    /**
     * Render the aliases list in the sidebar.
     *
     * @param \Knp\Menu\MenuFactory $menuFactory
     * @param \App\Menu\Renderer\TreeRenderer $treeRenderer
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/fragments/aliases", methods={"GET"}, name="aliases")
     */
    public function aliases(MenuFactory $menuFactory, TreeRenderer $treeRenderer)
    {
        // Initialize vars
        $document = new DOMDocument('1.0', 'utf-8');
        $rootNode = $menuFactory->createItem('menu');

        // Build virtual hosts list
        $this->buildAliases($rootNode);
        $document->loadHTML(
            '<!DOCTYPE html><html lang="en-US">'.
            '<head><meta charset="utf-8"/><title></title></head>'.
            '<body>'.$treeRenderer->render($rootNode).'</body>'.
            '</html>',
            LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD
        );

        return new Response($this->getInnerHtml(
            $document,
            $document->getElementsByTagName('ul')->item(0)
        ));
    }

    /**
     * Builds the virtual hosts list.
     *
     * @param \Knp\Menu\ItemInterface $rootNode The menu's root node.
     */
    protected function buildVirtualHosts(ItemInterface $rootNode)
    {
        $repository = $this->getDoctrine()->getRepository(VirtualHost::class);
        $virtualHosts = $repository->findBy(['hidden' => false], ['name' => 'ASC']);

        foreach($virtualHosts as $virtualHost)
        {
            $rootNode->addChild(
                'virtual_host_'.$virtualHost->getId(),
                [
                    'label'  => $virtualHost->getName(),
                    'uri'    => $virtualHost->getUrl(),
                    'extras' => [
                        'left-icon' => 'fas fa-angle-right',
                    ],
                ]
            );
        }
    }

    /**
     * Builds the aliases list.
     *
     * @param \Knp\Menu\ItemInterface $rootNode The menu's root node.
     */
    protected function buildAliases(ItemInterface $rootNode)
    {
        $repository = $this->getDoctrine()->getRepository(Alias::class);
        $aliases = $repository->findBy(['hidden' => false], ['name' => 'ASC']);

        foreach($aliases as $alias)
        {
            $rootNode->addChild(
                'alias_'.$alias->getId(),
                [
                    'label'  => $alias->getName(),
                    'uri'    => $alias->getUrl(),
                    'extras' => [
                        'left-icon' => 'fas fa-angle-right',
                    ],
                ]
            );
        }
    }

    /**
     * Simulates the innerHTML DOM property.
     *
     * @param \DOMDocument $document The document.
     * @param \DOMNode $node The node.
     * @return string The node's inner HTML.
     */
    protected function getInnerHtml(DOMDocument $document, DOMNode $node)
    {
        $innerHtml = '';

        foreach($node->childNodes as $childNode)
        {
            $innerHtml .= $document->saveHTML($childNode);
        }

        return $innerHtml;
    }
}