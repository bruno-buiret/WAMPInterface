<?php

namespace App\Twig;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Translation\TranslatorInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * Class AppExtension
 *
 * @package App\Twig
 * @author Bruno Buiret <bruno.buiret@gmail.com>
 */
class AppExtension extends AbstractExtension
{
    /**
     * @var \Symfony\Component\Routing\Generator\UrlGeneratorInterface An URL generator.
     */
    protected $urlGenerator;

    /**
     * @var string The translated accessibility text for the previous page.
     */
    protected $previousText;

    /**
     * @var string The translated accessibility text for the next page.
     */
    protected $nextText;

    /**
     * AppExtension constructor.
     *
     * @param \Symfony\Component\Routing\Generator\UrlGeneratorInterface $urlGenerator An URL generator.
     * @param \Symfony\Component\Translation\TranslatorInterface $translator A translator.
     * @param string $previousText The key for the accessibility text for the previous page.
     * @param string $nextText The key for the accessibility text for the next page.
     * @param string $translationDomain
     */
    public function __construct(
        UrlGeneratorInterface $urlGenerator,
        TranslatorInterface $translator,
        string $previousText = 'pagination.previous',
        string $nextText = 'pagination.next',
        string $translationDomain = 'layout'
    )
    {
        // Initialize properties
        $this->urlGenerator = $urlGenerator;
        $this->previousText = $translator->trans($previousText, [], $translationDomain);
        $this->nextText = $translator->trans($nextText, [], $translationDomain);
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions(): array
    {
        return [
            new TwigFunction('pagination', [$this, 'buildPagination'], ['is_safe' => ['html']]),
        ];
    }

    /**
     * Builds a pagination.
     *
     * @param string $routeName The list's route.
     * @param array $parameters The query parameters to use.
     * @param int $currentPage The current page.
     * @param int $pagesNumber The number of pages.
     * @param string $pageParameter The page parameter's name.
     * @param int $adjacentPagesNumber The number of pages to display around the current one.
     * @return string The pagination.
     */
    public function buildPagination(string $routeName, array $parameters, int $currentPage, int $pagesNumber, string $pageParameter = 'page', int $adjacentPagesNumber = 3)
    {
        // Initialize vars
        $pagination = '';

        // Previous page
        if(1 === $currentPage)
        {
            $pagination .= sprintf(
                '<li class="disabled"><span aria-label="%1$s" aria-hidden="true">&laquo;</span></li>',
                $this->previousText
            );
        }
        else
        {
            $parameters[$pageParameter] = $currentPage - 1;
            $pagination .= sprintf(
                '<li><a href="%1$s" aria-label="%2$s"><span aria-hidden="true">&laquo;</span></a></li>',
                $this->generatePath($routeName, $parameters),
                $this->previousText
            );
        }

        if($pagesNumber < 7 + ($adjacentPagesNumber * 2))
        {
            for($i = 1; $i <= $pagesNumber; $i++)
            {
                $parameters[$pageParameter] = $i;

                if($i === $currentPage)
                {
                    $pagination .= sprintf(
                        '<li class="active"><a href="%1$s">%2$s</a></li>',
                        $this->generatePath($routeName, $parameters),
                        $i
                    );
                }
                else
                {
                    $pagination .= sprintf(
                        '<li><a href="%1$s">%2$s</a></li>',
                        $this->generatePath($routeName, $parameters),
                        $i
                    );
                }
            }
        }
        else
        {
            // At the beginning
            if($currentPage < 2 + ($adjacentPagesNumber * 2))
            {
                for($i = 1, $j = 4 + ($adjacentPagesNumber * 2); $i < $j; $i++)
                {
                    $parameters[$pageParameter] = $i;

                    if($i === $currentPage)
                    {
                        $pagination .= sprintf(
                            '<li class="active"><a href="%1$s">%2$s</a></li>',
                            $this->generatePath($routeName, $parameters),
                            $i
                        );
                    }
                    else
                    {
                        $pagination .= sprintf(
                            '<li><a href="%1$s">%2$s</a></li>',
                            $this->generatePath($routeName, $parameters),
                            $i
                        );
                    }
                }

                // Separator
                $pagination .= '<li><span>&hellip;</span></li>';

                // Penultimate page
                $parameters[$pageParameter] = $pagesNumber - 1;
                $pagination .= sprintf(
                    '<li><a href="%1$s">%2$s</a></li>',
                    $this->generatePath($routeName, $parameters),
                    $parameters[$pageParameter]
                );

                // Last page
                $parameters[$pageParameter] = $pagesNumber;
                $pagination .= sprintf(
                    '<li><a href="%1$s">%2$s</a></li>',
                    $this->generatePath($routeName, $parameters),
                    $parameters[$pageParameter]
                );
            }
            // In the middle
            elseif((($adjacentPagesNumber * 2) + 1 < $currentPage) && ($currentPage < $pagesNumber - ($adjacentPagesNumber * 2)))
            {
                // First page
                $parameters[$pageParameter] = 1;
                $pagination .= sprintf(
                    '<li><a href="%1$s">%2$s</a></li>',
                    $this->generatePath($routeName, $parameters),
                    $parameters[$pageParameter]
                );

                // Second page
                $parameters[$pageParameter] = 2;
                $pagination .= sprintf(
                    '<li><a href="%1$s">%2$s</a></li>',
                    $this->generatePath($routeName, $parameters),
                    $parameters[$pageParameter]
                );

                // Separator
                $pagination .= '<li><span>&hellip;</span></li>';

                for($i = $currentPage - $adjacentPagesNumber, $j = $currentPage + $adjacentPagesNumber; $i <= $j; $i++)
                {
                    $parameters[$pageParameter] = $i;

                    if($i === $currentPage)
                    {
                        $pagination .= sprintf(
                            '<li class="active"><a href="%1$s">%2$s</a></li>',
                            $this->generatePath($routeName, $parameters),
                            $i
                        );
                    }
                    else
                    {
                        $pagination .= sprintf(
                            '<li><a href="%1$s">%2$s</a></li>',
                            $this->generatePath($routeName, $parameters),
                            $i
                        );
                    }
                }

                // Separator
                $pagination .= '<li><span>&hellip;</span></li>';

                // Penultimate page
                $parameters[$pageParameter] = $pagesNumber - 1;
                $pagination .= sprintf(
                    '<li><a href="%1$s">%2$s</a></li>',
                    $this->generatePath($routeName, $parameters),
                    $parameters[$pageParameter]
                );

                // Last page
                $parameters[$pageParameter] = $pagesNumber;
                $pagination .= sprintf(
                    '<li><a href="%1$s">%2$s</a></li>',
                    $this->generatePath($routeName, $parameters),
                    $parameters[$pageParameter]
                );
            }
            // At the end
            else
            {
                // First page
                $parameters[$pageParameter] = 1;
                $pagination .= sprintf(
                    '<li><a href="%1$s">%2$s</a></li>',
                    $this->generatePath($routeName, $parameters),
                    $parameters[$pageParameter]
                );

                // Second page
                $parameters[$pageParameter] = 2;
                $pagination .= sprintf(
                    '<li><a href="%1$s">%2$s</a></li>',
                    $this->generatePath($routeName, $parameters),
                    $parameters[$pageParameter]
                );

                // Separator
                $pagination .= '<li><span>&hellip;</span></li>';

                for($i = $pagesNumber - (2 + (2 * $adjacentPagesNumber)); $i <= $pagesNumber; $i++)
                {
                    $parameters[$pageParameter] = $i;

                    if($i === $currentPage)
                    {
                        $pagination .= sprintf(
                            '<li class="active"><a href="%1$s">%2$s</a></li>',
                            $this->generatePath($routeName, $parameters),
                            $i
                        );
                    }
                    else
                    {
                        $pagination .= sprintf(
                            '<li><a href="%1$s">%2$s</a></li>',
                            $this->generatePath($routeName, $parameters),
                            $i
                        );
                    }
                }
            }
        }

        // Next page
        if($currentPage === $pagesNumber)
        {
            $pagination .= sprintf(
                '<li class="disabled"><span aria-label="%1$s" aria-hidden="true">&raquo;</span></li>',
                $this->nextText
            );
        }
        else
        {
            $parameters[$pageParameter] = $currentPage + 1;
            $pagination .= sprintf(
                '<li><a href="%1$s" aria-label="%2$s"><span aria-hidden="true">&raquo;</span></a></li>',
                $this->generatePath($routeName, $parameters),
                $this->nextText
            );
        }

        return sprintf(
            '<nav class="text-center"><ul class="pagination">%s</ul></nav>',
            $pagination
        );
    }

    /**
     * Utility method to generate a path.
     *
     * @param string $routeName The route's name.
     * @param array $parameters The route's parameters.
     * @return string The newly generated path.
     */
    protected function generatePath(string $routeName, array $parameters = [])
    {
        return $this->urlGenerator->generate($routeName, $parameters, UrlGeneratorInterface::ABSOLUTE_PATH);
    }

}
