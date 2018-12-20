<?php

namespace App\Controller;

use DOMDocument;
use DOMXPath;
use ReflectionExtension;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class PhpController
 *
 * @package App\Controller
 * @author Bruno Buiret <bruno.buiret@gmail.com>
 * @Route("/php", name="php_")
 */
class PhpController extends Controller
{
    /**
     * Displays PHP's settings.
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @see \phpinfo()
     * @Route("/settings", methods={"GET"}, name="settings")
     */
    public function settings(): Response
    {
        // Fetch phpinfo()'s contents
        ob_start();
        phpinfo();
        $contents = ob_get_contents();
        ob_end_clean();

        // And parse it
        libxml_use_internal_errors(true);
        $document = new DOMDocument('1.0', 'utf-8');
        $document->loadHTML($contents);
        $xpath = new DOMXPath($document);
        $body = $xpath->query('/html/body/div[@class="center"]')->item(0);

        // To build another document
        $rebuiltDocument = new DOMDocument('1.0', 'utf-8');

        foreach($body->childNodes as $childNode)
        {
            /** @var \DOMNode $childNode */
            switch($childNode->nodeName)
            {
                case 'h1':
                    // Transform <h1> into a <h2>
                    $node = $rebuiltDocument->createElement('h2');
                    $node->nodeValue = $childNode->nodeValue;
                    $rebuiltDocument->appendChild($node);
                    break;

                case 'h2':
                    // Transform <h2> into a <h3>
                    $node = $rebuiltDocument->createElement('h3');
                    $node->nodeValue = $childNode->nodeValue;
                    $rebuiltDocument->appendChild($node);

                    break;

                case 'table':
                    // Import table from document
                    $tableNode = $rebuiltDocument->importNode($childNode, true);
                    $attribute = $rebuiltDocument->createAttribute('class');
                    $attribute->nodeValue = 'table table-striped table-hover';
                    $tableNode->appendChild($attribute);

                    // Wrap table into a box
                    $boxBodyNode = $rebuiltDocument->createElement('div');
                    $attribute = $rebuiltDocument->createAttribute('class');
                    $attribute->nodeValue = 'box-body table-responsive';
                    $boxBodyNode->appendChild($attribute);
                    $boxBodyNode->appendChild($tableNode);

                    $boxNode = $rebuiltDocument->createElement('div');
                    $attribute = $rebuiltDocument->createAttribute('class');
                    $attribute->nodeValue = 'box box-solid';
                    $boxNode->appendChild($attribute);
                    $boxNode->appendChild($boxBodyNode);

                    $rebuiltDocument->appendChild($boxNode);

                    break;
            }
        }

        return $this->render(
            'php/settings.html.twig',
            [
                '_classes' => ['php-configuration'],
                'settings' => $rebuiltDocument->saveHTML(),
            ]
        );
    }

    /**
     * Displays each PHP extension's settings, defined functions, classes and constants.
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \ReflectionException
     * @Route("/extensions", methods={"GET"}, name="extensions")
     */
    public function extensions(): Response
    {
        // Initialize vars
        $extensions = [];
        $extensionNames = get_loaded_extensions();
        $finder = (new Finder())
            ->in(ini_get('extension_dir'))
            ->files()
        ;
        $availableExtensions = [];
        $fullList = [];

        foreach($finder as $file)
        {
            /** @var \SplFileInfo $file */
            preg_match('/(?:php_)?(?P<name>[a-z0-9_]+)\\./i', $file->getFilename(), $matches);
            $availableExtensions[$matches['name']] = true;
            $fullList[$matches['name']] = ['file' => true];
        }

        foreach($extensionNames as $extensionName)
        {
            $extension = new ReflectionExtension($extensionName);
            $extensionData = [
                'slug'      => str_replace([' ', '_'], '-', mb_strtolower($extension->getName())),
                'name'      => $extension->getName(),
                'version'   => $extension->getVersion(),
                'settings'  => $extension->getINIEntries(),
                'functions' => [],
                'classes'   => [],
                'constants' => $extension->getConstants(),
            ];

            foreach($extension->getFunctions() as $function)
            {
                if(false !== ($pos = mb_strpos($function->getName(), '\\')))
                {
                    $functionName = $function->getName();
                    $functionName[$pos] = '.';
                    $extensionData['functions'][$function->getName()] = sprintf(
                        'https://php.net/manual/function.%s.php',
                        str_replace(['\\', '_'], '-', mb_strtolower($functionName))
                    );
                }
                else
                {
                    $extensionData['functions'][$function->getName()] = sprintf(
                        'https://php.net/manual/function.%s.php',
                        str_replace(['_'], '-', mb_strtolower($function->getName()))
                    );
                }
            }

            foreach($extension->getClassNames() as $className)
            {
                $extensionData['classes'][$className] = sprintf(
                    'https://php.net/manual/class.%s.php',
                    str_replace(['\\'], '-', mb_strtolower($className))
                );
            }

            ksort($extensionData['functions']);
            ksort($extensionData['classes']);
            $extensions[$extensionData['name']] = $extensionData;

            if(!isset($fullList[$extensionData['name']]))
            {
                $fullList[$extensionData['name']] = [];
            }

            $fullList[$extensionData['name']]['loaded'] = true;
        }

        uksort($extensions, 'strcasecmp');
        uksort($fullList, 'strcasecmp');

        return $this->render(
            'php/extensions.html.twig',
            [
                '_classes'   => ['php-extensions'],
                'extensions' => $extensions,
                'fullList'   => $fullList,
            ]
        );
    }
}