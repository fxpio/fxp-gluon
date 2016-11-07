<?php

/*
 * This file is part of the Sonatra package.
 *
 * (c) François Pluchino <francois.pluchino@sonatra.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sonatra\Component\Gluon\Block\Type;

use Sonatra\Component\Block\AbstractType;
use Sonatra\Component\Block\BlockInterface;
use Sonatra\Component\Block\BlockView;
use Sonatra\Component\Block\Exception\InvalidConfigurationException;
use Sonatra\Component\Block\Util\BlockUtil;
use Sonatra\Component\Bootstrap\Block\Type\ButtonType;
use Sonatra\Component\Bootstrap\Block\Type\PanelHeaderType;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Panel Actions Block Type.
 *
 * @author François Pluchino <francois.pluchino@sonatra.com>
 */
class PanelActionsType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function addChild(BlockInterface $child, BlockInterface $block, array $options)
    {
        if (BlockUtil::isBlockType($child, ButtonType::class)) {
            $child->setOption('size', 'xs');
        }
    }

    /**
     * {@inheritdoc}
     */
    public function addParent(BlockInterface $parent, BlockInterface $block, array $options)
    {
        if (!BlockUtil::isBlockType($parent, array(PanelHeaderType::class, PanelSectionType::class))) {
            $msg = 'The "panel_actions" parent block (name: "%s") must be a "panel_header" or "panel_section" block type';
            throw new InvalidConfigurationException(sprintf($msg, $block->getName()));
        }
    }

    /**
     * {@inheritdoc}
     */
    public function finishView(BlockView $view, BlockInterface $block, array $options)
    {
        foreach ($view->children as $name => $child) {
            if (in_array('button', $child->vars['block_prefixes'])) {
                $class = isset($child->vars['attr']['class']) ? $child->vars['attr']['class'] : '';

                if (false !== strpos($class, 'btn-panel-collapse')) {
                    $view->vars['panel_button_collapse'] = $child;

                    unset($view->children[$name]);
                    break;
                }
            }
        }

        if (!is_scalar($view->vars['value'])) {
            $view->vars['value'] = '';
        }
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'inherit_data' => true,
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'panel_actions';
    }
}
