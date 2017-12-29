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

/**
 * Nav Scrollable Block Type.
 *
 * @author François Pluchino <francois.pluchino@gmail.com>
 */
class NavScrollableType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildView(BlockView $view, BlockInterface $block, array $options)
    {
        if (null !== $view->parent && in_array('navbar', $view->parent->vars['block_prefixes'])) {
            BlockUtil::addAttributeClass($view->parent, 'has-nav-scrollable');
        }
    }

    /**
     * {@inheritdoc}
     */
    public function finishView(BlockView $view, BlockInterface $block, array $options)
    {
        foreach ($view->children as $child) {
            if (in_array('nav', $child->vars['block_prefixes'])) {
                BlockUtil::addAttributeClass($view, 'is-nav-'.$child->vars['style'], true);
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'nav_scrollable';
    }
}
