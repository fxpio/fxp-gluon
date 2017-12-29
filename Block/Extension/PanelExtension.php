<?php

/*
 * This file is part of the Fxp package.
 *
 * (c) François Pluchino <francois.pluchino@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fxp\Component\Gluon\Block\Extension;

use Fxp\Component\Block\AbstractTypeExtension;
use Fxp\Component\Block\BlockInterface;
use Fxp\Component\Block\BlockView;
use Fxp\Component\Block\Util\BlockUtil;
use Fxp\Component\Bootstrap\Block\Type\ButtonType;
use Fxp\Component\Bootstrap\Block\Type\PanelHeaderType;
use Fxp\Component\Bootstrap\Block\Type\PanelType;
use Fxp\Component\Gluon\Block\Type\PanelActionsType;
use Fxp\Component\Gluon\Block\Type\PanelSectionType;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Panel Block Extension.
 *
 * @author François Pluchino <francois.pluchino@gmail.com>
 */
class PanelExtension extends AbstractTypeExtension
{
    /**
     * {@inheritdoc}
     */
    public function addChild(BlockInterface $child, BlockInterface $block, array $options)
    {
        if ($options['collapsible'] && BlockUtil::isBlockType($child, PanelHeaderType::class)) {
            $child->add('_panel_actions', PanelActionsType::class, []);
            $child->get('_panel_actions')->add('_button_collapse', ButtonType::class, [
                'label' => '',
                'attr' => ['class' => 'btn-panel-collapse'],
                'style' => 'default',
                'prepend' => '<span class="caret"></span>',
            ]);
        } elseif (BlockUtil::isBlockType($child, PanelType::class)) {
            if ($block->getOption('recursive_style')) {
                $child->setOption('style', $block->getOption('style'));
            }
        } elseif (BlockUtil::isBlockType($child, PanelSectionType::class)) {
            $cOptions = [];

            if (null !== $block->getOption('cell_label_style') && null === $child->getOption('cell_label_style')) {
                $cOptions['cell_label_style'] = $block->getOption('cell_label_style');
            }

            if (null !== $block->getOption('cell_layout_size') && null === $child->getOption('layout_size')) {
                $cOptions['layout_size'] = $block->getOption('cell_layout_size');
            }

            if (count($cOptions) > 0) {
                $child->setOptions($cOptions);
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(BlockView $view, BlockInterface $block, array $options)
    {
        $view->vars = array_replace($view->vars, [
            'border_top_style' => $options['border_top_style'],
            'collapsible' => $options['collapsible'],
            'collapsed' => $options['collapsed'],
            'panels_rendered' => $options['panels_rendered'],
            'hidden_if_empty' => $options['hidden_if_empty'],
            'recursive_style' => $options['recursive_style'],
            'panel_main' => $options['main'],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function finishView(BlockView $view, BlockInterface $block, array $options)
    {
        $relatedPanels = [];

        foreach ($view->children as $name => $child) {
            if (in_array('panel', $child->vars['block_prefixes'])) {
                $relatedPanels[] = $child;
                unset($view->children[$name]);
            }
        }

        $view->vars['related_panels'] = $relatedPanels;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'border_top_style' => null,
            'cell_label_style' => null,
            'cell_layout_size' => null,
            'collapsible' => false,
            'collapsed' => false,
            'panels_rendered' => true,
            'hidden_if_empty' => true,
            'recursive_style' => false,
            'main' => false,
        ]);

        $resolver->addAllowedTypes('border_top_style', ['null', 'string']);
        $resolver->addAllowedTypes('cell_label_style', ['null', 'string']);
        $resolver->addAllowedTypes('cell_layout_size', ['null', 'string']);
        $resolver->addAllowedTypes('collapsible', 'bool');
        $resolver->addAllowedTypes('collapsed', 'bool');
        $resolver->addAllowedTypes('panels_rendered', 'bool');
        $resolver->addAllowedTypes('hidden_if_empty', 'bool');
        $resolver->addAllowedTypes('recursive_style', 'bool');
        $resolver->addAllowedTypes('main', 'bool');

        $resolver->addAllowedValues('style', [
            null,
            'accent',
            'primary-box',
            'accent-box',
            'success-box',
            'info-box',
            'warning-box',
            'danger-box',
            'default-wire',
            'primary-wire',
            'accent-wire',
            'success-wire',
            'info-wire',
            'warning-wire',
            'danger-wire',
            'default-frame',
            'primary-frame',
            'accent-frame',
            'success-frame',
            'info-frame',
            'warning-frame',
            'danger-frame',
            'default-lite',
            'primary-lite',
            'accent-lite',
            'success-lite',
            'info-lite',
            'warning-lite',
            'danger-lite',
            'default-pref',
            'primary-pref',
            'accent-pref',
            'success-pref',
            'info-pref',
            'warning-pref',
            'danger-pref',
        ]);
        $resolver->addAllowedValues('border_top_style', [
            null,
            'default',
            'primary',
            'accent',
            'success',
            'info',
            'warning',
            'danger',
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getExtendedType()
    {
        return PanelType::class;
    }
}
