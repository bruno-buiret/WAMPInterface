<?php

namespace App\Menu\Renderer;

use Knp\Menu\ItemInterface;
use Knp\Menu\Matcher\MatcherInterface;
use Knp\Menu\Renderer\ListRenderer;

/**
 * Class TreeRenderer
 *
 * @package App\Menu\Renderer
 * @author Bruno Buiret <bruno.buiret@gmail.com>
 */
class TreeRenderer extends ListRenderer
{
    /**
     * TreeRenderer constructor.
     *
     * @param \Knp\Menu\Matcher\MatcherInterface $matcher
     * @param array $defaultOptions
     * @param string $charset
     */
    public function __construct(MatcherInterface $matcher, array $defaultOptions = [], ?string $charset = null)
    {
        parent::__construct(
            $matcher,
            array_merge(
                [
                    'currentClass'  => 'active',
                    'ancestorClass' => 'active',
                    'branch_class'  => 'branch-class',
                    'leaf_class'    => 'leaf-class',
                    // 'compressed'    => true,
                ],
                $defaultOptions
            ),
            $charset
        );
    }

    /**
     * {@inheritDoc}
     */
    protected function renderLabel(ItemInterface $item, array $options): string
    {
        $label = '';

        if(!empty($leftIcon = $item->getExtra('left-icon')))
        {
            $label .= sprintf(
                '<i class="%s"></i> ',
                $leftIcon
            );
        }

        $label .= '<span>';

        if($options['allow_safe_labels'] && $item->getExtra('safe_label', false))
        {
            $label .= $item->getLabel();
        }
        else
        {
            $label .= $this->escape($item->getLabel());
        }

        $label .= '</span>';

        if(!empty($badge = $item->getExtra('badge')))
        {
            $classes = ['label', 'pull-right'];

            if(!empty($badgeClasses = $item->getExtra('badge-classes')))
            {
                $classes = array_merge(
                    $classes,
                    is_array($badgeClasses) ? $badgeClasses : (array) $badgeClasses
                );
            }

            $classes = array_unique($classes);
            $label .= sprintf(
                '<span class="pull-right-container"><span class="%s">%s</span></span>',
                implode(' ', $classes),
                $badge
            );
        }
        elseif(!empty($rightIcon = $item->getExtra('right-icon')))
        {
            $label .= sprintf(
                '<span class="pull-right-container"><i class="%s pull-right"></i></span>',
                $rightIcon
            );
        }

        return $label;
    }

    /**
     * {@inheritDoc}
     */
    protected function renderSpanElement(ItemInterface $item, array $options): string
    {
        if(!empty($item->getExtra('is_header')))
        {
            // Header item
            return parent::renderSpanElement($item, $options);
        }
        else
        {
            // Multi level entry
            return sprintf(
                '<a href="#"%s>%s</a>',
                $this->renderHtmlAttributes($item->getLabelAttributes()),
                $this->renderLabel($item, $options)
            );
        }
    }
}
