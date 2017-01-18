<?php

/*
 * This file is part of the Sonatra package.
 *
 * (c) François Pluchino <francois.pluchino@sonatra.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sonatra\Component\Gluon\Block\Extension;

use Sonatra\Component\Block\AbstractTypeExtension;
use Sonatra\Component\Block\BlockInterface;
use Sonatra\Component\Block\BlockView;
use Sonatra\Component\Block\Util\BlockUtil;
use Sonatra\Component\Bootstrap\Block\Type\PanelHeaderType;
use Sonatra\Component\Gluon\Block\Type\PanelActionsType;

/**
 * Panel Header Block Extension.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
class PanelHeaderExtension extends AbstractTypeExtension
{
    /**
     * {@inheritdoc}
     */
    public function addChild(BlockInterface $child, BlockInterface $block, array $options)
    {
        if (BlockUtil::isBlockType($child, PanelActionsType::class)) {
            if ($block->getAttribute('already_actions')) {
                $actions = $block->get($block->getAttribute('already_actions'));

                foreach ($actions->all() as $action) {
                    $child->add($action);
                }

                $block->remove($block->getAttribute('already_actions'));
            } else {
                $block->setAttribute('already_actions', $child->getName());
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function finishView(BlockView $view, BlockInterface $block, array $options)
    {
        foreach ($view->children as $name => $child) {
            if (in_array('panel_actions', $child->vars['block_prefixes'])) {
                if (count($child->children) > 0 || isset($child->vars['panel_button_collapse'])) {
                    $view->vars['panel_actions'] = $child;
                }

                unset($view->children[$name]);
                break;
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getExtendedType()
    {
        return PanelHeaderType::class;
    }
}
