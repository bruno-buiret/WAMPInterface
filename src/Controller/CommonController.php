<?php

namespace App\Controller;

use App\Entity\Alias;
use App\Entity\Shortcut;
use App\Entity\VirtualHost;
use App\Form\ShortcutType;
use App\Repository\ShortcutRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Cache\Adapter\AdapterInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class CommonController
 *
 * @package App\Controller
 * @author Bruno Buiret <bruno.buiret@gmail.com>
 * @Route(name="common_")
 */
class CommonController extends AbstractController
{
    /**
     * Displays the main page with information about the current system.
     *
     * @param \Symfony\Component\Cache\Adapter\AdapterInterface $cache
     * @param \App\Repository\ShortcutRepository $shortcutsRepository
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Psr\Cache\InvalidArgumentException
     * @Route("/", methods={"GET"}, name="dashboard")
     */
    public function dashboard(AdapterInterface $cache, ShortcutRepository $shortcutsRepository): Response
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
                    'version'           => $configuration['php']['phpVersion'] ?? null,
                    'configurationPath' => php_ini_loaded_file(),
                ],
                'mysql'      => [
                    'version' => $configuration['mysql']['mysqlVersion'] ?? null,
                    'port'    => $configuration['mysqloptions']['mysqlPortUsed'] ?? null,
                ],
                'mariaDb'    => [
                    'version' => $configuration['mariadb']['mariadbVersion'] ?? null,
                    'port'    => $configuration['mariadboptions']['mariaPortUsed'] ?? null,
                ],
                'shortcuts'  => $shortcutsRepository->getBySortableGroups(),
            ]
        );
    }

    // ---

    /**
     * Displays a list of shortcuts.
     *
     * @param \App\Repository\ShortcutRepository $shortcutsRepository
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/shortcuts", methods={"GET"}, name="shortcuts_list")
     */
    public function shortcutsList(ShortcutRepository $shortcutsRepository): Response
    {
        return $this->render(
            'common/shortcuts/list.html.twig',
            [
                'shortcuts' => $shortcutsRepository->getBySortableGroups(),
            ]
        );
    }

    /**
     * Displays and handles a form to add a new shortcut.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Doctrine\ORM\EntityManagerInterface $entityManager
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/shortcuts/add", methods={"GET", "POST"}, name="shortcuts_add")
     */
    public function shortcutsAdd(Request $request, EntityManagerInterface $entityManager, TranslatorInterface $translator): Response
    {
        // Initialize form
        $shortcut = new Shortcut();
        $form = $this->createForm(
            ShortcutType::class,
            $shortcut,
            [
                'method'             => 'post',
                'action'             => $this->generateUrl('common_shortcuts_add'),
                'translation_domain' => 'common',
            ]
        );
        $form->handleRequest($request);

        if(!$form->isSubmitted() || !$form->isValid())
        {
            return $this->render(
                'common/shortcuts/form.html.twig',
                [
                    'form'   => $form->createView(),
                    'title'  => $translator->trans('shortcuts.add.title', [], 'common'),
                    'action' => $translator->trans('shortcuts.add.button_add', [], 'common'),
                    'reset'  => $translator->trans('shortcuts.add.button_reset', [], 'common'),
                ]
            );
        }

        $entityManager->persist($shortcut);
        $entityManager->flush();

        $this->addFlash('success', 'Le nouveau raccourci "'.$shortcut->getTitle().'" a été créé.');

        return $this->redirectToRoute('common_shortcuts_list');
    }

    /**
     * Displays and handles a form to edit a shortcut.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param int $shortcutId The shortcut's id.
     * @param \Doctrine\ORM\EntityManagerInterface $entityManager
     * @param \App\Repository\ShortcutRepository $shortcutsRepository
     * @param \Symfony\Contracts\Translation\TranslatorInterface $translator
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/shortcuts/{shortcutId<\d+>}/edit", methods={"GET", "POST"}, name="shortcuts_edit")
     */
    public function shortcutsEdit(Request $request, int $shortcutId, EntityManagerInterface $entityManager, ShortcutRepository $shortcutsRepository, TranslatorInterface $translator): Response
    {
        // Find shortcut
        $shortcut = $shortcutsRepository->find($shortcutId);

        if(null === $shortcut)
        {
            throw $this->createNotFoundException('No shortcut found for id #'.$shortcutId.'.');
        }

        // Initialize form
        $form = $this->createForm(
            ShortcutType::class,
            $shortcut,
            [
                'method'             => 'post',
                'action'             => $this->generateUrl('common_shortcuts_edit', ['shortcutId' => $shortcutId]),
                'translation_domain' => 'common',
            ]
        );
        $form->handleRequest($request);

        if(!$form->isSubmitted() || !$form->isValid())
        {
            return $this->render(
                'common/shortcuts/form.html.twig',
                [
                    'form'   => $form->createView(),
                    'title'  => $translator->trans('shortcuts.edit.title', [], 'common'),
                    'action' => $translator->trans('shortcuts.edit.button_add', [], 'common'),
                    'reset'  => $translator->trans('shortcuts.edit.button_reset', [], 'common'),
                ]
            );
        }

        $entityManager->persist($shortcut);
        $entityManager->flush();

        $this->addFlash('success', 'Le raccourci "'.$shortcut->getTitle().'" a été édité.');

        return $this->redirectToRoute('common_shortcuts_list');
    }

    /**
     * Deletes an existing shortcut.
     *
     * @param int $shortcutId The shortcut's id.
     * @param \Doctrine\ORM\EntityManagerInterface $entityManager
     * @param \App\Repository\ShortcutRepository $shortcutsRepository
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/shortcuts/{shortcutId<\d+>}/delete", methods={"GET"}, name="shortcuts_delete")
     */
    public function shortcutsDelete(int $shortcutId, EntityManagerInterface $entityManager, ShortcutRepository $shortcutsRepository): Response
    {
        // Find shortcut
        $shortcut = $shortcutsRepository->find($shortcutId);

        if(null === $shortcut)
        {
            throw $this->createNotFoundException('No shortcut found for id #'.$shortcutId.'.');
        }

        $entityManager->remove($shortcut);
        $entityManager->flush();

        $this->addFlash('success', 'Le raccourci "'.$shortcut->getTitle().'" a été supprimé.');

        return $this->redirectToRoute('common_shortcuts_list');
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/shortcuts/sort", methods={"POST"}, name="shortcuts_sort")
     */
    public function shortcutsSort(Request $request, EntityManagerInterface $entityManager, ShortcutRepository $shortcutsRepository): Response
    {
        $positions = $request->request->get('positions', []);
        $positions = is_array($positions) ? $positions : [];
        $shortcuts = $shortcutsRepository->findAll();

        foreach($shortcuts as $shortcut)
        {
            if(!isset($positions[$shortcut->getId()]))
            {
                continue;
            }

            $shortcut->setPosition($positions[$shortcut->getId()]);
            $entityManager->persist($shortcut);
        }

        $entityManager->flush();

        return new Response('', Response::HTTP_ACCEPTED);
    }
}