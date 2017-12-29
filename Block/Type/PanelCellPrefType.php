<?php

/*
 * This file is part of the Fxp package.
 *
 * (c) François Pluchino <francois.pluchino@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fxp\Component\Gluon\Block\Type;

use Fxp\Component\Block\AbstractType;
use Fxp\Component\Block\BlockInterface;
use Fxp\Component\Block\BlockView;
use Fxp\Component\Block\Util\BlockUtil;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @author François Pluchino <francois.pluchino@gmail.com>
 */
class PanelCellPrefType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildView(BlockView $view, BlockInterface $block, array $options)
    {
        if (null !== $options['src'] && !$options['disabled']) {
            BlockUtil::addAttribute($view, 'href', $options['src'], 'control_attr');
        }

        if ($options['disabled'] && isset($view->vars['control_attr']['tabindex'])) {
            unset($view->vars['control_attr']['tabindex']);
        }

        $view->vars = array_replace($view->vars, [
            'disabled' => $options['disabled'],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'src' => null,
            'disabled' => false,
        ]);

        $resolver->setAllowedTypes('src', ['null', 'string']);
        $resolver->setAllowedTypes('disabled', 'bool');
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return PanelCellType::class;
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'panel_cell_pref';
    }
}
